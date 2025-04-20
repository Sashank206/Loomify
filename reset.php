<?php
// === Database Connection ===
include 'db.php';
// === Handle Reset Request Submission ===
if (isset($_POST['request_reset'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $conn->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE email=?")
             ->execute([$token, $expiry, $email]);

        $link = "http://localhost/reset_password.php?token=$token";
        $subject = "Password Reset - Loomfy Store";
        $message = "Click the link below to reset your password:\n\n$link\n\nValid for 1 hour.";
        $headers = "From: no-reply@loomfy.com";

        mail($email, $subject, $message, $headers);
        echo "<p>✅ Reset link sent to your email.</p>";
    } else {
        echo "<p>❌ Email not found.</p>";
    }
}

// === Handle Password Update Submission ===
if (isset($_POST['update_password'])) {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        $conn->prepare("UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?")
             ->execute([$new_password, $token]);
        echo "<p>✅ Password updated successfully.</p>";
    } else {
        echo "<p>❌ Invalid or expired token.</p>";
    }
}

// === Show Reset Request Form ===
if (!isset($_GET['token'])) {
?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: grey;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        </style>
    <form method="POST">
    <h2>Forgot Password?</h2>
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <button type="submit" name="request_reset">Send Reset Link</button>
    </form>
<?php
}

// === Show New Password Form if token exists ===
if (isset($_GET['token'])) {
    $token = $_GET['token'];
?>
    <h2>Set New Password</h2>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="new_password" placeholder="New Password" required><br><br>
        <button type="submit" name="update_password">Update Password</button>
    </form>
<?php
}
?>
