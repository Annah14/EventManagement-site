<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

// Read JSON input or POST form data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$fullname = trim($input['fullname'] ?? '');
$email = strtolower(trim($input['email'] ?? ''));
$password = $input['password'] ?? '';

if (!$fullname || !$email || !$password) {
    jsonResponse('error', 'Please provide fullname, email, and password.', null, 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse('error', 'Please provide a valid email address.', null, 400);
}

// Check if email exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse('error', 'Email is already registered.', null, 409);
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$api_token = bin2hex(random_bytes(32));

$insertStmt = $pdo->prepare('INSERT INTO users (fullname, email, password, api_token) VALUES (?, ?, ?, ?)');
if ($insertStmt->execute([$fullname, $email, $hashedPassword, $api_token])) {
    $userId = $pdo->lastInsertId();
    $user = [
        'id' => $userId,
        'fullname' => $fullname,
        'email' => $email,
        'role' => 'user',
        'api_token' => $api_token
    ];
    jsonResponse('success', 'Registration successful.', ['user' => $user], 201);
} else {
    jsonResponse('error', 'Registration failed. Please try again.', null, 500);
}
?>
