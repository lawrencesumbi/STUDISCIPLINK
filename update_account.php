<?php
session_start();
require 'db_connect.php'; // 🔹 include your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password'];

    // Handle profile image
    $imgPath = null;
    if (!empty($_FILES['img']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imgPath = $targetDir . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $imgPath);
    }

    // Build update query
    $sql = "UPDATE users SET username=?, email=?, contact=?";
    $params = [$username, $email, $contact];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password=?";
        $params[] = $hashedPassword;
    }

    if ($imgPath) {
        $sql .= ", img=?";
        $params[] = $imgPath;
    }

    $sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Log the update
    $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, 'Updated account details', NOW())");
    $log->execute([$id]);

    $_SESSION['message'] = "Account updated successfully!";
    header("Location: admin.php?page=my_account");
    exit;
}
?>
