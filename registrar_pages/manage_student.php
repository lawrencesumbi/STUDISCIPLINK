<?php
require __DIR__ . '/../db_connect.php'; // Database connection

// Check logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to manage students.</p>";
    return;
}
$current_user_id = $_SESSION['user_id'];
$current_user_name = $_SESSION['username'] ?? 'Unknown';

// Get current school year from DB (is_current = 1)
$current_sy = $pdo->query("SELECT * FROM school_years WHERE is_current=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if (!$current_sy) {
    echo "<p style='color:red;'>No current school year set. Please set a school year as current first.</p>";
    return;
}
$current_sy_id = $current_sy['id'];

// Fetch independent year levels, programs, sections
$year_levels = $pdo->query("SELECT * FROM year_levels ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$programs = $pdo->query("SELECT * FROM programs ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$sections = $pdo->query("SELECT * FROM sections ORDER BY section_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Function to log activities
function log_activity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

// Handle Add Student
if (isset($_POST['add_student'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $section_id = $_POST['section_id'];
    $year_level_id = $_POST['year_level_id'];
    $program_id = $_POST['program_id'];
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);

    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, school_year_id, section_id, year_level_id, program_id, address, contact, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $current_sy_id, $section_id, $year_level_id, $program_id, $address, $contact, $current_user_id]);

    log_activity($pdo, $current_user_id, "Added student: $first_name $last_name for School Year {$current_sy['school_year']}");
    echo "<p style='color:green;'>Student added successfully!</p>";
}

// Handle Update Student
if (isset($_POST['update_student'])) {
    $id = $_POST['id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $section_id = $_POST['section_id'];
    $year_level_id = $_POST['year_level_id'];
    $program_id = $_POST['program_id'];
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);

    $stmt = $pdo->prepare("UPDATE students SET first_name=?, last_name=?, section_id=?, year_level_id=?, program_id=?, address=?, contact=?, user_id=? WHERE id=?");
    $stmt->execute([$first_name, $last_name, $section_id, $year_level_id, $program_id, $address, $contact, $current_user_id, $id]);

    log_activity($pdo, $current_user_id, "Updated student ID $id: $first_name $last_name");
    echo "<p style='color:green;'>Student updated successfully!</p>";
}

// Handle Delete Student
if (isset($_POST['delete_student'])) {
    $id = $_POST['id'];

    // Fetch student name for logging
    $stu = $pdo->prepare("SELECT first_name, last_name FROM students WHERE id=?");
    $stu->execute([$id]);
    $stu = $stu->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("DELETE FROM students WHERE id=?");
    $stmt->execute([$id]);

    log_activity($pdo, $current_user_id, "Deleted student ID $id: {$stu['first_name']} {$stu['last_name']}");
    echo "<p style='color:red;'>Student deleted successfully!</p>";
}

// Fetch students for current school year including names from related tables
$students = $pdo->prepare("
    SELECT st.*, sec.section_name, yl.year_level, p.program_name
    FROM students st
    LEFT JOIN sections sec ON st.section_id = sec.id
    LEFT JOIN year_levels yl ON st.year_level_id = yl.id
    LEFT JOIN programs p ON st.program_id = p.id
    WHERE st.school_year_id = ?
    ORDER BY st.id ASC
");
$students->execute([$current_sy_id]);
$students = $students->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Students (School Year: <?= htmlspecialchars($current_sy['school_year']); ?>)</h3>
<p>Logged-in User: <b><?= htmlspecialchars($current_user_name); ?></b></p>

<!-- Add Student Form -->
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>

    <select name="section_id" required>
        <option value="">Select Section</option>
        <?php foreach ($sections as $sec): ?>
            <option value="<?= $sec['id'] ?>"><?= htmlspecialchars($sec['section_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="year_level_id" required>
        <option value="">Select Year Level</option>
        <?php foreach ($year_levels as $yl): ?>
            <option value="<?= $yl['id'] ?>"><?= htmlspecialchars($yl['year_level']) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="program_id" required>
        <option value="">Select Program</option>
        <?php foreach ($programs as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['program_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="address" placeholder="Address">
    <input type="text" name="contact" placeholder="Contact">
    <button type="submit" name="add_student">Add Student</button>
</form>

<!-- Students Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Section</th>
        <th>Year Level</th>
        <th>Program</th>
        <th>Address</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($students as $stu): ?>
    <tr>
        <td><?= $stu['id'] ?></td>
        <td><?= htmlspecialchars($stu['first_name']) ?></td>
        <td><?= htmlspecialchars($stu['last_name']) ?></td>
        <td><?= htmlspecialchars($stu['section_name']) ?></td>
        <td><?= htmlspecialchars($stu['year_level']) ?></td>
        <td><?= htmlspecialchars($stu['program_name']) ?></td>
        <td><?= htmlspecialchars($stu['address']) ?></td>
        <td><?= htmlspecialchars($stu['contact']) ?></td>
        <td>
            <!-- Edit form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $stu['id'] ?>">
                <input type="text" name="first_name" value="<?= htmlspecialchars($stu['first_name']) ?>" required>
                <input type="text" name="last_name" value="<?= htmlspecialchars($stu['last_name']) ?>" required>

                <select name="section_id" required>
                    <?php foreach ($sections as $sec): ?>
                        <option value="<?= $sec['id'] ?>" <?= $sec['id']==$stu['section_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sec['section_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="year_level_id" required>
                    <?php foreach ($year_levels as $yl): ?>
                        <option value="<?= $yl['id'] ?>" <?= $yl['id']==$stu['year_level_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($yl['year_level']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="program_id" required>
                    <?php foreach ($programs as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id']==$stu['program_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['program_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="text" name="address" value="<?= htmlspecialchars($stu['address']) ?>">
                <input type="text" name="contact" value="<?= htmlspecialchars($stu['contact']) ?>">
                <button type="submit" name="update_student">Update</button>
            </form>

            <!-- Delete form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $stu['id'] ?>">
                <button type="submit" name="delete_student" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
