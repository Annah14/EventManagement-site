<?php
require_once __DIR__ . '/db_api.php';

$user = requireApiAuth();

if ($user['role'] !== 'admin') {
    jsonResponse('error', 'Unauthorized: Admin access required', null, 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

// Read JSON input or POST form data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$bookingId = intval($input['booking_id'] ?? 0);
$newStatus = $input['status'] ?? '';

if (!$bookingId || !in_array($newStatus, ['Approved', 'Rejected'], true)) {
    jsonResponse('error', 'Invalid booking ID or status.', null, 400);
}

$stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');

if ($stmt->execute([$newStatus, $bookingId])) {
    jsonResponse('success', 'Booking status updated successfully.');
} else {
    jsonResponse('error', 'Failed to update booking status.', null, 500);
}
?>
