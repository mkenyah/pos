<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PROJECTLIGHT";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // FETCHING INPUTS
    $pname = $_POST['pname'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $pprice = $_POST['pprice'];
    $ppbottle = $_POST['ppbottle'];
    $userIncharge = $_POST['userIncharge'];

    
    $sql = "INSERT INTO productlist (product_name, product_category, product_quantity, stock_price, price_per_bottle, userIncharge)
            VALUES ('$pname', '$category', '$quantity', '$pprice', '$ppbottle', '$userIncharge')";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// FETCH ALL PRODUCTS
$sql = "SELECT * FROM productlist";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>New Stock</title>
    <style>
        table, th, td {
            border: 1px solid gray;
            border-collapse: collapse;
            padding: 11px;
        }
        th, td {
            text-align: center;
        }
        section {
            margin: 19px;
        }
    </style>
</head>
<body>
    <form action="newstock.php" method="post">
        <h4 class="title">Add New Product</h4>
        <h5 class="title">Product Name</h5>
        <input class="input" type="text" name="pname" required>
        <h5>Product Category</h5>
        <select class="input name="category" required>
            <option value="" selected disabled></option>
            <option value="wine">Wine</option>
            <option value="spirit">Spirit</option>
            <option value="soda">Soda</option>
            <option value="beer">Beer</option>
            <option value="whiskey">Whiskey</option>
            <option value="vodka">Vodka</option>
            <option value="gin">Gin</option>
            <option value="champagne">Champagne</option>
            <option value="tequila">Tequila</option>
            <option value="minuteMaid">Minute Maid</option>
            <option value="energyDrink">Energy Drink</option>
        </select>
        <h5 class="title">Product Quantity</h5>
        <input class="input type="number" name="quantity" required>
        <h5 class="title">Stock Price</h5>
        <input class="input type="number" name="pprice" required>
        <h5 class="title">Price Per Bottle</h5>
        <input class="input type="number" name="ppbottle" required>
        <h5 class="title">Employee in Charge (Employee ID)</h5>
        <input class="input type="text" name="userIncharge" required>

        <button class="btn" type="submit" name="submit">Add Product</button>
    </form>

    <section>
        <h4>Product List</h4>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Total Stock Price</th>
                    <th>Price per Bottle</th>
                    <th>User in Charge</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['product_category']}</td>
                                <td>{$row['product_quantity']}</td>
                                <td>{$row['stock_price']}</td>
                                <td>{$row['price_per_bottle']}</td>
                                <td>{$row['userIncharge']}</td>
                                <td>{$row['date_added']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No products available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

</body>
</html>

<?php
$conn->close();
?>
