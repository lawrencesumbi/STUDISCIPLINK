<?php
session_start();

// Redirect if not logged in or not admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'manage_users', 'activity_logs', 'my_account'];

if(!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { margin:0; font-family:'Roboto', sans-serif; background:#f4f4f4; }
        .sidebar {
            position: fixed; top:0; left:0; width:220px; height:100%;
            background-color:#2c3e50; color:white; display:flex; flex-direction:column; padding-top:20px;
        }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:22px; }
        .sidebar a { padding:12px 20px; text-decoration:none; color:white; display:block; transition:0.3s; }
        .sidebar a:hover { background-color:#34495e; }
        .main-content { margin-left:220px; padding:20px; }
        .header { display:flex; justify-content:space-between; align-items:center; background:#ecf0f1; padding:10px 20px; border-radius:8px; }
        .header h2 { margin:0; }
        .header a { text-decoration:none; color:#c41e1e; font-weight:bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="?page=dashboard">Dashboard</a>
    <a href="?page=manage_users">Manage Users</a>
    <a href="?page=activity_logs">Activity Logs</a>
    <a href="?page=my_account">My Account</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h2><?php echo ucfirst(str_replace('_',' ', $page)); ?></h2>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
    </div>
    <div class="content" style="margin-top:20px;">
        <?php include "admin_pages/{$page}.php"; ?>
    </div>
</div>

</body>
</html>
