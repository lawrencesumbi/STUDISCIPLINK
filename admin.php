<?php
require 'db_connect.php';
session_start();

// Redirect if not logged in or not admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch logged in user
$id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'manage_users', 'activity_logs', 'manage_school_year', 'manage_program', 'manage_year_level', 'manage_section', 'manage_student', 'my_account'];
if(!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background-color: #c41e1e; /* red from login page */
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
            letter-spacing: 1px;
        }

        .sidebar a {
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            display: block;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover {
            background-color: #a01b1b;
            
        }

        .sidebar a.active {
            background-color: #900f0f;
            border-left: 4px solid #fff;
        }

        /* Main content */
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ecf0f1;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            text-transform: capitalize;
        }

        .header span {
            font-weight: bold;
            color: #c41e1e;
        }
        .profile-photo {
            text-align: center;
        }
        .profile-photo img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #ebe0e0ff;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="column profile-photo">
        <?php if (!empty($user['img'])): ?>
            <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Profile Image">
        <?php else: ?>
            <img src="default.png" alt="Profile Image">
        <?php endif; ?>
    </div>
    <h2>Admin</h2>
    <a href="?page=dashboard" class="<?php echo $page=='dashboard' ? 'active' : ''; ?>">Dashboard</a>
    <a href="?page=manage_users" class="<?php echo $page=='manage_users' ? 'active' : ''; ?>">Manage Users</a>
    <a href="?page=activity_logs" class="<?php echo $page=='activity_logs' ? 'active' : ''; ?>">Activity Logs</a>
    <a href="?page=manage_school_year" class="<?php echo $page=='manage_school_year' ? 'active' : ''; ?>">Manage School Year</a>
    <a href="?page=manage_program" class="<?php echo $page=='manage_program' ? 'active' : ''; ?>">Manage Program</a>
    <a href="?page=manage_year_level" class="<?php echo $page=='manage_year_level' ? 'active' : ''; ?>">Manage Year Level</a>
    <a href="?page=manage_section" class="<?php echo $page=='manage_section' ? 'active' : ''; ?>">Manage Section</a>
    <a href="?page=manage_student" class="<?php echo $page=='manage_student' ? 'active' : ''; ?>">Manage Student</a>
    <a href="?page=my_account" class="<?php echo $page=='my_account' ? 'active' : ''; ?>">My Account</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h2><?php echo str_replace('_',' ', $page); ?></h2>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
    </div>
    <div class="content">
        <?php 
        // Load page content
        $file = "admin_pages/{$page}.php";
        if(file_exists($file)){
            include $file; 
        } else {
            echo "<p>Page not found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
