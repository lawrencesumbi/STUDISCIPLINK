<?php
require __DIR__ . '/../db_connect.php'; // include your database connection

// Get logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in.</p>";
    exit;
}
$user_id = $_SESSION['user_id'];

// Get the current school year from DB
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

// Total Classes for this faculty in current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM class_enrollments WHERE user_id = ? AND school_year_id = ?");
    $stmt->execute([$user_id, $current_sy_id]);
    $total_classes = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM class_enrollments WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_classes = $stmt->fetchColumn();
}

// Total Students under this faculty’s classes in current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT se.student_id)
        FROM student_enrollments se
        JOIN class_enrollments ce ON se.class_enrollment_id = ce.id
        WHERE ce.user_id = ? AND ce.school_year_id = ?
    ");
    $stmt->execute([$user_id, $current_sy_id]);
    $total_students = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT se.student_id)
        FROM student_enrollments se
        JOIN class_enrollments ce ON se.class_enrollment_id = ce.id
        WHERE ce.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $total_students = $stmt->fetchColumn();
}

// ✅ Total Violations recorded by this faculty in current school year
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM student_violations sv
        JOIN students st ON sv.student_id = st.id
        WHERE sv.user_id = ? AND st.school_year_id = ?
    ");
    $stmt->execute([$user_id, $current_sy_id]);
    $total_violations = $stmt->fetchColumn();
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM student_violations WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_violations = $stmt->fetchColumn();
}

/// ✅ Fetch per-class students and violations for the chart
if ($current_sy_id) {
    $stmt = $pdo->prepare("
        SELECT 
            CONCAT(p.program_code, yl.year_code, s.section_name) AS class_name,
            COUNT(DISTINCT se.student_id) AS total_students,
            COUNT(DISTINCT sv.id) AS total_violations
        FROM class_enrollments ce
        JOIN programs p ON ce.program_id = p.id
        JOIN year_levels yl ON ce.year_level_id = yl.id
        JOIN sections s ON ce.section_id = s.id
        LEFT JOIN student_enrollments se ON ce.id = se.class_enrollment_id
        LEFT JOIN students st ON se.student_id = st.id
        LEFT JOIN student_violations sv ON sv.student_id = st.id
        WHERE ce.user_id = ? AND ce.school_year_id = ?
        GROUP BY class_name
        ORDER BY class_name
    ");
    $stmt->execute([$user_id, $current_sy_id]);
} else {
    $stmt = $pdo->prepare("
        SELECT 
            CONCAT(p.program_code, yl.year_code, s.section_name) AS class_name,
            COUNT(DISTINCT se.student_id) AS total_students,
            COUNT(DISTINCT sv.id) AS total_violations
        FROM class_enrollments ce
        JOIN programs p ON ce.program_id = p.id
        JOIN year_levels yl ON ce.year_level_id = yl.id
        JOIN sections s ON ce.section_id = s.id
        LEFT JOIN student_enrollments se ON ce.id = se.class_enrollment_id
        LEFT JOIN students st ON se.student_id = st.id
        LEFT JOIN student_violations sv ON sv.student_id = st.id
        WHERE ce.user_id = ?
        GROUP BY class_name
        ORDER BY class_name
    ");
    $stmt->execute([$user_id]);
}

$chartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Dashboard Stats -->
<div class="stats-container">
    <div class="stat-box box-red">
        <h4>Current School Year</h4>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <div class="stat-box box-dark-red">
        <h4>Total Classes</h4>
        <p class="stat-value"><?= $total_classes ?></p>
    </div>

    <div class="stat-box box-light-red">
        <h4>Total Students</h4>
        <p class="stat-value"><?= $total_students ?></p>
    </div>

    <div class="stat-box box-maroon">
        <h4>Total Violations</h4>
        <p class="stat-value"><?= $total_violations ?></p>
    </div>
</div>

<!-- 📊 Charts Row -->
<div class="charts-row">
    <!-- 📋 Grouped Bar Chart -->
    <div class="chart-box left-chart">
        <h3>Students and Violations per Class</h3>
        <div class="chart-wrapper">
            <canvas id="classChart"></canvas>
        </div>
    </div>

    <!-- 🥧 Pie Chart -->
    <div class="chart-box right-chart">
        <h3>Violation Percentage per Class</h3>
        <div class="chart-wrapper">
            <canvas id="violationPieChart"></canvas>
        </div>
    </div>
</div>



<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('classChart').getContext('2d');
    const classNames = <?= json_encode(array_column($chartData, 'class_name')) ?>;
    const studentCounts = <?= json_encode(array_column($chartData, 'total_students')) ?>;
    const violationCounts = <?= json_encode(array_column($chartData, 'total_violations')) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: classNames,
            datasets: [
                {
                    label: 'Students',
                    data: studentCounts,
                    backgroundColor: '#27ae60',
                    borderRadius: 8
                },
                {
                    label: 'Violations',
                    data: violationCounts,
                    backgroundColor: '#c41e1e',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { color: '#333', font: { weight: 'bold' } }
                },
                tooltip: {
                    backgroundColor: '#333',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 10
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Class', color: '#555', font: { weight: 'bold' } },
                    ticks: { color: '#333' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Count', color: '#555', font: { weight: 'bold' } },
                    ticks: { stepSize: 1, color: '#333' }
                }
            }
        }
    });
</script>


<script>
    const pieCtx = document.getElementById('violationPieChart').getContext('2d');
    const pieLabels = <?= json_encode(array_column($chartData, 'class_name')) ?>;
    const pieData = <?= json_encode(array_column($chartData, 'total_violations')) ?>;

    // ✅ Generate distinct colors for each slice
    const pieColors = pieLabels.map((_, i) => {
        const hue = (i * 60) % 360; // spread colors evenly
        return `hsl(${hue}, 70%, 50%)`;
    });

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: pieColors,
                borderWidth: 1,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        color: '#333',
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.chart._metasets[context.datasetIndex].total;
                            const value = context.raw;
                            const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} violations (${percentage}%)`;
                        }
                    }
                },
                title: {
                    display: false
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
    padding: 20px;
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
.box-light-red { background: #8d2525ff; }
.box-maroon { background: #5c0a0a; }

.charts-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-top: 10px;
    flex-wrap: nowrap; /* stay side-by-side */
}

.chart-box {
    width: 50%;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    box-sizing: border-box;
}

.chart-box h3 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* prevent charts from stretching */
.chart-wrapper {
    width: 100%;
    height: 350px;
}

.chart-wrapper canvas {
    width: 100% !important;
    height: 100% !important;
}


</style>
