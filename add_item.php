<?php
include('includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $item = $_POST['item'];
    $contact = $_POST['contact'];

    $sql = "INSERT INTO lost_found (name, item, contact) VALUES ('$name', '$item', '$contact')";
    if ($conn->query($sql) === TRUE) {
        echo "Item added to Lost & Found.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<form method="POST" action="add_item.php">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="text" name="item" placeholder="Lost Item" required>
    <input type="text" name="contact" placeholder="Your Contact" required>
    <button type="submit">Add Item</button>
</form>
