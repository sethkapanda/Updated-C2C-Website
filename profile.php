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

// Get past orders
$orders = $pdo->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY order_date DESC");
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
?>
<!DOCTYPE html>
<html>
<head><title>My Profile - C2C Market</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<header><?php include 'header.php'; ?></header>
<div class="container">
    <a href="index.php" class="back-link">← Back to Home</a>
    <h2>My Profile</h2>
    
    <div class="profile-grid">
        <div class="profile-info">
            <h3>Account Information</h3>
            <form method="POST">
                <input type="hidden" name="update_profile" value="1">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Location (City/Township)</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>" required>
                </div>
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
        
        <div class="profile-orders">
            <h3>My Orders</h3>
            <?php if(count($orders) > 0): ?>
                <?php foreach($orders as $order): ?>
                    <div class="order-card">
                        <div>Order #<?php echo $order['id']; ?></div>
                        <div>Date: <?php echo date('d M Y', strtotime($order['order_date'])); ?></div>
                        <div>Total: R <?php echo number_format($order['total_amount'],2); ?></div>
                        <div>Status: <?php echo ucfirst($order['payment_status']); ?></div>
                        <div>Delivery: <?php echo htmlspecialchars($order['delivery_method']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No orders yet. <a href="index.php">Start shopping</a></p>
            <?php endif; ?>
        </div>
        
        <div class="profile-reviews">
            <h3>My Reviews</h3>
            <?php if(count($reviews) > 0): ?>
                <?php foreach($reviews as $review): ?>
                    <div class="review-card">
                        <div><strong><?php echo htmlspecialchars($review['product_name']); ?></strong></div>
                        <div><?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5-$review['rating']); ?></div>
                        <div><?php echo htmlspecialchars($review['comment']); ?></div>
                        <div class="date"><?php echo date('d M Y', strtotime($review['created_at'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You haven't written any reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<footer><?php include 'footer.php'; ?></footer>
</body>
</html>