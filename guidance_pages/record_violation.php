<?php
require __DIR__ . '/../db_connect.php';

// Ensure guidance user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in as Guidance.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Get current school year
$current_sy_row = $pdo->query("SELECT * FROM school_years WHERE is_current=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$current_sy_id = $current_sy_row ? $current_sy_row['id'] : null;
$current_sy = $current_sy_row ? $current_sy_row['school_year'] : "None";

// Messages
$message = "";

// Edit mode
$edit_mode = false;
$edit_id = null;
$edit_action = "";
$edit_remarks = "";

// ✅ Handle Add Record
if (isset($_POST['add_record'])) {
    $student_violation_id = $_POST['student_violations_id'];
    $action_taken = trim($_POST['action_taken']);
    $remarks = trim($_POST['remarks']);

    if ($student_violation_id && $action_taken && $current_sy_id) {
        $stmt = $pdo->prepare("INSERT INTO record_violations (student_violations_id, action_taken, remarks, user_id, school_year_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$student_violation_id, $action_taken, $remarks, $user_id, $current_sy_id]);
        // ✅ Update student_violations status to "Recorded"
        $stmt = $pdo->prepare("UPDATE student_violations SET status='Recorded' WHERE id=?");
        $stmt->execute([$student_violation_id]);
        $message = "<p class='success-msg'>Record added successfully!</p>";
    } else {
        $message = "<p class='error-msg'>Please complete the form.</p>";
    }
}

// ✅ Handle Update Record
if (isset($_POST['update_record'])) {
    $id = $_POST['id'];
    $action_taken = trim($_POST['action_taken']);
    $remarks = trim($_POST['remarks']);
    $stmt = $pdo->prepare("UPDATE record_violations SET action_taken=?, remarks=? WHERE id=?");
    $stmt->execute([$action_taken, $remarks, $id]);
    $message = "<p class='success-msg'>Record updated successfully!</p>";
}

// ✅ Handle Delete Record
if (isset($_POST['delete_record'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM record_violations WHERE id=?");
    $stmt->execute([$id]);
    $message = "<p class='error-msg'>Record deleted successfully!</p>";
}

// ✅ Handle Edit Mode
if (isset($_POST['edit_record'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM record_violations WHERE id=?");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_action = $row['action_taken'];
        $edit_remarks = $row['remarks'];
    }
}

// ✅ Fetch all records (filtered by current school year)
$records = $pdo->prepare("
    SELECT rv.*, sv.description AS violation_description, st.first_name, st.last_name, sy.school_year
    FROM record_violations rv
    JOIN student_violations sv ON rv.student_violations_id = sv.id
    JOIN students st ON sv.student_id = st.id
    JOIN school_years sy ON rv.school_year_id = sy.id
    WHERE rv.school_year_id = ?
    ORDER BY rv.date_recorded DESC
");
$records->execute([$current_sy_id]);
$records = $records->fetchAll(PDO::FETCH_ASSOC);

// Fetch student violations for dropdown (only current SY)
$student_violations = $pdo->prepare("
    SELECT sv.id, st.first_name, st.last_name, sv.description
    FROM student_violations sv
    JOIN students st ON sv.student_id = st.id
    WHERE sv.school_year_id = ? AND sv.status = 'Pending'
    ORDER BY sv.id DESC
");
$student_violations->execute([$current_sy_id]);
$student_violations = $student_violations->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="container">
    <?= $message; ?>

    <!-- Current School Year -->
    <h3>Current School Year: <?= htmlspecialchars($current_sy) ?></h3>

    <!-- Add / Update Form -->
    <form method="POST" class="form-box">
        <!-- Student Violation Dropdown -->
        <select name="student_violations_id" required>
            <option value="">-- Select Student Violation --</option>
            <?php foreach ($student_violations as $sv): ?>
                <option value="<?= $sv['id']; ?>"><?= $sv['first_name'] . " " . $sv['last_name'] . " - " . $sv['description']; ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="action_taken" placeholder="Enter Action Taken"
               value="<?= htmlspecialchars($edit_action); ?>" required>
        <input type="text" name="remarks" placeholder="Enter Remarks"
               value="<?= htmlspecialchars($edit_remarks); ?>">

        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_record" class="btn btn-warning">Update</button>
            <a href="manage_record_violations.php" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_record" class="btn btn-primary">Add Record</button>
        <?php endif; ?>
    </form>

            

    <!-- Records Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Violation</th>
                    <th>Action Taken</th>
                    <th>Remarks</th>
                    <th>Date Recorded</th>
                    
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= $r['id']; ?></td>
                    <td><?= htmlspecialchars($r['first_name'] . " " . $r['last_name']); ?></td>
                    <td><?= htmlspecialchars($r['violation_description']); ?></td>
                    <td><?= htmlspecialchars($r['action_taken']); ?></td>
                    <td><?= htmlspecialchars($r['remarks']); ?></td>
                    <td><?= $r['date_recorded']; ?></td>
                    
                    <td>
                        <!-- Edit -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $r['id']; ?>">
                            <button type="submit" name="edit_record" class="btn btn-info">Edit</button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $r['id']; ?>">
                            <button type="submit" name="delete_record" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Same style as manage_section */
.container { background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-top:20px; }
.success-msg { color:green; font-weight:bold; margin-bottom:10px; }
.error-msg { color:red; font-weight:bold; margin-bottom:10px; }
.form-box { margin-bottom:20px; }
.form-box input, .form-box select {
    padding:8px; border-radius:6px; border:1px solid #ccc; margin-right:10px;
}
.table-box { max-height:400px; overflow-y:auto; }
.styled-table { width:100%; border-collapse:collapse; border:1px solid #ddd; border-radius:8px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.styled-table th, .styled-table td { padding:12px; text-align:left; border:1px solid #ddd; }
.styled-table thead { background:#c41e1e; color:white; }
.styled-table tr:nth-child(even) { background:#f9f9f9; }
.inline-form { display:inline-block; margin:2px; }
.btn { padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:bold; color:white; }
.btn-primary { background:#fc6464ff; }
.btn-warning { background:#27ae60; }
.btn-danger { background:#dc3545; }
.btn-info { background:#27ae60; }
.btn-secondary { background:gray; text-decoration:none; }
.btn:hover { opacity:0.9; }
</style>
