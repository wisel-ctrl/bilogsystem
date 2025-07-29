<?php
session_start();
require_once 'db_connect.php';

$registration_success = false;
$errors = [];

// Initialize variables to preserve form inputs
$firstName = '';
$middleName = '';
$lastName = '';
$suffix = '';
$username = '';
$contactNumber = '';

function sendSemaphoreOTP($contactNumber, $otpCode) {
    $apiKey = '487b60aae3df89ca35dc3b4dd69e2518';
    $senderName = 'CaffeLilio';
    
    $url = 'https://api.semaphore.co/api/v4/messages';
    
    $data = [
        'apikey' => $apiKey,
        'number' => $contactNumber,
        'message' => "Your Caffè Lilio verification code is: $otpCode. This code expires in 5 minutes.",
        'sendername' => $senderName
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}

function isPhoneNumberExists($conn, $phoneNumber) {
    $stmt = $conn->prepare("SELECT id FROM users_tb WHERE contact_number = ?");
    $stmt->execute([$phoneNumber]);
    return $stmt->rowCount() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'send_otp') {
        $contactNumber = str_replace('-', '', trim($_POST['contactNumber'] ?? ''));
        
        // Validate contact number
        if (!preg_match('/^[0-9]{11}$/', $contactNumber) || !preg_match('/^09\d{9}$/', $contactNumber)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid contact number']);
            exit;
        }
        
        // Generate 6-digit OTP
        $otpCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = time() + 300; // 5 minutes from now
        
        // Store OTP in session
        $_SESSION['otp'] = [
            'contact_number' => $contactNumber,
            'code' => $otpCode,
            'expires_at' => $expiresAt
        ];
        
        // Send OTP via Semaphore
        if (sendSemaphoreOTP($contactNumber, $otpCode)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);
        } else {
            unset($_SESSION['otp']);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
        }
        exit;
    }
    
    // Registration processing
    $firstName = trim($_POST['firstName'] ?? '');
    $middleName = trim($_POST['middleName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contactNumber = str_replace('-', '', trim($_POST['contactNumber'] ?? ''));
    $password = $_POST['password'] ?? '';
    $otpCode = $_POST['otpCode'] ?? '';
    
    // Basic validation
    if (empty($firstName)) $errors['firstName'] = 'First name is required';
    if (empty($lastName)) $errors['lastName'] = 'Last name is required';
    if (empty($username)) $errors['username'] = 'Username is required';
    if (empty($contactNumber)) {
        $errors['contactNumber'] = 'Contact number is required';
    } elseif (!preg_match('/^[0-9]{11}$/', $contactNumber)) {
        $errors['contactNumber'] = 'Contact number must be 11 digits';
    } elseif (isPhoneNumberExists($conn, $contactNumber)) {
        $errors['contactNumber'] = 'Phone number already registered';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }
    if (empty($otpCode)) {
        $errors['otpCode'] = 'OTP code is required';
    }
    
    // Check if username already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }
    
    // Verify OTP
    if (empty($errors)) {
        if (!isset($_SESSION['otp']) || 
            $_SESSION['otp']['contact_number'] !== $contactNumber || 
            $_SESSION['otp']['code'] !== $otpCode || 
            $_SESSION['otp']['expires_at'] < time()) {
            $errors['otpCode'] = 'Invalid or expired OTP';
        } else {
            // Clear OTP from session after successful verification
            unset($_SESSION['otp']);
        }
    }
    
    // If no errors, register user
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $usertype = 3; // Customer account
            
            $stmt = $conn->prepare("INSERT INTO users_tb 
                (first_name, middle_name, last_name, suffix, username, contact_number, password, usertype) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $firstName,
                $middleName,
                $lastName,
                $suffix,
                $username,
                $contactNumber,
                $hashedPassword,
                $usertype
            ]);
            
            $registration_success = true;
        } catch(PDOException $e) {
            $errors['database'] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'check_phone') {
    $phoneNumber = str_replace('-', '', trim($_POST['phoneNumber'] ?? ''));
    
    if (!preg_match('/^[0-9]{11}$/', $phoneNumber) || !preg_match('/^09\d{9}$/', $phoneNumber)) {
        header('Content-Type: application/json');
        echo json_encode(['valid' => false, 'message' => 'Invalid contact number format']);
        exit;
    }
    
    $exists = isPhoneNumberExists($conn, $phoneNumber);
    header('Content-Type: application/json');
    echo json_encode(['valid' => !$exists, 'message' => $exists ? 'Phone number already registered' : 'Phone number available']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Caffè Lilio - Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-cream': '#E8E0D5',
                        'rich-brown': '#8B4513',
                        'deep-brown': '#5D2F0F',
                        'accent-brown': '#A0522D'
                    },
                    fontFamily: {
                        'serif': ['Georgia', 'serif'],
                        'script': ['Brush Script MT', 'cursive']
                    }
                }
            }
        }
    </script>
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
        
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            opacity: 0;
            transform: translateX(-30px);
            animation: slideIn 0.6s ease-out forwards;
        }
        
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .btn-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(139, 69, 19, 0.3);
        }
        
        .btn-hover:active {
            transform: translateY(0);
        }
        
        .field-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        
        .field-success {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }
        
        .otp-countdown {
            transition: all 0.3s ease;
        }
        
        .floating-label {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group input,
        .input-group select {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(93, 47, 15, 0.2);
            transition: all 0.3s ease;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .input-group input:focus,
        .input-group select:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }

        .input-group input::placeholder {
            color: rgba(93, 47, 15, 0.6);
            opacity: 1;
        }

        .input-group input:focus::placeholder {
            color: transparent;
        }

        body {
            background: #E8E0D5;
            min-height: 100vh;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-warm-cream via-amber-50 to-stone-100">

    <!-- Header -->
    <header class="relative z-10 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div>
                        <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
                        <p class="nav-subtitle text-xs text-warm-cream tracking-widest">RISTORANTE</p>
                    </div>
                </div>
                <a href="index.php" class="font-baskerville text-warm-cream hover:text-deep-brown transition-colors duration-300 slide-in" style="animation-delay: 0.2s;">
                    ← Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative py-12">
        <!-- Background image with blur -->
        <div class="fixed inset-0" style="z-index: -1;">
            <div class="absolute inset-0 bg-[url('images/bg4.jpg')] bg-cover bg-center bg-no-repeat blur-sm"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
        </div>

        <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Registration Card -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 md:p-12 fade-in">
                <!-- Header -->
                <div class="text-center mb-10">
                    <h2 class="font-playfair text-4xl md:text-5xl font-bold text-warm-cream mb-4">Join Our Family</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-6"></div>
                    <p class="font-baskerville text-lg text-warm-cream leading-relaxed">
                        Create your account to enjoy exclusive dining experiences and personalized service
                    </p>
                </div>

                <!-- Registration Form -->
                <form id="registrationForm" class="space-y-4" method="POST" action="">
                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-3 rounded-xl mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">Please fix the following issues:</span>
                            <ul class="mt-1 list-disc list-inside">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($registration_success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded-xl mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">Registration successful! Welcome to Caffè Lilio.</span>
                        </div>
                    <?php endif; ?>

                    <!-- Name Fields Row -->
                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-2">
                            <!-- First Name -->
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="firstName" 
                                    name="firstName"
                                    value="<?php echo htmlspecialchars($firstName); ?>"
                                    class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                    placeholder="First Name *"
                                    required
                                >
                                <div class="field-feedback mt-1 text-sm font-baskerville hidden"></div>
                            </div>

                            <!-- Middle Name -->
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="middleName" 
                                    name="middleName"
                                    value="<?php echo htmlspecialchars($middleName); ?>"
                                    class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                    placeholder="Middle Name"
                                >
                            </div>
                        </div>

                        <!-- Last Name and Suffix Row -->
                        <div class="grid md:grid-cols-3 gap-2">
                            <!-- Last Name -->
                            <div class="input-group md:col-span-2">
                                <input 
                                    type="text" 
                                    id="lastName" 
                                    name="lastName"
                                    value="<?php echo htmlspecialchars($lastName); ?>"
                                    class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                    placeholder="Last Name *"
                                    required
                                >
                                <div class="field-feedback mt-1 text-sm font-baskerville hidden"></div>
                            </div>

                            <!-- Suffix -->
                            <div class="input-group">
                                <select 
                                    id="suffix" 
                                    name="suffix"
                                    class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown appearance-none cursor-pointer"
                                >
                                    <option value="" <?php echo $suffix === '' ? 'selected' : ''; ?>>Suffix</option>
                                    <option value="" <?php echo $suffix === '' ? 'selected' : ''; ?>>None</option>
                                    <option value="Jr." <?php echo $suffix === 'Jr.' ? 'selected' : ''; ?>>Jr.</option>
                                    <option value="Sr." <?php echo $suffix === 'Sr.' ? 'selected' : ''; ?>>Sr.</option>
                                    <option value="II" <?php echo $suffix === 'II' ? 'selected' : ''; ?>>II</option>
                                    <option value="III" <?php echo $suffix === 'III' ? 'selected' : ''; ?>>III</option>
                                    <option value="IV" <?php echo $suffix === 'IV' ? 'selected' : ''; ?>>IV</option>
                                </select>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="input-group">
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            value="<?php echo htmlspecialchars($username); ?>"
                            class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Username *"
                            required
                        >
                        <div class="field-feedback mt-1 text-sm font-baskerville hidden"></div>
                    </div>
                    
                    <!-- Contact Number -->
                    <div class="input-group">
                        <div class="flex space-x-2">
                            <div class="flex-grow">
                                <input 
                                    type="tel" 
                                    id="contactNumber" 
                                    name="contactNumber"
                                    value="<?php echo htmlspecialchars($contactNumber); ?>"
                                    class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                    placeholder="Contact Number *"
                                    required
                                >
                            </div>
                            <button 
                                type="button" 
                                id="sendOtpBtn"
                                class="px-4 py-3 bg-rich-brown text-warm-cream rounded-xl font-baskerville btn-hover disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
                                disabled
                            >
                                Send OTP
                            </button>
                        </div>
                        <div class="field-feedback mt-1 text-sm font-baskerville hidden"></div>
                        <div id="otpCountdown" class="mt-2 text-sm font-baskerville text-rich-brown hidden"></div>
                    </div>
                    
                    <!-- OTP Code -->
                    <div class="input-group">
                        <input 
                            type="text" 
                            id="otpCode" 
                            name="otpCode"
                            class="w-full px-4 py-3 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Enter OTP Code *"
                            required
                        >
                        <div class="field-feedback mt-1 text-sm font-baskerville hidden"></div>
                    </div>
                    
                    <!-- Password -->
                    <div class="input-group">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-3 pr-10 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown peer"
                            placeholder="Password *"
                            required
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-rich-brown transition-colors duration-300 focus:outline-none"
                        >
                            <svg id="eyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L12 12m-2.122-2.122L7.758 7.758M12 12l2.122-2.122m0 0L16.242 7.758M12 12l-2.122 2.122"></path>
                            </svg>
                        </button>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div id="passwordStrength" class="flex space-x-1 mb-1">
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                            </div>
                            <div class="field-feedback text-sm font-baskerville hidden"></div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start space-x-3 mt-4">
                        <input 
                            type="checkbox" 
                            id="termsAccepted" 
                            name="termsAccepted"
                            class="mt-1 w-4 h-4 text-rich-brown border-2 border-stone-300 rounded focus:ring-rich-brown focus:ring-2"
                            required
                        >
                        <label for="termsAccepted" class="font-baskerville text-warm-cream leading-relaxed text-sm">
                            I agree to the <a href="#" class="text-rich-brown hover:text-deep-brown underline transition-colors duration-300">Terms of Service</a> 
                            and <a href="#" class="text-rich-brown hover:text-deep-brown underline transition-colors duration-300">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full py-3 mt-6 bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream rounded-xl font-baskerville font-bold text-lg btn-hover focus:outline-none focus:ring-4 focus:ring-rich-brown/30 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Create Account
                    </button>
                </form>

                <!-- Sign In Link -->
                <div class="text-center mt-6">
                    <p class="font-baskerville text-warm-cream">
                        Already have an account? 
                        <a href="login.php" class="text-rich-brown hover:text-accent-brown font-bold underline transition-colors duration-300">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Form elements
        const form = document.getElementById('registrationForm');
        const inputs = form.querySelectorAll('input[required], select[required]');
        const submitBtn = document.getElementById('submitBtn');
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const contactNumberInput = document.getElementById('contactNumber');
        const otpCodeInput = document.getElementById('otpCode');
        const otpCountdown = document.getElementById('otpCountdown');
        let countdownInterval;

        // Initialize floating labels
        function initFloatingLabels() {
            inputs.forEach(input => {
                const inputGroup = input.closest('.input-group');
                const label = inputGroup?.querySelector('.floating-label');
                
                if (input.value.trim() !== '') {
                    inputGroup?.classList.add('has-content');
                }
                
                input.addEventListener('input', () => {
                    if (input.value.trim() !== '') {
                        inputGroup?.classList.add('has-content');
                    } else {
                        inputGroup?.classList.remove('has-content');
                    }
                    validateField(input);
                    checkFormValidity();
                });
                
                input.addEventListener('blur', () => {
                    validateField(input);
                });
            });
        }

        // Validation functions
        function validateField(field) {
            const inputGroup = field.closest('.input-group');
            const feedback = inputGroup?.querySelector('.field-feedback');
            let isValid = true;
            let message = '';

            // Remove previous states
            field.classList.remove('field-error', 'field-success');
            feedback?.classList.add('hidden');

            switch (field.name) {
                case 'firstName':
                case 'lastName':
                    if (!field.value.trim()) {
                        isValid = false;
                        message = `${field.name === 'firstName' ? 'First' : 'Last'} name is required`;
                    } else if (field.value.trim().length < 2) {
                        isValid = false;
                        message = 'Name must be at least 2 characters';
                    } else if (!/^[A-Za-z\s]+$/.test(field.value.trim())) {
                        isValid = false;
                        message = 'Name can only contain letters and spaces';
                    }
                    break;

                case 'username':
                    if (!field.value.trim()) {
                        isValid = false;
                        message = 'Username is required';
                    } else if (field.value.trim().length < 3) {
                        isValid = false;
                        message = 'Username must be at least 3 characters';
                    } else if (!/^[A-Za-z0-9_]+$/.test(field.value.trim())) {
                        isValid = false;
                        message = 'Username can only contain letters, numbers, and underscores';
                    }
                    break;

                case 'contactNumber':
                    const cleanNumber = field.value.replace(/\D/g, '');
                    if (!cleanNumber) {
                        isValid = false;
                        message = 'Contact number is required';
                    } else if (cleanNumber.length !== 11) {
                        isValid = false;
                        message = 'Contact number must be 11 digits';
                    } else if (!/^09\d{9}$/.test(cleanNumber)) {
                        isValid = false;
                        message = 'Please enter a valid Philippine mobile number (09XXXXXXXXX)';
                    }
                    break;

                case 'otpCode':
                    if (!field.value.trim()) {
                        isValid = false;
                        message = 'OTP code is required';
                    } else if (!/^\d{6}$/.test(field.value.trim())) {
                        isValid = false;
                        message = 'OTP must be a 6-digit number';
                    }
                    break;

                case 'password':
                    const strength = checkPasswordStrength(field.value);
                    updatePasswordStrength(strength);
                    
                    if (!field.value.trim()) {
                        isValid = false;
                        message = 'Password is required';
                    } else if (field.value.length < 8) {
                        isValid = false;
                        message = 'Password must be at least 8 characters';
                    } else if (strength.score < 3) {
                        isValid = false;
                        message = 'Password is too weak. Include uppercase, lowercase, number, and special character';
                    }
                    break;
            }

            // Apply validation state
            if (field.value.trim() !== '') {
                if (isValid) {
                    field.classList.add('field-success');
                    if (message) {
                        feedback.textContent = message;
                        feedback.className = 'field-feedback mt-2 text-sm font-baskerville text-green-600';
                        feedback.classList.remove('hidden');
                    }
                } else {
                    field.classList.add('field-error');
                    feedback.textContent = message;
                    feedback.className = 'field-feedback mt-2 text-sm font-baskerville text-red-600';
                    feedback.classList.remove('hidden');
                }
            }

            return isValid;
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let score = 0;
            const checks = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            Object.values(checks).forEach(check => {
                if (check) score++;
            });

            return { score, checks };
        }

        // Update password strength indicator
        function updatePasswordStrength(strength) {
            const strengthBars = document.querySelectorAll('#passwordStrength > div');
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            
            strengthBars.forEach((bar, index) => {
                bar.className = 'h-1 flex-1 rounded';
                if (index < strength.score) {
                    bar.classList.add(colors[Math.min(strength.score - 1, 3)]);
                } else {
                    bar.classList.add('bg-stone-200');
                }
            });
        }

        // Check form validity
        function checkFormValidity() {
            let isValid = true;

            // Check required fields
            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    isValid = false;
                }
            });

            // Check specific validations
            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');
            const username = document.getElementById('username');
            const contactNumber = document.getElementById('contactNumber');
            const otpCode = document.getElementById('otpCode');
            const password = document.getElementById('password');
            const termsAccepted = document.getElementById('termsAccepted');

            if (!validateField(firstName) || !validateField(lastName) || 
                !validateField(username) || !validateField(contactNumber) || 
                !validateField(otpCode) || !validateField(password)) {
                isValid = false;
            }

            // Check terms acceptance
            if (!termsAccepted.checked) {
                isValid = false;
            }

            submitBtn.disabled = !isValid;
        }

        // Password toggle functionality
        function togglePasswordVisibility() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Form submission
        function handleFormSubmit(e) {
            checkFormValidity();
            
            if (!document.getElementById('termsAccepted').checked) {
                e.preventDefault();
                alert('Please accept the terms and conditions.');
                return;
            }
        
            const errorFields = document.querySelectorAll('.field-error');
            if (errorFields.length > 0) {
                e.preventDefault();
                alert('Please fix all errors before submitting.');
                return;
            }
        }

        // Enable/disable OTP button based on contact number validation
        contactNumberInput.addEventListener('input', function() {
            const cleanNumber = this.value.replace(/\D/g, '');
            sendOtpBtn.disabled = !(cleanNumber.length === 11 && /^09\d{9}$/.test(cleanNumber));
            validateField(this);
            checkFormValidity();
        });

        // OTP input validation
        otpCodeInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) {
                value = value.substring(0, 6);
            }
            e.target.value = value;
            validateField(e.target);
            checkFormValidity();
        });

        // Handle OTP button click
        sendOtpBtn.addEventListener('click', async function() {
            const contactNumber = contactNumberInput.value.replace(/\D/g, '');
            if (contactNumber.length === 11 && /^09\d{9}$/.test(contactNumber)) {
                try {
                    sendOtpBtn.disabled = true;
                    let timeLeft = 60;
                    
                    otpCountdown.textContent = `Resend OTP in ${timeLeft}s`;
                    otpCountdown.classList.remove('hidden');
                    
                    countdownInterval = setInterval(() => {
                        timeLeft--;
                        otpCountdown.textContent = `Resend OTP in ${timeLeft}s`;
                        
                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            sendOtpBtn.disabled = false;
                            otpCountdown.classList.add('hidden');
                        }
                    }, 1000);
                    
                    // Send OTP request to server
                    const response = await fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'action': 'send_otp',
                            'contactNumber': contactNumber
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('OTP sent successfully!', 'success');
                        otpCodeInput.focus();
                    } else {
                        showNotification(result.message, 'error');
                        clearInterval(countdownInterval);
                        sendOtpBtn.disabled = false;
                        otpCountdown.classList.add('hidden');
                    }
                } catch (error) {
                    showNotification('Failed to send OTP. Please try again.', 'error');
                    clearInterval(countdownInterval);
                    sendOtpBtn.disabled = false;
                    otpCountdown.classList.add('hidden');
                }
            }
        });

        // Clear countdown when leaving the page
        window.addEventListener('beforeunload', () => {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        });

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            initFloatingLabels();
            
            // Password toggle
            togglePassword.addEventListener('click', togglePasswordVisibility);
            
            // Terms checkbox
            document.getElementById('termsAccepted').addEventListener('change', checkFormValidity);
            
            // Form submission
            form.addEventListener('submit', handleFormSubmit);
            
            document.getElementById('contactNumber').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                e.target.value = value;
                validateField(e.target);
                checkFormValidity();
            });
                        
            ['firstName', 'lastName', 'middleName'].forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('input', function(e) {
                        let value = e.target.value;
                        if (value.length > 0) {
                            value = value.charAt(0).toUpperCase() + value.slice(1);
                        }
                        value = value.replace(/\s+/g, ' ');
                        value = value.replace(/[^A-Za-z\s]/g, '');
                        if (value.startsWith(' ')) {
                            value = value.trimStart();
                        }
                        if (value.length < 2 && value.includes(' ')) {
                            value = value.replace(/\s/g, '');
                        }
                        e.target.value = value;
                        validateField(e.target);
                        checkFormValidity();
                    });

                    field.addEventListener('paste', function(e) {
                        e.preventDefault();
                        let pastedText = (e.clipboardData || window.clipboardData).getData('text');
                        pastedText = pastedText.replace(/[^A-Za-z\s]/g, '');
                        pastedText = pastedText.replace(/\s+/g, ' ').trim();
                        if (pastedText.length > 0) {
                            pastedText = pastedText.charAt(0).toUpperCase() + pastedText.slice(1);
                        }
                        if (this.value.length < 2) {
                            pastedText = pastedText.replace(/\s/g, '');
                        }
                        this.value = pastedText;
                        validateField(this);
                        checkFormValidity();
                    });
                }
            });
            
            document.getElementById('username').addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/\s/g, '');
                value = value.replace(/[^A-Za-z0-9!@#$%^&*()_+=\-[\]{}|\\:;"'<>,.?/]/g, '');
                e.target.value = value;
                validateField(e.target);
                checkFormValidity();
            });

            document.getElementById('password').addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/\s/g, '');
                e.target.value = value;
                validateField(e.target);
                checkFormValidity();
            });

            document.getElementById('password').addEventListener('paste', function(e) {
                e.preventDefault();
                let pastedText = (e.clipboardData || window.clipboardData).getData('text');
                pastedText = pastedText.replace(/\s/g, '');
                this.value = pastedText;
                validateField(this);
                checkFormValidity();
            });
        });

        // Additional helper functions
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 font-baskerville ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
                'bg-blue-100 text-blue-800 border border-blue-200'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.style.transition = 'transform 0.3s ease-out';
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }

        // Form data collection helper
        function getFormData() {
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            return data;
        }
        
        // Add this function to your JavaScript section
