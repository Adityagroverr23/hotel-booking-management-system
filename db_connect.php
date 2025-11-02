<?php
// includes/db_connect.php
$host = 'localhost';
$db = 'hotel_db';
$user = 'root';
$pass = ''; // empty password


$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
die('Database connection failed: ' . mysqli_connect_error());
}
// set charset
mysqli_set_charset($conn, 'utf8mb4');
?>