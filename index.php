<?php
require 'config.php';

// ---------- MANUAL IMAGE MAPPING ----------
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

$stmt = $pdo->prepare("
    SELECT p.*, u.fullname as seller_name, u.location as seller_location,
          (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
    FROM products p 
    JOIN users u ON p.seller_id = u.id 
    WHERE p.status = 'active'
    ORDER BY p.created_at DESC
    LIMIT 4
");
$stmt->execute();
$featured_products = $stmt->fetchAll();

foreach ($featured_products as &$product) {
    $mapped_filename = $image_map[$product['id']] ?? '';
    if ($mapped_filename) {
        $mapped_path = 'assets/uploads/products/' . $mapped_filename;
        if (file_exists($mapped_path)) {
            $product['image'] = $mapped_path;
        }
    }
}

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbuntuBazaar - Buy & Sell Locally</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('assets/images/mutter-kind-supermarkt-einkaufen.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 100px 40px;
            border-radius: var(--radius-xl);
            margin: 40px 0;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .hero h2 {
            font-size: 2.8rem;
            margin-bottom: 16px;
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1.2;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }
        .hero p {
            font-size: 1.15rem;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 400;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            opacity: 1;
        }
        @media (max-width: 768px) {
            .hero { padding: 60px 24px; min-height: 200px; }
            .hero h2 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
        }
    </style>
</head>
<body class="dark-theme">
<main>
    <div class="container">
        <div class="hero">
            <h2>Discover Local Treasures</h2>
            <p>Support local entrepreneurs in your community. Shop unique products with secure payments and reliable delivery.</p>
        </div>

        <div class="glass-card">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2.2rem; margin-bottom: 12px; color: #f1f5f9;">Featured Products</h2>
                <p style="color: #94a3b8; font-size: 1.05rem; max-width: 600px; margin: 0 auto;">Handpicked items from our most trusted local sellers. Every purchase directly supports your community.</p>
            </div>

            <div class="product-grid">
                <?php if(count($featured_products) > 0): ?>
                    <?php foreach($featured_products as $product): ?>
                        <div class="product-card">
                            <?php if($product['image'] && file_exists($product['image'])): ?>
                                <img src="<?php echo $product['image']; ?>" class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div class="product-image" style="background:#1e293b; display:flex; align-items:center; justify-content:center; color:#64748b;">📷 No image</div>
                            <?php endif; ?>
                            <div class="product-info">
                                <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                                <div class="product-price">R <?php echo number_format($product['price'],2); ?></div>
                                <div class="product-meta">by <?php echo htmlspecialchars($product['seller_name']); ?> • <?php echo htmlspecialchars($product['seller_location']); ?></div>
                                <div class="product-rating">
                                    <?php 
                                    $rating = round($product['avg_rating'] ?? 0);
                                    for($i=1;$i<=5;$i++): 
                                        echo $i<=$rating ? '★' : '☆';
                                    endfor; 
                                    ?>
                                    (<?php echo number_format($product['avg_rating']??0,1); ?>)
                                </div>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm">View Details</a>
                                <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn-outline btn btn-sm" style="margin-top:8px; display:inline-block;">Add to Cart</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <p style="font-size: 1.1rem; color: #94a3b8;">No products yet. Be the first to <a href="register.php" style="color:#10b981;">register as a seller</a>!</p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="text-align: center; margin-top: 50px;">
                <a href="products.php" class="btn btn-large" style="max-width: 300px; margin: 0 auto; display: inline-block;">Explore All Products →</a>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="glass-card" style="margin-top: 40px;">
            <h2 style="text-align: center; font-size: 2rem; margin-bottom: 40px; color: #f1f5f9;">How UbuntuBazaar Works</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🔍</div>
                    <h3 style="margin-bottom: 12px; color: #f1f5f9;">Browse & Search</h3>
                    <p style="color: #94a3b8;">Explore thousands of unique products from local sellers across your community with advanced filtering.</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 16px;">🛒</div>
                    <h3 style="margin-bottom: 12px; color: #f1f5f9;">Add to Cart</h3>
                    <p style="color: #94a3b8;">Build your cart easily and proceed to secure checkout with multiple payment options.</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 16px;">📦</div>
                    <h3 style="margin-bottom: 12px; color: #f1f5f9;">Get Delivered</h3>
                    <p style="color: #94a3b8;">Choose local delivery options and receive your order with tracking directly in your area.</p>
                </div>
            </div>
        </div>

        <!-- Why UbuntuBazaar Section -->
        <div class="glass-card" style="margin-top: 40px; background: rgba(16, 185, 129, 0.03);">
            <h2 style="text-align: center; font-size: 2rem; margin-bottom: 40px; color: #f1f5f9;">Why Choose UbuntuBazaar?</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <div style="padding: 20px;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 12px;">🌍 Support Local</h3>
                    <p style="color: #94a3b8;">Every purchase directly supports local entrepreneurs and keeps money in your community.</p>
                </div>
                <div style="padding: 20px;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 12px;">✅ Verified Sellers</h3>
                    <p style="color: #94a3b8;">All sellers are verified and reviewed by the community. Shop with confidence.</p>
                </div>
                <div style="padding: 20px;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 12px;">🔒 Secure Payments</h3>
                    <p style="color: #94a3b8;">Multiple payment options and encrypted transactions keep your data safe.</p>
                </div>
                <div style="padding: 20px;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 12px;">🚚 Fast Delivery</h3>
                    <p style="color: #94a3b8;">Local delivery partners ensure your order arrives quickly and reliably.</p>
                </div>
                <div style="padding: 20px;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 12px;">⭐ Community Ratings</h3>
                    <p style="color: #94a3b8;">Read honest reviews from other buyers to make informed purchasing decisions.</p>
                </div>
                <div style="padding: 20px;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 12px;">🌐 Multilingual</h3>
                    <p style="color: #94a3b8;">Browse in English, isiZulu, or Afrikaans. Your marketplace, your language.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="glass-card" style="margin-top: 40px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(245, 158, 11, 0.1)); text-align: center;">
            <h2 style="font-size: 2rem; margin-bottom: 16px; color: #f1f5f9;">Ready to Become a Seller?</h2>
            <p style="font-size: 1.1rem; margin-bottom: 24px; color: #94a3b8;">Join thousands of local entrepreneurs earning from home. Sell what you make, craft, or offer services in your community.</p>
            <a href="register.php" class="btn" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none;">Start Selling Today</a>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
<script>
function changeLanguage(lang) {
    fetch('set_lang.php?lang='+lang)
        .then(() => window.location.reload());
}
</script>
</body>
</html>
