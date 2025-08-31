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

        .container {
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }
        .container.hidden {
            opacity: 0;
            pointer-events: none; /* Prevent clicking when hidden */
        }

    </style>
</head>
<body>

<!-- Background slideshow -->
<div id="bg-slideshow"></div>

<div class="container">
    <!-- Left image panel -->
    <div class="left-panel">
        <img src="img/studisciplink.jpg" alt="Logo">
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