async function checkPhoneNumberAvailability(phoneNumber) {
    try {
        const response = await fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'check_phone',
                'phoneNumber': phoneNumber
            })
        });
        
        return await response.json();
    } catch (error) {
        console.error('Error checking phone number:', error);
        return { valid: false, message: 'Error checking phone number' };
    }
}

// Update your contactNumberInput event listener
contactNumberInput.addEventListener('input', async function() {
    const cleanNumber = this.value.replace(/\D/g, '');
    const isValidFormat = cleanNumber.length === 11 && /^09\d{9}$/.test(cleanNumber);
    
    // Validate format first
    sendOtpBtn.disabled = !isValidFormat;
    validateField(this);
    
    // If format is valid, check availability
    if (isValidFormat) {
        const result = await checkPhoneNumberAvailability(cleanNumber);
        const inputGroup = this.closest('.input-group');
        const feedback = inputGroup.querySelector('.field-feedback');
        
        if (!result.valid) {
            this.classList.add('field-error');
            this.classList.remove('field-success');
            feedback.textContent = result.message;
            feedback.className = 'field-feedback mt-2 text-sm font-baskerville text-red-600';
            feedback.classList.remove('hidden');
            sendOtpBtn.disabled = true;
        } else {
            this.classList.add('field-success');
            this.classList.remove('field-error');
            feedback.textContent = result.message;
            feedback.className = 'field-feedback mt-2 text-sm font-baskerville text-green-600';
            feedback.classList.remove('hidden');
            sendOtpBtn.disabled = false;
        }
    }
    
    checkFormValidity();
});

