<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['customer_name'])) {
    header('Location: user.php');
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM shopping_cart WHERE id = ? AND customer_name = ?");
    $stmt->execute([$_GET['id'], $_SESSION['customer_name']]);
}

header('Location: customer.php');
exit;
