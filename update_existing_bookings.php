<?php
// Script to update existing bookings with username tracking
require_once 'config/database.php';

echo "<h2>Update Existing Bookings</h2>";

try {
    $conn = getConnection();
    
    // Get all existing bookings without username tracking
    $result = $conn->query("SELECT p.id, p.email FROM passengers p WHERE p.email NOT LIKE '%(booked by:%'");
    
    if ($result->num_rows > 0) {
        echo "<p>Found " . $result->num_rows . " bookings without username tracking.</p>";
        
        // For demonstration, we'll assume these were booked by 'admin' user
        // In real scenario, you'd need to determine the actual user
        $default_username = 'admin'; // Change this to the actual username who made the booking
        
        echo "<form method='POST'>";
        echo "<p>Update bookings to be tracked under username: <input type='text' name='username' value='" . $default_username . "' required></p>";
        echo "<input type='submit' name='update' value='Update Bookings' class='btn btn-primary'>";
        echo "</form>";
        
        if (isset($_POST['update'])) {
            $username = $_POST['username'];
            
            // Update existing passenger emails
            $update_query = "UPDATE passengers SET email = CONCAT(email, ' (booked by: ', ?, ')') WHERE email NOT LIKE '%(booked by:%'";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("s", $username);
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>✓ Successfully updated " . $stmt->affected_rows . " booking(s)!</p>";
                echo "<p><a href='my_bookings.php'>Check My Bookings</a> | <a href='check_bookings.php'>Check Database</a></p>";
            } else {
                echo "<p style='color: red;'>Error updating bookings: " . $stmt->error . "</p>";
            }
            
            $stmt->close();
        }
    } else {
        echo "<p>All bookings already have username tracking.</p>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
    .btn { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background: #0056b3; }
</style>
