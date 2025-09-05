<?php

// ✅ Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$host = "localhost";
$dbname = "studisciplink";
$user = "root";  
$pass = "";       

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) die("User not found.");

    if (isset($_POST['update_user'])) {
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $email    = $_POST['email'];
        $contact  = $_POST['contact'];

        $imgPath = $user['img']; 
        if (!empty($_FILES['img']['name'])) {
            $targetDir = "../studisciplink/userUploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $imgPath = $targetDir . basename($_FILES['img']['name']);
            move_uploaded_file($_FILES['img']['tmp_name'], $imgPath);
        }

        if ($password) {
            $stmt = $pdo->prepare("UPDATE users SET username=?, password=?, email=?, contact=?, img=? WHERE id=?");
            $stmt->execute([$username, $password, $email, $contact, $imgPath, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, contact=?, img=? WHERE id=?");
            $stmt->execute([$username, $email, $contact, $imgPath, $_SESSION['user_id']]);
        }

        $log = $pdo->prepare("INSERT INTO logs (user_id, action, date_time) VALUES (?, ?, NOW())");
        $log->execute([$_SESSION['user_id'], 'Updated account details']);

        $_SESSION['message'] = "Account updated successfully!";
        header("Location: admin.php?page=my_account");
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
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        .container { 
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            display: flex; 
            align-items: center; 
            gap: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .profile-img {
            flex-shrink: 0;
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
            flex: 1;
        }

        form h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            font-size: 26px;
            border-bottom: 2px solid #c41e1eff;
            padding-bottom: 10px;
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
            border-color: #c41e1eff;
            box-shadow: 0 0 5px rgba(240, 13, 13, 0.5);
            outline: none;
        }

        button { 
            background: #c41e1eff; 
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
            background: #ff0000ff; 
            transform: translateY(-2px);
        }

        .message { 
            padding: 12px; 
            margin-bottom: 15px; 
            border-radius: 8px; 
            font-size: 14px;
        }

        .success { 
            background: #d4edda; 
            color: #155724; 
        }

        .error { 
            background: #f8d7da; 
            color: #721c24; 
        }

        @media(max-width: 850px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
            .profile-img img {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-img">
            <?php if (!empty($user['img'])): ?>
                <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Profile Image">
            <?php else: ?>
                <img src="default.png" alt="Profile Image">
            <?php endif; ?>
        </div>

        <form action="update_account.php" method="POST" enctype="multipart/form-data">
            <h2>My Account</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Password:</label>
            <input type="password" name="password">

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

            <label>Contact:</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">

            <label>Profile Image:</label>
            <input type="file" name="img" accept="image/*">

            <button type="submit" name="update_user">Update Account</button>
        </form>
    </div>
</body>
</html>
