<?php
require_once 'admin_auth.php'; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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

        /* Enhanced UI Styles */
        .dashboard-card {
            @apply bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-warm-cream/30 p-6;
        }
        
        .stat-value {
            @apply text-3xl font-bold font-playfair tracking-tight;
        }
        
        .stat-label {
            @apply text-sm font-baskerville text-deep-brown/80;
        }

        .nav-link {
            @apply flex items-center space-x-3 p-4 rounded-xl transition-all duration-300 hover:bg-accent-brown/90 text-warm-cream/90 hover:text-warm-cream hover:translate-x-1;
        }

        .nav-link.active {
            @apply bg-accent-brown text-warm-cream shadow-md;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-brown);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--deep-brown);
        }
    </style>
</head>
<body class="bg-warm-cream/30 font-baskerville">
    <div class="flex h-screen overflow-hidden">
        <!-- Enhanced Sidebar -->
        <div id="sidebar" class="bg-gradient-to-br from-deep-brown via-rich-brown to-deep-brown text-white transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-8 border-b border-accent-brown/30">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-coffee text-3xl text-warm-cream"></i>
                    <h1 id="cafe-title" class="text-2xl font-bold text-warm-cream font-script">Cafe Lilio</h1>
                </div>
            </div>
            
            <nav class="mt-8 px-4 space-y-2">
                <div class="nav-link active">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="sidebar-text">Dashboard</span>
                </div>
                <a href="admin_bookings.html" class="nav-link">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span class="sidebar-text">Booking Requests</span>
                </a>
                <a href="admin_menu.html" class="nav-link">
                    <i class="fas fa-utensils w-5"></i>
                    <span class="sidebar-text">Menu Management</span>
                </a>
                <a href="admin_inventory.html" class="nav-link">
                    <i class="fas fa-boxes w-5"></i>
                    <span class="sidebar-text">Inventory</span>
                </a>
                <a href="admin_expenses.html" class="nav-link">
                    <i class="fas fa-receipt w-5"></i>
                    <span class="sidebar-text">Expenses</span>
                </a>
                <a href="admin_employee_creation.php" class="nav-link">
                    <i class="fas fa-user-plus w-5"></i>
                    <span class="sidebar-text">Employee Creation</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Enhanced Header -->
            <header class="bg-white shadow-md border-b border-warm-cream/20 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <button id="sidebar-toggle" class="text-rich-brown hover:text-deep-brown transition-colors duration-300 focus:outline-none">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                        <h2 class="text-3xl font-bold text-deep-brown font-playfair">Dashboard Overview</h2>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-rich-brown font-baskerville">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span id="current-date"></span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="../logout.php" class="bg-rich-brown hover:bg-deep-brown text-warm-cream px-6 py-2.5 rounded-xl transition-all duration-300 font-baskerville text-sm hover:shadow-lg">
                                Sign Out
                            </a>
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-10 h-10 rounded-full border-2 border-accent-brown object-cover">
                                <span class="text-sm font-medium text-deep-brown font-playfair">Admin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Enhanced Main Content Area -->
            <main class="flex-1 overflow-y-auto p-8">
                <!-- Revenue Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="animate-on-scroll delay-100 dashboard-card bg-gradient-to-br from-rich-brown to-deep-brown">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/90 text-sm font-baskerville">Today's Revenue</p>
                                <p class="stat-value text-warm-cream">₱2,450</p>
                                <p class="text-warm-cream/80 text-xs mt-2 font-baskerville">+12% from yesterday</p>
                            </div>
                            <div class="bg-warm-cream/10 p-4 rounded-xl">
                                <i class="fas fa-coins text-3xl text-warm-cream"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll delay-200 bg-gradient-to-br from-accent-brown to-rich-brown rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm">Weekly Revenue</p>
                                <p class="text-2xl font-bold">₱18,750</p>
                                <p class="text-warm-cream/70 text-xs mt-1">+8% from last week</p>
                            </div>
                            <i class="fas fa-chart-line text-3xl text-warm-cream/80"></i>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll delay-300 bg-gradient-to-br from-deep-brown to-accent-brown rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm">Monthly Revenue</p>
                                <p class="text-2xl font-bold">₱84,320</p>
                                <p class="text-warm-cream/70 text-xs mt-1">+15% from last month</p>
                            </div>
                            <i class="fas fa-calendar-alt text-3xl text-warm-cream/80"></i>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll delay-400 bg-gradient-to-br from-rich-brown to-accent-brown rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-warm-cream/80 text-sm">Yearly Revenue</p>
                                <p class="text-2xl font-bold">₱950,680</p>
                                <p class="text-warm-cream/70 text-xs mt-1">+22% from last year</p>
                            </div>
                            <i class="fas fa-trophy text-3xl text-warm-cream/80"></i>
                        </div>
                    </div>
                </div>

                <!-- Orders Served -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="animate-on-scroll delay-100 bg-white rounded-xl p-6 shadow-lg border border-warm-cream">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm">Today's Orders</p>
                                <p class="text-2xl font-bold text-rich-brown">124</p>
                            </div>
                            <i class="fas fa-shopping-bag text-3xl text-accent-brown"></i>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll delay-200 bg-white rounded-xl p-6 shadow-lg border border-warm-cream">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm">Weekly Orders</p>
                                <p class="text-2xl font-bold text-rich-brown">892</p>
                            </div>
                            <i class="fas fa-clipboard-list text-3xl text-accent-brown"></i>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll delay-300 bg-white rounded-xl p-6 shadow-lg border border-warm-cream">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm">Monthly Orders</p>
                                <p class="text-2xl font-bold text-rich-brown">3,847</p>
                            </div>
                            <i class="fas fa-chart-bar text-3xl text-accent-brown"></i>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll delay-400 bg-white rounded-xl p-6 shadow-lg border border-warm-cream">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm">Yearly Orders</p>
                                <p class="text-2xl font-bold text-rich-brown">45,320</p>
                            </div>
                            <i class="fas fa-award text-3xl text-accent-brown"></i>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Financial Overview -->
                <div class="animate-on-scroll dashboard-card mb-8">
                    <h3 class="text-2xl font-bold text-deep-brown mb-8 font-playfair">Financial Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center p-6 bg-warm-cream/5 rounded-xl hover:bg-warm-cream/10 transition-all duration-300">
                            <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-arrow-up text-2xl text-green-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-deep-brown font-playfair">Revenue</h4>
                            <p class="text-2xl font-bold text-green-600 mt-2">₱84,320</p>
                            <p class="text-sm text-rich-brown mt-1">This Month</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-red-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-arrow-down text-2xl text-red-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-deep-brown">Expenses</h4>
                            <p class="text-2xl font-bold text-red-600">₱45,680</p>
                            <p class="text-sm text-rich-brown">This Month</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-pie text-2xl text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-deep-brown">Profit</h4>
                            <p class="text-2xl font-bold text-blue-600">₱38,640</p>
                            <p class="text-sm text-rich-brown">This Month</p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="animate-on-scroll dashboard-card">
                        <h3 class="text-xl font-bold text-deep-brown mb-6 font-playfair">Revenue Analysis</h3>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Menu Sales Chart -->
                    <div class="animate-on-scroll delay-100 bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <h3 class="text-xl font-bold text-deep-brown mb-4 font-script">Top Menu Items</h3>
                        <div class="chart-container">
                            <canvas id="menuChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Season Trends and Customer Satisfaction -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Season Trends Chart -->
                    <div class="animate-on-scroll bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <h3 class="text-xl font-bold text-deep-brown mb-4 font-script">Seasonal Trends</h3>
                        <div class="chart-container">
                            <canvas id="seasonChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Customer Satisfaction -->
                    <div class="animate-on-scroll delay-100 bg-white rounded-xl shadow-lg p-6 border border-warm-cream">
                        <h3 class="text-xl font-bold text-deep-brown mb-4 font-script">Customer Satisfaction</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-rich-brown">Excellent</span>
                                <span class="text-sm font-bold text-deep-brown">65%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-rich-brown">Good</span>
                                <span class="text-sm font-bold text-deep-brown">25%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-rich-brown">Average</span>
                                <span class="text-sm font-bold text-deep-brown">8%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: 8%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-rich-brown">Poor</span>
                                <span class="text-sm font-bold text-deep-brown">2%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: 2%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Recent Activity -->
                <div class="animate-on-scroll dashboard-card">
                    <h3 class="text-2xl font-bold text-deep-brown mb-6 font-playfair">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 p-6 bg-warm-cream/5 rounded-xl hover:bg-warm-cream/10 transition-all duration-300">
                            <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center shadow-md">
                                <i class="fas fa-shopping-cart text-xl text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-base font-medium text-deep-brown font-playfair">New order #1234 received</p>
                                <p class="text-sm text-rich-brown mt-1">2 Cappuccino, 1 Croissant - ₱385</p>
                            </div>
                            <span class="text-xs text-rich-brown bg-warm-cream/20 px-3 py-1 rounded-full">5 min ago</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-warm-cream/30 rounded-lg">
                            <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-deep-brown">New employee added</p>
                                <p class="text-xs text-rich-brown">Maria Santos - Barista</p>
                            </div>
                            <span class="text-xs text-rich-brown">1 hour ago</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-warm-cream/30 rounded-lg">
                            <div class="bg-orange-100 rounded-full w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-orange-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-deep-brown">Low inventory warning</p>
                                <p class="text-xs text-rich-brown">Coffee beans running low (5 kg remaining)</p>
                            </div>
                            <span class="text-xs text-rich-brown">3 hours ago</span>
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

        // Chart.js configurations
        // Revenue Analysis Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [65000, 72000, 68000, 78000, 82000, 84000],
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
                labels: ['Cappuccino', 'Latte', 'Americano', 'Espresso', 'Frappe', 'Others'],
                datasets: [{
                    data: [35, 25, 15, 10, 8, 7],
                    backgroundColor: [
                        '#8B4513',
                        '#A0522D',
                        '#5D2F0F',
                        '#E8E0D5',
                        '#D2B48C',
                        '#CD853F'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Season Trends Chart
        const seasonCtx = document.getElementById('seasonChart').getContext('2d');
        const seasonChart = new Chart(seasonCtx, {
            type: 'bar',
            data: {
                labels: ['Spring', 'Summer', 'Fall', 'Winter'],
                datasets: [{
                    label: 'Revenue',
                    data: [180000, 250000, 220000, 200000],
                    backgroundColor: [
                        '#8B4513',
                        '#A0522D',
                        '#5D2F0F',
                        '#E8E0D5'
                    ]
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
                        min: 150000,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>