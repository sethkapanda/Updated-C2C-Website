<?php
require 'config.php';
if(!isLoggedIn()) { header("Location: login.php"); exit; }

$order_id = $_GET['order_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND buyer_id = ? AND payment_status = 'pending'");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();
if(!$order) die("Invalid order or already paid.");

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_number = $_POST['card_number'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    if(empty($card_number) || empty($expiry) || empty($cvv)) {
        $error = "Please fill all card details.";
    } else {
        $update = $pdo->prepare("UPDATE orders SET payment_status = 'paid', status = 'processing' WHERE id = ?");
        $update->execute([$order_id]);
        header("Location: order_confirmation.php?id=$order_id");
        exit;
    }
}
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>Simulated Payment · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card" style="max-width:600px;">
        <h2 style="color:#f1f5f9;">Payment Details (Demo)</h2>
        <p>Order #<?php echo $order_id; ?> | Total: R <?php echo number_format($order['total_amount'], 2); ?></p>
        <p style="background:rgba(245,158,11,0.15); padding:10px; border-radius:12px; color:#fbbf24;">This is a simulation. Any valid-looking card will work.</p>
        <?php if($error): ?>
            <p style="color:#f87171;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Card Number (any 16 digits)</label>
                <input type="text" name="card_number" placeholder="4111 1111 1111 1111" required maxlength="19">
            </div>
            <div class="form-group">
                <label>Expiry (MM/YY)</label>
                <input type="text" name="expiry" placeholder="12/28" required>
            </div>
            <div class="form-group">
                <label>CVV</label>
                <input type="text" name="cvv" placeholder="123" required>
            </div>
            <button type="submit" class="btn" style="width:100%;">Pay Now (Simulated)</button>
        </form>
        <a href="cart.php" class="back-link">← Back to cart</a>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
