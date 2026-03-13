<?php
session_start();
include 'config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id = intval($_GET['id']);

    // Fetch item to verify ownership
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id='$id'");
    if(mysqli_num_rows($result) > 0){
        $item = mysqli_fetch_assoc($result);

        // Allow delete if owner or admin
        if($item['user_id'] == $_SESSION['user_id'] || $_SESSION['user_role'] == "admin"){

            // Delete image file from uploads folder
            $image_path = "uploads/".$item['image'];
            if(file_exists($image_path)){
                unlink($image_path);
            }

            // Delete item from DB
            mysqli_query($conn, "DELETE FROM items WHERE id='$id'");
        }
    }
}

// Redirect back to marketplace
header("Location: view_items.php");
exit();
?>