<?php
require 'config.php';
if(!isSeller()) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$product = $stmt->fetch();
if(!$product) die("Product not found");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $location = $_POST['location'];
    
    $update = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, location=? WHERE id=?");
    $update->execute([$name, $desc, $price, $stock, $location, $id]);
    header("Location: seller_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Product</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
    <a href="seller_dashboard.php" class="back-link">← Back to Dashboard</a>
    <h2>Edit Product</h2>
    <form method="POST">
        <label>Product Name</label> <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        <label>Description</label> <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <label>Price (R)</label> <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        <label>Stock</label> <input type="number" name="stock" value="<?php echo $product['stock']; ?>">
        <label>Location</label> <input type="text" name="location" value="<?php echo htmlspecialchars($product['location']); ?>" required>
        <button type="submit" class="btn">Update Product</button>
    </form>
</div>
</body>
</html>