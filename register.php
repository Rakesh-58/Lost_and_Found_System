<?php
// Start session
session_start();

// Database credentials
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'lostandfound';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $conn->real_escape_string($_POST['role']);
    $password = $conn->real_escape_string($_POST['password']);

    // Insert the data into the database
    $sql = "INSERT INTO users (user_id, name, email, phone_number, user_type, password) 
            VALUES ('$user_id', '$name', '$email', '$phone', '$role', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="public/login.css">
</head>
<body class="bg">
<secion class="whole_container">
    <h2>Create Account</h2>
<div class="login-container" style="max-width: 500px">
    <h2>Register</h2>
    
    <form method="POST">
        <input type="text" name="user_id" placeholder="Roll No or Faculty ID" required>
        <input type= "text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="number" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" maxlength="10" required>
        <select id="role" name="role">
            <option value="student">Student</option>
            <option value="staff">Staff</option>
            <option value="visitor">Visitor</option>
          </select>
        <input type="password" name="password" placeholder="Password" required>
        <a href="login.php">Already registered? Login here</a>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <input type="submit" value="Register">
    </form>
</div>
</section>
</body>
</html>
