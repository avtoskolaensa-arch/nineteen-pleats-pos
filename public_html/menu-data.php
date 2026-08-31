<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/public-menu.php';

session_write_close();

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

try {
    echo json_encode(public_menu_payload(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'მენიუს განახლება დროებით ვერ მოხერხდა.',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
