<?php
require __DIR__ . '/../db_connect.php';


// Get logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in.</p>";
    exit;
}

// ✅ Get the current school year
$current_sy_row = $pdo->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($current_sy_row) {
    $current_sy_id = $current_sy_row['id'];
    $current_school_year = $current_sy_row['school_year'];
} else {
    $current_sy_row = $pdo->query("SELECT * FROM school_years ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $current_sy_id = $current_sy_row ? $current_sy_row['id'] : null;
    $current_school_year = $current_sy_row ? $current_sy_row['school_year'] : "None";
}

// ✅ Pending Violations
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM student_violations
        WHERE status = 'pending' AND school_year_id = ?
    ");
    $stmt->execute([$current_sy_id]);
    $pending_violations = $stmt->fetchColumn();
} else {
    $pending_violations = 0;
}

// ✅ Recorded Violations
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM student_violations
        WHERE status IN ('recorded','resolved') AND school_year_id = ?
    ");
    $stmt->execute([$current_sy_id]);
    $recorded_violations = $stmt->fetchColumn();
} else {
    $recorded_violations = 0;
}

// ✅ Fetch all violations with counts for current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT v.violation, COUNT(sv.id) AS violation_count
        FROM violations v
        LEFT JOIN student_violations sv 
            ON sv.violation_id = v.id AND sv.school_year_id = :sy
        GROUP BY v.id, v.violation
        ORDER BY v.id ASC
    ");
    $stmt->execute(['sy' => $current_sy_id]);
} else {
    $stmt = $pdo->query("
        SELECT v.violation, COUNT(sv.id) AS violation_count
        FROM violations v
        LEFT JOIN student_violations sv 
            ON sv.violation_id = v.id
        GROUP BY v.id, v.violation
        ORDER BY v.id ASC
    ");
}

$violationTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Dashboard Stats -->
<div class="stats-container">
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <div class="stat-box box-dark-red">
        <h4>Pending Violations</h4>
        <p class="stat-value"><?= $pending_violations ?></p>
    </div>

    <div class="stat-box box-maroon">
        <h4>Recorded Violations</h4>
        <p class="stat-value"><?= $recorded_violations ?></p>
    </div>
</div>

<!-- Charts Container -->
<div style="display: flex; gap: 20px; margin-top: 40px; flex-wrap: wrap;">
    <!-- Bar Chart: All Violations -->
    <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom: 20px; color:#333;">Most Committed Violations</h3>
        <div style="height:300px;">
            <canvas id="violationsBarChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart: Pending vs Recorded Violations -->
    <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom: 20px; color:#333;">Violation Status Percentage</h3>
        <div style="height:300px;">
            <canvas id="violationPieChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart: All Violations
    const barCtx = document.getElementById('violationsBarChart').getContext('2d');
    const barLabels = <?= json_encode(array_column($violationTypes, 'violation')) ?>;
    const barData = <?= json_encode(array_column($violationTypes, 'violation_count')) ?>;

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'Number of Students',
                data: barData,
                backgroundColor: '#c41e1e',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#333',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 10
                }
            },
            scales: {
                x: { ticks: { color: '#333' } },
                y: { beginAtZero: true, ticks: { stepSize: 1, color: '#333' } }
            }
        }
    });

    // Pie Chart: Pending vs Recorded Violations
    const pieCtx = document.getElementById('violationPieChart').getContext('2d');
    const pieData = [<?= $pending_violations ?>, <?= $recorded_violations ?>];
    const pieLabels = ['Pending Violations', 'Recorded Violations'];
    const pieColors = ['#e67e22', '#27ae60'];

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: pieColors,
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { color: '#333', font: { size: 14 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a,b)=>a+b,0);
                            const value = context.raw;
                            const percent = total ? ((value/total)*100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
</script>

<style>
.stats-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}
.stat-box {
    flex: 1;
    padding: 35px;
    color: white;
    border-radius: 8px;
    min-width: 200px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.stat-value {
    font-size: 28px;
    font-weight: bold;
    margin: 0;
}
/* Colors */
.box-red { background: #ff0000ff; }
.box-dark-red { background: #c41616ff; }
.box-maroon { background: #5c0a0a; }
</style>
