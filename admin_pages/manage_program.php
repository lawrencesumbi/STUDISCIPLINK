<?php
require __DIR__ . '/../db_connect.php'; // include DB connection

// Messages
$message = "";

// Get current user ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Variables for edit mode
$edit_mode = false;
$edit_id = null;
$edit_code = "";
$edit_name = "";

// Handle add program
if (isset($_POST['add_program'])) {
    $program_name = trim($_POST['program_name']);
    $program_code = trim($_POST['program_code']);
    if (!empty($program_name) && !empty($program_code)) {
        $stmt = $pdo->prepare("INSERT INTO programs (program_name, program_code) VALUES (?, ?)");
        $stmt->execute([$program_name, $program_code]);

        // Log action
        if ($user_id) {
            $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
            $log->execute([$user_id, "Added new program: $program_name ($program_code)"]);
        }

        $message = "<p class='success-msg'>Program added successfully!</p>";
    } else {
        $message = "<p class='error-msg'>Please fill in all fields.</p>";
    }
}

// Handle update program
if (isset($_POST['update_program'])) {
    $id = $_POST['id'];
    $program_name = trim($_POST['program_name']);
    $program_code = trim($_POST['program_code']);

    $stmt = $pdo->prepare("UPDATE programs SET program_name=?, program_code=? WHERE id=?");
    $stmt->execute([$program_name, $program_code, $id]);

    // Log action
    if ($user_id) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Updated program (ID: $id) to: $program_name ($program_code)"]);
    }

    $message = "<p class='success-msg'>Program updated successfully!</p>";
}

// Handle delete
if (isset($_POST['delete_program'])) {
    $id = $_POST['id'];

    // Get program details before deleting (for log)
    $getProgram = $pdo->prepare("SELECT program_name, program_code FROM programs WHERE id=?");
    $getProgram->execute([$id]);
    $deleted = $getProgram->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("DELETE FROM programs WHERE id=?");
    $stmt->execute([$id]);

    // Log action
    if ($user_id && $deleted) {
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$user_id, "Deleted program: {$deleted['program_name']} ({$deleted['program_code']})"]);
    }

    $message = "<p class='error-msg'>Program deleted successfully!</p>";
}

// Handle edit (load values to form)
if (isset($_POST['edit_program'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM programs WHERE id=?");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_code = $row['program_code'];
        $edit_name = $row['program_name'];
    }
}

// Fetch all programs
$programs = $pdo->query("SELECT * FROM programs ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <?= $message; ?>

    <!-- Add/Update Program Form -->
    <form method="POST" class="form-box">
        <input type="text" name="program_code" placeholder="Enter Program Code" 
               value="<?= htmlspecialchars($edit_code); ?>" required>
        <input type="text" name="program_name" placeholder="Enter Program Name" 
               value="<?= htmlspecialchars($edit_name); ?>" required>
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_program" class="btn btn-warning">Update Program</button>
            <a href="" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_program" class="btn btn-primary">Add Program</button>
        <?php endif; ?>
    </form>

    <!-- Programs Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Program Code</th>
                    <th>Program Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($programs as $program): ?>
                <tr>
                    <td><?= $program['id']; ?></td>
                    <td><?= htmlspecialchars($program['program_code']); ?></td>
                    <td><?= htmlspecialchars($program['program_name']); ?></td>
                    <td>
                        <!-- Edit -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $program['id']; ?>">
                            <button type="submit" name="edit_program" class="btn btn-info">Edit</button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $program['id']; ?>">
                            <button type="submit" name="delete_program" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this program?')">Delete</button>
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
