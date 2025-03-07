<?php
session_start();

// Include the database connection file
include('includes/db_connect.php');

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    // Store the current page URL to redirect back after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login_page.php");
    exit;
}

// Get user information
$role = $_SESSION['role'] ?? 'regular_user';
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Get some statistics for the dashboard
// Count total notices
$total_notices = 0;
$sql = "SELECT COUNT(*) as count FROM notices";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_notices = $row['count'];
}

// Count official notices
$official_notices = 0;
$sql = "SELECT COUNT(*) as count FROM notices WHERE type = 'official'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $official_notices = $row['count'];
}

// Count unofficial notices
$unofficial_notices = 0;
$sql = "SELECT COUNT(*) as count FROM notices WHERE type = 'unofficial'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $unofficial_notices = $row['count'];
}

// Count total users
$total_users = 0;
$sql = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_users = $row['count'];
}

// Get recent notices (limit to 5)
$recent_notices = [];
$sql = "SELECT notices.*, users.username FROM notices 
        JOIN users ON notices.created_by = users.id 
        ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recent_notices[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
    <style>
        /* Main Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(14, 61, 108);
            color: #333;
            line-height: 1.6;
            position: relative;
        }
        
        body:before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: -1;
        }
        
        .container {
            padding-top: 30px;
            padding-bottom: 50px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            position: relative;
        }
        
        .page-header:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, #3498db, #9b59b6);
            border-radius: 2px;
        }
        
        /* Dashboard Cards */
        .dashboard-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            padding: 20px;
            font-weight: 600;
            font-size: 1.2rem;
            border-bottom: none;
            display: flex;
            align-items: center;
        }
        
        .card-header i {
            margin-right: 10px;
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card:before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .stat-card.purple {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }
        
        .stat-card.orange {
            background: linear-gradient(135deg, #e67e22, #d35400);
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-title {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 0;
        }
        
        /* College Info */
        .college-info {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .college-logo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 5px solid #f8f9fa;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .college-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .college-tagline {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .college-description {
            color: #555;
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .college-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .college-stat {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            flex: 1;
            margin: 0 5px;
        }
        
        .college-stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #3498db;
            margin-bottom: 5px;
        }
        
        .college-stat-title {
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        /* Recent Notices */
        .recent-notice {
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }
        
        .recent-notice:last-child {
            border-bottom: none;
        }
        
        .recent-notice:hover {
            background-color: #f8f9fa;
        }
        
        .notice-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .notice-title i {
            margin-right: 8px;
            color: #3498db;
        }
        
        .notice-meta {
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        
        /* Quick Links */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .quick-link {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #2c3e50;
        }
        
        .quick-link:hover {
            background-color: #3498db;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }
        
        .quick-link i {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, #3498db, #9b59b6);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-message:before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                padding-top: 20px;
            }
            
            .stat-card {
                margin-bottom: 20px;
            }
            
            .college-stats {
                flex-direction: column;
            }
            
            .college-stat {
                margin: 5px 0;
            }
            
            .quick-links {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Include the header -->
    <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="welcome-message animate__animated animate__fadeIn">
            <h2 class="welcome-title">Welcome, <?php echo htmlspecialchars($username); ?>! 👋</h2>
            <p class="welcome-subtitle">Here's what's happening at Evergreen College today.</p>
        </div>

        <h1 class="page-header animate__animated animate__fadeIn">College Dashboard</h1>
        
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card animate__animated animate__fadeInUp">
                    <div class="stat-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_notices; ?></div>
                    <p class="stat-title">Total Notices</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="stat-card purple animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-number"><?php echo $official_notices; ?></div>
                    <p class="stat-title">Official Notices</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="stat-card orange animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="stat-icon">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="stat-number"><?php echo $unofficial_notices; ?></div>
                    <p class="stat-title">Unofficial Notices</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="stat-card green animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <p class="stat-title">College Members</p>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <!-- College Information -->
            <div class="col-lg-6">
                <div class="college-info animate__animated animate__fadeIn" style="animation-delay: 0.4s;">
                    <div class="text-center">
                        <img src="https://via.placeholder.com/120" alt="College Logo" class="college-logo">
                        <h2 class="college-name">Evergreen College</h2>
                        <p class="college-tagline">Nurturing Minds, Building Futures</p>
                    </div>
                    
                    <p class="college-description">
                        Evergreen College is a premier educational institution dedicated to academic excellence and holistic development. 
                        Founded in 1985, we have a rich history of producing leaders across various fields. Our campus spans 50 acres 
                        of lush greenery, providing an ideal environment for learning and growth.
                    </p>
                    
                    <div class="college-stats">
                        <div class="college-stat">
                            <div class="college-stat-number">50+</div>
                            <div class="college-stat-title">Courses</div>
                        </div>
                        <div class="college-stat">
                            <div class="college-stat-number">200+</div>
                            <div class="college-stat-title">Faculty</div>
                        </div>
                        <div class="college-stat">
                            <div class="college-stat-number">5000+</div>
                            <div class="college-stat-title">Students</div>
                        </div>
                        <div class="college-stat">
                            <div class="college-stat-number">35+</div>
                            <div class="college-stat-title">Years</div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="#" class="btn btn-primary">Learn More About Us</a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Notices and Quick Links -->
            <div class="col-lg-6">
                <div class="row">
                    <!-- Recent Notices -->
                    <div class="col-12">
                        <div class="dashboard-card animate__animated animate__fadeIn" style="animation-delay: 0.5s;">
                            <div class="card-header bg-white">
                                <i class="fas fa-bell text-primary"></i> Recent Notices
                            </div>
                            <div class="card-body p-0">
                                <?php if (count($recent_notices) > 0): ?>
                                    <?php foreach ($recent_notices as $notice): ?>
                                        <div class="recent-notice">
                                            <div class="notice-title">
                                                <i class="fas <?php echo ($notice['type'] === 'official') ? 'fa-bullhorn' : 'fa-comment-alt'; ?>"></i>
                                                <?php echo htmlspecialchars($notice['title']); ?>
                                            </div>
                                            <div class="notice-meta">
                                                <span><i class="fas fa-user mr-1"></i> <?php echo htmlspecialchars($notice['username']); ?></span>
                                                <span class="ml-2"><i class="far fa-clock mr-1"></i> <?php echo date('M d, g:i A', strtotime($notice['created_at'])); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted">
                                        <i class="fas fa-info-circle mr-2"></i>No notices found.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white text-center">
                                <a href="add_notice.php" class="btn btn-sm btn-outline-primary">View All Notices</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-12 mt-4">
                        <div class="dashboard-card animate__animated animate__fadeIn" style="animation-delay: 0.6s;">
                            <div class="card-header bg-white">
                                <i class="fas fa-link text-primary"></i> Quick Links
                            </div>
                            <div class="card-body">
                                <div class="quick-links">
                                    <a href="add_notice.php" class="quick-link">
                                        <i class="fas fa-bullhorn text-primary"></i>
                                        <span>Notices</span>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <i class="fas fa-calendar-alt text-success"></i>
                                        <span>Events</span>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <i class="fas fa-book text-danger"></i>
                                        <span>Library</span>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <i class="fas fa-graduation-cap text-warning"></i>
                                        <span>Academics</span>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <i class="fas fa-users text-info"></i>
                                        <span>Faculty</span>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <i class="fas fa-cog text-secondary"></i>
                                        <span>Settings</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Information Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="dashboard-card animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                    <div class="card-header bg-white">
                        <i class="fas fa-calendar-alt text-primary"></i> Upcoming Events
                    </div>
                    <div class="card-body">
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-trophy text-warning"></i>
                                Annual Sports Day
                            </div>
                            <div class="notice-meta">
                                <span><i class="far fa-calendar mr-1"></i> May 15, 2023</span>
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-music text-danger"></i>
                                Cultural Festival
                            </div>
                            <div class="notice-meta">
                                <span><i class="far fa-calendar mr-1"></i> June 5, 2023</span>
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-graduation-cap text-success"></i>
                                Graduation Ceremony
                            </div>
                            <div class="notice-meta">
                                <span><i class="far fa-calendar mr-1"></i> July 10, 2023</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="dashboard-card animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
                    <div class="card-header bg-white">
                        <i class="fas fa-award text-primary"></i> Achievements
                    </div>
                    <div class="card-body">
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-medal text-warning"></i>
                                National Science Competition Winner
                            </div>
                            <div class="notice-meta">
                                <span><i class="far fa-user mr-1"></i> Science Department</span>
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-trophy text-danger"></i>
                                Inter-College Basketball Champions
                            </div>
                            <div class="notice-meta">
                                <span><i class="far fa-user mr-1"></i> Sports Department</span>
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-star text-success"></i>
                                Best College Award 2023
                            </div>
                            <div class="notice-meta">
                                <span><i class="far fa-building mr-1"></i> Education Ministry</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="dashboard-card animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
                    <div class="card-header bg-white">
                        <i class="fas fa-info-circle text-primary"></i> Important Information
                    </div>
                    <div class="card-body">
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-book text-info"></i>
                                Library Hours: 8 AM - 8 PM
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-phone text-success"></i>
                                Helpdesk: +1 (555) 123-4567
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-envelope text-warning"></i>
                                Contact: info@evergreencollege.edu
                            </div>
                        </div>
                        <div class="recent-notice">
                            <div class="notice-title">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                Address: 123 College Road, Education City
                            </div>
                        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to elements on page load
            const animatedElements = document.querySelectorAll('.animate__animated');
            animatedElements.forEach(function(element, index) {
                setTimeout(function() {
                    element.classList.add('animate__fadeIn');
                }, index * 100);
            });
        });
    </script>
</body>
</html>