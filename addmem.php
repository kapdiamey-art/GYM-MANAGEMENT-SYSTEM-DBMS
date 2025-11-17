<?php
include 'db_connect.php';

$result = $conn->query("SELECT COUNT(*) as total FROM member");
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("ALTER TABLE member AUTO_INCREMENT = 1");
}


$name = $_POST['name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$contact = $_POST['contact'];
$membership_type = $_POST['membership_type'];
$start_date = $_POST['start_date'];
$duration = $_POST['duration'];


$trainer_id = ($_POST['trainer_id'] !== "") ? $_POST['trainer_id'] : 0;
$workout_id = ($_POST['workout_id'] !== "") ? $_POST['workout_id'] : 0;
$diet_id    = ($_POST['diet_id'] !== "") ? $_POST['diet_id'] : 0;


header("Location: payment.php?name=$name&age=$age&gender=$gender&contact=$contact&membership_type=$membership_type&start_date=$start_date&duration=$duration&trainer_id=$trainer_id&workout_id=$workout_id&diet_id=$diet_id");
exit();
?>
