<?php
include 'db_connect.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $conn->query("UPDATE member SET trainer_id = NULL WHERE trainer_id = '$delete_id'");

  
    $deleteSQL = "DELETE FROM trainer WHERE trainer_id = ?";
    $stmt = $conn->prepare($deleteSQL);
    $stmt->bind_param("s", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Trainer deleted successfully'); window.location.href='view_trainers.html';</script>";
    } else {
        echo "<script>alert('Error deleting trainer'); window.location.href='view_trainers.html';</script>";
    }

    $stmt->close();
    exit;
}
$sql = "
    SELECT 
        t.trainer_id,
        t.name,
        t.specialization,
        t.contact,
        t.experience,
        COUNT(m.mem_id) AS total_members
    FROM trainer t
    LEFT JOIN member m ON t.trainer_id = m.trainer_id
    GROUP BY 
        t.trainer_id, 
        t.name, 
        t.specialization, 
        t.contact, 
        t.experience
    ORDER BY t.name ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Trainers | Lifestyle Fitness</title>
  <link rel="stylesheet" href="view_trainers.css">
  <style>
    .delete-btn {
      background-color: #e74c3c;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }
    .delete-btn:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

<header>
  <h1>All Trainers</h1>
  <nav>
    <a href="membership.html">Back</a>
    <a href="home.html">Home</a>
  </nav>
</header>

<main>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Trainer ID</th>
          <th>Name</th>
          <th>Specialization</th>
          <th>Contact</th>
          <th>Experience</th>
          <th>Total Members</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['trainer_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['specialization']}</td>
                    <td>{$row['contact']}</td>
                    <td>{$row['experience']}</td>
                    <td>{$row['total_members']}</td>
                    <td>
                        <a href='fetch_trainers.php?delete_id={$row['trainer_id']}'
                           onclick=\"return confirm('Are you sure you want to delete trainer {$row['name']}?');\">
                           <button class='delete-btn'>Delete</button>
                        </a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No trainers found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</main>

<footer>
  <p>© 2025 Lifestyle Fitness</p>
</footer>

</body>
</html>

<?php $conn->close(); ?>
