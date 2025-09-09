<?php
require __DIR__ . '/../db_connect.php'; // include your database connection

// Get logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in.</p>";
    exit;
}
$user_id = $_SESSION['user_id'];

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

// Total Classes for this faculty in current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM class_enrollments WHERE user_id = ? AND school_year_id = ?");
    $stmt->execute([$user_id, $current_sy_id]);
    $total_classes = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM class_enrollments WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_classes = $stmt->fetchColumn();
}

// Total Students under this faculty’s classes in current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT se.student_id)
        FROM student_enrollments se
        JOIN class_enrollments ce ON se.class_enrollment_id = ce.id
        WHERE ce.user_id = ? AND ce.school_year_id = ?
    ");
    $stmt->execute([$user_id, $current_sy_id]);
    $total_students = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT se.student_id)
        FROM student_enrollments se
        JOIN class_enrollments ce ON se.class_enrollment_id = ce.id
        WHERE ce.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $total_students = $stmt->fetchColumn();
}

// ✅ Total Violations recorded by this faculty in current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM student_violations sv
        JOIN students st ON sv.student_id = st.id
        WHERE sv.user_id = ? AND st.school_year_id = ?
    ");
    $stmt->execute([$user_id, $current_sy_id]);
    $total_violations = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM student_violations WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_violations = $stmt->fetchColumn();
}
?>

<!-- Dashboard Stats -->
<div class="stats-container">
    <!-- Current School Year -->
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <!-- Total Classes -->
    <div class="stat-box box-dark-red">
        <h4>Total Classes</h4>
        <p class="stat-value"><?= $total_classes ?></p>
    </div>

    <!-- Total Students -->
    <div class="stat-box box-light-red">
        <h4>Total Students</h4>
        <p class="stat-value"><?= $total_students ?></p>
    </div>

    <!-- ✅ Total Violations -->
    <div class="stat-box box-maroon">
        <h4>Total Violations</h4>
        <p class="stat-value"><?= $total_violations ?></p>
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
.box-light-red {
    background: #8d2525ff;
}
.box-maroon {
    background: #5c0a0a;
}
</style>
