<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>" href="manage_users.php">
                    <i class="fas fa-users"></i>
                    Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_buses.php' ? 'active' : '' ?>" href="manage_buses.php">
                    <i class="fas fa-bus"></i>
                    Manage Buses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_routes.php' ? 'active' : '' ?>" href="manage_routes.php">
                    <i class="fas fa-route"></i>
                    Manage Routes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_drivers.php' ? 'active' : '' ?>" href="manage_drivers.php">
                    <i class="fas fa-id-card"></i>
                    Manage Drivers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_schedules.php' ? 'active' : '' ?>" href="manage_schedules.php">
                    <i class="fas fa-calendar-alt"></i>
                    Manage Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_transactions.php' ? 'active' : '' ?>" href="manage_transactions.php">
                    <i class="fas fa-receipt"></i>
                    Manage Transactions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_activity.php' ? 'active' : '' ?>" href="user_activity.php">
                    <i class="fas fa-clipboard-list"></i>
                    User Activity
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '' ?>" href="reports.php">
                    <i class="fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
        </ul>
    </div>
</nav>
