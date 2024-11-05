<?php
// sales_report.php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$dbusername = "root";
$dbpassword = "";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Logic for fetching and displaying sales report
$productName = $_POST['product_name'] ?? 'All';
$categoryName = $_POST['category_name'] ?? 'All';

// Query to fetch sales based on filters
$query = "SELECT * FROM sales WHERE 1=1";
if ($productName !== 'All') {
    $query .= " AND product_id IN (SELECT product_id FROM products WHERE product_name = '$productName')";
}
if ($categoryName !== 'All') {
    $query .= " AND product_id IN (SELECT product_id FROM products WHERE category = '$categoryName')";
}
$result = mysqli_query($conn, $query);

// Fetch all field names from the sales table
$fields = [];
if ($result && mysqli_num_rows($result) > 0) {
    $fields = array_keys(mysqli_fetch_assoc($result));
    // Move the pointer back to the beginning of the result set
    mysqli_data_seek($result, 0);
}

// Fetch product names and categories for dropdowns
$productQuery = "SELECT DISTINCT product_name FROM products";
$categoryQuery = "SELECT DISTINCT category FROM products";
$productResult = mysqli_query($conn, $productQuery);
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        /* Page styling */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h3, .real-time-date {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #f28a0a;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f28a0a;
        }
        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .select-category {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
            border: 1px solid #f28a0a;
        }
        .button, .print-button, .nav-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .print-button:hover, .button:hover, .nav-button:hover {
            color: black;
            background-color: #e67e22;
        }
        .nav-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
            h1, h3, .real-time-date {
                margin: 0;
                text-align: center;
            }
            table {
                margin: 0 auto;
                width: auto;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <button class="nav-button" onclick="window.location.href='report.php';">Back to Reports</button>
        <button class="nav-button" onclick="window.location.href='admin.php';">Go to Dashboard</button>
    </div>

    <!-- <h1>WINSP</h1>
    <h3>Sales Report</h3>
    <p class="real-time-date">Date: <?php echo date('Y-m-d H:i:s'); ?></p> -->

    <!-- Filter Form -->
    <div class="filter-container">
        <form method="post" action="sales_report.php">
            <select name="product_name" class="select-category">
                <option value="All">All Products</option>
                <?php while ($product = mysqli_fetch_assoc($productResult)): ?>
                    <option value="<?php echo $product['product_name']; ?>"><?php echo $product['product_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="category_name" class="select-category">
                <option value="All">All Categories</option>
                <?php while ($category = mysqli_fetch_assoc($categoryResult)): ?>
                    <option value="<?php echo $category['category']; ?>"><?php echo $category['category']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="button">Filter</button>
            <button type="button" class="print-button" onclick="printReport();">Print Report</button>
        </form>
    </div>

    <!-- Displaying Sales Report -->
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div id="printableArea">
            <h1>WINSP</h1>
            <h3>Sales Report</h3>
            <p class="real-time-date">Date: <?php echo date('Y-m-d H:i:s'); ?></p>
            <table>
                <tr>
                    <?php foreach ($fields as $field): ?>
                        <th><?php echo strtoupper($field); ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <?php foreach ($fields as $field): ?>
                            <td><?php echo $row[$field] ?? 'N/A'; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php else: ?>
        <p>No sales found.</p>
    <?php endif; ?>

    <script>
        function printReport() {
            document.getElementById('printableArea').style.display = 'block';
            window.print();
            document.getElementById('printableArea').style.display = 'none';
        }
    </script>

</body>
</html>
