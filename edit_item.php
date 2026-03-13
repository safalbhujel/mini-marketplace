<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid      = (int)$_SESSION['user_id'];
$is_admin = ($_SESSION['user_role'] ?? '') === 'admin';
$message  = '';
$success  = false;

// ── Validate GET id ──────────────────────────────────────────────────────────
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_items.php");
    exit();
}
$id = (int)$_GET['id'];

// ── Fetch item ───────────────────────────────────────────────────────────────
function fetch_item($conn, $id) {
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
    return (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
}

$item = fetch_item($conn, $id);
if (!$item) {
    header("Location: view_items.php");
    exit();
}

// ── Ownership check ──────────────────────────────────────────────────────────
if ((int)$item['user_id'] !== $uid && !$is_admin) {
    header("Location: view_items.php");
    exit();
}

// ── Handle update ────────────────────────────────────────────────────────────
if (isset($_POST['update'])) {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price']       ?? '');

    if (empty($title) || empty($description) || empty($price)) {
        $message = "All fields are required.";
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $message = "Please enter a valid price.";
    } else {
        // Image
        $image = $item['image']; // keep old image by default

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
                // Delete old image
                if (!empty($item['image']) && file_exists('uploads/' . $item['image'])) {
                    unlink('uploads/' . $item['image']);
                }
                $image = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
            }
        }

        if ($message === '') {
            $t = mysqli_real_escape_string($conn, $title);
            $d = mysqli_real_escape_string($conn, $description);
            $p = (float)$price;
            $i = mysqli_real_escape_string($conn, $image);

            $sql = "UPDATE items SET title='$t', description='$d', price='$p', image='$i' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                $message = "Item updated successfully!";
                $success = true;
                $item    = fetch_item($conn, $id); // refresh item
            } else {
                $message = "Database error. Please try again.";
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
    <title>Edit Item — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <span class="brand">🛍️ Mini Marketplace</span>
    <a href="dashboard.php">Dashboard</a>
    <a href="view_items.php" class="active">Marketplace</a>
    <?php if ($is_admin): ?><a href="admin_panel.php">Admin</a><?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="page-wrap">
<div class="container">

    <h2>✏️ Edit Item</h2>

    <?php if ($message !== ''): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>">
            <?= $success ? '✅' : '⚠️' ?> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>

        <div class="form-group">
            <label for="title">Item Title</label>
            <input type="text" id="title" name="title"
                   value="<?= htmlspecialchars($item['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($item['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (Rs)</label>
            <input type="number" id="price" name="price" min="0" step="0.01"
                   value="<?= htmlspecialchars($item['price']) ?>" required>
        </div>

        <?php if (!empty($item['image']) && file_exists('uploads/' . $item['image'])): ?>
        <div class="form-group">
            <label>Current Image</label>
            <img src="uploads/<?= htmlspecialchars($item['image']) ?>"
                 alt="Current image"
                 style="width:120px; height:100px; object-fit:cover; border-radius:8px; margin-top:4px;">
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="image">Replace Image <small style="color:var(--text-muted); font-weight:400;">(leave blank to keep current — any size accepted)</small></label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:8px;">
            <button type="submit" name="update" class="btn btn-primary">💾 Save Changes</button>
            <a href="view_items.php" class="btn btn-secondary">← Cancel</a>
        </div>

    </form>

</div>
</div>

<div class="footer">Mini Marketplace &copy; <?= date('Y') ?></div>

</body>
</html>