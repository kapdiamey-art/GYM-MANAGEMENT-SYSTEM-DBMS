<?php
include 'db_connect.php';


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM equipments WHERE equip_id = ?");
    $stmt->bind_param("s", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: equipment.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $equip_id = $_POST['equip_id'];
    $equip_name = $_POST['equip_name'];
    $purchase_date = $_POST['purchase_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO equipments (equip_id, equip_name, purchase_date, status)
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $equip_id, $equip_name, $purchase_date, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Equipment added successfully'); window.location.href='equipment.php';</script>";
    } else {
        echo "<script>alert('Error adding equipment: " . addslashes($stmt->error) . "');</script>";
    }

    $stmt->close();
}


$result = $conn->query("SELECT * FROM equipments ORDER BY equip_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Equipment Management</title>
  <link rel="stylesheet" href="equipment.css">
</head>
<body>

<header>
  <h1>Equipment Management</h1>
  <nav>
    <a href="home.html">Home</a>
    <a href="membership.html">Back</a>
  </nav>
</header>

<main>
  <div class="form-container">
    <h2>Add New Equipment</h2>
    <form method="POST" action="">
      <label>Equipment ID:</label>
      <input type="text" name="equip_id" placeholder="EQ001" required>

      <label>Equipment Name:</label>
      <input type="text" name="equip_name" required>

      <label>Purchase Date:</label>
      <input type="date" name="purchase_date" required>

      <label>Status:</label>
      <select name="status" required>
        <option value="Working">Working</option>
        <option value="Under Maintenance">Under Maintenance</option>
        <option value="Damaged">Damaged</option>
      </select>

      <button type="submit">Add Equipment</button>
    </form>
  </div>

  <div class="table-container">
    <h2>All Equipment</h2>
    <table>
      <thead>
        <tr>
          <th>Equipment ID</th>
          <th>Name</th>
          <th>Purchase Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['equip_id']}</td>
                    <td>{$row['equip_name']}</td>
                    <td>{$row['purchase_date']}</td>
                    <td>{$row['status']}</td>
                    <td>
                      <a href='equipment.php?delete_id={$row['equip_id']}'
                         onclick=\"return confirm('Delete {$row['equip_name']}?');\">
                         <button class='delete-btn'>Delete</button>
                      </a>
                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No equipment found</td></tr>";
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
