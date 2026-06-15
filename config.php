<?php
session_start();

$host = 'localhost';
$dbname = 'c2c_platform';
$username = 'root';   
$password = '';       

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Language setup
$lang = $_SESSION['lang'] ?? 'en';
$translations = include "lang/$lang.php";

function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isSeller() {
    return isset($_SESSION['is_seller']) && $_SESSION['is_seller'] == 1;
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
?>