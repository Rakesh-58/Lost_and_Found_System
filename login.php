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

// Process login when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['username'];
    $password = $_POST['password'];
   
    // Check if user exists with the provided role
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE user_id = ?");
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_password);
        $stmt->fetch();
        
        // Verify password
        if (($password == $db_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Invalid username";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="public/login.css">
</head>
<body class="bg">
<secion class="whole_container">
    <h2>Welcome back to Lost and Found!</h2>
<div class="login-container">
    <h2>Login</h2>
    
    <form method="POST">
        <input type="text" name="username" placeholder="User ID (Roll No)" required>
        <input type="password" name="password" placeholder="Password" required>
        <a href="register.php">New user? Register here</a>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <input type="submit" value="Login">
    </form>
</div>
</section>
</body>
</html>
