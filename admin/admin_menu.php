<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Menu Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add these to your head section -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap">
    <script src="../tailwind.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Animation classes */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Delay classes for staggered animations */
        .delay-100 {
            transition-delay: 100ms;
        }
        .delay-200 {
            transition-delay: 200ms;
        }
        .delay-300 {
            transition-delay: 300ms;
        }
        .delay-400 {
            transition-delay: 400ms;
        }

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
        .dashboard-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(232, 224, 213, 0.5);
            box-shadow: 0 4px 6px rgba(93, 47, 15, 0.1);
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 8px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(-2px);
        }
        
        /* Sidebar improvements */
        .sidebar-link {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #E8E0D5;
            transition: width 0.3s ease;
        }
        
        .sidebar-link:hover::after {
            width: 100%;
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

        /* Add this to your existing style section 
        BEFORE z-index: 9999 */
        #profileMenu {
            z-index: 49 !important;
            transform: translateY(0) !important;
        }

        header {
            z-index: 45;
        }

        /* Add table styles from admin_bookings.php */
        .dashboard-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(232, 224, 213, 0.5);
            box-shadow: 0 4px 6px rgba(93, 47, 15, 0.1);
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 8px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(-2px);
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background-color: #f9fafb;
        }

        thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f3f4f6;
        }

        tbody td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Status badge styles */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Action button styles */
        .action-btn {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background-color: #f3f4f6;
        }

        .action-btn i {
            font-size: 1rem;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            width: 100%;
            max-width: 300px;
        }

        /* Compact pagination styling */
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
            background: white;
            color: #374151 !important;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #8B4513 !important;
            color: white !important;
            border-color: #8B4513;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6 !important;
            border-color: #e5e7eb;
            color: #374151 !important;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 0.5rem 0;
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Fix sorting icons */
        table.dataTable thead th {
            position: relative;
            background-image: none !important;
        }

        table.dataTable thead th.sorting:after,
        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            position: absolute;
            right: 8px;
            color: #8B4513;
        }

        table.dataTable thead th.sorting:after {
            content: "↕";
            opacity: 0.4;
        }

        table.dataTable thead th.sorting_asc:after {
            content: "↑";
        }

        table.dataTable thead th.sorting_desc:after {
            content: "↓";
        }

        /* Add these styles to your existing style section */
        .modal-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            background: white;
            z-index: 10;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
        }

        /* Improved scrollbar for modal body */
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }

        /* Custom scrollbar for modal content */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #E8E0D5;
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }
    </style>
</head>
<body class="bg-warm-cream/50 font-baskerville">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown via-rich-brown to-accent-brown text-warm-cream transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-6 border-b border-warm-cream/20">
                <div>
                    <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
                    <p class="nav-subtitle text-xs text-warm-cream tracking-widest">RISTORANTE</p>
                </div>
            </div>
            
            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-chart-pie w-5"></i>
                            <span class="sidebar-text font-baskerville">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookings.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span class="sidebar-text font-baskerville">Booking Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-warm-cream/10 text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inventory.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-boxes w-5"></i>
                            <span class="sidebar-text font-baskerville">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_expenses.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="sidebar-text font-baskerville">Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_employee_creation.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-user-plus w-5"></i>
                            <span class="sidebar-text font-baskerville">Employee Creation</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[45]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-deep-brown hover:text-rich-brown transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-deep-brown font-playfair">Menu Management</h2>
                    </div>
                    <div class="text-sm text-rich-brown font-baskerville flex-1 text-center mx-4">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span id="current-date"></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="profileDropdown" class="flex items-center space-x-2 hover:bg-warm-cream/10 p-2 rounded-lg transition-all duration-200">
                                <div class="w-10 h-10 rounded-full border-2 border-accent-brown overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-medium text-deep-brown font-baskerville">Admin</span>
                                <i class="fas fa-chevron-down text-deep-brown text-sm transition-transform duration-200"></i>
                            </button>
                            <div id="profileMenu" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden transform opacity-0 transition-all duration-200">
                                <a href="../logout.php" class="flex items-center space-x-2 px-4 py-2 text-sm text-deep-brown hover:bg-warm-cream/10 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Sign Out</span>
                                </a>
                            </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 relative z-0">
                <!-- Menu Management Section -->
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Dish Management</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-64">
                                <input type="text" id="menu-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Search dishes...">
                            </div>
                            <button id="add-dish-btn" class="w-52 h-10 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus"></i>
                                <span class="font-baskerville">Add New Dish</span>
                            </button>
                        </div>
                    </div>

                    <!-- Menu Table -->
                    <div class="overflow-x-auto">
                        <table id="menu-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Dish ID</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Dish Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Category</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Status</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Capital</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                        
                    </div>
                </div>

                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 mt-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Menu Packages</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-64">
                                <input type="text" id="packages-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Search packages...">
                            </div>
                            <button id="add-package-btn" class="w-52 h-10 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus"></i>
                                <span class="font-baskerville">Create New Package</span>
                            </button>
                        </div>
                    </div>

                    <!-- Packages Table -->
                    <div class="overflow-x-auto">
                        <table id="packages-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Package ID</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Package Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Type</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Status</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                        
                    </div>
                </div>

                

            </main>
        </div>
    </div>

