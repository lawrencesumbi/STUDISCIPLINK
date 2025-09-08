<?php
require __DIR__ . '/../db_connect.php'; // Database connection

// Check logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to enroll students.</p>";
    return;
}
$current_user_id = $_SESSION['user_id'];
$current_user_name = $_SESSION['username'] ?? 'Unknown';

// Get current school year
$current_sy = $pdo->query("SELECT * FROM school_years WHERE is_current=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if (!$current_sy) {
    echo "<p style='color:red;'>No current school year set. Please set one first.</p>";
    return;
}
$current_sy_id = $current_sy['id'];

// Fetch dropdown data
$year_levels = $pdo->query("SELECT * FROM year_levels ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$programs = $pdo->query("SELECT * FROM programs ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT * FROM sections ORDER BY section_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Messages
$message = "";

// Enroll student (insert only)
if (isset($_POST['enroll_student'])) {
    $stmt = $pdo->prepare("INSERT INTO students 
        (first_name, last_name, school_year_id, section_id, year_level_id, program_id, address, contact, user_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        trim($_POST['first_name']),
        trim($_POST['last_name']),
        $current_sy_id,
        $_POST['section_id'],
        $_POST['year_level_id'],
        $_POST['program_id'],
        trim($_POST['address']),
        trim($_POST['contact']),
        $current_user_id
    ]);

    // Remember last selected values
    $_SESSION['last_program'] = $_POST['program_id'];
    $_SESSION['last_year'] = $_POST['year_level_id'];
    $_SESSION['last_section'] = $_POST['section_id'];

    $message = "<p class='success-msg'>Student enrolled successfully!</p>";
}

// Search & Filters
$search = $_GET['search'] ?? '';
$filter_program = $_GET['filter_program'] ?? '';
$filter_year = $_GET['filter_year'] ?? '';
$filter_section = $_GET['filter_section'] ?? '';

$sql = "
    SELECT st.*, sec.section_name, yl.year_level, p.program_code
    FROM students st
    LEFT JOIN sections sec ON st.section_id = sec.id
    LEFT JOIN year_levels yl ON st.year_level_id = yl.id
    LEFT JOIN programs p ON st.program_id = p.id
    WHERE st.school_year_id = ? AND st.user_id = ?
";
$params = [$current_sy_id, $current_user_id];

if (!empty($search)) {
    $sql .= " AND (st.first_name LIKE ? OR st.last_name LIKE ? OR sec.section_name LIKE ? OR yl.year_level LIKE ? OR p.program_name LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param, $search_param]);
}

if ($filter_program) { $sql .= " AND st.program_id = ?"; $params[] = $filter_program; }
if ($filter_year) { $sql .= " AND st.year_level_id = ?"; $params[] = $filter_year; }
if ($filter_section) { $sql .= " AND st.section_id = ?"; $params[] = $filter_section; }

$sql .= " ORDER BY st.id ASC";
$students = $pdo->prepare($sql);
$students->execute($params);
$students = $students->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h3>School Year: <?= htmlspecialchars($current_sy['school_year']); ?></h3>
    <?= $message; ?>
</div>

<!-- Flex container for enroll + search -->
<div class="flex-container">
    <!-- Enroll Student Form -->
    <div class="container half">
        <h4>Enroll Student</h4>
        <form method="POST" class="form-box">
            <!-- Program -->
            <select name="program_id" required>
                <option value="">Select Program</option>
                <?php foreach ($programs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (isset($_SESSION['last_program']) && $_SESSION['last_program'] == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['program_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Year Level -->
            <select name="year_level_id" required>
                <option value="">Select Year Level</option>
                <?php foreach ($year_levels as $yl): ?>
                    <option value="<?= $yl['id'] ?>" <?= (isset($_SESSION['last_year']) && $_SESSION['last_year'] == $yl['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($yl['year_level']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Section -->
            <select name="section_id" required>
                <option value="">Select Section</option>
                <?php foreach ($sections as $sec): ?>
                    <option value="<?= $sec['id'] ?>" <?= (isset($_SESSION['last_section']) && $_SESSION['last_section'] == $sec['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sec['section_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- First & Last Name -->
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>

            <!-- Address & Contact -->
            <input type="text" name="contact" placeholder="Contact">
            <input type="text" name="address" placeholder="Address">

            <button type="submit" name="enroll_student" class="btn btn-primary">Enroll Student</button>
        </form>
    </div>

    <!-- Search & Filter -->
    <div class="container half">
        <h4>Search & Filter</h4>
        <form method="GET" action="registrar.php" class="form-box">
            <input type="hidden" name="page" value="manage_student">

            <select name="filter_program">
                <option value="">Select Programs</option>
                <?php foreach ($programs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $filter_program == $p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['program_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_year">
                <option value="">Select Year Levels</option>
                <?php foreach ($year_levels as $yl): ?>
                    <option value="<?= $yl['id'] ?>" <?= $filter_year == $yl['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($yl['year_level']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_section">
                <option value="">Select Sections</option>
                <?php foreach ($sections as $sec): ?>
                    <option value="<?= $sec['id'] ?>" <?= $filter_section == $sec['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sec['section_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="search" placeholder="Search students..." value="<?= htmlspecialchars($search) ?>">

            <button type="submit" class="btn btn-info">Apply</button>
            <?php if (!empty($search) || $filter_program || $filter_year || $filter_section): ?>
                <a href="registrar.php?page=manage_student" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Table -->
<div class="container">
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Section</th>
                    <th>Year</th>
                    <th>Program</th>
                    <th>Address</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $stu): ?>
                    <tr>
                        <td><?= $stu['id'] ?></td>
                        <td><?= htmlspecialchars($stu['first_name']) ?></td>
                        <td><?= htmlspecialchars($stu['last_name']) ?></td>
                        <td><?= htmlspecialchars($stu['section_name']) ?></td>
                        <td><?= htmlspecialchars($stu['year_level']) ?></td>
                        <td><?= htmlspecialchars($stu['program_code']) ?></td>
                        <td><?= htmlspecialchars($stu['address']) ?></td>
                        <td><?= htmlspecialchars($stu['contact']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No students found.</td></tr>
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
.form-box input, .form-box select {
    padding:5px; border:1px solid #bebebe; border-radius:10px;
    flex:1 1 calc(25% - 10px); min-width:150px;
}
.table-box { max-height:400px; overflow-y:auto; }
.styled-table { width:100%; border-collapse:collapse; border:1px solid #ddd; border-radius:8px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.styled-table th, .styled-table td { padding:10px; border:1px solid #ddd; text-align:left; }
.styled-table thead { background:#c41e1e; color:#fff; }
.styled-table tr:nth-child(even){ background:#f9f9f9; }
.btn { padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:bold; color:white; }
.btn-primary { background:#fc6464ff; }
.btn-info { background:#27ae60; }
.btn-secondary { background:gray; text-decoration:none; color:white; padding:6px 12px; border-radius:6px; }
.btn:hover { opacity:0.9; }
</style>
