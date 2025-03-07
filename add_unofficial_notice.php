<?php
session_start();
include('includes/db_connect.php');

// Check if the user is a representative
if ($_SESSION['role'] !== 'class_representative') {
    die("Access denied. Only representatives can add unofficial notices.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $created_by = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Insert notice into the database
    $sql = "INSERT INTO notices (title, content, type, created_by) VALUES (?, ?, 'unofficial', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $created_by);

    if ($stmt->execute()) {
        echo "<script>alert('Unofficial notice added successfully!'); window.location.href='add_notice.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Unofficial Notice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?> <!-- Include the header -->

    <div class="container">
        <h1 class="text-center my-4">Add Unofficial Notice</h1>
        <form method="POST" action="add_unofficial_notice.php">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Notice Title" required>
            </div>
            <div class="form-group">
                <textarea name="content" class="form-control" placeholder="Notice Content" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Add Notice</button>
        </form>
    </div>
</body>
</html>