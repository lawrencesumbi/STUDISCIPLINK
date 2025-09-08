<?php
require __DIR__ . '/../db_connect.php'; // include your database connection

// Get the current school year from DB
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

// Total programs (assuming programs are not school year dependent)
$total_programs = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();
?>

<!-- Dashboard Stats -->
<div class="stats-container">
    <!-- Current School Year -->
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <!-- Total Students -->
    <div class="stat-box box-dark-red">
        <h4>Total Students</h4>
        <p class="stat-value"><?= $total_students ?></p>
    </div>

    <!-- Total Programs -->
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
