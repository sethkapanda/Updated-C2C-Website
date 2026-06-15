<?php
require 'config.php';
if(!isSeller()) { header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target = 'assets/uploads/id_docs/'.time().'_'.basename($_FILES['id_doc']['name']);
    move_uploaded_file($_FILES['id_doc']['tmp_name'], $target);
    $stmt = $pdo->prepare("UPDATE users SET id_document = ?, verification_status = 'pending' WHERE id = ?");
    $stmt->execute([$target, $_SESSION['user_id']]);
    $msg = "Verification request submitted. Admin will review.";
}
?>
<!DOCTYPE html>
<html>
<head><title>Verify Identity</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
    <h2>Seller Identity Verification</h2>
    <?php if(isset($msg)) echo "<p style='color:green'>$msg</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Upload ID Document (PDF/Image)</label>
        <input type="file" name="id_doc" required>
        <button type="submit" class="btn">Submit for Verification</button>
    </form>
</div>
</body>
</html>