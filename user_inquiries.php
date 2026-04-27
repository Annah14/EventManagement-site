<?php
require_once __DIR__ . '/db_api.php';

$user = requireApiAuth();
$email = $user['email'];

$stmt = $pdo->prepare('SELECT * FROM inquiries WHERE email = ? ORDER BY created_at DESC');
$stmt->execute([$email]);
$inquiries = $stmt->fetchAll();

jsonResponse('success', 'User inquiries fetched successfully.', $inquiries);
?>
