<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse('error', 'Only GET method is allowed', null, 405);
}

$stmt = $pdo->query('SELECT id, title, short_desc, long_desc, icon_class, image_url FROM services ORDER BY id ASC');
$services = $stmt->fetchAll();

jsonResponse('success', 'Services retrieved successfully', ['services' => $services]);
?>
