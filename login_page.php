<?php
session_start();
include 'includes/db_connect.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password, $role);
        $stmt->fetch();

        // Simple password check (in a real app, you'd use password_verify)
        if ($password == $db_password) {
            $_SESSION['username'] = $db_username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password! Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User not found! Please register.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Connect</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 700;
            font-size: 2rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
            padding-left: 40px;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
            background-color: #fff;
        }
        
        .input-icon {
            position: absolute;
            top: 40px;
            left: 15px;
            color: #a0aec0;
        }

        button {
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
        }
        
        button:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header .logo {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: var(--text-color);
            font-size: 1rem;
        }
        
        .test-credentials {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9em;
            border-left: 4px solid var(--primary-color);
        }
        
        .test-credentials h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .test-credentials p {
            margin: 5px 0;
            color: var(--text-color);
        }
        
        .test-credentials strong {
            color: #333;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: var(--text-color);
            font-size: 0.9rem;
        }
        
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e2e8f0;
        }
        
        .divider::before {
            margin-right: 10px;
        }
        
        .divider::after {
            margin-left: 10px;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            h2 {
                font-size: 1.75rem;
            }
        }
    </style> 
</head>
<body>
    <div class="container">
        <div class="form-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to continue to College Connect</p>
        </div>
        
        <form class="login-form" method="POST" action="login_page.php">
            <div class="input-group">
                <label for="username">Username</label>
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            <p class="register-link">Don't have an account? <a href="registration_page.php">Register</a></p>
        </form>
    </div>
</body>
</html>
