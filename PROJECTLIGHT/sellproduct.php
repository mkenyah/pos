<?php 
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available products
$sql = "SELECT product_id, product_name, category, quantity FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f9;
            flex-direction: column;
        }
        .navigation-buttons {
            display: flex;
            gap: 10px;
            position: absolute;
            top: 20px;
            left: 50%; 
            transform: translateX(-50%); 
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
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
            width: 60%;
            max-width: 500px;
            align-items: center;
            position: relative;
            bottom: 10px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            text-align: center;
            font-weight: bold;
            color: #555;
        }
        select, input, button {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px;
            width: 60%;
            margin: 0 auto;
            background-color: #fafafa;
        }
        .main-buttons {
            background-color: #f28a0a;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .main-buttons:hover {
            background-color: #f08a0a;
            color: black;
        }
        ::placeholder {
            text-align: center;
        }
    </style>
    <script>
        function validateForm(event) {
            event.preventDefault(); // Prevent the form from submitting
            
            // Get the selected product and quantity
            const productSelect = document.getElementById('productId');
            const quantityInput = document.getElementById('quantity');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const availableQuantity = parseInt(selectedOption.text.match(/\(Available: (\d+)\)/)[1]);
            const quantityToSell = parseInt(quantityInput.value);

            // Check if the quantity to sell exceeds available stock
            if (quantityToSell > availableQuantity) {
                alert(`You cannot sell more than you have. Available amount: ${availableQuantity}`);
                return; // Prevent form submission
            }

            // If validation passes, submit the form
            event.target.submit();
        }
    </script>
</head>
<body>

<div class="navigation-buttons">
    <button onclick="location.href='dashboard.php'">Back to Dashboard</button>
    <button onclick="location.href='sales.php'">View Sales</button>
    <button onclick="location.href='your_form_page.php'">View products</button>
</div>

<div class="container">
    <h2>Sell Product</h2>
    <form onsubmit="validateForm(event)" action="salesprocessing.php" method="post">
        <label for="productId">Select Product:</label>
        <select id="productId" name="product_id" required>
            <option value="" disabled selected>Select a product</option>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <option value="<?php echo $row['product_id']; ?>">
                    <?php echo $row['product_name'] . " - " . $row['category'] . " (Available: " . $row['quantity'] . ")"; ?>
                </option>
            <?php } ?>
        </select>

        <label for="quantity">Quantity to Sell:</label>
        <input type="number" id="quantity" name="quantity" min="1" required>

        <button type="submit" class="main-buttons">Sell Product</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
