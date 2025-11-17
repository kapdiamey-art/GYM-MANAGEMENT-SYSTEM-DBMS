<?php
include 'db_connect.php';
$memid = $_POST['mem_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO feedback VALUES ('$memid','$name', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Thank you for your feedback!'); window.location.href='contact.html';</script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
