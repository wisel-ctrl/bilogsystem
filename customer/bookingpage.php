<?php
require_once 'customer_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
</head>
<body class="bg-warm-cream text-deep-brown min-h-screen">
    <!-- Navigation -->
    <nav class="bg-warm-cream text-deep-brown shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex-1 flex items-center justify-start">
                    <a href="customerindex.php" class="flex items-center space-x-2 hover:opacity-90 transition-opacity" aria-label="Home">
                        <div>
                            <h1 class="font-playfair font-bold text-xl text-deep-brown">Caffè Lilio</h1>
                            <p class="text-xs tracking-widest text-deep-brown/90">RISTORANTE</p>
                        </div>
                    </a>
                </div>
                <!-- Desktop Navigation -->
                <div class="hidden md:flex flex-1 justify-center space-x-8">
                    <a href="customerindex.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Home
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <a href="customerindex.php#reservations" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        My Reservations
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <a href="menu.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Menu
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <a href="customerindex.php#contact" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Contact
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                </div>
                <div class="flex-1 flex items-center justify-end">
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-deep-brown hover:text-deep-brown/80 transition-colors duration-300" 
                            aria-label="Toggle menu"
                            id="mobile-menu-button">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>

                    <div class="hidden md:flex items-center space-x-0">
                        <!-- Notifications -->
                        <div class="relative group">
                            <button class="p-2 hover:bg-deep-brown/10 rounded-full transition-colors duration-300" 
                                    aria-label="Notifications"
                                    id="notifications-button">
                                <i class="fas fa-bell text-deep-brown"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div class="absolute right-0 mt-2 w-80 bg-card rounded-lg shadow-lg py-2 hidden group-hover:block border border-deep-brown/10 z-50">
                                <div class="px-4 py-2 border-b border-deep-brown/10">
                                    <h3 class="font-playfair font-bold text-deep-brown">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <!-- Notification items will be dynamically loaded -->
                                    <div class="animate-pulse p-4">
                                        <div class="skeleton-text w-3/4"></div>
                                        <div class="skeleton-text w-1/2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="relative group">
                            <a href="profile.php" class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                    aria-label="User menu"
                                    id="user-menu-button">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=E8E0D5&color=5D2F0F" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full border border-deep-brown/30">
                                <span class="font-baskerville">John Doe</span>
                                <i class="fas fa-chevron-down text-xs ml-2 transition-transform duration-300 group-hover:rotate-180"></i>
                            </a>
                            <div class="absolute right-0 mt-2 w-48 bg-warm-cream rounded-lg shadow-lg py-2 hidden group-hover:block border border-deep-brown/10 z-50 transition-all duration-300">
                                <a href="profile.php" class="flex items-center px-4 py-2 text-deep-brown hover:bg-rich-brown hover:text-warm-cream transition-colors duration-300">
                                    <i class="fas fa-user-circle w-5"></i>
                                    <span>Profile Settings</span>
                                </a>
                                <a href="#notifications" class="flex items-center px-4 py-2 text-deep-brown hover:bg-rich-brown hover:text-warm-cream transition-colors duration-300">
                                    <i class="fas fa-bell w-5"></i>
                                    <span>Notifications</span>
                                </a>
                                <hr class="my-2 border-deep-brown/20">
                                <a href="../logout.php" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-300">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden mobile-menu fixed inset-0 bg-warm-cream/95 z-40" id="mobile-menu">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-center p-4 border-b border-deep-brown/10">
                    <h2 class="font-playfair text-xl text-deep-brown">Menu</h2>
                    <button class="text-deep-brown hover:text-deep-brown/80 transition-colors duration-300" 
                            aria-label="Close menu"
                            id="close-mobile-menu">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto p-4">
                    <div class="space-y-4">
                        <a href="customerindex.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-home w-8"></i> Home
                        </a>
                        <a href="customerindex.php#reservations" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-calendar-alt w-8"></i> My Reservations
                        </a>
                        <a href="menu.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-utensils w-8"></i> Menu
                        </a>
                        <a href="customerindex.php#contact" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-envelope w-8"></i> Contact
                        </a>
                    </div>
                </nav>
                <div class="p-4 border-t border-warm-cream">
                    <a href="../logout.php" class="flex items-center text-red-600 hover:text-red-500 transition-colors duration-300">
                        <i class="fas fa-sign-out-alt w-8"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Loading Progress Bar -->
    <div id="nprogress-container"></div>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Menu Section -->
        <section class="mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <h2 class="font-playfair text-3xl sm:text-4xl font-bold text-deep-brown">Our Menu</h2>
                <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 flex items-center justify-center sm:justify-start space-x-2 p-2 rounded-lg hover:bg-deep-brown/5"
                        data-tippy-content="Refresh menu"
                        id="refresh-btn">
                    <i class="fas fa-sync-alt"></i>
                    <span class="font-baskerville text-sm">Refresh</span>
                </button>
            </div>
            
            <div class="bg-card rounded-xl p-4 sm:p-6 shadow-md">
                <!-- Loading State -->
                <div id="menu-loading" class="menu-grid">
                    <div class="loading-card skeleton">
                        <div class="loading-header skeleton"></div>
                        <div class="loading-text w-3/4 skeleton"></div>
                        <div class="loading-text w-1/2 skeleton"></div>
                        <div class="loading-text w-2/3 skeleton"></div>
                        <div class="loading-button skeleton"></div>
                    </div>
                    <div class="loading-card skeleton">
                        <div class="loading-header skeleton"></div>
                        <div class="loading-text w-4/5 skeleton"></div>
                        <div class="loading-text w-3/5 skeleton"></div>
                        <div class="loading-text w-3/4 skeleton"></div>
                        <div class="loading-button skeleton"></div>
                    </div>
                    <div class="loading-card skeleton">
                        <div class="loading-header skeleton"></div>
                        <div class="loading-text w-2/3 skeleton"></div>
                        <div class="loading-text w-4/5 skeleton"></div>
                        <div class="loading-text w-1/2 skeleton"></div>
                        <div class="loading-button skeleton"></div>
                    </div>
                </div>

                <!-- Menu Container -->
                <div id="menu-container" class="hidden menu-grid"></div>

                <!-- Empty State -->
                <div id="menu-empty" class="hidden text-center py-12">
                    <i class="fas fa-utensils text-6xl text-deep-brown/30 mb-4"></i>
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">No Menu Items Available</h3>
                    <p class="font-baskerville text-deep-brown/70 mb-4">Check back later for our delicious offerings.</p>
                    <button class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300"
                            onclick="loadMenu()">
                        Try Again
                    </button>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            tippy('[data-tippy-content]', {
                theme: 'custom',
                animation: 'scale',
                duration: [200, 150],
                placement: 'bottom'
            });

            // Initialize loading bar
            NProgress.configure({ 
                showSpinner: false,
                minimum: 0.3,
                easing: 'ease',
                speed: 500
            });

            // Mobile menu functionality
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            function toggleMobileMenu() {
                mobileMenu.classList.toggle('open');
                document.body.classList.toggle('overflow-hidden');
            }

            mobileMenuButton.addEventListener('click', toggleMobileMenu);
            closeMobileMenu.addEventListener('click', toggleMobileMenu);

            // Close mobile menu when clicking outside
            mobileMenu.addEventListener('click', function(e) {
                if (e.target === mobileMenu) {
                    toggleMobileMenu();
                }
            });

            // Show loading bar on navigation
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

                    // Simulate loading time
                    setTimeout(() => {
                        NProgress.done();
                    }, 500);
                });
            });

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                        <span class="font-baskerville">${message}</span>
                    </div>
                `;
                document.getElementById('toast-container').appendChild(toast);
                
                // Show toast
                setTimeout(() => toast.classList.add('show'), 100);
                
                // Remove toast
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Global function to show toast (accessible from other functions)
            window.showToast = showToast;

            // Fetch and display menu packages
            function loadMenu() {
                const menuContainer = document.getElementById('menu-container');
                const loadingContainer = document.getElementById('menu-loading');
                const emptyContainer = document.getElementById('menu-empty');

                // Show loading state
                loadingContainer.classList.remove('hidden');
                menuContainer.classList.add('hidden');
                emptyContainer.classList.add('hidden');

                fetch('customerindex/get_menu_packages.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.data && data.data.length > 0) {
                            loadingContainer.classList.add('hidden');
                            menuContainer.classList.remove('hidden');
                            menuContainer.innerHTML = '';
                            
                            data.data.forEach(package => {
                                const menuItem = document.createElement('div');
                                menuItem.className = 'menu-card bg-card rounded-xl shadow-md hover-lift group relative overflow-hidden';
                                menuItem.innerHTML = `
                                    <div class="package-badge">
                                        ${package.type || 'Package'}
                                    </div>
                                    <div class="menu-card-content p-6">
                                        <h4 class="font-playfair text-xl font-bold mb-3 text-deep-brown pr-20">
                                            ${package.package_name || 'Menu Item'}
                                        </h4>
                                        <p class="font-baskerville text-deep-brown/80 menu-card-description mb-4">
                                            ${package.package_description || 'Delicious menu item description.'}
                                        </p>
                                        <div class="menu-card-footer">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="price-display font-baskerville">
                                                    ₱${parseFloat(package.price || 0).toFixed(2)} <span class="text-sm">per pax</span>
                                                </span>
                                                <div class="text-sm text-deep-brown/60 font-baskerville">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Available
                                                </div>
                                            </div>
                                            <button class="reserve-btn btn-primary bg-rich-brown text-warm-cream rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 group"
                                                    onclick="showPackageDetails(${package.package_id})"
                                                    aria-label="Reserve ${package.package_name}">
                                                <span>Reserve Now</span>
                                                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                `;
                                menuContainer.appendChild(menuItem);
                            });
                        } else {
                            loadingContainer.classList.add('hidden');
                            emptyContainer.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching menu data:', error);
                        loadingContainer.classList.add('hidden');
                        emptyContainer.classList.remove('hidden');
                        showToast('Error loading menu. Please try again.', 'error');
                    })
                    .finally(() => {
                        NProgress.done();
                    });
            }

            function showPackageDetails(packageId) {
                // Fetch package details
                fetch('bookingAPI/get_package_dishes.php?package_id=' + packageId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.data && data.data.length > 0) {
                            // Group dishes by category in the order we want
                            const categoriesOrder = ['house-salad', 'spanish-dish', 'italian-dish', 'burgers', 'pizza', 'Pasta', 'pasta_caza', 'main-course', 'drinks', 'coffee', 'desserts'];
                            const dishesByCategory = {};
                            
                            // Initialize categories
                            categoriesOrder.forEach(category => {
                                dishesByCategory[category] = [];
                            });
                            
                            // Group dishes
                            data.data.forEach(dish => {
                                if (!dishesByCategory[dish.dish_category]) {
                                    dishesByCategory[dish.dish_category] = [];
                                }
                                dishesByCategory[dish.dish_category].push(dish);
                            });
                            
                            // Create modal content
                            let modalContent = `
                                <div class="package-details-modal">
                                    <h3 class="text-2xl font-playfair font-bold mb-4">${data.data[0].package_name}</h3>
                                    <p class="text-gray-700 mb-6">${data.data[0].package_description}</p>
                                    
                                    <div class="dishes-list">
                            `;
                            
                            // Add dishes by category
                            categoriesOrder.forEach(category => {
                                if (dishesByCategory[category] && dishesByCategory[category].length > 0) {
                                    modalContent += `
                                        <div class="dish-category mb-6">
                                            <h4 class="text-xl font-playfair font-semibold mb-3 capitalize">${category.replace('-', ' ')}</h4>
                                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    `;
                                    
                                    dishesByCategory[category].forEach(dish => {
                                        modalContent += `
                                            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span class="font-medium">${dish.dish_name}</span>
                                                <span class="text-sm text-gray-600">x${dish.quantity}</span>
                                            </li>
                                        `;
                                    });
                                    
                                    modalContent += `
                                            </ul>
                                        </div>
                                    `;
                                }
                            });
                            
                            modalContent += `
                                    </div>
                                    
                                    <div class="modal-actions flex justify-end gap-3 mt-6">
                                        <button onclick="closeModal()" class="btn-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                                            Cancel
                                        </button>
                                        <button onclick="showReservationForm('${packageId}')" class="btn-reserve px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition">
                                            Reserve Now
                                        </button>
                                    </div>
                                </div>
                            `;
                            
                            // Show modal
                            showModal('Package Details', modalContent);
                        } else {
                            showToast('Error loading package details.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching package details:', error);
                        showToast('Error loading package details.', 'error');
                    });
            }

            window.showPackageDetails = showPackageDetails; 

            function showReservationForm(packageId) {
                const formHtml = `
                    <div class="reservation-form">
                        <form id="reservationForm">
                            <div class="mb-4">
                                <label for="reservationDate" class="block text-gray-700 mb-2">Reservation Date</label>
                                <input type="datetime-local" id="reservationDate" name="reservationDate" 
                                    class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="numberOfPax" class="block text-gray-700 mb-2">Number of Pax</label>
                                <input type="number" id="numberOfPax" name="numberOfPax" min="1" 
                                    class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-gray-700 mb-2">Additional Notes</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-3 py-2 border rounded-lg" placeholder="Any special requests or additional information..."></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label for="paymentProof" class="block text-gray-700 mb-2">Downpayment Proof (Screenshot)</label>
                                <input type="file" id="paymentProof" name="paymentProof" 
                                    accept="image/*" class="w-full px-3 py-2 border rounded-lg" required>
                                <p class="text-sm text-gray-500 mt-1">Please upload a screenshot of your payment transaction.</p>
                            </div>
                            
                            <div class="modal-actions flex justify-end gap-3">
                                <button type="button" onclick="closeModal()" class="btn-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-reserve px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition">
                                    Confirm Reservation
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                
                // Show modal
                showModal('Complete Reservation', formHtml);
                
                // Add form submission handler
                document.getElementById('reservationForm')?.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitReservation(packageId);
                });
            }

            window.showReservationForm = showReservationForm;

            function showModal(title, content) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold mb-4">${title}</h2>
                            ${content}
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                document.body.style.overflow = 'hidden';
                
                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            function closeModal() {
                const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
                if (modal) {
                    document.body.removeChild(modal);
                    document.body.style.overflow = '';
                }
            }

            window.closeModal = closeModal;

            // Order item function
            function orderItem(itemName, price) {
                showToast(`Added "${itemName}" to your order!`, 'success');
                // Here you would typically add the item to cart or redirect to order page
                console.log(`Ordering: ${itemName} - ₱${price}`);
            }

            // Make functions global
            window.loadMenu = loadMenu;
            window.orderItem = orderItem;

            // Initialize menu loading
            NProgress.start();
            loadMenu();

            // Refresh button functionality
            document.getElementById('refresh-btn').addEventListener('click', () => {
                NProgress.start();
                const refreshIcon = document.querySelector('#refresh-btn i');
                refreshIcon.classList.add('fa-spin');
                
                loadMenu();
                
                setTimeout(() => {
                    refreshIcon.classList.remove('fa-spin');
                    showToast('Menu refreshed successfully!', 'success');
                }, 1000);
            });

            // Initialize dynamic content loading
            function loadNotifications() {
                const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
                if (notificationsContainer) {
                    setTimeout(() => {
                        notificationsContainer.innerHTML = `
                            <div class="p-4 border-b border-deep-brown/10">
                                <p class="font-baskerville text-deep-brown">New menu items available!</p>
                                <p class="text-sm text-deep-brown/60">Check out our latest dishes.</p>
                            </div>
                            <div class="p-4">
                                <p class="font-baskerville text-deep-brown">Reservation confirmed</p>
                                <p class="text-sm text-deep-brown/60">Your table is ready for tonight.</p>
                            </div>
                        `;
                    }, 1000);
                }
            }

            loadNotifications();

            // Add keyboard navigation support
            document.addEventListener('keydown', function(e) {
                // ESC key closes mobile menu
                if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                    toggleMobileMenu();
                }
            });

            // Add smooth scroll behavior for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
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

            // Add loading state management
            let isLoading = false;

            function setLoading(loading) {
                isLoading = loading;
                const refreshBtn = document.getElementById('refresh-btn');
                const refreshIcon = refreshBtn.querySelector('i');
                
                if (loading) {
                    refreshBtn.disabled = true;
                    refreshIcon.classList.add('fa-spin');
                    refreshBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    refreshBtn.disabled = false;
                    refreshIcon.classList.remove('fa-spin');
                    refreshBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Enhanced error handling
            function handleError(error, userMessage) {
                console.error('Error:', error);
                showToast(userMessage || 'Something went wrong. Please try again.', 'error');
                setLoading(false);
            }

            // Add intersection observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe menu cards for animation
            function observeMenuCards() {
                document.querySelectorAll('.menu-card').forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(card);
                });
            }

            // Update loadMenu function to include animations
            const originalLoadMenu = loadMenu;
            window.loadMenu = function() {
                setLoading(true);
                originalLoadMenu();
                
                // Add a small delay to ensure DOM is updated before observing
                setTimeout(() => {
                    observeMenuCards();
                    setLoading(false);
                }, 100);
            };

            // Add responsive image loading for future enhancements
            function loadImage(src, placeholder) {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.onload = () => resolve(src);
                    img.onerror = () => resolve(placeholder);
                    img.src = src;
                });
            }

            // Add touch gesture support for mobile
            let touchStartY = 0;
            let touchEndY = 0;

            document.addEventListener('touchstart', function(e) {
                touchStartY = e.changedTouches[0].screenY;
            });

            document.addEventListener('touchend', function(e) {
                touchEndY = e.changedTouches[0].screenY;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartY - touchEndY;
                
                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe up - could trigger refresh or other action
                        console.log('Swipe up detected');
                    } else {
                        // Swipe down - could trigger refresh
                        console.log('Swipe down detected');
                    }
                }
            }

            // Add performance monitoring
            function measurePerformance() {
                if (typeof performance !== 'undefined' && performance.mark) {
                    performance.mark('menu-load-start');
                    
                    // Measure when menu loading is complete
                    const originalDone = NProgress.done;
                    NProgress.done = function() {
                        originalDone.call(this);
                        performance.mark('menu-load-end');
                        performance.measure('menu-load', 'menu-load-start', 'menu-load-end');
                    };
                }
            }

            measurePerformance();

            // Add accessibility improvements
            function enhanceAccessibility() {
                // Add skip to main content link
                const skipLink = document.createElement('a');
                skipLink.href = '#main-content';
                skipLink.textContent = 'Skip to main content';
                skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-rich-brown text-warm-cream px-4 py-2 rounded z-50';
                document.body.insertBefore(skipLink, document.body.firstChild);

                // Add main landmark
                const main = document.querySelector('main');
                if (main) {
                    main.id = 'main-content';
                    main.setAttribute('role', 'main');
                }

                // Enhance button accessibility
                document.querySelectorAll('button').forEach(button => {
                    if (!button.getAttribute('aria-label') && !button.textContent.trim()) {
                        button.setAttribute('aria-label', 'Button');
                    }
                });
            }

            enhanceAccessibility();

            // Add print styles support
            function addPrintSupport() {
                const printStyles = document.createElement('style');
                printStyles.textContent = `
                    @media print {
                        .mobile-menu, nav, #toast-container, #nprogress-container {
                            display: none !important;
                        }
                        
                        .menu-card {
                            break-inside: avoid;
                            margin-bottom: 1rem;
                        }
                        
                        .hover-lift:hover {
                            transform: none;
                            box-shadow: none;
                        }
                        
                        body {
                            background: white !important;
                            color: black !important;
                        }
                    }
                `;
                document.head.appendChild(printStyles);
            }

            addPrintSupport();

            console.log('Menu page initialized successfully');
        });
    </script>
</body>
</html>