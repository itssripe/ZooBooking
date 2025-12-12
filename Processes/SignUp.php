<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php 
//establishing connection to database
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
$HashedPassword = password_hash($Password, PASSWORD_DEFAULT);
$Email = $_POST['Email'];
$LEmail = strtolower($Email);
//Duplication handling and empty handling
$check = $conn->prepare("SELECT * FROM userdb WHERE Username = ? OR Email = ?");
$check->bind_param("ss", $Username, $Email);
$check->execute();
$results = $check->get_result();
if ($results->num_rows > 0) {
    echo "Username or Email already exists. Please try again.";
    echo "Redirecting to sign up page...";
    header("refresh:3;url=UserForm.html");
    $check->close();
    $conn->close();
    exit();
}

if (empty($Username) || empty($Password) || empty($Email)) {
    echo "cannot leave fields blank! Redirecting...";
    header("refresh:3;url=UserSignUpForm.html");
    exit();
}

// Insert new user into database
$sql = "INSERT INTO userdb (Username, Password, Email) VALUES (?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $Username, $HashedPassword, $LEmail);
if ($stmt->execute()) {
    echo "User Added Successfully";
} else {
    echo "User Failed to Add Please try Again";
    echo "Redirecting to sign up page...";
    header("refresh:3;url=/OSP_T2/Templates/UserSignUpForm.html");
}
echo "account Created for " . $Username;
echo "Redirecting to Login page...";
header("refresh:3;url=/OSP_T2/Processes/UserLogin.php");
