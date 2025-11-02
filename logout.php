<?php
// Start the session
session_start();

// If user (or admin) is logged in, clear their session
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
    session_unset();      // Remove all session variables
    session_destroy();    // Destroy the session completely
}

// Redirect to login page
header("Location: login.php");
exit();
?>
