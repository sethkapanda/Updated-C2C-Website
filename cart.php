<?php
require 'config.php';
// Add to cart
if(isset($_GET['add'])) {
    $id = $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}
// Update quantity
if(isset($_GET['update']) && isset($_GET['qty'])) {
    $id = $_GET['update'];
    $qty = max(1, intval($_GET['qty']));
    $_SESSION['cart'][$id] = $qty;
    header("Location: cart.php");
    exit;
}
// Remove item
if(isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit;
}

// Get cart items
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
?>
<!DOCTYPE html>
<html>
<head><title>Shopping Cart - C2C Market</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<header><?php include 'header.php'; ?></header>
<div class="container">
    <a href="javascript:history.back()" class="back-link">← Continue Shopping</a>
    <h2>Shopping Cart</h2>
    
    <?php if(empty($cart_items)): ?>
        <p>Your cart is empty. <a href="index.php">Start shopping</a></p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product']['name']); ?></td>
                    <td>R <?php echo number_format($item['product']['price'],2); ?></td>
                    <td>
                        <input type="number" value="<?php echo $item['qty']; ?>" min="1" max="<?php echo $item['product']['stock']; ?>" class="qty-input" data-id="<?php echo $item['product']['id']; ?>">
                        <button class="update-qty btn-sm btn-outline" data-id="<?php echo $item['product']['id']; ?>">Update</button>
                    </td>
                    <td>R <?php echo number_format($item['subtotal'],2); ?></td>
                    <td><a href="?remove=<?php echo $item['product']['id']; ?>" class="btn-danger btn-sm">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-total">
            <strong>Total: R <?php echo number_format($total,2); ?></strong>
        </div>
        <a href="checkout.php" class="btn btn-large">Proceed to Checkout →</a>
    <?php endif; ?>
</div>
<footer><?php include 'footer.php'; ?></footer>
<script>
// For quantity update
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