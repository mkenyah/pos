<?php
include('config.php');
session_start();

// Handle new product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addProduct'])) {
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $pricePerBottle = $_POST['price_per_bottle'];
    $expectedProfit = $quantity * $pricePerBottle;

    $sql = "INSERT INTO products (product_name, category, quantity, price_per_bottle, expected_profit) 
            VALUES ('$productName', '$category', $quantity, $pricePerBottle, $expectedProfit)";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $conn->query("DELETE FROM products WHERE product_id = '$deleteId'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Product Management</h2>

    <!-- Add New Product Form -->
    <form method="POST" action="product_management.php">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="text" name="category" placeholder="Category" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" name="price_per_bottle" placeholder="Price per Bottle" step="0.01" required>
        <button type="submit" name="addProduct">Add Product</button>
    </form>

    <!-- Products Table -->
    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price per Bottle</th>
                <th>Expected Profit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['price_per_bottle']}</td>
                        <td>{$row['expected_profit']}</td>
                        <td>
                            <a href='edit_product.php?id={$row['product_id']}'>Edit</a> |
                            <a href='product_management.php?delete_id={$row['product_id']}'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
