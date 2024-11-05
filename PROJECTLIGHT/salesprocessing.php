<?php 
session_start();

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

// Check if the user_in_charge session variable is set
if (!isset($_SESSION['username'])) {
    die("Error: User in charge is not set in the session.");
}

$userIncharge = $_SESSION['username'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $conn->real_escape_string($_POST['product_id']);
    $quantity_sold = (int)$_POST['quantity'];

    // Fetch the product details
    $productQuery = "SELECT product_name, quantity, price_per_bottle, category FROM products WHERE product_id = '$product_id'";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();

        // Check if the quantity to sell is available
        if ($quantity_sold <= $product['quantity']) {
            // Calculate total sale amount
            $sale_amount = $quantity_sold * $product['price_per_bottle'];

            // Record sale in sales table
            $stmt = $conn->prepare("INSERT INTO sales (product_id, product_name, category, quantity_sold, kshSold, userIncharge, sale_date, price_per_bottle) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
            $stmt->bind_param("sssidss", $product_id, $product['product_name'], $product['category'], $quantity_sold, $sale_amount, $userIncharge, $product['price_per_bottle']);
            if ($stmt->execute()) {
                // Update the product quantity in the products table
                $new_quantity = $product['quantity'] - $quantity_sold;

                if ($new_quantity > 0) {
                    // Update quantity if greater than zero
                    $updateProduct = "UPDATE products SET quantity = $new_quantity WHERE product_id = '$product_id'";
                    $conn->query($updateProduct);
                    $_SESSION['success_message'] = "Product sold successfully!";
                    header("Location: sales.php");
                    exit(); // Ensure no further code is executed after the redirect
                } else {
                    // Delete the product if the quantity reaches zero
                    $deleteProduct = "DELETE FROM products WHERE product_id = '$product_id'";
                    $conn->query($deleteProduct);
                    $_SESSION['success_message'] = "Product sold and removed from inventory.";
                    header("Location: sales.php");
                    exit(); // Ensure no further code is executed after the redirect
                }
            } else {
                $_SESSION['error_message'] = "Error recording the sale. Please try again.";
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error: Quantity exceeds available stock.";
        }
    } else {
        $_SESSION['error_message'] = "Error: Product not found.";
    }
} else {
    $_SESSION['error_message'] = "Error: Invalid request.";
}

$conn->close();
header("Location: sales.php"); // This was incorrect in your original code (extra '.ph')
exit();
?>
