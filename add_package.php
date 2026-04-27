<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$name = trim($input['name'] ?? '');
$price = floatval($input['price'] ?? 0);
$short = trim($input['short_desc'] ?? '');
$long = trim($input['long_desc'] ?? '');
$icon = trim($input['icon_class'] ?? 'fa-box');

if (!$name || $price <= 0) {
    jsonResponse('error', 'Package name and price are required');
}

try {
    $stmt = $pdo->prepare('INSERT INTO packages (name, price, short_desc, long_desc, icon_class) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $price, $short, $long, $icon]);
    jsonResponse('success', 'Package launched successfully');
} catch (Exception $e) {
    jsonResponse('error', 'Database error: ' . $e->getMessage());
}
?>
