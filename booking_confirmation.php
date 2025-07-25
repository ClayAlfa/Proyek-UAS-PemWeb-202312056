<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// Check if booking was successful
if (!isset($_SESSION['booking_success']) || !isset($_SESSION['ticket_id'])) {
    header('Location: schedules.php');
    exit();
}

$ticket_id = $_SESSION['ticket_id'];

// Get ticket details
$conn = getConnection();
$query = "SELECT t.*, p.name, p.email, p.phone, s.departure_time, s.arrival_time, s.price,
                 r.origin, r.destination, b.plate_number, b.brand, tr.payment_method, tr.payment_status
          FROM tickets t
          JOIN passengers p ON t.passenger_id = p.id
          JOIN schedules s ON t.schedule_id = s.id
          JOIN routes r ON s.route_id = r.id
          JOIN buses b ON s.bus_id = b.id
          JOIN transactions tr ON tr.ticket_id = t.id
          WHERE t.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: schedules.php');
    exit();
}

$ticket = $result->fetch_assoc();

// Clear session
unset($_SESSION['booking_success']);
unset($_SESSION['ticket_id']);

$page_title = "Booking Confirmation";
require 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success text-center">
                <h4><i class="fas fa-check-circle"></i> Booking Successful!</h4>
                <p>Your ticket has been booked successfully. Please keep this information for your records.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Ticket Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Passenger Information</h6>
                            <p><strong>Name:</strong> <?= $ticket['name'] ?></p>
                            <p><strong>Email:</strong> <?= $ticket['email'] ?></p>
                            <p><strong>Phone:</strong> <?= $ticket['phone'] ?></p>
                            <p><strong>Seat Number:</strong> <?= $ticket['seat_number'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Trip Information</h6>
                            <p><strong>Route:</strong> <?= $ticket['origin'] ?> → <?= $ticket['destination'] ?></p>
                            <p><strong>Departure:</strong> <?= formatDate($ticket['departure_time']) ?></p>
                            <p><strong>Arrival:</strong> <?= formatDate($ticket['arrival_time']) ?></p>
                            <p><strong>Bus:</strong> <?= $ticket['brand'] ?> - <?= $ticket['plate_number'] ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Payment Information</h6>
                            <p><strong>Payment Method:</strong> <?= $ticket['payment_method'] ?></p>
                            <p><strong>Payment Status:</strong> 
                                <span class="badge badge-<?= $ticket['payment_status'] == 'paid' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($ticket['payment_status']) ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Pricing</h6>
                            <p><strong>Ticket Price:</strong> <?= formatCurrency($ticket['price']) ?></p>
                            <p><strong>Total:</strong> <span class="text-success"><?= formatCurrency($ticket['price']) ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($ticket['payment_status'] == 'pending'): ?>
                <div class="alert alert-warning mt-3">
                    <h6><i class="fas fa-exclamation-triangle"></i> Payment Pending</h6>
                    <p>Your payment is still pending. Please complete your payment using the selected method. 
                       Contact our customer service for assistance.</p>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="schedules.php" class="btn btn-primary">Book Another Ticket</a>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
                <button onclick="window.print()" class="btn btn-outline-primary">Print Ticket</button>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
<?php $conn->close(); ?>
