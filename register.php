<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Background slideshow */
        #bg-slideshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url('img/scc4.png'); /* initial image */
            transition: opacity 1s ease-in-out;
            opacity: 1;
        }

        /* Wrapper for left image + form */
        .register-container {
            display: flex;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 12px 12px rgba(0,0,0,0.25);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        /* Left side image */
        .register-image {
            width: 500px;
            background: url('img/scc.png') no-repeat center center;
            background-size: cover;
        }

        /* Registration box (right side) */
        .register-box {
            padding: 35px 30px;
            width: 350px;
            text-align: center;
        }

        .register-box h2 {
            margin-bottom: 25px;
            font-size: 26px;
            color: #2c3e50;
        }

        input[type="text"], input[type="password"] {
            width: 92%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background: #fafafa;
            transition: 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #e74c3c;
            outline: none;
            background: #fff;
            box-shadow: 0 0 6px rgba(33,150,243,0.3);
        }

        button {
            width: 92%;
            padding: 12px;
            margin-top: 12px;
            background: #d13131ff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff0000ff;
            transform: scale(1.02);
        }

        .error { 
            color: #e74c3c; 
            font-size: 14px; 
            margin-bottom: 10px;
        }

        .success { 
            color: #21a358ff; 
            font-size: 14px; 
            margin-bottom: 10px;
        }

        a {
            color: #d13131ff;;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        select {
            width: 92%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background: #fafafa;
            transition: 0.3s;
        }

        select:focus {
            border-color: #e74c3c;
            outline: none;
            background: #fff;
            box-shadow: 0 0 6px rgba(33,150,243,0.3);
        }

        .register-container {
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }
        .register-container.hidden {
            opacity: 0;
            pointer-events: none; /* Prevent clicking when hidden */
        }

    </style>
</head>
<body>

<!-- Slideshow background -->
<div id="bg-slideshow"></div>

<!-- Registration form with image on left -->
<div class="register-container">
    <div class="register-image"></div>

    <div class="register-box">
        <h2>Register</h2>
        <?php if(isset($_SESSION['message'])) { ?>
            <p class="<?php echo $_SESSION['msg_type']; ?>">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </p>
        <?php } ?>
        <form method="POST" action="register_process.php">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <!-- Role selection dropdown -->
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="admin">Admin</option>
                <option value="guidance">Guidance</option>
                <option value="sao">SAO</option>
                <option value="registrar">Registrar</option>
                <option value="faculty">Faculty</option>
            </select>

            <button type="submit">Register</button>
        </form>
        <p style="margin-top:15px;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script>
    const backgrounds = [
        "img/scc4.png",
        "img/scc5.jpg",
        "img/scc2.png",
        "img/scc1.png",
        "img/scc6.jpg"
    ];

    let index = 1; // start from second image
    const slideshow = document.getElementById('bg-slideshow');

    function changeBackground() {
        slideshow.style.opacity = 0; // fade out
        setTimeout(() => {
            slideshow.style.backgroundImage = `url('${backgrounds[index]}')`;
            slideshow.style.opacity = 1; // fade in
            index = (index + 1) % backgrounds.length;
        }, 500); // match half of fade duration
    }

    setInterval(changeBackground, 5000); // every 5 seconds
</script>
<script>
    let idleTimer;
    const container = document.querySelector('.register-container');

    function resetIdleTimer() {
        container.classList.remove("hidden"); // show container
        clearTimeout(idleTimer);
        idleTimer = setTimeout(() => {
            container.classList.add("hidden"); // hide container
        }, 12000); // 12 seconds idle before fade out
    }

    // Reset timer on any mouse movement
    window.addEventListener("mousemove", resetIdleTimer);
    window.addEventListener("keydown", resetIdleTimer);

    // Start initially
    resetIdleTimer();
</script>

</body>
</html>
