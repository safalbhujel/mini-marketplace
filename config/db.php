<?php
$host = "localhost";      
$dbname = "marketplace_db";  
$username = "root"; 
$password = ""; 

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed. Please ensure MySQL is running and the database is set up. Run config/schema.sql to create the database and tables.");
}
?>