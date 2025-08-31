<?php
// admin_pages/manage_users.php
$host = "localhost";
$dbname = "studisciplink";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submissions
    if(isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, contact, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $role, $email, $contact, $status]);
    }

    if(isset($_POST['update_user'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $role = $_POST['role'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];

        if($password) {
            $stmt = $pdo->prepare("UPDATE users SET username=?, password=?, role=?, email=?, contact=?, status=? WHERE id=?");
            $stmt->execute([$username, $password, $role, $email, $contact, $status, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username=?, role=?, email=?, contact=?, status=? WHERE id=?");
            $stmt->execute([$username, $role, $email, $contact, $status, $id]);
        }
    }

    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
    }

    // Fetch all users
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "<p style='color:red;'>Database error: ".$e->getMessage()."</p>";
}
?>

<h3 style="margin-bottom:15px;">USERS</h3>

<!-- Add/Edit User Form -->
<form method="POST" style="margin-bottom:20px; background:#fff; padding:15px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
    <input type="hidden" name="id" id="user_id">
    <input type="text" name="username" id="username" placeholder="Username" required>
    <input type="password" name="password" id="password" placeholder="Password (leave blank to keep)">
    <input type="text" name="email" id="email" placeholder="Email" required>
    <input type="text" name="contact" id="contact" placeholder="Contact" required>
    <select name="role" id="role" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="guidance">Guidance</option>
        <option value="SAO">SAO</option>
        <option value="registrar">Registrar</option>
        <option value="faculty">Faculty</option>
    </select>
    <select name="status" id="status" required>
        <option value="">Select Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
    <button type="submit" name="add_user">Add User</button>
    <button type="submit" name="update_user">Update User</button>
</form>

<!-- Users Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width:100%; background:#fff; border-radius:8px; overflow:hidden;">
    <thead style="background:#c41e1e; color:white;">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td>********</td>
            <td><?php echo $user['role']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['contact']; ?></td>
            <td><?php echo $user['status']; ?></td>
            <td>
                <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</button>
                <a href="?page=manage_users&delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
// Fill form with existing user data for editing
function editUser(user) {
    document.getElementById('user_id').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('password').value = '';
    document.getElementById('email').value = user.email;
    document.getElementById('contact').value = user.contact;
    document.getElementById('role').value = user.role;
    document.getElementById('status').value = user.status;
}
</script>
