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
$edit_sanction_id = null;
$edit_remarks = "";
$edit_violation_id = null;

// ✅ Handle Add Record
if (isset($_POST['add_record'])) {
    $student_violation_id = $_POST['student_violations_id'];
    $sanction_id = $_POST['sanction_id'];
    $remarks = trim($_POST['remarks']);

    if ($student_violation_id && $sanction_id && $current_sy_id) {
        $stmt = $pdo->prepare("INSERT INTO record_violations 
            (student_violations_id, sanction_id, remarks, user_id, school_year_id, status) 
            VALUES (?, ?, ?, ?, ?, 'Ongoing')");
        $stmt->execute([$student_violation_id, $sanction_id, $remarks, $user_id, $current_sy_id]);

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
    $sanction_id = $_POST['sanction_id'];
    $remarks = trim($_POST['remarks']);
    $stmt = $pdo->prepare("UPDATE record_violations SET sanction_id=?, remarks=? WHERE id=?");
    $stmt->execute([$sanction_id, $remarks, $id]);
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
        SELECT rv.*, 
               sv.id AS student_violation_id,
               s.first_name, s.last_name,
               v.violation AS violation_name
        FROM record_violations rv
        JOIN student_violations sv ON rv.student_violations_id = sv.id
        JOIN students s ON sv.student_id = s.id
        JOIN violations v ON sv.violation_id = v.id
        WHERE rv.id=?
    ");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $edit_mode = true;
        $edit_id = $row['id'];
        $edit_sanction_id = $row['sanction_id'];
        $edit_remarks = $row['remarks'];
        $edit_violation_id = $row['student_violation_id']; // important
        $edit_student_name = $row['first_name'] . " " . $row['last_name'];
        $edit_violation_name = $row['violation_name'];
    }
}


// ✅ Handle Search
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$query = "
    SELECT rv.*, 
           v.violation AS violation_name,
           s.sanction AS sanction_name,
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
    JOIN violations v ON sv.violation_id = v.id
    JOIN sanctions s ON rv.sanction_id = s.id
    WHERE rv.school_year_id = ?
";
$params = [$current_sy_id];

if ($search !== "") {
    $query .= " AND (st.first_name LIKE ? OR st.last_name LIKE ? OR sv.description LIKE ? OR s.sanction LIKE ? OR rv.remarks LIKE ?)";
    $like = "%" . $search . "%";
    $params = [$current_sy_id, $like, $like, $like, $like, $like];
}

$query .= " ORDER BY rv.date_recorded DESC";

$records = $pdo->prepare($query);
$records->execute($params);
$records = $records->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch student violations with violation name (pending only)
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

// ✅ Fetch sanctions
$stmt = $pdo->prepare("SELECT id, sanction FROM sanctions ORDER BY sanction ASC");
$stmt->execute();
$sanctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    JOIN users u ON sv.user_id = u.id
    WHERE sv.school_year_id = ?
      AND sv.status = 'Pending'
    ORDER BY sv.date_time DESC
");
$stmt->execute([$current_sy_id]);

$studentViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalViolations = count($studentViolations);

?>

<div class="container small-container">
    <h3>Current School Year: <span style="color:#b30000;"><?= htmlspecialchars($current_sy) ?></span></h3>
    <?= $message; ?>
</div>

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
                <th>Date Reported</th>
                <th>ReportBy</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($totalViolations > 0): ?>
                <?php foreach ($studentViolations as $i => $v): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($v['first_name'] . " " . $v['last_name']); ?></td>
                        <td><?= htmlspecialchars($v['program_code']) ?> - <?= htmlspecialchars($v['year_level']) ?><?= htmlspecialchars($v['section_name']) ?></td>
                        <td><?= htmlspecialchars($v['violation']); ?></td>
                        <td><?= htmlspecialchars($v['description']); ?></td>
                        <td><?= htmlspecialchars($v['location']); ?></td>
                        <td><?= htmlspecialchars($v['date_time']); ?></td>
                        <td><?= htmlspecialchars($v['reported_by']); ?></td>
                        <td><strong><?= htmlspecialchars($v['status']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" style="text-align:center;">No violations reported yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="two-columns">
    <!-- LEFT SIDE: Form -->
    <div class="left-box">
        <h4>Assign Sanction</h4>

        <form method="POST" class="form-box">
            <!-- Student Violation Dropdown -->
            <?php if ($edit_mode): ?>
    <!-- Show student + violation (not editable) -->
    <input type="text" value="<?= htmlspecialchars($edit_student_name . " - " . $edit_violation_name) ?>" disabled>
    <input type="hidden" name="student_violations_id" value="<?= $edit_violation_id ?>">
<?php else: ?>
    <!-- Normal dropdown when adding -->
    <select name="student_violations_id" required>
        <option value="">-- Select Student Violation --</option>
        <?php foreach ($student_violations as $sv): ?>
            <option value="<?= $sv['id']; ?>">
                <?= $sv['first_name'] . " " . $sv['last_name'] . " - " . $sv['violation_name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php endif; ?>


            <!-- ✅ Sanction Dropdown -->
            <select name="sanction_id" required>
                <option value="">-- Select Sanction --</option>
                <?php foreach ($sanctions as $s): ?>
                    <option value="<?= $s['id']; ?>" <?= ($edit_mode && $edit_sanction_id == $s['id']) ? "selected" : "" ?>>
                        <?= htmlspecialchars($s['sanction']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- ✅ Bigger Remarks Field -->
            <textarea name="remarks" class="remarks-input" placeholder="Enter Remarks / Notes" rows="5" required><?= htmlspecialchars($edit_remarks); ?></textarea>

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
            <input type="text" id="recordSearch" class="search-input" placeholder="Search student, violations or sanctions...">
        </div>

        <div class="form-box filter-grid">
    <select id="filterClass">
        <option value="">Select Class</option>
        <?php 
        $classes = [];
        foreach ($records as $rec) {
            $className = $rec['program_code'] . " - " . $rec['year_level'] . $rec['section_name'];
            $classes[] = $className;
        }
        foreach (array_unique($classes) as $class): ?>
            <option value="<?= htmlspecialchars($class) ?>"><?= htmlspecialchars($class) ?></option>
        <?php endforeach; ?>
    </select>

    <select id="filterViolation">
        <option value="">Select Violation</option>
        <?php foreach (array_unique(array_column($records, 'violation_name')) as $vio): ?>
            <option value="<?= htmlspecialchars($vio) ?>"><?= htmlspecialchars($vio) ?></option>
        <?php endforeach; ?>
    </select>

    <!-- ✅ NEW: Sanction Filter -->
    <select id="filterSanction">
        <option value="">Select Sanction</option>
        <?php foreach (array_unique(array_column($records, 'sanction_name')) as $san): ?>
            <option value="<?= htmlspecialchars($san) ?>"><?= htmlspecialchars($san) ?></option>
        <?php endforeach; ?>
    </select>

    <select id="filterStatus">
        <option value="">Select Status</option>
        <option value="Ongoing">Ongoing</option>
        <option value="Resolved">Resolved</option>
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

        <table class="styled-table" id="recordedTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Violation</th>
                    <th>Sanction</th>
                    <th>Remarks</th>
                    <th>Date Recorded</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($records): ?>
                <?php foreach ($records as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= htmlspecialchars($r['first_name'] . " " . $r['last_name']); ?></td>
                        <td><?= htmlspecialchars($r['program_code'] . " - " . $r['year_level'] . $r['section_name']); ?></td>
                        <td><?= htmlspecialchars($r['violation_name']); ?></td>
                        <td><?= htmlspecialchars($r['sanction_name']); ?></td>
                        <td><?= htmlspecialchars($r['remarks']); ?></td>
                        <td><?= $r['date_recorded']; ?></td>
                        <td><strong><?= htmlspecialchars($r['status']); ?></strong></td>
                        <td>
                        <?php if ($r['status'] === 'Ongoing'): ?>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?= $r['id']; ?>">
                                <button type="submit" name="edit_record" class="btn btn-warning">Edit</button>
                            </form>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                <input type="hidden" name="id" value="<?= $r['id']; ?>">
                                <button type="submit" name="delete_record" class="btn btn-danger">Delete</button>
                            </form>
                        <?php else: ?>
                            <em>N/A</em>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="noRecordRow" style="display:none;">
                    <td colspan="9" style="text-align:center; color:red; font-weight:bold;">No matching records found.</td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align:center; color:red; font-weight:bold;">No records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.small-container { padding: 8px 15px; flex: 1; display: block; max-width: 100%; }
.small-container h3 { font-size: 16px; margin: 0; }
.container { background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-top:20px; }
.success-msg { color:green; font-weight:bold; margin-bottom:10px; }
.error-msg { color:red; font-weight:bold; margin-bottom:10px; }
.form-box { display: flex; flex-wrap: wrap; gap: 5px; }
.form-box select, .form-box textarea, .form-box input { padding: 8px; border: 1px solid #ccc; border-radius: 15px; margin-bottom: 10px; width: 100%; }
.table-box { max-height:400px; overflow-y:auto; }
.styled-table { width:100%; border-collapse:collapse; border:1px solid #ddd; border-radius:8px; overflow:hidden; box-shadow:0 1px 5px rgba(0,0,0,0.1); }
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
.remarks-input { width: 100%; resize: vertical; padding: 10px; font-size: 14px; }
.two-columns { display: flex; gap: 20px; align-items: stretch; margin-top: 15px; margin-bottom: 15px; }
.left-box, .right-box { flex: 1; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.filter-buttons { grid-column: span 3; display: flex; gap: 10px; margin-top: 10px; justify-content: flex-end; }
</style>

<script>
function applyRecordFilters() {
    let search = document.getElementById("recordSearch").value.toLowerCase();
    let classFilter = document.getElementById("filterClass").value.toLowerCase();
    let violationFilter = document.getElementById("filterViolation").value.toLowerCase();
    let sanctionFilter = document.getElementById("filterSanction").value.toLowerCase(); // ✅ new
    let statusFilter = document.getElementById("filterStatus").value.toLowerCase();

    let table = document.getElementById("recordedTable").getElementsByTagName("tbody")[0];
    let rows = table.getElementsByTagName("tr");
    let noRecordRow = document.getElementById("noRecordRow");
    let found = false;

    for (let i = 0; i < rows.length; i++) {
        let cols = rows[i].getElementsByTagName("td");
        if (cols.length > 0) {
            let student = cols[1].innerText.toLowerCase();
            let className = cols[2].innerText.toLowerCase();
            let violation = cols[3].innerText.toLowerCase();
            let sanction = cols[4].innerText.toLowerCase();
            let remarks = cols[5].innerText.toLowerCase();
            let status = cols[7].innerText.toLowerCase();

            let matchesSearch = search === "" || student.includes(search) || violation.includes(search) || sanction.includes(search) || remarks.includes(search);
            let matchesClass = classFilter === "" || className.includes(classFilter);
            let matchesViolation = violationFilter === "" || violation.includes(violationFilter);
            let matchesSanction = sanctionFilter === "" || sanction.includes(sanctionFilter); // ✅ new
            let matchesStatus = statusFilter === "" || status.includes(statusFilter);

            if (matchesSearch && matchesClass && matchesViolation && matchesSanction && matchesStatus) {
                rows[i].style.display = "";
                found = true;
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    noRecordRow.style.display = found ? "none" : "";
}

function cancelRecordFilters() {
    document.getElementById("recordSearch").value = "";
    document.getElementById("filterClass").value = "";
    document.getElementById("filterViolation").value = "";
    document.getElementById("filterSanction").value = ""; // ✅ reset
    document.getElementById("filterStatus").value = "";

    applyRecordFilters();
}

</script>
