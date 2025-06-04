<?php
require_once 'db_connect.php';

$registration_success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = trim($_POST['firstName'] ?? '');
    $middleName = trim($_POST['middleName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contactNumber = str_replace('-', '', trim($_POST['contactNumber'] ?? ''));
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($firstName)) $errors['firstName'] = 'First name is required';
    if (empty($lastName)) $errors['lastName'] = 'Last name is required';
    if (empty($username)) $errors['username'] = 'Username is required';
    if (empty($contactNumber)) {
        $errors['contactNumber'] = 'Contact number is required';
    } elseif (!preg_match('/^[0-9]{11}$/', $contactNumber)) {
        $errors['contactNumber'] = 'Contact number must be 11 digits';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    // Check if username already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
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
                <form id="registrationForm" class="space-y-6" method="POST" action="">
                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">Please fix the following issues:</span>
                            <ul class="mt-2 list-disc list-inside">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($registration_success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">Registration successful! Welcome to Caffè Lilio.</span>
                        </div>
                    <?php endif; ?>
                    <!-- Name Fields Row -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="input-group">
                            <input 
                                type="text" 
                                id="firstName" 
                                name="firstName"
                                class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                placeholder="First Name *"
                                required
                            >
                            <div class="field-feedback mt-2 text-sm font-baskerville hidden"></div>
                        </div>

                        <!-- Middle Name -->
                        <div class="input-group">
                            <input 
                                type="text" 
                                id="middleName" 
                                name="middleName"
                                class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                placeholder="Middle Name"
                            >
                        </div>
                    </div>

                    <!-- Last Name and Suffix Row -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Last Name -->
                        <div class="input-group md:col-span-2">
                            <input 
                                type="text" 
                                id="lastName" 
                                name="lastName"
                                class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                                placeholder="Last Name *"
                                required
                            >
                            <div class="field-feedback mt-2 text-sm font-baskerville hidden"></div>
                        </div>

                        <!-- Suffix -->
                        <div class="input-group">
                            <select 
                                id="suffix" 
                                name="suffix"
                                class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown appearance-none cursor-pointer"
                            >
                                <option value="" disabled selected>Suffix</option>
                                <option value="">None</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="input-group">
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Username *"
                            required
                        >
                        <div class="field-feedback mt-2 text-sm font-baskerville hidden"></div>
                    </div>
                    
                    <!-- Contact Number -->
                    <div class="input-group">
                        <input 
                            type="tel" 
                            id="contactNumber" 
                            name="contactNumber"
                            class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Contact Number *"
                            required
                        >
                        <div class="field-feedback mt-2 text-sm font-baskerville hidden"></div>
                    </div>
                    
                    <!-- Password -->
                    <div class="input-group">
                        <label for="password" class="floating-label">Password *</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-4 pr-12 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown peer"
                            required
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-stone-400 hover:text-rich-brown transition-colors duration-300 focus:outline-none"
                        >
                            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L12 12m-2.122-2.122L7.758 7.758M12 12l2.122-2.122m0 0L16.242 7.758M12 12l-2.122 2.122"></path>
                            </svg>
                        </button>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div id="passwordStrength" class="flex space-x-1 mb-2">
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                                <div class="h-1 flex-1 bg-stone-200 rounded"></div>
                            </div>
                            <div class="field-feedback text-sm font-baskerville hidden"></div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start space-x-3">
                        <input 
                            type="checkbox" 
                            id="termsAccepted" 
                            name="termsAccepted"
                            class="mt-1 w-5 h-5 text-rich-brown border-2 border-stone-300 rounded focus:ring-rich-brown focus:ring-2"
                            required
                        >
                        <label for="termsAccepted" class="font-baskerville text-warm-cream leading-relaxed">
                            I agree to the <a href="#" class="text-rich-brown hover:text-deep-brown underline transition-colors duration-300">Terms of Service</a> 
                            and <a href="#" class="text-rich-brown hover:text-deep-brown underline transition-colors duration-300">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full py-4 bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream rounded-xl font-baskerville font-bold text-lg btn-hover focus:outline-none focus:ring-4 focus:ring-rich-brown/30 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Create Account
                    </button>
                </form>

                <!-- Sign In Link -->
                <div class="text-center mt-8">
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
            const password = document.getElementById('password');
            const termsAccepted = document.getElementById('termsAccepted');

            if (!validateField(firstName) || !validateField(lastName) || 
                !validateField(username) || !validateField(contactNumber) || 
                !validateField(password)) {
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
            // Let the form submit normally since we have PHP handling it
            // The validation will still run to provide immediate feedback
            checkFormValidity();
            
        
            if (!document.getElementById('termsAccepted').checked) {
                e.preventDefault();
                alert('Please accept the terms and conditions.');
                return;
            }
        
            // Check if there are any validation errors
            const errorFields = document.querySelectorAll('.field-error');
            if (errorFields.length > 0) {
                e.preventDefault();
                alert('Please fix all errors before submitting.');
                return;
            }
        }

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
                // Only allow digits and limit to 11 characters
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                e.target.value = value;
                
                // Update validation
                validateField(e.target);
                checkFormValidity();
            });
                        
            // Username input - only allow alphanumeric and underscore
            document.getElementById('username').addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^A-Za-z0-9_]/g, '');
            });
            
            // Name inputs - only allow letters and spaces
            ['firstName', 'lastName', 'middleName'].forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('input', function(e) {
                        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
                    });
                }
            });
        });

        // Additional helper functions
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 font-baskerville ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
                'bg-blue-100 text-blue-800 border border-blue-200'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animate in
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.style.transition = 'transform 0.3s ease-out';
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Remove after 5 seconds
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
    </script>
</body>
</html>