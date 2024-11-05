<?php
// products_report.php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$dbusername = "root";
$dbpassword = "";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logic for fetching products and categories for dropdowns
$productQuery = "SELECT product_name FROM products";
$categoryQuery = "SELECT DISTINCT category FROM products";
$productResult = mysqli_query($conn, $productQuery);
$categoryResult = mysqli_query($conn, $categoryQuery);

// Logic for fetching and displaying products report based on filters
$productName = $_POST['product_name'] ?? 'All';
$categoryName = $_POST['category_name'] ?? 'All';

$query = "SELECT * FROM products";
if ($productName !== "All" && $categoryName !== "All") {
    $query .= " WHERE product_name = '$productName' AND category = '$categoryName'";
} elseif ($productName !== "All") {
    $query .= " WHERE product_name = '$productName'";
} elseif ($categoryName !== "All") {
    $query .= " WHERE category = '$categoryName'";
}

$result = mysqli_query($conn, $query);

// Fetch all field names from the products table
$fields = [];
if ($result && mysqli_num_rows($result) > 0) {
    $fields = array_keys(mysqli_fetch_assoc($result));
    mysqli_data_seek($result, 0); // Reset result pointer
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        h3 {
            text-align: center;
            margin-top: 0;
        }
        p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background-color: #f28a0a;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .select-category, .select-product {
            padding: 8px;
            margin-right: 10px;
            font-size: 14px;
            border: 1px solid #f28a0a;
        }
        .button {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .button:hover {
            color: #000;
            background-color: #f28a0a;
        }
        .print-button {
            background-color: #f28a0a;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
        }
        .print-button:hover {
            color: #000;
            background-color: #f28a0a;
        }
        .nav-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-button {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .nav-button:hover {
            background-color: #e67e22;
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
                text-align: center;
            }
            h1, h3, p {
                margin: 0;
            }
            table {
                margin: 0 auto;
                width: auto;
                border: none;
            }
            p{
                text-align: center;
            }
            th, td {
                border: 1px solid #f28a0a;
                padding: 5px;
                font-size: 12px;
            }
            th{
                color: white;
              background-color:  #f28a0a;  
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

    <!-- Filter Form -->
    <div class="filter-container">
        <form method="post" action="products_report.php">
            <select name="product_name" class="select-product">
                <option value="All">All Products</option>
                <?php while ($productRow = mysqli_fetch_assoc($productResult)): ?>
                    <option value="<?php echo $productRow['product_name']; ?>" <?php if ($productRow['product_name'] === $productName) echo 'selected'; ?>>
                        <?php echo $productRow['product_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <select name="category_name" class="select-category">
                <option value="All">All Categories</option>
                <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)): ?>
                    <option value="<?php echo $categoryRow['category']; ?>" <?php if ($categoryRow['category'] === $categoryName) echo 'selected'; ?>>
                        <?php echo $categoryRow['category']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="button">Filter</button>
            <button type="button" class="print-button" onclick="printReport();">Print Report</button>
        </form>
    </div>

    <!-- Displaying Products Report -->
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div id="printableArea" style="display:none;">
            <h1>WINSP</h1>
            <h3>Products Report</h3>
            <p><?php echo date("F j, Y"); ?></p> <!-- Current date -->
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
        <script>
            document.getElementById('printableArea').style.display = 'block'; // Show the printable area after filtering
        </script>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>

    <script>
        function printReport() {
            // Show the printable area
            document.getElementById('printableArea').style.display = 'block';
            window.print();
            // Hide the printable area again
            document.getElementById('printableArea').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
