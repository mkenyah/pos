<?php
session_start(); // Start the session

$servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the sales table to retrieve the sold items
$salesQuery = "SELECT product_id, product_name, category, quantity_sold, kshSold, price_per_bottle, userIncharge, sale_date 
               FROM sales ORDER BY sale_date DESC"; // Sort by most recent sale
$result = $conn->query($salesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        .navigation-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .navigation-buttons button {
            background-color: #f28a0a;
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .navigation-buttons button:hover {
            background-color: #f08a0a;
            color: black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid  #f28a0a;
            text-align: left;
        }
        th {
            color: #ddd;
            background-color:  #f28a0a;
        }
    </style>
</head>
<body>

<div class="navigation-buttons">
    <button onclick="location.href='sellproduct.php'">Sell a Product</button>
    <button onclick="location.href='dashboard.php'">Go to Dashboard</button>
    <button onclick="location.href='your_form_page.php'">view products</button>
</div>

<h2>Sales Records</h2>

<table>
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Quantity Sold</th>
            <th>Sale Price (Ksh)</th>
            <th>Price per Bottle (Ksh)</th>
            <th>User In Charge</th>
            <th>Sale Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['product_id'] . "</td>
                        <td>" . $row['product_name'] . "</td>
                        <td>" . $row['category'] . "</td>
                        <td>" . $row['quantity_sold'] . "</td>
                        <td>" . number_format($row['kshSold'], 2) . "</td>
                        <td>" . number_format($row['price_per_bottle'], 2) . "</td>
                        <td>" . $row['userIncharge'] . "</td>
                        <td>" . $row['sale_date'] . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No sales records found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
