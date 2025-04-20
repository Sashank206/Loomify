<?php
session_start();
include '../db.php';


$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];

   
    $stmt = $conn->prepare("SELECT admin_id, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

       
        if ($password === $admin['password']) {
            
            session_regenerate_id(true);

            
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['is_admin'] = true;

            
            error_log("Admin {$username} logged in successfully at " . date('Y-m-d H:i:s'));

            
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid credentials. Please try again.";
            
            
            error_log("Failed admin login attempt for username {$username} at " . date('Y-m-d H:i:s'));
        }
    } else {
        $error_message = "No admin account found.";
    }

    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./admin.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        /* background-color: #f4f4f9; */
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-container {
        background-color: #ffffff;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    h1 {
        margin-bottom: 20px;
        color: #333333;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555555;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #cccccc;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #ffffff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        margin-top: 10px;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .back-to-site {
        margin-top: 15px;
    }

    .back-to-site a {
        color: #007bff;
        text-decoration: none;
    }

    .back-to-site a:hover {
        text-decoration: underline;
    }
    </style>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>

        <?php if(!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="AdminLogin.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       placeholder="Enter your username" 
                       required 
                       autocomplete="username"
                       maxlength="50">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Enter your password" 
                       required 
                       autocomplete="current-password"
                       maxlength="50">
            </div>

            <input type="submit" value="Login">
        </form>

        <div class="back-to-site">
            <a href="../index.php">← Back to Main Site</a>
        </div>
    </div>
</body>
</html>