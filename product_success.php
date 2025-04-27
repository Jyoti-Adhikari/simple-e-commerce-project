<!DOCTYPE html>
<html>
<head>
    <title>Product Added</title>
</head>
<body>
    <h1>Product Added Successfully</h1>
    <?php
    $conn = new mysqli("localhost", "root", "", "product_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_name = $_POST['product_name'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("INSERT INTO product (p_name, c_id, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $product_name, $category_id, $price); // s: string, i: integer, d: double

        if ($stmt->execute()) {
            echo "<p>Product '<b>" . htmlspecialchars($product_name) . "</b>' has been added successfully with price <b>Rs. " . htmlspecialchars(number_format($price, 2)) . "</b>.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $conn->close();
    ?>
    <a href="admin.html">Return to Home</a>
</body>
</html>
