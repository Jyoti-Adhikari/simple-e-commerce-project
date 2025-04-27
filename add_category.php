<?php
$conn = new mysqli("localhost", "root", "", "product_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    $stmt = $conn->prepare("INSERT INTO category (c_name) VALUES (?)");
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
        echo "<div class='message'>Category added successfully.</div>";
        echo "<a href='admin.html' class='return-link'>Click to return</a>";
    } else {
        echo "<div class='message'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Added</title>
   
</head>
<body>
</body>
</html>
