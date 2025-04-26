<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection (update with your credentials)
$conn = new mysqli('localhost', 'root', 'root', 'lostandfound');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch lost items
$lost_items_query = "SELECT item_id, item_name, image_path, created_at, status
                     FROM lost_found_item WHERE status = 'lost' order by created_at";
$lost_items_result = $conn->query($lost_items_query);

// Fetch found items
$found_items_query = "SELECT item_id, item_name, image_path, created_at, status
                     FROM lost_found_item li  WHERE status = 'found' order by created_at";
$found_items_result = $conn->query($found_items_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="public/index.css">
</head>
<body>
    <header class="header" id="header">
    
        <nav class="nav_container">
        <a href="#" class="nav__logo"><b>Lost and Found</b></a>
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

    

    <div class="tabs">
        <div class="tab active" onclick="showTab('lost')">Lost Items</div>
        <div class="tab" onclick="showTab('found')">Found Items</div>
    </div>

    <div id="lost" class="cards-container">
        <?php
        if ($lost_items_result->num_rows > 0) {
            while ($row = $lost_items_result->fetch_assoc()) {
                echo '<div class="card">';
                echo '<a href="item_detail.php?id=' . $row['item_id'] . '">';
                echo '<img src="' . $row['image_path'] . '" alt="' . htmlspecialchars($row['item_name']) . '">';
                echo '<h4>' . htmlspecialchars($row['item_name']) . '</h4>';
                echo '<p>Date Posted: ' . date('d-m-Y', strtotime($row['created_at'])). '</p>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No lost items found.</p>';
        }
        ?>
    </div>

    <div id="found" class="cards-container" style="display: none;">
        <?php
        if ($found_items_result->num_rows > 0) {
            while ($row = $found_items_result->fetch_assoc()) {
                echo '<div class="card">';
                echo '<a href="item_detail.php?id=' . $row['item_id'] . '">';
                echo '<img src="' . $row['image_path'] . '" alt="' . htmlspecialchars($row['item_name']) . '">';
                echo '<h4>' . htmlspecialchars($row['item_name']) . '</h4>';
                echo '<p>Date Posted: ' . date('Y-m-d', strtotime($row['created_at'])) . '</p>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No found items found.</p>';
        }
        ?>
    </div>
    <!-- Floating Add Button -->
    <a href="add_item.php" class="floating-button" title="Add New Item">+</a>
    
    <script>
        function showTab(tab) {
            const lostTab = document.getElementById('lost');
            const foundTab = document.getElementById('found');
            const lostTabButton = document.querySelector('.tab.active');

            if (tab === 'lost') {
                lostTab.style.display = 'flex';
                foundTab.style.display = 'none';
                lostTabButton.classList.add('active');
                lostTabButton.nextElementSibling.classList.remove('active');
            } else {
                lostTab.style.display = 'none';
                foundTab.style.display = 'flex';
                lostTabButton.classList.remove('active');
                lostTabButton.nextElementSibling.classList.add('active');
            }
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
