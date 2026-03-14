<?php
session_start();
include 'config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = '';
$success = false;

if (isset($_POST['register'])) {
    $name     = trim($_POST['name'] ?? '');
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    // Validate BEFORE hashing
    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
    } else {
        $name_safe  = mysqli_real_escape_string($conn, $name);
        $email_safe = mysqli_real_escape_string($conn, $email);

        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email_safe'");
        if ($check === false) {
            $message = "Database error: " . mysqli_error($conn);
        } elseif (mysqli_num_rows($check) > 0) {
            $message = "An account with that email already exists!";
        } else {
            $hash      = password_hash($password, PASSWORD_DEFAULT);
            $hash_safe = mysqli_real_escape_string($conn, $hash);
            $insert = mysqli_query($conn,
                "INSERT INTO users (name, email, password, role)
                 VALUES ('$name_safe', '$email_safe', '$hash_safe', 'user')"
            );
            if (!$insert) {
                $err = mysqli_error($conn);
                // Auto-fix: "Duplicate entry '0' for key 'PRIMARY'" = missing AUTO_INCREMENT
                if (strpos($err, "Duplicate entry '0' for key 'PRIMARY'") !== false) {
                    @mysqli_query($conn, "ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT");
                    $insert = mysqli_query($conn,
                        "INSERT INTO users (name, email, password, role)
                         VALUES ('$name_safe', '$email_safe', '$hash_safe', 'user')"
                    );
                }
            }
            if (!$insert) {
                $message = "Database error: " . mysqli_error($conn);
            } else {
                $message = "Registration successful! Sign in with your email and password.";
                $success = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-icon">✨</div>
        <h2>Create Account</h2>
        <p class="sub">Join the Mini Marketplace community</p>

        <?php if ($message !== ''): ?>
            <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>">
                <?= $success ? '✅' : '⚠️' ?> <?= htmlspecialchars($message) ?>
                <?php if ($success): ?><br><a href="login.php" style="font-weight:700;">Sign in now →</a><?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" novalidate>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       placeholder="Your full name"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                       required autofocus>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="you@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="password">Password <small style="color:var(--text-muted);font-weight:400;">(min. 6 characters)</small></label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>
            <input type="submit" name="register" value="Create Account">
        </form>
        <?php endif; ?>

        <p style="text-align:center; margin-top:18px; font-size:14px; color:var(--text-muted);">
            Already have an account? <a href="login.php">Sign in here</a>
        </p>

    </div>
</div>

</body>
</html>