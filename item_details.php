<?php
session_start();
include 'config/db.php';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_items.php");
    exit();
}
$id = (int)$_GET['id'];

// Fetch item + seller
$result = mysqli_query($conn,
    "SELECT items.*, users.name AS seller_name
     FROM items
     JOIN users ON items.user_id = users.id
     WHERE items.id = $id"
);

if (mysqli_num_rows($result) === 0) {
    header("Location: view_items.php");
    exit();
}

$item     = mysqli_fetch_assoc($result);
$uid      = $_SESSION['user_id'] ?? 0;
$is_admin = ($_SESSION['user_role'] ?? '') === 'admin';
$is_owner = ((int)$item['user_id'] === (int)$uid) || $is_admin;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['title']) ?> — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .seller-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-light); color: var(--primary-dark);
            border-radius: 20px; padding: 4px 14px; font-size: 13px; font-weight: 600;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="brand">🛍️ Mini Marketplace</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_items.php" class="active">Marketplace</a>
        <?php if ($is_admin): ?><a href="admin_panel.php">Admin</a><?php endif; ?>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="index.php">Home</a>
        <a href="view_items.php" class="active">Browse</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<div class="page-wrap">
<div class="container" style="max-width:760px;">

    <?php if (!empty($item['image']) && file_exists('uploads/' . $item['image'])): ?>
        <img class="detail-img" src="uploads/<?= htmlspecialchars($item['image']) ?>"
             alt="<?= htmlspecialchars($item['title']) ?>">
    <?php else: ?>
        <div style="width:100%;height:220px;background:linear-gradient(135deg,#e0ddff,#f3f4f8);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:60px;margin-bottom:22px;">🖼️</div>
    <?php endif; ?>

    <h2 style="margin-bottom:14px;"><?= htmlspecialchars($item['title']) ?></h2>

    <div class="detail-meta">
        <span class="badge badge-price">Rs <?= number_format((float)$item['price'], 2) ?></span>
        <span class="seller-chip">👤 <?= htmlspecialchars($item['seller_name']) ?></span>
    </div>

    <p style="color:var(--text-muted); margin-bottom:24px; line-height:1.8;">
        <?= nl2br(htmlspecialchars($item['description'])) ?>
    </p>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="view_items.php" class="btn btn-secondary">← Back to Marketplace</a>
        <?php if ($is_owner): ?>
            <a href="edit_item.php?id=<?= $id ?>" class="btn btn-success">✏️ Edit Item</a>
            <a href="delete_item.php?id=<?= $id ?>"
               class="btn btn-danger"
               onclick="return confirm('Delete this item? This cannot be undone.');">🗑 Delete</a>
        <?php endif; ?>
    </div>

</div>
</div>

<div class="footer">Mini Marketplace &copy; <?= date('Y') ?></div>

</body>
</html>