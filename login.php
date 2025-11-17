<?php
include 'db_connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo "<script>alert('Please enter both username and password.');
    window.location.href='adminlogin.html';</script>";
    exit;
}

$sql = "SELECT * FROM admin_login WHERE username=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>
        localStorage.setItem('isLoggedIn', 'true');
        alert('Welcome Admin!');
        window.location.href='home.html';
    </script>";
} else {
    echo "<script>
        alert('Invalid username or password. Please try again.');
        window.location.href='adminlogin.html';
    </script>";
}
$stmt->close();
$conn->close();
?>
