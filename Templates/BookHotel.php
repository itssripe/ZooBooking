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
        <a href="/OSP_T2/Templates/UserLoginForm.html">SignUp/Login</a>
        <a href="/OSP_T2/Templates/BookZoo.php">Book Tickets</a>
        <a href="/OSP_T2/Templates/BookHotel.php">Book Hotels</a>
        <a href="/OSP_T2/Templates/ContactUs.html">Contact Us</a>
        <a href="/OSP_T2/Templates/AccountPage.php">My Account</a>
    </nav>

        <h1>Book Your Hotel</h1>
        <p>Use the buttons below to book your hotel.</p>
        <div>we accept bookings for the following 2 weeks</div>
        <div id="container"></div>
<div id="container"></div>
    <div id="container2"></div>
<div id="container2"></div>
<script>

    function handleDateClick(btn, dateString) {
        const clickedDate = new Date(dateString);

     if (!startDate) {
        startDate = clickedDate;
        endDate = null;
        }
    
    else if (!endDate) {

        if (clickedDate < startDate) {
            endDate = startDate;
            startDate = clickedDate;
     } else {
            endDate = clickedDate;
    }
}

    else {
        startDate = clickedDate;
        endDate = null;
    }

    updateSelectedDatesDisplay();


}

function updateSelectedDatesDisplay() {
    const buttons = document.querySelectorAll(".date-btn");

    buttons.forEach(btn => {
        btn.classList.remove("selected");
        btn.classList.remove("selected-range");

        const btnDate = new Date(btn.dataset.date);

        if (startDate && !endDate) {
            if (btnDate.getTime() == startDate.getTime()) {
                btn.classList.add("selected");
            }
        }

            if (startDate && endDate) {
                if (btnDate >= startDate && btnDate <= endDate) {
                    btn.classList.add("selected-range");
                }
            }
    });

    const display = document.getElementById("selectedDateDisplay");
    const booking = document.getElementById("bookingDate");

    if (startDate && endDate) {
        let from = startDate.toISOString().split("T")[0];
        let to = endDate.toISOString().split("T")[0];

        display.textContent = from + " → " + to;
        booking.value = from + " to " + to;
    } else if (startDate) {
        let from = startDate.toISOString().split("T")[0];
        display.textContent = from;
        booking.value = from;
    }

}

</script>
<script>
const container = document.getElementById("container");
// select hotel provider 4 options buttons
const hotels = ["Premier Inn", "Holiday Inn", "Travelodge", "Hilton Hotels"];
for (let i = 0; i < hotels.length; i++) {
    let hotelName = hotels[i];

    let btn = document.createElement("button");
    btn.innerText = hotelName;
    btn.classList.add("hotel-btn");
    btn.type = "button";
    btn.onclick = () => {
        document.querySelectorAll(".hotel-btn").forEach(b =>
            b.classList.remove("selected")
        );

        btn.classList.add("selected");

        document.getElementById("selectedHotelDisplay").innerText = hotelName;
        document.getElementById("bookingHotel").value = hotelName;
        document.getElementById("btnsubmit").style.display = "block";};

    container.appendChild(btn);
    }
    </script>
    <script>
const container2 = document.getElementById("container2");

for (let i = 0; i < 14; i++) {
    const date = new Date();
    date.setDate(date.getDate() + i);

    const iso = date.toISOString().split('T')[0];

    const label = date.toLocaleDateString("en-UK", {
        weekday: "short",
        month: "short",
        day: "numeric"
    });

    const btn = document.createElement("button");
    btn.innerText = label;
    btn.classList.add("date-btn");
    btn.type = "button";
    btn.dataset.iso = iso;

    btn.addEventListener("click", () => {
        document.querySelectorAll(".date-btn").forEach(b =>
            b.classList.remove("selected")
        );

        btn.classList.add("selected");

        document.getElementById("selectedDateDisplay").innerText = label;
        document.getElementById("bookingDate").value = iso;
        document.getElementById("btnsubmit").style.display = "block";
    });

    container2.appendChild(btn);
}
</script>

    <div>Select number of people:</div>
<div class='stepper'>
    <button type="button" onclick="decrement()">-</button>
    <input type="number" id="ticketCount" value="<?php echo isset($_SESSION['stepper']) ? $_SESSION['stepper'] : 1; ?>" min="1" max="10" readonly>
    <button type="button" onclick="increment()">+</button>
</div>
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
        <div> current selected hotel:
        <div id="selectedHotelDisplay">Select Hotel</div>

        <p>Selected range: <span id="selectedDateDisplay"></span></p>
<input type="hidden" id="bookingDate">

<!-- so i need to finish range selection and 