// Add this helper function to your JavaScript
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Then modify your contactNumberInput event listener to use debounce:
contactNumberInput.addEventListener('input', debounce(async function() {
    const cleanNumber = this.value.replace(/\D/g, '');
    const isValidFormat = cleanNumber.length === 11 && /^09\d{9}$/.test(cleanNumber);
    
    // Validate format first
    sendOtpBtn.disabled = !isValidFormat;
    validateField(this);
    
    // If format is valid, check availability
    if (isValidFormat && cleanNumber.length === 11) {
        const result = await checkPhoneNumberAvailability(cleanNumber);
        const inputGroup = this.closest('.input-group');
        const feedback = inputGroup.querySelector('.field-feedback');
        
        if (!result.valid) {
            this.classList.add('field-error');
            this.classList.remove('field-success');
            feedback.textContent = result.message;
            feedback.className = 'field-feedback mt-2 text-sm font-baskerville text-red-600';
            feedback.classList.remove('hidden');
            sendOtpBtn.disabled = true;
        } else {
            this.classList.add('field-success');
            this.classList.remove('field-error');
            feedback.textContent = result.message;
            feedback.className = 'field-feedback mt-2 text-sm font-baskerville text-green-600';
            feedback.classList.remove('hidden');
            sendOtpBtn.disabled = false;
        }
    }
    
    checkFormValidity();
}, 500)); // 500ms delay
    </script>
</body>
</html>