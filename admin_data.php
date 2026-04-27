<?php
require_once __DIR__ . '/db_api.php';

$user = requireApiAuth();

if ($user['role'] !== 'admin') {
    jsonResponse('error', 'Unauthorized: Admin access required', null, 403);
}

// Fetch all bookings with user details
$stmt = $pdo->query('SELECT b.*, u.fullname, u.email FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC');
$bookings = $stmt->fetchAll();

// Fetch all registered users
$stmt = $pdo->query('SELECT id, fullname, email, role, created_at FROM users ORDER BY created_at DESC');
$usersList = $stmt->fetchAll();

// Fetch all inquiries
$stmt = $pdo->query('SELECT * FROM inquiries ORDER BY created_at DESC');
$inquiries = $stmt->fetchAll();

// Fetch services and packages
$services = $pdo->query('SELECT * FROM services ORDER BY created_at DESC')->fetchAll();
$packages = $pdo->query('SELECT * FROM packages ORDER BY created_at DESC')->fetchAll();

jsonResponse('success', 'Admin data fetched successfully.', [
    'bookings' => $bookings,
    'users' => $usersList,
    'inquiries' => $inquiries,
    'services' => $services,
    'packages' => $packages
]);
?>
