<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Verification System - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            overflow: hidden;
            position: relative;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: 'Istok Web', sans-serif;
            background: url('img/Blue Backround Enhanced.png') no-repeat center center fixed;
            background-size: cover;
        }

        .logo {
            position: absolute;
            top: 27px;
            left: 39px;
            width: 88px;
            height: 84px;
            z-index: 2;
        }

        .cpc-text {
            position: absolute;
            top: 7%;
            left: 125px;
            transform: translateY(-50%);
            font-size: 17px;
            color: #f0f0f0;
            margin: 20px;
            z-index: 2;
            font-family: 'Istok Web', sans-serif;
        }

        .login-container {
            position: absolute;
            top: 50%;
            left: 50%; /* Center the container */
            transform: translate(-50%, -50%); /* Center it vertically and horizontally */
            width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .login-container img {
            width: auto;
            height: 100px;
            display: block;
            margin: 0 auto 40px;
            margin-bottom: 50px;
        }

        .input-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
            margin-left: 40px;
        }

        .input-container input {
            width: 300px;
            padding: 10px 0;
            padding-left: 40px;
            margin: 0;
            border: none;
            border-bottom: 2px solid #ddd;
            font-size: 16px;
            background: transparent;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .view-password {
            position: absolute;
            right: 10px;
            margin-left: 290px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #000522;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Istok Web', sans-serif;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            margin-left: 210px;
            color: #0056b3;
            text-decoration: none;
            font-size: 14px;
            font-family: 'Istok Web', sans-serif;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <img src="img/CPC LOGO BACKROUND REMOVED.png" alt="logo" class="logo">
    <div class="cpc-text">COLEGIO DE LA PURISIMA CONCEPCION</div>

    <div class="login-container">
        <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Login Form Image">
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fas fa-eye view-password" id="togglePassword"></i>
        </div>
        <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
        <button type="button" onclick="checkLogin()">LOGIN</button>
    </div>

    <script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const username = document.getElementById('username');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });

    function checkLogin() {
        event.preventDefault();

        const validUsername = "admin";
        const validPassword = "password";

        // Get the input values
        const usernameValue = username.value;
        const passwordValue = password.value;

        // Simple client-side validation (ensure both fields are filled)
        if (usernameValue === "" || passwordValue === "") {
            alert("Please fill out both fields.");
            return;
        }

        // Proceed to submit the data via AJAX (fetch API)
        fetch('dashboard.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `username=${encodeURIComponent(usernameValue)}&password=${encodeURIComponent(passwordValue)}`
        })
        .then(response => response.text())
        .then(data => {
            if (usernameValue === validUsername && passwordValue === validPassword) {
                window.location.href = "dashboard.php";
            } else {
                alert("Invalid login credentials. Please try again.");
            }
        })
        .catch(error => {
            console.error('Error during login:', error);
        });
    }

    // Add event listener to handle Enter key press
    username.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            checkLogin();
        }
    });

    password.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            checkLogin();
        }
    });
</script>

</body>
</html>
