<?php
// admin_pages/dashboard.php

// Make sure session is started in parent file
// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root"; // change if needed
$pass = "";     // change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get total number of users
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalUsers = $result['total_users'];

} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
    $totalUsers = 0;
}
?>

<div style="display:flex; gap:20px; flex-wrap:wrap;">
    <div style="background:#c41e1e; color:white; padding:30px; border-radius:12px; flex:1; min-width:200px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
        <h3>Total Users</h3>
        <p style="font-size:32px; font-weight:bold;"><?php echo $totalUsers; ?></p>
    </div>
</div>
