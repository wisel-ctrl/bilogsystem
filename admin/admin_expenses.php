<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Expense Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Modal styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 999;
        }

        .modal-container {
            position: relative;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            background: white;
            border-radius: 0.5rem;
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

        /* Add blur effect class */
        .blur-effect {
            filter: blur(5px);
            transition: filter 0.3s ease;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-warm-cream/50 font-baskerville">
    <!-- Header with Clock -->
    <div class="fixed top-0 right-0 p-4 text-deep-brown z-50 flex items-center space-x-4">
        <div class="text-right">
            <div id="current-time" class="text-xl font-playfair"></div>
            <div id="current-date" class="text-sm font-baskerville"></div>
        </div>
        <!-- Profile Dropdown -->
        <div class="relative">
            <button id="profile-button" class="flex items-center space-x-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-lg hover:bg-white/90 transition-all duration-200">
                <i class="fas fa-user-circle text-xl"></i>
                <span class="font-baskerville">Admin</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>
            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                <a href="#" class="block px-4 py-2 text-deep-brown hover:bg-warm-cream/20 transition-colors duration-200">
                    <i class="fas fa-user-cog mr-2"></i>Settings
                </a>
                <a href="logout.php" class="block px-4 py-2 text-deep-brown hover:bg-warm-cream/20 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div id="expense-modal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
        <div class="modal-backdrop"></div>
        <div class="dashboard-card rounded-lg max-w-2xl w-full modal-container">
            <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Add New Expense</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="expense-form" class="modal-body px-6 py-4 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Expense Name</label>
                    <input type="text" id="expense-name" name="expense-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
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
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Amount</label>
                    <input type="number" id="expense-amount" name="expense-amount" step="0.01" min="0" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Notes</label>
                    <textarea id="expense-notes" name="expense-notes" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville"></textarea>
                </div>
            </form>

            <div class="modal-footer px-6 py-4 border-t border-gray-200 flex space-x-3">
                <button type="button" id="cancel-expense" class="flex-1 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville px-6 py-2">
                    Cancel
                </button>
                <button type="button" id="save-expense" class="flex-1 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville px-6 py-2">
                    Save Expense
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div id="edit-expense-modal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
        <div class="modal-backdrop"></div>
        <div class="dashboard-card rounded-lg max-w-2xl w-full modal-container">
            <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Expense</h3>
                <button id="close-edit-modal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="edit-expense-form" class="modal-body px-6 py-4 space-y-6">
                <input type="hidden" id="edit-expense-id">
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Expense Name</label>
                    <input type="text" id="edit-expense-name" name="edit-expense-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
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
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Amount</label>
                    <input type="number" id="edit-expense-amount" name="edit-expense-amount" step="0.01" min="0" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Notes</label>
                    <textarea id="edit-expense-notes" name="edit-expense-notes" rows="3" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville"></textarea>
                </div>
            </form>

            <div class="modal-footer px-6 py-4 border-t border-gray-200 flex space-x-3">
                <button type="button" id="cancel-edit-expense" class="flex-1 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville px-6 py-2">
                    Cancel
                </button>
                <button type="button" id="update-expense" class="flex-1 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville px-6 py-2">
                    Update Expense
                </button>
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
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 relative z-0">
                <!-- Expense Management Section -->
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 mb-8">
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

                    <!-- Expense Table -->
                    <div class="overflow-x-auto">
                        <table id="expenses-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr class="border-b-2 border-accent-brown/30">
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

        // Modal functionality
        const expenseModal = document.getElementById('expense-modal');
        const addExpenseBtn = document.getElementById('add-expense-btn');
        const closeModal = document.getElementById('close-modal');
        const cancelExpenseBtn = document.getElementById('cancel-expense');

        // Function to close modal with animation
        function closeModalWithAnimation(modal) {
            document.body.classList.remove('blur-effect');
            modal.querySelector('.dashboard-card').style.opacity = '0';
            modal.querySelector('.dashboard-card').style.transform = 'translateY(20px)';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Function to open modal with animation
        function openModalWithAnimation(modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('blur-effect');
            setTimeout(() => {
                modal.querySelector('.dashboard-card').style.opacity = '1';
                modal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
            }, 50);
        }

        // Open modal
        addExpenseBtn.addEventListener('click', () => {
            openModalWithAnimation(expenseModal);
        });

        // Close modal functions
        const closeModalFunction = () => {
            closeModalWithAnimation(expenseModal);
            document.getElementById('expense-form').reset();
        };

        closeModal.addEventListener('click', closeModalFunction);
        cancelExpenseBtn.addEventListener('click', closeModalFunction);

        // Close modal when clicking outside
        expenseModal.addEventListener('click', (e) => {
            if (e.target === expenseModal) {
                closeModalFunction();
            }
        });

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#expenses-table').DataTable({
                responsive: true,
                dom: '<"flex items-center justify-between mb-4"<"flex items-center space-x-2"l<"ml-2"f>>><"overflow-x-auto"t><"flex items-center justify-between mt-4"<"text-sm text-gray-600"i><"flex"p>>',
                pageLength: 10,
                ajax: {
                    url: 'expense_handlers/get_expenses.php',
                    dataSrc: ''
                },
                columns: [
                    { 
                        data: 'created_at',
                        render: function(data) {
                            const date = new Date(data);
                            return date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                    },
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
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'notes' },
                    {
                        data: 'expense_id',
                        render: function(data, type, row) {
                            return `
                                <button onclick="editExpense(${data})" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteExpense(${data})" class="text-red-600 hover:text-red-900 ml-3 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                        }
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 3 },
                    { responsivePriority: 3, targets: -1 }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search expenses...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ expenses",
                    infoEmpty: "No expenses available",
                    infoFiltered: "(filtered from _MAX_ total expenses)",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        previous: '<i class="fas fa-angle-left"></i>'
                    }
                },
                order: [[0, 'desc']], // Sort by date descending by default
                initComplete: function() {
                    // Enhance search input styling
                    $('.dataTables_filter input').addClass('px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville');
                    
                    // Enhance length menu select styling
                    $('.dataTables_length select').addClass('px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville');
                }
            });

            // Move the search box to the custom input
            $('#expense-search').on('keyup', function() {
                table.search(this.value).draw();
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
                    closeModalWithAnimation(expenseModal);
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
                    
                    openModalWithAnimation(document.getElementById('edit-expense-modal'));
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
                    closeModalWithAnimation(document.getElementById('edit-expense-modal'));
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

        // Delete expense
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
        document.getElementById('close-edit-modal').addEventListener('click', function() {
            closeModalWithAnimation(document.getElementById('edit-expense-modal'));
        });

        document.getElementById('cancel-edit-expense').addEventListener('click', function() {
            closeModalWithAnimation(document.getElementById('edit-expense-modal'));
        });

        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!expenseModal.classList.contains('hidden')) {
                    closeModalWithAnimation(expenseModal);
                }
                if (!document.getElementById('edit-expense-modal').classList.contains('hidden')) {
                    closeModalWithAnimation(document.getElementById('edit-expense-modal'));
                }
            }
        });

        // Prevent negative inputs
        document.addEventListener('DOMContentLoaded', () => {
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

            preventNegativeInputs('expense-amount');
            preventNegativeInputs('edit-expense-amount');
        });

        // Clock functionality
        function updateClock() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            const dateElement = document.getElementById('current-date');
            
            // Format time
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
            
            // Format date
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.textContent = now.toLocaleDateString('en-US', options);
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Profile dropdown functionality
        const profileButton = document.getElementById('profile-button');
        const profileDropdown = document.getElementById('profile-dropdown');

        profileButton.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>