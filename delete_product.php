<?php
require 'config.php';
if(!isSeller()) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
header("Location: seller_dashboard.php");
exit;
?>