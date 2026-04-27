<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$id = intval($input['package_id'] ?? 0);

if (!$id) {
    jsonResponse('error', 'Package ID is required');
}

try {
    $stmt = $pdo->prepare('DELETE FROM packages WHERE id = ?');
    $stmt->execute([$id]);
    jsonResponse('success', 'Package removed');
} catch (Exception $e) {
    jsonResponse('error', 'Database error: ' . $e->getMessage());
}
?>
