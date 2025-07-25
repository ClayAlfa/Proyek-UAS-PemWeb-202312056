<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Add Driver";
$is_admin = true;
require '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $license_number = $_POST['license_number'];
    
    $conn = getConnection();
    
    // Check if license number already exists
    $check_stmt = $conn->prepare("SELECT id FROM drivers WHERE license_number = ?");
    $check_stmt->bind_param("s", $license_number);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "License number already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO drivers (name, phone, license_number) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $license_number);
        
        if ($stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Add Driver', "Driver created: $name");
            $_SESSION['message'] = "Driver added successfully.";
            header('Location: manage_drivers.php');
            exit();
        } else {
            $error = "Error adding driver.";
        }
        $stmt->close();
    }
    $check_stmt->close();
    $conn->close();
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add New Driver</h1>
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
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="license_number">License Number</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Add Driver</button>
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
