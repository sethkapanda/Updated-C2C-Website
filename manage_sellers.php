<?php
require '../config.php';
if(!isAdmin()) { die("Access denied"); }

if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $seller_id = $_GET['delete'];
    if($seller_id != $_SESSION['user_id']) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND is_admin = 0");
        $stmt->execute([$seller_id]);
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
        $_SESSION['admin_msg'] = "Seller account deleted.";
    } else {
        $_SESSION['admin_msg'] = "You cannot delete your own admin account.";
    }
    header("Location: manage_sellers.php");
    exit;
}

$sellers = $pdo->query("SELECT id, email, fullname, phone, location, verification_status, created_at FROM users WHERE is_seller = 1 ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Manage Sellers</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="container">
    <h2>Manage Sellers</h2>
    <?php if(isset($_SESSION['admin_msg'])): ?>
        <div class="alert"><?php echo $_SESSION['admin_msg']; unset($_SESSION['admin_msg']); ?></div>
    <?php endif; ?>
    <a href="verify_sellers.php" class="btn">Pending Verifications</a>
    <a href="index.php" class="btn-outline btn">Admin Home</a>
    
    <table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Location</th><th>Verified</th><th>Joined</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach($sellers as $seller): ?>
            <tr>
                <td><?php echo $seller['id']; ?></td>
                <td><?php echo htmlspecialchars($seller['fullname']); ?></td>
                <td><?php echo htmlspecialchars($seller['email']); ?></td>
                <td><?php echo htmlspecialchars($seller['location']); ?></td>
                <td><?php echo ucfirst($seller['verification_status']); ?></td>
                <td><?php echo date('d M Y', strtotime($seller['created_at'])); ?></td>
                <td><a href="manage_sellers.php?delete=<?php echo $seller['id']; ?>" class="btn-danger" onclick="return confirm('Delete seller permanently?')">Delete</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>