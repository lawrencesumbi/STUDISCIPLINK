<?php

// ✅ Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root";   // change if needed
$pass = "";       // change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Fetch current user details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }

    // ✅ Update if form submitted
if (isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $email    = $_POST['email'];
    $contact  = $_POST['contact'];

    // ✅ Optional: Handle profile image upload
    $imgPath = $user['img']; // keep old image if not changed
    if (!empty($_FILES['img']['name'])) {
        $targetDir = "../studisciplink/userUploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imgPath = $targetDir . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $imgPath);
    }

    if ($password) {
        $stmt = $pdo->prepare("UPDATE users SET username=?, password=?, email=?, contact=?, img=? WHERE id=?");
        $stmt->execute([$username, $password, $email, $contact, $imgPath, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, contact=?, img=? WHERE id=?");
        $stmt->execute([$username, $email, $contact, $imgPath, $id]);
    }


        // ✅ Log the action
        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$_SESSION['user_id'], 'Updated account details']);

        $_SESSION['message'] = "Account updated successfully!";
        header("Location: my_account.php");
        exit;
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 500px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; }
        input, button { width: 92%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #4CAF50; color: white; cursor: pointer; }
        button:hover { background: #45a049; }
        img { max-width: 100px; margin: 10px 0; border-radius: 50%; }
        .message { padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Account</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form action="update_account.php" method="POST" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Password (leave blank to keep current):</label>
            <input type="password" name="password">

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

            <label>Contact:</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">

            <label>Profile Image:</label>
            <?php if (!empty($user['img'])): ?>
                <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Profile Image">
            <?php endif; ?>
            <input type="file" name="img" accept="image/*">

            <button type="submit">Update Account</button>
        </form>
    </div>
</body>
</html>
