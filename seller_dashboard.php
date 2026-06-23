<?php
require 'config.php';
if(!isLoggedIn() || !isSeller()) { header("Location: login.php"); exit; }
$user_id = $_SESSION['user_id'];

$products = $pdo->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$products->execute([$user_id]);
$products = $products->fetchAll();

$earnings = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) as total FROM order_items oi JOIN orders o ON oi.order_id = o.id JOIN products p ON oi.product_id = p.id WHERE p.seller_id = ? AND o.payment_status = 'paid'");
$earnings->execute([$user_id]);
$total_earned = $earnings->fetch()['total'] ?? 0;

$avg_rating = $pdo->prepare("SELECT AVG(r.rating) as avg FROM reviews r JOIN products p ON r.product_id = p.id WHERE p.seller_id = ?");
$avg_rating->execute([$user_id]);
$avg_rating = round($avg_rating->fetch()['avg'] ?? 0, 1);

$products_sold = $pdo->prepare("SELECT SUM(oi.quantity) as sold FROM order_items oi JOIN orders o ON oi.order_id = o.id JOIN products p ON oi.product_id = p.id WHERE p.seller_id = ? AND o.payment_status = 'paid'");
$products_sold->execute([$user_id]);
$products_sold = $products_sold->fetch()['sold'] ?? 0;

$stmt = $pdo->prepare("SELECT verification_status FROM users WHERE id=?");
$stmt->execute([$user_id]);
$status = $stmt->fetch()['verification_status'];
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>Seller Dashboard · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card">
        <a href="index.php" class="back-link">← Back to Home</a>
        <h2 style="color:#f1f5f9;">Seller Dashboard</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">R <?php echo number_format($total_earned,2); ?></div>
                <div>Total Earnings</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $products_sold; ?></div>
                <div>Products Sold</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $avg_rating; ?> ★</div>
                <div>Average Rating</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo ucfirst($status); ?></div>
                <div>Verification Status</div>
                <?php if($status != 'approved'): ?>
                    <div><a href="verify_request.php" class="btn-sm btn" style="margin-top:10px;">Request Verification</a></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="margin: 20px 0;">
            <a href="add_product.php" class="btn">+ Add New Product</a>
        </div>
        
        <h3 style="color:#f1f5f9;">My Products</h3>
        <?php if(count($products) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Product</th><th>Price</th><th>Stock</th><th>Category</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td>R <?php echo number_format($p['price'],2); ?></td>
                        <td><?php echo $p['stock']; ?></td>
                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn-warning btn-sm">Edit</a>
                            <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn-danger btn-sm" onclick="return confirm('Delete product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color:#94a3b8;">You haven't added any products yet. <a href="add_product.php" style="color:#10b981;">Add your first product</a></p>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
