<?php
include('includes/db_connect.php'); // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Use prepared statements to prevent SQL injection
    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('New admin added successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Add Admin</h1>
        <form method="POST" action="add_admin.php">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Admin Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Admin</button>
        </form>
    </div>
</body>
</html>