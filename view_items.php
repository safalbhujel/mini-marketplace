<?php
session_start();
include 'config/db.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid      = (int)$_SESSION['user_id'];
$is_admin = ($_SESSION['user_role'] ?? '') === 'admin';

$result = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <span class="brand">🛍️ Mini Marketplace</span>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_item.php">Add Item</a>
    <a href="view_items.php" class="active">Marketplace</a>
    <?php if ($is_admin): ?><a href="admin_panel.php">Admin</a><?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="page-wrap">

    <div class="section-header">
        <h1>Marketplace</h1>
        <a href="add_item.php" class="btn btn-primary">➕ Post Item</a>
    </div>

    <?php
    $num = mysqli_num_rows($result);
    if ($num === 0):
    ?>
        <div style="text-align:center; padding:60px 20px; color:var(--text-muted);">
            <div style="font-size:52px; margin-bottom:12px;">📭</div>
            <p>No items listed yet. <a href="add_item.php">Be the first to post one!</a></p>
        </div>
    <?php else: ?>
    <div class="items-grid">
    <?php while ($row = mysqli_fetch_assoc($result)):
        $is_owner = ((int)$row['user_id'] === $uid) || $is_admin;
    ?>
        <div class="item-card">

            <?php if (!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
                <img class="card-img" src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
            <?php else: ?>
                <div class="card-img-placeholder">🖼️</div>
            <?php endif; ?>

            <div class="card-body">
                <div class="card-title"><?= htmlspecialchars($row['title']) ?></div>
                <div class="card-desc"><?= htmlspecialchars($row['description']) ?></div>
                <div class="card-price">Rs <?= number_format((float)$row['price'], 2) ?></div>

                <div class="card-actions">
                    <a href="item_details.php?id=<?= (int)$row['id'] ?>" class="btn btn-secondary btn-sm">👁 View</a>
                    <?php if ($is_owner): ?>
                        <a href="edit_item.php?id=<?= (int)$row['id'] ?>" class="btn btn-success btn-sm">✏️ Edit</a>
                        <a href="delete_item.php?id=<?= (int)$row['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this item? This cannot be undone.');">🗑 Delete</a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    <?php endwhile; ?>
    </div>
    <?php endif; ?>

</div>

<div class="footer">Mini Marketplace &copy; <?= date('Y') ?></div>

</body>
</html>