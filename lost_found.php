<?php
// Start the session (only once)
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login_page.php");
    exit();
}

// Include the database connection file
include 'includes/db_connect.php';

// Fetch lost and found items from the database
$sql = "SELECT id, item_name, description, location_found, reported_by, reported_at, status, resolved, photo_path FROM lost_items";
$result = $conn->query($sql);

$lostItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lostItems[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Collage Connect</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card img {
            width: 100%; /* Make the image responsive */
            height: 200px; /* Fixed height */
            object-fit: cover; /* Ensure the image covers the area without distortion */
            border-radius: 5px;
        }
        .status-lost { color: red; }
        .status-found { color: green; }
    </style>
</head>
<body>
    <!-- Include the header file -->
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h2 class="text-center mb-4">Lost & Found</h2>

        <!-- Add New Lost/Found Item Button -->
        <div class="text-center mb-4">
            <a href="lost_and_found/add_lost_item.php" class="btn btn-primary">Add New Lost/Found Item</a>
        </div>

        <!-- Display Lost and Found Items -->
        <div class="row">
            <?php foreach ($lostItems as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card" onclick="window.location.href='view_lost_item.php?id=<?php echo $item['id']; ?>';">
                        <?php if (!empty($item['photo_path']) && file_exists($item['photo_path'])): ?>
                            <img src="<?php echo $item['photo_path']; ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" class="card-img-top">
                        <?php else: ?>
                            <img src="images/default.jpg" alt="No Image Available" class="card-img-top">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['item_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($item['location_found']); ?></p>
                            <p class="card-text"><strong>Reported By:</strong> <?php echo htmlspecialchars($item['reported_by']); ?></p>
                            <p class="card-text"><strong>Status:</strong> 
                                <span class="<?php echo $item['status'] === 'lost' ? 'status-lost' : 'status-found'; ?>">
                                    <?php echo ucfirst($item['status']); ?>
                                </span>
                            </p>
                            <p class="card-text"><strong>Reported At:</strong> <?php echo date('d M Y, h:i A', strtotime($item['reported_at'])); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Include the footer file -->
    <?php include('includes/footer.php'); ?>
</body>
</html>

