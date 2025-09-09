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
    SELECT s.id, s.first_name, s.last_name, p.program_code, yl.year_level, sec.section_name
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
if (isset($_POST['add_violation'])) {
    $student_id = $_POST['student_id'];
    $violation_id = $_POST['violation_id'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);

    if (!empty($student_id) && !empty($violation_id) && !empty($location)) {
        $stmt = $pdo->prepare("
            INSERT INTO student_violations (student_id, violation_id, description, location, date_time, status, user_id)
            VALUES (?, ?, ?, ?, NOW(), 'Pending', ?)
        ");
        $stmt->execute([$student_id, $violation_id, $description, $location, $user_id]);
        $message = "<p class='success-msg'>Violation recorded successfully (Pending status).</p>";
    } else {
        $message = "<p class='error-msg'>Please complete all required fields.</p>";
    }
}

// ---------------------- FETCH STUDENT VIOLATIONS ----------------------
$stmt = $pdo->prepare("
    SELECT sv.id, s.first_name, s.last_name, p.program_code, yl.year_level, sec.section_name,
           v.violation, sv.description, sv.location, sv.date_time, sv.status
    FROM student_violations sv
    JOIN students s ON sv.student_id = s.id
    JOIN programs p ON s.program_id = p.id
    JOIN year_levels yl ON s.year_level_id = yl.id
    JOIN sections sec ON s.section_id = sec.id
    JOIN violations v ON sv.violation_id = v.id
    WHERE sv.user_id = ?
    ORDER BY sv.date_time DESC
");
$stmt->execute([$user_id]);
$studentViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------- TOTAL VIOLATIONS ----------------------
$totalViolations = count($studentViolations);
?>

<div class="two-column">
    <!-- Left: Record Violation -->
    <div class="container">
        <h3>Record Violation</h3>
        <?= $message; ?>

        <form method="POST" class="form-box">
            <div class="form-row">
                <div>
                    <label>Student</label>
                    <select name="student_id" id="studentSelect" required>
                        <option value="">Select Student</option>
                        <?php foreach ($students as $stu): ?>
                            <option value="<?= $stu['id'] ?>">
                                <?= htmlspecialchars($stu['last_name'] . ", " . $stu['first_name'] . " - " . $stu['program_code'] . " " . $stu['year_level'] . " " . $stu['section_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label>Violation</label>
                    <select name="violation_id" id="violationSelect" required>
                        <option value="">Select Violation</option>
                        <?php foreach ($violations as $vio): ?>
                            <option value="<?= $vio['id'] ?>"><?= htmlspecialchars($vio['violation']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Load jQuery + Select2 (via CDN) -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script>
            $(document).ready(function() {
                $('#studentSelect').select2({ placeholder: "Select a student", allowClear: true, width: '100%' });
                $('#violationSelect').select2({ placeholder: "Select a violation", allowClear: true, width: '100%' });
            });
            </script>

            <label>Description (optional)</label>
            <textarea name="description" placeholder="Add Details..."></textarea>

            <label>Location</label>
            <input type="text" name="location" required placeholder="Enter location">

            <button type="submit" name="add_violation" class="btn btn-primary">Submit Violation</button>
        </form>
    </div>

    <!-- Right: Filters -->
    <div class="container">
        <h3>(School Year: <?= htmlspecialchars($current_sy['school_year']); ?>)</h3>
        <h4>Search & Filter Violations</h4>
        <div class="form-box filter-grid">
            <input type="text" id="violationSearch" placeholder="Search student or violations...">

            <select id="filterProgram">
                <option value="">All Programs</option>
                <?php foreach (array_unique(array_column($studentViolations, 'program_code')) as $program): ?>
                    <option value="<?= htmlspecialchars($program) ?>"><?= htmlspecialchars($program) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterYear">
                <option value="">All Year Levels</option>
                <?php foreach (array_unique(array_column($studentViolations, 'year_level')) as $year): ?>
                    <option value="<?= htmlspecialchars($year) ?>"><?= htmlspecialchars($year) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterSection">
                <option value="">All Sections</option>
                <?php foreach (array_unique(array_column($studentViolations, 'section_name')) as $sec): ?>
                    <option value="<?= htmlspecialchars($sec) ?>"><?= htmlspecialchars($sec) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterViolation">
                <option value="">All Violations</option>
                <?php foreach (array_unique(array_column($studentViolations, 'violation')) as $vio): ?>
                    <option value="<?= htmlspecialchars($vio) ?>"><?= htmlspecialchars($vio) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterLocation">
                <option value="">All Locations</option>
                <?php foreach (array_unique(array_column($studentViolations, 'location')) as $loc): ?>
                    <option value="<?= htmlspecialchars($loc) ?>"><?= htmlspecialchars($loc) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterStatus">
                <option value="">All Status</option>
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
    <h4>My Recorded Violations (Total: <?= $totalViolations ?>)</h4>

    <table class="styled-table" id="violationTable">
        <thead>
            <tr>
                <th>Student</th>
                <th>Program</th>
                <th>Year</th>
                <th>Section</th>
                <th>Violation</th>
                <th>Description</th>
                <th>Location</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($totalViolations > 0): ?>
                <?php foreach ($studentViolations as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['last_name'] . ", " . $v['first_name']); ?></td>
                        <td><?= htmlspecialchars($v['program_code']); ?></td>
                        <td><?= htmlspecialchars($v['year_level']); ?></td>
                        <td><?= htmlspecialchars($v['section_name']); ?></td>
                        <td><?= htmlspecialchars($v['violation']); ?></td>
                        <td><?= htmlspecialchars($v['description']); ?></td>
                        <td><?= htmlspecialchars($v['location']); ?></td>
                        <td><?= htmlspecialchars($v['date_time']); ?></td>
                        <td><strong><?= htmlspecialchars($v['status']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" style="text-align:center;">No violations recorded yet.</td></tr>
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
        // Skip the "no record" row if it exists
        if (row.classList.contains("no-record")) return;

        let text = row.textContent.toLowerCase();
        let rowProgram = row.cells[1].textContent.toLowerCase();
        let rowYear = row.cells[2].textContent.toLowerCase();
        let rowSection = row.cells[3].textContent.toLowerCase();
        let rowViolation = row.cells[4].textContent.toLowerCase();
        let rowLocation = row.cells[6].textContent.toLowerCase();
        let rowStatus = row.cells[8].textContent.toLowerCase();

        let matches = text.includes(search) &&
                      (program === "" || rowProgram === program) &&
                      (year === "" || rowYear === year) &&
                      (section === "" || rowSection === section) &&
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

    // If nothing matched, add "no record found"
    if (matchCount === 0) {
        let tr = document.createElement("tr");
        tr.classList.add("no-record");
        let td = document.createElement("td");
        td.colSpan = 9; // match number of columns in your table
        td.style.textAlign = "center";
        td.textContent = "No record found";
        tr.appendChild(td);
        tbody.appendChild(tr);
    }
}


// CANCEL FILTERS → refresh page
function cancelFilters() {
    location.reload();
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
    background: #27ae60;e;
}
.btn:hover { opacity: 0.9; }
.btn-secondary { background: #6c757d; color: white; }
.btn-secondary:hover { opacity: 0.9; }

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
