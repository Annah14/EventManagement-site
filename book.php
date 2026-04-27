<?php
require_once __DIR__ . '/db_api.php';

// Mobile apps must send the token as a Bearer token in the Authorization header
// Example: Authorization: Bearer <token_here>
$user = requireApiAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

// Read JSON input or POST form data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$event_date = $input['event_date'] ?? '';
$event_type = trim($input['event_type'] ?? '');
$venue = trim($input['venue'] ?? '');
$guests = intval($input['guests'] ?? 0);
$message_text = trim($input['message'] ?? '');
$selected_package = $input['package_type'] ?? '';
$payment_method = $input['payment_method'] ?? '';
$payment_pin = trim($input['payment_pin'] ?? '');

if (!$event_date || !$event_type || !$venue || $guests <= 0 || !$payment_method || !$selected_package) {
    jsonResponse('error', 'Please complete all required booking fields.', null, 400);
}

// Optional validation: check if package exists
$packageList = $pdo->query("SELECT name FROM packages")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array($selected_package, $packageList, true)) {
    jsonResponse('error', 'Invalid package selected.', null, 400);
}

$stmt = $pdo->prepare('INSERT INTO bookings (user_id, event_date, package_type, event_type, venue, guests, message, payment_method, payment_pin, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "Pending")');

if ($stmt->execute([$user['id'], $event_date, $selected_package, $event_type, $venue, $guests, $message_text, $payment_method, $payment_pin])) {
    $bookingId = $pdo->lastInsertId();
    jsonResponse('success', 'Your booking request was submitted successfully.', ['booking_id' => $bookingId], 201);
} else {
    jsonResponse('error', 'Failed to create booking.', null, 500);
}
?>
