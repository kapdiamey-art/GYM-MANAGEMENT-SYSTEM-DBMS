<?php
include 'db_connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $membership_type = $_POST['membership_type'];
    $duration = $_POST['duration'];
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];

$trainer_id = !empty($_POST['trainer_id']) ? $_POST['trainer_id'] : NULL;
$workout_id = !empty($_POST['workout_id']) ? $_POST['workout_id'] : NULL;
$diet_id    = !empty($_POST['diet_id'])    ? $_POST['diet_id'] : NULL;

    $payment_date = date("Y-m-d");

    
    $result = $conn->query("SELECT COUNT(*) as total FROM payment");
    $row = $result->fetch_assoc();
    $next_id = $row['total'] + 1;
    $payment_id = $next_id;

    $stmt = $conn->prepare("INSERT INTO payment (payment_id, amount, status, date) VALUES (?, ?, 'Active', ?)");
    $stmt->bind_param("ids", $payment_id, $amount, $payment_date);
    $stmt->execute();

    
    $stmt2 = $conn->prepare("
        INSERT INTO member 
        (name, age, gender, contact, membership_type,start_date,duration, payment_id, trainer_id, plan_id, diet_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
    ");
    $stmt2->bind_param("sissssiiiii", $name, $age, $gender, $contact, $membership_type,$start_date, $duration, $payment_id, $trainer_id, $workout_id, $diet_id);
    $stmt2->execute();

    
    $mem_id = $conn->insert_id;
    $membership_id = 'MEM' . str_pad($mem_id, 3, '0', STR_PAD_LEFT);

    $update = $conn->prepare("UPDATE member SET membership_id=? WHERE mem_id=?");
    $update->bind_param("si", $membership_id, $mem_id);
    $update->execute();

    echo "<script>
        alert(' Payment Successful!\\nMember ID: $membership_id\\nPayment ID: $payment_id');
        window.location.href='membership.html';
    </script>";
    exit();
}

$name = $_GET['name'] ?? '';
$membership_type = $_GET['membership_type'] ?? '';
$duration = $_GET['duration'] ?? '';

function getAmount($type, $duration) {
    $base = ['Basic'=>999,'Premium'=>1999,'Deluxe'=>2999];
    $multiplier = ['1 Month'=>1,'3 Months'=>3,'6 Months'=>6,'1 Year'=>12];
    return ($base[$type] ?? 0) * ($multiplier[$duration] ?? 1);
}

$amount = getAmount($membership_type, $duration);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment | Fitness</title>
<style>
body { font-family:sans-serif; text-align:center; padding:50px; background:#f2f2f2; background: url('mem.jpg') no-repeat center center fixed;
    background-size: cover;}
.payment-box { background:#fff; padding:30px; border-radius:10px; display:inline-block; }
button { padding:10px 20px; font-size:16px; cursor:pointer; background:#00cc99; color:#fff; border:none; border-radius:5px; margin-top:20px; }
button:hover { background:#009966; }
</style>
</head>
<body>

<div class="payment-box">
<h2>Payment Summary</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
<p><strong>Membership Type:</strong> <?= htmlspecialchars($membership_type) ?></p>
<p><strong>Duration:</strong> <?= htmlspecialchars($duration) ?></p>
<h3>Amount: ₹<?= $amount ?></h3>

<form method="POST">
<?php foreach ($_GET as $k => $v): ?>
<input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
<?php endforeach; ?>
<input type="hidden" name="amount" value="<?= $amount ?>">
<button type="submit">Pay (Cash)</button>
</form>
</div>

</body>
</html>
