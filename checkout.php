<?php
require 'config.php';
if(!isLoggedIn()) { header("Location: login.php"); exit; }
if(empty($_SESSION['cart'])) { header("Location: cart.php"); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $delivery_method = $_POST['delivery_method'];
    $address = $_POST['address'];
    
    $total = 0;
    foreach($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$pid]);
        $price = $stmt->fetch()['price'];
        $total += $price * $qty;
    }
    
    $stmt = $pdo->prepare("INSERT INTO orders (buyer_id, total_amount, payment_method, delivery_method, delivery_address, payment_status, status) VALUES (?,?,?,?,?, 'pending', 'pending_payment')");
    $stmt->execute([$_SESSION['user_id'], $total, $payment_method, $delivery_method, $address]);
    $order_id = $pdo->lastInsertId();
    
    foreach($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$pid]);
        $price = $stmt->fetch()['price'];
        $stmt2 = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)");
        $stmt2->execute([$order_id, $pid, $qty, $price]);
    }
    
    unset($_SESSION['cart']);
    header("Location: pay.php?order_id=$order_id");
    exit;
}

$cart_items = [];
$total = 0;
foreach($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $product = $stmt->fetch();
    if($product) {
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        $cart_items[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
    }
}
$user = $pdo->prepare("SELECT * FROM users WHERE id=?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>Checkout · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card">
        <a href="cart.php" class="back-link">← Back to Cart</a>
        <h2 style="color:#f1f5f9;">Checkout</h2>
        <div class="checkout-grid">
            <div class="checkout-form">
                <form method="POST">
                    <div class="form-group">
                        <label>Delivery Address</label>
                        <textarea name="address" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" required>
                            <option value="EFT">🏦 EFT (Bank Transfer)</option>
                            <option value="Mobile Money">📱 Mobile Money (M-Pesa)</option>
                            <option value="Instant EFT">⚡ Instant EFT</option>
                            <option value="PayFast">💳 PayFast (simulated)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Delivery Method</label>
                        <select name="delivery_method" required>
                            <option value="Pargo">📦 Pargo Pickup Point</option>
                            <option value="The Courier Guy">🚚 The Courier Guy (door delivery)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Proceed to Payment →</button>
                </form>
            </div>
            <div class="order-summary">
                <h3 style="color:#f1f5f9;">Order Summary</h3>
                <?php foreach($cart_items as $item): ?>
                    <div class="summary-item">
                        <span><?php echo htmlspecialchars($item['product']['name']); ?> x<?php echo $item['qty']; ?></span>
                        <span>R <?php echo number_format($item['subtotal'],2); ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="summary-total">
                    <strong>Total</strong>
                    <strong>R <?php echo number_format($total,2); ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
