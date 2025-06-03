<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
        
        /* Modal transition */
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .modal-hidden {
            opacity: 0;
            transform: translateY(-20px);
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
                        <a href="admin_menu.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text">Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inventory.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
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
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
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
                        <h2 class="text-2xl font-bold text-deep-brown font-script">Employee Management</h2>
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
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <div class="bg-white rounded-lg shadow-md p-6 animate-on-scroll">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-deep-brown">Cashier Management</h3>
                        <button id="create-cashier-btn" class="bg-accent-brown hover:bg-deep-brown text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <i class="fas fa-user-plus mr-2"></i> Create Cashier
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-rich-brown text-warm-cream">
                                <tr>
                                    <th class="py-3 px-4 text-left">Name</th>
                                    <th class="py-3 px-4 text-left">Date Created</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="cashier-table-body">
                                <!-- Sample data - will be populated by JavaScript -->
                                <tr>
                                    <td class="py-3 px-4">Juan P. Dela Cruz Jr.</td>
                                    <td class="py-3 px-4">June 15, 2023</td>
                                    <td class="py-3 px-4 flex justify-center space-x-2">
                                        <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="archive-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition-colors duration-200">
                                            <i class="fas fa-archive"></i> Archive
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">Maria R. Santos</td>
                                    <td class="py-3 px-4">May 22, 2023</td>
                                    <td class="py-3 px-4 flex justify-center space-x-2">
                                        <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="archive-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition-colors duration-200">
                                            <i class="fas fa-archive"></i> Archive
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">Antonio B. Reyes</td>
                                    <td class="py-3 px-4">April 10, 2023</td>
                                    <td class="py-3 px-4 flex justify-center space-x-2">
                                        <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="archive-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition-colors duration-200">
                                            <i class="fas fa-archive"></i> Archive
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Create Cashier Modal -->
    <div id="create-cashier-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center border-b p-4 bg-rich-brown text-warm-cream rounded-t-lg">
                <h3 class="text-lg font-bold">Create New Cashier</h3>
                <button id="close-modal" class="text-warm-cream hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="cashier-form" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label for="fname" class="block text-sm font-medium text-deep-brown mb-1">First Name</label>
                        <input type="text" id="fname" name="fname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    </div>
                    <div class="col-span-1">
                        <label for="mname" class="block text-sm font-medium text-deep-brown mb-1">Middle Name</label>
                        <input type="text" id="mname" name="mname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    <div class="col-span-1">
                        <label for="lname" class="block text-sm font-medium text-deep-brown mb-1">Last Name</label>
                        <input type="text" id="lname" name="lname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    </div>
                    <div class="col-span-1">
                        <label for="suffix" class="block text-sm font-medium text-deep-brown mb-1">Suffix</label>
                        <select id="suffix" name="suffix" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                            <option value="">None</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="username" class="block text-sm font-medium text-deep-brown mb-1">Username</label>
                        <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    </div>
                    <div class="col-span-2">
                        <label for="phone" class="block text-sm font-medium text-deep-brown mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    </div>
                    <div class="col-span-1">
                        <label for="password" class="block text-sm font-medium text-deep-brown mb-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-span-1">
                        <label for="confirm-password" class="block text-sm font-medium text-deep-brown mb-1">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="cancel-btn" class="px-4 py-2 border border-gray-300 rounded-md text-deep-brown hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-accent-brown text-white rounded-md hover:bg-deep-brown transition-colors duration-200">
                        Create Cashier
                    </button>
                </div>
            </form>
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

        // Cashier Management Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modal = document.getElementById('create-cashier-modal');
            const createBtn = document.getElementById('create-cashier-btn');
            const closeBtn = document.getElementById('close-modal');
            const cancelBtn = document.getElementById('cancel-btn');
            const form = document.getElementById('cashier-form');
            
            // Toggle modal visibility
            function toggleModal() {
                modal.classList.toggle('modal-hidden');
            }
            
            // Event listeners for modal
            createBtn.addEventListener('click', toggleModal);
            closeBtn.addEventListener('click', toggleModal);
            cancelBtn.addEventListener('click', toggleModal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    toggleModal();
                }
            });
            
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const fname = document.getElementById('fname').value;
                const mname = document.getElementById('mname').value;
                const lname = document.getElementById('lname').value;
                const suffix = document.getElementById('suffix').value;
                const username = document.getElementById('username').value;
                const phone = document.getElementById('phone').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm-password').value;
                
                // Validate passwords match
                if (password !== confirmPassword) {
                    alert('Passwords do not match!');
                    return;
                }
                
                // Create full name
                let fullName = fname;
                if (mname) fullName += ` ${mname.charAt(0)}.`;
                fullName += ` ${lname}`;
                if (suffix) fullName += ` ${suffix}`;
                
                // Create new table row
                const tableBody = document.getElementById('cashier-table-body');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="py-3 px-4">${fullName}</td>
                    <td class="py-3 px-4">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="archive-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition-colors duration-200">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                    </td>
                `;
                
                // Add to top of table
                tableBody.insertBefore(newRow, tableBody.firstChild);
                
                // Reset form and close modal
                form.reset();
                toggleModal();
                
                // Add event listeners to new buttons
                addButtonListeners();
            });
            
            // Add event listeners to edit and archive buttons
            function addButtonListeners() {
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        // In a real app, you would populate the modal with the row's data
                        alert('Edit functionality would go here');
                    });
                });
                
                document.querySelectorAll('.archive-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to archive this cashier?')) {
                            this.closest('tr').remove();
                        }
                    });
                });
            }
            
            // Initialize button listeners
            addButtonListeners();
        });
    </script>
</body>
</html>