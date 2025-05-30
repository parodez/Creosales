<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creotec</title>
    <link rel="icon" href="assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login_design.css">
</head>
<body>

    <div class="content">
        <section class="hero">
            <div class="hero-container">
                <img src="assets/images/CREOTEC BUILDING.jpg" alt="Creotec Building">
            </div>
        </section>

        <div class="login-container" id="loginFormContainer">
            <div class="login-header">
                <img src="assets/images/CreoSales-logo.png" alt="Creotec Logo">
                <h2 class="logo-text">CREOSALES</h2>
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
                <div class="close-btn" onclick="closeDynamicModal()">×</div>
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
    <script src="assets/js/login.js"></script>
</body>
</html>