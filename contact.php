<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

// Read JSON input or POST form data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$fullname = trim($input['fullname'] ?? '');
$email = trim($input['email'] ?? '');
$subject = trim($input['subject'] ?? '');
$message = trim($input['message'] ?? '');

if (!$fullname || !$email || !$subject || !$message) {
    jsonResponse('error', 'Please fill all fields.', null, 400);
}

$stmt = $pdo->prepare('INSERT INTO inquiries (fullname, email, subject, message) VALUES (?, ?, ?, ?)');

if ($stmt->execute([$fullname, $email, $subject, $message])) {
    jsonResponse('success', 'Your message has been sent successfully.', null, 201);
} else {
    jsonResponse('error', 'Failed to send message.', null, 500);
}
?>
