<?php
session_start();
include 'config/db.php';

// Admin-only
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$items = mysqli_query($conn,
    "SELECT items.*, users.name AS seller_name
     FROM items
     JOIN users ON items.user_id = users.id
     ORDER BY items.id DESC"
);

// User count & item count for quick stats
$ucount = mysqli_num_rows($users);
$icount = mysqli_num_rows($items);

// Re-run queries after counting (pointer is at end)
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$items = mysqli_query($conn,
    "SELECT items.*, users.name AS seller_name
     FROM items
     JOIN users ON items.user_id = users.id
     ORDER BY items.id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <span class="brand">🛍️ Mini Marketplace</span>
    <a href="dashboard.php">Dashboard</a>
    <a href="view_items.php">Marketplace</a>
    <a href="admin_panel.php" class="active">Admin</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="page-wrap" style="max-width:1000px; margin:0 auto; padding:28px 20px 60px;">

    <h2 style="margin-bottom:24px;">⚙️ Admin Panel</h2>

    <!-- Quick stats row -->
    <div style="display:flex; gap:16px; margin-bottom:32px; flex-wrap:wrap;">
        <div style="background:var(--primary-light); border-radius:var(--radius-sm); padding:16px 24px; flex:1; min-width:140px;">
            <div style="font-size:24px; font-weight:800; color:var(--primary);"><?= $ucount ?></div>
            <div style="font-size:13px; color:var(--text-muted); font-weight:600;">Registered Users</div>
        </div>
        <div style="background:#dcfce7; border-radius:var(--radius-sm); padding:16px 24px; flex:1; min-width:140px;">
            <div style="font-size:24px; font-weight:800; color:#16a34a;"><?= $icount ?></div>
            <div style="font-size:13px; color:var(--text-muted); font-weight:600;">Total Listings</div>
        </div>
    </div>

    <!-- Users Table -->
    <h3 style="margin-bottom:10px;">👥 All Users</h3>
    <div style="overflow-x:auto; margin-bottom:36px;">
        <table class="admin-table">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= (int)$user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <span class="badge <?= $user['role'] === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Items Table -->
    <h3 style="margin-bottom:10px;">📦 All Items</h3>
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Price</th>
                <th>Seller</th>
                <th>Action</th>
            </tr>
            <?php while ($item = mysqli_fetch_assoc($items)): ?>
            <tr>
                <td><?= (int)$item['id'] ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td>Rs <?= number_format((float)$item['price'], 2) ?></td>
                <td><?= htmlspecialchars($item['seller_name']) ?></td>
                <td>
                    <a href="item_details.php?id=<?= (int)$item['id'] ?>"
                       class="btn btn-secondary btn-sm">👁 View</a>
                    <a href="delete_item.php?id=<?= (int)$item['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this item permanently?');">🗑 Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

<div class="footer">Mini Marketplace &copy; <?= date('Y') ?></div>

</body>
</html>