<?php
/**
 * Database repair script - fixes id=0 and AUTO_INCREMENT issues
 * Visit this page once to fix: http://localhost/mini_marketplace/db_repair.php
 */
session_start();
include 'config/db.php';

$results = [];

// Fix users table
@mysqli_query($conn, "ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT");
$results[] = "Users table: AUTO_INCREMENT fixed";

// Fix items table - reassign any id=0 rows to unique ids (handles multiple rows with id=0)
$count = 0;
while (mysqli_num_rows(mysqli_query($conn, "SELECT 1 FROM items WHERE id = 0 LIMIT 1")) > 0) {
    $maxq = mysqli_query($conn, "SELECT COALESCE(MAX(id), 0) + 1 FROM items");
    $new_id = (int)mysqli_fetch_row($maxq)[0];
    mysqli_query($conn, "UPDATE items SET id = $new_id WHERE id = 0 LIMIT 1");
    $count++;
}
if ($count > 0) {
    $results[] = "Items table: Fixed $count row(s) with id=0";
}

@mysqli_query($conn, "ALTER TABLE items MODIFY id INT NOT NULL AUTO_INCREMENT");
$results[] = "Items table: AUTO_INCREMENT fixed";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Repair — Mini Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <h2>Database Repair Complete</h2>
        <div class="alert alert-success">
            <?php foreach ($results as $r): ?>
                <div>✓ <?= htmlspecialchars($r) ?></div>
            <?php endforeach; ?>
        </div>
        <p style="text-align:center; margin-top:18px;">
            <a href="view_items.php" class="btn btn-primary">Go to Marketplace</a>
            <a href="index.php" class="btn btn-secondary">Home</a>
        </p>
    </div>
</div>
</body>
</html>
