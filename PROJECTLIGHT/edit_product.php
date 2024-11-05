<?php
session_start(); // Start the session at the beginning

$servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// FETCH FOR EDITING PRODUCTS
$product = null; 
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("s", $productId);
    
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// UPDATING THE PRODUCT LIST
$errorOccurred = false; // Initialize error flag
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = $conn->real_escape_string($_POST['pname']);
    $category = $conn->real_escape_string($_POST['category']);
    $quantity = (int)$_POST['quantity']; 
    $pprice = (float)$_POST['pprice']; 
    $ppbottle = (float)$_POST['ppbottle']; 
    $user_in_charge = $conn->real_escape_string($_POST['user_in_charge']);
    $productId = $_POST['productId'];

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, category = ?, quantity = ?, stock_price = ?, price_per_bottle = ?, user_in_charge = ? WHERE product_id = ?");
    $stmt->bind_param("ssddsss", $pname, $category, $quantity, $pprice, $ppbottle, $user_in_charge, $productId);
    
    if ($stmt->execute()) {
        // Setting a success flag in the session
        $_SESSION['product_updated'] = true;
       echo" alert('Product edited successfully!'); ";
               header("Location: your_form_page.php"); // Redirect to the main stock page
        exit;
    } else {
        $errorOccurred = true; // Set error flag
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
            display: block;
            text-align: center;
        }
        input, select {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: calc(100% - 20px);
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #f28a0a;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            color: black;
            background-color: #f28a0a;
            font-size: bold;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        ::placeholder {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Edit Product</h2>

<?php if ($product): ?>
    <form action="" method="post">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="pname" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

        <label for="productCategory">Product Category:</label>
        <select id="productCategory" name="category" required>
            <option value="" disabled>Select a category</option>
            <option value="wine" <?php echo ($product['category'] == 'wine') ? 'selected' : ''; ?>>Wine</option>
            <option value="spirit" <?php echo ($product['category'] == 'spirit') ? 'selected' : ''; ?>>Spirit</option>
            <option value="soda" <?php echo ($product['category'] == 'soda') ? 'selected' : ''; ?>>Soda</option>
            <option value="beer" <?php echo ($product['category'] == 'beer') ? 'selected' : ''; ?>>Beer</option>
            <option value="whiskey" <?php echo ($product['category'] == 'whiskey') ? 'selected' : ''; ?>>Whiskey</option>
            <option value="vodka" <?php echo ($product['category'] == 'vodka') ? 'selected' : ''; ?>>Vodka</option>
            <option value="gin" <?php echo ($product['category'] == 'gin') ? 'selected' : ''; ?>>Gin</option>
            <option value="champagne" <?php echo ($product['category'] == 'champagne') ? 'selected' : ''; ?>>Champagne</option>
            <option value="tequila" <?php echo ($product['category'] == 'tequila') ? 'selected' : ''; ?>>Tequila</option>
            <option value="minuteMaid" <?php echo ($product['category'] == 'minuteMaid') ? 'selected' : ''; ?>>Minute Maid</option>
            <option value="energyDrink" <?php echo ($product['category'] == 'energyDrink') ? 'selected' : ''; ?>>Energy Drink</option>
        </select>

        <label for="productQuantity">Product Quantity:</label>
        <input type="number" id="productQuantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>

        <label for="productPrice">Stock Price:</label>
        <input type="number" id="productPrice" name="pprice" value="<?php echo htmlspecialchars($product['stock_price']); ?>" required>

        <label for="pricePerBottle">Price Per Bottle:</label>
        <input type="number" id="pricePerBottle" name="ppbottle" value="<?php echo htmlspecialchars($product['price_per_bottle']); ?>" required>

        <label for="employeeInCharge">Employee in Charge (Employee ID):</label>
        <input type="text" id="employeeInCharge" name="user_in_charge" value="<?php echo htmlspecialchars($product['user_in_charge']); ?>" required>

        <input type="hidden" id="productId" name="productId" value="<?php echo htmlspecialchars($product['product_id']); ?>" required>

        <button type="submit">Update Product</button>
    </form>
<?php else: ?>
    <p class="error">Product not found.</p>
<?php endif; ?>

<script>
    // Check if the session variable is set and display alert
    <?php if (isset($_SESSION['product_updated']) && $_SESSION['product_updated'] === true): ?>
        // alert('Product edited successfully!'); // Show success alert
        <?php unset($_SESSION['product_updated']); // Unset the session variable ?>
    <?php elseif ($errorOccurred): ?>
        alert('Error updating product. Please try again.'); // Show error alert if update fails
    <?php endif; ?>
</script>

</body>
</html>
