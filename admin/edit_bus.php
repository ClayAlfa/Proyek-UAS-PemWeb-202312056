<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Edit Bus";
$is_admin = true;
require '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_buses.php');
    exit();
}

$bus_id = $_GET['id'];

// Get bus data
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM buses WHERE id = ?");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: manage_buses.php');
    exit();
}

$bus = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plate_number = $_POST['plate_number'];
    $brand = $_POST['brand'];
    $seat_count = $_POST['seat_count'];
    $status = $_POST['status'];
    
    // Check if plate number already exists (excluding current bus)
    $check_stmt = $conn->prepare("SELECT id FROM buses WHERE plate_number = ? AND id != ?");
    $check_stmt->bind_param("si", $plate_number, $bus_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Plate number already exists!";
    } else {
        $update_stmt = $conn->prepare("UPDATE buses SET plate_number = ?, brand = ?, seat_count = ?, status = ? WHERE id = ?");
        $update_stmt->bind_param("ssisi", $plate_number, $brand, $seat_count, $status, $bus_id);
        
        if ($update_stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Edit Bus', "Bus updated: $plate_number");
            $_SESSION['message'] = "Bus updated successfully.";
            header('Location: manage_buses.php');
            exit();
        } else {
            $error = "Error updating bus.";
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
                <h1 class="h2">Edit Bus</h1>
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
                                    <input type="text" class="form-control" id="plate_number" name="plate_number" value="<?= $bus['plate_number'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <input type="text" class="form-control" id="brand" name="brand" value="<?= $bus['brand'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="seat_count">Seat Count</label>
                                    <input type="number" class="form-control" id="seat_count" name="seat_count" value="<?= $bus['seat_count'] ?>" min="1" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available" <?= $bus['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                                        <option value="maintenance" <?= $bus['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Bus</button>
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
