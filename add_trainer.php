<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $result = $conn->query("SELECT COUNT(*) AS count FROM trainer");
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
    
        $conn->query("ALTER TABLE trainer AUTO_INCREMENT = 1");
    }

    $mem_id = !empty($_POST['member_id']) ? $_POST['member_id'] : NULL;
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $specialization = $_POST['specialization'];
    $experience = $_POST['experience'];

    
    $sql = "INSERT INTO trainer (name, contact, specialization, experience)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $contact, $specialization, $experience);

    if ($stmt->execute()) {
        $trainer_id = $stmt->insert_id;

        echo "<script>
            alert('Trainer Added Successfully! Trainer ID: $trainer_id');
            window.location.href = 'membership.html';
        </script>";
    } else {
        echo "<script>
            alert('Error adding trainer: " . addslashes($stmt->error) . "');
            window.history.back();
        </script>";
    }

    $stmt->close();
}
$conn->close();
?>
