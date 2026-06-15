<?php
require 'config.php';

// Get filter inputs
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$location_filter = $_GET['location'] ?? '';

$query = "SELECT p.*, u.fullname as seller_name, u.location as seller_location,
          (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
          FROM products p 
          JOIN users u ON p.seller_id = u.id 
          WHERE p.status = 'active'";

$params = [];

if ($category) {
    $query .= " AND p.category = :category";
    $params['category'] = $category;
}
if ($search) {
    $query .= " AND p.name LIKE :search";
    $params['search'] = "%$search%";
}
if ($location_filter) {
    $query .= " AND u.location LIKE :location";
    $params['location'] = "%$location_filter%";
}
$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get cart count
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
?>

<?php if(isAdmin()): ?>
    <a href="admin/index.php">Admin Panel</a>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C2C Market - Buy & Sell Locally</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="container header-inner">
        <div class="logo">
            <h1>C2C<span>Market</span></h1>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <?php if(isLoggedIn()): ?>
                <?php if(isSeller()): ?>
                    <a href="seller_dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
            <a href="cart.php" class="cart-link">🛒 <span class="cart-count"><?php echo $cart_count; ?></span></a>
            <!-- Language Switcher (simple) -->
            <select id="langSwitcher" onchange="changeLanguage(this.value)">
                <option value="en" <?php echo ($lang=='en')?'selected':''; ?>>English</option>
                <option value="zu" <?php echo ($lang=='zu')?'selected':''; ?>>isiZulu</option>
                <option value="af" <?php echo ($lang=='af')?'selected':''; ?>>Afrikaans</option>
            </select>
        </nav>
    </div>
</header>

<main class="container">
    <div class="hero">
        <h2><?php echo __('welcome'); ?></h2>
        <p>Discover unique items from local sellers in your community. Secure payments, local delivery.</p>
    </div>

    <!-- Filters and Search -->
    <div class="filters-bar">
        <div class="filter-group">
            <label>Search products</label>
            <input type="text" id="searchInput" placeholder="e.g. handmade scarf" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="filter-group">
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
        <div class="filter-group">
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
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <?php if($product['image']): ?>
                        <img src="<?php echo $product['image']; ?>" class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div class="product-image" style="background:#e9ecef; display:flex; align-items:center; justify-content:center;">📷 No image</div>
                    <?php endif; ?>
                    <div class="product-info">
                        <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price">R <?php echo number_format($product['price'],2); ?></div>
                        <div class="product-meta">
                            by <?php echo htmlspecialchars($product['seller_name']); ?> • <?php echo htmlspecialchars($product['seller_location']); ?>
                        </div>
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
            <p>No products found. Be the first to <a href="register.php">register as a seller</a>!</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>&copy; 2026 C2C Market - Empowering local South African sellers</p>
</footer>

<script>
    function updateCartCount() {
    fetch('cart_count.php')
        .then(res => res.json())
        .then(data => {
            document.querySelector('.cart-count').innerText = data.count;
        });
}
    
function applyFilters() {
    let category = document.getElementById('categorySelect').value;
    let search = document.getElementById('searchInput').value;
    let location = document.getElementById('locationInput').value;
    window.location.href = `index.php?category=${encodeURIComponent(category)}&search=${encodeURIComponent(search)}&location=${encodeURIComponent(location)}`;
}
function resetFilters() {
    window.location.href = 'index.php';
}
function changeLanguage(lang) {
    fetch('set_lang.php?lang='+lang)
        .then(() => window.location.reload());
}
</script>
</body>
</html>