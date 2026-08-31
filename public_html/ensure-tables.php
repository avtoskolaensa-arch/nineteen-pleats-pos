<?php
require __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Authentication required'], JSON_UNESCAPED_UNICODE);
    exit;
}
session_write_close();

$pdo = db();
$wanted = [
    ['name' => 'მაგიდა 11', 'sort_order' => 11],
    ['name' => 'მაგიდა 12', 'sort_order' => 12],
    ['name' => 'გატანა 1', 'sort_order' => 1001],
    ['name' => 'გატანა 2', 'sort_order' => 1002],
    ['name' => 'გატანა 3', 'sort_order' => 1003],
    ['name' => 'გატანა 4', 'sort_order' => 1004],
    ['name' => 'გატანა 5', 'sort_order' => 1005],
];
$wantedNames = array_column($wanted, 'name');
$placeholders = implode(',', array_fill(0, count($wantedNames), '?'));

try {
    $countStmt = $pdo->prepare(
        'SELECT COUNT(*) FROM restaurant_tables WHERE is_active=1 AND name IN (' . $placeholders . ')'
    );
    $countStmt->execute($wantedNames);
    $before = (int)$countStmt->fetchColumn();

    $pdo->beginTransaction();
    $stmt = $pdo->prepare(
        'INSERT INTO restaurant_tables (name, sort_order, is_active) VALUES (?, ?, 1) '
        . 'ON DUPLICATE KEY UPDATE sort_order=VALUES(sort_order), is_active=1'
    );
    foreach ($wanted as $table) {
        $stmt->execute([$table['name'], $table['sort_order']]);
    }
    $pdo->commit();

    $countStmt->execute($wantedNames);
    $after = (int)$countStmt->fetchColumn();

    echo json_encode([
        'ok' => true,
        'changed' => $after > $before,
        'active_tables_added' => max(0, $after - $before),
        'regular_tables' => 12,
        'takeaway_slots' => 5,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Tables could not be synchronized'], JSON_UNESCAPED_UNICODE);
}
