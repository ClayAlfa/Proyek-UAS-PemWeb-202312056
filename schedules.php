<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// Get all schedules with route and bus information
$conn = getConnection();
$query = "SELECT s.*, r.origin, r.destination, r.distance_km, r.estimated_time, 
                 b.plate_number, b.brand, b.seat_count
          FROM schedules s
          JOIN routes r ON s.route_id = r.id
          JOIN buses b ON s.bus_id = b.id
          ORDER BY s.departure_time";

$result = $conn->query($query);

// Check if query was successful
if (!$result) {
    echo "<div class='alert alert-danger'>Database Error: " . $conn->error . "</div>";
}

$page_title = "Available Schedules";
require 'includes/header.php';
?>

<div class="container mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-4 mb-2">Available Bus Schedules</h1>
                <p class="lead text-muted">Find and book your perfect journey</p>
                <hr class="my-4">
            </div>
        </div>
    </div>
    
    <!-- Schedules Grid -->
    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($schedule = $result->fetch_assoc()): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="transition: transform 0.2s; border-radius: 15px;">
                        <!-- Route Header -->
                        <div class="card-header bg-gradient text-white text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?= $schedule['origin'] ?> 
                                <i class="fas fa-arrow-right mx-2"></i> 
                                <?= $schedule['destination'] ?>
                            </h5>
                            <small class="opacity-75"><?= $schedule['distance_km'] ?> km • <?= $schedule['estimated_time'] ?></small>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Time Information -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-center p-2" style="background-color: #f8f9fa; border-radius: 8px;">
                                        <small class="text-muted d-block">Departure</small>
                                        <strong class="text-primary"><?= date('H:i', strtotime($schedule['departure_time'])) ?></strong>
                                        <br><small><?= date('d M Y', strtotime($schedule['departure_time'])) ?></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-2" style="background-color: #f8f9fa; border-radius: 8px;">
                                        <small class="text-muted d-block">Arrival</small>
                                        <strong class="text-success"><?= date('H:i', strtotime($schedule['arrival_time'])) ?></strong>
                                        <br><small><?= date('d M Y', strtotime($schedule['arrival_time'])) ?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bus Information -->
                            <div class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h6 class="mb-1">
                                            <i class="fas fa-bus text-primary"></i> 
                                            <?= $schedule['brand'] ?>
                                        </h6>
                                        <small class="text-muted"><?= $schedule['plate_number'] ?></small>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span class="badge badge-info">
                                            <i class="fas fa-chair"></i> <?= $schedule['seat_count'] ?> seats
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price and Action -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">Starting from</small>
                                    <h4 class="text-success mb-0"><?= formatCurrency($schedule['price']) ?></h4>
                                </div>
                                <a href="booking.php?schedule_id=<?= $schedule['id'] ?>" 
                                   class="btn btn-primary btn-lg" 
                                   style="border-radius: 25px; padding: 10px 25px;">
                                    <i class="fas fa-ticket-alt"></i> Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <!-- No Schedules Available -->
        <div class="row">
            <div class="col-md-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-5x text-muted"></i>
                    </div>
                    <h3 class="text-muted">No Schedules Available</h3>
                    <p class="lead text-muted">There are currently no bus schedules available for booking.</p>
                    <p class="text-muted">Please check back later or contact our customer service for more information.</p>
                    <a href="index.php" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Custom CSS for hover effects -->
<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
}

.opacity-75 {
    opacity: 0.75;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}
</style>

<?php require 'includes/footer.php'; ?>
<?php $conn->close(); ?>
