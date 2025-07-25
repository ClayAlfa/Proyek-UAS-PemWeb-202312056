<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

// Check if schedule_id is provided
if (!isset($_GET['schedule_id'])) {
    header('Location: schedules.php');
    exit();
}

$schedule_id = $_GET['schedule_id'];

// Get schedule details
$conn = getConnection();
$query = "SELECT s.*, r.origin, r.destination, r.distance_km, r.estimated_time, 
                 b.plate_number, b.brand, b.seat_count
          FROM schedules s
          JOIN routes r ON s.route_id = r.id
          JOIN buses b ON s.bus_id = b.id
          WHERE s.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: schedules.php');
    exit();
}

$schedule = $result->fetch_assoc();

// Get booked seats
$booked_seats_query = "SELECT seat_number FROM tickets WHERE schedule_id = ? AND status = 'booked'";
$stmt = $conn->prepare($booked_seats_query);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$booked_result = $stmt->get_result();
$booked_seats = [];
while ($row = $booked_result->fetch_assoc()) {
    $booked_seats[] = $row['seat_number'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $seat_number = $_POST['seat_number'];
    $payment_method = $_POST['payment_method'];
    
    // Always create new passenger record with username tracking
    // This ensures we can track who made the booking
    $tracking_email = $email . ' (booked by: ' . $_SESSION['username'] . ')';
    $stmt = $conn->prepare("INSERT INTO passengers (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $tracking_email, $phone);
    $stmt->execute();
    $passenger_id = $conn->insert_id;
    
    // Insert ticket
    $stmt = $conn->prepare("INSERT INTO tickets (schedule_id, passenger_id, seat_number, status) VALUES (?, ?, ?, 'booked')");
    $stmt->bind_param("iis", $schedule_id, $passenger_id, $seat_number);
    $stmt->execute();
    $ticket_id = $conn->insert_id;
    
    // Insert transaction
    $stmt = $conn->prepare("INSERT INTO transactions (ticket_id, payment_method, payment_status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("is", $ticket_id, $payment_method);
    $stmt->execute();
    
    // Log activity
    logActivity(null, 'Booking', "Ticket booked for seat $seat_number on schedule $schedule_id");
    
    $_SESSION['booking_success'] = true;
    $_SESSION['ticket_id'] = $ticket_id;
    header('Location: booking_confirmation.php');
    exit();
}

$page_title = "Book Ticket";
require 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2>Book Your Ticket</h2>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Trip Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Route:</strong> <?= $schedule['origin'] ?> → <?= $schedule['destination'] ?></p>
                            <p><strong>Departure:</strong> <?= formatDate($schedule['departure_time']) ?></p>
                            <p><strong>Arrival:</strong> <?= formatDate($schedule['arrival_time']) ?></p>
                            <p><strong>Duration:</strong> <?= $schedule['estimated_time'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Bus:</strong> <?= $schedule['brand'] ?> - <?= $schedule['plate_number'] ?></p>
                            <p><strong>Total Seats:</strong> <?= $schedule['seat_count'] ?></p>
                            <p><strong>Price:</strong> <span class="text-success"><?= formatCurrency($schedule['price']) ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" id="bookingForm">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Passenger Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Select Seat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php for ($i = 1; $i <= $schedule['seat_count']; $i++): ?>
                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="seat_number" id="seat<?= $i ?>" 
                                               value="<?= $i ?>" <?= in_array($i, $booked_seats) ? 'disabled' : '' ?> required>
                                        <label class="form-check-label <?= in_array($i, $booked_seats) ? 'text-danger' : '' ?>" for="seat<?= $i ?>">
                                            Seat <?= $i ?> <?= in_array($i, $booked_seats) ? '(Booked)' : '' ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="Bank Transfer" required>
                            <label class="form-check-label" for="bank_transfer">Bank Transfer</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="Credit Card" required>
                            <label class="form-check-label" for="credit_card">Credit Card</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="Cash" required>
                            <label class="form-check-label" for="cash">Cash</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Book Ticket</button>
                <a href="schedules.php" class="btn btn-secondary btn-lg">Back to Schedules</a>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Booking Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Route:</strong> <?= $schedule['origin'] ?> → <?= $schedule['destination'] ?></p>
                    <p><strong>Date:</strong> <?= formatDate($schedule['departure_time']) ?></p>
                    <p><strong>Price:</strong> <?= formatCurrency($schedule['price']) ?></p>
                    <hr>
                    <p><strong>Total:</strong> <span class="text-success"><?= formatCurrency($schedule['price']) ?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
<?php $conn->close(); ?>
