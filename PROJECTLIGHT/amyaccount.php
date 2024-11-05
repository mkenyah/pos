<?php 
session_start();

// Database connection
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

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user details from the database
    $sql = "SELECT FULL_NAME, USER_NAME, EMAIL, CONTACT, ROLE FROM users WHERE USER_NAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<script>alert('Profile update successful');</script>";
    } else {
        echo "<script>alert('User not found');</script>";
        exit();
    }
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .welcome-message {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .account-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 300px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[readonly] {
            background-color: #e9e9e9;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            color: black;
            background-color: #f28a0a;
        }
        .nav-button {
            margin-bottom: 20px; /* Add some space below the button */
        }
    </style>
    </style>
</head>
<body>

<!-- Navigation Button to Dashboard -->
<div class="nav-button">
    <button onclick="location.href='admin.php'">Go to Dashboard</button>
</div>

<h2 class="welcome-message">Welcome, <?php echo htmlspecialchars($user['FULL_NAME']); ?>!</h2>

<div class="account-container">
    <form method="POST" action="update_account.php">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['FULL_NAME']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL']); ?>" required>

        <label>Contact:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($user['CONTACT']); ?>" required>

        <label>Username (cannot be changed):</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['USER_NAME']); ?>" readonly>

        <label>Role:</label>
        <input type="text" name="role" value="<?php echo htmlspecialchars($user['ROLE']); ?>" readonly>

        <button type="submit">Update Account</button>
    </form>
</div>

</body>
</html>
