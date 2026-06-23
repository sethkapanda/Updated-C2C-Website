<?php
require 'config.php';
if(!isSeller()) { header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $location = $_POST['location'];
    $category = $_POST['category'] ?? 'Home';
    
    $image = '';
    if($_FILES['image']['error'] == 0) {
        $target = 'assets/uploads/products/'.time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image = $target;
    }
    
    $stmt = $pdo->prepare("INSERT INTO products (seller_id, name, description, category, price, stock, location, image) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $name, $desc, $category, $price, $stock, $location, $image]);
    header("Location: seller_dashboard.php");
    exit;
}
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head><title>Add Product · UbuntuBazaar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card" style="max-width:700px;">
        <a href="seller_dashboard.php" class="back-link">← Back to Dashboard</a>
        <h2 style="color:#f1f5f9;">Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="Home">Home & Living</option>
                    <option value="Technology">Technology</option>
                    <option value="Fashion">Fashion</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Collectibles">Collectibles</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price (R)</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" value="1">
            </div>
            <div class="form-group">
                <label>Location (township/city)</label>
                <input type="text" name="location" required>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image">
            </div>
            <button type="submit" class="btn">Save Product</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
