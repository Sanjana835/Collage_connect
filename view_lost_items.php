<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login_page.php");
    exit();
}

include '../includes/db_connect.php';

// Check if the user is an admin
$isAdmin = isset($_SESSION['role']) && ($_SESSION['role'] === 'admin');

$sql = "SELECT * FROM lost_items ORDER BY reported_at DESC";
$result = $conn->query($sql);

// Handle delete request
if ($isAdmin && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href='view_lost_items.php';</script>";
    } else {
        echo "<script>alert('Failed to delete item.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Lost Items - College Connect</title>
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
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
        }
        
        h1 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .button-group {
            margin: 30px 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .button-group a {
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-back {
            background-color: #6c757d;
        }
        
        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-add {
            background-color: var(--primary-0,0,0.15);
        }
        
        .btn-add {
            background-color: var(--primary-color);
        }
        
        .btn-add:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
        }
        
        .btn-delete {
            background-color: var(--accent-color);
            border: none;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            padding-top: 20px;
        }
        
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: left;
            transition: all 0.3s ease;
            border: 1px solid #edf2f7;
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-color);
        }
        
        .card h3 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #edf2f7;
        }
        
        .card p {
            margin-bottom: 12px;
            color: var(--text-color);
        }
        
        .card strong {
            color: #333;
            font-weight: 600;
        }
        
        .card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 15px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            max-height: 250px;
        }
        
        .no-items {
            text-align: center;
            color: var(--text-color);
            padding: 50px 0;
            grid-column: 1 / -1;
            font-size: 1.2rem;
        }
        
        .no-items i {
            font-size: 3rem;
            color: #d1d3e2;
            margin-bottom: 15px;
            display: block;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 5px;
        }
        
        .status-lost {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--accent-color);
        }
        
        .status-found {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
        }
        
        .item-meta {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .item-meta i {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 15px;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .button-group a {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lost and Found Items</h1>

        <!-- Back and Add Item Buttons -->
        <div class="button-group">
            <a href="../lost_found.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="add_lost_item.php" class="btn-add">
                <i class="fas fa-plus-circle"></i> Add New Item
            </a>
        </div>

        <div class="grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<h3>" . htmlspecialchars($row['item_name']) . "</h3>";
                    
                    echo "<div class='item-meta'>";
                    echo "<i class='fas fa-align-left'></i>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
                    echo "</div>";
                    
                    echo "<div class='item-meta'>";
                    echo "<i class='fas fa-map-marker-alt'></i>";
                    echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location_found']) . "</p>";
                    echo "</div>";
                    
                    echo "<div class='item-meta'>";
                    echo "<i class='fas fa-user'></i>";
                    echo "<p><strong>Reported By:</strong> " . htmlspecialchars($row['reported_by']) . "</p>";
                    echo "</div>";
                    
                    echo "<div class='item-meta'>";
                    echo "<i class='fas fa-tag'></i>";
                    echo "<p><strong>Status:</strong> <span class='status-badge status-" . htmlspecialchars($row['status']) . "'>" . ucfirst(htmlspecialchars($row['status'])) . "</span></p>";
                    echo "</div>";
                    
                    echo "<div class='item-meta'>";
                    echo "<i class='fas fa-calendar-alt'></i>";
                    echo "<p><strong>Reported At:</strong> " . date('d M Y, h:i A', strtotime($row['reported_at'])) . "</p>";
                    echo "</div>";
                    
                    if (!empty($row['photo_path'])) {
                        // Use the correct path relative to this file
                        echo "<img src='../" . htmlspecialchars($row['photo_path']) . "' alt='Item Photo'>";
                    } else {
                        echo "<p class='text-center'><i class='fas fa-image' style='font-size: 3rem; color: #d1d3e2; margin: 20px 0;'></i><br>No photo available</p>";
                    }
                    
                    // Show delete button only for admin users
                    if ($isAdmin) {
                        echo "<form method='GET' onsubmit='return confirm(\"Are you sure you want to delete this item?\");'>";
                        echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' class='btn-delete'><i class='fas fa-trash-alt'></i> Delete Item</button>";
                        echo "</form>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<div class='no-items'>";
                echo "<i class='fas fa-search'></i>";
                echo "<p>No lost items found.</p>";
                echo "<p>Be the first to report a lost or found item!</p>";
                echo "</div>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>

