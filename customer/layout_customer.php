<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Caffè Lilio'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>
    <!-- Add loading animation library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <!-- Add tooltip library -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
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
                        'playfair': ['Playfair Display', serif],
                        'baskerville': ['Libre Baskerville', serif]
                    }
                }
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        /* Enhanced hover effect */
        .hover-lift {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        /* Improved background gradients */
        .bg-warm-gradient {
            background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
        }

        .bg-card {
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            backdrop-filter: blur(8px);
        }

        .bg-nav {
            background: linear-gradient(to bottom, #5D2F0F, #4a260d);
        }

        /* Menu card improvements */
        .menu-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 400px;
        }

        .menu-card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-card-description {
            flex: 1;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5;
            min-height: 4.5em; /* 3 lines * 1.5 line-height */
        }

        .menu-card-footer {
            margin-top: auto;
            padding-top: 1rem;
        }

        /* Consistent button sizing */
        .order-btn {
            width: 100%;
            min-height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Loading skeleton animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Accessibility improvements */
        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #E8E0D5;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }

        /* Toast notification styles */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            background: #E8E0D5;
            box-shadow: 0 4px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Button animations */
        .btn-primary {
            position: relative;
            overflow: hidden;
            background: #8B4513;
            color: #E8E0D5;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(232, 224, 213, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:active::after {
            width: 200%;
            height: 200%;
        }

        /* Improved mobile menu */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        /* Skeleton loading placeholder */
        .skeleton-text {
            height: 1em;
            background: #e0e0e0;
            margin: 0.5em 0;
            border-radius: 4px;
        }

        /* Improved form inputs */
        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }

        /* Price display */
        .price-display {
            font-weight: bold;
            font-size: 1.25rem;
            color: #5D2F0F;
        }

        /* Package type badge */
        .package-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #5D2F0F;
            color: #E8E0D5;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Responsive grid improvements */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 640px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .menu-card {
                min-height: 350px;
            }
            
            .package-badge {
                top: 0.75rem;
                right: 0.75rem;
                font-size: 0.625rem;
                padding: 0.125rem 0.5rem;
            }
        }

        /* Loading states */
        .loading-card {
            min-height: 400px;
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .loading-header {
            height: 1.5rem;
            background: #d4c8b9;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .loading-text {
            height: 1rem;
            background: #d4c8b9;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .loading-button {
            height: 3rem;
            background: #d4c8b9;
            border-radius: 0.5rem;
            margin-top: auto;
        }
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        /* Enhanced hover effect */
        .hover-lift {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        /* Improved background gradients */
        .bg-warm-gradient {
            background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
        }

        .bg-card {
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            backdrop-filter: blur(8px);
        }

        .bg-nav {
            background: linear-gradient(to bottom, #5D2F0F, #4a260d);
        }

        /* Menu card improvements */
        .menu-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 400px;
        }

        .menu-card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-card-description {
            flex: 1;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5;
            min-height: 4.5em; /* 3 lines * 1.5 line-height */
        }

        .menu-card-footer {
            margin-top: auto;
            padding-top: 1rem;
        }

        /* Consistent button sizing */
        .order-btn {
            width: 100%;
            min-height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Loading skeleton animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Accessibility improvements */
        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #E8E0D5;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }

        /* Toast notification styles */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            background: #E8E0D5;
            box-shadow: 0 4px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Button animations */
        .btn-primary {
            position: relative;
            overflow: hidden;
            background: #8B4513;
            color: #E8E0D5;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(232, 224, 213, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:active::after {
            width: 200%;
            height: 200%;
        }

        /* Improved mobile menu */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        /* Skeleton loading placeholder */
        .skeleton-text {
            height: 1em;
            background: #e0e0e0;
            margin: 0.5em 0;
            border-radius: 4px;
        }

        /* Improved form inputs */
        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }

        /* Price display */
        .price-display {
            font-weight: bold;
            font-size: 1.25rem;
            color: #5D2F0F;
        }

        /* Package type badge */
        .package-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #5D2F0F;
            color: #E8E0D5;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Responsive grid improvements */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 640px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .menu-card {
                min-height: 350px;
            }
            
            .package-badge {
                top: 0.75rem;
                right: 0.75rem;
                font-size: 0.625rem;
                padding: 0.125rem 0.5rem;
            }
        }

        /* Loading states */
        .loading-card {
            min-height: 400px;
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .loading-header {
            height: 1.5rem;
            background: #d4c8b9;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .loading-text {
            height: 1rem;
            background: #d4c8b9;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .loading-button {
            height: 3rem;
            background: #d4c8b9;
            border-radius: 0.5rem;
            margin-top: auto;
        }
    </style>
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
                        'playfair': ['Playfair Display', serif],
                        'baskerville': ['Libre Baskerville', serif]
                    }
                }
            }
        }
    </script>
 
   
