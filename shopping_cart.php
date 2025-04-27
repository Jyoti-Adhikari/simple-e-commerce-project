<?php
session_start();
require_once('config.php');

// Check if the product ID is passed and customer is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['P_id'])) {
    if (isset($_SESSION['customer_name'])) {
        $product_id = $_POST['P_id'];
        $quantity = 1; // Default quantity
        $price = 1000.00; // Default price, or fetch dynamically as needed

        // Fetch product price from database if needed
        $stmt = $pdo->prepare("SELECT price FROM product WHERE P_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $price = $product['price'];
        }

        $customer_name = $_SESSION['customer_name']; // Get customer name from session

        // Insert into shopping_cart table
        $stmt = $pdo->prepare("INSERT INTO shopping_cart (customer_name, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$customer_name, $product_id, $quantity, $price]);

        echo "Product added to cart!";
        header("Location: customer.php");
        exit;
    } else {
        echo "Please log in first.";
    }
}
?>
