<?php
require '../config.php';
if(!isAdmin()) { die("Access denied"); }

if(isset($_GET['approve'])) {
    $stmt = $pdo->prepare("UPDATE users SET verification_status = 'approved' WHERE id = ?");
    $stmt->execute([$_GET['approve']]);
    header("Location: verify_sellers.php");
    exit;
}

$pending = $pdo->query("SELECT * FROM users WHERE is_seller = 1 AND verification_status = 'pending'")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Verify Sellers</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="container">
    <h2>Pending Seller Verifications</h2>
    <?php foreach($pending as $seller): ?>
        <div style="border:1px solid #ccc; margin:10px 0; padding:10px;">
            <strong><?php echo htmlspecialchars($seller['fullname']); ?></strong> (<?php echo htmlspecialchars($seller['email']); ?>)
            <a href="verify_sellers.php?approve=<?php echo $seller['id']; ?>" class="btn">Approve</a>
            <?php if($seller['id_document']): ?>
                <a href="<?php echo $seller['id_document']; ?>" target="_blank">View ID</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <a href="index.php">Back to Admin</a>
</div>
</body>
</html>