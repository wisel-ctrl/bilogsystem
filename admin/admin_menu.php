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
    <style>
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
<body class="bg-warm-cream font-serif">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown to-rich-brown text-white transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-6 border-b border-accent-brown">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-coffee text-2xl text-warm-cream"></i>
                    <h1 id="cafe-title" class="text-xl font-bold text-warm-cream font-script">Cafe Lilio</h1>
                </div>
            </div>
            
            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream transition-colors duration-200">
                            <i class="fas fa-chart-pie w-5"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookings.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span class="sidebar-text">Booking Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text">Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inventory.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-boxes w-5"></i>
                            <span class="sidebar-text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_expenses.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="sidebar-text">Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_employee_creation.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-user-plus w-5"></i>
                            <span class="sidebar-text">Employee Creation</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-deep-brown font-script">Menu Management</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-rich-brown">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span id="current-date"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-8 h-8 rounded-full border-2 border-accent-brown">
                            <span class="text-sm font-medium text-deep-brown">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
                        <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <!-- Menu Management Section -->
                <div class="bg-white rounded-xl shadow-lg p-6 animate-on-scroll">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-script">Dish Management</h3>
                        <button id="add-dish-btn" class="bg-rich-brown hover:bg-deep-brown text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Add New Dish</span>
                        </button>
                    </div>

                    <!-- Menu Table -->
                    <div class="overflow-x-auto">
                        <table id="menu-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr class="border-b-2 border-accent-brown">
                                    <th class="text-left p-4 font-semibold text-deep-brown">Dish ID</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Dish Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Category</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Status</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Capital</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 mt-8 animate-on-scroll delay-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-script">Menu Packages</h3>
                        <button id="add-package-btn" class="bg-rich-brown hover:bg-deep-brown text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Create New Package</span>
                        </button>
                    </div>

                    <!-- Packages Table -->
                    <div class="overflow-x-auto">
                        <table id="packages-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr class="border-b-2 border-accent-brown">
                                    <th class="text-left p-4 font-semibold text-deep-brown">Package ID</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Package Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Type</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Status</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Dish Creation Modal -->
                <div id="dish-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-deep-brown font-script">Create New Dish</h3>
                                <button id="close-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <form id="dish-form" class="space-y-6">
                                <!-- Dish Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dish Name</label>
                                    <input type="text" id="dish-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter dish name" required>
                                </div>

                                <!-- Dish Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Description</label>
                                    <textarea id="dish-description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter dish description"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dish Category</label>
                                    <select id="dish-category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" required>
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
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Price (₱)</label>
                                        <input type="number" id="dish-price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Capital (₱)</label>
                                        <input type="number" id="dish-capital" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                </div>

                                <!-- Dish Image Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dish Image</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative flex-1">
                                            <input type="file" id="dish-image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            <div class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-center cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                                <i class="fas fa-upload mr-2"></i>
                                                <span id="file-name">Choose an image file</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="image-preview-container" class="mt-4 hidden">
                                        <p class="text-sm text-gray-500 mb-2">Image Preview:</p>
                                        <img id="image-preview" src="#" alt="Preview" class="max-w-full h-auto max-h-48 rounded-lg border border-gray-200">
                                    </div>
                                </div>

                                <!-- Ingredients Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Ingredients</label>
                                    <div id="ingredients-container">
                                        <div class="ingredient-row flex items-center space-x-2 mb-2">
                                            <select class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent ingredient-select">
                                                <option value="">Select ingredient</option>
                                                <!-- Options will be populated by JavaScript -->
                                            </select>
                                            <input type="number" placeholder="Quantity (grams)" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent ingredient-quantity" step="0.01" min="0" value="">
                                            <button type="button" class="text-red-500 hover:text-red-700 remove-ingredient hidden">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" id="add-ingredient" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Ingredient</span>
                                    </button>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="button" id="cancel-dish" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-white transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-rich-brown text-white rounded-lg hover:bg-deep-brown transition-colors duration-200">
                                        Create Dish
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    
                <div id="package-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-deep-brown font-script">Create New Package</h3>
                                <button id="close-package-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <form id="package-form" class="space-y-6">
                                <!-- Package Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Package Name</label>
                                    <input type="text" id="package-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter package name" required>
                                </div>

                                <!-- Package Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Description</label>
                                    <textarea id="package-description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter package description"></textarea>
                                </div>

                                <!-- Price and Capital -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Price (₱)</label>
                                        <input type="number" id="package-price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Capital (₱)</label>
                                        <input type="number" id="package-capital" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Menu Type</label>
                                    <select id="package-type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent">
                                        <option value="buffet">Buffet</option>
                                        <option value="per_plate">Sit - On's</option>
                                    </select>
                                </div>

                                <!-- Dishes Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Add Dishes</label>
                                    <div id="dishes-container">
                                        <div class="dish-row flex items-center space-x-2 mb-2">
                                            <select class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent dish-select">
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
                                            <input type="number" placeholder="Quantity" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent dish-quantity" min="1" value="1">
                                            <button type="button" class="text-red-500 hover:text-red-700 remove-dish hidden">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" id="add-dish" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Dish</span>
                                    </button>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="button" id="cancel-package" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-white transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-rich-brown text-white rounded-lg hover:bg-deep-brown transition-colors duration-200">
                                        Create Package
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add this modal right after the dish creation modal in your HTML -->
                <div id="edit-dish-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-deep-brown font-script">Edit Dish</h3>
                                <button id="close-edit-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <form id="edit-dish-form" class="space-y-6">
                                <input type="hidden" id="edit-dish-id">
                                
                                <!-- Dish Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dish Name</label>
                                    <input type="text" id="edit-dish-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter dish name" required>
                                </div>

                                <!-- Dish Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Description</label>
                                    <textarea id="edit-dish-description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter dish description"></textarea>
                                </div>

                                <!-- Dish Category -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dish Category</label>
                                    <select id="edit-dish-category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" required>
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
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Price (₱)</label>
                                        <input type="number" id="edit-dish-price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Capital (₱)</label>
                                        <input type="number" id="edit-dish-capital" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                </div>

                                <!-- Dish Status -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Status</label>
                                    <select id="edit-dish-status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent">
                                        <option value="active">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                </div>

                                <!-- Dish Image Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dish Image</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative flex-1">
                                            <input type="file" id="edit-dish-image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            <div class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-center cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                                <i class="fas fa-upload mr-2"></i>
                                                <span id="edit-file-name">Choose an image file</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="edit-image-preview-container" class="mt-4">
                                        <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                                        <img id="edit-image-preview" src="#" alt="Preview" class="max-w-full h-auto max-h-48 rounded-lg border border-gray-200">
                                    </div>
                                </div>

                                <!-- Ingredients Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Ingredients</label>
                                    <div id="edit-ingredients-container">
                                        <!-- Ingredients will be populated here -->
                                    </div>
                                    <button type="button" id="add-edit-ingredient" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Ingredient</span>
                                    </button>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="button" id="cancel-edit-dish" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-white transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-rich-brown text-white rounded-lg hover:bg-deep-brown transition-colors duration-200">
                                        Update Dish
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- View Package Modal -->
                <div id="view-package-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-deep-brown font-script">Package Details</h3>
                                <button id="close-view-package-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <div class="space-y-6">
                                <!-- Package Info -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1">Package Name</label>
                                        <p id="view-package-name" class="text-lg font-semibold text-rich-brown">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1">Type</label>
                                        <p id="view-package-type" class="text-lg font-semibold text-rich-brown">-</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1">Price</label>
                                        <p id="view-package-price" class="text-lg font-semibold text-rich-brown">-</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-1">Status</label>
                                        <p id="view-package-status" class="text-lg font-semibold text-rich-brown">-</p>
                                    </div>
                                </div>

                                <!-- Package Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1">Description</label>
                                    <p id="view-package-description" class="text-gray-700">-</p>
                                </div>

                                <!-- Dishes Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dishes Included</label>
                                    <div id="view-dishes-container" class="space-y-4">
                                        <!-- Dishes will be grouped by category here -->
                                    </div>
                                </div>

                                <!-- Close Button -->
                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="button" id="close-view-package-btn" class="px-6 py-2 bg-rich-brown text-white rounded-lg hover:bg-deep-brown transition-colors duration-200">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Package Modal -->
                <div id="edit-package-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-deep-brown font-script">Edit Package</h3>
                                <button id="close-edit-package-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <form id="edit-package-form" class="space-y-6">
                                <input type="hidden" id="edit-package-id">
                                
                                <!-- Package Name -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Package Name</label>
                                    <input type="text" id="edit-package-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter package name" required>
                                </div>

                                <!-- Package Description -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Description</label>
                                    <textarea id="edit-package-description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="Enter package description"></textarea>
                                </div>

                                <!-- Price and Capital -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Price (₱)</label>
                                        <input type="number" id="edit-package-price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Capital (₱)</label>
                                        <input type="number" id="edit-package-capital" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent" placeholder="0.00" step="0.01" required>
                                    </div>
                                </div>

                                <!-- Status and Type -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Status</label>
                                        <select id="edit-package-status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent">
                                            <option value="active">Available</option>
                                            <option value="inactive">Unavailable</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-deep-brown mb-2">Menu Type</label>
                                        <select id="edit-package-type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent">
                                            <option value="buffet">Buffet</option>
                                            <option value="per_plate">Sit - On's</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dishes Section -->
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2">Dishes in Package</label>
                                    <div id="edit-package-dishes-container">
                                        <!-- Dishes will be populated here -->
                                    </div>
                                    <button type="button" id="add-edit-package-dish" class="text-rich-brown hover:text-deep-brown transition-colors duration-200 flex items-center space-x-1 mt-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Another Dish</span>
                                    </button>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="button" id="cancel-edit-package" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-white transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-rich-brown text-white rounded-lg hover:bg-deep-brown transition-colors duration-200">
                                        Update Package
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </main>
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
                ajax: {
                    url: 'menu_handlers/get_dishes.php', // Your PHP endpoint that returns JSON data
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
                            var statusClass = data === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            var statusText = data === 'active' ? 'Active' : 'Unavailable';
                            return `<span class="px-3 py-1 rounded-full text-sm ${statusClass}">${statusText}</span>`;
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
                            return `<button class="text-rich-brown hover:text-deep-brown transition-colors duration-200 edit-dish-btn" data-id="${data}">
                                        <i class="fas fa-edit"></i>
                                    </button>`;
                        },
                        orderable: false
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 1 }, // Dish Name
                    { responsivePriority: 2, targets: 3 }, // Status
                    { responsivePriority: 3, targets: 4 }, // Price
                    { responsivePriority: 4, targets: -1 } // Actions
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search dishes...",
                    lengthMenu: "Show _MENU_ dishes per page",
                    zeroRecords: "No dishes found",
                    info: "Showing _START_ to _END_ of _TOTAL_ dishes",
                    infoEmpty: "No dishes available",
                    infoFiltered: "(filtered from _MAX_ total dishes)"
                }
            });

            // You might want to add this to handle edit button clicks
            $('#menu-table').on('click', '.edit-dish-btn', function() {
                var dishId = $(this).data('id');
                // Handle edit functionality here
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
                ajax: {
                    url: 'menu_handlers/get_packages.php', // Your PHP endpoint that returns JSON data
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
                            // Convert type value to display text
                            if (data === 'buffet') {
                                return 'Buffet';
                            } else if (data === 'per_plate') {
                                return 'Per Plate';
                            }
                            return data; // Fallback
                        }
                    },
                    { 
                        data: 'status',
                        render: function(data, type, row) {
                            var statusClass = data === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            var statusText = data === 'active' ? 'Active' : 'Unavailable';
                            return `<span class="px-3 py-1 rounded-full text-sm ${statusClass}">${statusText}</span>`;
                        }
                    },
                    {
                        data: 'package_id',
                        render: function(data) {
                            return `
                                <button class="text-rich-brown hover:text-deep-brown transition-colors duration-200 view-package-btn" data-id="${data}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-rich-brown hover:text-deep-brown transition-colors duration-200 edit-package-btn" data-id="${data}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            `;
                        },
                        orderable: false
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 1 }, // Package Name
                    { responsivePriority: 2, targets: 3 }, // Type
                    { responsivePriority: 3, targets: 2 }, // Price
                    { responsivePriority: 4, targets: -1 } // Actions
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search packages...",
                    lengthMenu: "Show _MENU_ packages per page",
                    zeroRecords: "No packages found",
                    info: "Showing _START_ to _END_ of _TOTAL_ packages",
                    infoEmpty: "No packages available",
                    infoFiltered: "(filtered from _MAX_ total packages)"
                }
            });

            // Handle edit package button clicks
            $('#packages-table').on('click', '.edit-package-btn', function() {
                var packageId = $(this).data('id');
                // Handle edit functionality here
                console.log('Edit package with ID:', packageId);
                openEditPackageModal(packageId);
            });

            // Handle view package button clicks
            $('#packages-table').on('click', '.view-package-btn', function() {
                var packageId = $(this).data('id');
                // Handle view functionality here
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