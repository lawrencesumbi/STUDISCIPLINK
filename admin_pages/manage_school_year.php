<?php
require __DIR__ . '/../db_connect.php';

$message = "";

// Get current user ID (default to null if not logged in)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Variables for edit mode
$edit_mode = false;
$edit_id = null;
$edit_school_year = "";

// Handle add new school year
if (isset($_POST['add_school_year'])) {
    $school_year = trim($_POST['school_year']);
    $stmt = $pdo->prepare("INSERT INTO school_years (school_year, is_current) VALUES (?, 0)");
    $stmt->execute([$school_year]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Added new school year: $school_year"]);
    }

    $message = "<p class='success-msg'>School Year added successfully!</p>";
}

// Handle update school year
if (isset($_POST['update_school_year'])) {
    $id = $_POST['id'];
    $school_year = trim($_POST['school_year']);
    $stmt = $pdo->prepare("UPDATE school_years SET school_year=? WHERE id=?");
    $stmt->execute([$school_year, $id]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Updated school year (ID: $id) to: $school_year"]);
    }

    $message = "<p class='success-msg'>School Year updated successfully!</p>";
}

// Handle delete
if (isset($_POST['delete_school_year'])) {
    $id = $_POST['id'];

    // Fetch deleted SY name before deletion for log
    $getSY = $pdo->prepare("SELECT school_year FROM school_years WHERE id=?");
    $getSY->execute([$id]);
    $deleted_sy = $getSY->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM school_years WHERE id=?");
    $stmt->execute([$id]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Deleted school year: $deleted_sy"]);
    }

    $message = "<p class='error-msg'>School Year deleted successfully!</p>";
}

// Handle set current school year (persistent)
if (isset($_POST['select_sy'])) {
    $id = $_POST['id'];

    // Fetch selected SY for log
    $getSY = $pdo->prepare("SELECT school_year FROM school_years WHERE id=?");
    $getSY->execute([$id]);
    $selected_sy = $getSY->fetchColumn();

    $pdo->query("UPDATE school_years SET is_current = 0");
    $stmt = $pdo->prepare("UPDATE school_years SET is_current = 1 WHERE id=?");
    $stmt->execute([$id]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Set current school year to: $selected_sy"]);
    }

    $message = "<p class='success-msg'>School Year set as current successfully!</p>";
}

// Handle edit (populate form)
if (isset($_POST['edit_school_year'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM school_years WHERE id=? LIMIT 1");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_school_year = $row['school_year'];
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

    <!-- Add / Update Form -->
    <form method="POST" class="form-box">
        <input type="text" name="school_year" placeholder="Enter School Year" value="<?= htmlspecialchars($edit_school_year); ?>" required>
        
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_school_year" class="btn btn-warning">Update School Year</button>
            <a href="" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_school_year" class="btn btn-primary">Add School Year</button>
        <?php endif; ?>
    </form>

    <!-- School Year Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($school_years as $sy): ?>
                <tr>
                    <td><?= $sy['id']; ?></td>
                    <td><?= htmlspecialchars($sy['school_year']); ?></td>
                    <td>
                        <!-- Edit -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $sy['id']; ?>">
                            <button type="submit" name="edit_school_year" class="btn btn-warning">Edit</button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $sy['id']; ?>">
                            <button type="submit" name="delete_school_year" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                        <!-- Select for recording -->
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
.error-msg { color: red; font-weight: bold; margin-bottom: 10px; }

/* Form */
.form-box {
    margin-bottom: 20px;
}
.form-box input[type="text"] {
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-right: 10px;
}

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
.btn-primary { background: #fc6464ff; }
.btn-warning { background: #ffb547ff; color: black; }
.btn-danger { background: #dc3545; }
.btn-success { background: #28a745; }
.btn-secondary { background: gray; text-decoration: none; }

.btn:hover { opacity: 0.9; }

/* Selected label */
.selected-label { color: green; font-weight: bold; }
</style>
