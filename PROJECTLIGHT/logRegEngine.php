<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PROJECTLIGHT";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['fullname']) && !empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['role']) && !empty($_POST['user_id'])) {

        //RETRIEVING FROM INPUTS AND ESCAPING THE DANAGEROUS STRING CHARACTERS
        $user_id = $conn->real_escape_string($_POST['user_id']);
        $fullname = $conn->real_escape_string($_POST['fullname']);
        $email = $conn->real_escape_string($_POST['email']);
        $contact = $conn->real_escape_string($_POST['contact']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
        $role = $conn->real_escape_string($_POST['role']);


        echo "User ID: " . $user_id . "<br>";


        $sql = "INSERT INTO users (USER_ID, FULL_NAME, EMAIL, CONTACT, USER_NAME, PASSWORD, ROLE, CREATED_AT) VALUES ('$user_id', '$fullname', '$email', '$contact', '$username', '$password', '$role', NOW())";

        //CHECKING IF THE REGISTER IS SUCCESSFUL 
        //AND ALL FIELDS ARE CORRECTLY INSERTED 
        //AND REDIRECTION TO LOGING PAGE
        if ($conn->query($sql) === TRUE) {
            
            header("Location: ./login.php");
            echo "<script>alert('Registration successful!');</script>";
            
            exit();
           
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Required fields are missing.";
    }
}

$conn->close();
