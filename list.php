<?php
$conn = new mysqli("localhost", "root", "", "product_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1 class='title'>Category and Product List</h1>";

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

if (!empty($data)) {
    echo "<ul>";
    foreach ($data as $category => $products) {
        echo "<li><strong>$category</strong></li>";
        if (!empty($products)) {
            echo "<ul>";
            foreach ($products as $product) {
                echo "<li>{$product['name']} - <strong>Price: Rs. {$product['price']}</strong></li>";
            }
            echo "</ul>";
        } else {
            echo "<ul><li>No products available</li></ul>";
        }
    }
    echo "</ul>";
} else {
    echo "No data available.";
}

echo "<br><a href='admin.html'>Return to Home</a>";

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category and Product List</title>
</head>
<body>
</body>
</html>
