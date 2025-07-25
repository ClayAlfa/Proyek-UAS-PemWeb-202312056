<?php
// Simple script to check bookings in database
require_once 'config/database.php';

echo "<h2>Database Booking Check</h2>";

try {
    $conn = getConnection();
    
    // Check total tickets
    $result = $conn->query("SELECT COUNT(*) as total FROM tickets");
    $total = $result->fetch_assoc()['total'];
    echo "<p><strong>Total tickets in database:</strong> " . $total . "</p>";
    
    // Check total passengers
    $result = $conn->query("SELECT COUNT(*) as total FROM passengers");
    $total_passengers = $result->fetch_assoc()['total'];
    echo "<p><strong>Total passengers in database:</strong> " . $total_passengers . "</p>";
    
    // Check total transactions
    $result = $conn->query("SELECT COUNT(*) as total FROM transactions");
    $total_transactions = $result->fetch_assoc()['total'];
    echo "<p><strong>Total transactions in database:</strong> " . $total_transactions . "</p>";
    
    if ($total > 0) {
        echo "<h3>Sample Tickets:</h3>";
        $result = $conn->query("SELECT t.id, t.seat_number, t.status, p.name, p.email FROM tickets t JOIN passengers p ON t.passenger_id = p.id ORDER BY t.id DESC LIMIT 5");
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>Ticket #" . $row['id'] . ": " . $row['name'] . " (" . $row['email'] . ") - Seat " . $row['seat_number'] . " - Status: " . $row['status'] . "</li>";
        }
        echo "</ul>";
    }
    
    // Check transaction status
    if ($total_transactions > 0) {
        echo "<h3>Transaction Status:</h3>";
        $result = $conn->query("SELECT payment_status, COUNT(*) as count FROM transactions GROUP BY payment_status");
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . ucfirst($row['payment_status']) . ": " . $row['count'] . " transactions</li>";
        }
        echo "</ul>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
