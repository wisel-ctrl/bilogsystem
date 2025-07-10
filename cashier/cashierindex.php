<?php 
require_once 'cashier_auth.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    'playfair': ['Playfair Display', 'serif'],
                    'baskerville': ['Libre Baskerville', 'serif']
                }
            }
        }
    }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
        
        /* Improved hover effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(93, 47, 15, 0.15);
        }
        
        /* Card styles */
        .pos-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(232, 224, 213, 0.5);
            box-shadow: 0 4px 6px rgba(93, 47, 15, 0.1);
            transition: all 0.3s ease;
        }
        
        .pos-card:hover {
            box-shadow: 0 8px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(-2px);
        }

        /* Menu item card styles */
        .menu-item-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .menu-item-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(93, 47, 15, 0.15);
        }

        .menu-item-card img {
            transition: transform 0.3s ease;
        }

        .menu-item-card:hover img {
            transform: scale(1.05);
        }

        /* Cart styles */
        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: rgba(232, 224, 213, 0.2);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
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

        /* Animation classes */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal styles */
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Category button styles */
        .category-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .category-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #E8E0D5;
            transition: width 0.3s ease;
        }
        
        .category-btn:hover::after {
            width: 100%;
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #printSection, #printSection * {
                visibility: visible;
            }
            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }

        /* Improved Sidebar styles */
        #sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            transition: width 0.3s ease-in-out;
            position: relative;
            z-index: 40;
        }

        #sidebar.collapsed {
            width: 4rem !important;
        }

        #sidebar .sidebar-header {
            flex-shrink: 0;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(232, 224, 213, 0.2);
        }

        #sidebar.collapsed .sidebar-header {
            padding: 1.5rem 0.75rem;
        }

        #sidebar nav {
            flex: 1;
            overflow-y: auto;
            padding-right: 4px;
        }

        .category-btn {
            position: relative;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        #sidebar.collapsed .category-btn {
            padding: 0.75rem !important;
            justify-content: center;
        }

        #sidebar.collapsed .category-btn i {
            margin: 0 !important;
        }

        #sidebar.collapsed .sidebar-text,
        #sidebar.collapsed .nav-title,
        #sidebar.collapsed .nav-subtitle,
        #sidebar.collapsed h2 {
            display: none;
        }

        .category-btn .tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #5D2F0F;
            color: #E8E0D5;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 50;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            pointer-events: none;
        }

        .category-btn .tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #5D2F0F;
        }

        #sidebar.collapsed .category-btn:hover .tooltip {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 0.5rem);
        }

        /* Active state for category buttons */
        .category-btn.active {
            background: rgba(232, 224, 213, 0.2) !important;
            color: #E8E0D5 !important;
        }

        /* Improved hover effects */
        .category-btn:hover {
            background: rgba(232, 224, 213, 0.15) !important;
        }

        #sidebar.collapsed .category-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-warm-cream/50 font-baskerville min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown via-rich-brown to-accent-brown text-warm-cream transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="sidebar-header p-6 border-b border-warm-cream/20">
                <div>
                    <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
                    <p class="nav-subtitle text-xs text-warm-cream tracking-widest">Ristorante</p>
                </div>
            </div>
            
            <nav class="px-4">
                <h2 class="text-xl font-bold text-warm-cream mb-4 font-playfair">Categories</h2>
                <ul class="space-y-2">
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 bg-warm-cream/10 text-warm-cream rounded-lg hover:bg-warm-cream/20 transition-all duration-200 flex items-center space-x-3" data-category="all">
                            <i class="fas fa-th-large w-5"></i>
                            <span class="sidebar-text font-baskerville">All Items</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="main-course">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Main Course</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="italian-dish">
                            <i class="fas fa-pizza-slice w-5"></i>
                            <span class="sidebar-text font-baskerville">Italian Dishes</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="spanish-dish">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Spanish Dishes</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="house-salad">
                            <i class="fas fa-leaf w-5"></i>
                            <span class="sidebar-text font-baskerville">House Salads</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="burger-pizza">
                            <i class="fas fa-circle-notch w-5"></i>
                            <span class="sidebar-text font-baskerville">Burgers & Pizza</span>
                        </button>
                    </li>
           
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="pasta">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Pasta</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="pasta_caza">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Pasta e Caza</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="desserts">
                            <i class="fas fa-ice-cream w-5"></i>
                            <span class="sidebar-text font-baskerville">Desserts</span>
                        </button>
                    </li>
                    <!-- <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="main">
                            <i class="fas fa-drumstick-bite w-5"></i>
                            <span class="sidebar-text font-baskerville">Main Courses</span>
                        </button>
                    </li> -->
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="drinks">
                            <i class="fas fa-glass-martini-alt w-5"></i>
                            <span class="sidebar-text font-baskerville">Drinks</span>
                        </button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-4 py-3 hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream rounded-lg transition-all duration-200 flex items-center space-x-3" data-category="coffee">
                            <i class="fas fa-coffee w-5"></i>
                            <span class="sidebar-text font-baskerville">Coffee</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
        <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-amber-100/20 px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button id="sidebar-toggle" class="text-amber-900 hover:text-amber-800 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="hidden sm:block text-xl sm:text-2xl font-bold text-amber-900 font-serif">Point-of-Sale</h2>
                </div>
                <div class="text-sm text-amber-800 font-serif flex-1 text-center mx-4 hidden sm:flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span id="current-date"></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../logout.php?usertype=cashier" class="flex items-center space-x-2 hover:bg-amber-100/10 p-2 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-sm font-medium text-amber-900 font-serif">Sign Out</span>
                    </a>
                </div>
            </div>
        </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Menu Items Section -->
                    <div class="w-full lg:w-3/5">
                        <div class="pos-card rounded-xl p-6 mb-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Menu Items</h3>
                                <div class="relative w-1/2">
                                    <input type="text" id="menu-search" placeholder="Search menu items..." 
                                        class="w-full p-3 pl-10 pr-10 border border-rich-brown/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-brown bg-white/50 backdrop-blur-sm">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-rich-brown/50"></i>
                                    <button id="clear-search" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-rich-brown/50 hover:text-rich-brown hidden">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="menu-items">
                                <!-- Menu items will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>

               <!-- Cart Section -->
               <div class="w-full lg:w-2/5">
                    <div class="pos-card rounded-xl p-4 sm:p-6 sticky top-4 sm:top-6 max-h-[calc(100vh-100px)] sm:max-h-[calc(100vh-120px)] overflow-y-auto">
                        <!-- Reduced padding, adjusted max-h for mobile, added overflow-y-auto for scrollable cart -->
                        <h3 class="text-xl sm:text-2xl font-bold text-deep-brown mb-4 sm:mb-6 font-playfair">
                            <!-- Reduced font size and margin -->
                            Order Summary
                        </h3>
                        <div class="bg-warm-cream/20 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                            <!-- Reduced padding and margin -->
                            <div id="cart-items" class="space-y-3 sm:space-y-4">
                                <!-- Reduced spacing; removed max-h to allow natural scrolling -->
                                <!-- Cart items will be dynamically inserted here -->
                                <p class="text-center text-rich-brown/60 py-3 sm:py-4 text-xs sm:text-sm">Your cart is empty</p>
                                <!-- Adjusted text size -->
                            </div>
                        </div>
                        
                        <div class="bg-warm-cream/20 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                            <!-- Reduced padding and margin -->
                            <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                <!-- Reduced spacing and text size -->
                                <div class="flex justify-between py-1 sm:py-2 border-b border-rich-brown/20">
                                    <!-- Reduced padding -->
                                    <span class="font-bold text-deep-brown">Subtotal:</span>
                                    <span id="subtotal" class="font-bold text-deep-brown">₱0.00</span>
                                </div>
                                <div class="flex justify-between py-1 sm:py-2 border-b border-rich-brown/20">
                                    <span class="font-bold text-deep-brown">Tax (10%):</span>
                                    <span id="tax" class="font-bold text-deep-brown">₱0.00</span>
                                </div>
                                <div class="flex justify-between py-1 sm:py-2 font-bold text-sm sm:text-lg">
                                    <!-- Adjusted font size -->
                                    <span class="text-deep-brown">Total:</span>
                                    <span id="total" class="text-deep-brown">₱0.00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 sm:space-x-3">
                            <!-- Reduced spacing -->
                            <button id="clear-cart" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 sm:py-3 px-3 sm:px-4 rounded-lg transition-colors duration-300 font-baskerville flex items-center justify-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                                <!-- Reduced padding, spacing, and text size -->
                                <i class="fas fa-trash-alt"></i>
                                <span>Clear</span>
                            </button>
                            <button id="checkout" class="flex-1 bg-deep-brown hover:bg-rich-brown text-warm-cream py-2 sm:py-3 px-3 sm:px-4 rounded-lg transition-colors duration-300 font-baskerville flex items-center justify-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                                <!-- Reduced padding, spacing, and text size -->
                                <i class="fas fa-cash-register"></i>
                                <span>Checkout</span>
                            </button>
                        </div>
                    </div>
                </div>
                </div>
            </main>
        </div>
    </div>

        <!-- Discount Modal -->
        <div id="discount-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50">
            <div class="modal-content bg-white rounded-xl shadow-2xl w-[480px] max-w-[90vw] mx-4 sm:max-w-[85vw] md:max-w-[600px] lg:max-w-[480px]">
                <!-- Added sm:max-w-[85vw] and md:max-w-[600px] for better width scaling on small laptops and tablets -->
                <div class="p-4 sm:p-6">
                    <!-- Reduced padding on smaller screens with p-4 and restored p-6 for sm and up -->
                    <h3 class="text-xl sm:text-2xl font-bold text-deep-brown mb-4 sm:mb-6 font-playfair">
                        <!-- Adjusted text size to text-xl for smaller screens, text-2xl for sm and up; reduced margin -->
                        Payment Details
                    </h3>
                    
                    <!-- Discount Options -->
                    <div class="space-y-2 sm:space-y-3 mb-3 sm:mb-4">
                        <!-- Reduced vertical spacing (space-y-2 from 3, mb-3 from 4) for compactness; restored for sm -->
                        <h4 class="font-semibold text-deep-brown text-xs sm:text-sm font-baskerville">
                            <!-- Reduced font size to text-xs (from text-sm) for smaller screens; restored to text-sm for sm -->
                            Discount Type:
                        </h4>
                        <div class="grid grid-cols-3 gap-1 sm:gap-2">
                            <!-- Reduced grid gap to 1 (from 2) for tighter layout; restored to 2 for sm -->
                            <label class="relative">
                                <input type="radio" id="none" name="discount" value="none" checked class="peer sr-only">
                                <div class="p-1 sm:p-2 border border-rich-brown/20 rounded-md text-center cursor-pointer peer-checked:bg-deep-brown peer-checked:text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
                                    <!-- Reduced padding to p-1 (from p-2) and rounded-md (from rounded-lg) for smaller, sharper look -->
                                    <i class="fas fa-times-circle mb-0.5 sm:mb-1 text-sm sm:text-base"></i>
                                    <!-- Reduced icon size to text-sm (from text-base) and margin to mb-0.5; restored for sm -->
                                    <div class="text-[0.65rem] sm:text-xs font-baskerville">None</div>
                                    <!-- Reduced font size to 0.65rem (from text-xs) for compactness; restored to text-xs for sm -->
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" id="senior" name="discount" value="senior" class="peer sr-only">
                                <div class="p-1 sm:p-2 border border-rich-brown/20 rounded-md text-center cursor-pointer peer-checked:bg-deep-brown peer-checked:text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
                                    <i class="fas fa-user-tag mb-0.5 sm:mb-1 text-sm sm:text-base"></i>
                                    <div class="text-[0.65rem] sm:text-xs font-baskerville">Senior (20%)</div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" id="pwd" name="discount" value="PWD" class="peer sr-only">
                                <div class="p-1 sm:p-2 border border-rich-brown/20 rounded-md text-center cursor-pointer peer-checked:bg-deep-brown peer-checked:text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
                                    <i class="fas fa-wheelchair mb-0.5 sm:mb-1 text-sm sm:text-base"></i>
                                    <div class="text-[0.65rem] sm:text-xs font-baskerville">PWD (20%)</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <!-- Payment Amount Input -->
                    <div class="mb-3 sm:mb-4 flex items-center gap-2 sm:gap-3">
                        <!-- Reduced margin to mb-3 (from mb-4); used flex and gap for inline layout -->
                        <label for="payment-amount" class="font-semibold text-deep-brown text-xs sm:text-sm font-baskerville whitespace-nowrap">
                            <!-- Reduced font size to text-xs (from text-sm); added whitespace-nowrap to prevent wrapping -->
                            Amount Paid:
                        </label>
                        <div class="relative flex-grow">
                            <!-- Added flex-grow to make input take remaining space -->
                            <span class="absolute left-2 sm:left-2 top-1/2 transform -translate-y-1/2 text-rich-brown/50 text-xs sm:text-sm">
                                <!-- Reduced text size to text-xs (from text-sm); kept left-2 for consistency -->
                                ₱
                            </span>
                            <input type="number" id="payment-amount" class="w-full p-1.5 sm:p-2 pl-5 sm:pl-6 border border-rich-brown/20 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown text-xs sm:text-sm" min="0" step="0.01">
                            <!-- Reduced padding to p-1.5 (from p-2), pl-5 (from pl-6), used rounded-md (from rounded-lg), and text-xs (from text-sm) -->
                        </div>
                        <p id="payment-error" class="text-red-500 text-[0.65rem] sm:text-xs mt-1 sm:mt-1 hidden"></p>
                        <!-- Reduced text size to 0.65rem (from text-xs) and margin to mt-1 -->
                    </div>
                    
                    <!-- Summary Display -->
                    <div class="bg-warm-cream/20 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-deep-brown mb-3 font-baskerville">Payment Summary:</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-rich-brown">Subtotal:</span>
                                    <span id="summary-subtotal" class="font-medium">₱0.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-rich-brown">Tax (10%):</span>
                                    <span id="summary-tax" class="font-medium">₱0.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-rich-brown">Discount:</span>
                                    <span id="summary-discount" class="font-medium">₱0.00</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-rich-brown/20">
                                    <span class="font-bold text-deep-brown">Total:</span>
                                    <span id="summary-total" class="font-bold text-deep-brown">₱0.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-rich-brown">Amount Paid:</span>
                                    <span id="summary-paid" class="font-medium">₱0.00</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-rich-brown/20">
                                    <span class="font-bold text-deep-brown">Change:</span>
                                    <span id="summary-change" class="font-bold text-deep-brown">₱0.00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button id="cancel-discount" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-300 font-baskerville">
                                Cancel
                            </button>
                            <button id="apply-discount" class="px-6 py-3 bg-deep-brown hover:bg-rich-brown text-warm-cream rounded-lg transition-colors duration-300 font-baskerville">
                                Complete Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="modal-content bg-white rounded-xl shadow-2xl w-[320px] max-w-[90vw] mx-4 p-4 sm:p-6">
        <!-- Compact modal size, responsive padding -->
        <div class="text-center">
        <i class="fas fa-check-circle text-5xl sm:text-6xl text-amber-600 mb-4 sm:mb-6"></i>
        <!-- Success image with responsive sizing -->
            <h3 class="text-lg sm:text-xl font-bold text-deep-brown mb-4 font-playfair">Order Successful!</h3>
            <!-- Reduced font size for mobile -->
            <p class="text-xs sm:text-sm text-rich-brown/70 font-baskerville mb-4 sm:mb-6">Your order has been placed successfully.</p>
            <!-- Reduced text size and margin -->
            <button id="close-success" class="px-4 sm:px-6 py-2 sm:py-3 bg-deep-brown hover:bg-rich-brown text-warm-cream rounded-lg transition-colors duration-300 font-baskerville text-xs sm:text-sm">
                <!-- Responsive button sizing -->
                Close
            </button>
        </div>
    </div>
