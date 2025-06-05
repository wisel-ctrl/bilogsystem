<?php
require_once 'db_connect.php';
session_start();

// If user is already logged in, redirect them to appropriate page
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['usertype']) {
        case 1:
            header("Location: admin/admin_dashboard.php");
            break;
        case 2:
            header("Location: cashier/cashierindex.php");
            break;
        case 3:
            header("Location: customer/customerindex.php");
            break;
        default:
            // Invalid usertype - destroy session
            session_destroy();
    }
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $login_error = 'Please enter both username and password';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users_tb WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['usertype'] = $user['usertype'];
                
                // Redirect based on usertype
                if ($user['usertype'] == 3) {
                    header("Location: customer/customerindex.php");
                } elseif ($user['usertype'] == 2) {
                    header("Location: cashier/cashierindex.php");
                } elseif ($user['usertype'] == 1) {
                    header("Location: admin/admin_dashboard.php");
                }
                exit();
            } else {
                $login_error = 'Invalid username or password';
            }
        } catch(PDOException $e) {
            $login_error = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back to Caffè Lilio - Login</title>
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
        
        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group input {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(93, 47, 15, 0.2);
            transition: all 0.3s ease;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .input-group input:focus {
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

        <div class="relative max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Login Card -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 md:p-12 fade-in">
                <!-- Header -->
                <div class="text-center mb-10">
                    <h2 class="font-playfair text-4xl md:text-5xl font-bold text-warm-cream mb-4">Welcome Back</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-6"></div>
                    <p class="font-baskerville text-lg text-warm-cream leading-relaxed">
                        Sign in to your account to continue your Caffè Lilio experience
                    </p>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-6" method="POST" action="">
                    <?php if (!empty($login_error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline"><?php echo htmlspecialchars($login_error); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Username -->
                    <div class="input-group">
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            class="w-full px-4 py-4 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Username *"
                            required
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        >
                    </div>
                    
                    <!-- Password -->
                    <div class="input-group">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-4 pr-12 bg-white border-2 border-stone-200 rounded-xl font-baskerville text-deep-brown input-focus focus:outline-none focus:border-rich-brown"
                            placeholder="Password *"
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
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="rememberMe" 
                                name="rememberMe"
                                class="w-4 h-4 text-rich-brown border-2 border-stone-300 rounded focus:ring-rich-brown focus:ring-2"
                            >
                            <label for="rememberMe" class="ml-2 font-baskerville text-warm-cream">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="font-baskerville text-rich-brown hover:text-accent-brown underline transition-colors duration-300">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream rounded-xl font-baskerville font-bold text-lg btn-hover focus:outline-none focus:ring-4 focus:ring-rich-brown/30"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="text-center mt-8">
                    <p class="font-baskerville text-warm-cream">
                        Don't have an account? 
                        <a href="register.php" class="text-rich-brown hover:text-accent-brown font-bold underline transition-colors duration-300">
                            Register Now
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

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

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            togglePassword.addEventListener('click', togglePasswordVisibility);
            
            // Form validation
            const form = document.getElementById('loginForm');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Simple validation
                if (!username.value.trim()) {
                    username.classList.add('field-error');
                    isValid = false;
                } else {
                    username.classList.remove('field-error');
                }
                
                if (!password.value.trim()) {
                    password.classList.add('field-error');
                    isValid = false;
                } else {
                    password.classList.remove('field-error');
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // Clear error state when user starts typing
            username.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('field-error');
                }
            });
            
            password.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('field-error');
                }
            });
        });
    </script>
</body>
</html>