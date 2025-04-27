<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['customer_name'])) {
    header('Location: user.php');
    exit;
}

$customerName = $_SESSION['customer_name'];

// Fetch all categories
$stmt = $pdo->query("SELECT * FROM category");
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Get price
        $stmt = $pdo->prepare("SELECT price FROM product WHERE P_id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        $price = $product['price'];

        // Check if already in cart
        $stmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE customer_name = ? AND product_id = ?");
        $stmt->execute([$customerName, $productId]);
        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (customer_name, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customerName, $productId, $quantity, $price]);
        }
    } elseif (isset($_POST['add_to_wishlist'])) {
        $productId = $_POST['product_id'];

        // Get price
        $stmt = $pdo->prepare("SELECT price FROM product WHERE P_id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        $price = $product['price'];

        // Insert with price and default quantity 1
        $stmt = $pdo->prepare("INSERT INTO wish_list (customer_name, product_id, price, quantity) VALUES (?, ?, ?, 1)");
        $stmt->execute([$customerName, $productId, $price]);
    }
}

// Fetch cart and wishlist items
$cartStmt = $pdo->prepare("SELECT * FROM shopping_cart WHERE customer_name = ?");
$cartStmt->execute([$customerName]);
$cartItems = $cartStmt->fetchAll();

$wishlistStmt = $pdo->prepare("SELECT * FROM wish_list WHERE customer_name = ?");
$wishlistStmt->execute([$customerName]);
$wishlistItems = $wishlistStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h2, h3, h4 {
            color: #2c3e50;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .section {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-bottom: 15px;
        }

        form input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-right: 10px;
        }

        form button {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            background-color: #3498db;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2980b9;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        li:last-child {
            border-bottom: none;
        }

        li a {
            color: #e74c3c;
            text-decoration: none;
            margin-left: 10px;
        }

        li a:hover {
            text-decoration: underline;
        }

        .total-price {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $customerName; ?>!</h2>

    <h3>Select Products</h3>
    <?php foreach ($categories as $category): ?>
        <h4><?php echo $category['C_name']; ?></h4>
        <?php
        $productStmt = $pdo->prepare("SELECT * FROM product WHERE C_id = ?");
        $productStmt->execute([$category['C_id']]);
        $products = $productStmt->fetchAll();
        foreach ($products as $product):
        ?>
            <form method="POST">
                <h5><?php echo $product['P_name']; ?> - $<?php echo $product['price']; ?></h5>
                <input type="hidden" name="product_id" value="<?php echo $product['P_id']; ?>">
                <input type="number" name="quantity" min="1" value="1" required>
                <button type="submit" name="add_to_cart">Add to Cart</button>
                <button type="submit" name="add_to_wishlist">Add to Wishlist</button>
            </form>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <h3>Your Shopping Cart</h3>
    <ul>
        <?php
        $totalPrice = 0;
        foreach ($cartItems as $item):
            $productStmt = $pdo->prepare("SELECT P_name FROM product WHERE P_id = ?");
            $productStmt->execute([$item['product_id']]);
            $product = $productStmt->fetch();

            $itemTotal = $item['price'] * $item['quantity'];
            $totalPrice += $itemTotal;
        ?>
            <li>
                <?php echo $product['P_name']; ?> - Quantity: <?php echo $item['quantity']; ?> - Price: $<?php echo $item['price']; ?> - Total: $<?php echo $itemTotal; ?>
                <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>">Remove</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total: $<?php echo $totalPrice; ?></strong></p>

    <h3>Your Wishlist</h3>
    <ul>
        <?php foreach ($wishlistItems as $item): ?>
            <?php
            $productStmt = $pdo->prepare("SELECT P_name FROM product WHERE P_id = ?");
            $productStmt->execute([$item['product_id']]);
            $product = $productStmt->fetch();
            ?>
            <li>
                <?php echo $product['P_name']; ?> - Quantity: <?php echo $item['quantity']; ?> - Price: $<?php echo $item['price']; ?>
                <a href="move_to_cart.php?id=<?php echo $item['id']; ?>">Move to Cart</a> |
                <a href="remove_from_wishlist.php?id=<?php echo $item['id']; ?>">Remove</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
