<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset("UTF-8")>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zoo Booking System - Book a Visit</title>
        <link rel="stylesheet" href="/OSP_T2/Styles/styles.css">
    </head>
    <body>
    <nav id="headerbar">
        <a href="/OSP_T2/Templates/Homepage.html"><img src="/OSP_T2/Images/ZooLogo.png"></a>
        <a href="/OSP_T2/Templates/UserLoginForm">SignUp/Login</a>
        <a href="/OSP_T2/Templates/BookZoo.php">Book Tickets</a>
        <a href="/OSP_T2/Templates/BookHotel.php">Book Hotels</a>
        <a href="/OSP_T2/Templates/ContactUs.html">Contact Us</a>
        <a href="/OSP_T2/Templates/AccountPage.php">My Account</a>
    </nav>

        <h1>Book Your Zoo Visit</h1>
        <p>Use the buttons below to book your date to visit the zoo.</p>
        <div>we accept bookings for the following 2 weeks</div>
        <div id="container"></div>



<script>
const container = document.getElementById("container");

for (let i = 0; i < 14; i++) {
    let date = new Date();
    date.setDate(date.getDate() + i);

    let formatted = date.toISOString().split('T')[0];

    let btn = document.createElement("button");
    btn.innerText = formatted;
    btn.classList.add("date-btn");
    btn.type = "button";
    btn.onclick = () => {
        document.querySelectorAll(".date-btn").forEach(b =>
            b.classList.remove("selected")
        );

        btn.classList.add("selected");

        document.getElementById("selectedDateDisplay").innerText = formatted;
        document.getElementById("bookingDate").value = formatted;
        document.getElementById("btnsubmit").style.display = "block";};

    container.appendChild(btn);
    }
    </script>

<div>Select number of tickets:</div>
<div class='stepper'>
    <button type="button" onclick="decrement()">-</button>
    <input type="number" id="ticketCount" value="<?php echo isset($_SESSION['stepper']) ? $_SESSION['stepper'] : 1; ?>" min="1" max="10" readonly>
    <button type="button" onclick="increment()">+</button>
</div>
        <div> current selected date:
        <div id="selectedDateDisplay">YYYY-MM-DD</div>
<form type="hidden" id="dateForm" method="post" action="/OSP_T2/Templates/BookZoo.php">
    <input type="hidden" name="bookingDate" id="bookingDate">
    <input type="hidden" name="ticketCount" id="ticketCountInput" value="<?php echo isset($_SESSION['stepper']) ? $_SESSION['stepper'] : 1; ?>">
    <button id="btnsubmit" type="submit">Book Now</button>
</form>
<script>
const ticketInput = document.getElementById("ticketCount");
const hiddenInput = document.getElementById("ticketCountInput");

hiddenInput.value = ticketInput.value;

function increment() {
    let currentValue = parseInt(ticketInput.value);
    if (currentValue < 10) {
        currentValue++;
        ticketInput.value = currentValue;
        hiddenInput.value = currentValue; 
    }
}

function decrement() {
    let currentValue = parseInt(ticketInput.value);
    if (currentValue > 1) {
        currentValue--;
        ticketInput.value = currentValue;
        hiddenInput.value = currentValue;
}
}
</script>

    </body>

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
if(isset($_POST['bookingDate'])) {
    $_SESSION['BookingDate'] = $_POST['bookingDate'];
} else {
    exit();
}
if(isset($_POST['ticketCount'])) {
    $_SESSION['TicketCount'] = $_POST['ticketCount'];
} else {
    $_SESSION['TicketCount'] = 1; // Default to 1 ticket if not set
}

// retrieve userid from userdb
if(isset($_SESSION['Username'])) {
    $Username = $_SESSION['Username'];
} else {
    echo "User not logged in.";
    exit();
}
$Username = $_SESSION['Username'];
$check = $conn->prepare("SELECT UserID FROM userdb WHERE Username = ?");
$check->bind_param("s", $Username);
$check->execute();
$results = $check->get_result();
if ($results->num_rows > 0) {
    $row = $results->fetch_assoc();
    $_SESSION['UserID'] = $row['UserID'];
} else {
    echo "Error retrieving UserID. Please ensure you are logged in.";
    exit();
}

$CurrentDate = date("Y-m-d");
$sql = "INSERT INTO zootickets (UserID, BookingDate, VisitDate, Amount)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isss",
    $_SESSION['UserID'],
    $_SESSION['BookingDate'],
    $CurrentDate,
    $_SESSION['TicketCount']
);
if ($stmt->execute()) {
    echo "Booking Successful for " . $_SESSION['Username'];
    header("refresh:3;url=/OSP_T2/Templates/Homepage.html");
} else {
    echo "Booking Failed. Please try again.";
    header("refresh:3;url=/OSP_T2/Templates/BookZoo.php");
    exit();
}
?>

