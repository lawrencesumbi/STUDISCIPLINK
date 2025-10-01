<?php
// admin_pages/dashboard.php

// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root"; // change if needed
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get total number of users
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalUsers = $result['total_users'];

    // Get pending users
    $stmt = $pdo->query("SELECT COUNT(*) as pending_users FROM users WHERE status = 'pending'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pendingUsers = $result['pending_users'];

    // Get active users
    $stmt = $pdo->query("SELECT COUNT(*) as active_users FROM users WHERE status = 'active'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $activeUsers = $result['active_users'];

    // Get the current school year
    $current_sy_row = $pdo->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);

    if ($current_sy_row) {
        $current_sy_id = $current_sy_row['id'];
        $current_school_year = $current_sy_row['school_year'];
    } else {
        // fallback to latest school year if none selected
        $current_sy_row = $pdo->query("SELECT * FROM school_years ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $current_sy_id = $current_sy_row ? $current_sy_row['id'] : null;
        $current_school_year = $current_sy_row ? $current_sy_row['school_year'] : "None";
    }

    // Total students for selected school year
    if ($current_sy_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE school_year_id = ?");
        $stmt->execute([$current_sy_id]);
        $total_students = $stmt->fetchColumn();
    } else {
        $total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }

    // Total programs
    $total_programs = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();

} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
    $totalUsers = 0;
    $pendingUsers = 0;
    $activeUsers = 0;
    $current_school_year = "None";
    $total_students = 0;
    $total_programs = 0;
}
?>

<!-- User Stats -->
<div class="stats-container">
    <div class="stat-box box-red">
        <h3>Total Users</h3>
        <p class="stat-value"><?= $totalUsers ?></p>
    </div>

    <div class="stat-box box-orange">
        <h3>Pending Users</h3>
        <p class="stat-value"><?= $pendingUsers ?></p>
    </div>

    <div class="stat-box box-green">
        <h3>Active Users</h3>
        <p class="stat-value"><?= $activeUsers ?></p>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="stats-container">
    <div class="stat-box box-red-dark">
        <h3>Current School Year</h3>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <div class="stat-box box-red">
        <h3>Total Students</h3>
        <p class="stat-value"><?= $total_students ?></p>
    </div>

    <div class="stat-box box-red-light">
        <h3>Total Programs</h3>
        <p class="stat-value"><?= $total_programs ?></p>
    </div>
</div>

<style>
.stats-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.stat-box {
    flex: 1;
    min-width: 200px;
    min-height: 150px; /* ✅ same height */
    padding: 20px;
    color: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);

    /* ✅ Center everything */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    text-align: center;
}

.stat-box h3 {
    margin: 0 0 10px;
    font-size: 18px;
    font-weight: 600;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    margin: 0;
}

/* Colors */
.box-red { background: #c41e1e; }
.box-orange { background: #e67e22; }
.box-green { background: #27ae60; }

.box-red-dark { background: #8d2525; }
.box-red-light { background: #c41616; }
</style>
