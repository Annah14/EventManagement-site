<?php
require_once __DIR__ . '/db.php';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annah Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">Annah <span>Events</span></div>
        <button class="menu-toggle" aria-label="Toggle navigation" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="packages.php">Packages</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($user): ?>
                <?php if ($user['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                <?php else: ?>
                    <li><a href="dashboard.php">My Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <div class="nav-buttons">
            <?php if ($user): ?>
                <span class="user-welcome">Welcome, <?= htmlspecialchars($user['fullname']) ?></span>
                <a class="btn-primary" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn-outline" href="login.php">Login</a>
                <a class="btn-primary" href="register.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
<script>
function toggleMenu() {
    document.getElementById('navLinks').classList.toggle('active');
}
</script>