<!-- Dish Creation Modal -->
<div id="dish-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
        <div class="modal-header p-6 border-b border-warm-cream/20">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Create New Dish</h3>
                <button id="close-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="modal-body flex-1 overflow-y-auto p-6">
            <form id="dish-form" class="space-y-6">
                <!-- Dish Name -->
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dish Name</label>
                    <input type="text" id="dish-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter dish name" required>
                </div>

                <!-- Dish Description -->
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Description</label>
                    <textarea id="dish-description" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter dish description"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dish Category</label>
                    <select id="dish-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <option value="">Select category</option>
                        <option value="italian-dish">Italian Dish</option>
                        <option value="spanish-dish">Spanish Dish</option>
                        <option value="house-salad">House Salad</option>
                        <option value="pizza">Pizza</option>
                        <option value="burgers">Burgers</option>
                        <option value="pasta">Pasta</option>
                        <option value="pasta_caza">Pasta e Caza</option>
                        <option value="desserts">Desserts</option>
                        <option value="main-course">Main Course</option>
                        <option value="drinks">Drinks</option>
                        <option value="coffee">Coffee</option>
                    </select>
                </div>

                <!-- Price and Capital -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (₱)</label>
                        <input type="number" id="dish-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Capital (₱)</label>
                        <input type="number" id="dish-capital" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required>
                    </div>
                </div>

                <!-- Dish Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dish Image</label>
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-1">
                            <input type="file" id="dish-image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="px-4 py-2 border border-warm-cream/50 rounded-lg bg-white/50 backdrop-blur-sm text-center cursor-pointer hover:bg-warm-cream/10 transition-colors duration-200 font-baskerville">
                                <i class="fas fa-upload mr-2"></i>
                                <span id="file-name">Choose an image file</span>
                            </div>
                        </div>
                    </div>
                    <div id="image-preview-container" class="mt-4 hidden">
                        <p class="text-sm text-gray-500 mb-2 font-baskerville">Image Preview:</p>
                        <img id="image-preview" src="#" alt="Preview" class="max-w-full h-auto max-h-48 rounded-lg border border-warm-cream/50">
                    </div>
                </div>

                <!-- Ingredients Section -->
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Ingredients</label>
                    <div id="ingredients-container">
                        <div class="ingredient-row flex items-center space-x-2 mb-2">
                            <select class="flex-1 px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville ingredient-select">
                                <option value="">Select ingredient</option>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                            <input type="number" placeholder="Quantity (grams)" class="w-32 px-3 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville ingredient-quantity" step="0.01" min="0" value="">
                            <button type="button" class="text-red-500 hover:text-red-700 remove-ingredient hidden">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="add-ingredient" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2 font-baskerville">
                        <i class="fas fa-plus"></i>
                        <span>Add Another Ingredient</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="modal-footer p-6 border-t border-warm-cream/20">
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-dish" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                    Cancel
                </button>
                <button type="submit" form="dish-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                    Create Dish
                </button>
            </div>
        </div>
    </div>
