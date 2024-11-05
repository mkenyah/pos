<?php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$username = "root";
$password = "";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE BASED ON PRODUCT ID
if (isset($_GET['id'])) {
    // Sanitize the input and prepare for deletion
    $productId = $_GET['id']; // Get the product ID from the URL parameter
    
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $productId); // Bind the parameter as a string since product_id may not be an integer
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the form page after successful deletion
            header("Location: your_form_page.php"); 
            exit;
        } else {
            echo "Error deleting product: " . $stmt->error;
        }
        
        $stmt->close(); // Close the prepared statement
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "No product ID specified.";
}

$conn->close(); // Close the database connection
?>
