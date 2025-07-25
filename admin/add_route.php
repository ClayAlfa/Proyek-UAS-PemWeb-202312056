<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Add Route";
$is_admin = true;
require '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $distance_km = $_POST['distance_km'];
    $estimated_time = $_POST['estimated_time'];
    
    $conn = getConnection();
    
    // Check if route already exists
    $check_stmt = $conn->prepare("SELECT id FROM routes WHERE origin = ? AND destination = ?");
    $check_stmt->bind_param("ss", $origin, $destination);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Route already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO routes (origin, destination, distance_km, estimated_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $origin, $destination, $distance_km, $estimated_time);
        
        if ($stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Add Route', "Route created: $origin to $destination");
            $_SESSION['message'] = "Route added successfully.";
            header('Location: manage_routes.php');
            exit();
        } else {
            $error = "Error adding route.";
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
                <h1 class="h2">Add New Route</h1>
                <a href="manage_routes.php" class="btn btn-secondary">Back to Routes</a>
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
                                    <label for="origin">Origin</label>
                                    <input type="text" class="form-control" id="origin" name="origin" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <input type="text" class="form-control" id="destination" name="destination" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="distance_km">Distance (km)</label>
                                    <input type="number" class="form-control" id="distance_km" name="distance_km" min="1" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="estimated_time">Estimated Time</label>
                                    <input type="text" class="form-control" id="estimated_time" name="estimated_time" placeholder="e.g., 3 hours" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Add Route</button>
                                <a href="manage_routes.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
