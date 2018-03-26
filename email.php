<?php
 session_start();

// the message

$email = $_POST['email'];

$userName = $_SESSION["username"];

$date = $_SESSION["date"];

$time = $_SESSION["time"]; 

$room = $_SESSION["roomNumber"];
   
$building = $_SESSION["BuildingName"];

$msg = "Hi" . ' ' . $userName . ",\n" . "Booking Details:\n" . 'Building: ' . $building . "\n" . "Room Number: " . $room .  "\n" . "Date: " . $date;

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail($email,"Your Room is Booked!!",$msg);

header("Location: bookingform.php");

?>