<?php
require 'db_connect.php';

// Fetch logged in user
$id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Account</title>
<style>
body {
    background: #e0e0e0;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 900px;
    margin: 50px auto;
    background: #fff;
    padding: 30px 40px;
    border-radius: 15px;
    display: flex;
    gap: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.profile-img {
    margin: auto 0;
    text-align: center;
}
.profile-img img {
    width: 250px;
    height: 250px;
    object-fit: cover;
    border-radius: 50%;
    border: 5px solid #ebe0e0ff;
}
form {
    margin-bottom: 25px;
}
form h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
    font-size: 22px;
    border-left: 4px solid #c41e1e;
    padding-left: 10px;
}
input[type="text"],
input[type="password"],
input[type="email"],
input[type="file"] {
    width: 92%;
    padding: 12px 15px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
}
input:focus {
    border-color: #c41e1e;
    box-shadow: 0 0 5px rgba(240,13,13,0.5);
    outline: none;
}
button {
    background: #c41e1e;
    color: white;
    cursor: pointer;
    padding: 12px;
    margin-top: 15px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    transition: 0.3s;
}
button:hover {
    background: #ff0000;
    transform: translateY(-2px);
}
.message {
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    font-size: 14px;
}
.success { background: #d4edda; color: #155724; }
.error { background: #f8d7da; color: #721c24; }

@media(max-width: 1200px) {
    .container { flex-direction: column; align-items: center; }
    .column { width: 100%; }
    .profile-img img { margin-bottom: 20px; }
}
</style>
</head>
<body>

<div class="container">
    <!-- Left Column: Profile Image -->
    <div class="column profile-img">
        <?php if (!empty($user['img'])): ?>
            <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Profile Image">
        <?php else: ?>
            <img src="default.png" alt="Profile Image">
        <?php endif; ?>
    </div>

    <!-- Center Column: Update Account Info -->
    <div class="column">
        <form action="/studisciplink/update_account.php" method="POST" enctype="multipart/form-data">
            <h2>Update Account Info</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

            <label>Contact:</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">

            <label>Profile Image:</label>
            <input type="file" name="img" accept="image/*">

            <button type="submit" name="update_info">Update Account</button>
        </form>
    </div>

    <!-- Right Column: Change Password -->
    <div class="column">
        <form action="/studisciplink/update_account.php" method="POST">
            <h2>Change Password</h2>

            <?php if (isset($_SESSION['pass_message'])): ?>
                <div class="message <?php echo strpos($_SESSION['pass_message'],'success')!==false?'success':'error'; ?>">
                    <?php echo $_SESSION['pass_message']; unset($_SESSION['pass_message']); ?>
                </div>
            <?php endif; ?>

            <label>Old Password:</label>
            <input type="password" name="old_password" required>

            <label>New Password:</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</div>

</body>
</html>
