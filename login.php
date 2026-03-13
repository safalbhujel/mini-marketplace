<?php
session_start();
include 'config/db.php';

// Already logged in → go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = '';

if (isset($_POST['login'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = "Both fields are required!";
    } else {
        $email_safe = mysqli_real_escape_string($conn, $email);
        $result     = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_safe'");

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-icon">🛍️</div>
        <h2>Welcome Back</h2>
        <p class="sub">Sign in to your Mini Marketplace account</p>

        <?php if ($message !== ''): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="you@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>
            <input type="submit" name="login" value="Sign In">
        </form>

        <p style="text-align:center; margin-top:18px; font-size:14px; color:var(--text-muted);">
            Don't have an account? <a href="register.php">Register here</a>
        </p>

    </div>
</div>

</body>
</html>