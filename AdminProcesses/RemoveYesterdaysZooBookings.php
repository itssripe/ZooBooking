<?php
// connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoobooking";
// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// find out yesterday's date
$yesterday = date('Y-m-d', strtotime('-1 day'));
// delete bookings from yesterday
$sql = "DELETE FROM zootickets WHERE BookingDate = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $yesterday);

if ($stmt->execute()) {
    echo "Successfully removed bookings from " . $yesterday;
} else {
    echo "Error removing bookings: " . $stmt->error;
}
?>