<?php
// Start the session (only if it hasn't already been started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
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
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        /* Header Styles */
        .cc-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .cc-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cc-navbar .navbar-brand i {
            font-size: 1.8rem;
        }
        
        .cc-navbar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .cc-navbar .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .cc-navbar .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .cc-navbar .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background-color: white;
            border-radius: 3px;
        }
        
        .cc-navbar .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .cc-navbar .dropdown-item {
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .cc-navbar .dropdown-item:hover {
            background-color: var(--light-gray);
            color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .cc-navbar .dropdown-item i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        .cc-navbar .navbar-toggler {
            border: none;
            color: white;
            padding: 0.5rem;
        }
        
        .cc-navbar .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .user-welcome {
            background-color: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .user-welcome i {
            font-size: 1.2rem;
        }
        
        .user-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50px;
            margin-left: 8px;
            text-transform: uppercase;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        @media (max-width: 991px) {
            .cc-navbar .navbar-collapse {
                background-color: var(--primary-color);
                border-radius: 10px;
                padding: 1rem;
                margin-top: 0.5rem;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            .user-welcome {
                margin-top: 1rem;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar starts -->
    <nav class="navbar navbar-expand-lg cc-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i>
                College Connect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_notice.php' ? 'active' : ''; ?>" href="add_notice.php">
                            <i class="fas fa-bullhorn"></i> Notices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lost_found.php' ? 'active' : ''; ?>" href="lost_found.php">
                            <i class="fas fa-search"></i> Lost & Found
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Account
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user"></i> My Profile
                                </a>
                            </li>
                            <?php if ($userRole === 'admin' || $userRole === 'class_representative'): ?>
                            <li>
                                <a class="dropdown-item" href="dashboard.php">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <?php if ($isLoggedIn): ?>
                    <div class="user-welcome">
                        <i class="fas fa-user"></i>
                        Welcome, <?php echo htmlspecialchars($username); ?>
                        <?php if (!empty($userRole)): ?>
                            <span class="user-badge">
                                <?php 
                                    if ($userRole === 'admin') echo 'Admin';
                                    elseif ($userRole === 'class_representative') echo 'CR';
                                    else echo 'Student';
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex gap-2">
                        <a href="login_page.php" class="btn btn-sm btn-light">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="registration_page.php" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- Navbar completed -->
    <main>
