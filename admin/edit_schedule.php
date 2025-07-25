<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Edit Schedule";
$is_admin = true;
require '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_schedules.php');
    exit();
}

$schedule_id = $_GET['id'];

// Get schedule data
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM schedules WHERE id = ?");
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: manage_schedules.php');
    exit();
}

$schedule = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_id = $_POST['route_id'];
    $bus_id = $_POST['bus_id'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];
    
    $update_stmt = $conn->prepare("UPDATE schedules SET route_id = ?, bus_id = ?, departure_time = ?, arrival_time = ?, price = ? WHERE id = ?");
    $update_stmt->bind_param("iissdi", $route_id, $bus_id, $departure_time, $arrival_time, $price, $schedule_id);
    
    if ($update_stmt->execute()) {
        logActivity($_SESSION['user_id'], 'Edit Schedule', "Schedule updated for route ID: $route_id");
        $_SESSION['message'] = "Schedule updated successfully.";
        header('Location: manage_schedules.php');
        exit();
    } else {
        $error = "Error updating schedule.";
    }
    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Schedule</h1>
                <a href="manage_schedules.php" class="btn btn-secondary">Back to Schedules</a>
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
                                    <label for="route_id">Route ID</label>
                                    <input type="number" class="form-control" id="route_id" name="route_id" value="<?= $schedule['route_id'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="bus_id">Bus ID</label>
                                    <input type="number" class="form-control" id="bus_id" name="bus_id" value="<?= $schedule['bus_id'] ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="departure_time">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" value="<?= date('Y-m-d\TH:i', strtotime($schedule['departure_time'])) ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="arrival_time">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" value="<?= date('Y-m-d\TH:i', strtotime($schedule['arrival_time'])) ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control" id="price" name="price" value="<?= $schedule['price'] ?>" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Schedule</button>
                                <a href="manage_schedules.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
