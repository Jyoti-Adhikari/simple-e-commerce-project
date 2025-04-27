<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <?php
    $conn = new mysqli("localhost", "root", "", "product_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $categories = $conn->query("SELECT c_id, c_name FROM category");

    $selected_category = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    ?>

    <form action="" method="POST">
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="this.form.submit()" required>
            <option value="">--Select a Category--</option>
            <?php
            while ($row = $categories->fetch_assoc()) {
                $selected = ($row['c_id'] == $selected_category) ? "selected" : "";
                echo "<option value='" . $row['c_id'] . "' $selected>" . $row['c_name'] . "</option>";
            }
            ?>
        </select>
    </form>

    <?php
    if (!empty($selected_category)) {
    ?>
        <form action="product_success.php" method="POST">
            <input type="hidden" name="category_id" value="<?php echo $selected_category; ?>">

            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required><br><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required><br><br>

            <button type="submit">Add Product</button>
        </form>
    <?php
    }
    $conn->close();
    ?>
</body>
</html>
