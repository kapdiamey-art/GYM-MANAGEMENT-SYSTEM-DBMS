<?php
include 'db_connect.php';

if (isset($_POST['trainer_id'])) {
    $trainer_id = $_POST['trainer_id'];


    $sql = "DELETE FROM trainer WHERE trainer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $trainer_id);

    if ($stmt->execute()) {
        echo "deleted successfully";
    } else {
        echo "error";
    }

    $stmt->close();
}

$conn->close();
?>
