<?php
require __DIR__ . '/../db_connect.php';


$message = "";

// Handle set current school year (persistent)
if (isset($_POST['select_sy'])) {
    $id = $_POST['id'];

    // Get the school year name for logging
    $stmt = $pdo->prepare("SELECT school_year FROM school_years WHERE id = ?");
    $stmt->execute([$id]);
    $selected_sy = $stmt->fetchColumn();

    // Update current school year
    $pdo->query("UPDATE school_years SET is_current = 0");
    $stmt = $pdo->prepare("UPDATE school_years SET is_current = 1 WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<p class='success-msg'>School Year set as current successfully!</p>";

    // ✅ Log action
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $action = "Set School Year '$selected_sy' as current";
        $log_stmt = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log_stmt->execute([$user_id, $action]);
    }
}

// Fetch all school years
$school_years = $pdo->query("SELECT * FROM school_years ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Get the current school year
$current_sy_row = $pdo->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$current_sy = $current_sy_row ? $current_sy_row['school_year'] : "None";
$current_sy_id = $current_sy_row ? $current_sy_row['id'] : null;
?>

<div class="container">
    <?= $message; ?>

    <!-- School Year Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Year</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($school_years as $sy): ?>
                <tr>
                    <td><?= $sy['id']; ?></td>
                    <td><?= htmlspecialchars($sy['school_year']); ?></td>
                    <td>
                        <?php if ($current_sy_id != $sy['id']): ?>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?= $sy['id']; ?>">
                                <button type="submit" name="select_sy" class="btn btn-success">Select</button>
                            </form>
                        <?php else: ?>
                            <span class="selected-label">Selected School Year</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Container */
.container {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Messages */
.success-msg { color: green; font-weight: bold; margin-bottom: 10px; }

/* Table */
.table-box {
    max-height: 400px;
    overflow-y: auto;
}
.styled-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.styled-table thead {
    background: #c41e1e;
    color: white;
}
.styled-table th, .styled-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}
.styled-table tr:nth-child(even) {
    background: #f9f9f9;
}

/* Inline forms inside table */
.inline-form {
    display: inline-block;
    margin: 2px;
}

/* Buttons */
.btn {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    color: white;
}
.btn-success { background: #28a745; }
.btn:hover { opacity: 0.9; }

/* Selected label */
.selected-label { color: green; font-weight: bold; }
</style>
