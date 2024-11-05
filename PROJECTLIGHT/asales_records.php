<?php
include('config.php');
session_start();

// Handle new sale addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addSale'])) {
    $productId = $_POST['product_id'];
    $quantitySold = $_POST['quantity_sold'];
    $userIncharge = $_SESSION['userIncharge'];
    $salePrice = $_POST['sale_price'];

    // Get product details to calculate total and update quantity
    $product = $conn->query("SELECT * FROM products WHERE product_id = '$productId'")->fetch_assoc();
    $totalSaleAmount = $quantitySold * $salePrice;
    $newQuantity = $product['quantity'] - $quantitySold;

    // Update product quantity
    if ($newQuantity >= 0) {
        $conn->query("UPDATE products SET quantity = $newQuantity WHERE product_id = '$productId'");

        // Insert new sale record
        $sql = "INSERT INTO sales (product_id, quantity_sold, sale_price, userIncharge, product_name, category, kshSold)
                VALUES ('$productId', $quantitySold, $salePrice, '$userIncharge', '{$product['product_name']}', '{$product['category']}', $totalSaleAmount)";
        
        if ($conn->query($sql) === TRUE) {
            echo "New sale record added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Insufficient stock for this sale.";
    }
}

// Handle sale deletion
if (isset($_GET['delete_sale_id'])) {
    $deleteId = $_GET['delete_sale_id'];
    $conn->query("DELETE FROM sales WHERE sale_id = '$deleteId'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Records</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Sales Records</h2>

    <!-- Add New Sale Form -->
    <form method="POST" action="sales_records.php">
        <label for="product_id">Product:</label>
        <select name="product_id" required>
            <?php
            $products = $conn->query("SELECT * FROM products");
            while ($product = $products->fetch_assoc()) {
                echo "<option value='{$product['product_id']}'>{$product['product_name']} - {$product['category']}</option>";
            }
            ?>
        </select>
        
        <input type="number" name="quantity_sold" placeholder="Quantity Sold" required>
        <input type="number" name="sale_price" placeholder="Sale Price" step="0.01" required>
        <button type="submit" name="addSale">Add Sale</button>
    </form>

    <!-- Sales Records Table -->
    <table border="1">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Quantity Sold</th>
                <th>Sale Price</th>
                <th>Total Sale Amount (Ksh)</th>
                <th>User In Charge</th>
                <th>Sale Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sales = $conn->query("SELECT * FROM sales");
            while ($sale = $sales->fetch_assoc()) {
                echo "<tr>
                        <td>{$sale['sale_id']}</td>
                        <td>{$sale['product_name']}</td>
                        <td>{$sale['category']}</td>
                        <td>{$sale['quantity_sold']}</td>
                        <td>{$sale['sale_price']}</td>
                        <td>{$sale['kshSold']}</td>
                        <td>{$sale['userIncharge']}</td>
                        <td>{$sale['sale_date']}</td>
                        <td>
                            <a href='sales_records.php?delete_sale_id={$sale['sale_id']}'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
