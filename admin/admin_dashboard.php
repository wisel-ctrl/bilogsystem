<?php
require_once 'admin_auth.php'; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio Ristorante - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'warm-cream': '#E8E0D5',
                    'rich-brown': '#8B4513',
                    'deep-brown': '#5D2F0F',
                    'accent-brown': '#A0522D',
                    'olive': '#808000',
                    'terra-cotta': '#E2725B'
                },
                fontFamily: {
                    'serif': ['Georgia', 'serif'],
                    'script': ['Playfair Display', 'serif'],
                    'baskerville': ['Libre Baskerville', 'serif']
                }
            }
        }
    }
    </script>
    <style>
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

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }

        /* Card hover effects */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Sidebar hover effects */
        .sidebar-link {
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

        /* Custom tooltip */
        .tooltip {
            position: relative;
        }

        .tooltip:before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px 10px;
            background: #5D2F0F;
            color: #E8E0D5;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .tooltip:hover:before {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body class="bg-warm-cream font-baskerville">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown to-rich-brown text-white transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-6 border-b border-accent-brown">
                <div class="flex items-center space-x-3">
                    <img src="../images/logo.png" alt="Caffè Lilio" class="w-10 h-10 rounded-full">
                    <div>
                        <h1 id="cafe-title" class="text-xl font-bold text-warm-cream font-script">Caffè Lilio</h1>
                        <p class="text-xs text-warm-cream/70 uppercase tracking-wider">Ristorante</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-accent-brown text-warm-cream transition-all duration-200 group">
                            <i class="fas fa-chart-pie w-5 group-hover:rotate-12 transition-transform"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookings.html" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-all duration-200 group">
                            <i class="fas fa-calendar-check w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="sidebar-text">Reservations</span>
                            <span class="ml-auto bg-terra-cotta text-white text-xs px-2 py-1 rounded-full">12</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_menu.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-all duration-200 group">
                            <i class="fas fa-utensils w-5 group-hover:rotate-12 transition-transform"></i>
                            <span class="sidebar-text">Menu</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inventory.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-all duration-200 group">
                            <i class="fas fa-boxes w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="sidebar-text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_expenses.html" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-all duration-200 group">
                            <i class="fas fa-receipt w-5 group-hover:rotate-12 transition-transform"></i>
                            <span class="sidebar-text">Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_employee_creation.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-all duration-200 group">
                            <i class="fas fa-user-plus w-5 group-hover:scale-110 transition-transform"></i>
                            <span class="sidebar-text">Staff</span>
                        </a>
                    </li>
                </ul>

                <div class="mt-8 px-3">
                    <div class="p-4 bg-deep-brown rounded-lg">
                        <h3 class="text-warm-cream text-sm font-semibold mb-2">Need Help?</h3>
                        <p class="text-warm-cream/70 text-xs mb-3">Contact our support team for assistance</p>
                        <a href="mailto:support@caffelilio.com" class="text-xs text-warm-cream hover:text-accent-brown transition-colors">
                            support@caffelilio.com
                        </a>
                    </div>
                </div>
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
                        <div>
                            <h2 class="text-2xl font-bold text-deep-brown font-script">Dashboard Overview</h2>
                            <p class="text-sm text-rich-brown/70">Welcome back, Administrator</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2 text-sm text-rich-brown tooltip" data-tooltip="Current Date & Time">
                            <i class="fas fa-calendar-alt"></i>
                            <span id="current-date"></span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <button class="tooltip relative" data-tooltip="Notifications">
                                    <i class="fas fa-bell text-rich-brown"></i>
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-terra-cotta text-white text-xs rounded-full flex items-center justify-center">3</span>
                                </button>
                            </div>
                            <a href="../logout.php" class="bg-rich-brown hover:bg-deep-brown text-warm-cream px-4 py-2 rounded-lg transition-colors duration-300 flex items-center space-x-2">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Sign Out</span>
                            </a>
                            <div class="flex items-center space-x-2 tooltip" data-tooltip="Admin Profile">
                                <img src="../images/admin-avatar.jpg" alt="Admin" class="w-8 h-8 rounded-full border-2 border-accent-brown">
                                <span class="text-sm font-medium text-deep-brown">Admin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card animate-on-scroll delay-100 bg-gradient-to-br from-rich-brown to-deep-brown rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm font-script">Today's Revenue</p>
                                <p class="text-2xl font-bold">₱2,450</p>
                                <div class="flex items-center text-warm-cream/70 text-xs mt-1">
                                    <i class="fas fa-arrow-up mr-1 text-green-400"></i>
                                    <span>12% from yesterday</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-warm-cream/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-coins text-2xl text-warm-cream/80"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card animate-on-scroll delay-200 bg-gradient-to-br from-accent-brown to-rich-brown rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm font-script">Active Reservations</p>
                                <p class="text-2xl font-bold">12</p>
                                <div class="flex items-center text-warm-cream/70 text-xs mt-1">
                                    <i class="fas fa-arrow-up mr-1 text-green-400"></i>
                                    <span>3 new today</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-warm-cream/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-2xl text-warm-cream/80"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card animate-on-scroll delay-300 bg-gradient-to-br from-deep-brown to-accent-brown rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm font-script">Popular Dishes</p>
                                <p class="text-2xl font-bold">Pasta</p>
                                <div class="flex items-center text-warm-cream/70 text-xs mt-1">
                                    <span>42 orders today</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-warm-cream/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-utensils text-2xl text-warm-cream/80"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card animate-on-scroll delay-400 bg-gradient-to-br from-rich-brown to-accent-brown rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm font-script">Customer Satisfaction</p>
                                <p class="text-2xl font-bold">95%</p>
                                <div class="flex items-center text-warm-cream/70 text-xs mt-1">
                                    <i class="fas fa-arrow-up mr-1 text-green-400"></i>
                                    <span>+2% this week</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-warm-cream/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-smile text-2xl text-warm-cream/80"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Revenue Analysis Chart -->
                    <div class="animate-on-scroll bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-deep-brown font-script">Revenue Analysis</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs rounded-full bg-rich-brown text-warm-cream">Daily</button>
                                <button class="px-3 py-1 text-xs rounded-full bg-warm-cream text-rich-brown">Weekly</button>
                                <button class="px-3 py-1 text-xs rounded-full bg-warm-cream text-rich-brown">Monthly</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Menu Performance Chart -->
                    <div class="animate-on-scroll delay-100 bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-deep-brown font-script">Popular Menu Items</h3>
                            <select class="text-sm border border-warm-cream rounded-lg px-3 py-1">
                                <option>Italian Dishes</option>
                                <option>Spanish Dishes</option>
                                <option>All Dishes</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="menuChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 animate-on-scroll bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-deep-brown font-script">Recent Activity</h3>
                            <button class="text-sm text-rich-brown hover:text-deep-brown transition-colors">View All</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors">
                                <div class="bg-green-100 rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-utensils text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-deep-brown">New reservation #1234</p>
                                    <p class="text-xs text-rich-brown">Table for 4 - Special Italian Menu</p>
                                </div>
                                <span class="text-xs text-rich-brown">5 min ago</span>
                            </div>
                            
                            <div class="flex items-center space-x-4 p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors">
                                <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-star text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-deep-brown">New Review</p>
                                    <p class="text-xs text-rich-brown">5★ rating - "Amazing Spanish paella!"</p>
                                </div>
                                <span class="text-xs text-rich-brown">1 hour ago</span>
                            </div>
                            
                            <div class="flex items-center space-x-4 p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors">
                                <div class="bg-orange-100 rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-deep-brown">Inventory Alert</p>
                                    <p class="text-xs text-rich-brown">Low stock: Italian olive oil</p>
                                </div>
                                <span class="text-xs text-rich-brown">3 hours ago</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="animate-on-scroll delay-100 bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <h3 class="text-xl font-bold text-deep-brown mb-6 font-script">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors text-center group">
                                <i class="fas fa-calendar-plus text-2xl text-rich-brown mb-2 group-hover:scale-110 transition-transform"></i>
                                <p class="text-sm font-medium text-deep-brown">New Reservation</p>
                            </button>
                            <button class="p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors text-center group">
                                <i class="fas fa-plus-circle text-2xl text-rich-brown mb-2 group-hover:scale-110 transition-transform"></i>
                                <p class="text-sm font-medium text-deep-brown">Add Menu Item</p>
                            </button>
                            <button class="p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors text-center group">
                                <i class="fas fa-clipboard-list text-2xl text-rich-brown mb-2 group-hover:scale-110 transition-transform"></i>
                                <p class="text-sm font-medium text-deep-brown">View Orders</p>
                            </button>
                            <button class="p-4 bg-warm-cream/30 rounded-lg hover:bg-warm-cream/40 transition-colors text-center group">
                                <i class="fas fa-cog text-2xl text-rich-brown mb-2 group-hover:scale-110 transition-transform"></i>
                                <p class="text-sm font-medium text-deep-brown">Settings</p>
                            </button>
                        </div>

                        <!-- Today's Summary -->
                        <div class="mt-6 p-4 bg-rich-brown rounded-lg text-warm-cream">
                            <h4 class="font-script text-lg mb-3">Today's Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Total Reservations</span>
                                    <span class="font-bold">24</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Available Tables</span>
                                    <span class="font-bold">8</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Special Requests</span>
                                    <span class="font-bold">3</span>
                                </div>
                            </div>
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

        // Set current date with time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            document.getElementById('current-date').textContent = 
                now.toLocaleDateString('en-US', dateOptions) + ' ' + 
                now.toLocaleTimeString('en-US', timeOptions);
        }
        updateDateTime();
        setInterval(updateDateTime, 60000);

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

        // Chart.js configurations
        // Revenue Analysis Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Revenue',
                    data: [65000, 72000, 68000, 78000, 82000, 84000, 90000],
                    borderColor: '#8B4513',
                    backgroundColor: 'rgba(139, 69, 19, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 60000,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Menu Sales Chart
        const menuCtx = document.getElementById('menuChart').getContext('2d');
        const menuChart = new Chart(menuCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paella', 'Pasta Carbonara', 'Risotto', 'Tapas', 'Pizza', 'Others'],
                datasets: [{
                    data: [30, 25, 15, 12, 10, 8],
                    backgroundColor: [
                        '#8B4513',
                        '#A0522D',
                        '#5D2F0F',
                        '#E2725B',
                        '#808000',
                        '#CD853F'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                family: 'Libre Baskerville'
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>