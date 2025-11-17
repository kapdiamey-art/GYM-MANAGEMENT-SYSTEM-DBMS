<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plan_id = $_POST['PLAN_ID'];
    $plan_name = $_POST['PLAN_NAME'];
    $description = $_POST['DESCRIPTION'];
    $duration = $_POST['DURATION_PER_WEEK'];
    $equip_id = $_POST['EQUIP_ID'];

    $sql = "INSERT INTO workout_plan (PLAN_ID, PLAN_NAME, DESCRIPTION, DURATION_PER_WEEK, EQUIP_ID)
            VALUES ('$plan_id', '$plan_name', '$description', '$duration', '$equip_id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Plan added successfully!'); window.location.href='workout_plan.html';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
