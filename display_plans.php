<?php
include('db_connect.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Workout Plans</title>
<style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        font-family: Arial, sans-serif;
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('back.jpg') no-repeat center center fixed;
        background-size: cover;
        color: white;
        text-align: center;
    }

    h1 {
        margin-top: 40px;
        color: #00ffcc;
    }

    table {
        width: 80%;
        margin: 40px auto;
        border-collapse: collapse;
        background-color: rgba(255,255,255,0.1);
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    th, td {
        border: 1px solid rgba(255,255,255,0.2);
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: rgba(0, 255, 204, 0.2);
    }

    td {
        color: #f1f1f1;
    }

    tr:hover {
        background-color: rgba(255,255,255,0.1);
    }
</style>
</head>
<body>

<h1>Workout Plans</h1>

<?php
$result = $conn->query("SELECT * FROM workout_plan");

echo "<table>";
echo "<tr><th>PLAN ID</th><th>PLAN NAME</th><th>DESCRIPTION</th><th>DURATION / WEEK</th><th>EQUIP ID</th></tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Plan_id']}</td>
                <td>{$row['Plan_name']}</td>
                <td>{$row['description']}</td>
                <td>{$row['duration_per_week']}</td>
                <td>{$row['Equip_id']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No plans found.</td></tr>";
}
echo "</table>";

$conn->close();
?>

</body>
</html>
