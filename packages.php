<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse('error', 'Only GET method is allowed', null, 405);
}

$stmt = $pdo->query('SELECT id, name, price, short_desc, long_desc, icon_class FROM packages ORDER BY price ASC');
$packages = $stmt->fetchAll();

jsonResponse('success', 'Packages retrieved successfully', ['packages' => $packages]);
?>
