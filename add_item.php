    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); 
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $item_name = $_POST['item_name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $location = $_POST['location'];

        // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Define the public folder path for uploads
        $upload_dir = __DIR__ . '/public/uploads/';
        
        // Create the folder if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Get file info
        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_error = $_FILES['image']['error'];
        $file_size = $_FILES['image']['size'];

        // Debugging: Check for any file upload errors
        if ($file_error !== UPLOAD_ERR_OK) {
            echo "File upload error: " . $file_error;
            exit;
        }

        // Ensure the file is a valid image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed_types)) {
            echo "Only JPG, PNG, and GIF files are allowed. File type: " . $file_type;
            exit;
        }

        // Debugging: Check the file size
        echo "File size: " . $file_size . " bytes<br>";

        // Create a unique file name to avoid overwriting
        $new_file_name = uniqid() . '_' . $file_name;

        // Move the file to the uploads directory
        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            // Save the path of the uploaded image
            $image_path = 'public/uploads/' . $new_file_name;
            echo "File uploaded successfully!<br>";
            echo $_SESSION['user_id'];
        } else {
            echo "Failed to move uploaded file.";
            exit;
        }
    } else {
        echo "Error with file upload. Error code: " . $_FILES['image']['error'];
        exit;
    }

        $conn = new mysqli('localhost', 'root', 'root', 'lostandfound');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO lost_found_item (user_id, item_name, category, description, status, location, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssss', $_SESSION['user_id'], $item_name, $category, $description, $status, $location, $image_path);

        if ($stmt->execute()) {
            // Redirect to dashboard after successful insert
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>



    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add New Item</title>
        <link rel="stylesheet" href="public/add_item.css">
    </head>
    <body class="bg ">

    <div class="form-container">
        <h2>Add New Lost or Found Item</h2>
        
        <form method="POST" enctype="multipart/form-data">
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" id="item_name" required>

            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
                <option value="Books">Books</option>
                <option value="Accessories">Accessories</option>
                <option value="Keys">Keys</option>
                <option value="Other">Other</option>
            </select>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
            </select>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>

            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <input type="submit" value="Add Item">
        </form>
    </div>

    </body>
    </html>

