<?php
require __DIR__ . '/../db_connect.php'; // include your database connection

// Get logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in.</p>";
    exit;
}
$user_id = $_SESSION['user_id'];

// ✅ Get the current school year
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

// ✅ Pending Violations (status = 'pending')
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM student_violations sv
        JOIN students st ON sv.student_id = st.id
        WHERE sv.status = 'pending' AND st.school_year_id = ?
    ");
    $stmt->execute([$current_sy_id]);
    $pending_violations = $stmt->fetchColumn();
} else {
    $pending_violations = 0;
}

// ✅ Recorded Violations (status = 'recorded' or 'resolved')
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM student_violations sv
        JOIN students st ON sv.student_id = st.id
        WHERE sv.status IN ('recorded','resolved') AND st.school_year_id = ?
    ");
    $stmt->execute([$current_sy_id]);
    $recorded_violations = $stmt->fetchColumn();
} else {
    $recorded_violations = 0;
}
?>

<!-- Dashboard Stats -->
<div class="stats-container">
    <!-- Current School Year -->
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <!-- Pending Violations -->
    <div class="stat-box box-dark-red">
        <h4>Pending Violations</h4>
        <p class="stat-value"><?= $pending_violations ?></p>
    </div>

    <!-- Recorded Violations -->
    <div class="stat-box box-maroon">
        <h4>Recorded Violations</h4>
        <p class="stat-value"><?= $recorded_violations ?></p>
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
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 28px;
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
.box-maroon {
    background: #5c0a0a;
}
</style>
