<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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

        /* Add this to your existing style section */
        #profileMenu {
            z-index: 9999 !important;
            transform: translateY(0) !important;
        }

        header {
            z-index: 50;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.75rem;
            border: 1px solid #d1d5db;
            margin-left: 0.25rem;
            border-radius: 0.375rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #A0522D;
            color: white !important;
            border-color: #A0522D;
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
                        'serif': ['Georgia', 'serif'],
                        'script': ['Brush Script MT', 'cursive']
                    }
                }
            }
        }
    </script>
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
                        <a href="admin_bookings.html" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span class="sidebar-text font-baskerville">Booking Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_menu.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-warm-cream/10 text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
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
            <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[100]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-deep-brown hover:text-rich-brown transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-deep-brown font-playfair">Inventory Management</h2>
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
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 relative z-0">
                <!-- Inventory Management Section -->
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Inventory Items</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-64">
                                <input type="text" id="inventory-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Search ingredients...">
                            </div>
                            <button id="add-ingredient-btn" class="w-52 h-10 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus"></i>
                                <span class="font-baskerville">Add Ingredient</span>
                            </button>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="overflow-x-auto">
                        <table id="ingredients-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr class="border-b-2 border-accent-brown/30">
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Category</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Quantity</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Total Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Ingredient Modal -->
    <div id="add-ingredient-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Add New Ingredient</h3>
                    <button id="close-add-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="add-ingredient-form" class="space-y-6">
                    <div>
                        <label for="ingredient-name" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Name</label>
                        <input type="text" id="ingredient-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter ingredient name" required>
                    </div>

                    <div>
                        <label for="ingredient-category" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
                        <select id="ingredient-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                            <option value="">Select category</option>
                            <option value="produce" title="Fresh fruits and vegetables">Produce</option>
                            <option value="protein" title="Meat, poultry, seafood, and plant proteins">Proteins</option>
                            <option value="dairy" title="Milk, cheese, butter, etc.">Dairy</option>
                            <option value="grains" title="Rice, pasta, flour, bread, etc.">Grains & Starches</option>
                            <option value="spices_herbs" title="Seasoning and herbs for flavor">Spices & Herbs</option>
                            <option value="oils_fats" title="Cooking oils, butter, and fats">Oils & Fats</option>
                            <option value="condiments" title="Sauces, vinegar, and condiments">Condiments & Sauces</option>
                            <option value="baking" title="Flour, sugar, yeast, baking powder, etc.">Baking Ingredients</option>
                            <option value="beverages" title="Juices, soft drinks, tea, coffee, etc.">Beverages</option>
                            <option value="canned" title="Canned fruits, beans, jams, etc.">Canned & Preserved Goods</option>
                            <option value="frozen" title="Frozen vegetables, meats, pre-cooked items">Frozen Ingredients</option>
                            <option value="nuts_seeds" title="Nuts, seeds, legumes, beans">Nuts, Seeds & Legumes</option>
                            <option value="other">Other..</option>
                        </select>
                    </div>

                    <div>
                        <label for="ingredient-quantity" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Quantity</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" id="ingredient-quantity" placeholder="e.g. 1.5" step="0.01" min="0"
                                class="w-full px-4 py-2 pr-12 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500 text-sm">
                                kg
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">1 kg = 1000 grams</p>
                    </div>

                    <div>
                        <label for="ingredient-price" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (per unit)</label>
                        <input type="text" id="ingredient-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter price" required>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-warm-cream/20">
                        <button type="button" id="cancel-add-ingredient" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                            Add Ingredient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Ingredient Modal -->
    <div id="edit-ingredient-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Ingredient</h3>
                    <button id="close-edit-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="edit-ingredient-form" class="space-y-6">
                    <input type="hidden" id="edit-ingredient-id">

                    <div>
                        <label for="edit-ingredient-name" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Name</label>
                        <input type="text" id="edit-ingredient-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                    </div>

                    <div>
                        <label for="edit-ingredient-category" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
                        <select id="edit-ingredient-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                            <option value="produce" title="Fresh fruits and vegetables">Produce</option>
                            <option value="protein" title="Meat, poultry, seafood, and plant proteins">Proteins</option>
                            <option value="dairy" title="Milk, cheese, butter, etc.">Dairy</option>
                            <option value="grains" title="Rice, pasta, flour, bread, etc.">Grains & Starches</option>
                            <option value="spices_herbs" title="Seasoning and herbs for flavor">Spices & Herbs</option>
                            <option value="oils_fats" title="Cooking oils, butter, and fats">Oils & Fats</option>
                            <option value="condiments" title="Sauces, vinegar, and condiments">Condiments & Sauces</option>
                            <option value="baking" title="Flour, sugar, yeast, baking powder, etc.">Baking Ingredients</option>
                            <option value="beverages" title="Juices, soft drinks, tea, coffee, etc.">Beverages</option>
                            <option value="canned" title="Canned fruits, beans, jams, etc.">Canned & Preserved Goods</option>
                            <option value="frozen" title="Frozen vegetables, meats, pre-cooked items">Frozen Ingredients</option>
                            <option value="nuts_seeds" title="Nuts, seeds, legumes, beans">Nuts, Seeds & Legumes</option>
                            <option value="other">Other..</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit-ingredient-quantity" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Quantity</label>
                        <input type="text" id="edit-ingredient-quantity" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                    </div>

                    <div>
                        <label for="edit-ingredient-price" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (per unit)</label>
                        <input type="text" id="edit-ingredient-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-warm-cream/20">
                        <button type="button" id="cancel-edit-ingredient" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Confirm Deletion</h3>
                    <button id="close-delete-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700 font-baskerville">Are you sure you want to delete this ingredient? This action cannot be undone.</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-delete" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="button" id="confirm-delete" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-baskerville">
                        Delete
                    </button>
                </div>
                <input type="hidden" id="ingredient-to-delete">
            </div>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const cafeTitle = document.getElementById('cafe-title');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');
            
            if (sidebar.classList.contains('w-16')) {
                cafeTitle.style.display = 'none';
                sidebarTexts.forEach(text => text.style.display = 'none');
            } else {
                cafeTitle.style.display = 'block';
                sidebarTexts.forEach(text => text.style.display = 'block');
            }
        });

        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
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

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#ingredients-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "inventory_handlers/fetch_ingredient.php",
                    "type": "POST"
                },
                "columns": [
                    {
                        "data": "ingredient_name",
                        "render": function(data, type, row) {
                            return data.replace(/\w\S*/g, function(txt) {
                                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                            });
                        }
                    },
                    {   "data": "category" },
                    { "data": "quantity", 
                        "render": function(data, type, row) {
                    return parseFloat(data).toFixed(2) + ' kg'; // Add kg suffix
                        } },
                    { 
                        "data": "price",
                        "render": function(data, type, row) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        "data": "total_price",
                        "render": function(data, type, row) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        "data": "ingredient_id",
                        "render": function(data, type, row) {
                            return `
                                <button onclick="editIngredient(${data})" class="text-accent-brown hover:text-deep-brown mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete(${data})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            `;
                        },
                        "orderable": false
                    }
                ],
                "order": [[0, 'asc']],
                "responsive": true,
                "dom": '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search ingredients...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });

            // Refresh table after adding/editing
            window.refreshTable = function() {
                table.ajax.reload(null, false);
            };
        });

        // Modal handling
        const addIngredientBtn = document.getElementById('add-ingredient-btn');
        const addIngredientModal = document.getElementById('add-ingredient-modal');
        const addIngredientForm = document.getElementById('add-ingredient-form');
        const closeAddModal = document.getElementById('close-add-modal');
        const cancelAddIngredient = document.getElementById('cancel-add-ingredient');
        
        const editIngredientModal = document.getElementById('edit-ingredient-modal');
        const closeEditModal = document.getElementById('close-edit-modal');
        const cancelEditIngredient = document.getElementById('cancel-edit-ingredient');
        
        const deleteConfirmModal = document.getElementById('delete-confirm-modal');
        const closeDeleteModal = document.getElementById('close-delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDeleteBtn = document.getElementById('confirm-delete');

        // Show add ingredient modal
        addIngredientBtn.addEventListener('click', () => {
            addIngredientModal.classList.remove('hidden');
        });

        // Close add ingredient modal
        closeAddModal.addEventListener('click', () => {
            addIngredientModal.classList.add('hidden');
        });

        cancelAddIngredient.addEventListener('click', () => {
            addIngredientModal.classList.add('hidden');
        });

        // Close edit ingredient modal
        closeEditModal.addEventListener('click', () => {
            editIngredientModal.classList.add('hidden');
        });

        cancelEditIngredient.addEventListener('click', () => {
            editIngredientModal.classList.add('hidden');
        });

        // Close delete confirmation modal
        closeDeleteModal.addEventListener('click', () => {
            deleteConfirmModal.classList.add('hidden');
        });

        cancelDelete.addEventListener('click', () => {
            deleteConfirmModal.classList.add('hidden');
        });

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === addIngredientModal) {
                addIngredientModal.classList.add('hidden');
            }
            if (event.target === editIngredientModal) {
                editIngredientModal.classList.add('hidden');
            }
            if (event.target === deleteConfirmModal) {
                deleteConfirmModal.classList.add('hidden');
            }
        });

        // Form submissions
        document.getElementById('add-ingredient-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('ingredient-name').value.trim();
            const category = document.getElementById('ingredient-category').value;
            const quantityInput = document.getElementById('ingredient-quantity').value;
            const priceInput = document.getElementById('ingredient-price').value;
            
            // Validate quantity
            if (!/^\d*\.?\d+$/.test(quantityInput)) {
                alert('Quantity must be a positive number (letters and symbols not allowed)');
                return;
            }
            
            const quantity = parseFloat(quantityInput);
            if (quantity <= 0) {
                alert('Quantity must be greater than zero');
                return;
            }
            
            // Validate price
            if (!/^\d*\.?\d+$/.test(priceInput)) {
                alert('Price must be a positive number (letters and symbols not allowed)');
                return;
            }
            
            const price = parseFloat(priceInput);
            if (price < 0) {
                alert('Price cannot be negative');
                return;
            }
            
            // Basic validation for other fields
            if (!name || !category) {
                alert('Please fill all fields');
                return;
            }
            
            try {
                // Send data to server
                const response = await fetch('inventory_handlers/add_ingredient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ingredient_name: name,
                        category: category,
                        quantity: quantity,
                        price: price
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to add ingredient');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and reset form
                    addIngredientModal.classList.add('hidden');
                    addIngredientForm.reset();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Ingredient added successfully!');
                } else {
                    throw new Error(result.message || 'Failed to add ingredient');
                }
                
            } catch (error) {
                console.error('Error adding ingredient:', error);
                alert('Error adding ingredient: ' + error.message);
            }
        });

        // Function to open the edit modal with ingredient data
        window.editIngredient = async function(ingredientId) {
            try {
                // Validate ingredientId
                if (!ingredientId) {
                    throw new Error('Ingredient ID is required');
                }

                // Fetch ingredient data
                const response = await fetch(`inventory_handlers/get_ingredient.php?id=${ingredientId}`);
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    throw new Error(errorData?.error || `HTTP error! status: ${response.status}`);
                }
                
                const ingredient = await response.json();
                
                // Debug: log the received data
                console.log('Received ingredient data:', ingredient);
                
                // Populate the form - add null checks
                if (ingredient) {
                    document.getElementById('edit-ingredient-id').value = ingredient.ingredient_id || '';
                    document.getElementById('edit-ingredient-name').value = ingredient.ingredient_name || '';
                    document.getElementById('edit-ingredient-category').value = ingredient.category || '';
                    document.getElementById('edit-ingredient-quantity').value = ingredient.quantity || '';
                    document.getElementById('edit-ingredient-price').value = ingredient.price || '';
                    
                    // Show the modal
                    editIngredientModal.classList.remove('hidden');
                } else {
                    throw new Error('Ingredient data is empty');
                }
                
            } catch (error) {
                console.error('Error fetching ingredient:', error);
                alert('Error fetching ingredient data: ' + error.message);
            }
        };

        // Form submission handler for edit
        document.getElementById('edit-ingredient-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                ingredient_id: document.getElementById('edit-ingredient-id').value,
                ingredient_name: document.getElementById('edit-ingredient-name').value,
                category: document.getElementById('edit-ingredient-category').value,
                quantity: document.getElementById('edit-ingredient-quantity').value,
                price: document.getElementById('edit-ingredient-price').value
            };
            
            try {
                // Send data to server
                const response = await fetch('inventory_handlers/update_ingredient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) {
                    throw new Error('Failed to update ingredient');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and refresh the ingredients list
                    editIngredientModal.classList.add('hidden');
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Ingredient updated successfully!');
                } else {
                    throw new Error(result.message || 'Failed to update ingredient');
                }
                
            } catch (error) {
                console.error('Error updating ingredient:', error);
                alert('Error updating ingredient: ' + error.message);
            }
        });

        // Delete confirmation
        window.confirmDelete = function(ingredientId) {
            document.getElementById('ingredient-to-delete').value = ingredientId;
            deleteConfirmModal.classList.remove('hidden');
        };

        // Delete handler
        confirmDeleteBtn.addEventListener('click', async function() {
            const ingredientId = document.getElementById('ingredient-to-delete').value;
            
            try {
                const response = await fetch('inventory_handlers/delete_ingredient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ingredient_id: ingredientId })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to delete ingredient');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and refresh the ingredients list
                    deleteConfirmModal.classList.add('hidden');
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Ingredient deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete ingredient');
                }
                
            } catch (error) {
                console.error('Error deleting ingredient:', error);
                alert('Error deleting ingredient: ' + error.message);
            }
        });
    </script>
</body>
</html>