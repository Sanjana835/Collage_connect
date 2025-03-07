<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied!");
}

include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);

    // Secure deletion
    $stmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href='view_lost_items.php';</script>";
    } else {
        echo "<script>alert('Error deleting item.'); window.location.href='view_lost_items.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>