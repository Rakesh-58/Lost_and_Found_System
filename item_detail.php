<?php
session_start();
$conn = new mysqli('localhost', 'root', 'root', 'lostandfound');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch item details
if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    $query = "SELECT li.item_name, li.description, li.location, li.image_path,li.status,li.created_at, u.name, u.email,u.phone_number
              FROM lost_found_item li 
              JOIN users u ON li.user_id = u.user_id 
              WHERE li.item_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        echo "Item not found.";
        exit;
    }
} else {
    echo "Invalid item ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['item_name']); ?></title>
    <link rel="stylesheet" href="public/item_detailed.css">
</head>
<body>
    <header class="header" id="header">
        <nav class="nav_container">
        <h2><b>Lost and Found</b></h2>
        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">                     
                    <div class="dropdown">
                        <button class="dropbtn"> <img src="public/image.png" alt="Hello"></button>
                        <div class="dropdown-content">
                        <a href="">Hello, <?php echo $_SESSION['user_id']; ?></a>
                        <a href="logout.php">Logout</a>
                        </div>
                    </div>
                    
                </li>
            </ul>
        </div>
        </nav>
    </header>
    <div class="container">
        <h1><?php echo htmlspecialchars($item['item_name']); ?></h1>
        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?></p>
        <p><strong>Date Posted:</strong> <?php echo nl2br(htmlspecialchars($item['created_at'])); ?></p>
        <p><strong>Posted by:</strong> <?php echo htmlspecialchars($item['name']); ?></p>
        <p><strong>Contact Phone Number:</strong> <?php echo htmlspecialchars($item['phone_number']); ?></p>
        <p><strong>Mail:</strong> <?php echo htmlspecialchars($item['email']); ?></p>
    </div>
    

    <a href="index.php">Back to Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>
