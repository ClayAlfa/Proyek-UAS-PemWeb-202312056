<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

$page_title = "Bus Samarinda Lestari - Perjalanan Nyaman dan Aman";

// Fetch recent schedules (3 latest)
$conn = getConnection();
$query = "SELECT s.*, r.origin, r.destination, r.distance_km, r.estimated_time, b.plate_number, b.brand
          FROM schedules s
          JOIN routes r ON s.route_id = r.id
          JOIN buses b ON s.bus_id = b.id
          ORDER BY s.departure_time
          LIMIT 3";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><polygon points="0,0 1000,100 1000,0"/></svg>');
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .feature-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }
        
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-modern.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 30px rgba(0,0,0,0.15);
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
        
        .schedule-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .schedule-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stats-section {
            background: #f8f9fa;
            padding: 80px 0;
        }
        
        .stat-item {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 50px;
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
        }
        
        .route-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-modern">
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-4 font-weight-bold mb-4">Perjalanan Nyaman & Aman Bersama Kami</h1>
                        <p class="lead mb-4">Bus Samarinda Lestari menyediakan layanan transportasi terpercaya dengan armada modern dan layanan berkualitas tinggi untuk kenyamanan perjalanan Anda.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <?php if (isLoggedIn() && !isAdmin()): ?>
                                <a href="schedules.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-alt"></i> Lihat Jadwal
                                </a>
                                <a href="my_bookings.php" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-ticket-alt"></i> Tiket Saya
                                </a>
                            <?php else: ?>
                                <a href="schedules.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-alt"></i> Lihat Jadwal
                                </a>
                                <a href="login.php" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-bus" style="font-size: 15rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Mengapa Memilih Bus Samarinda Lestari?</h2>
            <p class="section-subtitle">Kami berkomitmen memberikan pelayanan terbaik untuk perjalanan Anda</p>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5 class="card-title">Keamanan Terjamin</h5>
                            <p class="card-text">Armada terawat dengan driver berpengalaman dan asuransi perjalanan lengkap untuk keamanan maksimal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="card-title">Tepat Waktu</h5>
                            <p class="card-text">Jadwal keberangkatan yang konsisten dan tepat waktu untuk mendukung aktivitas perjalanan Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h5 class="card-title">Pelayanan Prima</h5>
                            <p class="card-text">Staff yang ramah dan profesional siap membantu kebutuhan perjalanan Anda dengan sepenuh hati.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">10+</div>
                        <h5>Tahun Pengalaman</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <h5>Armada Bus</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <h5>Pelanggan Puas</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">25+</div>
                        <h5>Rute Tersedia</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Schedules Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Jadwal Keberangkatan Terbaru</h2>
            <p class="section-subtitle">Pilih jadwal yang sesuai dengan rencana perjalanan Anda</p>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="row">
                    <?php while ($schedule = $result->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card schedule-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="route-badge">
                                            <?= $schedule['origin'] ?> → <?= $schedule['destination'] ?>
                                        </span>
                                        <small class="text-muted"><?= $schedule['distance_km'] ?> km</small>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Keberangkatan</small>
                                            <div class="font-weight-bold"><?= date('H:i', strtotime($schedule['departure_time'])) ?></div>
                                            <small><?= date('d M Y', strtotime($schedule['departure_time'])) ?></small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Tiba</small>
                                            <div class="font-weight-bold"><?= date('H:i', strtotime($schedule['arrival_time'])) ?></div>
                                            <small><?= date('d M Y', strtotime($schedule['arrival_time'])) ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <small class="text-muted">Bus</small>
                                            <div class="font-weight-bold"><?= $schedule['brand'] ?></div>
                                            <small><?= $schedule['plate_number'] ?></small>
                                        </div>
                                        <div class="text-right">
                                            <small class="text-muted">Durasi</small>
                                            <div class="font-weight-bold"><?= $schedule['estimated_time'] ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="h5 text-success mb-0"><?= formatCurrency($schedule['price']) ?></div>
                                        <?php if (isLoggedIn() && !isAdmin()): ?>
                                            <a href="booking.php?schedule_id=<?= $schedule['id'] ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-ticket-alt"></i> Pesan
                                            </a>
                                        <?php else: ?>
                                            <a href="login.php" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-sign-in-alt"></i> Login
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="schedules.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-alt"></i> Lihat Semua Jadwal
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>Tidak Ada Jadwal Tersedia</h4>
                    <p>Saat ini belum ada jadwal keberangkatan. Silakan cek kembali nanti.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-bus"></i> Bus Samarinda Lestari</h5>
                    <p>Menyediakan layanan transportasi bus yang nyaman, aman, dan terpercaya untuk berbagai tujuan perjalanan Anda.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-phone"></i> +62 541 123456</p>
                    <p><i class="fas fa-envelope"></i> info@bussamarinda.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Raya Samarinda No. 123</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Jam Operasional</h5>
                    <p>Senin - Minggu: 06:00 - 22:00</p>
                    <p>Customer Service: 24/7</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Bus Samarinda Lestari. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        $(window).scroll(function() {
            if ($(window).scrollTop() > 50) {
                $('.navbar-modern').addClass('scrolled');
            } else {
                $('.navbar-modern').removeClass('scrolled');
            }
        });
        
        // Smooth scrolling
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                    return false;
                }
            }
        });
    </script>
</body>
</html>

<?php
// Utility functions
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatDate($datetime) {
    return date('M d, Y H:i', strtotime($datetime));
}

$conn->close();
?>
