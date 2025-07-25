<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Log the logout activity
if (isset($_SESSION['user_id'])) {
    logActivity($_SESSION['user_id'], 'Logout', 'User logged out');
}

// Destroy session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>
