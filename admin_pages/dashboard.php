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

} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
    $totalUsers = 0;
    $pendingUsers = 0;
    $activeUsers = 0;
}
?>

<div style="display:flex; gap:20px; flex-wrap:wrap;">
    <!-- Total Users -->
    <div style="background:#c41e1e; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Total Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?php echo $totalUsers; ?></p>
    </div>

    <!-- Pending Users -->
    <div style="background:#e67e22; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Pending Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?php echo $pendingUsers; ?></p>
    </div>

    <!-- Active Users -->
    <div style="background:#27ae60; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Active Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?php echo $activeUsers; ?></p>
    </div>

    
</div>
