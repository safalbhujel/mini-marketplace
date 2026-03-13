<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mini Marketplace</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
<a href="index.php">Home</a>
<a href="login.php">Login</a>
<a href="register.php">Register</a>
</div>

<div class="container">
<h2>Welcome to Mini Marketplace</h2>

<p>
This is a simple marketplace system where users can register,
login, and sell items online. Users can add items, edit them,
delete them, and view other items in the marketplace.
</p>

<p>
This project is developed using <b>HTML, CSS, PHP, and MySQL</b>
as part of a Web Technology project.
</p>

<a href="register.php">
<button>Start Selling</button>
</a>

</div>

</body>
</html>