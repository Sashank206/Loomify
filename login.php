
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page</title>
</head>
<body>
    <style>
        *{
    margin: 0;
    padding: 0;
    font-family: sans-serif;
    box-sizing: border-box;
}
body{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color:rgb(248, 211, 183);
}
a {
    text-decoration: none;}
.login{
    background-color: aliceblue;
    padding: 30px;
    border-radius: 30px;
    text-align: center;
    width:350px;
}
.logo{
    width:150px;
    margin-bottom: 10px;
    border: 3px solid black; 
    border-radius: 20px;
}
h2{
    text-align: center;
    margin-bottom: 30px;
    font-weight: bold;
    color: orange;
}

label{
    color:black;
    font-weight: bold;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    margin-top: 10px;

}
form{
    display: flex;
    flex-direction: column;
    text-align: left;
}
input{
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 20px;
    background: white;
    color:black;
    border: 1px solid black;
    font-size: 16px;

}
button{
    padding: 10px;
    border-radius: 40px;
    border:2px solid black;
    margin-top: 10px;
    background-color: grey;
    font-size: 18px;
    color:black;
    font-weight: bold;
    cursor: pointer;

}
button:hover{
    background-color: aquamarine;
}
.last{
    margin-top: 10px;
    font-size: 12px;
    color:black;
    text-align: right;
}
    </style>
    <script>
        function toggleInputType() {
            const emailInput = document.getElementById('emailOrPhone');
            emailInput.type = emailInput.type === 'email' ? 'text' : 'email';
        }
    </script>
    <div class="login" method="POST" id="myform">
        
        <img src="src/logo.png" alt="logo png" class="logo"> 
       
        <h2>Come On, In </h2> 
        <form action="#" method="POST">
            <label for="email">Email or Phone number</label>
            <input  id="email" name="email" required>
            <label for="password">PASSWORD</label>
            <input type="password" id="Password" name="password"  required>
            <button  type="submit">LOGIN</button>
        </form>
        <div class = "last">
            <p>By signing up, you agree to our <a href="term.html">Terms And Conditions</a>.</p>
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <p>Forgot password? <a href="reset.php">Reset password</a></p>
            
        </div>
    <?php
    session_start();
    include 'db.php';

    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $login_input = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($login_input) || empty($password)) {
            $error_message = "Please enter both login credentials and password.";
        } else {
            // Prepare statement for admin login
            $admin_stmt = $conn->prepare("SELECT admin_id, username, email, phone_number, password, 'admin' as user_type FROM admin WHERE username = ? OR email = ? OR phone_number = ?");
            if (!$admin_stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $admin_stmt->bind_param("sss", $login_input, $login_input, $login_input);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();

            // If no admin found, check user table
            if ($admin_result->num_rows == 0) {
                $admin_stmt->close();

                // Prepare statement for user login
                $user_stmt = $conn->prepare("SELECT user_id, username, email, phone_number, password, role, 'user' as user_type FROM users WHERE username = ? OR email = ? OR phone_number = ?");
                if (!$user_stmt) {
                    die("Error preparing statement: " . $conn->error);
                }
                $user_stmt->bind_param("sss", $login_input, $login_input, $login_input);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();

                if ($user_result->num_rows > 0) {
                    $user = $user_result->fetch_assoc();

                    // Verify user password
                    if (password_verify($password, $user['password'])) {
                        // User login successful
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_type'] = $user['user_type'];
                        $_SESSION['role'] = $user['role'];  // Store role

                        // Log successful login
                        error_log("User {$login_input} logged in successfully at " . date('Y-m-d H:i:s'));

                        // Redirect to appropriate page based on role
                        if ($user['role'] === 'buyer') {
                            header("Location: index.php");
                        } else if ($user['role'] === 'seller') {
                            header("Location: seller/sellerdashboard.php");
                        } else {
                            $error_message = "Invalid role assigned to the user.";
                        }
                        exit();
                    } else {
                        $error_message = "Invalid username, email, phone_number, or password.";
                    }

                    $user_stmt->close();
                } else {
                    $error_message = "No account found with that username, email, or phone.";
                }
            } else {
                $admin = $admin_result->fetch_assoc();

                if (password_verify($password, $admin['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['user_type'] = $admin['user_type'];

                    error_log("Admin {$login_input} logged in successfully at " . date('Y-m-d H:i:s'));

                    header("Location: admin/index.php");
                    exit();
                } else {
                    $error_message = "Invalid credentials. Please try again.";
                }

                $admin_stmt->close();
            }
        }

        // Close database connection
        $conn->close();
    }
    ?>
<script>
        // Display error message if set
        <?php if (!empty($error_message)) : ?>
            alert("<?php echo $error_message; ?>");
        <?php endif; ?>
    </script>
</body>
</html>