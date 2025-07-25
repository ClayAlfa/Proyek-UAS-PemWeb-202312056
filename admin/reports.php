<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Reports";
$is_admin = true;
require '../includes/header.php';

// Get statistics
$conn = getConnection();

// Total users
$users_result = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $users_result->fetch_assoc()['total'];

// Total buses
$buses_result = $conn->query("SELECT COUNT(*) as total FROM buses");
$total_buses = $buses_result->fetch_assoc()['total'];

// Total routes
$routes_result = $conn->query("SELECT COUNT(*) as total FROM routes");
$total_routes = $routes_result->fetch_assoc()['total'];

// Total tickets
$tickets_result = $conn->query("SELECT COUNT(*) as total FROM tickets");
$total_tickets = $tickets_result->fetch_assoc()['total'];

// Total revenue
$revenue_result = $conn->query("SELECT SUM(s.price) as total FROM transactions tr 
                                JOIN tickets t ON tr.ticket_id = t.id 
                                JOIN schedules s ON t.schedule_id = s.id 
                                WHERE tr.payment_status = 'paid'");
$total_revenue = $revenue_result->fetch_assoc()['total'] ?? 0;

// Monthly bookings
$monthly_bookings = $conn->query("SELECT DATE_FORMAT(tr.payment_date, '%Y-%m') as month, 
                                        COUNT(*) as bookings, 
                                        SUM(s.price) as revenue
                                 FROM transactions tr
                                 JOIN tickets t ON tr.ticket_id = t.id
                                 JOIN schedules s ON t.schedule_id = s.id
                                 WHERE tr.payment_status = 'paid'
                                 GROUP BY DATE_FORMAT(tr.payment_date, '%Y-%m')
                                 ORDER BY month DESC
                                 LIMIT 12");

// Top routes
$top_routes = $conn->query("SELECT r.origin, r.destination, COUNT(*) as bookings
                           FROM tickets t
                           JOIN schedules s ON t.schedule_id = s.id
                           JOIN routes r ON s.route_id = r.id
                           WHERE t.status = 'booked'
                           GROUP BY r.id
                           ORDER BY bookings DESC
                           LIMIT 5");
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reports & Analytics</h1>
                <button onclick="window.print()" class="btn btn-primary">Print Report</button>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2><?= $total_users ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Buses</h5>
                            <h2><?= $total_buses ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Routes</h5>
                            <h2><?= $total_routes ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Tickets</h5>
                            <h2><?= $total_tickets ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Revenue</h5>
                            <h2><?= formatCurrency($total_revenue) ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Bookings -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Monthly Bookings</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Bookings</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($monthly = $monthly_bookings->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $monthly['month'] ?></td>
                                            <td><?= $monthly['bookings'] ?></td>
                                            <td><?= formatCurrency($monthly['revenue']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Routes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Top Routes</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Route</th>
                                        <th>Bookings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($route = $top_routes->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $route['origin'] ?> → <?= $route['destination'] ?></td>
                                            <td><?= $route['bookings'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php
require '../includes/footer.php';
?>
