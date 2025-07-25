<?php
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .feature-card {
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-bus"></i> Bus Management System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedules.php"><i class="fas fa-calendar"></i> Schedules</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'operator'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Admin</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user_dashboard.php"><i class="fas fa-user"></i> My Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 mb-4">Welcome to Bus Management System</h1>
            <p class="lead mb-4">Book your bus tickets easily and manage your travel plans efficiently</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p class="mb-4">Welcome back, <strong><?= $_SESSION['username'] ?></strong>!</p>
                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'operator'): ?>
                    <a href="admin/dashboard.php" class="btn btn-light btn-lg mr-3">
                        <i class="fas fa-tachometer-alt"></i> Go to Admin Dashboard
                    </a>
                <?php else: ?>
                    <a href="user_dashboard.php" class="btn btn-light btn-lg mr-3">
                        <i class="fas fa-ticket-alt"></i> Book Tickets
                    </a>
                <?php endif; ?>
                <a href="schedules.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-calendar"></i> View Schedules
                </a>
            <?php else: ?>
                <a href="login.php" class="btn btn-light btn-lg mr-3">
                    <i class="fas fa-sign-in-alt"></i> Login to Book
                </a>
                <a href="schedules.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-calendar"></i> View Schedules
                </a>
            <?php endif; ?>
        </div>
    </section>
    
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <i class="fas fa-search fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Easy Search</h5>
                            <p class="card-text">Find the perfect bus schedule for your journey with our easy-to-use search system.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <i class="fas fa-ticket-alt fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Online Booking</h5>
                            <p class="card-text">Book your tickets online and secure your seat with just a few clicks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <i class="fas fa-shield-alt fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Safe & Secure</h5>
                            <p class="card-text">Your payments and personal information are protected with our secure system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>&copy; 2024 Bus Management System. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
