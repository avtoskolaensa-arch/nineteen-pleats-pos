<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/order-numbers.php';
require __DIR__ . '/includes/receipt-templates.php';

require_login();
session_write_close();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function close_print_fail(string $message, int $status = 400): void {
    http_response_code($status);
    echo json_encode(['ok' => false, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    close_print_fail('არასწორი მოთხოვნა.', 405);
}

$day = active_day();
if (!$day) {
    close_print_fail('სამუშაო დღე დახურულია.');
}

ensure_order_discount_columns();
$tableId = (int)($_POST['table_id'] ?? 0);
$table = fetch_table($tableId);
if (!$table) {
    close_print_fail('მაგიდა ვერ მოიძებნა.');
}

$order = current_open_order((int)$day['id'], $tableId);
if (!$order) {
    close_print_fail('ამ მაგიდაზე ღია შეკვეთა არ არის.');
}

$orderId = (int)$order['id'];
if (unsent_items_count($orderId) > 0) {
    close_print_fail('ამ მაგიდაზე არის გაუგზავნელი პროდუქცია — ჯერ გაგზავნე შეკვეთა.');
}

$subtotal = order_total($orderId);
if ($subtotal <= 0) {
    close_print_fail('ამ მაგიდას ჯამი 0.00 ₾ აქვს — გამოიყენე „ნულით დახურვა“.');
}

$discountType = 'none';
$discountValue = 0.0;
$discountAmount = 0.0;
if (($_POST['discount_enabled'] ?? '') === '1') {
    $requestedType = $_POST['discount_type'] ?? 'percent';
    $requestedValue = max(0, (float)($_POST['discount_value'] ?? 0));
    if ($requestedType === 'percent') {
        $discountType = 'percent';
        $discountValue = min(100, $requestedValue);
        $discountAmount = round($subtotal * $discountValue / 100, 2);
    } elseif ($requestedType === 'amount') {
        $discountType = 'amount';
        $discountValue = min($subtotal, $requestedValue);
        $discountAmount = round($discountValue, 2);
    }
}
$discountAmount = min($subtotal, max(0, $discountAmount));
$total = round(max(0, $subtotal - $discountAmount), 2);

$paymentType = $_POST['payment_type'] ?? 'cash';
if (!in_array($paymentType, ['cash', 'card', 'mixed'], true)) {
    $paymentType = 'cash';
}
if ($paymentType === 'cash') {
    $cash = $total;
    $card = 0.0;
} elseif ($paymentType === 'card') {
    $cash = 0.0;
    $card = $total;
} else {
    $cash = max(0, (float)($_POST['cash_amount'] ?? 0));
    $card = max(0, (float)($_POST['card_amount'] ?? 0));
    if (abs(($cash + $card) - $total) > 0.01) {
        close_print_fail('შერეულ გადახდაში ნაღდი + ბარათი უნდა უდრიდეს საბოლოო ჯამს.');
    }
}

$pdo = db();
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE orders SET status='closed', subtotal_total=?, total=?, discount_type=?, discount_value=?, discount_amount=?, payment_type=?, cash_amount=?, card_amount=?, closed_at=NOW() WHERE id=? AND status='open'");
    $stmt->execute([$subtotal, $total, $discountType, $discountValue, $discountAmount, $paymentType, $cash, $card, $orderId]);
    if ($stmt->rowCount() !== 1) {
        throw new RuntimeException('Order was already closed.');
    }
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    close_print_fail('მაგიდის დახურვა ვერ მოხერხდა.', 500);
}

$closedOrder = fetch_order($orderId);
if (!$closedOrder) {
    close_print_fail('დახურული ანგარიში ვერ მოიძებნა.', 500);
}

$items = order_items($orderId);
$receiptNumber = receipt_number_for_order($closedOrder);
$template = receipt_template('final');

$result = [
    'ok' => true,
    'message' => 'მაგიდა დაიხურა და საბოლოო ქვითარი მზადაა დასაბეჭდად.',
    'order_id' => $orderId,
    'receipt_number' => $receiptNumber,
    'redirect' => '/tables',
    'final' => [
        'text' => build_configurable_final_receipt($table, $closedOrder, $items, $receiptNumber),
        'font_size' => (int)$template['font_size'],
    ],
];

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
