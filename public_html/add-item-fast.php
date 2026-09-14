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

$dayId = (int)$day['id'];
$tableId = (int)($_POST['table_id'] ?? 0);
$productId = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, min(999, (int)($_POST['quantity'] ?? 1)));
$comment = trim((string)($_POST['comment'] ?? ''));
if (function_exists('mb_substr')) $comment = mb_substr($comment, 0, 250, 'UTF-8');
else $comment = substr($comment, 0, 250);

try {
    $pdo = db();

    // Hot path: validate table + product and resolve the current open order in
    // one roundtrip instead of three separate SELECTs.
    $lookup = $pdo->prepare(
        "SELECT
            t.id AS table_id,
            p.id AS product_id,
            p.name AS product_name,
            p.price AS product_price,
            p.cost AS product_cost,
            o.id AS order_id,
            o.receipt_number
         FROM restaurant_tables t
         JOIN products p ON p.id=? AND p.is_active=1
         LEFT JOIN orders o ON o.id=(
             SELECT o2.id
             FROM orders o2
             WHERE o2.business_day_id=?
               AND o2.table_id=t.id
               AND o2.status='open'
             ORDER BY o2.id DESC
             LIMIT 1
         )
         WHERE t.id=? AND t.is_active=1
         LIMIT 1"
    );
    $lookup->execute([$productId, $dayId, $tableId]);
    $row = $lookup->fetch();

    if (!$row) {
        // Detailed checks are only paid on the exceptional/error path.
        $table = fetch_table($tableId);
        if (!$table) fast_add_fail('მაგიდა ვერ მოიძებნა.', 404);
        $productCheck = $pdo->prepare('SELECT id FROM products WHERE id=? AND is_active=1 LIMIT 1');
        $productCheck->execute([$productId]);
        if (!$productCheck->fetchColumn()) fast_add_fail('პროდუქტი ვერ მოიძებნა ან გათიშულია.', 404);
        fast_add_fail('პროდუქტის დამატება ვერ მოხერხდა.', 409);
    }

    $orderId = (int)($row['order_id'] ?? 0);
    $receiptNumber = (int)($row['receipt_number'] ?? 0);

    if ($orderId <= 0) {
        $orderId = create_order($dayId, $tableId);
        $createdOrder = fetch_order($orderId);
        $receiptNumber = (int)($createdOrder['receipt_number'] ?? 0);
    }

    $insert = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, quantity, price, product_cost, comment) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $insert->execute([
        $orderId,
        $productId,
        $row['product_name'],
        $quantity,
        $row['product_price'],
        $row['product_cost'] ?? 0,
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
            'name' => (string)$row['product_name'],
            'quantity' => $quantity,
            'price' => (float)$row['product_price'],
            'comment' => $comment,
        ],
        'order' => [
            'id' => $orderId,
            'receipt_number' => $receiptNumber,
            'total' => $total,
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    error_log('GARBALIA fast add item: ' . $e->getMessage());
    fast_add_fail('პროდუქტის დამატება ვერ მოხერხდა. სცადე თავიდან.', 500);
}
