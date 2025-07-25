<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Add Bus";
$is_admin = true;
require '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plate_number = $_POST['plate_number'];
    $brand = $_POST['brand'];
    $seat_count = $_POST['seat_count'];
    $status = $_POST['status'];
    
    $conn = getConnection();
    
    // Check if plate number already exists
    $check_stmt = $conn->prepare("SELECT id FROM buses WHERE plate_number = ?");
    $check_stmt->bind_param("s", $plate_number);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Plate number already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO buses (plate_number, brand, seat_count, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $plate_number, $brand, $seat_count, $status);
        
        if ($stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Add Bus', "Bus created: $plate_number");
            $_SESSION['message'] = "Bus added successfully.";
            header('Location: manage_buses.php');
            exit();
        } else {
            $error = "Error adding bus.";
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
                <h1 class="h2">Add New Bus</h1>
                <a href="manage_buses.php" class="btn btn-secondary">Back to Buses</a>
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
                                    <label for="plate_number">Plate Number</label>
                                    <input type="text" class="form-control" id="plate_number" name="plate_number" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <input type="text" class="form-control" id="brand" name="brand" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="seat_count">Seat Count</label>
                                    <input type="number" class="form-control" id="seat_count" name="seat_count" min="1" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available">Available</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Add Bus</button>
                                <a href="manage_buses.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
