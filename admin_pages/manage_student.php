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

// Edit mode variables
$edit_mode = false;
$edit_student = [];

// Activity log helper
function log_activity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

// After adding student
if (isset($_POST['add_student'])) {
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

    // ✅ Remember last selected values
    $_SESSION['last_program'] = $_POST['program_id'];
    $_SESSION['last_year'] = $_POST['year_level_id'];
    $_SESSION['last_section'] = $_POST['section_id'];

    log_activity($pdo, $current_user_id, "Added student: {$_POST['first_name']} {$_POST['last_name']}");
    $message = "<p class='success-msg'>Student added successfully!</p>";
}

// Update student
if (isset($_POST['update_student'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("UPDATE students 
        SET first_name=?, last_name=?, section_id=?, year_level_id=?, program_id=?, address=?, contact=?, user_id=? 
        WHERE id=?");
    $stmt->execute([
        trim($_POST['first_name']),
        trim($_POST['last_name']),
        $_POST['section_id'],
        $_POST['year_level_id'],
        $_POST['program_id'],
        trim($_POST['address']),
        trim($_POST['contact']),
        $current_user_id,
        $id
    ]);

    // ✅ Remember last selected values
    $_SESSION['last_program'] = $_POST['program_id'];
    $_SESSION['last_year'] = $_POST['year_level_id'];
    $_SESSION['last_section'] = $_POST['section_id'];
    log_activity($pdo, $current_user_id, "Updated student ID $id");
    $message = "<p class='success-msg'>Student updated successfully!</p>";
}

// Delete student
if (isset($_POST['delete_student'])) {
    $id = $_POST['id'];
    $stu = $pdo->prepare("SELECT first_name, last_name FROM students WHERE id=?");
    $stu->execute([$id]);
    $stu = $stu->fetch(PDO::FETCH_ASSOC);

    $pdo->prepare("DELETE FROM students WHERE id=?")->execute([$id]);
    log_activity($pdo, $current_user_id, "Deleted student ID $id: {$stu['first_name']} {$stu['last_name']}");
    $message = "<p class='error-msg'>Student deleted successfully!</p>";
}

// Edit student (load form)
if (isset($_POST['edit_student'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id=?");
    $stmt->execute([$id]);
    $edit_student = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($edit_student) $edit_mode = true;
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

<!-- Flex container for add/update + search -->
<div class="flex-container">
    <!-- Add / Update Student Form Container -->
    <div class="container half">
    <h4><?= $edit_mode ? "Update Student" : "Add Student" ?></h4>
    <form method="POST" class="form-box">

        <!-- Program -->
<select name="program_id" required>
    <option value="">Select Program</option>
    <?php foreach ($programs as $p): ?>
        <option value="<?= $p['id'] ?>"
            <?php 
                if (isset($edit_student['program_id']) && $edit_student['program_id'] == $p['id']) {
                    echo 'selected';
                } elseif (!$edit_mode && isset($_SESSION['last_program']) && $_SESSION['last_program'] == $p['id']) {
                    echo 'selected';
                }
            ?>>
            <?= htmlspecialchars($p['program_code']) ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Year Level -->
<select name="year_level_id" required>
    <option value="">Select Year Level</option>
    <?php foreach ($year_levels as $yl): ?>
        <option value="<?= $yl['id'] ?>"
            <?php 
                if (isset($edit_student['year_level_id']) && $edit_student['year_level_id'] == $yl['id']) {
                    echo 'selected';
                } elseif (!$edit_mode && isset($_SESSION['last_year']) && $_SESSION['last_year'] == $yl['id']) {
                    echo 'selected';
                }
            ?>>
            <?= htmlspecialchars($yl['year_level']) ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Section -->
<select name="section_id" required>
    <option value="">Select Section</option>
    <?php foreach ($sections as $sec): ?>
        <option value="<?= $sec['id'] ?>"
            <?php 
                if (isset($edit_student['section_id']) && $edit_student['section_id'] == $sec['id']) {
                    echo 'selected';
                } elseif (!$edit_mode && isset($_SESSION['last_section']) && $_SESSION['last_section'] == $sec['id']) {
                    echo 'selected';
                }
            ?>>
            <?= htmlspecialchars($sec['section_name']) ?>
        </option>
    <?php endforeach; ?>
</select>

        <!-- First & Last Name -->
        <input type="text" name="first_name" placeholder="First Name"
               value="<?= htmlspecialchars($edit_student['first_name'] ?? '') ?>" required>
        <input type="text" name="last_name" placeholder="Last Name"
               value="<?= htmlspecialchars($edit_student['last_name'] ?? '') ?>" required>

        <!-- Address & Contact -->
        <input type="text" name="contact" placeholder="Contact"
               value="<?= htmlspecialchars($edit_student['contact'] ?? '') ?>">
        <input type="text" name="address" placeholder="Address"
               value="<?= htmlspecialchars($edit_student['address'] ?? '') ?>">

        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_student['id'] ?>">
            <button type="submit" name="update_student" class="btn btn-warning">Update Student</button>
            <a href="" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
        <?php endif; ?>
    </form>
</div>
    <!-- Search & Filter Container -->
    <div class="container half">
        <h4>Search & Filter</h4>
        <form method="GET" action="admin.php" class="form-box">
            <input type="hidden" name="page" value="manage_student">
            
            
            
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
                <a href="admin.php?page=manage_student" class="btn btn-secondary">Clear</a>
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
                    <th>Program</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Actions</th>
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
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?= $stu['id'] ?>">
                                <button type="submit" name="edit_student" class="btn btn-info">Edit</button>
                            </form>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?= $stu['id'] ?>">
                                <button type="submit" name="delete_student" class="btn btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
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
    padding: 8px 15px;   /* less padding */
    flex: 1;          /* ✅ same flex behavior as .container */
    display: block;   /* ✅ not inline-block */
    max-width: 100%; 
}
.small-container h3 {
    font-size: 16px;  /* smaller font if you want */
    margin: 0;
}

.flex-container {
    display: flex;
    gap: 5px;
    margin-top: 5px;
}
.container {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    margin-top:5px;
}
.half {
    flex: 1;
    max-width: 50%;
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
    flex: 1 1 calc(25% - 10px); /* two columns */
    min-width: 150px;
}

.table-box { max-height:400px; overflow-y:auto; }
.styled-table { width:100%; border-collapse:collapse; border:1px solid #ddd; border-radius:8px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.styled-table th, .styled-table td { padding:10px; border:1px solid #ddd; text-align:left; }
.styled-table thead { background:#c41e1e; color:#fff; }
.styled-table tr:nth-child(even){ background:#f9f9f9; }
.inline-form { display:inline-block; margin:2px; }
.btn { padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:bold; color:white; }
.btn-primary { background:#fc6464ff; }
.btn-warning { background:#27ae60;  }
.btn-danger { background:#dc3545; }
.btn-info { background:#27ae60;  }
.btn-secondary { background:gray; text-decoration:none; color:white; padding:6px 12px; border-radius:6px; }
.btn:hover { opacity:0.9; }
</style>
