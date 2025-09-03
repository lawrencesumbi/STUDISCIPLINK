<?php
require __DIR__ . '/../db_connect.php'; // include your DB connection

// Handle add new year level
if (isset($_POST['add_year_level'])) {
    $year_level = trim($_POST['year_level']);
    $stmt = $pdo->prepare("INSERT INTO year_levels (year_level) VALUES (?)");
    $stmt->execute([$year_level]);
    echo "<p style='color:green;'>Year Level added successfully!</p>";
}

// Handle update year level
if (isset($_POST['update_year_level'])) {
    $id = $_POST['id'];
    $year_level = trim($_POST['year_level']);
    $stmt = $pdo->prepare("UPDATE year_levels SET year_level=? WHERE id=?");
    $stmt->execute([$year_level, $id]);
    echo "<p style='color:green;'>Year Level updated successfully!</p>";
}

// Handle delete
if (isset($_POST['delete_year_level'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM year_levels WHERE id=?");
    $stmt->execute([$id]);
    echo "<p style='color:red;'>Year Level deleted successfully!</p>";
}

// Fetch all year levels
$year_levels = $pdo->query("SELECT * FROM year_levels ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Year Levels</h3>

<!-- Add Year Level Form -->
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="year_level" placeholder="Enter Year Level" required>
    <button type="submit" name="add_year_level">Add Year Level</button>
</form>

<!-- Year Levels Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">
    <tr>
        <th>ID</th>
        <th>Year Level</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($year_levels as $yl): ?>
    <tr>
        <td><?= $yl['id']; ?></td>
        <td><?= htmlspecialchars($yl['year_level']); ?></td>
        <td>
            <!-- Edit form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $yl['id']; ?>">
                <input type="text" name="year_level" value="<?= htmlspecialchars($yl['year_level']); ?>" required>
                <button type="submit" name="update_year_level">Update</button>
            </form>

            <!-- Delete form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $yl['id']; ?>">
                <button type="submit" name="delete_year_level" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
