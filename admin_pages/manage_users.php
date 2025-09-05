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

    // Fetch users with optional search
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    if ($search) {
        $stmt = $pdo->prepare("SELECT * FROM users 
                               WHERE username LIKE ? 
                               OR email LIKE ? 
                               OR role LIKE ?
                               OR status LIKE ?  
                               ORDER BY id ASC");
        $stmt->execute(["%$search%", "%$search%", "%$search%", "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
    }
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "<p style='color:red;'>Database error: ".$e->getMessage()."</p>";
}
?>

<style>
/* Form Heading */
.form-heading {
    padding: 10px 15px;
    border-radius: 8px 8px 0 0;
    margin-bottom: 0;
}

/* Add/Edit User Form */
.user-form {
    padding: 15px;
    border-radius: 8px 8px 8px 8px;
    box-shadow: 2px 2px 8px rgba(0,0,0,0.25);
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 30px;
}

.user-form .row {
    display: flex;
    gap: 10px;
}

.user-form input,
.user-form select {
    flex: 1;
    min-width: 48%;
    height: 38px;
    padding: 10px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.user-form button {
    flex: 1;
    min-width: 48%;
    height: 40px;
    font-weight: bold;
    border: 1px solid #ccc;
    border-radius: 5px;
    cursor: pointer;
    background: none;
    color: inherit;
    transition: 0.3s;
}

.user-form .row button {
    background: #f2f2f2;
}
.user-form .row button:hover {
    background: #e0e0e0;
}

/* Table container with fixed height and scroll */
.table-container {
    max-height: 400px; /* adjust as needed */
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Table styling */
.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th,
.users-table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ccc;
}

.users-table th {
    background: #c41e1e;
    color: white;
    position: sticky;
    top: 0;
    z-index: 2;
}

.users-table tr:nth-child(even) {
    background: #f9f9f9;
}

/* Action buttons */
.btn-edit {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 5px;
    transition: 0.3s;
}

.btn-edit:hover {
    background-color: #2ecc71;
}

.btn-delete {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
}

.btn-delete:hover {
    background-color: #ff0000;
}

/* Search form */
.search-form {
    margin-bottom: 15px;
    display: flex;
    gap: 10px;
}

.search-form input[type="text"] {
    flex: 1;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.search-form button {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    background: #c41e1e;
    color: white;
    cursor: pointer;
}

.search-form a {
    padding: 8px 12px;
    border-radius: 5px;
    background: #555;
    color: white;
    text-decoration: none;
}
</style>

<!-- Add/Edit User Form -->
<form method="POST" class="user-form">
    <input type="hidden" name="id" id="user_id">

    <!-- Row 1: Username + Password -->
    <div class="row">
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="password" name="password" id="password" placeholder="Password">
    </div>

    <!-- Row 2: Email + Contact -->
    <div class="row">
        <input type="text" name="email" id="email" placeholder="Email">
        <input type="text" name="contact" id="contact" placeholder="Contact">
    </div>

    <!-- Row 3: Role + Status -->
    <div class="row">
        <select name="role" id="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="guidance">Guidance</option>
            <option value="sao">SAO</option>
            <option value="registrar">Registrar</option>
            <option value="faculty">Faculty</option>
        </select>
        <select name="status" id="status" required>
            <option value="">Select Status</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <!-- Row 4: Buttons -->
    <div class="row">
        <button type="submit" name="add_user" class="add-user">Add User</button>
        <button type="submit" name="update_user" class="update-user">Update User</button>
    </div>
</form>

<!-- Search Form -->
<form method="GET" class="search-form">
    <input type="hidden" name="page" value="manage_users">
    <input type="text" name="search" placeholder="Search by username, email, role or status" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if($search): ?>
        <a href="?page=manage_users">Clear</a>
    <?php endif; ?>
</form>

<!-- Users Table -->
<div class="table-container">
    <table class="users-table">
        <thead>
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
                    <button class="btn-edit" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</button>
                    <a class="btn-delete" href="?page=manage_users&delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Fill form with existing user data for editing
function editUser(user) {
    document.getElementById('user_id').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('password').value = '';
    document.getElementById('password').disabled = true; // Disable password field on edit
    document.getElementById('email').value = user.email;
    document.getElementById('contact').value = user.contact;
    document.getElementById('role').value = user.role;
    document.getElementById('status').value = user.status;
}

// Enable password field when adding a new user
document.querySelector('.add-user').addEventListener('click', function() {
    document.getElementById('password').disabled = false;
});
</script>
