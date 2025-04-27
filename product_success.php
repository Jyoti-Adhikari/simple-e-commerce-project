<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Added</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h1 class="text-center my-4">Product Added Successfully</h1>

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
            echo "<div class='alert alert-success'>
                    Product '<b>" . htmlspecialchars($product_name) . "</b>' has been added successfully with price <b>Rs. " . htmlspecialchars(number_format($price, 2)) . "</b>.
                  </div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    $conn->close();
    ?>

    <div class="text-center mt-4">
        <a href="admin.html" class="btn btn-primary">Return to Home</a>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
