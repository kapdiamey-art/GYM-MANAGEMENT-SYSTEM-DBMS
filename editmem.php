<?php
include 'db_connect.php';
$message = "";
$record = null;
$type = "";
$result = null;


if (isset($_POST['fetch_record'])) {
    if (!empty($_POST['mem_id'])) {
        $id = $_POST['mem_id'];
        $result = $conn->query("SELECT * FROM member WHERE mem_id='$id'");
        $type = "member";
    } elseif (!empty($_POST['trainer_id'])) {
        $id = $_POST['trainer_id'];
        $result = $conn->query("SELECT * FROM trainer WHERE trainer_id='$id'");
        $type = "trainer";
    }

    if ($result && $result->num_rows > 0) {
        $record = $result->fetch_assoc();
    } else {
        $message = ucfirst($type)." not found!";
        $record = null;
    }
}

// Update record
if (isset($_POST['update_record'])) {
    if ($_POST['type'] == "member") {
        $mem_id = $_POST['mem_id'];
        $membership_id = $_POST['membership_id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $contact = $_POST['contact'];
        $membership_type = $_POST['membership_type'];
        $start_date = $_POST['start_date'];
        $duration = $_POST['duration'];

        $sql = "UPDATE member SET 
                membership_id='$membership_id', 
                name='$name', 
                age='$age', 
                gender='$gender', 
                contact='$contact', 
                membership_type='$membership_type', 
                start_date='$start_date', 
                duration='$duration' 
                WHERE mem_id='$mem_id'";
        $type = "member";
        $id = $mem_id;
    } else {
        $trainer_id = $_POST['trainer_id'];
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $specialization = $_POST['specialization'];
        $experience = $_POST['experience'];

        $sql = "UPDATE trainer SET 
                name='$name', 
                contact='$contact', 
                specialization='$specialization', 
                experience='$experience' 
                WHERE trainer_id='$trainer_id'";
        $type = "trainer";
        $id = $trainer_id;
    }

    if ($conn->query($sql) === TRUE) {
        $message = ucfirst($type)." details updated successfully!";
        
        if ($type=="member") {
            $result = $conn->query("SELECT * FROM member WHERE mem_id='$id'");
        } else {
            $result = $conn->query("SELECT * FROM trainer WHERE trainer_id='$id'");
        }
        $record = $result->fetch_assoc();
    } else {
        $message = "Error updating ".$type.": " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Member / Trainer</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('back.jpg') no-repeat center center fixed;
    background-size: cover;
    color: white;
    text-align: center;
    padding: 20px;
}
h1 { color:#00ffcc; margin-bottom:20px; }
.back-btn {
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 10px 15px;
    background-color: #ff4757;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
.back-btn:hover {
    background-color: #c0392b;
}

.container {
    background: rgba(0,0,0,0.6);
    padding:20px;
    border-radius:10px;
    width: 500px;
    margin:auto;
}
input[type="text"], input[type="number"], input[type="date"], select {
    width:90%;
    padding:10px;
    margin:8px 0;
    border-radius:5px;
    border:none;
}
input[type="submit"] {
    padding:10px 25px;
    margin-top:10px;
    background-color:#28a745;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-weight:bold;
}
input[type="submit"]:hover { background-color:#218838; }
.message { margin:10px 0; color:#ffcc00; font-weight:bold; }
</style>
</head>
<body>
<button class="back-btn" onclick="window.location.href='membership.html'">⬅ Go Back</button>
<h1>Edit Member / Trainer</h1>

<div class="container">
 
    <form method="POST">
        <input type="text" name="mem_id" placeholder="Enter Member ID">
        <input type="text" name="trainer_id" placeholder="Enter Trainer ID">
        <input type="submit" name="fetch_record" value="Fetch Details">
    </form>

    <div class="message"><?php echo $message; ?></div>

   
    <?php if ($record): ?>
        <?php if($type=="member"): ?>
        <form method="POST">
            <input type="hidden" name="type" value="member">
            <input type="hidden" name="mem_id" value="<?php echo $record['mem_id']; ?>">
            <input type="text" name="membership_id" placeholder="Membership ID" value="<?php echo $record['membership_id']; ?>" required><br>
            <input type="text" name="name" placeholder="Name" value="<?php echo $record['name']; ?>" required><br>
            <input type="number" name="age" placeholder="Age" value="<?php echo $record['age']; ?>" required><br>
            <select name="gender" required>
                <option value="Male" <?php if($record['gender']=='Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($record['gender']=='Female') echo 'selected'; ?>>Female</option>
            </select><br>
            <input type="number" name="contact" placeholder="Contact" value="<?php echo $record['contact']; ?>" required><br>
            <input type="text" name="membership_type" placeholder="Membership Type" value="<?php echo $record['membership_type']; ?>" required><br>
            <input type="date" name="start_date" value="<?php echo $record['start_date']; ?>" required><br>
            <input type="text" name="duration" placeholder="Duration" value="<?php echo $record['duration']; ?>" required><br>
            <input type="submit" name="update_record" value="Update Member">
        </form>
        <?php else: ?>
        <form method="POST">
            <input type="hidden" name="type" value="trainer">
            <input type="text" name="trainer_id" placeholder="Trainer ID" value="<?php echo $record['trainer_id']; ?>"><br>
            <input type="text" name="name" placeholder="Name" value="<?php echo $record['name']; ?>" required><br>
            <input type="number" name="contact" placeholder="Contact" value="<?php echo $record['contact']; ?>" required><br>
            <input type="text" name="specialization" placeholder="Specialization" value="<?php echo $record['specialization']; ?>" required><br>
            <input type="text" name="experience" placeholder="Experience" value="<?php echo $record['experience']; ?>" required><br>
            <input type="submit" name="update_record" value="Update Trainer">
        </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
