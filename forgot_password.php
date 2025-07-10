<?php
require_once 'db_connect.php';
session_start();

// API Configuration
define('SMS_API_KEY', '487b60aae3df89ca35dc3b4dd69e2518');
define('SMS_SENDER_NAME', 'CaffeLilio');

// Function to generate OTP
function generateOTP($length = 6) {
    return str_pad(rand(0, pow(10, $length)-1), $length, '0', STR_PAD_LEFT);
}

// Function to send SMS via API
function sendSMS($number, $message) {
    $url = 'https://api.semaphore.co/api/v4/messages';
    $data = [
        'apikey' => SMS_API_KEY,
        'number' => $number,
        'message' => $message,
        'sendername' => SMS_SENDER_NAME
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return $result !== false;
}

$error = '';
$success = '';
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Step 1: Verify phone number
        $phone = trim($_POST['phone']);
        
        if (empty($phone)) {
            $error = 'Please enter your phone number';
        } else {
            // Check if phone exists in database
            $stmt = $conn->prepare("SELECT id FROM users_tb WHERE contact_number = ?");
            $stmt->execute([$phone]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Generate and store OTP
                $otp = generateOTP();
                $_SESSION['reset_phone'] = $phone;
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_attempts'] = 0;
                $_SESSION['otp_expiry'] = time() + 300; // 5 minutes expiry
                
                // Send OTP via SMS
                $message = "Your Caffè Lilio password reset OTP is: $otp";
                if (sendSMS($phone, $message)) {
                    header("Location: forgot_password.php?step=2");
                    exit();
                } else {
                    $error = 'Failed to send OTP. Please try again.';
                }
            } else {
                $error = 'Phone number not found in our system';
            }
        }
    } elseif ($step === 2) {
        // Step 2: Verify OTP
        $otp = trim($_POST['otp']);
        
        if (empty($otp)) {
            $error = 'Please enter the OTP';
        } elseif (!isset($_SESSION['reset_otp']) || !isset($_SESSION['otp_expiry'])) {
            $error = 'OTP session expired. Please start again.';
            session_unset();
            session_destroy();
            header("Location: forgot_password.php");
            exit();
        } elseif (time() > $_SESSION['otp_expiry']) {
            $error = 'OTP has expired. Please request a new one.';
            unset($_SESSION['reset_otp']);
            unset($_SESSION['otp_expiry']);
        } elseif ($otp !== $_SESSION['reset_otp']) {
            $_SESSION['reset_attempts']++;
            $attempts_left = 3 - $_SESSION['reset_attempts'];
            
            if ($_SESSION['reset_attempts'] >= 3) {
                $error = 'Too many incorrect attempts. Please start again.';
                session_unset();
                session_destroy();
                header("Location: forgot_password.php");
                exit();
            } else {
                $error = "Incorrect OTP. $attempts_left attempts left.";
            }
        } else {
            // OTP verified, proceed to password reset
            header("Location: forgot_password.php?step=3");
            exit();
        }
    } elseif ($step === 3) {
        // Step 3: Reset password
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        if (empty($password) || empty($confirm_password)) {
            $error = 'Please enter and confirm your new password';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters long';
        } elseif (!isset($_SESSION['reset_phone'])) {
            $error = 'Session expired. Please start again.';
            session_unset();
            session_destroy();
            header("Location: forgot_password.php");
            exit();
        } else {
            // Update password in database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users_tb SET password = ? WHERE contact_number = ?");
            $result = $stmt->execute([$hashed_password, $_SESSION['reset_phone']]);
            
            if ($result) {
                $success = 'Password reset successfully! You can now login with your new password.';
                session_unset();
                session_destroy();
            } else {
                $error = 'Failed to reset password. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(73, 72, 72, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .input-focus {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.15);
        }
        
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transform: translateX(200%);
            transition: transform 0.3s ease-in-out;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.error {
            background-color: #ef4444;
        }
        
        .toast.success {
            background-color: #10b981;
        }
        
        .back-to-login {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
            background: rgba(73, 72, 72, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
        .back-to-login:hover {
            background: rgba(73, 72, 72, 0.7);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-warm-cream via-amber-50 to-stone-100">

    <!-- Back to Login - Fixed Position -->
    <div class="back-to-login">
        <a href="login.php" class="font-baskerville text-warm-cream hover:text-accent-brown transition-colors duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Login
        </a>
    </div>

    <!-- Toast Notification -->
    <?php if ($error): ?>
        <div id="toast" class="toast error">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
        </div>
    <?php elseif ($success): ?>
        <div id="toast" class="toast success">
            <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <!-- Background image with blur -->
    <div class="fixed inset-0" style="z-index: -1;">
        <div class="absolute inset-0 bg-[url('images/bg4.jpg')] bg-cover bg-center bg-no-repeat blur-sm"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="glass-effect rounded-3xl shadow-2xl p-8 md:p-12 w-full max-w-md">
            <div class="text-center mb-8">
                <h2 class="font-playfair text-3xl font-bold text-warm-cream mb-2">Reset Password</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-4"></div>
                <p class="font-baskerville text-warm-cream">
                    <?php 
                    if ($step === 1) {
                        echo "Enter your registered phone number to receive an OTP";
                    } elseif ($step === 2) {
                        echo "Enter the OTP sent to your phone";
                    } elseif ($step === 3) {
                        echo "Set your new password";
                    }
                    ?>
                </p>
            </div>

            <?php if ($step === 1): ?>
                <!-- Step 1: Phone Number Verification -->
                <form method="POST" action="forgot_password.php?step=1" class="space-y-6">
                    <div class="input-group">
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone"
                            class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Phone Number *"
                            required
                            value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                        >
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream rounded-xl font-baskerville font-bold text-lg btn-hover focus:outline-none focus:ring-4 focus:ring-rich-brown/30"
                    >
                        Send OTP
                    </button>
                </form>
            
            <?php elseif ($step === 2): ?>
                <!-- Step 2: OTP Verification -->
                <form method="POST" action="forgot_password.php?step=2" class="space-y-6">
                    <div class="input-group">
                        <input 
                            type="text" 
                            id="otp" 
                            name="otp"
                            class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Enter OTP *"
                            required
                            maxlength="6"
                            pattern="\d{6}"
                            title="Please enter the 6-digit OTP"
                        >
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream rounded-xl font-baskerville font-bold text-lg btn-hover focus:outline-none focus:ring-4 focus:ring-rich-brown/30"
                    >
                        Verify OTP
                    </button>
                </form>
            
            <?php elseif ($step === 3): ?>
                <!-- Step 3: Password Reset -->
                <form method="POST" action="forgot_password.php?step=3" class="space-y-6" id="resetForm">
                    <div class="input-group relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-4 pr-12 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="New Password *"
                            required
                            minlength="8"
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-rich-brown transition-colors duration-300 focus:outline-none"
                        >
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <div class="input-group relative">
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password"
                            class="w-full px-4 py-4 pr-12 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Confirm New Password *"
                            required
                            minlength="8"
                        >
                        <button 
                            type="button" 
                            id="toggleConfirmPassword"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-rich-brown transition-colors duration-300 focus:outline-none"
                        >
                            <i id="confirmEyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream rounded-xl font-baskerville font-bold text-lg btn-hover focus:outline-none focus:ring-4 focus:ring-rich-brown/30"
                    >
                        Reset Password
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Show toast notification
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
            
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const password = document.getElementById('password');
                    const eyeIcon = document.getElementById('eyeIcon');
                    
                    if (password.type === 'password') {
                        password.type = 'text';
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        password.type = 'password';
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }
            
            // Toggle confirm password visibility
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            if (toggleConfirmPassword) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const confirmPassword = document.getElementById('confirm_password');
                    const confirmEyeIcon = document.getElementById('confirmEyeIcon');
                    
                    if (confirmPassword.type === 'password') {
                        confirmPassword.type = 'text';
                        confirmEyeIcon.classList.remove('fa-eye');
                        confirmEyeIcon.classList.add('fa-eye-slash');
                    } else {
                        confirmPassword.type = 'password';
                        confirmEyeIcon.classList.remove('fa-eye-slash');
                        confirmEyeIcon.classList.add('fa-eye');
                    }
                });
            }
            
            // Password validation
            const resetForm = document.getElementById('resetForm');
            if (resetForm) {
                resetForm.addEventListener('submit', function(e) {
                    const password = document.getElementById('password');
                    const confirmPassword = document.getElementById('confirm_password');
                    
                    if (password.value !== confirmPassword.value) {
                        e.preventDefault();
                        alert('Passwords do not match!');
                        return false;
                    }
                    
                    if (password.value.length < 8) {
                        e.preventDefault();
                        alert('Password must be at least 8 characters long!');
                        return false;
                    }
                    
                    return true;
                });
            }
        });
    </script>
</body>
</html>