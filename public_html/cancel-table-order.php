<?php
require __DIR__ . '/includes/bootstrap.php';

require_login();
session_write_close();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function cancel_table_json(array $payload, int $status = 200): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function ensure_order_cancellation_schema(): void {
    // Installed by database/schema.sql. Runtime requests must never execute DDL.
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    cancel_table_json(['ok' => false, 'message' => 'არასწორი მოთხოვნა.'], 405);
}

$day = active_day();
if (!$day) {
    cancel_table_json(['ok' => false, 'message' => 'სამუშაო დღე დახურულია.'], 409);
}

$tableId = (int)($_POST['table_id'] ?? 0);
$table = fetch_table($tableId);
if (!$table) {
    cancel_table_json(['ok' => false, 'message' => 'მაგიდა ვერ მოიძებნა.'], 404);
}

$preset = trim((string)($_POST['cancel_reason'] ?? ''));
$custom = trim((string)($_POST['cancel_reason_custom'] ?? ''));
$allowedReasons = [
    'კლიენტმა გადაიფიქრა',
    'სტუმარი წავიდა',
    'შეცდომით გაიხსნა',
    'შეკვეთა დუბლირებულია',
    'სხვა',
];
if (!in_array($preset, $allowedReasons, true)) {
    $preset = 'სხვა';
}

if ($preset === 'სხვა') {
    $reason = $custom;
} elseif ($custom !== '') {
    $reason = $preset . ' — ' . $custom;
} else {
    $reason = $preset;
}

if ($reason === '') {
    cancel_table_json(['ok' => false, 'message' => 'მიუთითე გაუქმების მიზეზი.'], 422);
}
if (function_exists('mb_substr')) {
    $reason = mb_substr($reason, 0, 250, 'UTF-8');
} else {
    $reason = substr($reason, 0, 250);
}

try {
    ensure_order_cancellation_schema();
    ensure_order_discount_columns();

    $pdo = db();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "SELECT * FROM orders WHERE business_day_id=? AND table_id=? AND status='open' ORDER BY id DESC LIMIT 1 FOR UPDATE"
    );
    $stmt->execute([(int)$day['id'], $tableId]);
    $order = $stmt->fetch();

    if (!$order) {
        $pdo->rollBack();
        cancel_table_json(['ok' => false, 'message' => 'ამ ადგილზე ღია შეკვეთა აღარ არის.'], 409);
    }

    $sumStmt = $pdo->prepare(
        'SELECT COALESCE(SUM(quantity * price),0) FROM order_items WHERE order_id=? AND is_cancelled=0'
    );
    $sumStmt->execute([(int)$order['id']]);
    $cancelledTotal = round((float)$sumStmt->fetchColumn(), 2);
    $userId = (int)(current_user()['id'] ?? 0);

    $itemStmt = $pdo->prepare(
        'UPDATE order_items SET is_cancelled=1, cancelled_by=?, cancelled_at=NOW(), cancel_reason=? '
        . 'WHERE order_id=? AND is_cancelled=0'
    );
    $itemStmt->execute([$userId ?: null, $reason, (int)$order['id']]);

    $orderStmt = $pdo->prepare(
        "UPDATE orders SET status='cancelled', subtotal_total=?, cancelled_total=?, total=0, "
        . "discount_type='none', discount_value=0, discount_amount=0, payment_type=NULL, "
        . "cash_amount=0, card_amount=0, cancel_reason=?, cancelled_by=?, cancelled_at=NOW(), closed_at=NOW() "
        . "WHERE id=? AND status='open'"
    );
    $orderStmt->execute([
        $cancelledTotal,
        $cancelledTotal,
        $reason,
        $userId ?: null,
        (int)$order['id'],
    ]);

    if ($orderStmt->rowCount() !== 1) {
        throw new RuntimeException('Order cancellation was not applied.');
    }

    $pdo->commit();

    cancel_table_json([
        'ok' => true,
        'message' => 'შეკვეთა გაუქმდა და გაყიდვაში არ ჩაითვლება.',
        'table_id' => $tableId,
        'table_name' => (string)$table['name'],
        'order_id' => (int)$order['id'],
        'cancelled_total' => $cancelledTotal,
        'redirect' => '/tables',
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    cancel_table_json(['ok' => false, 'message' => 'მაგიდის გაუქმება ვერ მოხერხდა. სცადე თავიდან.'], 500);
}
