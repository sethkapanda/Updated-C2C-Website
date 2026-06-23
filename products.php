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

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$location_filter = $_GET['location'] ?? '';

$query = "SELECT p.*, u.fullname as seller_name, u.location as seller_location,
          (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
          FROM products p 
          JOIN users u ON p.seller_id = u.id 
          WHERE p.status = 'active'";

$params = [];
if ($category) { $query .= " AND p.category = :category"; $params['category'] = $category; }
if ($search) { $query .= " AND p.name LIKE :search"; $params['search'] = "%$search%"; }
if ($location_filter) { $query .= " AND u.location LIKE :location"; $params['location'] = "%$location_filter%"; }
$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

foreach ($products as &$product) {
    $mapped_filename = $image_map[$product['id']] ?? '';
    if ($mapped_filename) {
        $mapped_path = 'assets/uploads/products/' . $mapped_filename;
        if (file_exists($mapped_path)) {
            $product['image'] = $mapped_path;
        }
    }
}

$cart_count = 0;
if (isset($_SESSION['cart'])) { $cart_count = array_sum($_SESSION['cart']); }
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products · UbuntuBazaar</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card">
        <a href="javascript:history.back()" class="back-link">← Back</a>
        <div style="margin: 20px 0;">
            <h1 style="font-size: 2.2rem; margin-bottom: 8px;">Shop from Local Sellers</h1>
            <p style="color: #94a3b8; font-size: 1.05rem;">Browse all products from verified local entrepreneurs</p>
        </div>

        <div class="filters-bar" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 20px; display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; margin-bottom: 32px;">
            <div class="filter-group" style="flex:1; min-width:150px;">
                <label>Search products</label>
                <input type="text" id="searchInput" placeholder="e.g. handmade scarf" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="filter-group" style="flex:1; min-width:150px;">
                <label>Category</label>
                <select id="categorySelect">
                    <option value="">All Categories</option>
                    <option value="Home" <?php echo $category=='Home'?'selected':''; ?>>Home & Living</option>
                    <option value="Technology" <?php echo $category=='Technology'?'selected':''; ?>>Technology</option>
                    <option value="Fashion" <?php echo $category=='Fashion'?'selected':''; ?>>Fashion</option>
                    <option value="Electronics" <?php echo $category=='Electronics'?'selected':''; ?>>Electronics</option>
                    <option value="Collectibles" <?php echo $category=='Collectibles'?'selected':''; ?>>Collectibles</option>
                </select>
            </div>
            <div class="filter-group" style="flex:1; min-width:150px;">
                <label>Location</label>
                <input type="text" id="locationInput" placeholder="Township/City" value="<?php echo htmlspecialchars($location_filter); ?>">
            </div>
            <div>
                <button class="btn" onclick="applyFilters()">Filter</button>
                <button class="btn-outline btn" onclick="resetFilters()">Reset</button>
            </div>
        </div>

        <div class="product-grid">
            <?php if(count($products) > 0): ?>
                <div style="grid-column: 1 / -1; color: #94a3b8; margin-bottom: 20px;">Showing <?php echo count($products); ?> products</div>
                <?php foreach($products as $product): ?>
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
                <div style="grid-column:1/-1; text-align:center; padding:60px 20px;">
                    <div style="font-size:3rem; margin-bottom:16px;">🔍</div>
                    <p style="font-size:1.1rem; color:#94a3b8;">No products found. Try adjusting your filters.</p>
                    <a href="products.php" class="btn" style="display:inline-block; margin-top:20px;">View All Products</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script>
function applyFilters() {
    let category = document.getElementById('categorySelect').value;
    let search = document.getElementById('searchInput').value;
    let location = document.getElementById('locationInput').value;
    window.location.href = `products.php?category=${encodeURIComponent(category)}&search=${encodeURIComponent(search)}&location=${encodeURIComponent(location)}`;
}
function resetFilters() { window.location.href = 'products.php'; }
</script>
</body>
</html>
