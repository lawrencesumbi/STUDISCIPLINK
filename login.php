<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        /* Reset and base */
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
            margin: 0;
            overflow: hidden;
        }

        /* Slideshow background */
        #bg-slideshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* behind everything */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url('img/scc4.png'); /* initial image */
            transition: opacity .8s ease-in-out;

            /* Zoom animation */
            animation: zoomEffect 15s ease-in-out infinite;
        }

        /* Keyframes for zoom */
        @keyframes zoomEffect {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.25); /* zoom in slightly */
            }
            100% {
                transform: scale(1);
            }
        }

        /* Container for image + form */
        .container {
            display: flex;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            box-shadow: 0 12px 12px rgba(0,0,0,0.25);
            overflow: hidden;
            max-width: 800px;
            max-height: 500px;
            backdrop-filter: blur(10px);
            opacity: 1;
            transition: opacity 1s ease-in-out;
            z-index: 2;
        }
        .container.hidden {
            opacity: 0;
            pointer-events: none; /* Prevent clicking when hidden */
        }

        /* Welcome text when idle */
        .welcome-message {
            background: rgba(230, 15, 15, 0.5);
            border-radius: 20px;
            position: absolute;
            width: 75%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 1;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            padding: 30px 40px;
        }
        .welcome-message.show {
            opacity: 1;
        }
        .welcome-message h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 58px;
            margin-bottom: 15px;
            text-shadow: 4px 4px 8px rgba(0,0,0,0.6);
        }
        .welcome-message p {
            font-family: 'Orbitron', sans-serif;
            font-size: 20px;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
        }

        /* Left panel image */
        .left-panel {
            flex: 2;
            display: flex;
            align-items: stretch;
            justify-content: center;
            overflow: hidden;
        }

        .left-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right login form */
        .right-panel {
            flex: 1;
            padding: 35px 30px;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 26px;
            color: #2c3e50;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background: #fafafa;
            transition: 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #c41e1eff;
            outline: none;
            background: #fff;
            box-shadow: 0 0 6px rgba(76,175,80,0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            background: #c41e1eff;
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
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            line-height: 1.5;
        }

        .register-link a {
            color: #c41e1eff;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Background slideshow -->
<div id="bg-slideshow"></div>

<!-- Welcome message -->
<div class="welcome-message" id="welcomeMessage">
    <h1>Welcome to STUDISCIPLINK</h1>
    <p>“A comprehensive platform designed to help schools manage student affairs, track disciplinary records, and support the holistic growth and development of every learner.”</p>
</div>

<div class="container">
    <!-- Left image panel -->
    <div class="left-panel">
        <img src="img/studisciplinks.jpg" alt="Logo">
    </div>

    <!-- Right login form -->
    <div class="right-panel">
        <h2>Login</h2>
        <?php if(isset($_SESSION['error'])) { ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php } ?>
        <form method="POST" action="authenticate.php">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p><a href="forgot_password.php">Forgot Password?</a></p>
            Don’t have an account? <a href="register.php">Register here</a>
            
        </div>
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

    let index = 1; // start from second image because first is set in CSS
    const slideshow = document.getElementById('bg-slideshow');

    function changeBackground() {
        slideshow.style.opacity = 0; // fade out
        setTimeout(() => {
            slideshow.style.backgroundImage = `url('${backgrounds[index]}')`;
            slideshow.style.opacity = 1; // fade in
            index = (index + 1) % backgrounds.length;
        }, 500); // half of fade duration
    }

    setInterval(changeBackground, 5000); // every 5 seconds
</script>
<script>
    let idleTimer;
    const container = document.querySelector('.container');
    const welcomeMessage = document.getElementById('welcomeMessage');

    function resetIdleTimer() {
        container.classList.remove("hidden"); // show login container
        welcomeMessage.classList.remove("show"); // hide welcome text
        clearTimeout(idleTimer);
        idleTimer = setTimeout(() => {
            container.classList.add("hidden"); // hide login
            welcomeMessage.classList.add("show"); // show welcome
        }, 7000); // 7 seconds idle before fade out
    }

    // Reset timer on any mouse movement or key press
    window.addEventListener("mousemove", resetIdleTimer);
    window.addEventListener("keydown", resetIdleTimer);

    // Start initially
    resetIdleTimer();
</script>

</body>
</html>
