<?php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// INSERT NEW PRODUCT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = $_POST['pname'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $pprice = $_POST['pprice'];
    $ppbottle = $_POST['ppbottle'];
    $employeeInCharge = $_POST['user_in_charge'];
    $productId = $_POST['productId'];
    $expectedProfit = $_POST['expectedProfit'];

    
    $sql = "INSERT INTO products (product_name, category, quantity, stock_price, price_per_bottle, user_in_charge, product_id, expected_profit)
            VALUES ('$pname', '$category', '$quantity', '$pprice', '$ppbottle', '$employeeInCharge', '$productId', '$expectedProfit')";

    if (mysqli_query($conn, $sql)) {
        
        header("Location: your_form_page.php");
    } else {
        
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }

    mysqli_close($conn);
}

?>
