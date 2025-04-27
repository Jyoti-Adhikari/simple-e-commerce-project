<?php
$conn = new mysqli("localhost", "root", "", "product_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1 class='text-center my-4'>Category and Product List</h1>";

$result = $conn->query("SELECT c.c_name, p.p_name, p.price FROM category c 
                        LEFT JOIN product p ON c.c_id = p.c_id");

$data = [];
while ($row = $result->fetch_assoc()) {
    $category_name = $row['c_name'];
    $product_name = $row['p_name'];
    $price = isset($row['price']) ? number_format($row['price'], 2) : "N/A"; // Format price

    if (!isset($data[$category_name])) {
        $data[$category_name] = [];
    }
    if ($product_name) {
        $data[$category_name][] = ["name" => $product_name, "price" => $price];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category and Product List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <?php
    if (!empty($data)) {
        echo "<div class='row'>";
        foreach ($data as $category => $products) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card'>";
            echo "<div class='card-header bg-primary text-white'><strong>$category</strong></div>";
            echo "<div class='card-body'>";
            if (!empty($products)) {
                echo "<ul class='list-group'>";
                foreach ($products as $product) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            {$product['name']} 
                            <span class='badge bg-secondary'>Rs. {$product['price']}</span>
                          </li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='text-center'>No products available</p>";
            }
            echo "</div></div></div>";
        }
        echo "</div>";
    } else {
        echo "<p class='text-center'>No data available.</p>";
    }
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

<?php
$conn->close();
?>
