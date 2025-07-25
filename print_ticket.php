<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
requireLogin();

// Get ticket ID from URL parameter
if (!isset($_GET['ticket_id']) || empty($_GET['ticket_id'])) {
    header('Location: my_bookings.php');
    exit();
}

$ticket_id = $_GET['ticket_id'];

// Get ticket details
$conn = getConnection();
$query = "SELECT t.*, tr.payment_method, tr.payment_status, tr.payment_date,
                 p.name, p.email, p.phone, s.departure_time, s.arrival_time, s.price,
                 r.origin, r.destination, r.distance_km, r.estimated_time,
                 b.plate_number, b.brand, b.seat_count
          FROM tickets t
          JOIN passengers p ON t.passenger_id = p.id
          JOIN schedules s ON t.schedule_id = s.id
          JOIN routes r ON s.route_id = r.id
          JOIN buses b ON s.bus_id = b.id
          LEFT JOIN transactions tr ON tr.ticket_id = t.id
          WHERE t.id = ? AND t.status = 'booked'";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Ticket not found!'); window.close();</script>";
    exit();
}

$ticket = $result->fetch_assoc();

// Generate QR code content (ticket verification)
$qr_content = "TICKET-" . $ticket['id'] . "-" . date('Y', strtotime($ticket['departure_time']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket - <?= $ticket['origin'] ?> to <?= $ticket['destination'] ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .ticket-container { 
                box-shadow: none !important;
                border: 2px solid #000 !important;
            }
        }
        
        .ticket-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 2px dashed #007bff;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .ticket-body {
            padding: 30px;
        }
        
        .route-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .route-point {
            text-align: center;
            flex: 1;
        }
        
        .route-arrow {
            font-size: 24px;
            color: #007bff;
            margin: 0 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        
        .info-item .label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .ticket-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 2px dashed #dee2e6;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            background: #f0f0f0;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 12px;
            color: #666;
        }
        
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Print Button -->
        <div class="text-center mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Ticket
            </button>
            <button onclick="window.close()" class="btn btn-secondary ml-2">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
        
        <!-- Ticket -->
        <div class="ticket-container">
            <!-- Status Badge -->
            <div class="status-badge status-<?= $ticket['payment_status'] ?? 'pending' ?>">
                <?= strtoupper($ticket['payment_status'] ?? 'pending') ?>
            </div>
            
            <!-- Header -->
            <div class="ticket-header">
                <h3><i class="fas fa-bus"></i> Bus Management System</h3>
                <p class="mb-0">Electronic Ticket</p>
            </div>
            
            <!-- Body -->
            <div class="ticket-body">
                <!-- Ticket Number -->
                <div class="text-center mb-4">
                    <h4 class="text-primary">Ticket #<?= $ticket['id'] ?></h4>
                    <small class="text-muted">Booking Reference: <?= $qr_content ?></small>
                </div>
                
                <!-- Route Information -->
                <div class="route-info">
                    <div class="route-point">
                        <div class="label">FROM</div>
                        <div class="value h5"><?= $ticket['origin'] ?></div>
                        <div class="text-muted">
                            <?= date('d M Y', strtotime($ticket['departure_time'])) ?><br>
                            <strong><?= date('H:i', strtotime($ticket['departure_time'])) ?></strong>
                        </div>
                    </div>
                    
                    <div class="route-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    
                    <div class="route-point">
                        <div class="label">TO</div>
                        <div class="value h5"><?= $ticket['destination'] ?></div>
                        <div class="text-muted">
                            <?= date('d M Y', strtotime($ticket['arrival_time'])) ?><br>
                            <strong><?= date('H:i', strtotime($ticket['arrival_time'])) ?></strong>
                        </div>
                    </div>
                </div>
                
                <!-- Passenger Information -->
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Passenger Name</div>
                        <div class="value"><?= $ticket['name'] ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="label">Seat Number</div>
                        <div class="value">Seat <?= $ticket['seat_number'] ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="label">Bus Information</div>
                        <div class="value"><?= $ticket['brand'] ?><br>
                            <small><?= $ticket['plate_number'] ?></small>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="label">Contact</div>
                        <div class="value"><?= $ticket['phone'] ?><br>
                            <small style="font-size: 12px;"><?= explode(' (booked by:', $ticket['email'])[0] ?></small>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="label">Journey Duration</div>
                        <div class="value"><?= $ticket['estimated_time'] ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="label">Price</div>
                        <div class="value text-success"><?= formatCurrency($ticket['price']) ?></div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <?php if ($ticket['payment_method']): ?>
                <div class="info-item" style="margin-top: 20px;">
                    <div class="label">Payment Method</div>
                    <div class="value"><?= $ticket['payment_method'] ?></div>
                    <?php if ($ticket['payment_date']): ?>
                        <small class="text-muted">Paid on: <?= date('d M Y H:i', strtotime($ticket['payment_date'])) ?></small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Footer -->
            <div class="ticket-footer">
                <!-- QR Code Placeholder -->
                <!-- <div class="qr-code">
                    QR CODE<br>
                    <small><?= $qr_content ?></small>
                </div> -->
                
                <div class="mt-3">
                    <p class="mb-1"><strong>Important Notes:</strong></p>
                    <ul class="text-left" style="font-size: 12px; list-style: none; padding-left: 0;">
                        <li>• Please arrive 15 minutes before departure</li>
                        <li>• This ticket is non-transferable</li>
                        <li>• Keep this ticket for verification</li>
                        <li>• Contact customer service for any issues</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        Printed on: <?= date('d M Y H:i') ?> | 
                        Status: <?= ucfirst($ticket['payment_status'] ?? 'pending') ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

<?php
// Utility function for currency formatting
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

$conn->close();
?>
