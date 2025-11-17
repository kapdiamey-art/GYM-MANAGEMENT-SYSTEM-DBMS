<?php
include 'db_connect.php';


if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);


    $stmt = $conn->prepare("SELECT payment_id FROM member WHERE mem_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($payment_id);
    $stmt->fetch();
    $stmt->close();

    
    $d = $conn->prepare("DELETE FROM member WHERE mem_id = ?");
    $d->bind_param("i", $delete_id);
    $d->execute();
    $d->close();


    if (!empty($payment_id)) {
        $p = $conn->prepare("DELETE FROM payment WHERE payment_id = ?");
        $p->bind_param("i", $payment_id);
        $p->execute();
        $p->close();
    }

    header("Location: dismem.php");
    exit;
}


$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $query = "
        SELECT m.*, 
               t.name AS trainer_name,
               w.plan_name AS workout_name,
               d.diet_name AS diet_name
        FROM member m
        LEFT JOIN trainer t ON m.trainer_id = t.trainer_id
        LEFT JOIN workout_plan w ON m.plan_id = w.plan_id
        LEFT JOIN diet_plan d ON m.diet_id = d.diet_id
        WHERE 
            m.membership_id LIKE '%$search%' OR
            m.name LIKE '%$search%' OR
            m.contact LIKE '%$search%'
        ORDER BY m.mem_id ASC
    ";
} else {

    
    $query = "
        SELECT m.*, 
               t.name AS trainer_name,
               w.plan_name AS workout_name,
               d.diet_name AS diet_name
        FROM member m
        LEFT JOIN trainer t ON m.trainer_id = t.trainer_id
        LEFT JOIN workout_plan w ON m.plan_id = w.plan_id
        LEFT JOIN diet_plan d ON m.diet_id = d.diet_id
        ORDER BY m.mem_id ASC
    ";
}

$result = $conn->query($query);

$headers = [
    "mem_id" => "Member ID",
    "membership_id" => "Membership ID",
    "name" => "Name",
    "age" => "Age",
    "gender" => "Gender",
    "contact" => "Contact",
    "membership_type" => "Membership Type",
    "start_date" => "Start Date",
    "duration" => "Duration",
    "trainer_name" => "Trainer",
    "workout_name" => "Workout Plan",
    "diet_name" => "Diet Plan"
];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>All Members | Lifestyle Fitness</title>
<link rel="stylesheet" href="dismem.css">
</head>
<body>

<header>
    <h1>All Members</h1>
    <nav>
        <a href="membership.html">Back</a>
        <a href="home.html">Home</a>
    </nav>
</header>


<div style="text-align:center; margin:20px;">
    <form method="GET" action="dismem.php">
        <input type="text" name="search"
            placeholder="Search by Name, Membership ID, or Contact"
            value="<?= htmlspecialchars($search) ?>"
            style="padding:8px; width:250px; border-radius:5px; border:1px solid #00ffcc;">
        <button type="submit" style="padding:8px 12px; border:none; background:#00ffcc; color:#000; border-radius:5px;">
            Search
        </button>
    </form>
</div>

<main>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <?php foreach ($headers as $col => $label) echo "<th>$label</th>"; ?>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";

                        foreach ($headers as $col => $label) {
                            echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
                        }

                        echo "
                        <td>
                            <a href='dismem.php?delete_id={$row['mem_id']}'
                               onclick=\"return confirm('Delete this member?');\">
                                <button class='delete-btn'>Delete</button>
                            </a>
                        </td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='".(count($headers)+1)."'>No Members Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<footer><p>© 2025 Lifestyle Fitness</p></footer>

</body>
</html>
