<?php 
// Start the session
session_start();

// Add database file
require_once('Placement_portal.php');

// SQL query to select all users
$sql = "SELECT * FROM users";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each row
    while ($row = $result->fetch_assoc()) {
        // Echo the Name column from the users table
        echo $row['Name'];
    }
} else {
    echo "No records found";
}
?>
