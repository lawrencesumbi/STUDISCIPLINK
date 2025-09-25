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

// Edit mode vars
$edit_mode = false;
$edit_id = null;
$edit_action = "";
$edit_remarks = "";
$edit_violation_id = null;

// ✅ Handle Add Record
if (isset($_POST['add_record'])) {
    $student_violation_id = $_POST['student_violations_id'];
    $action_taken = trim($_POST['action_taken']);
    $remarks = trim($_POST['remarks']);

    if ($student_violation_id && $action_taken && $current_sy_id) {
        // Insert with status "Ongoing"
        $stmt = $pdo->prepare("INSERT INTO record_violations 
            (student_violations_id, action_taken, remarks, user_id, school_year_id, status) 
            VALUES (?, ?, ?, ?, ?, 'Ongoing')");
        $stmt->execute([$student_violation_id, $action_taken, $remarks, $user_id, $current_sy_id]);

        // Update student_violations status
        $stmt = $pdo->prepare("UPDATE student_violations SET status='Recorded' WHERE id=?");
        $stmt->execute([$student_violation_id]);

        $message = "<p class='success-msg'>Record added successfully! (Status: Ongoing)</p>";
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
    $stmt = $pdo->prepare("
        SELECT rv.*, sv.id AS violation_id
        FROM record_violations rv
        JOIN student_violations sv ON rv.student_violations_id = sv.id
        WHERE rv.id=?
    ");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_action = $row['action_taken'];
        $edit_remarks = $row['remarks'];
        $edit_violation_id = $row['violation_id'];
    }
}

// ✅ Handle Search
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$query = "
    SELECT rv.*, 
           sv.description AS violation_description, 
           st.first_name, st.last_name, 
           p.program_code, 
           yl.year_code AS year_level, 
           sec.section_name,
           sy.school_year
    FROM record_violations rv
    JOIN student_violations sv ON rv.student_violations_id = sv.id
    JOIN students st ON sv.student_id = st.id
    JOIN programs p ON st.program_id = p.id
    JOIN year_levels yl ON st.year_level_id = yl.id
    JOIN sections sec ON st.section_id = sec.id
    JOIN school_years sy ON rv.school_year_id = sy.id
    WHERE rv.school_year_id = ?
";

$params = [$current_sy_id];

if ($search !== "") {
    $query .= " AND (st.first_name LIKE ? OR st.last_name LIKE ? OR sv.description LIKE ? OR rv.action_taken LIKE ? OR rv.remarks LIKE ?)";
    $like = "%" . $search . "%";
    $params = [$current_sy_id, $like, $like, $like, $like, $like];
}

$query .= " ORDER BY rv.date_recorded DESC";

$records = $pdo->prepare($query);
$records->execute($params);
$records = $records->fetchAll(PDO::FETCH_ASSOC);

// Fetch student violations with violation name
$stmt = $pdo->prepare("
    SELECT sv.id, sv.student_id, s.first_name, s.last_name,
           v.violation AS violation_name
    FROM student_violations sv
    JOIN students s ON sv.student_id = s.id
    JOIN violations v ON sv.violation_id = v.id
    WHERE sv.school_year_id = ? AND sv.status = 'Pending'
    ORDER BY sv.id DESC
");
$stmt->execute([$current_sy_id]);
$student_violations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------- FETCH STUDENT VIOLATIONS (Pending only) ----------------------
$stmt = $pdo->prepare("
    SELECT sv.id, s.first_name, s.last_name, 
           p.program_code, 
           yl.year_code AS year_level,
           sec.section_name,
           v.violation, sv.description, sv.location, sv.date_time, sv.status,
           u.username AS reported_by
    FROM student_violations sv
    JOIN students s ON sv.student_id = s.id
    JOIN programs p ON s.program_id = p.id
    JOIN year_levels yl ON s.year_level_id = yl.id
    JOIN sections sec ON s.section_id = sec.id
    JOIN violations v ON sv.violation_id = v.id
    JOIN users u ON sv.user_id = u.id  -- ✅ Join users table
    WHERE sv.school_year_id = ?
      AND sv.status = 'Pending'
    ORDER BY sv.date_time DESC
");
$stmt->execute([$current_sy_id]);

$studentViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalViolations = count($studentViolations);




?>

<div class="container">
    <h4>Pending Violations (Total: <?= $totalViolations ?>)</h4>

    <table class="styled-table" id="violationTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Student</th>
                <th>Class</th>
                <th>Violation</th>
                <th>Description</th>
                <th>Location</th>
                <th>Date</th>
                <th>Reported By</th> <!-- ✅ New column -->
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($totalViolations > 0): ?>
                <?php foreach ($studentViolations as $i => $v): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($v['first_name'] . " " . $v['last_name']); ?></td>
                        <td>
                            <?= htmlspecialchars($v['program_code']) ?> - 
                            <?= htmlspecialchars($v['year_level']) ?><?= htmlspecialchars($v['section_name']) ?>
                        </td>
                        <td><?= htmlspecialchars($v['violation']); ?></td>
                        <td><?= htmlspecialchars($v['description']); ?></td>
                        <td><?= htmlspecialchars($v['location']); ?></td>
                        <td><?= htmlspecialchars($v['date_time']); ?></td>
                        <td><?= htmlspecialchars($v['reported_by']); ?></td> <!-- ✅ Show username -->
                        <td><strong><?= htmlspecialchars($v['status']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" style="text-align:center;">No violations reported yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



<div class="container two-columns">
    <!-- LEFT SIDE: Form -->
    <div class="left-box">
        <?= $message; ?>
        <h3>Current School Year: <?= htmlspecialchars($current_sy) ?></h3>

        <form method="POST" class="form-box">
            <!-- Student Violation Dropdown -->
            <select name="student_violations_id" required <?= $edit_mode ? "disabled" : "" ?>>
                <option value="">-- Select Student Violation --</option>
                <?php foreach ($student_violations as $sv): ?>
                    <option value="<?= $sv['id']; ?>"
                        <?= ($edit_mode && $edit_violation_id == $sv['id']) ? "selected" : "" ?>>
                        <?= $sv['first_name'] . " " . $sv['last_name'] . " - " . $sv['violation_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if ($edit_mode): ?>
                <!-- Keep violation ID in form even if disabled -->
                <input type="hidden" name="student_violations_id" value="<?= $edit_violation_id ?>">
            <?php endif; ?>

            <input type="text" name="action_taken" placeholder="Enter Sanction"
                value="<?= htmlspecialchars($edit_action); ?>" required>
            <input type="text" name="remarks" class="remarks-input" placeholder="Enter Remarks"
                value="<?= htmlspecialchars($edit_remarks); ?>">

            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $edit_id; ?>">
                <button type="submit" name="update_record" class="btn btn-warning">Update</button>
                <a href="guidance.php?page=record_violation" class="btn btn-secondary">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_record" class="btn btn-primary">Add Record</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- RIGHT SIDE: Search + Filter -->
    <div class="right-box">
    
    <h4>Search & Filter</h4>

    <div class="form-box">
        <!-- Search input -->
        <input type="text" id="recordSearch" class="search-input" placeholder="Search student or violations...">
    </div>

    <div class="form-box filter-grid">
        <!-- Program Filter -->
        <select id="filterProgram">
            <option value="">All Programs</option>
            <?php foreach (array_unique(array_column($records, 'program_code')) as $program): ?>
                <option value="<?= htmlspecialchars($program) ?>"><?= htmlspecialchars($program) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Year Level Filter -->
        <select id="filterYear">
            <option value="">All Year Levels</option>
            <?php foreach (array_unique(array_column($records, 'year_level')) as $year): ?>
                <option value="<?= htmlspecialchars($year) ?>"><?= htmlspecialchars($year) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Section Filter -->
        <select id="filterSection">
            <option value="">All Sections</option>
            <?php foreach (array_unique(array_column($records, 'section_name')) as $sec): ?>
                <option value="<?= htmlspecialchars($sec) ?>"><?= htmlspecialchars($sec) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Violation Filter -->
        <select id="filterViolation">
            <option value="">All Violations</option>
            <?php foreach (array_unique(array_column($records, 'violation_description')) as $vio): ?>
                <option value="<?= htmlspecialchars($vio) ?>"><?= htmlspecialchars($vio) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-buttons">
            <button type="button" class="btn btn-info" onclick="applyRecordFilters()">Apply</button>
            <button type="button" class="btn btn-secondary" onclick="cancelRecordFilters()">Cancel</button>
    </div>
</div>
</div>

<div class="container">           
    <!-- Records Table -->
    <div class="table-box">
        <h4>RECORDED Student Violations</h4>

        <table class="styled-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Class</th>
            <th>Violation</th>
            <th>Action Taken</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Date Recorded</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($records): ?>
        <?php foreach ($records as $r): ?>
            <tr>
                <td><?= $r['id']; ?></td>
                <td><?= htmlspecialchars($r['last_name'] . ", " . $r['first_name']); ?></td>
                <td><?= htmlspecialchars($r['program_code'] . " - " . $r['year_level'] . $r['section_name']); ?></td>
                <td><?= htmlspecialchars($r['violation_description']); ?></td>
                <td><?= htmlspecialchars($r['action_taken']); ?></td>
                <td><?= htmlspecialchars($r['remarks']); ?></td>
                <td><strong><?= htmlspecialchars($r['status']); ?></strong></td>
                <td><?= $r['date_recorded']; ?></td>
                <td>
                    <?php if ($r['status'] === 'Resolved'): ?>
                        <em>N/A</em>
                    <?php else: ?>
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
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No matching records found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
    </div>
</div>

<style>
.container { background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-top:20px; }
.success-msg { color:green; font-weight:bold; margin-bottom:10px; }
.error-msg { color:red; font-weight:bold; margin-bottom:10px; }
.form-box {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}
.form-box select,
.form-box textarea,
.form-box input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 10px;
    width: 100%;
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
.remarks-input { width: 400px; }
.two-columns { display: flex; gap: 20px; }
.left-box, .right-box {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.filter-buttons {
    grid-column: span 3;
    display: flex;
    gap: 10px;
    margin-top: 10px;
    justify-content: flex-end;
}
</style>

<script>
function applyRecordFilters() {
    let search = document.getElementById("recordSearch").value.toLowerCase();
    let program = document.getElementById("filterProgram").value.toLowerCase();
    let year = document.getElementById("filterYear").value.toLowerCase();
    let section = document.getElementById("filterSection").value.toLowerCase();
    let violation = document.getElementById("filterViolation").value.toLowerCase();

    let rows = document.querySelectorAll(".styled-table tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        let rowProgram = row.cells[2]?.innerText.toLowerCase() || "";
        let rowYear = row.cells[2]?.innerText.toLowerCase() || "";
        let rowSection = row.cells[2]?.innerText.toLowerCase() || "";
        let rowViolation = row.cells[3]?.innerText.toLowerCase() || "";

        let match = true;

        if (search && !text.includes(search)) match = false;
        if (program && !rowProgram.includes(program)) match = false;
        if (year && !rowYear.includes(year)) match = false;
        if (section && !rowSection.includes(section)) match = false;
        if (violation && !rowViolation.includes(violation)) match = false;

        row.style.display = match ? "" : "none";
    });
}

function cancelRecordFilters() {
    document.getElementById("recordSearch").value = "";
    document.getElementById("filterProgram").value = "";
    document.getElementById("filterYear").value = "";
    document.getElementById("filterSection").value = "";
    document.getElementById("filterViolation").value = "";

    applyRecordFilters();
}
</script>
