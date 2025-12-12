<?php 
session_start();
// establishing connection to database
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

// retrieve form data
$Username = $_POST['Username'];
$Password = $_POST['Password'];
$Email = $_POST['Username'];
$LEmail = strtolower($Email);

// Check if user exists
$check = $conn-> prepare("SELECT * FROM userdb WHERE Username = ? || Email = ? AND Password = ?");
$check->bind_param("sss", $Username, $LEmail, $Password);
$check->execute();
$results = $check->get_result();
if ($results->num_rows == 0) {
    echo "Login Failed Your Email or Password is Incorrect. Please try again.";
    echo "Redirecting to login page...";
    header("refresh:3;url=/OSP_T2/Templates/UserLoginForm.html");
    $check->close();
    $conn->close();
    exit();
} else {
    $_SESSION['Username'] = $Username;
    $_SESSION['IsLoggedIn'] = true;
    echo "Login Successful. Welcome " . $Username;
    echo "Redirecting to homepage...";
    header("refresh:3;url=/OSP_T2/Templates/Homepage.html");
}
// retrieve userid 
$check = $conn->prepare("SELECT UserID FROM userdb WHERE Username = ?");
$check->bind_param("s", $Username);
$check->execute();
$results = $check->get_result();
if ($results->num_rows > 0) {
    $row = $results->fetch_assoc();
    $_SESSION['UserID'] = $row['UserID'];
    echo "UserID retrieved: " . $_SESSION['UserID'];
}
// So retrieve the userID so that the bookings can appear