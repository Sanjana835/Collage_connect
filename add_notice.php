<?php
session_start();

// Include the database connection file
include('includes/db_connect.php');

// Check if the database connection was successful
if (!isset($conn)) {
    die("Database connection failed.");
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    // Store the current page URL to redirect back after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login_page.php");
    exit;
}

// Get user information
$role = $_SESSION['role'] ?? 'regular_user'; // Default to regular_user if not set
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Initialize variables for error/success messages
$error_message = '';
$success_message = '';

// Handle form submission for adding notices
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_notice'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Security validation failed. Please try again.";
    } else {
        // Sanitize and validate inputs
        $title = trim(htmlspecialchars($_POST['title']));
        $content = trim(htmlspecialchars($_POST['content']));
        
        // Validate inputs
        if (empty($title) || empty($content)) {
            $error_message = "Title and content are required.";
        } else if (strlen($title) > 255) {
            $error_message = "Title is too long (maximum 255 characters).";
        } else {
            // Determine notice type based on user role
            $type = ($role === 'admin') ? 'official' : 'unofficial';
            
            // Only allow admins and CRs to add notices
            if ($role === 'admin' || $role === 'class_representative') {
                // Insert notice into the database
                $sql = "INSERT INTO notices (title, content, type, created_by) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $title, $content, $type, $user_id);
                
                if ($stmt->execute()) {
                    $success_message = "Notice added successfully!";
                    // Regenerate CSRF token after successful submission
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $error_message = "Error: Unable to add notice. " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                $error_message = "You don't have permission to add notices.";
            }
        }
    }
}

// Handle notice deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_notice'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Security validation failed. Please try again.";
    } else {
        $notice_id = filter_input(INPUT_POST, 'notice_id', FILTER_VALIDATE_INT);
        
        if (!$notice_id) {
            $error_message = "Invalid notice ID.";
        } else {
            // Only admins can delete notices
            if ($role === 'admin') {
                // Delete the notice
                $sql = "DELETE FROM notices WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $notice_id);
                
                if ($stmt->execute()) {
                    $success_message = "Notice deleted successfully!";
                    // Regenerate CSRF token after successful deletion
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $error_message = "Error: Unable to delete notice. " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                $error_message = "You don't have permission to delete notices.";
            }
        }
    }
}

// Get official notices
$official_notices = [];
$sql = "SELECT notices.*, users.username FROM notices 
        JOIN users ON notices.created_by = users.id 
        WHERE type = 'official' 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $official_notices[] = $row;
    }
}

