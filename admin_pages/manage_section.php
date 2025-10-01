<?php
require __DIR__ . '/../db_connect.php'; // include DB connection

// Messages
$message = "";

// Edit mode variables
$edit_mode = false;
$edit_id = null;
$edit_name = "";

// Handle add section
if (isset($_POST['add_section'])) {
    $section_name = trim($_POST['section_name']);
    if ($section_name) {
        $stmt = $pdo->prepare("INSERT INTO sections (section_name) VALUES (?)");
        $stmt->execute([$section_name]);
        $message = "<p class='success-msg'>Section added successfully!</p>";
    } else {
        $message = "<p class='error-msg'>Please enter a section name.</p>";
    }
}

// Handle update section
if (isset($_POST['update_section'])) {
    $id = $_POST['id'];
    $section_name = trim($_POST['section_name']);
    $stmt = $pdo->prepare("UPDATE sections SET section_name=? WHERE id=?");
    $stmt->execute([$section_name, $id]);
    $message = "<p class='success-msg'>Section updated successfully!</p>";
}

// Handle delete section
if (isset($_POST['delete_section'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM sections WHERE id=?");
    $stmt->execute([$id]);
    $message = "<p class='error-msg'>Section deleted successfully!</p>";
}

// Handle edit (load values into form)
if (isset($_POST['edit_section'])) {
    $edit_id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM sections WHERE id=?");
    $stmt->execute([$edit_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $edit_mode = true;
        $edit_name = $row['section_name'];
    }
}

// Fetch all sections
$sections = $pdo->query("SELECT * FROM sections ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <?= $message; ?>

    <!-- Add/Update Form -->
    <form method="POST" class="form-box">
        <input type="text" name="section_name" placeholder="Enter Section Name"
               value="<?= htmlspecialchars($edit_name); ?>" required>
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_id; ?>">
            <button type="submit" name="update_section" class="btn btn-warning">Update Section</button>
            <a href="" class="btn btn-secondary">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_section" class="btn btn-primary">Add Section</button>
        <?php endif; ?>
    </form>

    <!-- Sections Table -->
    <div class="table-box">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Section Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($sections as $s): ?>
                <tr>
                    <td><?= $s['id']; ?></td>
                    <td><?= htmlspecialchars($s['section_name']); ?></td>
                    <td>
                        <!-- Edit -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $s['id']; ?>">
                            <button type="submit" name="edit_section" class="btn btn-info">Edit</button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $s['id']; ?>">
                            <button type="submit" name="delete_section" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this section?')">
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
