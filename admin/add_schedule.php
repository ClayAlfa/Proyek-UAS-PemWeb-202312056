<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Add Schedule";
$is_admin = true;
require '../includes/header.php';

// Get routes and buses for dropdowns
$conn = getConnection();

// Get all routes
$routes_query = "SELECT * FROM routes ORDER BY origin, destination";
$routes_result = $conn->query($routes_query);

// Get all buses
$buses_query = "SELECT * FROM buses WHERE status = 'available' ORDER BY brand, plate_number";
$buses_result = $conn->query($buses_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_id = $_POST['route_id'];
    $bus_id = $_POST['bus_id'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];
    
    // Validate that route_id and bus_id exist
    $route_check = $conn->prepare("SELECT id FROM routes WHERE id = ?");
    $route_check->bind_param("i", $route_id);
    $route_check->execute();
    $route_exists = $route_check->get_result()->num_rows > 0;
    
    $bus_check = $conn->prepare("SELECT id FROM buses WHERE id = ?");
    $bus_check->bind_param("i", $bus_id);
    $bus_check->execute();
    $bus_exists = $bus_check->get_result()->num_rows > 0;
    
    if (!$route_exists) {
        $error = "Selected route does not exist.";
    } elseif (!$bus_exists) {
        $error = "Selected bus does not exist.";
    } else {
        $stmt = $conn->prepare("INSERT INTO schedules (route_id, bus_id, departure_time, arrival_time, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissd", $route_id, $bus_id, $departure_time, $arrival_time, $price);
        
        if ($stmt->execute()) {
            logActivity($_SESSION['user_id'], 'Add Schedule', "Schedule created for route ID: $route_id");
            $_SESSION['message'] = "Schedule added successfully.";
            header('Location: manage_schedules.php');
            exit();
        } else {
            $error = "Error adding schedule: " . $stmt->error;
        }
        $stmt->close();
    }
    
    $route_check->close();
    $bus_check->close();
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add New Schedule</h1>
                <a href="manage_schedules.php" class="btn btn-secondary">Back to Schedules</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <?php 
            // Debug info - check if there are buses
            $buses_debug = $conn->query("SELECT COUNT(*) as total FROM buses");
            $buses_available = $conn->query("SELECT COUNT(*) as available FROM buses WHERE status = 'available'");
            $total_buses = $buses_debug->fetch_assoc()['total'];
            $available_buses = $buses_available->fetch_assoc()['available'];
            ?>
            <div class="alert alert-info">
                <strong>Debug Info:</strong> Total buses: <?= $total_buses ?>, Available buses: <?= $available_buses ?>
                <?php if ($available_buses == 0): ?>
                    <br><small>No buses with status 'available' found. Please check bus statuses in Manage Buses.</small>
                <?php endif; ?>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="route_id">Route</label>
                                    <select class="form-control" id="route_id" name="route_id" required>
                                        <option value="">Select a route...</option>
                                        <?php 
                                        // Reset result pointer
                                        $routes_result->data_seek(0);
                                        while ($route = $routes_result->fetch_assoc()): 
                                        ?>
                                            <option value="<?= $route['id'] ?>">
                                                <?= $route['origin'] ?> → <?= $route['destination'] ?> 
                                                (<?= $route['distance_km'] ?>km, <?= $route['estimated_time'] ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="bus_id">Bus</label>
                                    <select class="form-control" id="bus_id" name="bus_id" required>
                                        <option value="">Select a bus...</option>
                                        <?php 
                                        // Reset result pointer
                                        $buses_result->data_seek(0);
                                        while ($bus = $buses_result->fetch_assoc()): 
                                        ?>
                                            <option value="<?= $bus['id'] ?>">
                                                <?= $bus['brand'] ?> - <?= $bus['plate_number'] ?> 
                                                (<?= $bus['seat_count'] ?> seats)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="departure_time">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="arrival_time">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control" id="price" name="price" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Add Schedule</button>
                                <a href="manage_schedules.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
// Auto-calculate arrival time based on departure time and route estimated time
function updateArrivalTime() {
    const routeSelect = document.getElementById('route_id');
    const departureTime = document.getElementById('departure_time').value;
    const arrivalTimeField = document.getElementById('arrival_time');
    
    if (routeSelect.value && departureTime) {
        const selectedOption = routeSelect.options[routeSelect.selectedIndex];
        const routeText = selectedOption.text;
        
        // Extract estimated time from the route text
        const estimatedTimeMatch = routeText.match(/([0-9.]+)\s*(jam|hour)/i);
        if (estimatedTimeMatch) {
            const hours = parseFloat(estimatedTimeMatch[1]);
            const departureDate = new Date(departureTime);
            const arrivalDate = new Date(departureDate.getTime() + (hours * 60 * 60 * 1000));
            
            // Format the date for datetime-local input
            const year = arrivalDate.getFullYear();
            const month = String(arrivalDate.getMonth() + 1).padStart(2, '0');
            const day = String(arrivalDate.getDate()).padStart(2, '0');
            const hour = String(arrivalDate.getHours()).padStart(2, '0');
            const minute = String(arrivalDate.getMinutes()).padStart(2, '0');
            
            arrivalTimeField.value = `${year}-${month}-${day}T${hour}:${minute}`;
        }
    }
}

// Add event listeners
document.getElementById('route_id').addEventListener('change', updateArrivalTime);
document.getElementById('departure_time').addEventListener('change', updateArrivalTime);

// Set minimum datetime to now
const now = new Date();
const minDateTime = now.toISOString().slice(0, 16);
document.getElementById('departure_time').min = minDateTime;
</script>

<?php require '../includes/footer.php'; ?>
