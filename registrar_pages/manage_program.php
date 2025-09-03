<?php
require __DIR__ . '/../db_connect.php'; // include your DB connection

// Handle add new program
if (isset($_POST['add_program'])) {
    $program_name = trim($_POST['program_name']);
    $program_code = trim($_POST['program_code']);
    if (!empty($program_name) && !empty($program_code)) {
        $stmt = $pdo->prepare("INSERT INTO programs (program_name, program_code) VALUES (?, ?)");
        $stmt->execute([$program_name, $program_code]);
        echo "<p style='color:green;'>Program added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }
}

// Handle update program
if (isset($_POST['update_program'])) {
    $id = $_POST['id'];
    $program_name = trim($_POST['program_name']);
    $program_code = trim($_POST['program_code']);
    $stmt = $pdo->prepare("UPDATE programs SET program_name=?, program_code=? WHERE id=?");
    $stmt->execute([$program_name, $program_code, $id]);
    echo "<p style='color:green;'>Program updated successfully!</p>";
}

// Handle delete
if (isset($_POST['delete_program'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM programs WHERE id=?");
    $stmt->execute([$id]);
    echo "<p style='color:red;'>Program deleted successfully!</p>";
}

// Fetch all programs
$programs = $pdo->query("SELECT * FROM programs ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Programs</h3>

<!-- Add Program Form -->
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="program_code" placeholder="Enter Program Code" required>
    <input type="text" name="program_name" placeholder="Enter Program Name" required>
    <button type="submit" name="add_program">Add Program</button>
</form>

<!-- Programs Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">
    <tr>
        <th>ID</th>
        <th>Program Code</th>
        <th>Program Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($programs as $program): ?>
    <tr>
        <td><?= $program['id']; ?></td>
        <td><?= htmlspecialchars($program['program_code']); ?></td>
        <td><?= htmlspecialchars($program['program_name']); ?></td>
        <td>
            <!-- Edit form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $program['id']; ?>">
                <input type="text" name="program_code" value="<?= htmlspecialchars($program['program_code']); ?>" required>
                <input type="text" name="program_name" value="<?= htmlspecialchars($program['program_name']); ?>" required>
                <button type="submit" name="update_program">Update</button>
            </form>

            <!-- Delete form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $program['id']; ?>">
                <button type="submit" name="delete_program" onclick="return confirm('Are you sure you want to delete this program?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
