<?php
require 'config.php';

$uploadDir = 'assets/uploads/products/';
$updated = 0;
$notFound = [];

// Get all products
$products = $pdo->query("SELECT id, name FROM products")->fetchAll();

foreach($products as $product) {
    $productName = $product['name'];
    // Create a safe filename pattern: remove special chars, allow spaces, case-insensitive
    $pattern = '/' . preg_quote($productName, '/') . '\.[a-z]+$/i';
    
    // Scan the uploads directory for matching files
    $files = scandir($uploadDir);
    $matched = false;
    foreach($files as $file) {
        if ($file === '.' || $file === '..') continue;
        // Check if filename matches product name (case-insensitive, extension any)
        $baseName = pathinfo($file, PATHINFO_FILENAME);
        if (strcasecmp($baseName, $productName) === 0) {
            $imagePath = $uploadDir . $file;
            $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            $stmt->execute([$imagePath, $product['id']]);
            $updated++;
            $matched = true;
            echo "✅ Updated product ID {$product['id']} ('{$productName}') with image: $file<br>";
            break;
        }
    }
    if (!$matched) {
        $notFound[] = $productName;
    }
}

echo "<hr>";
echo "<strong>Summary:</strong> $updated products updated.<br>";
if (!empty($notFound)) {
    echo "<strong>Products without matching images:</strong><br>";
    foreach($notFound as $name) {
        echo "❌ $name<br>";
    }
    echo "<br>Make sure your image filenames exactly match the product names (case-insensitive).";
} else {
    echo "All products now have images!";
}
?>