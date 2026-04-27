<?php
require_once __DIR__ . '/db_api.php';

$user = requireApiAuth();

if ($user['role'] !== 'admin') {
    jsonResponse('error', 'Unauthorized: Admin access required', null, 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Only POST method is allowed', null, 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$id = $input['id'] ?? null;
$reply = $input['reply'] ?? '';

if (!$id || !$reply) {
    jsonResponse('error', 'Inquiry ID and reply message are required.', null, 400);
}

$stmt = $pdo->prepare('UPDATE inquiries SET admin_reply = ? WHERE id = ?');

if ($stmt->execute([$reply, $id])) {
    jsonResponse('success', 'Reply sent successfully.');
} else {
    jsonResponse('error', 'Failed to save reply.', null, 500);
}
?>
