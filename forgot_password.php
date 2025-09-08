<?php
session_start();
require __DIR__ . '/db_connect.php'; // adjust path if needed

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email_or_contact = trim($_POST['email_or_contact']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $message = "<p class='msg'>Passwords do not match.</p>";
    } else {
        // Check if username exists with either email OR contact
        $stmt = $pdo->prepare("SELECT id, email, contact FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && ($user['email'] === $email_or_contact || $user['contact'] === $email_or_contact)) {
            // Update password
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashedPassword, $user['id']]);
            $message = "<p class='success'>Password reset successfully. 
                        <a class='login-link' href='login.php'>Login here</a></p>";
        } else {
            $message = "<p class='msg'>Username and Email/Contact do not match our records.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #c41e1e, #6b0f0f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            width: 360px;
            animation: zoomIn 0.6s ease;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #c41e1e;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #c41e1e;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        button:hover {
            background: #ff0000;
        }
        .msg {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            color: red;
        }
        .success {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            color: green;
        }
        .login-link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background: #c41e1e;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.3s ease;
        }
        .login-link:hover {
            background: #ff0000;
        }
        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?= $message ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter your Username" required>
            <input type="text" name="email_or_contact" placeholder="Enter your Email or Contact Number" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
