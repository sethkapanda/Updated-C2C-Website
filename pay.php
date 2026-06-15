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
?>
<!DOCTYPE html>
<html>
<head><title>Simulated Payment</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
    <h2>Payment Details (Demo)</h2>
    <p>Order #<?php echo $order_id; ?> | Total: R <?php echo number_format($order['total_amount'], 2); ?></p>
    <p style="background: #fff3cd; padding: 10px;">This is a simulation. Any valid-looking card will work.</p>
    <?php if($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Card Number (any 16 digits)</label>
        <input type="text" name="card_number" placeholder="4111 1111 1111 1111" required maxlength="19">
        <label>Expiry (MM/YY)</label>
        <input type="text" name="expiry" placeholder="12/28" required>
        <label>CVV</label>
        <input type="text" name="cvv" placeholder="123" required>
        <button type="submit" class="btn">Pay Now (Simulated)</button>
    </form>
    <a href="cart.php">Back to cart</a>
</div>
</body>
</html>