// Get unofficial notices
$unofficial_notices = [];
$sql = "SELECT notices.*, users.username FROM notices 
        JOIN users ON notices.created_by = users.id 
        WHERE type = 'unofficial' 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unofficial_notices[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Notices</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
    <style>
        /* Main Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            padding-top: 30px;
            padding-bottom: 50px;
        }
        
        h1 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        h1:after {
            content: "📢";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.2rem;
            border-bottom: none;
            display: flex;
            align-items: center;
        }
        
        .official-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .unofficial-header {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
        }
        
        /* Notice Item Styles */
        .notice-item {
            padding: 20px;
            position: relative;
            background-color: white;
            text-align: left;
            border-bottom: 3px dashed rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .notice-item:last-child {
            border-bottom: none;
        }
        
        .notice-item:hover {
            background-color: #f8f9fa;
        }
        
        .notice-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
            font-size: 1.1rem;
            text-align: left;
            display: flex;
            align-items: center;
        }
        
        .notice-title:before {
            content: "📌";
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .notice-content {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.7;
            text-align: left;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .unofficial-notice .notice-content {
            border-left-color: #e67e22;
        }
        
        .notice-meta {
            font-size: 0.85rem;
            color: #7f8c8d;
            display: flex;
            align-items: center;
        }
        
        .notice-meta i {
            margin-right: 5px;
        }
        
        .notice-date {
            margin-left: 15px;
            display: flex;
            align-items: center;
        }
        
        /* Button Styles */
        .btn-add {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            border: none;
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        
        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.6);
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            color: white;
        }
        
        .btn-add i {
            margin-right: 8px;
            font-size: 1.1rem;
        }
        
        .delete-btn {
            color: #e74c3c;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .delete-btn:hover {
            background-color: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }
        
        /* Form Styles */
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 0 auto 40px;
            max-width: 800px;
            position: relative;
            border-top: 5px solid #9b59b6;
        }
        
        .form-container h3 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .form-container h3:before {
            content: "✏️";
            margin-right: 10px;
        }
        
        .form-group label {
            font-weight: 500;
            color: #34495e;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .form-control:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 0 0.2rem rgba(155, 89, 182, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(155, 89, 182, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(155, 89, 182, 0.4);
        }
        
        .btn-secondary {
            background-color: #ecf0f1;
            color: #7f8c8d;
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .btn-secondary:hover {
            background-color: #dfe6e9;
            color: #636e72;
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        /* Empty State Styles */
        .empty-state {
            padding: 30px;
            text-align: center;
            color: #7f8c8d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #bdc3c7;
        }
        
        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                padding-top: 20px;
            }
            
            .card-header {
                padding: 12px 15px;
            }
            
            .notice-item {
                padding: 15px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .btn-add {
                width: 100%;
                margin-bottom: 20px;
            }
        }
        
        /* Custom Scrollbar */
        .card-body {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #bdc3c7 #f1f1f1;
        }
        
        .card-body::-webkit-scrollbar {
            width: 8px;
        }
        
        .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .card-body::-webkit-scrollbar-thumb {
            background-color: #bdc3c7;
            border-radius: 10px;
        }
        
        /* Hidden Class */
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Include the header -->
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h1 class="text-center mb-5 animate__animated animate__fadeIn">College Notices Board</h1>
        
        <!-- Display error/success messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $success_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Add Notice Button (Visible to Admins and CRs only) -->
        <?php if ($role === 'admin' || $role === 'class_representative'): ?>
            <div class="text-center mb-4">
                <button id="add-notice-btn" class="btn btn-add animate__animated animate__fadeIn">
                    <i class="fas fa-plus-circle"></i> Add New Notice
                </button>
            </div>
        <?php endif; ?>

        <!-- Add Notice Form Container (Hidden by Default) -->
        <div id="add-notice-container" class="form-container hidden animate__animated animate__fadeIn">
            <h3>
                <?php echo ($role === 'admin') ? '✨ Add Official Notice' : '✨ Add Unofficial Notice'; ?>
            </h3>
            <form id="add-notice-form" method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label for="title"><i class="fas fa-heading mr-2"></i>Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter notice title here..." required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="content"><i class="fas fa-align-left mr-2"></i>Content</label>
                    <textarea name="content" id="content" class="form-control" placeholder="Enter notice content here..." rows="5" required></textarea>
                </div>
                <div class="form-group text-right">
                    <button type="button" id="cancel-btn" class="btn btn-secondary mr-2">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" name="add_notice" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Notice
                    </button>
                </div>
            </form>
        </div>

        <!-- Split Screen for Notices -->
        <div class="row">
            <!-- Official Notices -->
            <div class="col-md-6">
                <div class="card animate__animated animate__fadeInLeft">
                    <div class="card-header official-header">
                        <i class="fas fa-bullhorn mr-2"></i> Official Notices 📋
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($official_notices) > 0): ?>
                            <?php foreach ($official_notices as $notice): ?>
                                <div class="notice-item fade-in">
                                    <h5 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h5>
                                    <div class="notice-content"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="notice-meta">
                                            <i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($notice['username']); ?>
                                            <span class="notice-date">
                                                <i class="far fa-calendar-alt ml-3"></i> <?php echo date('M d, Y', strtotime($notice['created_at'])); ?>
                                                <i class="far fa-clock ml-2"></i> <?php echo date('g:i A', strtotime($notice['created_at'])); ?>
                                            </span>
                                        </div>
                                        <?php if ($role === 'admin'): ?>
                                            <form method="POST" action="" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="notice_id" value="<?php echo $notice['id']; ?>">
                                                <button type="submit" name="delete_notice" class="delete-btn" 
                                                        onclick="return confirm('Are you sure you want to delete this notice?');">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="far fa-clipboard"></i>
                                <p>No official notices found at this time.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Unofficial Notices -->
            <div class="col-md-6">
                <div class="card animate__animated animate__fadeInRight">
                    <div class="card-header unofficial-header">
                        <i class="fas fa-comment-alt mr-2"></i> Unofficial Notices 📝
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($unofficial_notices) > 0): ?>
                            <?php foreach ($unofficial_notices as $notice): ?>
                                <div class="notice-item unofficial-notice fade-in">
                                    <h5 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h5>
                                    <div class="notice-content"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="notice-meta">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($notice['username']); ?>
                                            <span class="notice-date">
                                                <i class="far fa-calendar-alt ml-3"></i> <?php echo date('M d, Y', strtotime($notice['created_at'])); ?>
                                                <i class="far fa-clock ml-2"></i> <?php echo date('g:i A', strtotime($notice['created_at'])); ?>
                                            </span>
                                        </div>
                                        <?php if ($role === 'admin'): ?>
                                            <form method="POST" action="" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="notice_id" value="<?php echo $notice['id']; ?>">
                                                <button type="submit" name="delete_notice" class="delete-btn" 
                                                        onclick="return confirm('Are you sure you want to delete this notice?');">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="far fa-comment-alt"></i>
                                <p>No unofficial notices found at this time.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the footer -->
    <?php include('includes/footer.php'); ?>

    <!-- JavaScript for Bootstrap and custom functionality -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Show/hide the add notice container
        document.addEventListener('DOMContentLoaded', function() {
            const addNoticeBtn = document.getElementById('add-notice-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const addNoticeContainer = document.getElementById('add-notice-container');
            
            if (addNoticeBtn) {
                addNoticeBtn.addEventListener('click', function() {
                    addNoticeContainer.classList.remove('hidden');
                    addNoticeBtn.classList.add('hidden');
                    document.getElementById('title').focus();
                    
                    // Scroll to form
                    addNoticeContainer.scrollIntoView({behavior: 'smooth'});
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    addNoticeContainer.classList.add('hidden');
                    addNoticeBtn.classList.remove('hidden');
                    // Reset form
                    document.getElementById('add-notice-form').reset();
                });
            }
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    $(alert).alert('close');
                });
            }, 5000);
            
            // Add animation to notice items on page load
            const noticeItems = document.querySelectorAll('.notice-item');
            noticeItems.forEach(function(item, index) {
                setTimeout(function() {
                    item.classList.add('fade-in');
                }, index * 100);
            });
        });
    </script>
</body>
</html>