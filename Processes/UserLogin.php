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
$check = $conn-> prepare("SELECT * FROM userdb WHERE Username = ? || Email = ? AND Password = ?"); //SQL query to check user exists
$check->bind_param("sss", $Username, $LEmail, $Password); //bind parameters
$check->execute();
$results = $check->get_result(); // check results
if ($results->num_rows == 0) { // no user found
    echo "Login Failed Your Email or Password is Incorrect. Please try again.";
    echo "Redirecting to login page...";
    header("refresh:3;url=/OSP_T2/Templates/UserLoginForm.html");
    $check->close();
    $conn->close(); // Close connections
    exit();
} else { //user found
    $_SESSION['Username'] = $Username; // set session Username for user to be logged in across pages
    echo "Login Successful. Welcome " . $Username;
    echo "Redirecting to homepage...";
    header("refresh:3;url=/OSP_T2/Templates/Homepage.html");
}
// retrieve userid 
$check = $conn->prepare("SELECT UserID FROM userdb WHERE Username = ?"); //SQL query to get UserID
$check->bind_param("s", $Username);
$check->execute();
$results = $check->get_result();
if ($results->num_rows > 0) { // user found (should always be true here)
    $row = $results->fetch_assoc();
    $_SESSION['UserID'] = $row['UserID']; // set session UserID
    echo "UserID retrieved: " . $_SESSION['UserID'];
}
// So retrieve the userID so that the bookings can appear