</head>
<body class="bg-warm-cream/50 text-deep-brown min-h-screen">
    <?php include 'nav.php'; ?>

    <!-- Loading Progress Bar -->
    <div id="nprogress-container"></div>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-deep-brown text-warm-cream relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-8 left-8 w-32 h-32 border border-warm-cream rounded-full"></div>
            <div class="absolute bottom-12 right-12 w-24 h-24 border border-warm-cream rounded-full"></div>
            <div class="absolute top-1/2 left-1/4 w-2 h-2 bg-warm-cream rounded-full"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-warm-cream rounded-full"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            <div class="py-2">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center space-x-3 mt-4 mb-4">
                        <div>
                            <h2 class="font-playfair font-bold text-2xl tracking-tight">Caffè Lilio</h2>
                            <p class="text-xs tracking-[0.2em] text-warm-cream/80 uppercase font-inter font-light">Ristorante</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Contact
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="space-y-3 font-inter text-sm">
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-map-marker-alt text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">123 Restaurant St., Food District</p>
                            </div>
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-phone text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">+63 912 345 6789</p>
                            </div>
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-envelope text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">info@caffelilio.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Navigate
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <nav class="space-y-2 font-inter text-sm">
                            <a href="#about" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">About Us</a>
                            <a href="menu.php" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Our Menu</a>
                            <a href="my_reservations.php" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Reservations</a>
                            <a href="#contact" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Visit Us</a>
                        </nav>
                    </div>
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Hours
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="space-y-2 font-inter text-sm">
                            <div class="flex justify-between">
                                <span class="text-warm-cream/90">Mon - Fri</span>
                                <span class="text-warm-cream">11AM - 10PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-warm-cream/90">Sat - Sun</span>
                                <span class="text-warm-cream">10AM - 11PM</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Connect
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="flex space-x-3 mb-4">
                            <a href="https://web.facebook.com/caffelilio.ph" target="_blank" 
                               class="w-10 h-10 bg-warm-cream/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-warm-cream/20 hover:bg-warm-cream/20 transition-colors">
                                <i class="fab fa-facebook-f text-warm-cream text-sm"></i>
                            </a>
                            <a href="https://www.instagram.com/caffelilio.ph/" target="_blank" 
                               class="w-10 h-10 bg-warm-cream/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-warm-cream/20 hover:bg-warm-cream/20 transition-colors">
                                <i class="fab fa-instagram text-warm-cream text-sm"></i>
                            </a>
                        </div>
                        <div class="space-y-2">
                            <p class="text-warm-cream/80 text-xs font-inter">Stay updated</p>
                            <div class="flex">
                                <input type="email" placeholder="Email" 
                                       class="flex-1 px-3 py-2 bg-warm-cream/10 border border-warm-cream/20 rounded-l text-sm text-warm-cream placeholder-warm-cream/50 focus:outline-none focus:border-warm-cream/40 backdrop-blur-sm">
                                <button class="px-3 py-2 bg-warm-cream/20 border border-warm-cream/20 border-l-0 rounded-r hover:bg-warm-cream/30 transition-colors backdrop-blur-sm">
                                    <i class="fas fa-arrow-right text-warm-cream text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-warm-cream/10 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <p class="font-inter text-warm-cream/70 text-xs">
                        © 2024 Caffè Lilio Ristorante. All rights reserved.
                    </p>
                    <div class="flex space-x-4 text-xs font-inter">
                        <a href="#privacy" class="text-warm-cream/70 hover:text-warm-cream transition-colors">Privacy</a>
                        <a href="#terms" class="text-warm-cream/70 hover:text-warm-cream transition-colors">Terms</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tippy('[data-tippy-content]', {
                theme: 'custom',
                animation: 'scale',
                duration: [200, 150],
                placement: 'bottom'
            });

            NProgress.configure({ 
                showSpinner: false,
                minimum: 0.3,
                easing: 'ease',
                speed: 500
            });

            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            function toggleMobileMenu() {
                mobileMenu.classList.toggle('open');
                document.body.classList.toggle('overflow-hidden');
            }

            mobileMenuButton.addEventListener('click', toggleMobileMenu);
            closeMobileMenu.addEventListener('click', toggleMobileMenu);

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    NProgress.start();

                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }

                    setTimeout(() => {
                        NProgress.done();
                    }, 500);
                });
            });

            document.querySelectorAll('a[href$=".php"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    NProgress.start();
                });
            });

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.getElementById('toast-container').appendChild(toast);
                
                setTimeout(() => toast.classList.add('show'), 100);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            document.querySelectorAll('.fa-trash').forEach(button => {
                button.addEventListener('click', function() {
                    const confirmDialog = document.createElement('div');
                    confirmDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                    confirmDialog.innerHTML = `
                        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                            <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Cancel Reservation?</h3>
                            <p class="text-deep-brown/80 mb-6">Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                            <div class="flex justify-end space-x-4">
                                <button class="px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300" id="cancel-dialog">
                                    Keep Reservation
                                </button>
                                <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors duration-300" id="confirm-cancel">
                                    Yes, Cancel
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(confirmDialog);
                    document.body.classList.add('overflow-hidden');

                    document.getElementById('cancel-dialog').addEventListener('click', () => {
                        confirmDialog.remove();
                        document.body.classList.remove('overflow-hidden');
                    });

                    document.getElementById('confirm-cancel').addEventListener('click', () => {
                        NProgress.start();
                        setTimeout(() => {
                            confirmDialog.remove();
                            document.body.classList.remove('overflow-hidden');
                            showToast('Reservation cancelled successfully');
                            NProgress.done();
                        }, 1000);
                    });
                });
            });

            document.querySelectorAll('.fa-edit').forEach(button => {
                button.addEventListener('click', function() {
                    showToast('Opening reservation editor...', 'success');
                    NProgress.start();
                    
                    setTimeout(() => {
                        NProgress.done();
                    }, 1000);
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            function loadNotifications() {
                const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
                setTimeout(() => {
                    notificationsContainer.innerHTML = `
                        <div class="p-4 border-b border-deep-brown/10">
                            <p class="font-baskerville text-deep-brown">New special offer available!</p>
                            <p class="text-sm text-deep-brown/60">Check out our weekend buffet special.</p>
                        </div>
                    `;
                }, 1000);
            }

            loadNotifications();
        });
    </script>
    
</body>
</html>