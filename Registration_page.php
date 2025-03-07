<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - College Connect</title>
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
            max-width: 450px;
            padding: 30px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
        }

        .register-form {
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

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
            background-color: #fff;
        }
        
        .input-icon {
            position: absolute;
            top: 40px;
            right: 15px;
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
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
        }

        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
        }
        
        button:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
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
            <h2>Create Account</h2>
            <p>Join College Connect today</p>
        </div>
        
        <form class="register-form" action="registration_page.php" method="POST">
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your full name" required>
                <i class="fas fa-user input-icon"></i>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                <i class="fas fa-envelope input-icon"></i>
            </div>
            <div class="input-group">
                <label for="uname">Username</label>
                <input type="text" name="uname" id="uname" placeholder="Choose a username" required>
                <i class="fas fa-user-tag input-icon"></i>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Create a password" required>
                <i class="fas fa-lock input-icon"></i>
            </div>
            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm your password" required>
                <i class="fas fa-lock input-icon"></i>
            </div>
            <div class="input-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="regular_user">Student</option>
                    <option value="admin">Admin</option>
                    <option value="class_representative">Class Representative</option>
                </select>
                <i class="fas fa-user-shield input-icon"></i>
            </div>
            <button type="submit">
                <i class="fas fa-user-plus"></i> Register
            </button>
            <p class="login-link">Already have an account? <a href="login_page.php">Login</a></p>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $uname = $_POST['uname'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='registration_page.php';</script>";
        exit();
    }
    
    include 'includes/db_connect.php';
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(username,password,role) VALUES ('$uname', '$confirm_password', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location.href='login_page.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . " - " . $conn->error . "'); window.location.href='registration_page.php';</script>";
    }
    
    $conn->close();
}
?>
