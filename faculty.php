<?php
require 'db_connect.php';
session_start();

// Redirect if not logged in or not faculty
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
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
$allowed_pages = ['dashboard', 'manage_school_year', 'manage_classes', 'student_violation', 'my_account'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Faculty</title>
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
        .sidebar a.active {
            background-color: #900f0f;
            border-left: 4px solid #fff;
        }

        /* Main content */
        .main-content { margin-left:220px; padding:20px; }
        .header { display:flex; justify-content:space-between; align-items:center; background:#ecf0f1; padding:10px 20px; border-radius:8px; }
        .header h2 { margin:0; }
        .header a { text-decoration:none; color:#c41e1e; font-weight:bold; }
        .header span{font-weight: bold;color: #c41e1e;}
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
    <h2>Faculty</h2>
    <a href="?page=dashboard" class="<?= $page == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <a href="?page=manage_school_year" class="<?= $page == 'manage_school_year' ? 'active' : '' ?>">School Year</a>
    <a href="?page=manage_classes" class="<?= $page == 'manage_classes' ? 'active' : '' ?>">Manage Classes</a>
    <a href="?page=student_violation" class="<?= $page == 'student_violation' ? 'active' : '' ?>">Student Violation</a>
    <a href="?page=my_account" class="<?= $page == 'my_account' ? 'active' : '' ?>">My Account</a>
    <a href="logout.php" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
</div>


<div class="main-content">
    <div class="header">
        <h2><?php echo ucwords(str_replace('_',' ', $page)); ?></h2>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
    </div>

    <div class="content" style="margin-top:20px;">
        <?php
        // Include specific pages
        $pageFile = "faculty_pages/{$page}.php";
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
