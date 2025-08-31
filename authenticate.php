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

    // Get form values
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store session info
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // store role in session
        $_SESSION['user_id'] = $user['id']; // store user ID for logging

        // Log login action
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user['id'], 'Logged in']);

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin.php");
                break;
            case 'guidance':
            case 'SAO':
            case 'registrar':
            case 'faculty':
                header("Location: dashboard.php"); // generic user dashboard
                break;
            default:
                $_SESSION['error'] = "Role not recognized.";
                header("Location: login.php");
                break;
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit;
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
