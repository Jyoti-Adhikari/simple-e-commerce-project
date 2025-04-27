<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];

    // Insert into customer table
    $stmt = $pdo->prepare("INSERT INTO customer (name, address) VALUES (?, ?)");
    $stmt->execute([$name, $address]);

    // Store customer name in session
    $_SESSION['customer_name'] = $name;

    // Redirect to customer.php
    header('Location: customer.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<body>
    <h2>Enter Your Information</h2>
    <form method="POST">
        <label for="name">Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" name="address" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
