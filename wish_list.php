<?php
session_start();
require_once('config.php');

// Check if the product ID is passed and customer is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['P_id'])) {
    if (isset($_SESSION['customer_name'])) {
        $product_id = $_POST['P_id'];
        $customer_name = $_SESSION['customer_name']; // Get customer name from session

        // Insert into wish_list table
        $stmt = $pdo->prepare("INSERT INTO wish_list (customer_name, product_id) VALUES (?, ?)");
        $stmt->execute([$customer_name, $product_id]);

        echo "Product added to wishlist!";
        header("Location: customer.php");
        exit;
    } else {
        echo "Please log in first.";
    }
}
?>
