<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator');
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

// Require admin
function requireAdmin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
    if (!isAdmin()) {
        // Redirect non-admin users to main page
        header('Location: ../index.php');
        exit();
    }
}

// Logout function
function logout() {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

// Get current user info
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    // Try different paths for database.php
    $possible_paths = [
        'config/database.php',
        '../config/database.php',
        '../../config/database.php'
    ];
    
    $database_included = false;
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $database_included = true;
            break;
        }
    }
    
    if (!$database_included) {
        return null; // Return null if database file not found
    }
    
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $user;
}

// Log user activity
function logActivity($user_id, $action, $description) {
    // Try different paths for database.php
    $possible_paths = [
        'config/database.php',
        '../config/database.php',
        '../../config/database.php'
    ];
    
    $database_included = false;
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $database_included = true;
            break;
        }
    }
    
    if (!$database_included) {
        return; // Skip logging if database file not found
    }
    
    $conn = getConnection();
    
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, description, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $action, $description);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>
