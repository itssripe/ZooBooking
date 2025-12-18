<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Web Page</title>
    <link rel="stylesheet" href="/OSP_T2/Styles/styles.css">
</head>
<body>
    <nav id="headerbar">
        <a href="/OSP_T2/Templates/Homepage.html"><img src="/OSP_T2/Images/ZooLogo.png"></a>
        <a href="/OSP_T2/Templates/UserLoginForm.html">SignUp/Login</a>
        <a href="/OSP_T2/Templates/BookZoo.php">Book Tickets</a>
        <a href="/OSP_T2/Templates/BookHotel.php">Book Hotels</a>
        <a href="/OSP_T2/Templates/ContactUs.html">Contact Us</a>
        <a href="/OSP_T2/Templates/AccountPage.php">My Account</a>
    </nav>
    <h1>Welcome to the Account Page</h1>
    <p>This is a simple Account web page.</p>
    <p> Manage your account here.</p>
    <p>you can view your bookings here</p>


</body>

<?php
session_start();
$Username = $_SESSION['Username'];
if(isset($Username)){
    echo "Welcome, " . htmlspecialchars($Username) . "!";
} else {
    header("Location: /OSP_T2/Templates/UserLoginForm.html");
    exit();
}
?>
    <form action="/OSP_T2/Processes/UserLogOut.php" method="post">
        <button class="btnlogout" style="padding:10px 15px; color: #ffffffff; border:#f05858 4px solid; background-color: #e2697b;" type="submit">Log Out</button>
    </form> 
    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zoobooking";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $UserID = $_SESSION['UserID'];
    if (!isset($UserID)) {
        echo "<p>User ID not found in session. Please log in again.</p>";
        exit();
    }
    ?>

    <div class="ticketbox">
        <h2 class="ticketheader">Your Zoo Bookings</h2>
        <?php
        // fetch the latest booking
        $sql = "SELECT * FROM zootickets WHERE UserID = ? ORDER BY BookingID DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['UserID']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<div class='ticketinfo'>";
            echo "<p><strong>Number of Tickets:</strong> " . $_SESSION['TicketCount'] . "</p>";
            echo "<p><strong>Booking Date:</strong> " . $_SESSION['BookingDate'] . "</p>";
            echo "<p><strong>Booking ID:</strong> " . $row["BookingID"] . "</p>";
            echo "</div>";
            echo "<div class='ticketdivider'></div>";
        }
        ?>
    </div>


</html>