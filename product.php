<?php
require 'config.php';
$id = $_GET['id'];
$prod = $pdo->prepare("SELECT p.*, u.fullname as seller, u.verification_status as seller_verified FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?");
$prod->execute([$id]);
$product = $prod->fetch();
if(!$product) die("Product not found");

// ---------- MANUAL IMAGE MAPPING (based on your filenames) ----------
$image_map = [
    1 => 'Handmade Wooden Bowl.webp',
    2 => 'Traditional Beaded Necklace.jpg',
    3 => 'Wire Car Sculpture.webp',
    4 => 'Woven Basket Set.webp',
    5 => 'African Print Dress.webp',
    6 => 'Wooden Mask.webp',
    7 => 'Clay Pot.webp',
    8 => 'Used Laptop - Dell Inspiron.jpg',
    9 => 'Smartphone Holder.jpg',          // original filename without the query string
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
    21 => 'Coffee Mug Set.webp'
];

$image_path = 'assets/uploads/products/' . ($image_map[$product['id']] ?? '');
if (!empty($image_path) && file_exists($image_path)) {
    $product['image'] = $image_path;
}
// -----------------------------------------------------------------

$reviews = $pdo->prepare("SELECT r.*, u.fullname FROM reviews r JOIN users u ON r.buyer_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$reviews->execute([$id]);
$reviews = $reviews->fetchAll();

$can_review = false;
$already_reviewed = false;
if(isLoggedIn()) {
    $stmt = $pdo->prepare("
        SELECT o.id FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        WHERE oi.product_id = ? AND o.buyer_id = ? AND o.payment_status = 'paid'
        LIMIT 1
    ");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $can_review = $stmt->rowCount() > 0;
    
    $stmt2 = $pdo->prepare("SELECT id FROM reviews WHERE product_id = ? AND buyer_id = ?");
    $stmt2->execute([$id, $_SESSION['user_id']]);
    $already_reviewed = $stmt2->rowCount() > 0;
    if($already_reviewed) $can_review = false;
}
?>
<!DOCTYPE html>
<html>
<head><title><?php echo htmlspecialchars($product['name']); ?></title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<header><?php include 'header.php'; ?></header>
<div class="container">
    <a href="javascript:history.back()" class="back-link">← Back</a>
    <div class="product-simple">
        <?php if($product['image'] && file_exists($product['image'])): ?>
            <img src="<?php echo $product['image']; ?>" class="product-image-simple" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <?php else: ?>
            <div class="no-image-simple">📷 Image not available</div>
        <?php endif; ?>
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="price">R <?php echo number_format($product['price'],2); ?></p>
        <p class="stock">Stock: <?php echo $product['stock']; ?> available</p>
        <p>Seller: <?php echo htmlspecialchars($product['seller']); ?> <?php if($product['seller_verified']=='approved') echo "✓ Verified"; ?></p>
        <p>📍 <?php echo htmlspecialchars($product['location']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn">Add to Cart</a>
    </div>

    <div class="reviews-section">
        <h3>Customer Reviews</h3>
        <?php foreach($reviews as $rev): ?>
            <div class="review-card">
                <strong><?php echo htmlspecialchars($rev['fullname']); ?></strong>
                <div class="stars-readonly"><?php echo str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']); ?></div>
                <p><?php echo htmlspecialchars($rev['comment']); ?></p>
                <small><?php echo date('d M Y', strtotime($rev['created_at'])); ?></small>
            </div>
        <?php endforeach; ?>

        <?php if($can_review): ?>
            <div class="review-form">
                <h4>Write a Review</h4>
                <form method="POST" action="add_review.php" id="reviewForm">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="rating" id="ratingValue" required>
                    <div class="star-rating-widget">
                        <span class="star" data-value="1">☆</span>
                        <span class="star" data-value="2">☆</span>
                        <span class="star" data-value="3">☆</span>
                        <span class="star" data-value="4">☆</span>
                        <span class="star" data-value="5">☆</span>
                    </div>
                    <textarea name="comment" rows="3" placeholder="Your review..." required></textarea>
                    <button type="submit" class="btn">Submit Review</button>
                </form>
            </div>
        <?php elseif(isLoggedIn() && !$already_reviewed): ?>
            <p class="info">You can review after purchasing this product.</p>
        <?php elseif(!isLoggedIn()): ?>
            <p class="info"><a href="login.php">Login</a> to write a review.</p>
        <?php endif; ?>
    </div>
</div>
<footer><?php include 'footer.php'; ?></footer>
<script>
let stars = document.querySelectorAll('.star');
let ratingInput = document.getElementById('ratingValue');
stars.forEach(star => {
    star.addEventListener('click', function() {
        let value = this.dataset.value;
        ratingInput.value = value;
        stars.forEach((s, i) => {
            if(i < value) s.innerHTML = '★';
            else s.innerHTML = '☆';
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