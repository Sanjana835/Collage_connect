<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Include the database connection file
include('includes/db_connect.php');

// Get the user ID from the POST request
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

if ($user_id) {
    // Delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='admin_dashboard.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid user ID.'); window.location.href='admin_dashboard.php';</script>";
}
?>