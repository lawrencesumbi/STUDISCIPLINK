<?php
require __DIR__ . '/../db_connect.php'; // include your DB connection

// Handle add new section
if (isset($_POST['add_section'])) {
    $section_name = trim($_POST['section_name']);
    if ($section_name) {
        $stmt = $pdo->prepare("INSERT INTO sections (section_name) VALUES (?)");
        $stmt->execute([$section_name]);
        echo "<p style='color:green;'>Section added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Please enter a section name.</p>";
    }
}

// Handle update section
if (isset($_POST['update_section'])) {
    $id = $_POST['id'];
    $section_name = trim($_POST['section_name']);
    $stmt = $pdo->prepare("UPDATE sections SET section_name=? WHERE id=?");
    $stmt->execute([$section_name, $id]);
    echo "<p style='color:green;'>Section updated successfully!</p>";
}

// Handle delete section
if (isset($_POST['delete_section'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM sections WHERE id=?");
    $stmt->execute([$id]);
    echo "<p style='color:red;'>Section deleted successfully!</p>";
}

// Fetch all sections
$sections = $pdo->query("SELECT * FROM sections ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Sections</h3>

<!-- Add Section Form -->
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="section_name" placeholder="Enter Section Name" required>
    <button type="submit" name="add_section">Add Section</button>
</form>

<!-- Sections Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">
    <tr>
        <th>ID</th>
        <th>Section Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($sections as $s): ?>
    <tr>
        <td><?= $s['id']; ?></td>
        <td><?= htmlspecialchars($s['section_name']); ?></td>
        <td>
            <!-- Edit form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $s['id']; ?>">
                <input type="text" name="section_name" value="<?= htmlspecialchars($s['section_name']); ?>" required>
                <button type="submit" name="update_section">Update</button>
            </form>

            <!-- Delete form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $s['id']; ?>">
                <button type="submit" name="delete_section" onclick="return confirm('Are you sure you want to delete this section?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>