<?php
require '../config.php';
if(!isAdmin()) { die("Access denied. You are not an admin."); }
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="verify_sellers.php">Verify Seller IDs</a></li>
        <li><a href="manage_sellers.php">Manage All Sellers (Delete)</a></li>
    </ul>
    <a href="../index.php" class="btn">Back to Home</a>
</div>
</body>
</html>