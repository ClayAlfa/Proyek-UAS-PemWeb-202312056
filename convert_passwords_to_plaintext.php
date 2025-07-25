<?php
/**
 * PERINGATAN: Script ini mengubah password dari hash MD5 ke plain text
 * TIDAK AMAN untuk produksi - hanya untuk development/testing
 */

require_once 'config/database.php';

echo "⚠️  PERINGATAN: Script ini akan mengubah semua password ke plain text!\n";
echo "Ini TIDAK AMAN dan hanya boleh digunakan untuk development.\n\n";

// Password default untuk setiap role
$defaultPasswords = [
    'admin' => 'admin123',
    'operator' => 'operator123', 
    'user' => 'user123'
];

try {
    $conn = getConnection();
    
    // Ambil semua user
    $result = $conn->query("SELECT id, username, role FROM users");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    
    $conn->begin_transaction();
    
    $updateCount = 0;
    
    foreach ($users as $user) {
        // Tentukan password default berdasarkan role
        $newPassword = $defaultPasswords[$user['role']] ?? 'password123';
        
        // Update password ke plain text
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $user['id']);
        
        if ($stmt->execute()) {
            echo "✅ Updated user '{$user['username']}' (Role: {$user['role']}) - New password: '{$newPassword}'\n";
            $updateCount++;
        } else {
            echo "❌ Failed to update user '{$user['username']}'\n";
        }
        
        $stmt->close();
    }
    
    $conn->commit();
    
    echo "\n🎉 Successfully converted {$updateCount} passwords to plain text!\n\n";
    echo "📋 Default passwords by role:\n";
    foreach ($defaultPasswords as $role => $password) {
        echo "   • {$role}: {$password}\n";
    }
    echo "\n⚠️  REMEMBER: This is NOT SECURE for production use!\n";
    
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
