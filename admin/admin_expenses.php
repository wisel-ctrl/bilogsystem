<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Expense Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap">
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
            color: #A0522D;
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

        /* Blur effect class */
        .blur-effect {
            filter: blur(5px);
            transition: filter 0.3s ease;
            pointer-events: none;
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
    <!-- Modal for adding expenses -->
    <div id="expense-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-deep-brown font-script">
                                Add New Expense
                            </h3>
                            <div class="mt-4">
                                <form id="expense-form">
                                    <div class="mb-4">
                                        <label for="expense-name" class="block text-sm font-medium text-rich-brown">Expense Name</label>
                                        <input type="text" id="expense-name" name="expense-name" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="expense-category" class="block text-sm font-medium text-rich-brown">Category</label>
                                        <select id="expense-category" name="expense-category" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown" required>
                                            <option value="utilities">Utilities</option>
                                            <option value="rent">Rent</option>
                                            <option value="salaries">Salaries</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="ingredients">Ingredients</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="expense-amount" class="block text-sm font-medium text-rich-brown">Amount</label>
                                        <input type="number" id="expense-amount" name="expense-amount" step="0.01" min="0" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="expense-notes" class="block text-sm font-medium text-rich-brown">Notes</label>
                                        <textarea id="expense-notes" name="expense-notes" rows="3" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="save-expense" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-accent-brown text-base font-medium text-white hover:bg-deep-brown focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-brown sm:ml-3 sm:w-auto sm:text-sm">
                        Save Expense
                    </button>
                    <button type="button" id="cancel-expense" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-brown sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing expenses -->
    <div id="edit-expense-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-deep-brown font-script">
                                Edit Expense
                            </h3>
                            <div class="mt-4">
                                <form id="edit-expense-form">
                                    <input type="hidden" id="edit-expense-id">
                                    <div class="mb-4">
                                        <label for="edit-expense-name" class="block text-sm font-medium text-rich-brown">Expense Name</label>
                                        <input type="text" id="edit-expense-name" name="edit-expense-name" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit-expense-category" class="block text-sm font-medium text-rich-brown">Category</label>
                                        <select id="edit-expense-category" name="edit-expense-category" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown" required>
                                            <option value="utilities">Utilities</option>
                                            <option value="rent">Rent</option>
                                            <option value="salaries">Salaries</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="ingredients">Ingredients</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit-expense-amount" class="block text-sm font-medium text-rich-brown">Amount</label>
                                        <input type="number" id="edit-expense-amount" name="edit-expense-amount" step="0.01" min="0" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit-expense-notes" class="block text-sm font-medium text-rich-brown">Notes</label>
                                        <textarea id="edit-expense-notes" name="edit-expense-notes" rows="3" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-accent-brown focus:border-accent-brown"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="update-expense" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-accent-brown text-base font-medium text-white hover:bg-deep-brown focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-brown sm:ml-3 sm:w-auto sm:text-sm">
                        Update Expense
                    </button>
                    <button type="button" id="cancel-edit-expense" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-brown sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

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
            <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[100]">
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
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Expense Records</h3>
                        <button id="add-expense-btn" class="bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            <span class="font-baskerville">Add Expense</span>
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="expenses-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rich-brown uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rich-brown uppercase tracking-wider">
                                        Expense Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rich-brown uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rich-brown uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rich-brown uppercase tracking-wider">
                                        Notes
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rich-brown uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
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

        // Expense Modal functionality
        const expenseModal = document.getElementById('expense-modal');
        const addExpenseBtn = document.getElementById('add-expense-btn');
        const cancelExpenseBtn = document.getElementById('cancel-expense');
        const saveExpenseBtn = document.getElementById('save-expense');

        // Open modal
        addExpenseBtn.addEventListener('click', () => {
            expenseModal.classList.remove('hidden');
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('expense-date').value = today;
        });

        // Close modal
        function closeModal() {
            expenseModal.classList.add('hidden');
            // Reset form
            document.getElementById('expense-form').reset();
        }

        // Close modal when clicking cancel
        cancelExpenseBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside the modal content
        expenseModal.addEventListener('click', (e) => {
            if (e.target === expenseModal) {
                closeModal();
            }
        });

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#expenses-table').DataTable({
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
                                <button onclick="editExpense(${data})" class="text-accent-brown hover:text-deep-brown mr-3">Edit</button>
                                <button onclick="deleteExpense(${data})" class="text-red-600 hover:text-red-900">Delete</button>
                            `;
                        }
                    }
                ],
                order: [[0, 'desc']] // Sort by date descending by default
            });

            // Refresh table every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 30000);
        });

        // Add expense
        document.getElementById('save-expense').addEventListener('click', function() {
            const name = document.getElementById('expense-name').value.trim();
            const category = document.getElementById('expense-category').value;
            const amount = parseFloat(document.getElementById('expense-amount').value);
            
            // Validation
            if (!name) {
                alert('Expense name is required');
                return;
            }
            
            if (!category) {
                alert('Category is required');
                return;
            }
            
            if (isNaN(amount)) {
                alert('Amount must be a valid number');
                return;
            }
            
            if (amount <= 0) {
                alert('Amount must be greater than 0');
                return;
            }

            const formData = {
                expense_name: name,
                expense_category: category,
                amount: amount,
                notes: document.getElementById('expense-notes').value.trim()
            };

            fetch('expense_handlers/add_expense.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    $('#expenses-table').DataTable().ajax.reload();
                    closeModal();
                    alert('Expense added successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the expense.');
            });
        });

        // Edit expense
        function editExpense(expenseId) {
            fetch('expense_handlers/get_expense.php?id=' + expenseId)
            .then(response => response.json())
            .then(data => {
                if(data) {
                    document.getElementById('edit-expense-id').value = data.expense_id;
                    document.getElementById('edit-expense-name').value = data.expense_name;
                    document.getElementById('edit-expense-category').value = data.expense_category;
                    document.getElementById('edit-expense-amount').value = data.amount;
                    document.getElementById('edit-expense-notes').value = data.notes;
                    
                    document.getElementById('edit-expense-modal').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching expense data.');
            });
        }

        // Update expense
        document.getElementById('update-expense').addEventListener('click', function() {
            const name = document.getElementById('edit-expense-name').value.trim();
            const category = document.getElementById('edit-expense-category').value;
            const amount = parseFloat(document.getElementById('edit-expense-amount').value);
            
            // Validation
            if (!name) {
                alert('Expense name is required');
                return;
            }
            
            if (!category) {
                alert('Category is required');
                return;
            }
            
            if (isNaN(amount)) {
                alert('Amount must be a valid number');
                return;
            }
            
            if (amount <= 0) {
                alert('Amount must be greater than 0');
                return;
            }

            const formData = {
                expense_id: document.getElementById('edit-expense-id').value,
                expense_name: name,
                expense_category: category,
                amount: amount,
                notes: document.getElementById('edit-expense-notes').value.trim()
            };

            fetch('expense_handlers/update_expense.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    $('#expenses-table').DataTable().ajax.reload();
                    document.getElementById('edit-expense-modal').classList.add('hidden');
                    alert('Expense updated successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the expense.');
            });
        });

        // Delete expense (actually just hide it)
        function deleteExpense(expenseId) {
            if(confirm('Are you sure you want to delete this expense?')) {
                fetch('expense_handlers/delete_expense.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ expense_id: expenseId })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        $('#expenses-table').DataTable().ajax.reload();
                        alert('Expense deleted successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the expense.');
                });
            }
        }

        // Close edit modal
        document.getElementById('cancel-edit-expense').addEventListener('click', function() {
            document.getElementById('edit-expense-modal').classList.add('hidden');
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !expenseModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    </script>
</body>
</html>