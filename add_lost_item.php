<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login_page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/db_connect.php';

    // Handle file upload
    $photo_path = NULL; // Default to NULL if no photo uploaded
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Create uploads directory if it doesn't exist
        $target_dir = "../uploads/lost_items/";
        
        // Make sure the directory exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Create a unique filename to prevent overwriting
        $file_extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $unique_filename;
        
        // Check if the file is an image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check === false) {
            echo "<script>alert('File is not an image.'); window.location.href='add_lost_item.php';</script>";
            exit();
        }

        // Check file size (e.g., 5MB limit)
        if ($_FILES['photo']['size'] > 5000000) {
            echo "<script>alert('File is too large. Maximum size is 5MB.'); window.location.href='add_lost_item.php';</script>";
            exit();
        }

        // Allow only certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.'); window.location.href='add_lost_item.php';</script>";
            exit();
        }

        // Upload the file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // Store the path relative to the root directory
            $photo_path = "uploads/lost_items/" . $unique_filename;
            echo "<script>console.log('File uploaded successfully: " . $photo_path . "');</script>";
        } else {
            // Debugging: Check why the file upload failed
            echo "<script>alert('Error uploading file. Error code: " . $_FILES['photo']['error'] . "');</script>";
            exit();
        }
    }

    // Insert data into the database
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $location_found = $_POST['location_found'];
    $reported_by = $_SESSION['username']; // Assuming username is stored in session
    $status = $_POST['status'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO lost_items (item_name, description, location_found, reported_by, status, photo_path) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $item_name, $description, $location_found, $reported_by, $status, $photo_path);

    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!'); window.location.href='view_lost_items.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lost Item - College Connect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #2e59d9;
            --secondary-color: #f8f9fc;
            --accent-color: #e74a3b;
            --text-color: #5a5c69;
            --light-gray: #f8f9fc;
            --dark-gray: #5a5c69;
            --success-color: #1cc88a;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            width: 100%;
            max-width: 600px;
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-size: 2rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        input[type="text"], textarea, select {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        
        input[type="text"]:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
            background-color: #fff;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        input[type="file"] {
            margin-bottom: 20px;
            padding: 10px 0;
        }
        
        .file-input-wrapper {
            position: relative;
            margin-bottom: 20px;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #f1f5f9;
            color: #333;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .file-input-button:hover {
            background-color: #e2e8f0;
            border-color: #94a3b8;
        }
        
        .file-input-button i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        button {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
        }
        
        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .radio-option input[type="radio"] {
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Lost Item</h1>
        <form method="POST" action="add_lost_item.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" placeholder="Enter the name of the item" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Provide a detailed description of the item" required></textarea>
            </div>

            <div class="form-group">
                <label for="location_found">Location Found:</label>
                <input type="text" name="location_found" placeholder="Where was the item found/lost?" required>
            </div>

            <div class="form-group">
                <label>Status:</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="status" value="lost" checked> Lost
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="status" value="found"> Found
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <div class="file-input-wrapper">
                    <div class="file-input-button">
                        <i class="fas fa-cloud-upload-alt"></i> Choose a photo
                    </div>
                    <input type="file" name="photo" accept="image/*">
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-plus-circle"></i> Add Item
            </button>
        </form>
        <a href="view_lost_items.php">
            <i class="fas fa-arrow-left"></i> Back to Lost Items
        </a>
    </div>
</body>
</html>

