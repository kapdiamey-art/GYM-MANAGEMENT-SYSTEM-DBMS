<?php
include 'db_connect.php';

if (isset($_GET['delete_diet'])) {
    $id = $_GET['delete_diet'];
    $conn->query("DELETE FROM diet_plan WHERE diet_id='$id'");
}

if (isset($_GET['delete_supp'])) {
    $id = $_GET['delete_supp'];
    $conn->query("DELETE FROM supplements WHERE supp_id='$id'");
}

if (isset($_POST['add_diet'])) {
    $diet_id = $_POST['diet_id'];
    $supp_id = $_POST['supp_id'];
    $diet_name = $_POST['diet_name'];
    $description = $_POST['description'];
    $suitable_for = $_POST['suitable_for'];

    $conn->query("INSERT INTO diet_plan VALUES ('$diet_id','$diet_name','$description','$suitable_for','$supp_id')");
}

if (isset($_POST['add_supp'])) {
    $supp_id = $_POST['supp_id'];
    $supp_name = $_POST['supp_name'];
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];

    $conn->query("INSERT INTO supplements VALUES ('$supp_id','$supp_name','$price','$stock_qty')");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Diet & Supplements Manager</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('mem.jpg') no-repeat center center/cover;
      color: white;
      min-height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      background: rgba(0, 0, 0, 0.7);
      padding: 30px;
      border-radius: 20px;
      width: 90%;
      text-align: center;
      box-shadow: 0 0 15px rgba(255,255,255,0.2);
      position: relative;
    }

    /* Go Back Button */
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

    .toggle-box button {
      background: teal;
      border: none;
      color: white;
      padding: 10px 20px;
      margin: 10px;
      cursor: pointer;
      border-radius: 8px;
    }
    .toggle-box button:hover { background: darkcyan; }
    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    input, select {
      padding: 10px;
      width: 50%;
      border-radius: 5px;
      border: none;
      outline: none;
    }
    button {
      background: #00b894;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      color: white;
      cursor: pointer;
    }
    button:hover { background: #019870; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid white;
      padding: 10px;
    }
    th { background: teal; }
    .delete-btn {
      background: red;
      color: white;
      padding: 5px 10px;
      text-decoration: none;
      border-radius: 5px;
    }
    .delete-btn:hover { background: darkred; }
  </style>
</head>
<body>
  <div class="container">

    <button class="back-btn" onclick="window.location.href='membership.html'">⬅ Go Back</button>

    <h1>Diet & Supplements Manager</h1>

    <div class="toggle-box">
      <button onclick="showForm('diet')">Diet Plan</button>
      <button onclick="showForm('supplement')">Supplements</button>
    </div>

    <div id="dietForm" class="form-section">
      <h2>Add Diet Plan</h2>
      <form method="post">
        <input type="text" name="diet_id" placeholder="Diet ID" required>
        <input type="text" name="supp_id" placeholder="Supplement ID" required>
        <input type="text" name="diet_name" placeholder="Diet Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="text" name="suitable_for" placeholder="Suitable For" required>
        <button type="submit" name="add_diet">Add Diet</button>
      </form>

      <h3>All Diet Plans</h3>
      <table>
        <tr>
          <th>Diet ID</th>
          <th>Supplement ID</th>
          <th>Diet Name</th>
          <th>Description</th>
          <th>Suitable For</th>
          <th>Action</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM diet_plan");
        while ($row = $res->fetch_assoc()) {
            echo "<tr>
              <td>{$row['diet_id']}</td>
              <td>{$row['supp_id']}</td>
              <td>{$row['diet_name']}</td>
              <td>{$row['description']}</td>
              <td>{$row['suitable_for']}</td>
              <td><a href='?delete_diet={$row['diet_id']}' class='delete-btn'>Delete</a></td>
            </tr>";
        }
        ?>
      </table>
    </div>

    <div id="supplementForm" class="form-section" style="display:none;">
      <h2>Add Supplement</h2>
      <form method="post">
        <input type="text" name="supp_id" placeholder="Supplement ID" required>
        <input type="text" name="supp_name" placeholder="Supplement Name" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="number" name="stock_qty" placeholder="Stock Quantity" required>
        <button type="submit" name="add_supp">Add Supplement</button>
      </form>

      <h3>All Supplements</h3>
      <table>
        <tr>
          <th>Supplement ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Stock Quantity</th>
          <th>Action</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM supplements");
        while ($row = $res->fetch_assoc()) {
            echo "<tr>
              <td>{$row['supp_id']}</td>
              <td>{$row['supp_name']}</td>
              <td>{$row['price']}</td>
              <td>{$row['stock_qty']}</td>
              <td><a href='?delete_supp={$row['supp_id']}' class='delete-btn'>Delete</a></td>
            </tr>";
        }
        ?>
      </table>
    </div>

  </div>

  <script>
    function showForm(type) {
      document.getElementById('dietForm').style.display = (type === 'diet') ? 'block' : 'none';
      document.getElementById('supplementForm').style.display = (type === 'supplement') ? 'block' : 'none';
    }
  </script>

</body>
</html>
