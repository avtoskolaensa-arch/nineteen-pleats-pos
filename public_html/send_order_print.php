<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/order-numbers.php';
require __DIR__ . '/includes/receipt-templates.php';

require_login();
session_write_close();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function json_fail(string $message, int $status = 400): void {
    http_response_code($status);
    echo json_encode(['ok'=>false,'message'=>$message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_fail('არასწორი მოთხოვნა.', 405);

$day = active_day();
if (!$day) json_fail('სამუშაო დღე დახურულია.');

$tableId = (int)($_POST['table_id'] ?? 0);
$table = fetch_table($tableId);
if (!$table) json_fail('მაგიდა ვერ მოიძებნა.');

$pdo = db();
try {
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare(
        "SELECT * FROM orders WHERE business_day_id=? AND table_id=? AND status='open' ORDER BY id DESC LIMIT 1 FOR UPDATE"
    );
    $orderStmt->execute([(int)$day['id'], $tableId]);
    $order = $orderStmt->fetch();
    if (!$order) {
        $pdo->rollBack();
        json_fail('ამ მაგიდაზე შეკვეთა აღარ არის.', 409);
    }

    $stmt = $pdo->prepare(
        'SELECT * FROM order_items WHERE order_id=? AND is_cancelled=0 AND sent_at IS NULL ORDER BY id ASC FOR UPDATE'
    );
    $stmt->execute([(int)$order['id']]);
    $items = $stmt->fetchAll();
    if (!$items) {
        $pdo->rollBack();
        json_fail('ახალი გასაგზავნი პროდუქტი არ არის.', 409);
    }

    $ids = array_map(static function ($item) { return (int)$item['id']; }, $items);
    $sql = 'UPDATE order_items SET sent_at=NOW() WHERE id IN ('
        . implode(',', array_fill(0, count($ids), '?'))
        . ') AND sent_at IS NULL AND is_cancelled=0';
    $update = $pdo->prepare($sql);
    $update->execute($ids);

    if ($update->rowCount() !== count($ids)) {
        throw new RuntimeException('Some order items changed during sending.');
    }

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    json_fail('შეკვეთის გაგზავნა ვერ მოხერხდა. განაახლე გვერდი და სცადე თავიდან.', 500);
}

$receiptNumber = receipt_number_for_order($order);
$barTemplate = receipt_template('bar');
$kitchenTemplate = receipt_template('kitchen');

$result = [
    'ok' => true,
    'message' => 'შეკვეთა გაიგზავნა და მზადაა დასაბეჭდად.',
    'table_id' => $tableId,
    'order_id' => (int)$order['id'],
    'receipt_number' => $receiptNumber,
    'bar' => [
        'text' => build_configurable_bar_receipt($table, $items, $receiptNumber),
        'font_size' => (int)$barTemplate['font_size'],
    ],
    'kitchen' => [
        'text' => build_configurable_kitchen_receipt($table, $items, $receiptNumber),
        'font_size' => (int)$kitchenTemplate['font_size'],
    ],
];

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
