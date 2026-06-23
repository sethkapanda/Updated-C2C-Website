<?php
require '../config.php';
if(!isAdmin()) { die("Access denied. You are not an admin."); }
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel · UbuntuBazaar</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="dark-theme">
<div class="container">
    <div class="glass-card" style="max-width:800px;">
        <h2 style="color:#f1f5f9;">Admin Dashboard</h2>
        <ul style="list-style:none; padding:0;">
            <li style="margin:10px 0;"><a href="verify_sellers.php" class="btn" style="display:inline-block;">Verify Seller IDs</a></li>
            <li style="margin:10px 0;"><a href="manage_sellers.php" class="btn" style="display:inline-block;">Manage All Sellers</a></li>
        </ul>
        <a href="../index.php" class="back-link">← Back to Home</a>
    </div>
</div>
</body>
</html>
