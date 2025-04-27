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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($customerName); ?>!</h2>

    <div class="mb-5">
        <h3>Select Products</h3>
        <?php foreach ($categories as $category): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="mb-0"><?php echo htmlspecialchars($category['C_name']); ?></h4>
                </div>
                <div class="card-body">
                    <?php
                    $productStmt = $pdo->prepare("SELECT * FROM product WHERE C_id = ?");
                    $productStmt->execute([$category['C_id']]);
                    $products = $productStmt->fetchAll();
                    foreach ($products as $product):
                    ?>
                        <form method="POST" class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <h5><?php echo htmlspecialchars($product['P_name']); ?> - $<?php echo $product['price']; ?></h5>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="product_id" value="<?php echo $product['P_id']; ?>">
                                    <input type="number" name="quantity" min="1" value="1" class="form-control" required>
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary me-2">Add to Cart</button>
                                    <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger">Add to Wishlist</button>
                                </div>
                            </div>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mb-5">
        <h3>Your Shopping Cart</h3>
        <ul class="list-group mb-3">
            <?php
            $totalPrice = 0;
            foreach ($cartItems as $item):
                $productStmt = $pdo->prepare("SELECT P_name FROM product WHERE P_id = ?");
                $productStmt->execute([$item['product_id']]);
                $product = $productStmt->fetch();

                $itemTotal = $item['price'] * $item['quantity'];
                $totalPrice += $itemTotal;
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($product['P_name']); ?> - Qty: <?php echo $item['quantity']; ?> - Price: $<?php echo $item['price']; ?> - Total: $<?php echo $itemTotal; ?>
                    <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="fw-bold">Total: $<?php echo $totalPrice; ?></p>
    </div>

    <div>
        <h3>Your Wishlist</h3>
        <ul class="list-group">
            <?php foreach ($wishlistItems as $item): ?>
                <?php
                $productStmt = $pdo->prepare("SELECT P_name FROM product WHERE P_id = ?");
                $productStmt->execute([$item['product_id']]);
                $product = $productStmt->fetch();
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($product['P_name']); ?> - Qty: <?php echo $item['quantity']; ?> - Price: $<?php echo $item['price']; ?>
                    <div>
                        <a href="move_to_cart.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-success me-2">Move to Cart</a>
                        <a href="remove_from_wishlist.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
