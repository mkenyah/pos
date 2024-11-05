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

// Initialize variables for prefilled form
$user_id = '';
$full_name = '';
$email = '';
$role = '';

// Check if ID is provided in URL and fetch user data for editing
if (isset($_GET['id'])) {
    $user_id = $conn->real_escape_string($_GET['id']);
    
    // Fetch user data
    $result = $conn->query("SELECT * FROM users WHERE USER_ID = '$user_id'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $full_name = $user['FULL_NAME'];
        $email = $user['EMAIL'];
        $role = $user['ROLE'];
    } else {
        echo "<script>alert('User not found!'); window.location.href='admin.php';</script>";
        exit;
    }
}

// Handle form submission for editing user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET FULL_NAME=?, EMAIL=?, ROLE=? WHERE USER_ID=?");
    $stmt->bind_param("ssss", $full_name, $email, $role, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error updating user: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
            margin: 0;
        }

        h3 {
            color: #333;
            text-align: center;
            position: absolute;
            top:50px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #f28a0a;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #e57000;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e57000;
        }
    </style>
</head>
<body>

<h3>Edit User</h3>
<form method="post">
    <!-- Hidden field to store user ID -->
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
    
    <div>
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
    </div>
    <div>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div>
        <label>Role:</label>
        <input type="text" name="role" value="<?php echo htmlspecialchars($role); ?>" required>
    </div>
    <button type="submit" name="edit_user">Save Changes</button>
</form>

</body>
</html>
