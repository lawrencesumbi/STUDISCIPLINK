<?php
session_start();
require 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Role-based landing pages
function getLandingPage($role) {
    switch ($role) {
        case 'admin':
            return "admin.php?page=my_account";
        case 'faculty':
            return "faculty.php?page=my_account";
        case 'registrar':
            return "registrar.php?page=my_account";
        default:
            return "login.php"; // fallback if role not recognized
    }
}

$redirectPage = getLandingPage($user['role']);

// -------------------- UPDATE ACCOUNT INFO --------------------
if (isset($_POST['update_info'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);

    // Handle profile image
    $imgPath = $user['img']; // keep old image if not replaced
    if (!empty($_FILES['img']['name'])) {
        $targetDir = "../studisciplink/userUploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imgPath = $targetDir . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $imgPath);
    }

    // Update query
    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, contact=?, img=? WHERE id=?");
    $stmt->execute([$username, $email, $contact, $imgPath, $id]);

    // Log action
    $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, 'Updated account info', NOW())");
    $log->execute([$id]);

    $_SESSION['message'] = "Account information updated successfully!";
    header("Location: " . $redirectPage);
    exit;
}

// -------------------- CHANGE PASSWORD --------------------
if (isset($_POST['change_password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verify old password
    if (!password_verify($oldPassword, $user['password'])) {
        $_SESSION['pass_message'] = "Old password is incorrect.";
        header("Location: " . $redirectPage);
        exit;
    }

    // Check new password confirmation
    if ($newPassword !== $confirmPassword) {
        $_SESSION['pass_message'] = "New password and confirm password do not match.";
        header("Location: " . $redirectPage);
        exit;
    }

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->execute([$hashedPassword, $id]);

    // Log action
    $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, 'Changed password', NOW())");
    $log->execute([$id]);

    $_SESSION['pass_message'] = "Password changed successfully!";
    header("Location: " . $redirectPage);
    exit;
}
?>
