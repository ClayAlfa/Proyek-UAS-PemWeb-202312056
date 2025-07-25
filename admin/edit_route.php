<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Edit Route";
$is_admin = true;
require '../includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: manage_routes.php');
    exit();
}

$route_id = $_GET['id'];
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM routes WHERE id = ?");
$stmt->bind_param("i", $route_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: manage_routes.php');
    exit();
}

$route = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $distance_km = $_POST['distance_km'];
    $estimated_time = $_POST['estimated_time'];
    
    $check_stmt = $conn->prepare("SELECT id FROM routes WHERE origin = ? AND destination = ? AND id != ?");
    $check_stmt->bind_param("ssi", $origin, $destination, $route_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Route already exists!";
    } else {
        $update_stmt = $conn->prepare("UPDATE routes SET origin = ?, destination = ?, distance_km = ?, estimated_time = ? WHERE id = ?");
        $update_stmt->bind_param("ssisi", $origin, $destination, $distance_km, $estimated_time, $route_id);
        
        if ($update_stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Edit Route', "Route updated: $origin to $destination");
            $_SESSION['message'] = "Route updated successfully.";
            header('Location: manage_routes.php');
            exit();
        } else {
            $error = "Error updating route.";
        }
        $update_stmt->close();
    }
    $check_stmt->close();
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Route</h1>
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
                                    <input type="text" class="form-control" id="origin" name="origin" value="<?= $route['origin'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <input type="text" class="form-control" id="destination" name="destination" value="<?= $route['destination'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="distance_km">Distance (km)</label>
                                    <input type="number" class="form-control" id="distance_km" name="distance_km" value="<?= $route['distance_km'] ?>" min="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="estimated_time">Estimated Time</label>
                                    <input type="text" class="form-control" id="estimated_time" name="estimated_time" value="<?= $route['estimated_time'] ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Route</button>
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
