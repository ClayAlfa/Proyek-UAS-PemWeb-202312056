<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Edit User";
$is_admin = true;
require '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_users.php');
    exit();
}

$user_id = $_GET['id'];

// Get user data
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: manage_users.php');
    exit();
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    // Check if username already exists (excluding current user)
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $check_stmt->bind_param("si", $username, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        // Update user
        if (!empty($_POST['password'])) {
            // Update with new password
            // Menggunakan password plain text tanpa hash
            $password = $_POST['password'];
            $update_stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
            $update_stmt->bind_param("sssi", $username, $password, $role, $user_id);
        } else {
            // Update without changing password
            $update_stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $username, $role, $user_id);
        }
        
        if ($update_stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Edit User', "User updated: $username");
            $_SESSION['message'] = "User updated successfully.";
            header('Location: manage_users.php');
            exit();
        } else {
            $error = "Error updating user.";
        }
        $update_stmt->close();
    }
    $check_stmt->close();
}

$stmt->close();
$conn->close();
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit User</h1>
                <a href="manage_users.php" class="btn btn-secondary">Back to Users</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password (leave empty to keep current)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="operator" <?= $user['role'] == 'operator' ? 'selected' : '' ?>>Operator</option>
                                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
