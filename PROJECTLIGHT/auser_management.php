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

// Function to generate user ID based on role
function generateUserID($role) {
    $prefix = $role === 'Admin' ? 'A' : 'E';
    $uniqueNumber = random_int(100000, 999999); // Generate a random 6-digit number
    return $prefix . $uniqueNumber; // Append the letter at the end
}

// Initialize variables for form inputs
$full_name = '';
$email = '';
$role = '';
$username = '';
$password = '';

// Handle Add User form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Check if the user already exists
    $checkUserQuery = "SELECT * FROM users WHERE EMAIL = '$email' OR USER_NAME = '$username'";
    $existingUser = $conn->query($checkUserQuery);

    if ($existingUser->num_rows > 0) {
        echo "<script>alert('User already exists. Please use a different email or username.');</script>";
    } else {
        // Generate user ID based on role
        $user_id = generateUserID($role);

        // Insert the new user with generated ID
        $sql = "INSERT INTO users (USER_ID, FULL_NAME, EMAIL, ROLE, USER_NAME, PASSWORD) VALUES ('$user_id', '$full_name', '$email', '$role', '$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('User added successfully!');
                    document.getElementById('userForm').reset(); // Clear the form fields
                  </script>";
        } else {
            echo "<script>alert('Error adding user: " . $conn->error . "');</script>";
        }
    }
}

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE USER_ID = '$delete_id'");
}

// Handle Edit User submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET FULL_NAME='$full_name', EMAIL='$email', ROLE='$role' WHERE USER_ID='$user_id'";
    $conn->query($sql);
}
?>

<style>
    /* General Styling */
    h3, h4 { 
        color: black; 
        text-align: center; 
        position: relative;
        top: 20px;
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 20px; 
    }

    th, td { 
        padding: 10px; 
        text-align: left; 
        border: 1px solid #f28a0a; 
    }

    th { 
        background-color: #f28a0a; 
        color: white; 
    }

    a { 
        text-decoration: none; 
    }

    /* Form Styling */
    form { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        margin-top: 90px; 
    }

    .form-group {
        display: flex; 
    justify-content: space-between; 
    width: 100%; /* Full width */
    max-width: 600px;
    }

    .form-row {
    flex: 1; /* Make each input take equal space */
    margin: 0 10px; /* Add some space between input fields */
}

    label { 
        font-weight: bold; 
        min-width: 120px; /* Set a minimum width for labels */
        text-align: right; /* Align labels to the right */
    }

    input[type="text"], 
    input[type="email"], 
    input[type="password"] {
        padding: 8px;
        border: 1px solid #f28a0a;
        border-radius: 5px;
        outline: none;
        display: flex;
        transition: border-color 0.3s;
        width: 200px; /* Set a fixed width for input fields */
    }

    input[type="text"]:focus, 
    input[type="email"]:focus, 
    input[type="password"]:focus {
        border-color: #e57000;
    }

    /* Button Styling */
    button {
        background-color: #f28a0a;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
    }

    button:hover {
        background-color: #e57000;
    }

    ::placeholder{
        text-align: center;
    }
    .bbtn{
        background-color: #f28a0a;
        color: white;
        padding: 40px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 650px;
    }

    button:hover {
        background-color: #e57000;
    }
    
</style>

<a class="bbtn" href="./admin.php">Back to Dashboard</a>
   

<h3>User Management</h3>

<!-- Add User Form -->
<form id="userForm" method="post">
    <h4>Add New User</h4>
    
    <div class="form-row">
        
        <input type="text" placeholder="Full Name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
    </div>
    
    <div class="form-row">
        
        <input type="email" placeholder="Email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    
    <div class="form-row">
        
        <input type="text" placeholder="Role" name="role" value="<?php echo htmlspecialchars($role); ?>" required>
    </div>
    
    <div class="form-row">
        
        <input type="text" placeholder="Username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
    </div>
    
    <div class="form-row">
        
        <input type="password" placeholder="Password" name="password" required>
    </div>
    
    <button type="submit" name="add_user">Add User</button>
</form>

<!-- User Table -->
<table>
    <tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
    <?php
    $result = $conn->query("SELECT * FROM users");
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['USER_ID']}</td>
            <td>{$row['FULL_NAME']}</td>
            <td>{$row['EMAIL']}</td>
            <td>{$row['ROLE']}</td>
           <td>
    <a href='aedituser.php?id={$row['USER_ID']}' style='color: blue;'>Edit</a> |
   <a href='adelete.php?delete_id={$row['USER_ID']}' style='color: red;''>Delete</a>

</td>

        </tr>";
    }
    ?>
</table>

<?php $conn->close(); ?>
