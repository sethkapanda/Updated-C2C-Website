<?php
require 'config.php';
if(!isLoggedIn()) { header("Location: login.php"); exit; }
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND buyer_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$order = $stmt->fetch();
if(!$order) die("Order not found");
?>
<!DOCTYPE html>
<html>
<head><title>Order Confirmed</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
    <a href="index.php" class="back-link">← Back to Home</a>
    <h2>Order Placed Successfully</h2>
    <p>Order #<?php echo $order['id']; ?></p>
    <p>Total: R <?php echo number_format($order['total_amount'],2); ?></p>
    <p>Payment Method: <?php echo $order['payment_method']; ?></p>
    <p>Delivery: <?php echo $order['delivery_method']; ?></p>
    <p>Status: <?php echo $order['payment_status']; ?></p>
    <a href="index.php" class="btn">Continue Shopping</a>
</div>
</body>
</html>