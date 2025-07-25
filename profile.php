<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
requireLogin();

$page_title = "Profile";
$user = getCurrentUser();

if (!$user) {
    header('Location: login.php');
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $conn = getConnection();
    
    // Check current password
    if (!empty($current_password)) {
        // Menggunakan perbandingan password plain text
        if ($current_password != $user['password']) {
            $error = "Current password is incorrect!";
        } else {
            // Update password if new password is provided
            if (!empty($new_password)) {
                if ($new_password != $confirm_password) {
                    $error = "New passwords do not match!";
                } else {
                    // Menggunakan password plain text tanpa hash
                    $new_password_plain = $new_password;
                    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $username, $new_password_plain, $user['id']);
                    
                    if ($stmt->execute()) {
                        $_SESSION['username'] = $username;
                        $success = "Profile updated successfully!";
                        $user['username'] = $username;
                        $user['password'] = $new_password_plain;
                    } else {
                        $error = "Error updating profile: " . $stmt->error;
                    }
                    $stmt->close();
                }
            } else {
                // Update only username
                $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->bind_param("si", $username, $user['id']);
                
                if ($stmt->execute()) {
                    $_SESSION['username'] = $username;
                    $success = "Profile updated successfully!";
                    $user['username'] = $username;
                } else {
                    $error = "Error updating profile: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    } else {
        $error = "Please enter your current password to update profile!";
    }
    
    $conn->close();
}

require 'includes/header.php';
?>

<style>
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        margin-top: 100px;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 30px;
        text-align: center;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 3rem;
    }
    
    .form-group label {
        font-weight: 500;
        color: #333;
    }
    
    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .info-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 15px;
        border-left: 4px solid #667eea;
    }
    
    .info-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-weight: 500;
        color: #333;
    }
</style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card profile-card">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Profile Settings</h3>
                        <p class="mb-0">Manage your account information</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?= $success ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Current Profile Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Username</div>
                                    <div class="info-value"><?= $user['username'] ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Role</div>
                                    <div class="info-value"><?= ucfirst($user['role']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Member Since</div>
                                    <div class="info-value"><?= date('M d, Y', strtotime($user['created_at'])) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">User ID</div>
                                    <div class="info-value">#<?= $user['id'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Update Profile Form -->
                        <form method="POST">
                            <h5 class="mb-3">Update Profile</h5>
                            
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= $user['username'] ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" 
                                       placeholder="Enter current password to update profile" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">New Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" 
                                       placeholder="Enter new password">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm new password">
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Home
                                </a>
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
</script>

<?php require 'includes/footer.php'; ?>
