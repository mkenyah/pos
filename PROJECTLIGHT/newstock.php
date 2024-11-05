<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        /* Navigation buttons positioned at the top right */
        .navigation-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .navigation-buttons button {
            background-color: #f28a0a;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 15px;
            transition: background-color 0.3s;
        }

        .navigation-buttons button:hover {
            color: black;
            font-weight: bold;
            background-color: #f28a0a;
        }

        form {
            background: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
            padding: 20px;
            max-width: 500px;
            margin: auto;
            position: relative;
            top: 50px; /* offset form from top due to navigation buttons */
        }

        h4 {
            text-align: center;
            color: #333;
        }

        h5 {
            margin: 10px 0 5px;
            color: #555;
            text-align: center;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 70%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
            position: relative;
            left: 50px;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #66afe9;
            outline: none;
        }

        button[type="submit"] {
            background-color: #f28a0a;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: orangered;
        }
    </style>
</head>
<body>

<div class="navigation-buttons">
    <button type="button" onclick="location.href='admin.php'">Back to Dashboard</button>
    <button type="button" onclick="location.href='ayour_form_page.php'">Go to Products</button>
</div>

<form action="newstock_process.php" method="post" id="productForm" onsubmit="return validateForm()">
    <h4>Add New Product</h4>
    
    <h5>Product Name</h5>
    <input type="text" id="productName" name="pname" required>

    <h5>Product Category</h5>
    <select id="productCategory" name="category" required onchange="setProductId()">
        <option value="" selected disabled>Select Category</option>
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

    <h5>Product Quantity</h5>
    <input type="number" name="quantity" id="productQuantity" required oninput="calculateProfit()">
    
    <h5>Stock Price</h5>
    <input type="number" name="pprice" id="productPrice" required>

    <h5>Price Per Bottle</h5>
    <input type="number" id="pricePerBottle" name="ppbottle" required oninput="calculateProfit()">

    <h5>Employee in Charge (Employee ID)</h5>
    <select id="employeeInCharge" name="user_in_charge" required>
        <option value="" selected disabled>Select Employee</option>
        <?php
        // Fetch employees from the database
        $servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//  // Make sure to include your database connection file

        $sql = "SELECT USER_ID, FULL_NAME FROM users"; // Adjust the query based on your database structure
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row["USER_ID"]) . '">' . htmlspecialchars($row["FULL_NAME"]) . '</option>';
            }
        } else {
            echo '<option value="">No employees found</option>';
        }
        $conn->close();
        ?>
    </select>

    <h5>Product ID</h5>
    <input type="text" id="productId" name="productId" readonly required>

    <h5>Expected Profit</h5>
    <input type="number" id="expectedProfit" name="expectedProfit" readonly>

    <button type="submit" name="submit">Add Product</button>
</form>

<script>
    function generateProductId(category) {
        var categoryInitial = category.charAt(0).toUpperCase(); 
        var randomNumber = Math.floor(1000 + Math.random() * 9000); 
        return categoryInitial + randomNumber; 
    }

    function setProductId() {
        var category = document.getElementById("productCategory").value;
        if (category) {
            var productId = generateProductId(category);
            document.getElementById("productId").value = productId;
        }
    }

    function calculateProfit() {
        var quantity = parseFloat(document.getElementById("productQuantity").value);
        var pricePerBottle = parseFloat(document.getElementById("pricePerBottle").value);
        if (!isNaN(quantity) && !isNaN(pricePerBottle)) {
            var expectedProfit = quantity * pricePerBottle;
            document.getElementById("expectedProfit").value = expectedProfit.toFixed(2);
        }
    }

    function validateForm() {
        var productId = document.getElementById("productId").value;
        if (!productId) {
            alert("Please select a product category to generate the Product ID.");
            return false; 
        }
        return true; 
    }
</script>

</body>
</html>
