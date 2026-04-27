<?php
require_once __DIR__ . '/db_api.php';

$user = requireApiAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse('error', 'Only GET method is allowed', null, 405);
}

$stmt = $pdo->prepare('SELECT id, event_date, package_type, event_type, venue, guests, status, created_at FROM bookings WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$bookings = $stmt->fetchAll();

jsonResponse('success', 'Bookings retrieved successfully', $bookings);
?>
