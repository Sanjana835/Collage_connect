<?php
session_start();

// Include the database connection file
include('includes/db_connect.php');

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Store the current page URL to redirect back after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login_page.php");
    exit;
}

// Get user information
$role = $_SESSION['role'] ?? 'regular_user'; // Default to regular_user if not set
$user_id = $_SESSION['user_id'] ?? 0; // Get the logged-in user's ID

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
    <style>
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }
        
        .official-header {
            background-color: #e3f2fd;
            color: #0d47a1;
        }
        
        .unofficial-header {
            background-color: #f5f5f5;
            color: #424242;
        }
        
        .notice-item {
            border-bottom: 1px solid #eee;
            padding: 15px;
            transition: background-color 0.2s ease;
        }
        
        .notice-item:last-child {
            border-bottom: none;
        }
        
        .notice-item:hover {
            background-color: #f9f9f9;
        }
        
        .notice-title {
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .notice-content {
            margin-bottom: 10px;
            white-space: pre-line;
        }
        
        .notice-meta {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .btn-add {
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .form-container {
            max-width: 600px;
            margin: 0 auto 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        
        .delete-btn {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-size: 0.9rem;
        }
        
        .delete-btn:hover {
            color: #bd2130;
        }
        
        .hidden {
            display: none;
        }
        
        .alert {
            border-radius: 8px;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            
            .col-md-6 {
                width: 100%;
            }
            
            .card {
                margin-bottom: 20px;
            }
            
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Include the header -->
    <?php include('includes/header.php'); ?>

    <div class="container py-4">
        <h1 class="text-center mb-4">College Notices</h1>
        
        <!-- Display error/success messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Add Notice Button (Visible to Admins and CRs only) -->
        <?php if ($role === 'admin' || $role === 'class_representative'): ?>
            <div class="text-center mb-4">
                <button id="add-notice-btn" class="btn btn-primary btn-add">
                    <i class="fas fa-plus-circle mr-2"></i>Add Notice
                </button>
            </div>
        <?php endif; ?>

        <!-- Add Notice Form Container (Hidden by Default) -->
        <div id="add-notice-container" class="form-container hidden">
            <h3 class="mb-3">
                <?php echo ($role === 'admin') ? 'Add Official Notice' : 'Add Unofficial Notice'; ?>
            </h3>
            <form id="add-notice-form" method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Notice Title" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-control" placeholder="Notice Content" rows="5" required></textarea>
                </div>
                <div class="form-group text-right">
                    <button type="button" id="cancel-btn" class="btn btn-secondary mr-2">Cancel</button>
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
                <div class="card">
                    <div class="card-header official-header">
                        <i class="fas fa-bullhorn mr-2"></i>Official Notices
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($official_notices) > 0): ?>
                            <?php foreach ($official_notices as $notice): ?>
                                <div class="notice-item">
                                    <h5 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h5>
                                    <div class="notice-content"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="notice-meta">
                                            <i class="fas fa-user mr-1"></i> <?php echo htmlspecialchars($notice['username']); ?> 
                                            <i class="fas fa-clock ml-2 mr-1"></i> <?php echo date('M d, Y g:i A', strtotime($notice['created_at'])); ?>
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
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-info-circle mr-2"></i>No official notices found.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Unofficial Notices -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header unofficial-header">
                        <i class="fas fa-comment-alt mr-2"></i>Unofficial Notices
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($unofficial_notices) > 0): ?>
                            <?php foreach ($unofficial_notices as $notice): ?>
                                <div class="notice-item">
                                    <h5 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h5>
                                    <div class="notice-content"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="notice-meta">
                                            <i class="fas fa-user mr-1"></i> <?php echo htmlspecialchars($notice['username']); ?> 
                                            <i class="fas fa-clock ml-2 mr-1"></i> <?php echo date('M d, Y g:i A', strtotime($notice['created_at'])); ?>
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
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-info-circle mr-2"></i>No unofficial notices found.
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
        });
    </script>
</body>
</html>

