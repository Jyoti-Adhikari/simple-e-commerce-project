<?php
$conn = new mysqli('localhost', 'root', '', 'product_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM admin WHERE name='$name' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        header("Location: admin.html");
        exit();
    } else {
        echo "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login</title>
</head>
<body>
    <h1>Administrator Login</h1>
    <form method="POST" action="administrator.php">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <a href="index.html">Return Back</a>
</body>
</html>
