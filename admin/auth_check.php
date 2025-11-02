<?php
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    // Redirect to admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}
?>
