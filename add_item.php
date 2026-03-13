<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid     = (int)$_SESSION['user_id'];
$message = '';
$success = false;

if (isset($_POST['add_item'])) {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price']       ?? '');

    // Basic validation
    if (empty($title) || empty($description) || empty($price)) {
        $message = "All fields are required.";
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $message = "Please enter a valid price.";
    } else {
        $image_name = '';

        // Image upload
        if (!empty($_FILES['image']['name'])) {
            $allowed_ext  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];


            $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['image']['tmp_name']);


            if (!in_array($ext, $allowed_ext) || !in_array($mime, $allowed_mime)) {
                $message = "Only JPG, PNG, GIF, or WebP images are allowed.";
            } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $message = "Upload error. Please try again.";
            } else {
                $image_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
            }
        }

        if ($message === '') {
            $t = mysqli_real_escape_string($conn, $title);
            $d = mysqli_real_escape_string($conn, $description);
            $p = (float)$price;
            $i = mysqli_real_escape_string($conn, $image_name);

            $sql = "INSERT INTO items (title, description, price, image, user_id)
                    VALUES ('$t', '$d', '$p', '$i', $uid)";

            if (mysqli_query($conn, $sql)) {
                $message = "Item listed successfully!";
                $success = true;
            } else {
                $message = "Database error. Please try again.";
            }
        }
    }
}

$is_admin = ($_SESSION['user_role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <span class="brand">🛍️ Mini Marketplace</span>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_item.php" class="active">Add Item</a>
    <a href="view_items.php">Marketplace</a>
    <?php if ($is_admin): ?><a href="admin_panel.php">Admin</a><?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="page-wrap">
<div class="container">

    <h2>📦 Post a New Item</h2>

    <?php if ($message !== ''): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>">
            <?= $success ? '✅' : '⚠️' ?> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" enctype="multipart/form-data" novalidate>

        <div class="form-group">
            <label for="title">Item Title</label>
            <input type="text" id="title" name="title"
                   placeholder="e.g. Vintage Bicycle"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"
                      placeholder="Describe your item in detail..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (Rs)</label>
            <input type="number" id="price" name="price" min="0" step="0.01"
                   placeholder="0.00"
                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="image">Product Image <small style="color:var(--text-muted); font-weight:400;">(JPG / PNG / GIF / WebP)</small></label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <input type="submit" name="add_item" value="Post Item">

    </form>
    <?php else: ?>
        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:8px;">
            <a href="view_items.php" class="btn btn-primary">🌐 View Marketplace</a>
            <a href="add_item.php"   class="btn btn-secondary">➕ Add Another</a>
        </div>
    <?php endif; ?>

</div>
</div>

<div class="footer">Mini Marketplace &copy; <?= date('Y') ?></div>

</body>
</html>