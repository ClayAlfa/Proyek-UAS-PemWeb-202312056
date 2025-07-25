<?php
/**
 * Test script untuk verifikasi login dengan password plain text
 */

require_once 'config/database.php';

echo "🧪 Testing Plain Text Password Login\n";
echo "===================================\n\n";

try {
    $conn = getConnection();
    
    // Test data
    $testCredentials = [
        ['username' => 'admin', 'password' => 'admin123', 'expected_role' => 'admin'],
        ['username' => 'operator', 'password' => 'operator123', 'expected_role' => 'operator'],
        ['username' => 'user', 'password' => 'user123', 'expected_role' => 'user']
    ];
    
    foreach ($testCredentials as $test) {
        echo "Testing login for: {$test['username']}\n";
        
        // Simulate login process
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $test['username'], $test['password']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['role'] === $test['expected_role']) {
                echo "✅ SUCCESS: {$test['username']} login works! Role: {$user['role']}\n";
            } else {
                echo "⚠️  WARNING: {$test['username']} login works but wrong role. Expected: {$test['expected_role']}, Got: {$user['role']}\n";
            }
        } else {
            echo "❌ FAILED: {$test['username']} login failed\n";
        }
        
        $stmt->close();
        echo "\n";
    }
    
    // Show all users with their plain text passwords
    echo "📋 Current users in database:\n";
    echo "==============================\n";
    $result = $conn->query("SELECT id, username, password, role FROM users ORDER BY id");
    
    if ($result->num_rows > 0) {
        while ($user = $result->fetch_assoc()) {
            echo "ID: {$user['id']}, Username: {$user['username']}, Password: {$user['password']}, Role: {$user['role']}\n";
        }
    } else {
        echo "No users found in database.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

echo "\n⚠️  REMINDER: Plain text passwords are NOT SECURE!\n";
echo "Only use for development/testing purposes.\n";
?>
