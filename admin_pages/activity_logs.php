<?php
// admin_pages/activity_logs.php

// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all logs with corresponding username
    $stmt = $pdo->prepare("
        SELECT l.id, u.username, l.action, l.date_time
        FROM logs l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.date_time DESC
    ");
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<h3>Activity Logs</h3>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead style="background:#c41e1e; color:white;">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Action</th>
            <th>Date & Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($logs): ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['id']); ?></td>
                    <td><?php echo htmlspecialchars($log['username']); ?></td>
                    <td><?php echo htmlspecialchars($log['action']); ?></td>
                    <td><?php echo htmlspecialchars($log['date_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">No activity logs found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
