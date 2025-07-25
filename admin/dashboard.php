<?php
require_once '../includes/auth.php';
requireAdmin();

$page_title = "Admin Dashboard";
$is_admin = true;

require '../includes/header.php';
?>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_users.php">
                                <i class="fas fa-users"></i>
                                Manage Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_buses.php">
                                <i class="fas fa-bus"></i>
                                Manage Buses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_routes.php">
                                <i class="fas fa-route"></i>
                                Manage Routes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_schedules.php">
                                <i class="fas fa-calendar-alt"></i>
                                Manage Schedules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_transactions.php">
                                <i class="fas fa-receipt"></i>
                                Manage Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user_activity.php">
                                <i class="fas fa-clipboard-list"></i>
                                User Activity
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Dashboard Content -->
                <h4>Welcome, <?php echo $_SESSION['username']; ?>!</h4>
                <p>Select a module from the sidebar to manage.</p>

            </main>
        </div>
    </div>
<?php
require '../includes/footer.php';
?>
