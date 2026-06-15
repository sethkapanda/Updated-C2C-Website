<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $is_seller = isset($_POST['is_seller']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO users (email, password, fullname, phone, location, is_seller) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$email, $password, $fullname, $phone, $location, $is_seller]);
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
    <h2>Register</h2>
    <form method="POST">
        <label>Full Name</label> <input type="text" name="fullname" required>
        <label>Email</label> <input type="email" name="email" required>
        <label>Phone</label> <input type="text" name="phone">
        <label>Location (town/city)</label> <input type="text" name="location" required>
        <label>Password</label> <input type="password" name="password" required>
        <label><input type="checkbox" name="is_seller"> I want to sell products</label>
        <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have account? <a href="login.php">Login</a></p>
</div>
</body>
</html>