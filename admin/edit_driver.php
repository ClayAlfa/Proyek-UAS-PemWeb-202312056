<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Edit Driver";
$is_admin = true;
require '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_drivers.php');
    exit();
}

$driver_id = $_GET['id'];

// Get driver data
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM drivers WHERE id = ?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: manage_drivers.php');
    exit();
}

$driver = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $license_number = $_POST['license_number'];
    
    // Check if license number already exists (excluding current driver)
    $check_stmt = $conn->prepare("SELECT id FROM drivers WHERE license_number = ? AND id != ?");
    $check_stmt->bind_param("si", $license_number, $driver_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "License number already exists!";
    } else {
        $update_stmt = $conn->prepare("UPDATE drivers SET name = ?, phone = ?, license_number = ? WHERE id = ?");
        $update_stmt->bind_param("sssi", $name, $phone, $license_number, $driver_id);
        
        if ($update_stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Edit Driver', "Driver updated: $name");
            $_SESSION['message'] = "Driver updated successfully.";
            header('Location: manage_drivers.php');
            exit();
        } else {
            $error = "Error updating driver.";
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
                <h1 class="h2">Edit Driver</h1>
                <a href="manage_drivers.php" class="btn btn-secondary">Back to Drivers</a>
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
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $driver['name'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $driver['phone'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="license_number">License Number</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number" value="<?= $driver['license_number'] ?>" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Driver</button>
                                <a href="manage_drivers.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
