<?php
require_once __DIR__ . '/db.php';
if (currentUser()) {
    header('Location: dashboard.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$fullname || !$email || !$password || !$confirm) {
        $message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Enter a valid email address.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = 'That email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$fullname, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<?php include 'header.php'; ?>
<section class="page-section auth-section">
    <h2>Create your account</h2>
    <?php if ($message): ?><p class="message error"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <form method="post" class="auth-form">
        <label>Full Name</label>
        <input type="text" name="fullname" placeholder="Full Name" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required>
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button class="btn-primary" type="submit">Register</button>
        <p class="small-text">Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</section>
<?php include 'footer.php'; ?>