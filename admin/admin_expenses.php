<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Expense Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }

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

        /* Profile menu and header z-index */
        #profileMenu {
            z-index: 49 !important;
            transform: translateY(0) !important;
        }

        header {
            z-index: 45;
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

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            width: 100%;
            max-width: 300px;
        }

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

        /* Modal styles */
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

        /* Add this to your existing style section */
        #expense-modal, #edit-expense-modal, #delete-confirm-modal {
            z-index: 1000 !important; /* Higher than anything else */
        }

        /* Ensure the main content doesn't create stacking context */
        .flex-1 {
            position: static;
        }

        /* Sidebar should have lower z-index than modals */
        #sidebar {
            z-index: 40;
        }

        /* Header should have lower z-index than modals */
        header {
            z-index: 50;
        }

        /* Add blur effect class */
        .blur-effect {
            filter: blur(5px);
            transition: filter 0.3s ease;
            pointer-events: none;
        }

        /* Add this to your existing styles */
        .modal-container {
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        .modal-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            background: white;
            z-index: 10;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
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

        /* Table styles */
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
                        'playfair': ['Playfair Display', 'serif'],
                        'baskerville': ['Libre Baskerville', 'serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-warm-cream font-baskerville">
    <!-- Modal for adding expenses -->
    <div id="expense-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="modal-header p-6 border-b border-warm-cream/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Add New Expense</h3>
                    <button id="close-expense-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="expense-form" class="modal-body flex-1 overflow-y-auto p-6 space-y-6">
                <div>
                    <label for="expense-name" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Expense Name</label>
                    <input type="text" id="expense-name" name="expense-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>

                <div>
                    <label for="expense-category" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
                    <select id="expense-category" name="expense-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <option value="utilities">Utilities</option>
                        <option value="rent">Rent</option>
                        <option value="salaries">Salaries</option>
                        <option value="equipment">Equipment</option>
                        <option value="ingredients">Ingredients</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label for="expense-amount" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Amount</label>
                    <input type="number" id="expense-amount" name="expense-amount" step="0.01" min="0" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>

                <div>
                    <label for="expense-notes" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Notes</label>
                    <textarea id="expense-notes" name="expense-notes" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville"></textarea>
                </div>
            </form>

            <div class="modal-footer p-6 border-t border-warm-cream/20">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-expense" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="submit" form="expense-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                        Add Expense
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing expenses -->
    <div id="edit-expense-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="modal-header p-6 border-b border-warm-cream/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Expense</h3>
                    <button id="close-edit-expense-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="edit-expense-form" class="modal-body flex-1 overflow-y-auto p-6 space-y-6">
                <input type="hidden" id="edit-expense-id">
                <div>
                    <label for="edit-expense-name" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Expense Name</label>
                    <input type="text" id="edit-expense-name" name="edit-expense-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>

                <div>
                    <label for="edit-expense-category" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
                    <select id="edit-expense-category" name="edit-expense-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <option value="utilities">Utilities</option>
                        <option value="rent">Rent</option>
                        <option value="salaries">Salaries</option>
                        <option value="equipment">Equipment</option>
                        <option value="ingredients">Ingredients</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label for="edit-expense-amount" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Amount</label>
                    <input type="number" id="edit-expense-amount" name="edit-expense-amount" step="0.01" min="0" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>

                <div>
                    <label for="edit-expense-notes" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Notes</label>
                    <textarea id="edit-expense-notes" name="edit-expense-notes" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville"></textarea>
                </div>
            </form>

            <div class="modal-footer p-6 border-t border-warm-cream/20">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-edit-expense" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="submit" form="edit-expense-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-8">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col">
            <div class="modal-header p-6 border-b border-warm-cream/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Confirm Deletion</h3>
                    <button id="close-delete-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body flex-1 overflow-y-auto p-6">
                <p class="text-gray-700 font-baskerville">Are you sure you want to delete this expense? This action cannot be undone.</p>
            </div>

            <div class="modal-footer p-6 border-t border-warm-cream/20">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-delete" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="button" id="confirm-delete" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-baskerville">
                        Delete
                    </button>
                </div>
            </div>
            <input type="hidden" id="expense-to-delete">
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown to-rich-brown text-white transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-6 border-b border-accent-brown">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-coffee text-2xl text-warm-cream"></i>
                    <h1 id="cafe-title" class="text-xl font-bold text-warm-cream font-playfair">Cafe Lilio</h1>
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
                        <a href="admin_inventory.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-boxes w-5"></i>
                            <span class="sidebar-text font-baskerville">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-warm-cream/10 text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
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
                        <h2 class="text-2xl font-bold text-deep-brown font-playfair">Expense Management</h2>
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
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Expense Records</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-64">
                                <input type="text" id="expense-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Search expenses...">
                            </div>
                            <button id="add-expense-btn" class="w-52 h-10 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus"></i>
                                <span class="font-baskerville">Add New Expense</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="expenses-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Date</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Expense Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Category</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Amount</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Notes</th>
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

        // Modal handling
        const addExpenseBtn = document.getElementById('add-expense-btn');
        const expenseModal = document.getElementById('expense-modal');
        const expenseForm = document.getElementById('expense-form');
        const closeExpenseModal = document.getElementById('close-expense-modal');
        
        const editExpenseModal = document.getElementById('edit-expense-modal');
        const closeEditExpenseModal = document.getElementById('close-edit-expense-modal');
        const cancelEditExpense = document.getElementById('cancel-edit-expense');
        
        const deleteConfirmModal = document.getElementById('delete-confirm-modal');
        const closeDeleteModal = document.getElementById('close-delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDeleteBtn = document.getElementById('confirm-delete');

        // Show add expense modal
        addExpenseBtn.addEventListener('click', () => {
            // Add blur to main content
            document.querySelector('.flex-1').classList.add('blur-effect');
            document.querySelector('#sidebar').classList.add('blur-effect');
            
            // Show modal
            expenseModal.classList.remove('hidden');
        });

        // Close add expense modal
        function closeAddExpenseModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            
            // Hide modal
            expenseModal.classList.add('hidden');
            
            // Reset form
            expenseForm.reset();
        }

        closeExpenseModal.addEventListener('click', closeAddExpenseModal);
        cancelExpense.addEventListener('click', closeAddExpenseModal);

        // Close edit expense modal
        function closeEditExpenseModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            
            // Hide modal
            editExpenseModal.classList.add('hidden');
            
            // Reset form
            document.getElementById('edit-expense-form').reset();
        }

        closeEditExpenseModal.addEventListener('click', closeEditExpenseModal);
        cancelEditExpense.addEventListener('click', closeEditExpenseModal);

        // Close delete confirmation modal
        function closeDeleteConfirmModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            
            // Hide modal
            deleteConfirmModal.classList.add('hidden');
            
            // Clear the expense ID
            document.getElementById('expense-to-delete').value = '';
        }

        closeDeleteModal.addEventListener('click', closeDeleteConfirmModal);
        cancelDelete.addEventListener('click', closeDeleteConfirmModal);

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === expenseModal) {
                closeAddExpenseModal();
            }
            if (event.target === editExpenseModal) {
                closeEditExpenseModal();
            }
            if (event.target === deleteConfirmModal) {
                closeDeleteConfirmModal();
            }
        });

        // Close modals when pressing Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                if (!expenseModal.classList.contains('hidden')) {
                    closeAddExpenseModal();
                }
                if (!editExpenseModal.classList.contains('hidden')) {
                    closeEditExpenseModal();
                }
                if (!deleteConfirmModal.classList.contains('hidden')) {
                    closeDeleteConfirmModal();
                }
            }
        });

        // Form submissions
        document.getElementById('expense-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('expense-name').value.trim();
            const category = document.getElementById('expense-category').value;
            const amountInput = document.getElementById('expense-amount').value;
            const notes = document.getElementById('expense-notes').value.trim();
            
            // Validate amount
            if (!/^\d*\.?\d+$/.test(amountInput)) {
                alert('Amount must be a positive number (letters and symbols not allowed)');
                return;
            }
            
            const amount = parseFloat(amountInput);
            if (amount <= 0) {
                alert('Amount must be greater than zero');
                return;
            }
            
            // Basic validation for other fields
            if (!name || !category) {
                alert('Please fill all required fields');
                return;
            }
            
            try {
                // Send data to server
                const response = await fetch('expense_handlers/add_expense.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        expense_name: name,
                        expense_category: category,
                        amount: amount,
                        notes: notes
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to add expense');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and reset form
                    closeAddExpenseModal();
                    expenseForm.reset();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Expense added successfully!');
                } else {
                    throw new Error(result.message || 'Failed to add expense');
                }
                
            } catch (error) {
                console.error('Error adding expense:', error);
                alert('Error adding expense: ' + error.message);
            }
        });

        // Function to open the edit modal with expense data
        window.editExpense = async function(expenseId) {
            try {
                // Validate expenseId
                if (!expenseId) {
                    throw new Error('Expense ID is required');
                }

                // Fetch expense data
                const response = await fetch(`expense_handlers/get_expense.php?id=${expenseId}`);
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    throw new Error(errorData?.error || `HTTP error! status: ${response.status}`);
                }
                
                const expense = await response.json();
                
                // Debug: log the received data
                console.log('Received expense data:', expense);
                
                // Populate the form
                if (expense) {
                    document.getElementById('edit-expense-id').value = expense.expense_id || '';
                    document.getElementById('edit-expense-name').value = expense.expense_name || '';
                    document.getElementById('edit-expense-category').value = expense.expense_category || '';
                    document.getElementById('edit-expense-amount').value = expense.amount || '';
                    document.getElementById('edit-expense-notes').value = expense.notes || '';
                    
                    // Add blur to main content
                    document.querySelector('.flex-1').classList.add('blur-effect');
                    document.querySelector('#sidebar').classList.add('blur-effect');
                    
                    // Show modal
                    editExpenseModal.classList.remove('hidden');
                } else {
                    throw new Error('Expense data is empty');
                }
                
            } catch (error) {
                console.error('Error fetching expense:', error);
                alert('Error fetching expense data: ' + error.message);
            }
        };

        // Form submission handler for edit
        document.getElementById('edit-expense-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                expense_id: document.getElementById('edit-expense-id').value,
                expense_name: document.getElementById('edit-expense-name').value.trim(),
                expense_category: document.getElementById('edit-expense-category').value,
                amount: document.getElementById('edit-expense-amount').value,
                notes: document.getElementById('edit-expense-notes').value.trim()
            };
            
            // Validate amount
            if (!/^\d*\.?\d+$/.test(formData.amount)) {
                alert('Amount must be a positive number (letters and symbols not allowed)');
                return;
            }
            
            const amount = parseFloat(formData.amount);
            if (amount <= 0) {
                alert('Amount must be greater than zero');
                return;
            }
            
            // Basic validation for other fields
            if (!formData.expense_name || !formData.expense_category) {
                alert('Please fill all required fields');
                return;
            }
            
            try {
                // Send data to server
                const response = await fetch('expense_handlers/update_expense.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) {
                    throw new Error('Failed to update expense');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and refresh the expenses list
                    closeEditExpenseModal();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Expense updated successfully!');
                } else {
                    throw new Error(result.message || 'Failed to update expense');
                }
                
            } catch (error) {
                console.error('Error updating expense:', error);
                alert('Error updating expense: ' + error.message);
            }
        });

        // Delete confirmation
        window.deleteExpense = function(expenseId) {
            document.getElementById('expense-to-delete').value = expenseId;
            
            // Add blur to main content
            document.querySelector('.flex-1').classList.add('blur-effect');
            document.querySelector('#sidebar').classList.add('blur-effect');
            
            // Show modal
            deleteConfirmModal.classList.remove('hidden');
        };

        // Delete handler
        confirmDeleteBtn.addEventListener('click', async function() {
            const expenseId = document.getElementById('expense-to-delete').value;
            
            try {
                const response = await fetch('expense_handlers/delete_expense.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ expense_id: expenseId })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to delete expense');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and refresh the expenses list
                    closeDeleteConfirmModal();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Expense deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete expense');
                }
                
            } catch (error) {
                console.error('Error deleting expense:', error);
                alert('Error deleting expense: ' + error.message);
            }
        });

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#expenses-table').DataTable({
                dom: '<"overflow-x-auto"rt><"flex flex-col sm:flex-row justify-between items-center mt-2"<"text-sm text-gray-600"i><"mt-2 sm:mt-0"p>>',
                ajax: {
                    url: 'expense_handlers/get_expenses.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'created_at' },
                    { 
                        data: 'expense_name',
                        render: function(data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    { 
                        data: 'expense_category',
                        render: function(data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    { 
                        data: 'amount',
                        render: function(data, type, row) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'notes' },
                    {
                        data: 'expense_id',
                        render: function(data, type, row) {
                            return `
                                <button class="action-btn text-rich-brown hover:text-deep-brown transition-colors duration-200 mr-2" onclick="editExpense(${data})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn text-red-500 hover:text-red-700 transition-colors duration-200" onclick="deleteExpense(${data})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            `;
                        },
                        "orderable": false
                    }
                ],
                order: [[0, 'desc']], // Sort by date descending by default
                "lengthChange": false,
                "pageLength": 10,
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });

            // Add search functionality
            $('#expense-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Refresh table every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 30000);
        });

        // Add profile dropdown functionality
        document.getElementById('profileDropdown').addEventListener('click', function() {
            const menu = document.getElementById('profileMenu');
            const icon = this.querySelector('.fa-chevron-down');
            
            menu.classList.toggle('hidden');
            menu.classList.toggle('opacity-0');
            icon.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const menu = document.getElementById('profileMenu');
            
            if (!dropdown.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                menu.classList.add('opacity-0');
                dropdown.querySelector('.fa-chevron-down').style.transform = 'rotate(0deg)';
            }
        });
    </script>
</body>
</html>