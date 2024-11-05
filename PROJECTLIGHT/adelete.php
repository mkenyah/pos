<?php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$dbusername = "root";
$dbpassword = "";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if delete_id is provided in the URL
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    
    // Perform delete operation
    $deleteQuery = "DELETE FROM users WHERE USER_ID = '$delete_id'";
    if ($conn->query($deleteQuery) === TRUE) {
        // Redirect to auser_management.php on successful delete
        echo "<script>alert('User deleted successfully!'); window.location.href='auser_management.php';</script>";
    } else {
        // Redirect to auser_management.php on error
        echo "<script>alert('Error deleting user: " . $conn->error . "'); window.location.href='auser_management.php';</script>";
    }
}

$conn->close();
?>
