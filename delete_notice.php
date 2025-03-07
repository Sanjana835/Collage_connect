<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Include the database connection file
include('includes/db_connect.php');

// Get the notice ID from the POST request
$notice_id = filter_input(INPUT_POST, 'notice_id', FILTER_VALIDATE_INT);

if ($notice_id) {
    // Delete the notice
    $sql = "DELETE FROM notices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notice_id);

    if ($stmt->execute()) {
        echo "<script>alert('Notice deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='admin_dashboard.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid notice ID.'); window.location.href='admin_dashboard.php';</script>";
}
?>