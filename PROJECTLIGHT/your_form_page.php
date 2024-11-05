<?php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products with quantity greater than zero
$sql = "SELECT p.*, u.full_name FROM products p JOIN users u ON p.user_in_charge = u.user_id WHERE p.quantity > 0 ORDER BY p.time_added DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        section {
            margin: 19px;
            background: white;
            border-radius: 8px;
            /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
            padding: 20px;
        }

        h4 {
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            margin-bottom: 10px;
            display: inline-block;
        }

        /* a:hover {
            background-color: #f28a0a;
            color: black;
        } */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
       
        table, th, td {
            border: 1px solid #f28a0a;
            padding: 11px;
        }

        th, td {
            text-align: center;
        }
        th {
            background-color: #f28a0a;
            color: white;
        }

        /* Styles for background colors based on product quantity */
        .low-stock {
            background-color: red; /* Light red for low stock */
        }

        .medium-stock {
            background-color: white; /* Light orange for medium stock */
        }

        .button-container {
            margin-bottom: 20px;
        }

        .button {
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin-right: 60px;
            text-decoration: none;
            display: flex;
        }

        .button:hover {
            background-color: #e07a0a; /* Darker shade on hover */
        }
        h2 {
            text-align: center;
        }
        .btns{
            
            
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 50px;
            
        }
        .btns:hover {
            color: #000;
            background-color: #f28a0a;
       
        }
        /* .butns{
            gap: none;
             display: flex;
    justify-content: center;
    align-items: center;
        } */
        
    </style>
</head>
<body>

<div class="butns">
    <a class="btns" href="newstock.php">Add New Product</a>
    <a class="btns" href="dashboard.php">Go to Dashboard</a>
    <a class="btns" href="sellproduct.php">Sell a Product</a>
    <a class="btns" href="sales.php">View sales</a>
</div>

<h2>Product List</h2>
<table>
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Total Stock Price</th>
            <th>Price per Bottle</th>
            <th>Expected Profit</th>
            <th>User in Charge</th>
            <th>Date Added</th>
            <th>Time Added</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr class="<?php echo $row['quantity'] < 6 ? 'low-stock' : ''; ?>">
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo number_format($row['stock_price'], 2); ?></td>
            <td><?php echo number_format($row['price_per_bottle'], 2); ?></td>
            <td><?php echo number_format($row['expected_profit'], 2); ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['date_added']; ?></td>
            <td><?php echo $row['time_added']; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>">Edit</a>
                <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
</tbody>

</table>

</body>
</html>

<?php $conn->close(); ?>
