<?php
require __DIR__ . '/../db_connect.php'; // include DB connection

// Messages
$message = "";

// Edit mode variables
$edit_mode = false;
$edit_id = null;
$edit_name = "";

// Handle add violation
if (isset($_POST['add_violation'])) {
    $violation_name = trim($_POST['violation']);
    if ($violation_name) {
        $stmt = $pdo->prepare("INSERT INTO violations (violation) VALUES (?)");
        $stmt->execute([$violation_name]);
        $message = "<p class='success-msg'>Violation added successfully!</p>";
    } else {
        $message = "<p class='error-msg'>Please enter a violation.</p>";
    }
}

// Handle update violation
if (isset($_POST['update_violation'])) {
    $id = $_POST['id'];
    $violation_name = trim($_POST['violation']);
    $stmt = $pdo->prepare("UPDATE violations SET violation=? WHERE id=?");
    $stmt->execute([$violation_name, $id]);
    $message = "<p class='success-msg'>Violation updated successfully!</p>";
}

// Handle delete violation
if (isset($_POST['delete_violation'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM violations WHERE id=?");
    $stmt->execute([$id]);
    $message = "<p class='error-msg'>Violation deleted successfully!</p>";
}

// Handle edit (load values into form)
if (isset($_POST['edit_violation'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM violations WHERE id=?");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_name = $row['violation'];
    }
}

// Fetch all violations
$violations = $pdo->query("SELECT * FROM violations ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <?= $message; ?>

    <!-- Add/Update Form -->
    <form method="POST" class="form-box">
        <input type="text" name="violation" placeholder="Enter Violation"
               value="<?= htmlspecialchars($edit_name); ?>" required>
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_violation" class="btn btn-warning">Update Violation</button>
            <a href="faculty.php?page=manage_violation" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_violation" class="btn btn-primary">Add Violation</button>
        <?php endif; ?>
    </form>

    <!-- Violations Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Violation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($violations as $v): ?>
                <tr>
                    <td><?= $v['id']; ?></td>
                    <td><?= htmlspecialchars($v['violation']); ?></td>
                    <td>
                        <!-- Edit -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $v['id']; ?>">
                            <button type="submit" name="edit_violation" class="btn btn-info">Edit</button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $v['id']; ?>">
                            <button type="submit" name="delete_violation" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this violation?')">
                                Delete
                            </button>
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
.form-box { margin-bottom: 20px; }
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
.styled-table th, .styled-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd; /* gridlines */
}
.styled-table thead {
    background: #c41e1e;
    color: white;
}
.styled-table tr:nth-child(even) { background: #f9f9f9; }

/* Inline forms */
.inline-form { display: inline-block; margin: 2px; }

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
