<?php
require 'config.php';
if(!isLoggedIn()) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

// Get user info
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$user_id]);
$user = $user->fetch();

// Update profile
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $location = $_POST['location'];

    $update = $pdo->prepare("UPDATE users SET fullname=?, phone=?, address=?, location=? WHERE id=?");
    $update->execute([$fullname, $phone, $address, $location, $user_id]);
    header("Location: profile.php");
    exit;
}

// Get past orders with product details
$orders = $pdo->prepare("
    SELECT o.*, 
           GROUP_CONCAT(CONCAT(p.name, ' (x', oi.quantity, ')') SEPARATOR ', ') as product_details
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.buyer_id = ?
    GROUP BY o.id
    ORDER BY o.order_date DESC
");
$orders->execute([$user_id]);
$orders = $orders->fetchAll();

// Get reviews written by user
$reviews = $pdo->prepare("
    SELECT r.*, p.name as product_name 
    FROM reviews r 
    JOIN products p ON r.product_id = p.id 
    WHERE r.buyer_id = ? 
    ORDER BY r.created_at DESC
");
$reviews->execute([$user_id]);
$reviews = $reviews->fetchAll();

include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>My Profile · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card">
        <a href="index.php" class="back-link">← Back to Home</a>
        <h2 style="color:#f1f5f9;">My Profile</h2>
        
        <div class="profile-grid">
            <!-- ======== LEFT: Account Information ======== -->
            <div class="profile-info">
                <h3 style="color:black;">Account Information</h3>
                <form method="POST">
                    <input type="hidden" name="update_profile" value="1">
                    <div class="form-group">
                        <label style="color: black">Full Name</label>
                        <input style="color: black" type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label style="color: black">Email (read‑only)</label>
                        <input style="color: black" type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="opacity:0.6;">
                    </div>
                    <div class="form-group">
                        <label style="color: black">Phone</label>
                        <input style="color: black" type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label style="color: black">Address</label>
                        <textarea style="color: black" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label style="color: black">Location (City/Township)</label>
                        <input style="color: black" type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>" required>
                    </div>
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>

            <!-- ======== RIGHT: Orders & Reviews ======== -->
            <div style="display:flex; flex-direction:column; gap:32px;">
                <!-- Orders -->
                <div class="profile-orders">
                    <h3 style="color:black;">📦 My Orders</h3>
                    <?php if(count($orders) > 0): ?>
                        <?php foreach($orders as $order): ?>
                            <div class="order-card">
                                <div style="display:flex; justify-content:space-between; flex-wrap:wrap;">
                                    <span><strong>Order #<?php echo $order['id']; ?></strong></span>
                                    <span style="color:#94a3b8;"><?php echo date('d M Y', strtotime($order['order_date'])); ?></span>
                                </div>
                                <div style="font-size:0.9rem; color:#cbd5e1;">Products: <?php echo htmlspecialchars($order['product_details'] ?? 'N/A'); ?></div>
                                <div style="display:flex; justify-content:space-between; flex-wrap:wrap; margin-top:4px;">
                                    <span>Total: <strong style="color:#10b981;">R <?php echo number_format($order['total_amount'],2); ?></strong></span>
                                    <span>Status: <span style="color:<?php echo $order['payment_status']=='paid'?'#34d399':'#fbbf24'; ?>;"><?php echo ucfirst($order['payment_status']); ?></span></span>
                                </div>
                                <div style="font-size:0.8rem; color:#64748b;">Delivery: <?php echo htmlspecialchars($order['delivery_method']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:#94a3b8;">No orders yet. <a href="index.php" style="color:#10b981;">Start shopping</a></p>
                    <?php endif; ?>
                </div>

                <!-- Reviews -->
                <div class="profile-reviews" style="grid-column:unset;">
                    <h3 style="color:black;">⭐ My Reviews</h3>
                    <?php if(count($reviews) > 0): ?>
                        <?php foreach($reviews as $review): ?>
                            <div class="review-card">
                                <div><strong style="color:#f1f5f9;"><?php echo htmlspecialchars($review['product_name']); ?></strong></div>
                                <div style="color:#f59e0b;"><?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5-$review['rating']); ?></div>
                                <div style="color:#cbd5e1;"><?php echo htmlspecialchars($review['comment']); ?></div>
                                <div style="color:#64748b; font-size:0.8rem;"><?php echo date('d M Y', strtotime($review['created_at'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:#94a3b8;">You haven't written any reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
