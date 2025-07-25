<?php
require_once 'config/database.php';

$conn = getConnection();

// Insert sample bus
$bus_query = "INSERT IGNORE INTO buses (id, plate_number, brand, seat_count, status) VALUES (1, 'B1234XY', 'Mercedes', 40, 'available')";
$conn->query($bus_query);

// Insert sample route
$route_query = "INSERT IGNORE INTO routes (id, origin, destination, distance_km, estimated_time) VALUES (1, 'Jakarta', 'Bandung', 150, '3 hours')";
$conn->query($route_query);

// Insert sample schedule
$schedule_query = "INSERT IGNORE INTO schedules (id, route_id, bus_id, departure_time, arrival_time, price) VALUES (1, 1, 1, '2024-12-15 08:00:00', '2024-12-15 11:00:00', 75000)";
$conn->query($schedule_query);

// Insert sample passenger
$passenger_query = "INSERT IGNORE INTO passengers (id, name, email, phone) VALUES (1, 'admin', 'admin@example.com', '081234567890')";
$conn->query($passenger_query);

// Insert sample ticket
$ticket_query = "INSERT IGNORE INTO tickets (id, schedule_id, passenger_id, seat_number, status) VALUES (1, 1, 1, 'A1', 'booked')";
$conn->query($ticket_query);

// Insert sample transaction
$transaction_query = "INSERT IGNORE INTO transactions (id, ticket_id, payment_method, payment_status, payment_date) VALUES (1, 1, 'Bank Transfer', 'paid', NOW())";
$conn->query($transaction_query);

echo "<div class='alert alert-success'>Sample data inserted successfully!</div>";
echo "<a href='my_bookings.php' class='btn btn-primary'>Check My Bookings</a>";

$conn->close();
?>
