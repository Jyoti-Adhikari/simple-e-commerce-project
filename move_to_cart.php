<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['customer_name'])) {
    header('Location: user.php');
    exit;
}

$customerName = $_SESSION['customer_name'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get item from wishlist
    $stmt = $pdo->prepare("SELECT * FROM wish_list WHERE id = ? AND customer_name = ?");
    $stmt->execute([$id, $customerName]);
    $item = $stmt->fetch();

    if ($item) {
        $productId = $item['product_id'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        // Check if already in cart
        $stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE customer_name = ? AND product_id = ?");
        $stmt->execute([$customerName, $productId]);

        if ($stmt->rowCount() > 0) {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE shopping_cart SET quantity = quantity + ? WHERE customer_name = ? AND product_id = ?");
            $stmt->execute([$quantity, $customerName, $productId]);
        } else {
            // Insert into cart
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (customer_name, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customerName, $productId, $quantity, $price]);
        }

        // Delete from wishlist
        $stmt = $pdo->prepare("DELETE FROM wish_list WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header('Location: customer.php');
exit;
