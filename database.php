<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "your_db_name"; // find the sample SQL in the db folder

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>