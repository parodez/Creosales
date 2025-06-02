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

function openForgotPasswordModal() {
    document.getElementById('dynamicModal').classList.add('active');
}

function closeDynamicModal() {
    document.getElementById('dynamicModal').classList.remove('active');
}

function closeAllModals() {
    document.querySelectorAll('.modal').forEach(modal => modal.classList.remove('active'));
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
                window.location.href = 'dashboard.php';
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