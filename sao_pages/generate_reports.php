<?php
require __DIR__ . '/../db_connect.php'; // Database connection

// Check logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to manage students.</p>";
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
    WHERE st.school_year_id = ?
";
$params = [$current_sy_id];

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

<div class="container small-container">
    <h3>Current School Year: <span style="color:#b30000;"><?= htmlspecialchars($current_sy['school_year']); ?></span></h3>
    <?= $message; ?>
</div>

<!-- Search & Filter Container -->
<div class="container">
    <h4>Search & Filter</h4>
    <form method="GET" action="sao.php" class="form-box">
        <input type="hidden" name="page" value="generate_reports">

        <select name="filter_program">
            <option value="">Select Programs</option>
            <?php foreach ($programs as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $filter_program == $p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['program_code']) ?>
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
            <a href="sao.php?page=generate_reports" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
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
                    <th>Program</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $stu): ?>
                    <tr>
                        <td><?= $stu['id'] ?></td>
                        <td><?= htmlspecialchars($stu['first_name']) ?></td>
                        <td><?= htmlspecialchars($stu['last_name']) ?></td>
                        <td><?= htmlspecialchars($stu['program_code']) ?></td>
                        <td><?= htmlspecialchars($stu['year_level']) ?></td>
                        <td><?= htmlspecialchars($stu['section_name']) ?></td>
                        <td><?= htmlspecialchars($stu['address']) ?></td>
                        <td><?= htmlspecialchars($stu['contact']) ?></td>
                        <td>
                            <form method="POST" action="printsummary.php" class="inline-form">
                                <input type="hidden" name="id" value="<?= $stu['id'] ?>">
                                <button type="submit" name="view_record" class="btn btn-info">View Record</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" style="text-align:center;">No students found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.small-container {
    padding: 8px 15px;
    flex: 1;
    display: block;
    max-width: 100%;
}
.small-container h3 {
    font-size: 16px;
    margin: 0;
}
.container {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    margin-top:5px;
}
.success-msg { color:green; font-weight:bold; margin-bottom:10px; }
.error-msg { color:red; font-weight:bold; margin-bottom:10px; }
.form-box {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.form-box input,
.form-box select {
    padding: 5px; 
    border: 1px solid #bebebeff;
    border-radius: 10px;
    flex: 1 1 calc(25% - 10px);
    min-width: 150px;
}
.table-box { max-height:400px; overflow-y:auto; }
.styled-table { width:100%; border-collapse:collapse; border:1px solid #ddd; border-radius:8px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.styled-table th, .styled-table td { padding:10px; border:1px solid #ddd; text-align:left; }
.styled-table thead { background:#c41e1e; color:#fff; }
.styled-table tr:nth-child(even){ background:#f9f9f9; }
.inline-form { display:inline-block; margin:2px; }
.btn { padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:bold; color:white; }
.btn-info { background:#27ae60; }
.btn-secondary { background:gray; text-decoration:none; color:white; padding:6px 12px; border-radius:6px; }
.btn:hover { opacity:0.9; }
</style>
