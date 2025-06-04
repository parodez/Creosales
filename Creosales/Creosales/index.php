<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include ('Backend/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creotec</title>
    <link rel="icon" href="assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: #ffffff;
            color: #2D1442;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin: auto;
            width: 100%;
            max-width: auto; 

        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            height: 12%;
            z-index: 1000;
            padding-top: 10px;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            transition: background 0.3s, backdrop-filter 0.3s;
        }

        .header.scrolled {
            background: rgb(255, 255, 255);
            backdrop-filter: blur(3px);
        }

        .logo {
            position: relative;
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 10px; /* Add spacing between the image and the text */
        }

        #logoImg {
            height: 2rem;
            position: relative; /* Change from absolute to relative */
            top: 0; /* Remove vertical alignment adjustments */
            transform: none; /* Remove transform */
        }

        .logo-text {
            color: #4729a6;
            white-space: nowrap; /* Prevent text wrapping */
        }

        nav {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .nav-links-center {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-grow: 1;
        }

        nav a {
            color: #2D1442;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s;
            background: rgba(0, 0, 0, 0);
        }

        nav a:hover {
            background: rgb(255, 255, 255);
        }

        .login-link {
            margin-right: 0%;
        }

        .hero {
            position: relative;
            width: calc(100% - 20px);
            margin: 0 auto;
            padding: 0 10px;
            box-sizing: border-box;
            margin-top: 1rem;
        }

        .hero-container {
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hero img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: fill;
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 2;
        }

        .hero h1 {
            font-size: 5rem;
            margin-bottom: 0.5rem;
            font-weight: bold;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.871);
            color: #ffffff;
            letter-spacing: 3.5rem;
        }

        .hero .read-more {
            text-align: center;
            text-transform: uppercase;
            color: #ffffff;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: underline;
            cursor: pointer;
        }

        .hero .read-more:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .about {
            width: calc(100% - 20px);
            margin: 0 auto;
            padding: 4rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            background: #ffffff;
        }

        .about-top {
            display: flex;
            flex-direction: row;
            gap: 2rem;
            align-items: flex-start;
        }

        .about-content {
            flex: 1;
            max-width: 50%;
        }

        .about-content h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #2D1442;
        }

        .about-content p {
            line-height: 1.6;
            margin-bottom: 1rem;
            color: #2D1442;
        }

        .about-image {
            flex: 1;
            max-width: 50%;
            border-radius: 10px;
            overflow: hidden;
        }

        .about-image video {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .footer {
            background-color: #4100bf;
            color: white;
            padding: 1rem;
            text-align: center;
            width: 100%;
            flex-shrink: 0;
        }

        .login-container, .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: white;
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            z-index: 2000;
            transition: transform 0.3s ease-out;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            flex-direction: column;
            align-items: center;
            width: 350px;
            max-width: 400px;
            height: auto;
            overflow: hidden;
        }

        .login-container.active, .modal.active {
            display: flex;
            transform: translate(-50%, -50%) scale(1);
        }

        .login-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1rem;
            width: 100%;
        }

        .login-header img {
            width: 150px;
            height: auto;
            position: relative;
        }

        .login-header h2 {
            font-size: 1.5rem;
            color: #2D1442;
            margin: 0.5rem 0;
        }

        .close-btn, .back-btn {
            cursor: pointer;
            background: transparent;
            border: none;
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: red;
            font-size: 1.5rem;
            transition: color 0.3s;
            position: absolute;
        }

        .close-btn {
            top: 1rem;
            right: 1rem;
        }

        .close-btn:hover, .back-btn:hover {
            color: darkred;
        }

        .back-btn {
            top: 1rem;
            left: 1rem;
            color: #2D1442;
        }

        .login-container form, .modal form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
        }

        .login-container input, .modal input {
            padding: 0.8rem;
            border: 1px solid #cccccc;
            border-radius: 8px;
            background: #f9f9f9;
            color: #2D1442;
            font-size: 1rem;
            width: 100%;
            transition: border-color 0.3s, background-color 0.3s;
        }

        .login-container button, .modal button {
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            background: #4100bf;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }

        .login-container button:hover, .modal button:hover {
            background: #2D1442;
        }

        .forgot-password {
            text-align: center;
            margin-top: 1rem;
            color: #4100bf;
            cursor: pointer;
            text-decoration: underline;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            color: #2D1442;
        }

        .resend-code {
            text-align: center;
            margin-top: 1rem;
            color: #4100bf;
            cursor: pointer;
            text-decoration: underline;
            font-size: 0.9rem;
        }

        .resend-code:hover {
            color: #2D1442;
        }
       
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .otp-input {
            width: 40px;
            height: 40px;
            border: 1px solid #cccccc;
            border-radius: 8px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #2D1442;
        }

        .otp-input:focus {
            border-color: #4100bf;
            outline: none;
        }

        .loading-spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            position: fixed; 
            top: 0;
            left: 0;
            z-index: 2001; 
            background: rgba(255, 255, 255, 0.8); 
        }

        .loading-spinner {
            width: 6rem;
            height: 6rem;
            border: 0.4rem solid rgba(0, 0, 0, 0.1);
            border-top: 0.5rem solid #2D1442;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            gap: 10px;
        }

        .toast {
            display: flex;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            width: 600; 
            height: 200; 
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.success {
            border-left: 4px solid #4CAF50;
        }

        .toast.error {
            border-left: 4px solid #F44336;
        }

        .toast.info {
            border-left: 4px solid #2196F3;
        }

        .toast.warning {
            border-left: 4px solid #FF9800;
        }

        .toast-icon {
            background-color: transparent;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .toast.success .toast-icon {
            color: #4CAF50;
        }

        .toast.error .toast-icon {
            color: #F44336;
        }

        .toast.info .toast-icon {
            color: #2196F3;
        }

        .toast.warning .toast-icon {
            color: #FF9800;
        }

        .toast-content {
            flex-grow: 1;
            display: flex;
            flex-direction: row;
        }

        .toast-title {
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .toast-message {
            font-size: 13px;
            color: #666;
        }

        .toast-close {
            background: transparent;
            border: none;
            color: #aaa;
            font-size: 16px;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-left: auto;
            transition: background-color 0.2s;
        }

        .toast-close:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .disabled-button {
            pointer-events: none;
            opacity: 0.6;
        }

        .error-input {
            border-color: red;
            background-color: rgba(255, 0, 0, 0.1);
        }

       
        .input-wrapper {
            position: relative;
        }
        .input-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2rem; 
                letter-spacing: 1rem;
            }
            .about-content h2 {
                font-size: 1.25rem;
            }
            .toast {
                width: 90%; 
            }
        }
        @media (max-width: 500px){
            .hero-content h1 {
                font-size: 1.5rem;
            }
        }

        /* Responsive Design */
        @media (max-width: 898px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .about-top {
                flex-direction: column;
                text-align: center;
            }

            .about-content, .about-image {
                max-width: 100%;
            }

            .about-content h2 {
                font-size: 1.5rem;
            }

            .header {
                padding: 0.5rem 1rem;
                margin-left: 3%;
                height: 8%;
            }
            #logoImg {
                height: 1.5rem;
            }
            .login-link {
                margin-right: 3%;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="assets/images/CreoSales-logo.png" alt="Creotec Logo" id="logoImg">
            <div class="logo-text" style="font-weight: bold; color: #4729a6; font-size: 20px;">
                <span class="logo-text-1">CREOSALES</span>
            </div>
        </div>
        <nav>
            <div class="nav-links-center">
            </div>
            <a href="#" id="loginButton" class="login-link">Login</a>
        </nav>
    </header>

    <div class="content">
        <section class="hero">
            <div class="hero-container">
                <img src="assets/images/CREOTEC BUILDING.jpg" alt="Creotec Building">
                <div class="hero-content">
                    <h1>WELCOME</h1>
                    <a href="#about" class="read-more">Read more &#x1F5B1;</a>
                </div>
            </div>
        </section>

        <section class="about" id="about">
            <div class="about-top">
                <div class="about-content">
                    <h2>About Us</h2>
                    <p>CREOTEC Philippines Inc., is a duly-established, wholly-owned Filipino corporation which aims to provide excellent, skills-based solutions and exposure to the electronics manufacturing industry with it being a member of EMS Group of Companies.</p>
                </div>
                <div class="about-image">
                    <video controls autoplay muted>
                        <source src="assets/images/Creovid.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </section>

        <div class="login-container" id="loginFormContainer">
            <div class="login-header">
                <img src="assets/images/CreoSales-logo.png" alt="Creotec Logo">
                <h2>Admin Login</h2>
                <div class="close-btn" onclick="closeAllModals()">×</div>
            </div>
            <form id="loginForm" method="POST" onsubmit="return validateLogin(event)">
                <div class="input-wrapper">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Password" required id="passwordField">
                    <i class="fas fa-eye toggle-password" id="togglePassword" style="display: none;"></i>
                </div>
                <button type="submit">Login</button>
                <div class="forgot-password" onclick="openForgotPasswordModal()">Forgot Password?</div>
            </form>
        </div>

        <div class="modal" id="dynamicModal">
            <div class="login-header">
                <div class="loading-spinner-container" id="loadingSpinnerContainer" style="display: none;">
                    <div class="loading-spinner"></div>
                </div>
                <img src="assets/images/CreoSales-logo.png" alt="Creotec Logo" id="dynamicLogo">
                <h2 id="modalTitle">Forgot Password</h2>
                <div class="close-btn" onclick="closeAllModals()">×</div>
                <div class="back-btn" onclick="backToLogin()"><i class="fas fa-arrow-left"></i></div>
            </div>
            <form id="dynamicForm">
                <div class="input-wrapper">
                    <input type="text" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <button type="button" id="sendCodeButton" onclick="sendVerificationCode()">Send Verification Code</button>
            </form>
        </div>

        <div class="modal" id="verificationModal">
            <div class="login-header">
                <img src="assets/images/CreoSales-logo.png" alt="Creotec Logo">
                <div class="back-btn" onclick="backToForgotPasswordModal()"><i class="fas fa-arrow-left"></i></div>
                <h2>Enter Verification Code</h2>
                <div class="close-btn" onclick="closeAllModals()">×</div>
            </div>
            <form id="verificationForm">
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" required oninput="handleOTPInput(event)">
                    <input type="text" class="otp-input" maxlength="1" required oninput="handleOTPInput(event)">
                    <input type="text" class="otp-input" maxlength="1" required oninput="handleOTPInput(event)">
                    <input type="text" class="otp-input" maxlength="1" required oninput="handleOTPInput(event)">
                    <input type="text" class="otp-input" maxlength="1" required oninput="handleOTPInput(event)">
                    <input type="text" class="otp-input" maxlength="1" required oninput="handleOTPInput(event)">
                </div>
                <button type="button" onclick="verifyCode()">Verify Code</button>
                <div class="resend-code" id="resendCodeVerification" style="display: none;">Didn't get the code? Resend</div>
            </form>
        </div>

        <div class="modal" id="resetPasswordModal">
            <div class="login-header">
                <div class="back-btn" onclick="backToDynamicModal()"><i class="fas fa-arrow-left"></i></div>
                <h2>Reset Password</h2>
                <div class="close-btn" onclick="closeAllModals()">×</div>
            </div>
            <form id="resetPasswordForm" onsubmit="return resetPassword(event)">
                <div class="input-wrapper">
                    <input type="password" name="newPassword" placeholder="Enter new password" required id="newPasswordField">
                    <i class="fas fa-eye toggle-password" id="toggleNewPassword" style="display: none;"></i>
                </div>
                <div class="input-wrapper">
                    <input type="password" name="confirmPassword" placeholder="Confirm new password" required id="confirmPasswordField">
                    <i class="fas fa-eye toggle-password" id="toggleConfirmPassword" style="display: none;"></i>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>© 2025 CREOTEC. All rights reserved.</p>
    </footer>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('loginButton').addEventListener('click', function() {
            document.getElementById('loginFormContainer').classList.add('active');
        });

        document.getElementById('passwordField').addEventListener('input', function() {
            const togglePassword = document.getElementById('togglePassword');
            if (this.value) {
                togglePassword.style.display = 'inline';
            } else {
                togglePassword.style.display = 'none';
            }
        });

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('passwordField');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });

        document.getElementById('newPasswordField').addEventListener('input', function() {
            const toggleNewPassword = document.getElementById('toggleNewPassword');
            if (this.value) {
                toggleNewPassword.style.display = 'inline';
            } else {
                toggleNewPassword.style.display = 'none';
            }
        });

        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            const newPasswordField = document.getElementById('newPasswordField');
            if (newPasswordField.type === 'password') {
                newPasswordField.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                newPasswordField.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });

        document.getElementById('confirmPasswordField').addEventListener('input', function() {
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            if (this.value) {
                toggleConfirmPassword.style.display = 'inline';
            } else {
                toggleConfirmPassword.style.display = 'none';
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirmPasswordField');
            if (confirmPasswordField.type === 'password') {
                confirmPasswordField.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                confirmPasswordField.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });

        function closeLoginForm() {
            document.getElementById('loginFormContainer').classList.remove('active');
        }

        function openForgotPasswordModal() {
            document.getElementById('loginFormContainer').classList.remove('active');
            document.getElementById('dynamicModal').classList.add('active');
        }

        function closeDynamicModal() {
            document.getElementById('dynamicModal').classList.remove('active');
        }

        function closeAllModals() {
            document.querySelectorAll('.modal').forEach(modal => modal.classList.remove('active'));
            document.getElementById('loginFormContainer').classList.remove('active');
            resetForms();
            clearSession();
        }

        function backToForgotPasswordModal() {
            document.getElementById('verificationModal').classList.remove('active');
            document.getElementById('dynamicModal').classList.add('active');
            document.getElementById('loadingSpinnerContainer').style.display = 'none';
            document.getElementById('dynamicLogo').style.display = 'block';
            document.getElementById('resendCode').style.display = 'none';
            document.getElementById('sendCodeButton').innerText = 'Send Verification Code';
            document.getElementById('sendCodeButton').disabled = false;
            document.getElementById('sendCodeButton').classList.remove('disabled-button');
        }

        function backToDynamicModal() {
            document.getElementById('resetPasswordModal').classList.remove('active');
            document.getElementById('verificationModal').classList.add('active');
        }

        function backToLogin() {
            document.getElementById('dynamicModal').classList.remove('active');
            document.getElementById('loginFormContainer').classList.add('active');
        }

        function resetForms() {
            document.querySelectorAll('form').forEach(form => form.reset());
            document.querySelectorAll('.input-wrapper').forEach(wrapper => wrapper.classList.remove('error-input'));
        }

        function clearSession() {
            fetch('Backend/LoginSystemBackend/clear_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                sessionStorage.clear();
                location.reload();
            });
        }

        function showToast(type, title, message) {
            const toastContainer = document.getElementById('toastContainer');

            // Create the toast elements
            const toast = document.createElement('div');
            toast.classList.add('toast', type);

            // Define icons based on type
            const iconClass = type === 'success' ? 'fa-check-circle' :
                             type === 'error' ? 'fa-times-circle' :
                             type === 'info' ? 'fa-info-circle' :
                             'fa-exclamation-circle';

            // Set the HTML content
            toast.innerHTML = `

                <div class="toast-content">
                    <div class="toast-icon">
                        <i class="fas ${iconClass}"></i>
                    </div>
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">: ${message}</div>
                </div>
            `;

            // Add to the container
            toastContainer.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Auto-close after 4 seconds
            setTimeout(() => {
                closeToast(toast);
            }, 4000);
        }

        function closeToast(toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        function validateLogin(event) {
            event.preventDefault();
            const form = document.getElementById('loginForm');
            const formData = new FormData(form);
            const username = formData.get('username');
            const password = formData.get('password');

            let isValid = true;

            if (!username) {
                isValid = false;
                showToast('warning', 'Warning', 'Please fill out the Username field');
                document.querySelector('#loginForm .input-wrapper:first-child').classList.add('error-input');
            } else {
                document.querySelector('#loginForm .input-wrapper:first-child').classList.remove('error-input');
            }

            if (!password) {
                isValid = false;
                showToast('warning', 'Warning', 'Please fill out the Password field');
                document.querySelector('#loginForm .input-wrapper:nth-child(2)').classList.add('error-input');
            } else {
                document.querySelector('#loginForm .input-wrapper:nth-child(2)').classList.remove('error-input');
            }

            if (isValid) {
                fetch('Backend/LoginSystemBackend/validate_login.php', {
                    method: 'POST',
                    body: JSON.stringify({ username, password }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'Frontend/dashboard/dashboard.php';
                    } else {
                        showToast('error', 'Error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred while validating the login.');
                });
            }
        }

        function sendVerificationCode() {
            const usernameField = document.querySelector('#dynamicForm .input-wrapper:first-child input');
            const emailField = document.querySelector('#dynamicForm .input-wrapper:nth-child(2) input');
            const username = usernameField.value;
            const email = emailField.value;

            usernameField.classList.remove('error-input');
            emailField.classList.remove('error-input');

            let isValid = true;

            if (!username) {
                isValid = false;
                showToast('warning', 'Warning', 'Please fill out the Username field');
                usernameField.classList.add('error-input');
            }

            if (!email) {
                isValid = false;
                showToast('warning', 'Warning', 'Please fill out the Email field');
                emailField.classList.add('error-input');
            }

            if (isValid) {
                document.getElementById('loadingSpinnerContainer').style.display = 'flex';
                document.getElementById('dynamicLogo').style.display = 'none';
                const sendCodeButton = document.getElementById('sendCodeButton');
                sendCodeButton.classList.add('disabled-button');
                sendCodeButton.disabled = true;

                fetch('Backend/LoginSystemBackend/send_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, email })
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            document.getElementById('dynamicModal').classList.remove('active');
                            document.getElementById('verificationModal').classList.add('active');
                            showToast('success', 'Success', 'Please check your email for the verification code.');
                            startCountdown();
                        } else {
                            console.error('Error:', data);
                            showToast('error', 'Error', data.message);
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error, 'Response text:', text);
                        showToast('error', 'Error', 'An error occurred while sending the verification code.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred while sending the verification code.');
                })
                .finally(() => {
                    document.getElementById('loadingSpinnerContainer').style.display = 'none';
                    document.getElementById('dynamicLogo').style.display = 'block';
                    if (!document.getElementById('verificationModal').classList.contains('active')) {
                        sendCodeButton.classList.remove('disabled-button');
                        sendCodeButton.disabled = false;
                    }
                });
            }
        }

        function startCountdown() {
            let timeLeft = 60; // 1 minute in seconds
            const countdownElement = document.getElementById('resendCodeVerification');
            countdownElement.innerText = 'Resend Code in ' + Math.floor(timeLeft / 60) + ':' + (timeLeft % 60 < 10 ? '0' : '') + (timeLeft % 60);
            countdownElement.disabled = true;
            countdownElement.classList.add('disabled-button');
            countdownElement.style.display = 'block';

            const countdownInterval = setInterval(() => {
                timeLeft--;
                countdownElement.innerText = 'Resend Code in ' + Math.floor(timeLeft / 60) + ':' + (timeLeft % 60 < 10 ? '0' : '') + (timeLeft % 60);

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerText = 'Didn\'t get the code? Resend';
                    countdownElement.disabled = false;
                    countdownElement.classList.remove('disabled-button');
                }
            }, 1000);
        }

        function resendCode() {
            const usernameField = document.querySelector('#dynamicForm .input-wrapper:first-child input');
            const emailField = document.querySelector('#dynamicForm .input-wrapper:nth-child(2) input');
            const username = usernameField.value;
            const email = emailField.value;

            usernameField.classList.remove('error-input');
            emailField.classList.remove('error-input');

            let isValid = true;

            if (!username) {
                isValid = false;
                showToast('warning', 'Warning', 'Please fill out the Username field');
                usernameField.classList.add('error-input');
            }

            if (!email) {
                isValid = false;
                showToast('warning', 'Warning', 'Please fill out the Email field');
                emailField.classList.add('error-input');
            }

            if (isValid) {
                document.getElementById('loadingSpinnerContainer').style.display = 'flex';
                document.getElementById('dynamicLogo').style.display = 'none';
                const resendCodeButton = document.getElementById('resendCodeVerification');
                resendCodeButton.classList.add('disabled-button');
                resendCodeButton.disabled = true;

                fetch('Backend/LoginSystemBackend/send_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, email })
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showToast('success', 'Success', 'Verification code resent successfully. Please check your email.');
                            startCountdown();
                        } else {
                            console.error('Error:', data);
                            showToast('error', 'Error', data.message);
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error, 'Response text:', text);
                        showToast('error', 'Error', 'An error occurred while resending the verification code.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred while resending the verification code.');
                })
                .finally(() => {
                    document.getElementById('loadingSpinnerContainer').style.display = 'none';
                    document.getElementById('dynamicLogo').style.display = 'block';
                    resendCodeButton.classList.remove('disabled-button');
                    resendCodeButton.disabled = false;
                });
            }
        }

        // Attach the resendCode function to the "Resend Code" button
        document.getElementById('resendCodeVerification').addEventListener('click', resendCode);

        function handleOTPInput(event) {
            const otpInputs = document.querySelectorAll('#verificationForm .otp-input');
            const currentIndex = Array.from(otpInputs).indexOf(event.target);

            if (event.inputType === 'deleteContentBackward' || event.inputType === 'deleteContentForward') {
                if (currentIndex > 0) {
                    otpInputs[currentIndex - 1].focus();
                }
            } else if (event.target.value) {
                if (currentIndex < otpInputs.length - 1) {
                    otpInputs[currentIndex + 1].focus();
                }
            }
        }

        function verifyCode() {
            const otpInputs = document.querySelectorAll('#verificationForm .otp-input');
            let code = '';

            otpInputs.forEach(input => {
                code += input.value;
            });

            if (code.length !== 6) {
                showToast('warning', 'Warning', 'Please fill out all the OTP fields');
                return;
            }

            fetch('Backend/LoginSystemBackend/verify_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('verificationModal').classList.remove('active');
                    document.getElementById('resetPasswordModal').classList.add('active');
                } else {
                    showToast('error', 'Error', 'Invalid verification code');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while verifying the code.');
            });
        }

        function resetPassword(event) {
            event.preventDefault(); // Prevent default form submission
            const newPasswordField = document.getElementById('newPasswordField');
            const confirmPasswordField = document.getElementById('confirmPasswordField');
            const newPassword = newPasswordField.value;
            const confirmPassword = confirmPasswordField.value;

            newPasswordField.classList.remove('error-input');
            confirmPasswordField.classList.remove('error-input');

            let showToastAlert = true; // Flag to ensure only one toast alert is shown

            if (!newPassword) {
                showToast('warning', 'Warning', 'Please fill out the New Password field');
                newPasswordField.classList.add('error-input');
                return;
            }

            if (newPassword !== confirmPassword && showToastAlert) {
                showToast('warning', 'Warning', 'Password do not match'); // Show only one toast alert
                newPasswordField.classList.add('error-input');
                confirmPasswordField.classList.add('error-input');
                showToastAlert = false; // Prevent further alerts
                return;
            }

            fetch('Backend/LoginSystemBackend/reset_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ newPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAllModals();
                    showToast('success', 'Success', 'Password reset successfully');
                    // Delay the reload to allow the toast to be visible
                    setTimeout(() => {
                        location.reload(); // Reload the page after the timer
                    }, 4000); // Timer set to 4 seconds (adjust as needed)
                } else {
                    showToast('error', 'Error', 'Failed to reset password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while resetting the password.');
            });
        }

        // Ensure the form does not reload the page
        document.getElementById('resetPasswordForm').addEventListener('submit', resetPassword);

        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>