<?php
$conn = new mysqli("localhost", "root", "", "product_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories = $conn->query("SELECT c_id, c_name FROM category");

$selected_category = isset($_POST['category_id']) ? $_POST['category_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="text-center mb-4">Add Product</h1>
    
    <form action="" method="POST" class="mb-4">
        <div class="form-group">
            <label for="category_id">Select Category:</label>
            <select id="category_id" name="category_id" class="form-control" onchange="this.form.submit()" required>
                <option value="">--Select a Category--</option>
                <?php
                while ($row = $categories->fetch_assoc()) {
                    $selected = ($row['c_id'] == $selected_category) ? "selected" : "";
                    echo "<option value='" . $row['c_id'] . "' $selected>" . $row['c_name'] . "</option>";
                }
                ?>
            </select>
        </div>
    </form>

    <?php
    if (!empty($selected_category)) {
    ?>
        <form action="product_success.php" method="POST">
            <input type="hidden" name="category_id" value="<?php echo $selected_category; ?>">

            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Add Product</button>
        </form>
    <?php
    }
    $conn->close();
    ?>
</div>

<!-- Link to Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