</div>
    
                <div id="package-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <!-- Sticky Header -->
                        <div class="p-6 border-b border-warm-cream/30 sticky top-0 bg-white/95 backdrop-blur-md rounded-t-xl z-10">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Create New Package</h3>
                                <button id="close-package-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable Content -->
                        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                            <form id="package-form" class="space-y-6">
                                <!-- Package Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Package Name</label>
                                    <input type="text" id="package-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter package name" required>
                                </div>

                                <!-- Package Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Description</label>
                                    <textarea id="package-description" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter package description"></textarea>
                                </div>

                                <!-- Price and Capital -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (₱)</label>
                                        <input type="number" id="package-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Capital (₱)</label>
                                        <input type="number" id="package-capital" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required readonly>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Menu Type</label>
                                    <select id="package-type" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                                        <option value="buffet">Buffet</option>
                                        <option value="per_plate">Sit - On's</option>
                                    </select>
                                </div>

                                <!-- Dishes Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Add Dishes</label>
                                    <div id="dishes-container">
                                        <div class="dish-row flex items-center space-x-2 mb-2">
                                            <select class="flex-1 px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville dish-select">
                                                <option value="">Select Category</option>
                                                <option value="italian-dish">Italian Dish</option>
                                                <option value="spanish-dish">Spanish Dish</option>
                                                <option value="house-salad">House Salad</option>
                                                <option value="pizza">Pizza</option>
                                                <option value="burgers">Burgers</option>
                                                <option value="pasta">Pasta</option>
                                                <option value="pasta_caza">Pasta e Caza</option>
                                                <option value="desserts">Desserts</option>
                                                <option value="main-course">Main Course</option>
                                                <option value="drinks">Drinks</option>
                                                <option value="coffee">Coffee</option>
                                            </select>
                                            <input type="number" placeholder="Quantity" class="w-24 px-3 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville dish-quantity" min="1" value="1">
                                            <button type="button" class="text-red-500 hover:text-red-700 remove-dish hidden">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" id="add-dish" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2 font-baskerville">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Dish</span>
                                    </button>
                                </div>

                                
                            </form>
                        </div>

                        <!-- Sticky Footer -->
                        <div class="p-6 border-t border-warm-cream/30 sticky bottom-0 bg-white/95 backdrop-blur-md rounded-b-xl z-10">
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancel-package" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                                    Cancel
                                </button>
                                <button type="submit" form="package-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                    Create Package
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Dish Modal -->
                <div id="edit-dish-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-xl w-full max-h-[90vh] flex flex-col">
                        <div class="modal-header p-6 border-b border-warm-cream/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Dish</h3>
                                <button id="close-edit-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal-body flex-1 overflow-y-auto p-6">
                            <form id="edit-dish-form" class="space-y-6">
                                <input type="hidden" id="edit-dish-id">
                                
                                <!-- Dish Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dish Name</label>
                                    <input type="text" id="edit-dish-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter dish name" required>
                                </div>

                                <!-- Dish Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Description</label>
                                    <textarea id="edit-dish-description" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter dish description"></textarea>
                                </div>

                                <!-- Dish Category -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dish Category</label>
                                    <select id="edit-dish-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                        <option value="">Select category</option>
                                        <option value="italian-dish">Italian Dish</option>
                                        <option value="spanish-dish">Spanish Dish</option>
                                        <option value="house-salad">House Salad</option>
                                        <option value="pizza">Pizza</option>
                                        <option value="burgers">Burgers</option>
                                        <option value="pasta">Pasta</option>
                                        <option value="pasta_caza">Pasta e Caza</option>
                                        <option value="desserts">Desserts</option>
                                        <option value="main-course">Main Course</option>
                                        <option value="drinks">Drinks</option>
                                        <option value="coffee">Coffee</option>
                                    </select>
                                </div>

                                <!-- Price and Capital -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (₱)</label>
                                        <input type="number" id="edit-dish-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Capital (₱)</label>
                                        <input type="number" id="edit-dish-capital" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required>
                                    </div>
                                </div>

                                <!-- Dish Status -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Status</label>
                                    <select id="edit-dish-status" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                                        <option value="active">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                </div>

                                <!-- Dish Image Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dish Image</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative flex-1">
                                            <input type="file" id="edit-dish-image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            <div class="px-4 py-2 border border-warm-cream/50 rounded-lg bg-white/50 backdrop-blur-sm text-center cursor-pointer hover:bg-warm-cream/10 transition-colors duration-200 font-baskerville">
                                                <i class="fas fa-upload mr-2"></i>
                                                <span id="edit-file-name">Choose an image file</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="edit-image-preview-container" class="mt-4">
                                        <p class="text-sm text-gray-500 mb-2 font-baskerville">Current Image:</p>
                                        <img id="edit-image-preview" src="#" alt="Preview" class="max-w-full h-auto max-h-48 rounded-lg border border-warm-cream/50">
                                    </div>
                                </div>

                                <!-- Ingredients Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Ingredients</label>
                                    <div id="edit-ingredients-container">
                                        <!-- Ingredients will be populated here -->
                                    </div>
                                    <button type="button" id="add-edit-ingredient" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2 font-baskerville">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Ingredient</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer p-6 border-t border-warm-cream/20">
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancel-edit-dish" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                                    Cancel
                                </button>
                                <button type="submit" form="edit-dish-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Package Modal -->
                <div id="view-package-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <div class="modal-header p-6 border-b border-warm-cream/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Package Details</h3>
                                <button id="close-view-package-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal-body flex-1 overflow-y-auto p-6">
                            <div class="space-y-6">
                                <!-- Package Info -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Package Name</label>
                                        <p id="view-package-name" class="text-lg font-semibold text-rich-brown font-baskerville">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Type</label>
                                        <p id="view-package-type" class="text-lg font-semibold text-rich-brown font-baskerville">-</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Price</label>
                                        <p id="view-package-price" class="text-lg font-semibold text-rich-brown font-baskerville">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Status</label>
                                        <p id="view-package-status" class="text-lg font-semibold text-rich-brown font-baskerville">-</p>
                                    </div>
                                </div>

                                <!-- Package Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Description</label>
                                    <p id="view-package-description" class="text-gray-700 font-baskerville">-</p>
                                </div>

                                <!-- Dishes Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dishes Included</label>
                                    <div id="view-dishes-container" class="space-y-4">
                                        <!-- Dishes will be grouped by category here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer p-6 border-t border-warm-cream/20">
                            <div class="flex justify-end">
                                <button type="button" id="close-view-package-btn" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Package Modal -->
                <div id="edit-package-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <div class="modal-header p-6 border-b border-warm-cream/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Package</h3>
                                <button id="close-edit-package-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal-body flex-1 overflow-y-auto p-6">
                            <form id="edit-package-form" class="space-y-6">
                                <input type="hidden" id="edit-package-id">
                                
                                <!-- Package Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Package Name</label>
                                    <input type="text" id="edit-package-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter package name" required>
                                </div>

                                <!-- Package Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Description</label>
                                    <textarea id="edit-package-description" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter package description"></textarea>
                                </div>

                                <!-- Price and Capital -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (₱)</label>
                                        <input type="number" id="edit-package-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Capital (₱)</label>
                                        <input type="number" id="edit-package-capital" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="0.00" step="0.01" required readonly>
                                    </div>
                                </div>

                                <!-- Status and Type -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Status</label>
                                        <select id="edit-package-status" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                                            <option value="active">Available</option>
                                            <option value="inactive">Unavailable</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Menu Type</label>
                                        <select id="edit-package-type" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                                            <option value="buffet">Buffet</option>
                                            <option value="per_plate">Sit - On's</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dishes Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Dishes in Package</label>
                                    <div id="edit-package-dishes-container">
                                        <!-- Dishes will be populated here -->
                                    </div>
                                    <button type="button" id="add-edit-package-dish" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2 font-baskerville">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Dish</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer p-6 border-t border-warm-cream/20">
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancel-edit-package" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                                    Cancel
                                </button>
                                <button type="submit" form="edit-package-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

    <script>
        // Sidebar Toggle with smooth animation
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const cafeTitle = document.querySelector('.nav-title');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');
            
            if (sidebar.classList.contains('w-16')) {
                cafeTitle.style.opacity = '0';
                sidebarTexts.forEach(text => text.style.opacity = '0');
                setTimeout(() => {
                    cafeTitle.style.display = 'none';
                    sidebarTexts.forEach(text => text.style.display = 'none');
                }, 300);
            } else {
                cafeTitle.style.display = 'block';
                sidebarTexts.forEach(text => text.style.display = 'block');
                setTimeout(() => {
                    cafeTitle.style.opacity = '1';
                    sidebarTexts.forEach(text => text.style.opacity = '1');
                }, 50);
            }
        });

        // Set current date with time
        function updateDateTime() {
            const now = new Date();
            const dateTimeString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) + ' ' + now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-date').textContent = dateTimeString;
        }
        
        // Update time every second
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Profile Dropdown functionality
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        const dropdownIcon = profileDropdown.querySelector('.fa-chevron-down');

        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            setTimeout(() => {
                profileMenu.classList.toggle('opacity-0');
                dropdownIcon.classList.toggle('rotate-180');
            }, 50);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden', 'opacity-0');
                dropdownIcon.classList.remove('rotate-180');
            }
        });

        // Scroll animation observer
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, {
            threshold: 0.1
        });

        animateElements.forEach(element => {
            observer.observe(element);
        });

        // Image upload preview functionality
        document.getElementById('dish-image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            const fileNameDisplay = document.getElementById('file-name');
            
            if (file) {
                fileNameDisplay.textContent = file.name;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = 'Choose an image file';
                previewContainer.classList.add('hidden');
                previewImage.src = '#';
            }
        });

        // Modal functionality
        const modal = document.getElementById('dish-modal');
        const addDishBtn = document.getElementById('add-dish-btn');
        const closeModal = document.getElementById('close-modal');
        const cancelDish = document.getElementById('cancel-dish');
        const addIngredientBtn = document.getElementById('add-ingredient');
        const ingredientsContainer = document.getElementById('ingredients-container');

        async function fetchIngredients() {
            try {
                const response = await fetch('menu_handlers/get_ingredients.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const ingredients = await response.json();
                return ingredients;
            } catch (error) {
                console.error('Error fetching ingredients:', error);
                return [];
            }
        }

        // Open modal
        addDishBtn.addEventListener('click', async () => {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling

            // Fetch and populate ingredients
            const ingredients = await fetchIngredients();
            populateIngredientDropdowns(ingredients);
        });

        function populateIngredientDropdowns(ingredients) {
            const dropdowns = document.querySelectorAll('.ingredient-select');
            
            dropdowns.forEach(dropdown => {
                // Clear existing options except the first one
                while (dropdown.options.length > 1) {
                    dropdown.remove(1);
                }
                
                // Add new options
                ingredients.forEach(ingredient => {
                    const option = document.createElement('option');
                    option.value = ingredient.ingredient_id;
                    option.textContent = ingredient.ingredient_name;
                    dropdown.appendChild(option);
                });
            });
        }

        // Close modal functions
        const closeModalFunction = () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Re-enable background scrolling
            // Reset form
            document.getElementById('dish-form').reset();
            // Reset ingredients to initial state
            const initialIngredient = ingredientsContainer.querySelector('.ingredient-row');
            ingredientsContainer.innerHTML = '';
            const newRow = initialIngredient.cloneNode(true);
            newRow.querySelector('.ingredient-quantity').value = ''; // Reset quantity
            ingredientsContainer.appendChild(newRow);

            const dishImageInput = document.getElementById('dish-image');
            const fileNameSpan = document.getElementById('file-name');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            
            dishImageInput.value = ''; // Clear the file input
            fileNameSpan.textContent = 'Choose an image file';
            imagePreviewContainer.classList.add('hidden');
            imagePreview.src = '#';
        };

        closeModal.addEventListener('click', closeModalFunction);
        cancelDish.addEventListener('click', closeModalFunction);

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModalFunction();
            }
        });

        // Add new ingredient row
        addIngredientBtn.addEventListener('click', async () => {
            const ingredientRow = document.querySelector('.ingredient-row').cloneNode(true);
            const removeBtn = ingredientRow.querySelector('.remove-ingredient');
            const quantityInput = ingredientRow.querySelector('.ingredient-quantity');
            
            // Reset the quantity field to empty
            quantityInput.value = '';
            
            removeBtn.classList.remove('hidden');
            ingredientsContainer.appendChild(ingredientRow);

            // Fetch and populate the new dropdown
            const ingredients = await fetchIngredients();
            const newDropdown = ingredientRow.querySelector('.ingredient-select');
            populateDropdown(newDropdown, ingredients);
        });

        function populateDropdown(dropdown, ingredients) {
            // Clear existing options except the first one
            while (dropdown.options.length > 1) {
                dropdown.remove(1);
            }
            
            // Add new options
            ingredients.forEach(ingredient => {
                const option = document.createElement('option');
                option.value = ingredient.ingredient_id;
                option.textContent = ingredient.ingredient_name;
                dropdown.appendChild(option);
            });
        }

        // Remove ingredient row
        ingredientsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-ingredient')) {
                e.target.closest('.ingredient-row').remove();
            }
        });

        // Update the form submission
        document.getElementById('dish-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Collect form data
            const dishData = {
                name: document.getElementById('dish-name').value,
                description: document.getElementById('dish-description').value,
                category: document.getElementById('dish-category').value,
                price: document.getElementById('dish-price').value,
                capital: document.getElementById('dish-capital').value,
                image: document.getElementById('dish-image').files[0],
                ingredients: []
            };
            
            // Collect ingredients data
            const ingredientRows = document.querySelectorAll('.ingredient-row');
            ingredientRows.forEach(row => {
                const ingredientId = row.querySelector('.ingredient-select').value;
                const quantity = row.querySelector('.ingredient-quantity').value;
                
                if (ingredientId && quantity) {
                    dishData.ingredients.push({
                        ingredient_id: ingredientId,
                        quantity_kg: quantity
                    });
                }
            });
            
            // Validate
            if (!dishData.name || !dishData.price || !dishData.capital || !dishData.category) {
                alert('Please fill in all required fields');
                return;
            }
            
            if (dishData.ingredients.length === 0) {
                alert('Please add at least one ingredient');
                return;
            }
            
            // Submit to server
            try {
                const formData = new FormData();
                formData.append('name', dishData.name);
                formData.append('description', dishData.description);
                formData.append('category', dishData.category);
                formData.append('price', dishData.price);
                formData.append('capital', dishData.capital);
                if (dishData.image) {
                    formData.append('image', dishData.image);
                }
                formData.append('ingredients', JSON.stringify(dishData.ingredients));
                
                const response = await fetch('menu_handlers/add_dish.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Dish added successfully!');
                    closeModalFunction();
                    // Refresh the dishes table
                    $('#menu-table').DataTable().ajax.reload(null, false);
                } else {
                    alert('Error: ' + (result.message || 'Failed to add dish'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the dish');
            }
        });

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#menu-table').DataTable({
                responsive: true,
                dom: '<"overflow-x-auto"rt><"flex flex-col sm:flex-row justify-between items-center mt-2"<"text-sm text-gray-600"i><"mt-2 sm:mt-0"p>>',
                lengthChange: false,
                pageLength: 10,
                searching: true,
                ajax: {
                    url: 'menu_handlers/get_dishes.php',
                    type: 'GET',
                    dataSrc: ''
                },
                columns: [
                    { data: 'dish_id' },
                    { data: 'dish_name' },
                    { data: 'dish_category' },
                    { 
                        data: 'status',
                        render: function(data, type, row) {
                            var statusClass = data === 'active' ? 'status-active' : 'status-inactive';
                            var statusText = data === 'active' ? 'Active' : 'Unavailable';
                            return `<span class="status-badge ${statusClass}">${statusText}</span>`;
                        }
                    },
                    { 
                        data: 'price',
                        render: function(data) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: 'capital',
                        render: function(data) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: 'dish_id',
                        render: function(data) {
                            return `<button class="action-btn text-rich-brown hover:text-deep-brown transition-colors duration-200 edit-dish-btn" data-id="${data}">
                                        <i class="fas fa-edit"></i>
                                    </button>`;
                        },
                        orderable: false
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 3 },
                    { responsivePriority: 3, targets: 4 },
                    { responsivePriority: 4, targets: -1 }
                ],
                language: {
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No dishes available",
                    infoFiltered: "(filtered from _MAX_ total dishes)",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });

            // Move the search box to the custom input
            $('#menu-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#menu-table').on('click', '.edit-dish-btn', function() {
                var dishId = $(this).data('id');
                openEditDishModal(dishId);
                console.log('Edit dish with ID:', dishId);
            });
        });

        
        // Edit Dish Modal functionality
        const editDishModal = document.getElementById('edit-dish-modal');
        const closeEditModal = document.getElementById('close-edit-modal');
        const cancelEditDish = document.getElementById('cancel-edit-dish');
        const addEditIngredientBtn = document.getElementById('add-edit-ingredient');
        const editIngredientsContainer = document.getElementById('edit-ingredients-container');

        // Function to open edit modal with dish data
        async function openEditDishModal(dishId) {
            try {
                // Fetch dish data
                const response = await fetch(`menu_handlers/get_editDish.php?id=${dishId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const dishData = await response.json();
                
                // Populate form fields
                document.getElementById('edit-dish-id').value = dishData.dish_id;
                document.getElementById('edit-dish-name').value = dishData.dish_name;
                document.getElementById('edit-dish-description').value = dishData.dish_description;
                document.getElementById('edit-dish-category').value = dishData.dish_category;
                document.getElementById('edit-dish-price').value = dishData.price;
                document.getElementById('edit-dish-capital').value = dishData.capital;
                document.getElementById('edit-dish-status').value = dishData.status;
                
                // Set image preview if available
                const previewContainer = document.getElementById('edit-image-preview-container');
                const previewImage = document.getElementById('edit-image-preview');
                const fileNameDisplay = document.getElementById('edit-file-name');
                
                if (dishData.image_path) {
                    previewImage.src = dishData.image_path;
                    previewContainer.classList.remove('hidden');
                    fileNameDisplay.textContent = 'Current image'; // Or you can extract filename from path
                } else {
                    previewImage.src = '#';
                    fileNameDisplay.textContent = 'Choose an image file';
                    previewContainer.classList.remove('hidden'); // Keep container visible even if no image
                }
                
                // Clear and populate ingredients
                editIngredientsContainer.innerHTML = '';
                const ingredients = await fetchIngredients();
                
                if (dishData.ingredients && dishData.ingredients.length > 0) {
                    dishData.ingredients.forEach(ingredient => {
                        addIngredientRowToEditModal(ingredients, ingredient.ingredient_id, ingredient.quantity_grams);
                    });
                } else {
                    // Add at least one empty row
                    addIngredientRowToEditModal(ingredients);
                }
                
                // Show modal
                editDishModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error fetching dish data:', error);
                alert('Failed to load dish data for editing');
            }
        }

        // Function to add ingredient row to edit modal
        function addIngredientRowToEditModal(ingredients, selectedId = '', quantity = '') {
            const ingredientRow = document.createElement('div');
            ingredientRow.className = 'ingredient-row flex items-center space-x-2 mb-2';
            
            ingredientRow.innerHTML = `
                <select class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent ingredient-select">
                    <option value="">Select ingredient</option>
                    ${ingredients.map(ing => 
                        `<option value="${ing.ingredient_id}" ${ing.ingredient_id == selectedId ? 'selected' : ''}>${ing.ingredient_name}</option>`
                    ).join('')}
                </select>
                <input type="number" placeholder="Quantity (grams)" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent ingredient-quantity" step="0.01" min="0" value="${quantity || ''}">
                <button type="button" class="text-red-500 hover:text-red-700 remove-ingredient">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            
            editIngredientsContainer.appendChild(ingredientRow);
        }

        // Close modal functions
        const closeEditModalFunction = () => {
            editDishModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('edit-dish-form').reset();
            editIngredientsContainer.innerHTML = '';
            document.getElementById('edit-dish-image').value = '';
            document.getElementById('edit-file-name').textContent = 'Choose an image file';
        };

        closeEditModal.addEventListener('click', closeEditModalFunction);
        cancelEditDish.addEventListener('click', closeEditModalFunction);

        // Close modal when clicking outside
        editDishModal.addEventListener('click', (e) => {
            if (e.target === editDishModal) {
                closeEditModalFunction();
            }
        });

        // Add new ingredient row to edit modal
        addEditIngredientBtn.addEventListener('click', async () => {
            const ingredients = await fetchIngredients();
            addIngredientRowToEditModal(ingredients);
        });

        // Remove ingredient row from edit modal
        editIngredientsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-ingredient')) {
                e.target.closest('.ingredient-row').remove();
            }
        });

        // Edit dish image upload preview functionality
        document.getElementById('edit-dish-image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('edit-image-preview-container');
            const previewImage = document.getElementById('edit-image-preview');
            const fileNameDisplay = document.getElementById('edit-file-name');
            
            if (file) {
                fileNameDisplay.textContent = file.name;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = 'Choose an image file';
                // Don't hide the container as we might want to keep showing the current image
            }
        });

        // Handle edit form submission
        document.getElementById('edit-dish-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Collect form data
            const dishData = {
                dish_id: document.getElementById('edit-dish-id').value,
                name: document.getElementById('edit-dish-name').value,
                description: document.getElementById('edit-dish-description').value,
                category: document.getElementById('edit-dish-category').value,
                price: document.getElementById('edit-dish-price').value,
                capital: document.getElementById('edit-dish-capital').value,
                status: document.getElementById('edit-dish-status').value,
                image: document.getElementById('edit-dish-image').files[0],
                ingredients: []
            };
            
            // Collect ingredients data
            const ingredientRows = editIngredientsContainer.querySelectorAll('.ingredient-row');
            ingredientRows.forEach(row => {
                const ingredientId = row.querySelector('.ingredient-select').value;
                const quantity = row.querySelector('.ingredient-quantity').value;
                
                if (ingredientId && quantity) {
                    dishData.ingredients.push({
                        ingredient_id: ingredientId,
                        quantity_grams: quantity
                    });
                }
            });
            
            // Validate
            if (!dishData.name || !dishData.price || !dishData.capital || !dishData.category) {
                alert('Please fill in all required fields');
                return;
            }
            
            if (dishData.ingredients.length === 0) {
                alert('Please add at least one ingredient');
                return;
            }
            
            // Submit to server
            try {
                const formData = new FormData();
                formData.append('dish_id', dishData.dish_id);
                formData.append('name', dishData.name);
                formData.append('description', dishData.description);
                formData.append('category', dishData.category);
                formData.append('price', dishData.price);
                formData.append('capital', dishData.capital);
                formData.append('status', dishData.status);
                if (dishData.image) {
                    formData.append('image', dishData.image);
                }
                formData.append('ingredients', JSON.stringify(dishData.ingredients));
                
                const response = await fetch('menu_handlers/update_dish.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Dish updated successfully!');
                    closeEditModalFunction();
                    // Refresh the dishes table
                    $('#menu-table').DataTable().ajax.reload(null, false);
                } else {
                    alert('Error: ' + (result.message || 'Failed to update dish'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the dish');
            }
        });


        // Package Modal functionality
        const packageModal = document.getElementById('package-modal');
        const addPackageBtn = document.getElementById('add-package-btn');
        const closePackageModal = document.getElementById('close-package-modal');
        const cancelPackage = document.getElementById('cancel-package');
        const addDishesBtn = document.getElementById('add-dish');
        const dishesContainer = document.getElementById('dishes-container');

        // Function to fetch and populate dishes
        async function populateDishes() {
            try {
                const response = await fetch('menu_handlers/get_dishesForPackageModal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'query=SELECT dish_id, dish_name, dish_category, price, capital FROM `dishes_tb` WHERE status = "active"'
                });
                
                const dishes = await response.json();
                
                // Clear existing options and group by category
                const categoryMap = {};
                dishes.forEach(dish => {
                    if (!categoryMap[dish.dish_category]) {
                        categoryMap[dish.dish_category] = [];
                    }
                    categoryMap[dish.dish_category].push(dish);
                });
                
                // Update all dish selects
                document.querySelectorAll('.dish-select').forEach(select => {
                    // Save current value
                    const currentValue = select.value;
                    
                    // Clear existing options
                    select.innerHTML = '<option value="">Select Dish</option>';
                    
                    // Add options grouped by category
                    for (const [category, categoryDishes] of Object.entries(categoryMap)) {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = category;
                        
                        categoryDishes.forEach(dish => {
                            const option = document.createElement('option');
                            option.value = dish.dish_id;
                            option.textContent = `${dish.dish_name} (₱${dish.price})`;
                            option.dataset.price = dish.price;
                            option.dataset.capital = dish.capital;
                            optgroup.appendChild(option);
                        });
                        
                        select.appendChild(optgroup);
                    }
                    
                    // Restore value if it exists
                    if (currentValue) {
                        select.value = currentValue;
                    }
                });
                
                return dishes;
            } catch (error) {
                console.error('Error fetching dishes:', error);
                return [];
            }
        }

        // Function to calculate total price and capital
        function calculateTotals() {
            let totalPrice = 0;
            let totalCapital = 0;
            
            document.querySelectorAll('.dish-row').forEach(row => {
                const select = row.querySelector('.dish-select');
                const quantityInput = row.querySelector('.dish-quantity');
                
                if (select && select.value && quantityInput) {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price) || 0;
                    const capital = parseFloat(selectedOption.dataset.capital) || 0;
                    const quantity = parseInt(quantityInput.value) || 1;
                    
                    totalPrice += price * quantity;
                    totalCapital += capital * quantity;
                }
            });
            
            document.getElementById('package-price').value = totalPrice.toFixed(2);
            document.getElementById('package-capital').value = totalCapital.toFixed(2);
        }

        dishesContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('dish-select') || e.target.classList.contains('dish-quantity')) {
                calculateTotals();
            }
        });

        // Initialize when modal opens
        addPackageBtn.addEventListener('click', async () => {
            packageModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            await populateDishes();
        });

        // Form submission
        document.getElementById('package-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Collect package data
            const packageData = {
                name: document.getElementById('package-name').value,
                description: document.getElementById('package-description').value,
                price: document.getElementById('package-price').value,
                capital: document.getElementById('package-capital').value,
                type: document.getElementById('package-type').value,
                dishes: []
            };
            
            // Validate price and capital are not negative
            if (packageData.price < 0 || packageData.capital < 0) {
                alert('Price and capital cannot be negative values');
                return;
            }
            
            // Validate price is greater than capital
            if (packageData.price <= packageData.capital) {
                alert('Price must be greater than capital cost');
                return;
            }

            // Collect dish data
            document.querySelectorAll('.dish-row').forEach(row => {
                const select = row.querySelector('.dish-select');
                const quantityInput = row.querySelector('.dish-quantity');
                
                if (select && select.value && quantityInput) {
                    packageData.dishes.push({
                        dish_id: select.value,
                        quantity: quantityInput.value
                    });
                }
            });
            
            try {
                const response = await fetch('menu_handlers/add_menu_packages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(packageData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Package created successfully!');
                    closePackageModalFunction();
                    // You might want to refresh the packages list here
                    $('#packages-table').DataTable().ajax.reload(null, false);
                } else {
                    alert('Error creating package: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to create package. Please try again.');
            }
        });

        // Initial setup
        document.addEventListener('DOMContentLoaded', () => {
            // Clone the initial dish row template and remove it from DOM
            const initialDish = dishesContainer.querySelector('.dish-row');
            dishesContainer.innerHTML = '';
            dishesContainer.appendChild(initialDish.cloneNode(true));
        });

        // Close modal functions
        const closePackageModalFunction = () => {
            packageModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('package-form').reset();
            const initialDish = dishesContainer.querySelector('.dish-row');
            dishesContainer.innerHTML = '';
            dishesContainer.appendChild(initialDish.cloneNode(true));
        };

        closePackageModal.addEventListener('click', closePackageModalFunction);
        cancelPackage.addEventListener('click', closePackageModalFunction);

        // Close modal when clicking outside
        packageModal.addEventListener('click', (e) => {
            if (e.target === packageModal) {
                closePackageModalFunction();
            }
        });

        // Add new dish row
        addDishesBtn.addEventListener('click', () => {
            const dishRow = document.querySelector('.dish-row').cloneNode(true);
            const removeBtn = dishRow.querySelector('.remove-dish');
            removeBtn.classList.remove('hidden');
            dishesContainer.appendChild(dishRow);
        });

        // Remove dish row
        dishesContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-dish')) {
                e.target.closest('.dish-row').remove();
                calculateTotals();
            }
        });

        //menu package table
        // Initialize Packages DataTable
        $(document).ready(function() {
            var packagesTable = $('#packages-table').DataTable({
                responsive: true,
                dom: '<"overflow-x-auto"rt><"flex flex-col sm:flex-row justify-between items-center mt-2"<"text-sm text-gray-600"i><"mt-2 sm:mt-0"p>>',
                lengthChange: false,
                pageLength: 10,
                searching: true,
                ajax: {
                    url: 'menu_handlers/get_packages.php',
                    type: 'GET',
                    dataSrc: ''
                },
                columns: [
                    { data: 'package_id' },
                    { data: 'package_name' },
                    { 
                        data: 'price',
                        render: function(data) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: 'type',
                        render: function(data) {
                            return data === 'buffet' ? 'Buffet' : 'Per Plate';
                        }
                    },
                    { 
                        data: 'status',
                        render: function(data, type, row) {
                            var statusClass = data === 'active' ? 'status-active' : 'status-inactive';
                            var statusText = data === 'active' ? 'Active' : 'Unavailable';
                            return `<span class="status-badge ${statusClass}">${statusText}</span>`;
                        }
                    },
                    {
                        data: 'package_id',
                        render: function(data) {
                            return `
                                <button class="action-btn text-rich-brown hover:text-deep-brown transition-colors duration-200 view-package-btn mr-2" data-id="${data}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn text-rich-brown hover:text-deep-brown transition-colors duration-200 edit-package-btn" data-id="${data}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            `;
                        },
                        orderable: false
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 3 },
                    { responsivePriority: 3, targets: 2 },
                    { responsivePriority: 4, targets: -1 }
                ],
                language: {
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No packages available",
                    infoFiltered: "(filtered from _MAX_ total packages)",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });

            // Move the search box to the custom input
            $('#packages-search').on('keyup', function() {
                packagesTable.search(this.value).draw();
            });

            $('#packages-table').on('click', '.edit-package-btn', function() {
                var packageId = $(this).data('id');
                console.log('Edit package with ID:', packageId);
                openEditPackageModal(packageId);
            });

            $('#packages-table').on('click', '.view-package-btn', function() {
                var packageId = $(this).data('id');
                console.log('View package with ID:', packageId);
                openViewPackageModal(packageId);
            });
        });

        // View Package Modal functionality
        const viewPackageModal = document.getElementById('view-package-modal');
        const closeViewPackageModal = document.getElementById('close-view-package-modal');
        const closeViewPackageBtn = document.getElementById('close-view-package-btn');

        // Function to open view package modal
        async function openViewPackageModal(packageId) {
            try {
                // Fetch package data
                const response = await fetch(`menu_handlers/get_package_details.php?id=${packageId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const packageData = await response.json();
                
                // Populate basic info
                document.getElementById('view-package-name').textContent = packageData.package_name;
                document.getElementById('view-package-description').textContent = packageData.package_description || 'No description available';
                document.getElementById('view-package-price').textContent = '₱' + parseFloat(packageData.price).toFixed(2);
                
                // Set type
                const typeText = packageData.type === 'buffet' ? 'Buffet' : 'Per Plate';
                document.getElementById('view-package-type').textContent = typeText;
                
                // Set status
                const statusText = packageData.status === 'active' ? 'Available' : 'Unavailable';
                const statusClass = packageData.status === 'active' ? 'text-green-600' : 'text-red-600';
                document.getElementById('view-package-status').textContent = statusText;
                document.getElementById('view-package-status').className = `text-lg font-semibold ${statusClass}`;
                
                // Group dishes by category
                const dishesContainer = document.getElementById('view-dishes-container');
                dishesContainer.innerHTML = '';
                
                if (packageData.dishes && packageData.dishes.length > 0) {
                    const categories = {};
                    
                    // Group dishes by category
                    packageData.dishes.forEach(dish => {
                        if (!categories[dish.dish_category]) {
                            categories[dish.dish_category] = [];
                        }
                        categories[dish.dish_category].push(dish);
                    });
                    
                    // Define the order of categories (appetizers first, desserts last)
                    const categoryOrder = [
                        'house-salad', 'italian-dish', 'spanish-dish', // Appetizers/salads
                        'pizza', 'burgers', 'pasta', 'pasta_caza', 'main-course', // Main courses
                        'desserts', 'drinks', 'coffee' // Desserts/drinks
                    ];
                    
                    // Sort categories according to our defined order
                    const sortedCategories = Object.keys(categories).sort((a, b) => {
                        const aIndex = categoryOrder.indexOf(a);
                        const bIndex = categoryOrder.indexOf(b);
                        return aIndex - bIndex;
                    });
                    
                    // Create sections for each category
                    sortedCategories.forEach(category => {
                        const categoryDiv = document.createElement('div');
                        categoryDiv.className = 'mb-4';
                        
                        // Convert category name to display format
                        const displayCategory = category.replace(/-/g, ' ').replace(/_/g, ' ');
                        const categoryTitle = document.createElement('h4');
                        categoryTitle.className = 'text-lg font-semibold text-deep-brown mb-2 border-b border-accent-brown pb-1';
                        categoryTitle.textContent = displayCategory.charAt(0).toUpperCase() + displayCategory.slice(1);
                        categoryDiv.appendChild(categoryTitle);
                        
                        // Create list of dishes
                        const dishList = document.createElement('ul');
                        dishList.className = 'space-y-2';
                        
                        categories[category].forEach(dish => {
                            const dishItem = document.createElement('li');
                            dishItem.className = 'flex justify-between items-center';
                            
                            const dishName = document.createElement('span');
                            dishName.className = 'text-rich-brown';
                            dishName.textContent = dish.dish_name;
                            
                            const dishQuantity = document.createElement('span');
                            dishQuantity.className = 'bg-warm-cream px-2 py-1 rounded text-deep-brown text-sm';
                            dishQuantity.textContent = `x${dish.quantity}`;
                            
                            dishItem.appendChild(dishName);
                            dishItem.appendChild(dishQuantity);
                            dishList.appendChild(dishItem);
                        });
                        
                        categoryDiv.appendChild(dishList);
                        dishesContainer.appendChild(categoryDiv);
                    });
                } else {
                    dishesContainer.innerHTML = '<p class="text-gray-500">No dishes in this package.</p>';
                }
                
                // Show modal
                viewPackageModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error fetching package data:', error);
                alert('Failed to load package details');
            }
        }

        // Close view modal functions
        const closeViewPackageModalFunction = () => {
            viewPackageModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        closeViewPackageModal.addEventListener('click', closeViewPackageModalFunction);
        closeViewPackageBtn.addEventListener('click', closeViewPackageModalFunction);

        // Close modal when clicking outside
        viewPackageModal.addEventListener('click', (e) => {
            if (e.target === viewPackageModal) {
                closeViewPackageModalFunction();
            }
        });

        // Edit Package Modal functionality
        const editPackageModal = document.getElementById('edit-package-modal');
        const closeEditPackageModal = document.getElementById('close-edit-package-modal');
        const cancelEditPackage = document.getElementById('cancel-edit-package');
        const addEditPackageDishBtn = document.getElementById('add-edit-package-dish');
        const editPackageDishesContainer = document.getElementById('edit-package-dishes-container');

        // Function to open edit package modal
        async function openEditPackageModal(packageId) {
            try {
                // Fetch package data
                const response = await fetch(`menu_handlers/get_package_details.php?id=${packageId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const packageData = await response.json();
                
                // Populate form fields
                document.getElementById('edit-package-id').value = packageData.package_id;
                document.getElementById('edit-package-name').value = packageData.package_name;
                document.getElementById('edit-package-description').value = packageData.package_description || '';
                document.getElementById('edit-package-price').value = packageData.price;
                document.getElementById('edit-package-capital').value = packageData.capital;
                document.getElementById('edit-package-status').value = packageData.status;
                document.getElementById('edit-package-type').value = packageData.type;
                
                // Clear and populate dishes
                editPackageDishesContainer.innerHTML = '';
                
                // First fetch all available dishes
                const dishesResponse = await fetch('menu_handlers/get_dishesForPackageModal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'query=SELECT dish_id, dish_name, dish_category, price, capital FROM `dishes_tb` WHERE status = "active"'
                });
                
                const allDishes = await dishesResponse.json();
                
                if (packageData.dishes && packageData.dishes.length > 0) {
                    packageData.dishes.forEach(dish => {
                        addDishRowToEditPackageModal(allDishes, dish.dish_id, dish.quantity);
                    });
                } else {
                    // Add at least one empty row
                    addDishRowToEditPackageModal(allDishes);
                }
                
                // Show modal
                editPackageModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error fetching package data:', error);
                alert('Failed to load package data for editing');
            }
        }

        // Function to add dish row to edit package modal
        function addDishRowToEditPackageModal(allDishes, selectedId = '', quantity = 1) {
            const dishRow = document.createElement('div');
            dishRow.className = 'dish-row flex items-center space-x-2 mb-2';
            
            // Group dishes by category
            const categoryMap = {};
            allDishes.forEach(dish => {
                if (!categoryMap[dish.dish_category]) {
                    categoryMap[dish.dish_category] = [];
                }
                categoryMap[dish.dish_category].push(dish);
            });
            
            // Create select element with optgroups
            const select = document.createElement('select');
            select.className = 'flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent dish-select';
            select.innerHTML = '<option value="">Select Dish</option>';
            
            // Add optgroups for each category
            for (const [category, dishes] of Object.entries(categoryMap)) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = category;
                
                dishes.forEach(dish => {
                    const option = document.createElement('option');
                    option.value = dish.dish_id;
                    option.textContent = `${dish.dish_name} (₱${dish.price})`;
                    option.dataset.price = dish.price;
                    option.dataset.capital = dish.capital;
                    if (dish.dish_id === selectedId) {
                        option.selected = true;
                    }
                    optgroup.appendChild(option);
                });
                
                select.appendChild(optgroup);
            }
            
            dishRow.innerHTML = `
                <input type="number" placeholder="Quantity" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent dish-quantity" min="1" value="${quantity}">
                <button type="button" class="text-red-500 hover:text-red-700 remove-dish">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            
            // Insert select at the beginning
            dishRow.insertBefore(select, dishRow.firstChild);
            
            editPackageDishesContainer.appendChild(dishRow);
        }

        // Close modal functions
        const closeEditPackageModalFunction = () => {
            editPackageModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('edit-package-form').reset();
            editPackageDishesContainer.innerHTML = '';
        };

        closeEditPackageModal.addEventListener('click', closeEditPackageModalFunction);
        cancelEditPackage.addEventListener('click', closeEditPackageModalFunction);

        // Close modal when clicking outside
        editPackageModal.addEventListener('click', (e) => {
            if (e.target === editPackageModal) {
                closeEditPackageModalFunction();
            }
        });

        // Add new dish row to edit package modal
        addEditPackageDishBtn.addEventListener('click', async () => {
            const dishesResponse = await fetch('menu_handlers/get_dishesForPackageModal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'query=SELECT dish_id, dish_name, dish_category, price, capital FROM `dishes_tb` WHERE status = "active"'
            });
            
            const allDishes = await dishesResponse.json();
            addDishRowToEditPackageModal(allDishes);
        });

        // Remove dish row from edit package modal
        editPackageDishesContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-dish')) {
                e.target.closest('.dish-row').remove();
                calculateEditPackageTotals();
            }
        });

        // Function to calculate totals for edit package
        function calculateEditPackageTotals() {
            let totalPrice = 0;
            let totalCapital = 0;
            
            editPackageDishesContainer.querySelectorAll('.dish-row').forEach(row => {
                const select = row.querySelector('.dish-select');
                const quantityInput = row.querySelector('.dish-quantity');
                
                if (select && select.value && quantityInput) {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price) || 0;
                    const capital = parseFloat(selectedOption.dataset.capital) || 0;
                    const quantity = parseInt(quantityInput.value) || 1;
                    
                    totalPrice += price * quantity;
                    totalCapital += capital * quantity;
                }
            });
            
            document.getElementById('edit-package-price').value = totalPrice.toFixed(2);
            document.getElementById('edit-package-capital').value = totalCapital.toFixed(2);
        }

        // Update totals when dish selection or quantity changes
        editPackageDishesContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('dish-select') || e.target.classList.contains('dish-quantity')) {
                calculateEditPackageTotals();
            }
        });

        // Handle edit package form submission
        document.getElementById('edit-package-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Collect package data
            const packageData = {
                package_id: document.getElementById('edit-package-id').value,
                name: document.getElementById('edit-package-name').value,
                description: document.getElementById('edit-package-description').value,
                price: document.getElementById('edit-package-price').value,
                capital: document.getElementById('edit-package-capital').value,
                type: document.getElementById('edit-package-type').value,
                status: document.getElementById('edit-package-status').value,
                dishes: []
            };
            
            // Validate price and capital are not negative
            if (packageData.price < 0 || packageData.capital < 0) {
                alert('Price and capital cannot be negative values');
                return;
            }
            
            // Validate price is greater than capital
            if (packageData.price <= packageData.capital) {
                alert('Price must be greater than capital cost');
                return;
            }

            // Collect dish data
            editPackageDishesContainer.querySelectorAll('.dish-row').forEach(row => {
                const select = row.querySelector('.dish-select');
                const quantityInput = row.querySelector('.dish-quantity');
                
                if (select && select.value && quantityInput) {
                    packageData.dishes.push({
                        dish_id: select.value,
                        quantity: quantityInput.value
                    });
                }
            });
            
            try {
                const response = await fetch('menu_handlers/update_package.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(packageData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Package updated successfully!');
                    closeEditPackageModalFunction();
                    // Refresh the packages table
                    $('#packages-table').DataTable().ajax.reload(null, false);
                } else {
                    alert('Error updating package: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update package. Please try again.');
            }
        });

        // Add this near the top of your script section to prevent negative inputs
        document.addEventListener('DOMContentLoaded', () => {
            // Prevent negative values in price and capital fields
            const preventNegativeInputs = (inputId) => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('change', function() {
                        if (this.value < 0) {
                            this.value = 0;
                            alert('Value cannot be negative');
                        }
                    });
                }
            };

            // Apply to all relevant fields
            preventNegativeInputs('package-price');
            preventNegativeInputs('package-capital');
            preventNegativeInputs('edit-package-price');
            preventNegativeInputs('edit-package-capital');
            preventNegativeInputs('dish-price');
            preventNegativeInputs('dish-capital');
            preventNegativeInputs('edit-dish-price');
            preventNegativeInputs('edit-dish-capital');
        });


    </script>
</body>
</html>