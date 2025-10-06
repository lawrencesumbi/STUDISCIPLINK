<?php
// admin_pages/dashboard.php

// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root"; // change if needed
$pass = "";

$programData = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get total number of users
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalUsers = $result['total_users'];

    // Get pending users
    $stmt = $pdo->query("SELECT COUNT(*) as pending_users FROM users WHERE status = 'pending'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pendingUsers = $result['pending_users'];

    // Get active users
    $stmt = $pdo->query("SELECT COUNT(*) as active_users FROM users WHERE status = 'active'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $activeUsers = $result['active_users'];

    // Get the current school year
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

    // Total students for selected school year
    if ($current_sy_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE school_year_id = ?");
        $stmt->execute([$current_sy_id]);
        $total_students = $stmt->fetchColumn();
    } else {
        $total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }

    // Total programs
    $total_programs = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();

} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
    $totalUsers = 0;
    $pendingUsers = 0;
    $activeUsers = 0;
    $current_school_year = "None";
    $total_students = 0;
    $total_programs = 0;
}

try {
    if ($current_sy_id) {
        // ✅ Always show all programs, even if 0 students in current SY
        $stmt = $pdo->prepare("
            SELECT 
                p.program_code, 
                COUNT(s.id) AS student_count
            FROM programs p
            LEFT JOIN students s 
                ON s.program_id = p.id 
                AND s.school_year_id = :school_year_id
            GROUP BY p.id, p.program_code
            ORDER BY student_count DESC
        ");
        $stmt->execute(['school_year_id' => $current_sy_id]);
    } else {
        // Fallback: show all programs (any school year)
        $stmt = $pdo->query("
            SELECT 
                p.program_code, 
                COUNT(s.id) AS student_count
            FROM programs p
            LEFT JOIN students s 
                ON s.program_id = p.id
            GROUP BY p.id, p.program_code
            ORDER BY student_count DESC
        ");
    }

    $programData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error fetching program data: " . $e->getMessage() . "</p>";
}
?>

<!-- User Stats -->
<div class="stats-container">
    <div class="stat-box box-red">
        <h3>Total Users</h3>
        <p class="stat-value"><?= $totalUsers ?></p>
    </div>

    <div class="stat-box box-orange">
        <h3>Pending Users</h3>
        <p class="stat-value"><?= $pendingUsers ?></p>
    </div>

    <div class="stat-box box-green">
        <h3>Active Users</h3>
        <p class="stat-value"><?= $activeUsers ?></p>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="stats-container">
    <div class="stat-box box-red-dark">
        <h3>Current School Year</h3>
        <p class="stat-value"><?= htmlspecialchars($current_school_year) ?></p>
    </div>

    <div class="stat-box box-red">
        <h3>Total Students</h3>
        <p class="stat-value"><?= $total_students ?></p>
    </div>

    <div class="stat-box box-red-light">
        <h3>Total Programs</h3>
        <p class="stat-value"><?= $total_programs ?></p>
    </div>
</div>

<!-- Charts Container: Side by Side -->
<div style="display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap;">

    <!-- Bar Chart: Number of Students per Program -->
    <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom: 20px; color:#333;">Number of Students per Program</h3>
        <div style="height:215px;">
            <canvas id="programChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart: Active vs Pending Users -->
    <div style="flex: 1; min-width: 300px; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom: 20px; color:#333;">User Status Percentage</h3>
        <div style="height:215px;">
            <canvas id="userStatusPieChart"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart: Students per Program
    const ctxProgram = document.getElementById('programChart').getContext('2d');
    const programNames = <?= json_encode(array_column($programData, 'program_code')) ?>;
    const studentCounts = <?= json_encode(array_column($programData, 'student_count')) ?>;

    new Chart(ctxProgram, {
        type: 'bar',
        data: {
            labels: programNames,
            datasets: [{
                label: 'Number of Students',
                data: studentCounts,
                backgroundColor: '#c41e1e',
                borderRadius: 8,
                hoverBackgroundColor: '#e74c3c',
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
                x: {
                    title: { display: true, text: 'Programs', color: '#555', font: { weight: 'bold' } },
                    ticks: { color: '#333' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Students', color: '#555', font: { weight: 'bold' } },
                    ticks: { stepSize: 1, color: '#333' }
                }
            }
        }
    });

    // Pie Chart: Active vs Pending Users
    const ctxUserPie = document.getElementById('userStatusPieChart').getContext('2d');
    const userStatusData = [<?= $activeUsers ?>, <?= $pendingUsers ?>];
    const userStatusLabels = ['Active Users', 'Pending Users'];
    const userColors = ['#27ae60', '#e67e22'];

    new Chart(ctxUserPie, {
        type: 'pie',
        data: {
            labels: userStatusLabels,
            datasets: [{
                data: userStatusData,
                backgroundColor: userColors,
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#333', font: { size: 14 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percent = total ? ((value / total) * 100).toFixed(1) : 0;
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
    min-width: 200px;
    min-height: 100px; /* ✅ same height */
    padding: 20px;
    color: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);

    /* ✅ Center everything */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    text-align: center;
}

.stat-box h3 {
    margin: 0 0 10px;
    font-size: 18px;
    font-weight: 600;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    margin: 0;
}

/* Colors */
.box-red { background: #c41e1e; }
.box-orange { background: #e67e22; }
.box-green { background: #27ae60; }

.box-red-dark { background: #8d2525; }
.box-red-light { background: #ff5555ff; }
</style>