</div>




    <script>
        // Sample menu data
        let menuItems = [];
        let currentCategory = 'all'; 

        async function fetchMenuItems() {
            try {
                const response = await fetch('posFunctions/get_menu.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                menuItems = await response.json();
                renderMenuItems('all'); // Render all items by default
            } catch (error) {
                console.error('Error fetching menu items:', error);
                // You might want to show an error message to the user here
            }
        }

        // Cart state
        let cart = [];

        // DOM elements
        const menuItemsContainer = document.getElementById('menu-items');
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalElement = document.getElementById('subtotal');
        const taxElement = document.getElementById('tax');
        const totalElement = document.getElementById('total');
        const clearCartBtn = document.getElementById('clear-cart');
        const checkoutBtn = document.getElementById('checkout');
        const categoryBtns = document.querySelectorAll('.category-btn');

        // Initialize the page
        function init() {
            fetchMenuItems(); // This will now fetch from the server
            setupEventListeners();
        }

        // Render menu items based on category
        function renderMenuItems(category, searchTerm = '') {
            menuItemsContainer.innerHTML = '';
            currentCategory = category;
            
            const filteredItems = (category === 'all' 
                ? menuItems 
                : menuItems.filter(item => 
                    item.category.trim().toLowerCase() === category.trim().toLowerCase()
                ))
                .filter(item => {
                    if (!searchTerm) return true;
                    const term = searchTerm.toLowerCase();
                    return (
                        item.name.toLowerCase().includes(term) || 
                        item.description.toLowerCase().includes(term))
                });
            
            if (filteredItems.length === 0) {
                menuItemsContainer.innerHTML = `
                    <div class="col-span-2 text-center py-12">
                        <i class="fas fa-search text-4xl text-rich-brown/30 mb-4"></i>
                        <p class="text-rich-brown/60 font-baskerville">No items found</p>
                    </div>`;
                return;
            }
            
            filteredItems.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'menu-item-card fade-in cursor-pointer';
                itemElement.dataset.id = item.id; // Add item ID to the card
                itemElement.innerHTML = `
                    <div class="relative overflow-hidden group">
                        <img src="${item.image}" alt="${item.name}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"></div>
                        <div class="absolute top-3 right-3">
                            <button class="add-to-cart bg-deep-brown text-warm-cream px-6 py-3 rounded-lg hover:bg-rich-brown transition-all duration-300 font-baskerville flex items-center space-x-2 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl" data-id="${item.id}">
                                <i class="fas fa-plus"></i>
                                <span>Add</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-2">${item.name}</h3>
                        <p class="text-rich-brown/70 font-baskerville mb-3">${item.description}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-accent-brown font-baskerville">₱${item.price.toFixed(2)}</span>
                        </div>
                    </div>
                `;
                menuItemsContainer.appendChild(itemElement);
            });
        }

        // Render cart items
        function renderCart() {
    cartItemsContainer.innerHTML = '';
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-4xl text-rich-brown/30 mb-4"></i>
                <p class="text-rich-brown/60 font-baskerville">Your cart is empty</p>
            </div>`;
        updateTotals();
        return;
    }
    
    cart.forEach(item => {
        const cartItemElement = document.createElement('div');
        cartItemElement.className = 'cart-item fade-in bg-white/50 rounded-lg p-4 mb-3 last:mb-0';
        cartItemElement.innerHTML = `
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="font-bold text-deep-brown font-playfair">${item.name}</h4>
                    <p class="text-sm text-rich-brown/70 font-baskerville mt-1">₱${item.price.toFixed(2)} each</p>
                    <div class="flex items-center mt-3 space-x-2">
                        <button class="quantity-btn decrease px-3 py-1 bg-warm-cream/50 rounded hover:bg-warm-cream/70 transition-colors duration-200" data-id="${item.id}">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" 
                               class="quantity-input w-16 text-center font-medium text-deep-brown border border-warm-cream/50 rounded py-1 focus:outline-none focus:ring-1 focus:ring-amber-500" 
                               value="${item.quantity}" 
                               min="1" 
                               data-id="${item.id}">
                        <button class="quantity-btn increase px-3 py-1 bg-warm-cream/50 rounded hover:bg-warm-cream/70 transition-colors duration-200" data-id="${item.id}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-deep-brown font-baskerville">₱${(item.price * item.quantity).toFixed(2)}</div>
                    <button class="remove-item text-sm text-red-500 hover:text-red-700 mt-2 transition-colors duration-200" data-id="${item.id}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
        cartItemsContainer.appendChild(cartItemElement);
        
        // Add event listeners for the input field
        const quantityInput = cartItemElement.querySelector('.quantity-input');
        
        quantityInput.addEventListener('change', function() {
            const newQuantity = parseInt(this.value);
            if (!isNaN(newQuantity) && newQuantity >= 1) {
                updateCartItemQuantity(item.id, newQuantity);
            } else {
                this.value = item.quantity; // Revert if invalid
            }
        });
        
        quantityInput.addEventListener('blur', function() {
            if (this.value === "" || parseInt(this.value) < 1) {
                this.value = 1;
                updateCartItemQuantity(item.id, 1);
            }
        });
        
        // Prevent non-numeric input
        quantityInput.addEventListener('keydown', function(e) {
            if (['e', 'E', '+', '-'].includes(e.key)) {
                e.preventDefault();
            }
        });
    });
    
    updateTotals();
}

