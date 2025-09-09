<?php
require __DIR__ . '/../db_connect.php';

// Check logged-in faculty
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in as faculty.</p>";
    return;
}
$user_id = $_SESSION['user_id'];

// Get current school year
$current_sy = $pdo->query("SELECT * FROM school_years WHERE is_current=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if (!$current_sy) {
    echo "<p style='color:red;'>No current school year set. Please set one first.</p>";
    return;
}
$current_sy_id = $current_sy['id'];

// Dropdown data
$year_levels = $pdo->query("SELECT * FROM year_levels ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$programs = $pdo->query("SELECT * FROM programs ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT * FROM sections ORDER BY section_name ASC")->fetchAll(PDO::FETCH_ASSOC);

$message = "";

// Enroll class
if (isset($_POST['enroll_class'])) {
    $program_id = $_POST['program_id'];
    $year_level_id = $_POST['year_level_id'];
    $section_id = $_POST['section_id'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE program_id=? AND year_level_id=? AND section_id=?");
    $stmt->execute([$program_id, $year_level_id, $section_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($students) {
        $stmt = $pdo->prepare("INSERT INTO class_enrollments (user_id, program_id, year_level_id, section_id, school_year_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $program_id, $year_level_id, $section_id, $current_sy_id]);
        $class_enrollment_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO student_enrollments (class_enrollment_id, student_id) VALUES (?, ?)");
        foreach ($students as $stu) {
            $stmt->execute([$class_enrollment_id, $stu['id']]);
        }
        $message = "<p class='success-msg'>Class enrolled successfully (" . count($students) . " students added)</p>";
    } else {
        $message = "<p class='error-msg'>No students found for this Program, Year Level, and Section.</p>";
    }
}

// Delete class (removes all its student_enrollments too)
if (isset($_POST['delete_class'])) {
    $class_id = $_POST['class_enrollment_id'];
    $pdo->prepare("DELETE FROM student_enrollments WHERE class_enrollment_id=?")->execute([$class_id]);
    $pdo->prepare("DELETE FROM class_enrollments WHERE id=? AND user_id=?")->execute([$class_id, $user_id]);
    $message = "<p class='error-msg'>Class and its students deleted successfully!</p>";
}

// Search & filters
$search = $_GET['search'] ?? '';
$filter_program = $_GET['filter_program'] ?? '';
$filter_year = $_GET['filter_year'] ?? '';
$filter_section = $_GET['filter_section'] ?? '';

$sql = "
    SELECT se.id AS enrollment_id, s.id AS student_id, s.first_name, s.last_name,
           p.program_name, yl.year_level, sec.section_name, ce.id AS class_enrollment_id
    FROM student_enrollments se
    INNER JOIN class_enrollments ce ON se.class_enrollment_id = ce.id
    INNER JOIN students s ON se.student_id = s.id
    INNER JOIN programs p ON s.program_id = p.id
    INNER JOIN year_levels yl ON s.year_level_id = yl.id
    INNER JOIN sections sec ON s.section_id = sec.id
    WHERE ce.user_id = ? AND ce.school_year_id = ?
";
$params = [$user_id, $current_sy_id];

if (!empty($search)) {
    $sql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR p.program_name LIKE ? OR yl.year_level LIKE ? OR sec.section_name LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param, $search_param]);
}
if ($filter_program) { $sql .= " AND s.program_id = ?"; $params[] = $filter_program; }
if ($filter_year) { $sql .= " AND s.year_level_id = ?"; $params[] = $filter_year; }
if ($filter_section) { $sql .= " AND s.section_id = ?"; $params[] = $filter_section; }

$sql .= " ORDER BY s.last_name, s.first_name";
$enrolled_students = $pdo->prepare($sql);
$enrolled_students->execute($params);
$enrolled_students = $enrolled_students->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h3>School Year: <?= htmlspecialchars($current_sy['school_year']); ?></h3>
    <?= $message; ?>
</div>

<!-- Flex container -->
<div class="flex-container">
    <!-- Left: Enroll/Delete Class -->
    <div class="container half">
        <h4>Enroll Class</h4>
        <form method="POST" class="form-box">
            <select name="program_id" required>
                <option value="">Select Program</option>
                <?php foreach ($programs as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['program_code']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="year_level_id" required>
                <option value="">Select Year Level</option>
                <?php foreach ($year_levels as $yl): ?>
                    <option value="<?= $yl['id'] ?>"><?= htmlspecialchars($yl['year_level']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="section_id" required>
                <option value="">Select Section</option>
                <?php foreach ($sections as $sec): ?>
                    <option value="<?= $sec['id'] ?>"><?= htmlspecialchars($sec['section_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="enroll_class" class="btn btn-primary">Enroll Class</button>
        </form>

        <h4>Delete Class</h4>
        <form method="POST" class="form-box">
            <select name="class_enrollment_id" required>
                <option value="">Select Class to Delete</option>
                <?php
                $classes = $pdo->prepare("SELECT ce.id, p.program_code, yl.year_code, sec.section_name
                                          FROM class_enrollments ce
                                          JOIN programs p ON ce.program_id = p.id
                                          JOIN year_levels yl ON ce.year_level_id = yl.id
                                          JOIN sections sec ON ce.section_id = sec.id
                                          WHERE ce.user_id=? AND ce.school_year_id=?");
                $classes->execute([$user_id, $current_sy_id]);
                foreach ($classes as $cl): ?>
                    <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['program_code'] . "  " . $cl['year_code'] . "  " . $cl['section_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="delete_class" class="btn btn-danger" onclick="return confirm('Delete this class and all its students?')">Delete</button>
        </form>
    </div>

    <!-- Right: Search & Filter -->
    <div class="container half">
        <h4>Search & Filter</h4>
        <form method="GET" action="faculty.php" class="form-box">
            <input type="hidden" name="page" value="manage_student">

            <select name="filter_program">
                <option value="">All Programs</option>
                <?php foreach ($programs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $filter_program == $p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['program_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_year">
                <option value="">All Years</option>
                <?php foreach ($year_levels as $yl): ?>
                    <option value="<?= $yl['id'] ?>" <?= $filter_year == $yl['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($yl['year_level']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_section">
                <option value="">All Sections</option>
                <?php foreach ($sections as $sec): ?>
                    <option value="<?= $sec['id'] ?>" <?= $filter_section == $sec['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sec['section_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="search" placeholder="Search students..." value="<?= htmlspecialchars($search) ?>">

            <button type="submit" class="btn btn-info">Apply</button>
            <?php if ($search || $filter_program || $filter_year || $filter_section): ?>
                <a href="faculty.php?page=manage_student" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Table -->
<div class="container">
    <h4>Enrolled Students</h4>
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Program</th>
                    <th>Year</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($enrolled_students): ?>
                <?php foreach ($enrolled_students as $i => $stu): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $stu['student_id'] ?></td>
                    <td><?= htmlspecialchars($stu['last_name'].", ".$stu['first_name']) ?></td>
                    <td><?= htmlspecialchars($stu['program_name']) ?></td>
                    <td><?= htmlspecialchars($stu['year_level']) ?></td>
                    <td><?= htmlspecialchars($stu['section_name']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No students found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.flex-container { display:flex; gap:5px; margin-top:5px; }
.container { background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-top:5px; }
.half { flex:1; max-width:50%; }
.success-msg { color:green; font-weight:bold; margin-bottom:10px; }
.error-msg { color:red; font-weight:bold; margin-bottom:10px; }
.form-box { display:flex; flex-wrap:wrap; gap:10px; }
.form-box input, .form-box select { padding:5px; border:1px solid #bebebe; border-radius:10px; flex:1 1 calc(25% - 10px); min-width:150px; }
.table-box { max-height:400px; overflow-y:auto; }
.styled-table { width:100%; border-collapse:collapse; border:1px solid #ddd; border-radius:8px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.styled-table th, .styled-table td { padding:10px; border:1px solid #ddd; text-align:left; }
.styled-table thead { background:#c41e1e; color:#fff; }
.styled-table tr:nth-child(even){ background:#f9f9f9; }
.btn { padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:bold; color:white; }
.btn-primary { background:#28a745; }
.btn-danger { background:#dc3545; }
.btn-info { background:#27ae60; }
.btn-secondary { background:gray; text-decoration:none; color:white; padding:6px 12px; border-radius:6px; }
.btn:hover { opacity:0.9; }
</style>
