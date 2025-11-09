<?php
require __DIR__ . '/../db_connect.php'; // include DB connection

// Messages
$message = "";

// Get current user ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Variables to control edit mode
$edit_mode = false;
$edit_id = null;
$edit_year_level = "";
$edit_year_code = "";

// Handle add new year level
if (isset($_POST['add_year_level'])) {
    $year_level = trim($_POST['year_level']);
    $year_code = trim($_POST['year_code']);
    $stmt = $pdo->prepare("INSERT INTO year_levels (year_level, year_code) VALUES (?, ?)");
    $stmt->execute([$year_level, $year_code]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Added new year level: $year_level ($year_code)"]);
    }

    $message = "<p class='success-msg'>Year Level added successfully!</p>";
}

// Handle update year level
if (isset($_POST['update_year_level'])) {
    $id = $_POST['id'];
    $year_level = trim($_POST['year_level']);
    $year_code = trim($_POST['year_code']);
    $stmt = $pdo->prepare("UPDATE year_levels SET year_level=?, year_code=? WHERE id=?");
    $stmt->execute([$year_level, $year_code, $id]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Updated year level (ID: $id) to: $year_level ($year_code)"]);
    }

    $message = "<p class='success-msg'>Year Level updated successfully!</p>";
}

// Handle delete
if (isset($_POST['delete_year_level'])) {
    $id = $_POST['id'];

    // Get year level details before deleting (for logging)
    $getYear = $pdo->prepare("SELECT year_level, year_code FROM year_levels WHERE id=?");
    $getYear->execute([$id]);
    $deleted = $getYear->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("DELETE FROM year_levels WHERE id=?");
    $stmt->execute([$id]);

    // Log action
    if ($user_id && $deleted) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Deleted year level: {$deleted['year_level']} ({$deleted['year_code']})"]);
    }

    $message = "<p class='error-msg'>Year Level deleted successfully!</p>";
}

// Handle edit (load into form)
if (isset($_POST['edit_year_level'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM year_levels WHERE id=?");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_year_level = $row['year_level'];
        $edit_year_code = $row['year_code'];
    }
}

// Fetch all year levels
$year_levels = $pdo->query("SELECT * FROM year_levels ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container">
    <?= $message; ?>

    <!-- Add/Update Form -->
    <form method="POST" class="form-box">
        <input type="text" name="year_level" placeholder="Enter Year Level" 
               value="<?= htmlspecialchars($edit_year_level); ?>" required>
        <input type="text" name="year_code" placeholder="Enter Year Code" 
               value="<?= htmlspecialchars($edit_year_code); ?>" required>
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_year_level" class="btn btn-warning">Update Year Level</button>
            <a href="" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_year_level" class="btn btn-primary">Add Year Level</button>
        <?php endif; ?>
    </form>

    <!-- Year Levels Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Year Level</th>
                    <th>Year Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($year_levels as $yl): ?>
                <tr>
                    <td><?= $yl['id']; ?></td>
                    <td><?= htmlspecialchars($yl['year_level']); ?></td>
                    <td><?= htmlspecialchars($yl['year_code']); ?></td>
                    <td>
                        <!-- Edit button -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $yl['id']; ?>">
                            <button type="submit" name="edit_year_level" class="btn btn-info">Edit</button>
                        </form>

                        <!-- Delete button -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $yl['id']; ?>">
                            <button type="submit" name="delete_year_level" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
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
    margin-top: 20px;
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
    border: 1px solid #ddd; /* outer border */
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.styled-table th, .styled-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd; /* grid lines */
}
.styled-table thead {
    background: #c41e1e;
    color: white;
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
.btn-warning { background: #27ae60; }
.btn-danger { background: #dc3545; }
.btn-info { background: #27ae60; }
.btn-secondary { background: gray; text-decoration: none; }

.btn:hover { opacity: 0.9; }
</style>
