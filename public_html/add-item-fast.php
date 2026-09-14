<?php
require __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function fast_add_fail(string $message, int $status = 400): void {
    http_response_code($status);
    echo json_encode(['ok' => false, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fast_add_fail('არასწორი მოთხოვნა.', 405);
require_login();

// Do not hold the PHP session lock while MySQL is working.
if (session_status() === PHP_SESSION_ACTIVE) session_write_close();

$day = active_day();
if (!$day) fast_add_fail('სამუშაო დღე დახურულია.', 409);

$tableId = (int)($_POST['table_id'] ?? 0);
$productId = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, min(999, (int)($_POST['quantity'] ?? 1)));
$comment = trim((string)($_POST['comment'] ?? ''));
if (function_exists('mb_substr')) $comment = mb_substr($comment, 0, 250, 'UTF-8');
else $comment = substr($comment, 0, 250);

$table = fetch_table($tableId);
if (!$table) fast_add_fail('მაგიდა ვერ მოიძებნა.', 404);

$stmt = db()->prepare('SELECT id, name, price, cost FROM products WHERE id=? AND is_active=1 LIMIT 1');
$stmt->execute([$productId]);
$product = $stmt->fetch();
if (!$product) fast_add_fail('პროდუქტი ვერ მოიძებნა ან გათიშულია.', 404);

try {
    $pdo = db();
    $order = current_open_order((int)$day['id'], $tableId);
    $orderId = $order ? (int)$order['id'] : create_order((int)$day['id'], $tableId);
    if (!$order) $order = fetch_order($orderId);

    $insert = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, quantity, price, product_cost, comment) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $insert->execute([
        $orderId,
        $productId,
        $product['name'],
        $quantity,
        $product['price'],
        $product['cost'] ?? 0,
        $comment,
    ]);
    $itemId = (int)$pdo->lastInsertId();

    $sum = $pdo->prepare('SELECT COALESCE(SUM(quantity * price),0) FROM order_items WHERE order_id=? AND is_cancelled=0');
    $sum->execute([$orderId]);
    $total = (float)$sum->fetchColumn();

    echo json_encode([
        'ok' => true,
        'item' => [
            'id' => $itemId,
            'name' => (string)$product['name'],
            'quantity' => $quantity,
            'price' => (float)$product['price'],
            'comment' => $comment,
        ],
        'order' => [
            'id' => $orderId,
            'receipt_number' => (int)($order['receipt_number'] ?? 0),
            'total' => $total,
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    error_log('GARBALIA fast add item: ' . $e->getMessage());
    fast_add_fail('პროდუქტის დამატება ვერ მოხერხდა. სცადე თავიდან.', 500);
}
