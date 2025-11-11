<?php
require __DIR__ . '/../db_connect.php'; // include DB connection

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";
$edit_mode = false;
$edit_id = null;
$edit_name = "";

// Function to log user actions
function logAction($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $action]);
}

// Handle add sanction
if (isset($_POST['add_sanction'])) {
    $sanction_name = trim($_POST['sanction']);
    if ($sanction_name) {
        $stmt = $pdo->prepare("INSERT INTO sanctions (sanction) VALUES (?)");
        $stmt->execute([$sanction_name]);
        $message = "<p class='success-msg'>Sanction added successfully!</p>";

        // Log action
        logAction($pdo, $user_id, "Added sanction: $sanction_name");
    } else {
        $message = "<p class='error-msg'>Please enter a sanction.</p>";
    }
}

// Handle update sanction
if (isset($_POST['update_sanction'])) {
    $id = $_POST['id'];
    $sanction_name = trim($_POST['sanction']);
    $stmt = $pdo->prepare("UPDATE sanctions SET sanction=? WHERE id=?");
    $stmt->execute([$sanction_name, $id]);
    $message = "<p class='success-msg'>Sanction updated successfully!</p>";

    // Log action
    logAction($pdo, $user_id, "Updated sanction ID $id to '$sanction_name'");
}

// Handle delete sanction
if (isset($_POST['delete_sanction'])) {
    $id = $_POST['id'];
    // Get sanction name before deleting
    $stmt = $pdo->prepare("SELECT sanction FROM sanctions WHERE id=?");
    $stmt->execute([$id]);
    $sanction = $stmt->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM sanctions WHERE id=?");
    $stmt->execute([$id]);
    $message = "<p class='error-msg'>Sanction deleted successfully!</p>";

    // Log action
    logAction($pdo, $user_id, "Deleted sanction: $sanction (ID $id)");
}

// Handle edit (load values into form)
if (isset($_POST['edit_sanction'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM sanctions WHERE id=?");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_name = $row['sanction'];
    }
}

// Fetch all sanctions
$sanctions = $pdo->query("SELECT * FROM sanctions ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <?= $message; ?>

    <!-- Add/Update Form -->
    <form method="POST" class="form-box">
        <input type="text" name="sanction" placeholder="Enter Sanction"
               value="<?= htmlspecialchars($edit_name); ?>" required>
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_sanction" class="btn btn-warning">Update Sanction</button>
            <a href="faculty.php?page=manage_sanction" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_sanction" class="btn btn-primary">Add Sanction</button>
        <?php endif; ?>
    </form>

    <!-- Sanctions Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sanction</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($sanctions as $s): ?>
                <tr>
                    <td><?= $s['id']; ?></td>
                    <td><?= htmlspecialchars($s['sanction']); ?></td>
                    <td>
                        <!-- Edit -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $s['id']; ?>">
                            <button type="submit" name="edit_sanction" class="btn btn-info">Edit</button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $s['id']; ?>">
                            <button type="submit" name="delete_sanction" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this sanction?')">
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
