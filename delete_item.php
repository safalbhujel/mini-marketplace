<?php
/**
 * delete_item.php  — canonical delete handler (correct filename)
 * Also works for admins. Deletes image file from disk, then DB row.
 */
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$is_admin = ($_SESSION['user_role'] ?? '') === 'admin';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Fetch item first — verify it exists
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
    if (mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);

        // Only owner or admin may delete
        if ((int)$item['user_id'] === (int)$_SESSION['user_id'] || $is_admin) {

            // Remove image from disk if it exists
            if (!empty($item['image'])) {
                $image_path = 'uploads/' . $item['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            // Delete the DB record
            mysqli_query($conn, "DELETE FROM items WHERE id = $id");
        }
    }
}

header("Location: view_items.php");
exit();
?>
