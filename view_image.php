<?php
session_start();
include 'config/db.php';

$item = null;
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$img  = trim($_GET['img'] ?? '');

// Fetch by image filename (reliable when ids are broken)
if (!empty($img) && preg_match('/^[a-zA-Z0-9._-]+$/', $img)) {
    $img_safe = mysqli_real_escape_string($conn, $img);
    $result   = mysqli_query($conn, "SELECT * FROM items WHERE image = '$img_safe' LIMIT 1");
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
        $id   = (int)$item['id'];
    }
}

// Fetch by id (when image param not used or not found)
if (!$item) {
    if ($id === 0 && empty($img)) {
        header("Location: view_items.php");
        exit();
    }
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    }
}

if (!$item) {
    header("Location: view_items.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['title']) ?> — Image</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .image-view-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #1a1a1a;
        }
        .image-view-wrap img {
            max-width: 100%;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 8px;
        }
        .image-view-placeholder {
            width: 400px;
            height: 400px;
            max-width: 100%;
            background: linear-gradient(135deg, #2d2d2d, #1a1a1a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
        }
        .image-view-back {
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
            opacity: 0.8;
        }
        .image-view-back:hover {
            opacity: 1;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="image-view-wrap">
    <?php if (!empty($item['image']) && file_exists('uploads/' . $item['image'])): ?>
        <img src="uploads/<?= htmlspecialchars($item['image']) ?>?v=<?= (int)$item['id'] ?>" alt="<?= htmlspecialchars($item['title']) ?>">
    <?php else: ?>
        <div class="image-view-placeholder">🖼️</div>
    <?php endif; ?>
    <a href="view_items.php" class="image-view-back">← Back to Marketplace</a>
</div>
</body>
</html>
