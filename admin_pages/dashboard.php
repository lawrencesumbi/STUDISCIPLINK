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
<div style="display:flex; gap:20px; flex-wrap:wrap;">
    <div style="background:#c41e1e; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Total Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?= $totalUsers ?></p>
    </div>

    <div style="background:#e67e22; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Pending Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?= $pendingUsers ?></p>
    </div>

    <div style="background:#27ae60; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Active Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?= $activeUsers ?></p>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="stats-container">
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <div class="stat-box box-dark-red">
        <h4>Total Students</h4>
        <p class="stat-value"><?= $total_students ?></p>
    </div>

    <div class="stat-box box-light-red">
        <h4>Total Programs</h4>
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
    padding: 20px;
    color: white;
    border-radius: 8px;
    min-width: 200px;
}

.stat-value {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
}

/* Colors */
.box-red {
    background: #ff0000ff;
}

.box-dark-red {
    background: #c41616ff;
}

.box-light-red {
    background: #8d2525ff;
}
</style>
