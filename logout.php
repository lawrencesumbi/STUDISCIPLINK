<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root";   // change if needed
$pass = "";       // change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Check if user session exists before destroying
    if (isset($_SESSION['user_id'])) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$_SESSION['user_id'], 'Logged out']);
    }

    // Destroy session
    session_destroy();

    // Redirect to login
    header("Location: login.php");
    exit;

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
