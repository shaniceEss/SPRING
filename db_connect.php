<?php

// production config start
$servername = "localhost";
$username = "root";
$password = "";
$database = "house_rental_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// production config end


// developer testing config start
//$conn= new mysqli('localhost','testing_user','','house_rental_db')or die("Could not connect to mysql".mysqli_error($con));
// developer testing config end
