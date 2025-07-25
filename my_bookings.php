<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
requireLogin();

$page_title = "My Bookings";
require 'includes/header.php';
?>

<style>
    .info-item {
        display: flex;
        align-items: center;
    }
    
    .booking-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .badge-lg {
        font-size: 0.9em;
        padding: 0.5rem 1rem;
    }
    
    .payment-info {
        border-left: 4px solid #28a745;
    }
</style>

<?php

// Fetch user's bookings
$conn = getConnection();

// Find bookings made by current user
// Check for both new format (with username tracking) and old format (without tracking)
$query = "SELECT t.*, tr.payment_method, tr.payment_status, tr.payment_date,
                 p.name, p.email, p.phone, s.departure_time, s.arrival_time, s.price,
                 r.origin, r.destination, b.plate_number, b.brand
          FROM tickets t
          JOIN passengers p ON t.passenger_id = p.id
          JOIN schedules s ON t.schedule_id = s.id
          JOIN routes r ON s.route_id = r.id
          JOIN buses b ON s.bus_id = b.id
          LEFT JOIN transactions tr ON tr.ticket_id = t.id
          WHERE t.status = 'booked' AND (
              p.email LIKE ? OR 
              (p.email NOT LIKE '%(booked by:%' AND p.name LIKE ?)
          )
          ORDER BY t.id DESC, s.departure_time DESC";

$stmt = $conn->prepare($query);
$search_term = "%(booked by: " . $_SESSION['username'] . ")%";
$username_pattern = "%" . $_SESSION['username'] . "%";
$stmt->bind_param("ss", $search_term, $username_pattern);
$stmt->execute();
$result = $stmt->get_result();

// Debug info
if ($result->num_rows == 0) {
    echo "<div class='alert alert-info'><strong>Debug:</strong> No bookings found for user: " . $_SESSION['username'] . "</div>";
    
    // Show all bookings to help debug
    $debug_query = "SELECT t.id, p.name, p.email FROM tickets t JOIN passengers p ON t.passenger_id = p.id WHERE t.status = 'booked' ORDER BY t.id DESC LIMIT 5";
    $debug_result = $conn->query($debug_query);
    if ($debug_result->num_rows > 0) {
        echo "<div class='alert alert-warning'><strong>Available bookings in database:</strong><ul>";
        while ($debug_row = $debug_result->fetch_assoc()) {
            echo "<li>Ticket #" . $debug_row['id'] . ": " . $debug_row['name'] . " (" . $debug_row['email'] . ")</li>";
        }
        echo "</ul>";
        echo "<p><strong>Tip:</strong> If you see your booking above but it's not showing in 'My Bookings', ";
        echo "it means the booking was made before the username tracking was implemented. ";
        echo "You can use <a href='update_existing_bookings.php'>this tool</a> to update existing bookings.</p>";
        echo "</div>";
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Bookings</h2>
                <!-- <a href="user_dashboard.php" class="btn btn-primary">Book New Ticket</a> -->
            </div>
            
            <?php if ($result->num_rows > 0): ?>
                <!-- Header Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Booking History</h5>
                    </div>
                </div>
                
                <!-- Bookings Grid -->
                <div class="row">
                    <?php while ($booking = $result->fetch_assoc()): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm booking-card" style="border-radius: 15px; transition: transform 0.2s;">
                                <!-- Card Header with Route -->
                                <div class="card-header border-0 bg-light" style="border-radius: 15px 15px 0 0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <?= $booking['origin'] ?> → <?= $booking['destination'] ?>
                                        </h5>
                                        <span class="badge badge-lg badge-<?= $booking['payment_status'] == 'paid' ? 'success' : ($booking['payment_status'] == 'pending' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($booking['payment_status'] ?? 'pending') ?>
                                        </span>
                                    </div>
                                    <small class="text-muted">Ticket #<?= $booking['id'] ?></small>
                                </div>
                                
                                <div class="card-body p-4">
                                    <!-- Time Information -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="text-center p-3" style="background-color: #e3f2fd; border-radius: 10px;">
                                                <small class="text-muted d-block">Departure</small>
                                                <strong class="text-primary"><?= date('H:i', strtotime($booking['departure_time'])) ?></strong>
                                                <br><small><?= date('d M Y', strtotime($booking['departure_time'])) ?></small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-3" style="background-color: #e8f5e8; border-radius: 10px;">
                                                <small class="text-muted d-block">Arrival</small>
                                                <strong class="text-success"><?= date('H:i', strtotime($booking['arrival_time'])) ?></strong>
                                                <br><small><?= date('d M Y', strtotime($booking['arrival_time'])) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Bus and Passenger Info -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="info-item">
                                                <i class="fas fa-bus text-primary"></i>
                                                <div class="ml-2">
                                                    <strong><?= $booking['brand'] ?></strong><br>
                                                    <small class="text-muted"><?= $booking['plate_number'] ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <i class="fas fa-chair text-warning"></i>
                                                <div class="ml-2">
                                                    <strong>Seat <?= $booking['seat_number'] ?></strong><br>
                                                    <small class="text-muted"><?= $booking['name'] ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Info -->
                                    <div class="payment-info p-3 mb-3" style="background-color: #f8f9fa; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Payment Method</small>
                                                <strong><?= $booking['payment_method'] ?? 'Not specified' ?></strong>
                                            </div>
                                            <div class="col-6 text-right">
                                                <small class="text-muted d-block">Total Price</small>
                                                <h5 class="text-success mb-0"><?= formatCurrency($booking['price']) ?></h5>
                                            </div>
                                        </div>
                                        <?php if ($booking['payment_date']): ?>
                                            <small class="text-muted">Paid on: <?= formatDate($booking['payment_date']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Print Ticket button -->
                                    <div class="text-center mt-3">
                                        <a href="print_ticket.php?ticket_id=<?= $booking['id'] ?>" class="btn btn-secondary" target="_blank">
                                            <i class="fas fa-print"></i> Print Ticket
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Card Footer -->
                                <div class="card-footer border-0 bg-light" style="border-radius: 0 0 15px 15px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Status: <?= ucfirst($booking['status']) ?>
                                        </small>
                                        <?php if ($booking['payment_status'] == 'pending'): ?>
                                            <small class="text-warning">
                                                <i class="fas fa-clock"></i> Payment Pending
                                            </small>
                                        <?php elseif ($booking['payment_status'] == 'paid'): ?>
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Confirmed
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <h4>No Bookings Found</h4>
                    <p class="text-muted">You haven't made any bookings yet. Start by browsing available schedules.</p>
                    <a href="user_dashboard.php" class="btn btn-primary">Browse Schedules</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
