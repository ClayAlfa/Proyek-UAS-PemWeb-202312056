<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Bus Management System'; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #495057;
            border-radius: 5px;
            margin: 2px 10px;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #007bff;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        .content {
            min-height: calc(100vh - 56px);
        }
        
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #667eea !important;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 10px;
            color: #333 !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #667eea !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        <?php if (isset($is_admin) && $is_admin): ?>
        /* Admin styles */
        .navbar-modern {
            background: #343a40;
        }
        .navbar-brand {
            color: white !important;
        }
        .nav-link {
            color: white !important;
        }
        
        /* Admin sidebar styles */
        .sidebar .nav-link {
            color: #495057 !important;
            font-weight: 500;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #007bff !important;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white !important;
        }
        .sidebar .nav-link i {
            color: inherit;
        }
        <?php endif; ?>
    </style>
</head>
<body>
    <?php if (isset($is_admin) && $is_admin): ?>
        <!-- Admin Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-bus"></i> Bus Management
            </a>
            
            <div class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    <?php else: ?>
        <!-- User Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-modern">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-bus"></i> Bus Samarinda Lestari
                </a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="schedules.php">Schedules</a>
                        </li>
                        <?php if (isLoggedIn()): ?>
                            <?php if (!isAdmin()): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="my_bookings.php">My Bookings</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                    <i class="fas fa-user"></i> <?= $_SESSION['username'] ?>
                                </a>
                                <div class="dropdown-menu">
                                    <?php if (isAdmin()): ?>
                                        <a class="dropdown-item" href="admin/dashboard.php">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <?php else: ?>
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="fas fa-user-edit"></i> Profile
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <a class="dropdown-item" href="logout.php">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    <?php
    // Utility functions
    function formatDate($datetime) {
        return date('M d, Y H:i', strtotime($datetime));
    }
    
    function formatCurrency($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
    ?>
