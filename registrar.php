<?php
session_start();

// Redirect if not logged in or not registrar
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'registrar') {
    header("Location: login.php");
    exit;
}

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'manage_school_year', 'manage_year_level', 'manage_program', 'manage_section', 'manage_student', 'my_account'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Dashboard</title>
    <style>
        body { margin:0; font-family:'Roboto', sans-serif; background:#f4f4f4; }

        /* Sidebar */
        .sidebar {
            position: fixed; top:0; left:0; width:220px; height:100%;
            background-color:#c41e1e; /* same red as login page */
            color:white; display:flex; flex-direction:column; padding-top:20px;
        }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:22px; }
        .sidebar a { padding:12px 20px; text-decoration:none; color:white; display:block; transition:0.3s; }
        .sidebar a:hover { background-color:#a81a1a; }

        /* Main content */
        .main-content { margin-left:220px; padding:20px; }
        .header { display:flex; justify-content:space-between; align-items:center; background:#ecf0f1; padding:10px 20px; border-radius:8px; }
        .header h2 { margin:0; }
        .header a { text-decoration:none; color:#c41e1e; font-weight:bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Registrar Panel</h2>
    <a href="?page=dashboard">Dashboard</a>
    <a href="?page=manage_school_year">Manage School Year</a>
    <a href="?page=manage_year_level">Manage Year Level</a>
    <a href="?page=manage_program">Manage Program</a>
    <a href="?page=manage_section">Manage Section</a>
    <a href="?page=manage_student">Manage Student</a>
    <a href="?page=my_account">My Account</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h2><?php echo ucfirst(str_replace('_',' ', $page)); ?></h2>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
    </div>

    <div class="content" style="margin-top:20px;">
        <?php
        // Include specific pages
        $pageFile = "registrar_pages/{$page}.php";
        if (file_exists($pageFile)) {
            include $pageFile;
        } else {
            echo "<p>Page not found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
