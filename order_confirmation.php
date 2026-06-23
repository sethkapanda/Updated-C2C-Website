<?php
require 'config.php';
if(!isLoggedIn()) { header("Location: login.php"); exit; }
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND buyer_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$order = $stmt->fetch();
if(!$order) die("Order not found");
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>Order Confirmed · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card" style="max-width:600px; text-align:center;">
        <a href="index.php" class="back-link">← Back to Home</a>
        <h2 style="color:#10b981;">✅ Order Placed Successfully</h2>
        <p style="color:#f1f5f9;">Order #<?php echo $order['id']; ?></p>
        <p style="color:#cbd5e1;">Total: R <?php echo number_format($order['total_amount'],2); ?></p>
        <p style="color:#cbd5e1;">Payment Method: <?php echo $order['payment_method']; ?></p>
        <p style="color:#cbd5e1;">Delivery: <?php echo $order['delivery_method']; ?></p>
        <p style="color:#cbd5e1;">Status: <?php echo $order['payment_status']; ?></p>
        <a href="index.php" class="btn" style="display:inline-block; margin-top:20px;">Continue Shopping</a>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
