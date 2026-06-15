<?php
require 'config.php';

if(!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$product_id = $_POST['product_id'] ?? 0;
$rating = $_POST['rating'] ?? 0;
$comment = trim($_POST['comment'] ?? '');

if($product_id && $rating && $comment) {
    $buyer_id = $_SESSION['user_id'];
    
    // Verify user actually purchased this product (paid order)
    $check = $pdo->prepare("
        SELECT o.id FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        WHERE oi.product_id = ? AND o.buyer_id = ? AND o.payment_status = 'paid'
        LIMIT 1
    ");
    $check->execute([$product_id, $buyer_id]);
    $order = $check->fetch();
    
    if($order) {
        // Check if already reviewed
        $check_review = $pdo->prepare("SELECT id FROM reviews WHERE product_id = ? AND buyer_id = ?");
        $check_review->execute([$product_id, $buyer_id]);
        if($check_review->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO reviews (product_id, order_id, buyer_id, rating, comment) VALUES (?,?,?,?,?)");
            $stmt->execute([$product_id, $order['id'], $buyer_id, $rating, $comment]);
            $_SESSION['review_success'] = "Thank you for your review!";
        } else {
            $_SESSION['review_error'] = "You have already reviewed this product.";
        }
    } else {
        $_SESSION['review_error'] = "You can only review products you have purchased and paid for.";
    }
}

header("Location: product.php?id=$product_id");
exit;
?>