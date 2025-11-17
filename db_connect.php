<?php
$host = "localhost"; 
$user = "root";      
$pass = "";       
$db   = "gym_website";   

$conn = new mysqli($host, $user, $pass, $db,3307);

if ($conn->connect_error) {
    die(" Database Connection Failed: " . $conn->connect_error);
}
?>
