<?php
require __DIR__ . '/../db_connect.php'; // include your database connection

// Get logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in as Student Affairs Officer.</p>";
    exit;
}
$user_id = $_SESSION['user_id'];

// ✅ Get current school year
$current_sy_row = $pdo->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($current_sy_row) {
    $current_sy_id = $current_sy_row['id'];
    $current_school_year = $current_sy_row['school_year'];
} else {
    $current_sy_row = $pdo->query("SELECT * FROM school_years ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $current_sy_id = $current_sy_row ? $current_sy_row['id'] : null;
    $current_school_year = $current_sy_row ? $current_sy_row['school_year'] : "None";
}

// ✅ Total Ongoing Cases
if ($current_sy_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM record_violations WHERE status = 'Ongoing' AND school_year_id = ?");
    $stmt->execute([$current_sy_id]);
    $ongoing_cases = $stmt->fetchColumn();
} else {
    $ongoing_cases = 0;
}

// ✅ Total Resolved Cases
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM resolved_cases rc
        JOIN record_violations rv ON rc.record_violation_id = rv.id
        WHERE rv.school_year_id = ?
    ");
    $stmt->execute([$current_sy_id]);
    $resolved_cases = $stmt->fetchColumn();
} else {
    $resolved_cases = 0;
}

// ✅ Fetch violations per program
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT p.program_code, COUNT(sv.id) AS violation_count
        FROM programs p
        LEFT JOIN students st ON st.program_id = p.id AND st.school_year_id = :sy
        LEFT JOIN student_violations sv ON sv.student_id = st.id
        GROUP BY p.id, p.program_code
        ORDER BY violation_count DESC
    ");
    $stmt->execute(['sy' => $current_sy_id]);
} else {
    $stmt = $pdo->query("
        SELECT p.program_code, COUNT(sv.id) AS violation_count
        FROM programs p
        LEFT JOIN students st ON st.program_id = p.id
        LEFT JOIN student_violations sv ON sv.student_id = st.id
        GROUP BY p.id, p.program_code
        ORDER BY violation_count DESC
    ");
}
$programViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Dashboard Stats -->
<div class="stats-container">
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <div class="stat-box box-dark-red">
        <h4>Ongoing Cases</h4>
        <p class="stat-value"><?= $ongoing_cases ?></p>
    </div>

    <div class="stat-box box-maroon">
        <h4>Resolved Cases</h4>
        <p class="stat-value"><?= $resolved_cases ?></p>
    </div>
</div>

<!-- Charts: Side by Side -->
<div style="display: flex; gap: 20px; margin-top: 40px; flex-wrap: wrap;">

    <!-- Bar Chart: Violations per Program -->
    <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom: 20px; color:#333;">Violations per Program</h3>
        <div style="height:300px;">
            <canvas id="violationsBarChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart: Ongoing vs Resolved Cases -->
    <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom: 20px; color:#333;">Case Status Percentage</h3>
        <div style="height:300px;">
            <canvas id="casesPieChart"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart: Violations per Program
    const barCtx = document.getElementById('violationsBarChart').getContext('2d');
    const barLabels = <?= json_encode(array_column($programViolations, 'program_code')) ?>;
    const barData = <?= json_encode(array_column($programViolations, 'violation_count')) ?>;

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'Violations',
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
                tooltip: { backgroundColor: '#333', titleFont: { size:14, weight:'bold' }, bodyFont: { size:13 }, padding:10 }
            },
            scales: { x:{ ticks:{ color:'#333' } }, y:{ beginAtZero:true, ticks:{ stepSize:1, color:'#333' } } }
        }
    });

    // Pie Chart: Ongoing vs Resolved Cases
    const pieCtx = document.getElementById('casesPieChart').getContext('2d');
    const pieData = [<?= $ongoing_cases ?>, <?= $resolved_cases ?>];
    const pieLabels = ['Ongoing Cases', 'Resolved Cases'];
    const pieColors = ['#e67e22', '#27ae60'];

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{ data: pieData, backgroundColor: pieColors, borderColor:'#fff', borderWidth:1 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position:'right', labels:{ color:'#333', font:{ size:14 } } },
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
