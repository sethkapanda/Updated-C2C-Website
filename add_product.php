<?php
require 'config.php';
if(!isSeller()) { header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $location = $_POST['location'];
    
    $image = '';
    if($_FILES['image']['error'] == 0) {
        $target = 'assets/uploads/products/'.time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image = $target;
    }
    
    $stmt = $pdo->prepare("INSERT INTO products (seller_id, name, description, price, stock, location, image) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $name, $desc, $price, $stock, $location, $image]);
    header("Location: seller_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Product</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
    <a href="seller_dashboard.php" class="back-link">← Back to Dashboard</a>
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Product Name</label> <input type="text" name="name" required>
        <label>Description</label> <textarea name="description"></textarea>
        <label>Price (R)</label> <input type="number" step="0.01" name="price" required>
        <label>Stock</label> <input type="number" name="stock" value="1">
        <label>Location (township/city)</label> <input type="text" name="location" required>
        <label>Product Image</label> <input type="file" name="image">
        <button type="submit" class="btn">Save Product</button>
    </form>
</div>
</body>
</html>