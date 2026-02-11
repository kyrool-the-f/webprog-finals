<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "car_rental";

$conn = mysqli_connect($host, $user, $password, $database, 3307);
 
if (!$conn) {
    die("Connection Failed: ". mysqli_connect_error());        
}
?>