<?php
require 'config.php';
if(isset($_GET['add'])) {
    $id = $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}
if(isset($_GET['update']) && isset($_GET['qty'])) {
    $id = $_GET['update'];
    $qty = max(1, intval($_GET['qty']));
    $_SESSION['cart'][$id] = $qty;
    header("Location: cart.php");
    exit;
}
if(isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit;
}

$cart_items = [];
$total = 0;
if(!empty($_SESSION['cart'])) {
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
}
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>Shopping Cart · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card">
        <a href="javascript:history.back()" class="back-link">← Continue Shopping</a>
        <h2 style="color:#f1f5f9;">Shopping Cart</h2>
        <?php if(empty($cart_items)): ?>
            <p style="color:#94a3b8;">Your cart is empty. <a href="index.php" style="color:#10b981;">Start shopping</a></p>
        <?php else: ?>
            <table class="cart-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr><th style="padding:12px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.05);">Product</th><th style="padding:12px; text-align:left;">Price</th><th style="padding:12px; text-align:left;">Qty</th><th style="padding:12px; text-align:left;">Subtotal</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach($cart_items as $item): ?>
                    <tr>
                        <td style="padding:12px; border-bottom:1px solid rgba(255,255,255,0.05);"><?php echo htmlspecialchars($item['product']['name']); ?></td>
                        <td style="padding:12px; border-bottom:1px solid rgba(255,255,255,0.05);">R <?php echo number_format($item['product']['price'],2); ?></td>
                        <td style="padding:12px; border-bottom:1px solid rgba(255,255,255,0.05);">
                            <input type="number" value="<?php echo $item['qty']; ?>" min="1" max="<?php echo $item['product']['stock']; ?>" class="qty-input" data-id="<?php echo $item['product']['id']; ?>" style="width:70px; padding:8px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:12px; color:white;">
                            <button class="update-qty btn-sm btn-outline" data-id="<?php echo $item['product']['id']; ?>" style="background:transparent; border:1px solid #10b981; color:#10b981; border-radius:30px; padding:4px 12px; cursor:pointer;">Update</button>
                        </td>
                        <td style="padding:12px; border-bottom:1px solid rgba(255,255,255,0.05);">R <?php echo number_format($item['subtotal'],2); ?></td>
                        <td style="padding:12px; border-bottom:1px solid rgba(255,255,255,0.05);"><a href="?remove=<?php echo $item['product']['id']; ?>" class="btn-danger btn-sm" style="background:#ef4444; color:white; padding:4px 12px; border-radius:30px; text-decoration:none;">Remove</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-total" style="text-align:right; margin:24px 0; font-size:1.3rem; color:#f1f5f9;"><strong>Total: R <?php echo number_format($total,2); ?></strong></div>
            <a href="checkout.php" class="btn" style="display:inline-block;">Proceed to Checkout →</a>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
<script>
document.querySelectorAll('.update-qty').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let qty = this.parentElement.querySelector('.qty-input').value;
        window.location.href = `?update=${id}&qty=${qty}`;
    });
});
</script>
</body>
</html>