function updateCartItemQuantity(itemId, newQuantity) {
    const itemIndex = cart.findIndex(item => item.id === itemId);
    if (itemIndex !== -1) {
        cart[itemIndex].quantity = newQuantity;
        saveCartToLocalStorage();
        renderCart(); // Re-render to update all values
    }
}
        // Update totals in cart
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.10; // 10% tax
            const total = subtotal + tax;
            
            subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
            taxElement.textContent = `₱${tax.toFixed(2)}`;
            totalElement.textContent = `₱${total.toFixed(2)}`;
        }

        // Update the addToCart function to include visual feedback
        function addToCart(itemId) {
            const menuItem = menuItems.find(item => item.id === itemId);
            
            if (!menuItem) return;
            
            const existingItem = cart.find(item => item.id === itemId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    ...menuItem,
                    quantity: 1
                });
            }
            
            renderCart();
        }

        // Remove item from cart
        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            renderCart();
        }

        // Update item quantity in cart
        function updateQuantity(itemId, change) {
            const cartItem = cart.find(item => item.id === itemId);
            
            if (!cartItem) return;
            
            cartItem.quantity += change;
            
            if (cartItem.quantity <= 0) {
                removeFromCart(itemId);
            } else {
                renderCart();
            }
        }

        // Clear cart
        function clearCart() {
            cart = [];
            renderCart();
        }

        // Checkout
        async function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            // Calculate initial totals
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.10;
            const totalBeforeDiscount = subtotal + tax;
            
            // Show discount modal
            const modal = document.getElementById('discount-modal');
            modal.classList.remove('hidden');
            
            // Reset all inputs and selections
            document.getElementById('none').checked = true;
            document.getElementById('payment-amount').value = '';
            document.getElementById('payment-error').classList.add('hidden');
            document.getElementById('apply-discount').disabled = true;

            // FULLY reset summary display
            document.getElementById('summary-subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('summary-tax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('summary-discount').textContent = `₱0.00`;
            document.getElementById('summary-total').textContent = `₱${totalBeforeDiscount.toFixed(2)}`;
            document.getElementById('summary-paid').textContent = `₱0.00`;
            document.getElementById('summary-change').textContent = `₱0.00`;

            // Function to update payment summary
            function updateSummary() {
                const selectedDiscount = document.querySelector('input[name="discount"]:checked').value;
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const paymentError = document.getElementById('payment-error');
                
                // Calculate discount
                let discountPrice = 0;
                if (selectedDiscount === 'senior' || selectedDiscount === 'PWD') {
                    discountPrice = totalBeforeDiscount * 0.20;
                }
                const finalTotal = totalBeforeDiscount - discountPrice;
                
                // Calculate change
                const change = paymentAmount - finalTotal;

                // Validate payment amount
                if (paymentAmount < 0) {
                    paymentError.textContent = "Payment amount cannot be negative";
                    paymentError.classList.remove('hidden');
                    document.getElementById('apply-discount').disabled = true;
                } else if (paymentAmount > 0 && paymentAmount < finalTotal) {
                    paymentError.textContent = "Payment amount must cover the total amount";
                    paymentError.classList.remove('hidden');
                    document.getElementById('apply-discount').disabled = true;
                } else {
                    paymentError.classList.add('hidden');
                    document.getElementById('apply-discount').disabled = paymentAmount === 0 || change < 0;
                }
                
                // Update display
                document.getElementById('summary-discount').textContent = `₱${discountPrice.toFixed(2)}`;
                document.getElementById('summary-total').textContent = `₱${finalTotal.toFixed(2)}`;
                document.getElementById('summary-paid').textContent = `₱${paymentAmount.toFixed(2)}`;
                document.getElementById('summary-change').textContent = `₱${Math.max(change, 0).toFixed(2)}`;
            }
            
            // Remove old event listeners to prevent duplicates
            document.querySelectorAll('input[name="discount"]').forEach(radio => {
                radio.replaceWith(radio.cloneNode(true));
            });
            const paymentInput = document.getElementById('payment-amount');
            paymentInput.replaceWith(paymentInput.cloneNode(true));
            
            // Add event listeners for real-time updates
            document.querySelectorAll('input[name="discount"]').forEach(radio => {
                radio.addEventListener('change', updateSummary);
            });
            document.getElementById('payment-amount').addEventListener('input', updateSummary);
            
            // Wait for user to complete payment
            const paymentData = await new Promise((resolve) => {
                const applyBtn = document.getElementById('apply-discount');
                const cancelBtn = document.getElementById('cancel-discount');
                
                // Remove old listeners
                applyBtn.replaceWith(applyBtn.cloneNode(true));
                cancelBtn.replaceWith(cancelBtn.cloneNode(true));
                
                // Add new listeners
                document.getElementById('apply-discount').addEventListener('click', () => {
                    const selectedDiscount = document.querySelector('input[name="discount"]:checked').value;
                    const paymentAmount = parseFloat(document.getElementById('payment-amount').value);

                    // Validate payment amount again before proceeding
                    if (isNaN(paymentAmount)) {
                        alert('Please enter a valid payment amount');
                        return;
                    }
                    
                    if (paymentAmount < 0) {
                        alert('Payment amount cannot be negative');
                        return;
                    }
                    
                    // Calculate final totals
                    let discountPrice = 0;
                    if (selectedDiscount === 'senior' || selectedDiscount === 'PWD') {
                        discountPrice = totalBeforeDiscount * 0.20;
                    }
                    const finalTotal = totalBeforeDiscount - discountPrice;
                    const change = paymentAmount - finalTotal;
                    
                    modal.classList.add('hidden');
                    resolve({
                        discountType: selectedDiscount,
                        discountPrice,
                        paymentAmount,
                        change,
                        finalTotal
                    });
                });
                
                document.getElementById('cancel-discount').addEventListener('click', () => {
                    modal.classList.add('hidden');
                    resolve(null); // Indicates cancellation
                });
            });
            
            if (!paymentData) return; // User cancelled
            
            // Prepare data for backend
            const orderData = {
                total_price: paymentData.finalTotal,
                discount_type: paymentData.discountType,
                discount_price: paymentData.discountPrice,
                amount_paid: paymentData.paymentAmount,
                amount_change: paymentData.change,
                items: cart.map(item => ({
                    dish_id: item.id,
                    price: item.price,
                    quantity: item.quantity
                }))
            };
            
            // Send to backend
            try {
                const response = await fetch('posFunctions/process_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });
                
                if (!response.ok) {
                    throw new Error('Failed to process order');
                }
                
                const result = await response.json();
                if (result.success) {
                    // Show success modal instead of alert
                    const successModal = document.getElementById('success-modal');
                    successModal.classList.remove('hidden');
                    // Add event listener for close button
                    const closeSuccessBtn = document.getElementById('close-success');
                    closeSuccessBtn.replaceWith(closeSuccessBtn.cloneNode(true)); // Prevent duplicate listeners
                    document.getElementById('close-success').addEventListener('click', () => {
                        successModal.classList.add('hidden');
                    });
                    clearCart();
                    document.getElementById('none').checked = true;
                } else {
                    alert('Error processing order: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Checkout error:', error);
                alert('Error processing order. Please try again.');
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Category filter buttons
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.dataset.category;
                    console.log('Category button clicked:', btn.dataset.category);
                    console.log('Category  clicked:', category);
                    renderMenuItems(category, '');
                    
                    // Update active button style
                    categoryBtns.forEach(b => b.classList.remove('bg-deep-brown'));
                    btn.classList.add('bg-deep-brown');
                });
            });

            // Search functionality
            const searchInput = document.getElementById('menu-search');
            
            // Add input validation function
            function validateSearchInput(input) {
                let value = input.value;
                
                // If input is less than 2 characters, don't allow spaces
                if (value.length < 2) {
                    value = value.replace(/\s/g, '');
                } else {
                    // For inputs with 2 or more characters, just clean up multiple spaces
                    value = value.replace(/\s+/g, ' ').trim();
                }
                
                // Update input value if it changed
                if (value !== input.value) {
                    input.value = value;
                }
                
                return value;
            }

            searchInput.addEventListener('input', (e) => {
                const validatedValue = validateSearchInput(e.target);
                clearSearchBtn.classList.toggle('hidden', !validatedValue);
                renderMenuItems(currentCategory, validatedValue);
            });
            
            const clearSearchBtn = document.getElementById('clear-search');
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                clearSearchBtn.classList.add('hidden');
                renderMenuItems(currentCategory, '');
            });

            // Single event listener for both card and button clicks
            menuItemsContainer.addEventListener('click', (e) => {
                // Check if either the card or the add button was clicked
                const card = e.target.closest('.menu-item-card');
                const addButton = e.target.closest('.add-to-cart');
                
                if (card || addButton) {
                    // Get the item ID from either the card or button
                    const itemId = parseInt(card?.dataset.id || addButton?.dataset.id);
                    if (itemId) {
                        // Add visual feedback if the button was clicked
                        if (addButton) {
                            addButton.classList.add('bg-green-600');
                            setTimeout(() => {
                                addButton.classList.remove('bg-green-600');
                            }, 200);
                        }
                        addToCart(itemId);
                    }
                }
            });

            // Add touch event support for mobile devices
            menuItemsContainer.addEventListener('touchstart', (e) => {
                const addButton = e.target.closest('.add-to-cart');
                if (addButton) {
                    addButton.classList.add('active:scale-95');
                }
            }, { passive: true });

            menuItemsContainer.addEventListener('touchend', (e) => {
                const addButton = e.target.closest('.add-to-cart');
                if (addButton) {
                    addButton.classList.remove('active:scale-95');
                }
            }, { passive: true });
            
            // Cart quantity buttons (delegated)
            cartItemsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('quantity-btn')) {
                    const itemId = parseInt(e.target.dataset.id);
                    const isIncrease = e.target.classList.contains('increase');
                    updateQuantity(itemId, isIncrease ? 1 : -1);
                }
                
                if (e.target.classList.contains('remove-item')) {
                    const itemId = parseInt(e.target.dataset.id);
                    removeFromCart(itemId);
                }
            });
            
            // Clear cart button
            clearCartBtn.addEventListener('click', clearCart);
            
            // Checkout button
            checkoutBtn.addEventListener('click', checkout);
        }

        // Add current date display
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Update sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
        }

        // Update event listeners
        sidebarToggle.addEventListener('click', toggleSidebar);

        // Update category button click handler
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');
                
                const category = btn.dataset.category;
                renderMenuItems(category, '');
            });
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', () => {
            // Set initial active category
            const allItemsBtn = document.querySelector('[data-category="all"]');
            if (allItemsBtn) {
                allItemsBtn.classList.add('active');
            }
        });

        // Initialize the application
        init();
    </script>
</body>
</html>
