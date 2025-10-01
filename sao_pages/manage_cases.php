<?php
require __DIR__ . '/../db_connect.php';

// Ensure SAO user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in as Student Affairs Officer.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Messages
$message = "";

// ✅ Get the current school year
$current_sy_row = $pdo->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if ($current_sy_row) {
    $current_sy_id = $current_sy_row['id'];
    $current_school_year = $current_sy_row['school_year'];
} else {
    // fallback to latest school year if none selected
    $current_sy_row = $pdo->query("SELECT * FROM school_years ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $current_sy_id = $current_sy_row ? $current_sy_row['id'] : null;
    $current_school_year = $current_sy_row ? $current_sy_row['school_year'] : "None";
}

// ✅ Mark record as Resolved
if (isset($_POST['resolve_case'])) {
    $record_id = $_POST['record_id'];

    // Update status in record_violations
    $stmt = $pdo->prepare("UPDATE record_violations SET status='Resolved' WHERE id=?");
    $stmt->execute([$record_id]);

    // Insert into resolved_cases (now includes school_year_id)
    $stmt = $pdo->prepare("
        INSERT INTO resolved_cases (record_violation_id, status, date_resolved, school_year_id) 
        VALUES (?, 'Resolved', NOW(), ?)
    ");
    $stmt->execute([$record_id, $current_sy_id]);

    $message = "<p class='success-msg'>Case ID $record_id marked as Resolved.</p>";
}

// ✅ Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// ✅ Fetch Ongoing Records (filtered by school year + search)
if ($search) {
    $ongoing_stmt = $pdo->prepare("
        SELECT rv.*, st.first_name, st.last_name, v.violation AS violation
        FROM record_violations rv
        JOIN student_violations sv ON rv.student_violations_id = sv.id
        JOIN students st ON sv.student_id = st.id
        JOIN violations v ON sv.violation_id = v.id
        WHERE rv.status='Ongoing' AND rv.school_year_id = ?
        AND (
            CONCAT(st.first_name, ' ', st.last_name) LIKE ? 
            OR v.violation LIKE ? 
            OR rv.action_taken LIKE ? 
            OR rv.status LIKE ?
        )
        ORDER BY rv.date_recorded DESC
    ");
    $ongoing_stmt->execute([$current_sy_id, "%$search%", "%$search%", "%$search%", "%$search%"]);
} else {
    $ongoing_stmt = $pdo->prepare("
        SELECT rv.*, st.first_name, st.last_name, v.violation AS violation
        FROM record_violations rv
        JOIN student_violations sv ON rv.student_violations_id = sv.id
        JOIN students st ON sv.student_id = st.id
        JOIN violations v ON sv.violation_id = v.id
        WHERE rv.status='Ongoing' AND rv.school_year_id = ?
        ORDER BY rv.date_recorded DESC
    ");
    $ongoing_stmt->execute([$current_sy_id]);
}
$ongoing = $ongoing_stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch Resolved Records (filtered by school year + search)
if ($search) {
    $resolved_stmt = $pdo->prepare("
        SELECT rc.*, rv.action_taken, rv.remarks, st.first_name, st.last_name, v.violation AS violation
        FROM resolved_cases rc
        JOIN record_violations rv ON rc.record_violation_id = rv.id
        JOIN student_violations sv ON rv.student_violations_id = sv.id
        JOIN students st ON sv.student_id = st.id
        JOIN violations v ON sv.violation_id = v.id
        WHERE rv.school_year_id = ?
        AND (
            CONCAT(st.first_name, ' ', st.last_name) LIKE ? 
            OR v.violation LIKE ? 
            OR rv.action_taken LIKE ? 
            OR rc.status LIKE ?
        )
        ORDER BY rc.date_resolved DESC
    ");
    $resolved_stmt->execute([$current_sy_id, "%$search%", "%$search%", "%$search%", "%$search%"]);
} else {
    $resolved_stmt = $pdo->prepare("
        SELECT rc.*, rv.action_taken, rv.remarks, st.first_name, st.last_name, v.violation AS violation
        FROM resolved_cases rc
        JOIN record_violations rv ON rc.record_violation_id = rv.id
        JOIN student_violations sv ON rv.student_violations_id = sv.id
        JOIN students st ON sv.student_id = st.id
        JOIN violations v ON sv.violation_id = v.id
        WHERE rv.school_year_id = ?
        ORDER BY rc.date_resolved DESC
    ");
    $resolved_stmt->execute([$current_sy_id]);
}
$resolved = $resolved_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container small-container">
    <h3>Current School Year: 
            <span style="color:#b30000;"><?= htmlspecialchars($current_school_year) ?></span>
    </h3>
</div>

<!-- ✅ Search Form -->

    <form method="GET" class="search-form">
        <input type="hidden" name="page" value="manage_cases">
        <input type="text" name="search" placeholder="Search by student, violation, action taken, or status" value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <?php if ($search): ?>
            <a href="?page=manage_cases">Clear</a>
        <?php endif; ?>
    </form>


<div class="container">
    <?= $message; ?>

    <!-- Ongoing Cases -->
    <h3>Ongoing Cases</h3>
    <table class="styled-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Student</th>
                <th>Violation</th>
                <th>Action Taken</th>
                <th>Remarks</th>
                <th>Date Recorded</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($ongoing): ?>
                <?php foreach ($ongoing as $index => $o): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($o['first_name'] . " " . $o['last_name']); ?></td>
                        <td><?= htmlspecialchars($o['violation']); ?></td>
                        <td><?= htmlspecialchars($o['action_taken']); ?></td>
                        <td><?= htmlspecialchars($o['remarks']); ?></td>
                        <td><?= $o['date_recorded']; ?></td>
                        <td><span style="color:orange;font-weight:bold;"><?= $o['status']; ?></span></td>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="record_id" value="<?= $o['id']; ?>">
                                <button type="submit" name="resolve_case" class="btn btn-success"
                                    onclick="return confirm('Mark this case as Resolved?')">
                                    Mark as Resolved
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No ongoing cases.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="container">
    <!-- Resolved Cases -->
    <h3>Resolved Cases</h3>
    <table class="styled-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Student</th>
                <th>Violation</th>
                <th>Action Taken</th>
                <th>Remarks</th>
                <th>Date Resolved</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resolved): ?>
                <?php foreach ($resolved as $index => $r): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($r['first_name'] . " " . $r['last_name']); ?></td>
                        <td><?= htmlspecialchars($r['violation']); ?></td>
                        <td><?= htmlspecialchars($r['action_taken']); ?></td>
                        <td><?= htmlspecialchars($r['remarks']); ?></td>
                        <td><?= $r['date_resolved']; ?></td>
                        <td><span style="color:green;font-weight:bold;"><?= $r['status']; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">No resolved cases yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<style>
.small-container {
    padding: 8px 15px;   
    flex: 1;          /* ✅ same flex behavior as .container */
    display: block;   /* ✅ not inline-block */
    max-width: 100%; 
}
.small-container h3 {
    font-size: 16px;  
    margin: 0;
}
.container { background:#fff; padding:20px; border-radius:10px; margin-top:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
.success-msg { color:green; font-weight:bold; margin-bottom:10px; }
.styled-table { width:100%; border-collapse:collapse; margin-top:10px; }
.styled-table th, .styled-table td { border:1px solid #ddd; padding:10px; }
.styled-table th { background:#c41e1e; color:#fff; }
.btn { padding:6px 12px; border:none; border-radius:5px; cursor:pointer; }
.btn-success { background:green; color:white; }
.inline-form { display:inline; }

/* Search form */
.search-form {
    margin-top: 15px;
    margin-bottom: 15px;
    display: flex;
    gap: 10px;
}
.search-form input[type="text"] {
    flex: 1;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
.search-form button {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    background: #c41e1e;
    color: white;
    cursor: pointer;
}
.search-form a {
    padding: 8px 12px;
    border-radius: 5px;
    background: #555;
    color: white;
    text-decoration: none;
}
</style>
