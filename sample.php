<?php
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
            $user_stmt = $conn->prepare("SELECT user_id, username, email, phone_number, password, role, location FROM users WHERE username = ? OR email = ? OR phone_number = ?");
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
                    $_SESSION['user_type'] = 'user';
                    $_SESSION['role'] = $user['role'];  // Store role
                    $_SESSION['location'] = $user['location'];  // Store location

                    // Log successful login
                    error_log("User {$login_input} logged in successfully at " . date('Y-m-d H:i:s'));

                    // Redirect to appropriate page based on role
                    if ($user['role'] === 'buyer') {
                        header("Location: buyer_dashboard.php");  // Redirect to buyer's page
                    } else {
                        header("Location: seller_dashboard.php");  // Redirect to seller's page
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
                $_SESSION['user_type'] = 'admin';

                // Log successful login
                error_log("Admin {$login_input} logged in successfully at " . date('Y-m-d H:i:s'));

                // Redirect to admin page
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
