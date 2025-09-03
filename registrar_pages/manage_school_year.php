<?php
require '../db_connect.php'; // Database connection

// Handle add new school year
if (isset($_POST['add_school_year'])) {
    $school_year = trim($_POST['school_year']);
    $stmt = $pdo->prepare("INSERT INTO school_years (school_year) VALUES (?)");
    $stmt->execute([$school_year]);
    echo "<p style='color:green;'>School Year added successfully!</p>";
}

// Handle update school year
if (isset($_POST['update_school_year'])) {
    $id = $_POST['id'];
    $school_year = trim($_POST['school_year']);
    $stmt = $pdo->prepare("UPDATE school_years SET school_year=? WHERE id=?");
    $stmt->execute([$school_year, $id]);
    echo "<p style='color:green;'>School Year updated successfully!</p>";
}

// Handle delete
if (isset($_POST['delete_school_year'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM school_years WHERE id=?");
    $stmt->execute([$id]);
    echo "<p style='color:red;'>School Year deleted successfully!</p>";
}

// Fetch all school years
$school_years = $pdo->query("SELECT * FROM school_years ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage School Year</h3>

<!-- Add School Year Form -->
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="school_year" placeholder="Enter School Year (e.g., 2025-2026)" required>
    <button type="submit" name="add_school_year">Add School Year</button>
</form>

<!-- School Year Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">
    <tr>
        <th>ID</th>
        <th>School Year</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($school_years as $sy): ?>
    <tr>
        <td><?= $sy['id']; ?></td>
        <td><?= htmlspecialchars($sy['school_year']); ?></td>
        <td>
            <!-- Edit form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $sy['id']; ?>">
                <input type="text" name="school_year" value="<?= htmlspecialchars($sy['school_year']); ?>" required>
                <button type="submit" name="update_school_year">Update</button>
            </form>

            <!-- Delete form -->
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="id" value="<?= $sy['id']; ?>">
                <button type="submit" name="delete_school_year" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
