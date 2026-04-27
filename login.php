<?php
require_once __DIR__ . '/db_api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

// Read JSON input or POST form data
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$email = strtolower(trim($input['email'] ?? ''));
$password = $input['password'] ?? '';

if (!$email || !$password) {
    jsonResponse('error', 'Please enter your email and password.', null, 400);
}

$stmt = $pdo->prepare('SELECT id, fullname, email, password, role FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Generate a new API token
    $api_token = bin2hex(random_bytes(32));
    
    // Update the token in the database
    $updateStmt = $pdo->prepare('UPDATE users SET api_token = ? WHERE id = ?');
    $updateStmt->execute([$api_token, $user['id']]);
    
    // Prepare user data to return
    unset($user['password']);
    $user['api_token'] = $api_token;
    
    jsonResponse('success', 'Login successful.', ['user' => $user]);
}

jsonResponse('error', 'Invalid login details.', null, 401);
?>
