<?php
require_once __DIR__ . '/db.php';
if (currentUser()) {
    if (isAdmin()) {
        header('Location: admin.php');
    } else {
        header('Location: dashboard.php');
    }
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $message = 'Please enter your email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, fullname, email, password, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        }
        $message = 'Invalid login details.';
    }
}
?>
<?php include 'header.php'; ?>
<section class="page-section auth-section">
    <h2>Login to your account</h2>
    <?php if (isset($_GET['registered'])): ?><p class="message success">Your account was created. Please login.</p><?php endif; ?>
    <?php if ($message): ?><p class="message error"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <form method="post" class="auth-form">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>
        <button class="btn-primary" type="submit">Login</button>
        <p class="small-text">Don't have an account? <a href="register.php">Sign up</a>.</p>
    </form>
</section>
<?php include 'footer.php'; ?>