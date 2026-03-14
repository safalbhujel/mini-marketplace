<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = (int)$_SESSION['user_id'];

// Count user's own items
$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM items WHERE user_id = $uid");
$count_row    = mysqli_fetch_assoc($count_result);
$item_count   = $count_row['total'] ?? 0;

// Count total marketplace items
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM items");
$total_row    = mysqli_fetch_assoc($total_result);
$total_items  = $total_row['total'] ?? 0;

$is_admin = ($_SESSION['user_role'] ?? '') === 'admin';

// Get initials for avatar
$initials = strtoupper(substr($_SESSION['user_name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, #9b8aff 100%);
            border-radius: var(--radius);
            padding: 28px 32px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
        }
        .avatar {
            width: 60px; height: 60px;
            background: rgba(255,255,255,.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }
        .welcome-banner h1 { font-size: 22px; margin-bottom: 4px; }
        .welcome-banner p  { font-size: 14px; opacity: .85; margin: 0; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="brand">🛍️ Mini Marketplace</a>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="add_item.php">Add Item</a>
    <a href="view_items.php">Marketplace</a>
    <?php if ($is_admin): ?><a href="admin_panel.php">Admin</a><?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="page-wrap">

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="avatar"><?= htmlspecialchars($initials) ?></div>
        <div>
            <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>! 👋</h1>
            <p><?= $is_admin ? 'You are logged in as <strong>Administrator</strong>.' : 'Manage your listings and explore the marketplace.' ?></p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="dash-grid">
        <a href="view_items.php" class="dash-card">
            <div class="dc-icon">🗂️</div>
            <div class="dc-count"><?= $item_count ?></div>
            <div class="dc-label">My Listings</div>
        </a>
        <a href="view_items.php" class="dash-card">
            <div class="dc-icon">🌐</div>
            <div class="dc-count"><?= $total_items ?></div>
            <div class="dc-label">Total Items</div>
        </a>
        <a href="add_item.php" class="dash-card">
            <div class="dc-icon">➕</div>
            <div class="dc-count" style="font-size:20px;">Post</div>
            <div class="dc-label">Add New Item</div>
        </a>
        <?php if ($is_admin): ?>
        <a href="admin_panel.php" class="dash-card">
            <div class="dc-icon">⚙️</div>
            <div class="dc-count" style="font-size:20px;">Panel</div>
            <div class="dc-label">Admin Panel</div>
        </a>
        <?php endif; ?>
    </div>

</div>

<div class="footer">Mini Marketplace &copy; <?= date('Y') ?></div>

</body>
</html>