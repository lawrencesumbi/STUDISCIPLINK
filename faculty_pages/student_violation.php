<?php
require __DIR__ . '/../db_connect.php';

// Ensure faculty is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in as faculty.</p>";
    exit;
}
$user_id = $_SESSION['user_id'];

// Get current school year
$current_sy = $pdo->query("SELECT * FROM school_years WHERE is_current=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if (!$current_sy) {
    echo "<p style='color:red;'>No current school year set. Please set one first.</p>";
    exit;
}
$current_sy_id = $current_sy['id'];

// ---------------------- FETCH STUDENTS ----------------------
$stmt = $pdo->prepare("
    SELECT s.id, s.first_name, s.last_name, p.program_code, yl.year_code, sec.section_name
    FROM student_enrollments se
    JOIN class_enrollments ce ON se.class_enrollment_id = ce.id
    JOIN students s ON se.student_id = s.id
    JOIN programs p ON s.program_id = p.id
    JOIN year_levels yl ON s.year_level_id = yl.id
    JOIN sections sec ON s.section_id = sec.id
    WHERE ce.user_id = ? AND ce.school_year_id = ?
    ORDER BY p.program_code, yl.year_level, sec.section_name, s.last_name
");
$stmt->execute([$user_id, $current_sy_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------- FETCH VIOLATIONS ----------------------
$violations = $pdo->query("SELECT * FROM violations ORDER BY violation ASC")->fetchAll(PDO::FETCH_ASSOC);

// ---------------------- HANDLE ADD VIOLATION ----------------------
$message = "";
$edit_id = null;
$edit_data = null;

if (isset($_POST['add_violation'])) {
    $student_id = $_POST['student_id'];
    $violation_id = $_POST['violation_id'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);

    if (!empty($student_id) && !empty($violation_id) && !empty($location)) {
        $stmt = $pdo->prepare("
            INSERT INTO student_violations 
                (student_id, violation_id, description, location, date_time, status, user_id, school_year_id)
            VALUES (?, ?, ?, ?, NOW(), 'Pending', ?, ?)
        ");
        $stmt->execute([$student_id, $violation_id, $description, $location, $user_id, $current_sy_id]);
        $message = "<p class='success-msg'>Violation recorded successfully (Pending status).</p>";
    } else {
        $message = "<p class='error-msg'>Please complete all required fields.</p>";
    }
}

// Load record for editing
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $pdo->prepare("SELECT * FROM student_violations WHERE id=? AND user_id=? LIMIT 1");
    $stmt->execute([$edit_id, $user_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Delete violation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM student_violations WHERE id=? AND user_id=? AND status='Pending'");
    $stmt->execute([$delete_id, $user_id]);
    $message = "<p class='success-msg'>Violation deleted successfully.</p>";
}

// Add new or update
if (isset($_POST['save_violation'])) {
    $student_id = $_POST['student_id'];
    $violation_id = $_POST['violation_id'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $hidden_id = $_POST['hidden_id'] ?? "";

    if (!empty($student_id) && !empty($violation_id) && !empty($location)) {
        if ($hidden_id) {
            // Update
            $stmt = $pdo->prepare("
                UPDATE student_violations 
                SET student_id=?, violation_id=?, description=?, location=?
                WHERE id=? AND user_id=? AND status='Pending'
            ");
            $stmt->execute([$student_id, $violation_id, $description, $location, $hidden_id, $user_id]);
            $message = "<p class='success-msg'>Violation updated successfully.</p>";
        } else {
            // Insert
            $stmt = $pdo->prepare("
                INSERT INTO student_violations 
                    (student_id, violation_id, description, location, date_time, status, user_id, school_year_id)
                VALUES (?, ?, ?, ?, NOW(), 'Pending', ?, ?)
            ");
            $stmt->execute([$student_id, $violation_id, $description, $location, $user_id, $current_sy_id]);
            $message = "<p class='success-msg'>Violation recorded successfully (Pending status).</p>";
        }
    } else {
        $message = "<p class='error-msg'>Please complete all required fields.</p>";
    }
}

// ---------------------- FETCH STUDENT VIOLATIONS ----------------------
$stmt = $pdo->prepare("
    SELECT sv.id, s.first_name, s.last_name, 
           p.program_code, 
           yl.year_code AS year_level,
           sec.section_name,
           v.violation, sv.description, sv.location, sv.date_time, sv.status
    FROM student_violations sv
    JOIN students s ON sv.student_id = s.id
    JOIN programs p ON s.program_id = p.id
    JOIN year_levels yl ON s.year_level_id = yl.id
    JOIN sections sec ON s.section_id = sec.id
    JOIN violations v ON sv.violation_id = v.id
    WHERE sv.user_id = ? AND sv.school_year_id = ?
    ORDER BY sv.date_time DESC
");
$stmt->execute([$user_id, $current_sy_id]);

$studentViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------- TOTAL VIOLATIONS ----------------------
$totalViolations = count($studentViolations);
?>

<div class="two-column">
    <!-- Left: Record Violation -->
    <div class="container">
        <h3>Report Student</h3>
        <?= $message; ?>
<!-- Report Student Form -->
<form method="POST" class="form-box">
    <input type="hidden" name="hidden_id" value="<?= $edit_data['id'] ?? '' ?>">

    <div class="form-row">
        <div>
            <label>Student</label>
            <select name="student_id" id="studentSelect" required>
                <option value="">Select Student</option>
                <?php foreach ($students as $stu): ?>
                    <option value="<?= $stu['id'] ?>"
                        <?= ($edit_data && $edit_data['student_id'] == $stu['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($stu['first_name'] . " " . $stu['last_name'] . " - " . $stu['program_code'] . " " . $stu['year_code'] . " " . $stu['section_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Violation</label>
            <select name="violation_id" id="violationSelect" required>
                <option value="">Select Violation</option>
                <?php foreach ($violations as $vio): ?>
                    <option value="<?= $vio['id'] ?>"
                        <?= ($edit_data && $edit_data['violation_id'] == $vio['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($vio['violation']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <label>Description</label>
    <textarea name="description" placeholder="Specify Details..."><?= $edit_data['description'] ?? '' ?></textarea>

    <label>Location</label>
    <input type="text" name="location" required placeholder="Enter location"
           value="<?= $edit_data['location'] ?? '' ?>">

    <!-- Buttons -->
    <?php if ($edit_data): ?>
        <button type="submit" name="save_violation" class="btn btn-primary">Update Violation</button>
        <a href="faculty.php?page=student_violation" class="btn btn-secondary">Cancel</a>
    <?php else: ?>
        <button type="submit" name="add_violation" class="btn btn-primary">Submit Violation</button>
    <?php endif; ?>
</form>

    </div>

    <!-- Right: Filters -->
    <div class="container">
        <h3>(School Year: <?= htmlspecialchars($current_sy['school_year']); ?>)</h3>
        <h4>Search & Filter</h4>
        <div class="form-box filter-grid">
            <input type="text" id="violationSearch" placeholder="Search student or violations...">

            <select id="filterProgram">
                <option value="">Select Program</option>
                <?php foreach (array_unique(array_column($studentViolations, 'program_code')) as $program): ?>
                    <option value="<?= htmlspecialchars($program) ?>"><?= htmlspecialchars($program) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterYear">
                <option value="">Select Year Level</option>
                <?php foreach (array_unique(array_column($studentViolations, 'year_level')) as $year): ?>
                    <option value="<?= htmlspecialchars($year) ?>"><?= htmlspecialchars($year) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterSection">
                <option value="">Select Section</option>
                <?php foreach (array_unique(array_column($studentViolations, 'section_name')) as $sec): ?>
                    <option value="<?= htmlspecialchars($sec) ?>"><?= htmlspecialchars($sec) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterViolation">
                <option value="">Select Violation</option>
                <?php foreach (array_unique(array_column($studentViolations, 'violation')) as $vio): ?>
                    <option value="<?= htmlspecialchars($vio) ?>"><?= htmlspecialchars($vio) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterLocation">
                <option value="">Select Location</option>
                <?php foreach (array_unique(array_column($studentViolations, 'location')) as $loc): ?>
                    <option value="<?= htmlspecialchars($loc) ?>"><?= htmlspecialchars($loc) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterStatus">
                <option value="">Select Status</option>
                <?php foreach (array_unique(array_column($studentViolations, 'status')) as $status): ?>
                    <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Apply + Cancel Buttons -->
        <div class="filter-buttons">
            <button type="button" class="btn" onclick="applyFilters()">Apply</button>
            <button type="button" class="btn btn-secondary" onclick="cancelFilters()">Cancel</button>
        </div>

        </div>
    </div>
</div>

<div class="container">
    <h4>My Reported Violations (Total: <?= $totalViolations ?>)</h4>

    <table class="styled-table" id="violationTable">
        <thead>
            <tr>
                <th>No.</th> <!-- ✅ Added -->
                <th>Student</th>
                <th>Class</th> <!-- ✅ Combined -->
                <th>Violation</th>
                <th>Description</th>
                <th>Location</th>
                <th>Date Reported</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($totalViolations > 0): ?>
                <?php foreach ($studentViolations as $i => $v): ?> <!-- ✅ Added $i -->
                    <tr>
                        <td><?= $i + 1 ?></td> <!-- ✅ Auto-number -->
                        <td><?= htmlspecialchars($v['first_name'] . " " . $v['last_name']); ?></td>
                        <td>
                            <?= htmlspecialchars($v['program_code']) ?> - 
                            <?= htmlspecialchars($v['year_level']) ?><?= htmlspecialchars($v['section_name']) ?>
                        </td> <!-- ✅ Combined -->
                        <td><?= htmlspecialchars($v['violation']); ?></td>
                        <td><?= htmlspecialchars($v['description']); ?></td>
                        <td><?= htmlspecialchars($v['location']); ?></td>
                        <td><?= htmlspecialchars($v['date_time']); ?></td>
                        <td><strong><?= htmlspecialchars($v['status']); ?></strong></td>
                        <td>
                            <?php if ($v['status'] === 'Pending'): ?>
                                <a href="faculty.php?page=student_violation&edit_id=<?= $v['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="faculty.php?page=student_violation&delete_id=<?= $v['id'] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this violation?');"
                                   class="btn btn-sm btn-secondary">Delete</a>
                            <?php else: ?>
                                <em>N/A</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No violations recorded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<script>
// APPLY FILTERS
function applyFilters() {
    let search = document.getElementById("violationSearch").value.toLowerCase();
    let program = document.getElementById("filterProgram").value.toLowerCase();
    let year = document.getElementById("filterYear").value.toLowerCase();
    let section = document.getElementById("filterSection").value.toLowerCase();
    let violation = document.getElementById("filterViolation").value.toLowerCase();
    let location = document.getElementById("filterLocation").value.toLowerCase();
    let status = document.getElementById("filterStatus").value.toLowerCase();

    let rows = document.querySelectorAll("#violationTable tbody tr");
    let matchCount = 0;

    rows.forEach(row => {
        if (row.classList.contains("no-record")) return;

        let student = row.cells[1].textContent.toLowerCase();
        let classCol = row.cells[2].textContent.toLowerCase(); // ✅ Program+Year+Section
        let rowViolation = row.cells[3].textContent.toLowerCase();
        let rowDescription = row.cells[4].textContent.toLowerCase();
        let rowLocation = row.cells[5].textContent.toLowerCase();
        let rowStatus = row.cells[7].textContent.toLowerCase();

        let matches = student.includes(search) ||
                      rowViolation.includes(search) ||
                      rowDescription.includes(search);

        matches = matches &&
                  (program === "" || classCol.includes(program)) &&
                  (year === "" || classCol.includes(year)) &&
                  (section === "" || classCol.includes(section)) &&
                  (violation === "" || rowViolation === violation) &&
                  (location === "" || rowLocation === location) &&
                  (status === "" || rowStatus === status);

        row.style.display = matches ? "" : "none";
        if (matches) matchCount++;
    });

    // Remove old "no record" row if exists
    let tbody = document.querySelector("#violationTable tbody");
    let noRecordRow = tbody.querySelector(".no-record");
    if (noRecordRow) noRecordRow.remove();

    if (matchCount === 0) {
        let tr = document.createElement("tr");
        tr.classList.add("no-record");
        let td = document.createElement("td");
        td.colSpan = 9;
        td.style.textAlign = "center";
        td.textContent = "No record found";
        tr.appendChild(td);
        tbody.appendChild(tr);
    }
}



// CANCEL FILTERS → refresh page
function cancelFilters() {
    window.location.href = "faculty.php?page=student_violation";
}
</script>


<style>
/* === Layout for the two top containers === */
.two-column {
    display: flex;
    gap: 20px;
    align-items: stretch; /* makes both containers same height */
    margin-top: 15px;
    margin-bottom: 15px;
}

/* === Generic container === */
.container {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* === Messages === */
.success-msg { color: green; font-weight: bold; margin-bottom: 10px; }
.error-msg { color: red; font-weight: bold; margin-bottom: 10px; }

/* === Forms === */
.form-box {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 10px;
}
.form-box select,
.form-box textarea,
.form-box input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 10px;
    width: 96%;
}
.form-box textarea { min-height: 20px; }

/* === Form grid (2 per row for student & violation) === */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.form-row > div {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* === Filter grid (3 per row, search full width) === */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.filter-grid input { grid-column: span 3; }
.filter-buttons {
    grid-column: span 3;
    display: flex;
    gap: 10px;
    margin-top: 10px;
    justify-content: flex-end;
}

/* === Buttons === */
.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    color: white;
    background: #27ae60;
}
.btn:hover { opacity: 0.9; }
.btn-secondary { background: #c41e1e; color: white; }
.btn-secondary:hover { opacity: 0.9; }

.btn btn-sm btn-primary {}

a.btn {
    text-decoration: none;   /* removes underline */
    display: inline-block;   /* makes it behave like a button */
    text-align: center;
}

/* === Tables === */
.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.styled-table th,
.styled-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
.styled-table th {
    background: #c41e1e;
    color: white;
}
.styled-table tr:nth-child(even) { background: #f9f9f9; }

</style>
