<?php
require 'config.php';
$id = $_GET['id'];
$prod = $pdo->prepare("SELECT p.*, u.fullname as seller, u.verification_status as seller_verified FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?");
$prod->execute([$id]);
$product = $prod->fetch();
if(!$product) die("Product not found");

$image_map = [
    1 => 'Handmade Wooden Bowl.webp',
    2 => 'Traditional Beaded Necklace.jpg',
    3 => 'Wire Car Sculpture.webp',
    4 => 'Woven Basket Set.webp',
    5 => 'African Print Dress.webp',
    6 => 'Wooden Mask.webp',
    7 => 'Clay Pot.webp',
    8 => 'Used Laptop - Dell Inspiron.jpg',
    9 => 'Smartphone Holder.webp',
    10 => 'Wireless Mouse.webp',
    11 => 'USB-C Charger.webp',
    12 => 'Bluetooth Speaker.webp',
    13 => 'Mens Sneakers.webp',
    14 => 'Handbag.png',
    15 => 'Novel - "Born a Crime".jpg',
    16 => 'Sunglasses.jpg',
    17 => 'Power Bank.webp',
    18 => 'Desk Lamp.jpeg',
    19 => 'Yoga Mat.jpeg',
    20 => 'Kids Toy Car.webp',
    21 => 'Coffee Mug Set.webp',
    22=> '5472-premier-munchen-finale-soccer-ball-a.jpg'
];
$image_path = 'assets/uploads/products/' . ($image_map[$product['id']] ?? '');
if (!empty($image_path) && file_exists($image_path)) {
    $product['image'] = $image_path;
}

$reviews = $pdo->prepare("SELECT r.*, u.fullname FROM reviews r JOIN users u ON r.buyer_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$reviews->execute([$id]);
$reviews = $reviews->fetchAll();

$can_review = false; $already_reviewed = false;
if(isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT o.id FROM orders o JOIN order_items oi ON o.id = oi.order_id WHERE oi.product_id = ? AND o.buyer_id = ? AND o.payment_status = 'paid' LIMIT 1");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $can_review = $stmt->rowCount() > 0;
    $stmt2 = $pdo->prepare("SELECT id FROM reviews WHERE product_id = ? AND buyer_id = ?");
    $stmt2->execute([$id, $_SESSION['user_id']]);
    $already_reviewed = $stmt2->rowCount() > 0;
    if($already_reviewed) $can_review = false;
}
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title><?php echo htmlspecialchars($product['name']); ?></title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card">
        <a href="javascript:history.back()" class="back-link">← Back</a>
        <div class="product-simple">
            <?php if($product['image'] && file_exists($product['image'])): ?>
                <img src="<?php echo $product['image']; ?>" class="product-image-simple" style="max-width:100%; border-radius:20px; margin-bottom:20px;">
            <?php else: ?>
                <div class="no-image-simple" style="background:#1e293b; padding:80px; text-align:center; border-radius:20px; color:#64748b;">📷 Image not available</div>
            <?php endif; ?>
            <h1 style="color:#f1f5f9;"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="price" style="font-size:2rem; background:linear-gradient(135deg,#10b981,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">R <?php echo number_format($product['price'],2); ?></p>
            <p class="stock" style="background:linear-gradient(135deg,#10b981,#059669); display:inline-block; padding:6px 14px; border-radius:20px; color:white;">Stock: <?php echo $product['stock']; ?></p>
            <p style="color:#cbd5e1;">Seller: <?php echo htmlspecialchars($product['seller']); ?> <?php if($product['seller_verified']=='approved') echo "✓ Verified"; ?></p>
            <p style="color:#cbd5e1;">📍 <?php echo htmlspecialchars($product['location']); ?></p>
            <p style="color:#e2e8f0;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn" style="display:inline-block; margin-top:16px;">Add to Cart</a>
        </div>

        <div class="reviews-section" style="margin-top:48px;">
            <h3 style="color:#f1f5f9;">Customer Reviews</h3>
            <?php foreach($reviews as $rev): ?>
                <div class="review-card">
                    <strong><?php echo htmlspecialchars($rev['fullname']); ?></strong>
                    <div class="stars-readonly" style="color:#f59e0b;"><?php echo str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']); ?></div>
                    <p><?php echo htmlspecialchars($rev['comment']); ?></p>
                    <small style="color:#64748b;"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>

            <?php if($can_review): ?>
                <div class="review-form" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:20px; padding:24px; margin-top:24px;">
                    <h4 style="color:#f1f5f9;">Write a Review</h4>
                    <form method="POST" action="add_review.php" id="reviewForm">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="rating" id="ratingValue" required>
                        <div class="star-rating-widget" style="margin-bottom:16px;">
                            <span class="star" data-value="1" style="font-size:32px; cursor:pointer; color:#4b5563;">☆</span>
                            <span class="star" data-value="2" style="font-size:32px; cursor:pointer; color:#4b5563;">☆</span>
                            <span class="star" data-value="3" style="font-size:32px; cursor:pointer; color:#4b5563;">☆</span>
                            <span class="star" data-value="4" style="font-size:32px; cursor:pointer; color:#4b5563;">☆</span>
                            <span class="star" data-value="5" style="font-size:32px; cursor:pointer; color:#4b5563;">☆</span>
                        </div>
                        <textarea name="comment" rows="3" placeholder="Your review..." style="width:100%; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:16px; color:white; padding:14px; margin-bottom:16px;"></textarea>
                        <button type="submit" class="btn">Submit Review</button>
                    </form>
                </div>
            <?php elseif(isLoggedIn() && !$already_reviewed): ?>
                <p class="info" style="background:rgba(16,185,129,0.1); border-left:4px solid #10b981; padding:12px; border-radius:12px; color:#cbd5e1;">You can review after purchasing this product.</p>
            <?php elseif(!isLoggedIn()): ?>
                <p class="info" style="background:rgba(16,185,129,0.1); border-left:4px solid #10b981; padding:12px; border-radius:12px; color:#cbd5e1;"><a href="login.php" style="color:#10b981;">Login</a> to write a review.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script>
let stars = document.querySelectorAll('.star');
let ratingInput = document.getElementById('ratingValue');
stars.forEach(star => {
    star.addEventListener('click', function() {
        let value = this.dataset.value;
        ratingInput.value = value;
        stars.forEach((s, i) => {
            if(i < value) s.style.color = '#f59e0b';
            else s.style.color = '#4b5563';
        });
    });
});
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    if(!ratingInput.value) {
        alert('Please select a rating');
        e.preventDefault();
    }
});
</script>
</body>
</html>
