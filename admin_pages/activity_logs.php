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

<div class="container">
    <p class="subtitle">All user activity is listed below with recent actions shown first.</p>

    <div class="table-box">
        <table class="styled-table">
            <thead>
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
                        <?php $formattedDate = date("m-d-Y h:i A", strtotime($log['date_time'])); ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['id']); ?></td>
                            <td><?php echo htmlspecialchars($log['username']); ?></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($formattedDate); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No activity logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* ===== Layout ===== */
.container {
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-top: 20px;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.container h2 {
    color: #c41e1e;
    margin-bottom: 5px;
}

.subtitle {
    color: #555;
    font-size: 14px;
    margin-bottom: 15px;
}

/* ===== Table Styling ===== */
.table-box {
    overflow-x: auto;
    max-height: 550px;
    border-radius: 10px;
    overflow-y: auto;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    font-size: 15px;
}

.styled-table thead {
    background: #c41e1e;
    color: white;
    position: sticky;
    top: 0;
    z-index: 2;
}

.styled-table th, .styled-table td {
    padding: 12px 15px;
    text-align: left;
}

.styled-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.styled-table tbody tr:hover {
    background-color: #ffeaea;
    transition: 0.2s ease-in-out;
}

.styled-table td {
    border-bottom: 1px solid #ddd;
}

/* ===== Scrollbar Customization ===== */
.table-box::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.table-box::-webkit-scrollbar-thumb {
    background: #c41e1e;
    border-radius: 4px;
}
.table-box::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .styled-table th, .styled-table td {
        font-size: 13px;
        padding: 8px;
    }
    .container {
        padding: 15px;
    }
}
</style>
