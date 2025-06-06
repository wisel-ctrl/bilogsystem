<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio Ristorante - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'italian-gold': '#D4AF37',
                        'warm-cream': '#FDF6E3',
                        'rich-brown': '#8B4513',
                        'deep-espresso': '#3C2415',
                        'accent-amber': '#F59E0B',
                        'sage-green': '#87A96B',
                        'burgundy': '#8B1538',
                        'soft-beige': '#F5F5DC'
                    },
                    fontFamily: {
                        'serif': ['Playfair Display', 'serif'],
                        'sans': ['Inter', 'sans-serif']
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'elegant': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'inner-soft': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)'
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom animations and transitions */
        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }
        
        .animate-slide-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .animate-slide-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .sidebar-item {
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        
        .sidebar-item:hover::before {
            left: 100%;
        }
        
        .metric-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #87A96B 0%, #D4AF37 100%);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Mobile responsive enhancements */
        @media (max-width: 768px) {
            .mobile-menu-open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-warm-cream via-soft-beige to-warm-cream font-sans">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Enhanced Sidebar -->
        <div id="sidebar" class="fixed md:relative z-50 bg-gradient-to-b from-deep-espresso via-rich-brown to-deep-espresso text-white transition-all duration-500 ease-in-out w-72 md:w-72 h-full shadow-elegant transform -translate-x-full md:translate-x-0">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-italian-gold/30 bg-gradient-to-r from-transparent to-italian-gold/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <i class="fas fa-crown text-3xl text-italian-gold"></i>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-accent-amber rounded-full animate-pulse"></div>
                        </div>
                        <div id="cafe-branding" class="transition-all duration-300">
                            <h1 class="text-xl font-bold text-italian-gold font-serif">Cafe Lilio</h1>
                            <p class="text-xs text-warm-cream/70 font-light">Ristorante Italiano</p>
                        </div>
                    </div>
                    <button id="close-sidebar" class="md:hidden text-warm-cream hover:text-italian-gold transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-4 flex-1 overflow-y-auto">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <div class="sidebar-item">
                        <a href="#" class="flex items-center space-x-4 p-4 rounded-xl bg-gradient-to-r from-italian-gold/20 to-accent-amber/10 text-italian-gold transition-all duration-300 group">
                            <div class="relative">
                                <i class="fas fa-chart-pie text-lg"></i>
                                <div class="absolute inset-0 bg-italian-gold/20 rounded-full scale-0 group-hover:scale-110 transition-transform duration-300"></div>
                            </div>
                            <span class="sidebar-text font-medium">Dashboard</span>
                            <div class="ml-auto w-2 h-2 bg-italian-gold rounded-full"></div>
                        </a>
                    </div>
                    
                    <!-- Event Bookings -->
                    <div class="sidebar-item">
                        <a href="#" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gradient-to-r hover:from-sage-green/20 hover:to-italian-gold/10 text-warm-cream/80 hover:text-warm-cream transition-all duration-300 group">
                            <i class="fas fa-calendar-heart text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="sidebar-text font-medium">Event Bookings</span>
                            <div class="ml-auto notification-badge">
                                <span class="bg-burgundy text-xs px-2 py-1 rounded-full font-bold">3</span>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Menu Management -->
                    <div class="sidebar-item">
                        <a href="#" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gradient-to-r hover:from-sage-green/20 hover:to-italian-gold/10 text-warm-cream/80 hover:text-warm-cream transition-all duration-300 group">
                            <i class="fas fa-utensils text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="sidebar-text font-medium">Menu Italiano</span>
                        </a>
                    </div>
                    
                    <!-- Inventory -->
                    <div class="sidebar-item">
                        <a href="#" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gradient-to-r hover:from-sage-green/20 hover:to-italian-gold/10 text-warm-cream/80 hover:text-warm-cream transition-all duration-300 group">
                            <i class="fas fa-warehouse text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="sidebar-text font-medium">Inventory</span>
                            <div class="ml-auto">
                                <i class="fas fa-exclamation-triangle text-accent-amber text-xs notification-badge"></i>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Financial Reports -->
                    <div class="sidebar-item">
                        <a href="#" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gradient-to-r hover:from-sage-green/20 hover:to-italian-gold/10 text-warm-cream/80 hover:text-warm-cream transition-all duration-300 group">
                            <i class="fas fa-chart-line text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="sidebar-text font-medium">Financial Reports</span>
                        </a>
                    </div>
                    
                    <!-- Staff Management -->
                    <div class="sidebar-item">
                        <a href="#" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gradient-to-r hover:from-sage-green/20 hover:to-italian-gold/10 text-warm-cream/80 hover:text-warm-cream transition-all duration-300 group">
                            <i class="fas fa-users text-lg group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="sidebar-text font-medium">Staff Management</span>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mt-8 p-4 bg-gradient-to-r from-italian-gold/10 to-sage-green/10 rounded-xl border border-italian-gold/20">
                    <h3 class="text-sm font-semibold text-italian-gold mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <button class="w-full text-left text-xs text-warm-cream/80 hover:text-warm-cream p-2 rounded-lg hover:bg-white/10 transition-colors">
                            <i class="fas fa-plus mr-2"></i>New Event Booking
                        </button>
                        <button class="w-full text-left text-xs text-warm-cream/80 hover:text-warm-cream p-2 rounded-lg hover:bg-white/10 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Update Menu
                        </button>
                        <button class="w-full text-left text-xs text-warm-cream/80 hover:text-warm-cream p-2 rounded-lg hover:bg-white/10 transition-colors">
                            <i class="fas fa-bell mr-2"></i>Send Notifications
                        </button>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Enhanced Header -->
            <header class="glass-effect shadow-soft border-b border-italian-gold/20 px-6 py-4 sticky top-0 z-30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <button id="mobile-menu-toggle" class="md:hidden text-deep-espresso hover:text-italian-gold transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold text-deep-espresso font-serif">Event Management Dashboard</h2>
                            <p class="text-sm text-rich-brown font-light">Welcome back, Administrator</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Date Display -->
                        <div class="hidden md:flex items-center space-x-2 text-sm text-rich-brown bg-white/70 px-4 py-2 rounded-xl shadow-inner-soft">
                            <i class="fas fa-calendar-alt text-italian-gold"></i>
                            <span id="current-date" class="font-medium"></span>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative text-deep-espresso hover:text-italian-gold transition-colors p-2 rounded-xl hover:bg-white/50">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-burgundy text-white text-xs w-5 h-5 rounded-full flex items-center justify-center notification-badge">3</span>
                            </button>
                        </div>
                        
                        <!-- Profile Section -->
                        <div class="flex items-center space-x-3 bg-white/70 rounded-xl px-4 py-2 shadow-inner-soft">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Admin Profile" class="w-10 h-10 rounded-full border-2 border-italian-gold shadow-soft">
                            <div class="hidden md:block text-sm">
                                <p class="font-semibold text-deep-espresso">Admin</p>
                                <p class="text-xs text-rich-brown">Administrator</p>
                            </div>
                            <button class="text-deep-espresso hover:text-italian-gold transition-colors">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </div>
                        
                        <!-- Logout Button -->
                        <a href="../logout.php" class="bg-gradient-to-r from-burgundy to-rich-brown hover:from-rich-brown hover:to-burgundy text-warm-cream px-6 py-2 rounded-xl transition-all duration-300 shadow-soft hover:shadow-elegant transform hover:scale-105 font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8">
                <!-- Key Performance Indicators -->
                <section class="animate-slide-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-deep-espresso font-serif">Revenue Overview</h3>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 bg-italian-gold text-white rounded-lg text-sm font-medium shadow-soft hover:shadow-elegant transition-all duration-300">Today</button>
                            <button class="px-4 py-2 bg-white text-deep-espresso rounded-lg text-sm font-medium shadow-soft hover:bg-italian-gold hover:text-white transition-all duration-300">Week</button>
                            <button class="px-4 py-2 bg-white text-deep-espresso rounded-lg text-sm font-medium shadow-soft hover:bg-italian-gold hover:text-white transition-all duration-300">Month</button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Today's Revenue -->
                        <div class="card-hover bg-gradient-to-br from-italian-gold via-accent-amber to-italian-gold text-white rounded-2xl p-6 shadow-elegant relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-white/20 rounded-xl p-3">
                                        <i class="fas fa-coins text-2xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm opacity-90">Today</p>
                                        <p class="text-xs opacity-70">+12% vs yesterday</p>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold mb-1">₱3,450</p>
                                <p class="text-sm opacity-90">Daily Revenue</p>
                            </div>
                        </div>
                        
                        <!-- Weekly Revenue -->
                        <div class="card-hover bg-gradient-to-br from-sage-green to-rich-brown text-white rounded-2xl p-6 shadow-elegant relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-white/20 rounded-xl p-3">
                                        <i class="fas fa-chart-line text-2xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm opacity-90">This Week</p>
                                        <p class="text-xs opacity-70">+8% vs last week</p>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold mb-1">₱24,150</p>
                                <p class="text-sm opacity-90">Weekly Revenue</p>
                            </div>
                        </div>
                        
                        <!-- Event Bookings -->
                        <div class="card-hover bg-gradient-to-br from-burgundy to-deep-espresso text-white rounded-2xl p-6 shadow-elegant relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-white/20 rounded-xl p-3">
                                        <i class="fas fa-calendar-heart text-2xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm opacity-90">This Month</p>
                                        <p class="text-xs opacity-70">+25% growth</p>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold mb-1">47</p>
                                <p class="text-sm opacity-90">Event Bookings</p>
                            </div>
                        </div>
                        
                        <!-- Customer Satisfaction -->
                        <div class="card-hover bg-gradient-to-br from-deep-espresso to-rich-brown text-white rounded-2xl p-6 shadow-elegant relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-white/20 rounded-xl p-3">
                                        <i class="fas fa-star text-2xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm opacity-90">Rating</p>
                                        <p class="text-xs opacity-70">Excellent feedback</p>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold mb-1">4.9</p>
                                <p class="text-sm opacity-90">Customer Rating</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Enhanced Charts Section -->
                <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Revenue Trends -->
                    <div class="animate-slide-in glass-effect rounded-2xl p-6 shadow-elegant border border-italian-gold/20">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-deep-espresso font-serif">Revenue Trends</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-italian-gold rounded-full"></div>
                                <span class="text-sm text-rich-brown">Revenue</span>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Event Types Distribution -->
                    <div class="animate-slide-in glass-effect rounded-2xl p-6 shadow-elegant border border-italian-gold/20">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-deep-espresso font-serif">Event Types</h3>
                            <button class="text-sm text-italian-gold hover:text-deep-espresso transition-colors">View Details</button>
                        </div>
                        <div class="chart-container">
                            <canvas id="eventChart"></canvas>
                        </div>
                    </div>
                </section>

                <!-- Booking Management Section -->
                <section class="animate-slide-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-deep-espresso font-serif">Recent Event Bookings</h3>
                        <button class="bg-italian-gold hover:bg-accent-amber text-white px-6 py-2 rounded-xl transition-all duration-300 shadow-soft hover:shadow-elegant transform hover:scale-105 font-medium">
                            <i class="fas fa-plus mr-2"></i>New Booking
                        </button>
                    </div>
                    
                    <div class="glass-effect rounded-2xl shadow-elegant border border-italian-gold/20 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-italian-gold/10 to-sage-green/10">
                                    <tr>
                                        <th class="text-left p-4 font-semibold text-deep-espresso">Event</th>
                                        <th class="text-left p-4 font-semibold text-deep-espresso">Client</th>
                                        <th class="text-left p-4 font-semibold text-deep-espresso">Date</th>
                                        <th class="text-left p-4 font-semibold text-deep-espresso">Guests</th>
                                        <th class="text-left p-4 font-semibold text-deep-espresso">Status</th>
                                        <th class="text-left p-4 font-semibold text-deep-espresso">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-italian-gold/10">
                                    <tr class="hover:bg-italian-gold/5 transition-colors">
                                        <td class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-burgundy to-deep-espresso rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-ring text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-deep-espresso">Wedding Reception</p>
                                                    <p class="text-sm text-rich-brown">Romantic Italian Setup</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <p class="font-medium text-deep-espresso">Maria & Giuseppe</p>
                                            <p class="text-sm text-rich-brown">maria.rossi@email.com</p>
                                        </td>
                                        <td class="p-4">
                                            <p class="font-medium text-deep-espresso">June 15, 2024</p>
                                            <p class="text-sm text-rich-brown">7:00 PM</p>
                                        </td>
                                        <td class="p-4">
                                            <span class="font-bold text-sage-green">120</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="bg-sage-green/20 text-sage-green px-3 py-1 rounded-full text-sm font-medium">Confirmed</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <button class="text-italian-gold hover:text-deep-espresso transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-sage-green hover:text-deep-espresso transition-colors">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-italian-gold/5 transition-colors">
                                        <td class="p-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-italian-gold to-accent-amber rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-birthday-cake text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-deep-espresso">Birthday Celebration</p>
                                                    <p class="text-sm text-rich-brown">Spanish Tapas Theme</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <p class="font-medium text-deep-espresso">Carlos Rodriguez</p>
                                            <p class="text-sm text-rich-brown">carlos.r@email.com</p>
                                        </td>
                                        <td class="p-4">
                                            <p class="font-medium text-deep-espresso">June 20, 2024</p>
                                            <p class="text-sm text-rich-brown">6:30 PM</p>
                                        </td>
                                        <td class="p-4">
                                            <span class="font-bold text-sage-green">45</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="bg-accent-amber/20 text-accent-amber px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex space-x-2">
                                                <button class="text-italian-gold hover:text-deep-espresso transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-sage-green hover:text-deep-espresso transition-colors">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Activity Feed -->
                <section class="animate-slide-in glass-effect rounded-2xl p-6 shadow-elegant border border-italian-gold/20">
                    <h3 class="text-xl font-bold text-deep-espresso font-serif mb-6">Recent Activity</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-sage-green/10 to-transparent rounded-xl border-l-4 border-sage-green">
                            <div class="bg-sage-green/20 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-plus text-sage-green"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-deep-espresso">New Event Booking Confirmed</p>
                                <p class="text-sm text-rich-brown mb-2">Anniversary celebration for 80 guests - Italian cuisine theme</p>
                                <div class="flex items-center space-x-4 text-xs text-rich-brown">
                                    <span><i class="fas fa-user mr-1"></i>Isabella Martinez</span>
                                    <span><i class="fas fa-clock mr-1"></i>15 minutes ago</span>
                                    <span class="bg-sage-green/20 text-sage-green px-2 py-1 rounded-full">₱35,000</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-italian-gold/10 to-transparent rounded-xl border-l-4 border-italian-gold">
                            <div class="bg-italian-gold/20 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-utensils text-italian-gold"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-deep-espresso">Menu Updated</p>
                                <p class="text-sm text-rich-brown mb-2">Added new Spanish paella options and seasonal Italian dishes</p>
                                <div class="flex items-center space-x-4 text-xs text-rich-brown">
                                    <span><i class="fas fa-user mr-1"></i>Chef Antonio</span>
                                    <span><i class="fas fa-clock mr-1"></i>1 hour ago</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-burgundy/10 to-transparent rounded-xl border-l-4 border-burgundy">
                            <div class="bg-burgundy/20 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-burgundy"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-deep-espresso">Inventory Alert</p>
                                <p class="text-sm text-rich-brown mb-2">Premium Italian wine stock running low - 12 bottles remaining</p>
                                <div class="flex items-center space-x-4 text-xs text-rich-brown">
                                    <span><i class="fas fa-warehouse mr-1"></i>Inventory System</span>
                                    <span><i class="fas fa-clock mr-1"></i>2 hours ago</span>
                                    <button class="bg-burgundy text-white px-3 py-1 rounded-full hover:bg-deep-espresso transition-colors">Reorder</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-accent-amber/10 to-transparent rounded-xl border-l-4 border-accent-amber">
                            <div class="bg-accent-amber/20 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-star text-accent-amber"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-deep-espresso">Customer Review</p>
                                <p class="text-sm text-rich-brown mb-2">"Exceptional Italian dining experience! Perfect for our corporate event."</p>
                                <div class="flex items-center space-x-4 text-xs text-rich-brown">
                                    <span><i class="fas fa-user mr-1"></i>Roberto Silva</span>
                                    <span><i class="fas fa-clock mr-1"></i>3 hours ago</span>
                                    <div class="flex text-accent-amber">
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quick Stats Grid -->
                <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Customer Satisfaction -->
                    <div class="animate-slide-in glass-effect rounded-2xl p-6 shadow-elegant border border-italian-gold/20">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-deep-espresso font-serif">Customer Satisfaction</h4>
                            <div class="text-2xl font-bold text-sage-green">92%</div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-rich-brown">Excellent</span>
                                <span class="font-semibold text-deep-espresso">68%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="progress-bar h-2 rounded-full" style="width: 68%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-rich-brown">Very Good</span>
                                <span class="font-semibold text-deep-espresso">24%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-sage-green h-2 rounded-full" style="width: 24%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-rich-brown">Good</span>
                                <span class="font-semibold text-deep-espresso">6%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-italian-gold h-2 rounded-full" style="width: 6%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-rich-brown">Needs Improvement</span>
                                <span class="font-semibold text-deep-espresso">2%</span>
                            </div>
                            <div class="w-full bg-warm-cream rounded-full h-2">
                                <div class="bg-accent-amber h-2 rounded-full" style="width: 2%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Popular Menu Items -->
                    <div class="animate-slide-in glass-effect rounded-2xl p-6 shadow-elegant border border-italian-gold/20">
                        <h4 class="font-bold text-deep-espresso font-serif mb-4">Top Menu Items</h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-italian-gold to-accent-amber rounded-lg flex items-center justify-center">
                                    <i class="fas fa-pizza-slice text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Margherita Pizza</p>
                                    <p class="text-xs text-rich-brown">Traditional Italian</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sage-green">145</p>
                                    <p class="text-xs text-rich-brown">orders</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-burgundy to-deep-espresso rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wine-glass text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Paella Valenciana</p>
                                    <p class="text-xs text-rich-brown">Spanish Specialty</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sage-green">98</p>
                                    <p class="text-xs text-rich-brown">orders</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-sage-green to-rich-brown rounded-lg flex items-center justify-center">
                                    <i class="fas fa-leaf text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Risotto ai Funghi</p>
                                    <p class="text-xs text-rich-brown">Mushroom Risotto</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sage-green">87</p>
                                    <p class="text-xs text-rich-brown">orders</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-accent-amber to-italian-gold rounded-lg flex items-center justify-center">
                                    <i class="fas fa-ice-cream text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Tiramisu</p>
                                    <p class="text-xs text-rich-brown">Classic Dessert</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sage-green">76</p>
                                    <p class="text-xs text-rich-brown">orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Staff Performance -->
                    <div class="animate-slide-in glass-effect rounded-2xl p-6 shadow-elegant border border-italian-gold/20">
                        <h4 class="font-bold text-deep-espresso font-serif mb-4">Staff Performance</h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=40&h=40&fit=crop&crop=face" alt="Chef" class="w-10 h-10 rounded-full border-2 border-italian-gold">
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Chef Antonio</p>
                                    <p class="text-xs text-rich-brown">Head Chef</p>
                                </div>
                                <div class="flex text-italian-gold">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b647?w=40&h=40&fit=crop&crop=face" alt="Server" class="w-10 h-10 rounded-full border-2 border-sage-green">
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Sofia Garcia</p>
                                    <p class="text-xs text-rich-brown">Event Coordinator</p>
                                </div>
                                <div class="flex text-italian-gold">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs opacity-30"></i>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Manager" class="w-10 h-10 rounded-full border-2 border-burgundy">
                                <div class="flex-1">
                                    <p class="font-semibold text-deep-espresso">Marco Rossi</p>
                                    <p class="text-xs text-rich-brown">Floor Manager</p>
                                </div>
                                <div class="flex text-italian-gold">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-italian-gold/10 to-sage-green/10 rounded-lg p-3 mt-4">
                                <p class="text-sm font-semibold text-deep-espresso">Team Performance</p>
                                <p class="text-xs text-rich-brown mb-2">Overall efficiency this month</p>
                                <div class="w-full bg-warm-cream rounded-full h-2">
                                    <div class="progress-bar h-2 rounded-full" style="width: 94%"></div>
                                </div>
                                <p class="text-xs text-sage-green font-semibold mt-1">94% Efficiency</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        // Mobile menu functionality
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const closeSidebar = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        function openMobileMenu() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        mobileMenuToggle.addEventListener('click', openMobileMenu);
        closeSidebar.addEventListener('click', closeMobileMenu);
        mobileOverlay.addEventListener('click', closeMobileMenu);

        // Set current date with enhanced formatting
        const currentDate = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('current-date').textContent = currentDate.toLocaleDateString('en-US', options);

        // Enhanced scroll animation
        const animateElements = document.querySelectorAll('.animate-slide-in');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        animateElements.forEach(element => {
            observer.observe(element);
        });

        // Enhanced Chart configurations
        Chart.defaults.font.family = 'Inter';
        Chart.defaults.color = '#3C2415';

        // Revenue Trends Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [85000, 92000, 78000, 105000, 118000, 124000],
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#D4AF37',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(60, 36, 21, 0.95)',
                        titleColor: '#D4AF37',
                        bodyColor: '#FDF6E3',
                        borderColor: '#D4AF37',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#8B4513'
                        }
                    },
                    y: {
                        beginAtZero: false,
                        min: 70000,
                        grid: {
                            color: 'rgba(212, 175, 55, 0.1)'
                        },
                        ticks: {
                            color: '#8B4513',
                            callback: function(value) {
                                return '₱' + (value / 1000) + 'K';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Event Types Distribution Chart
        const eventCtx = document.getElementById('eventChart').getContext('2d');
        const eventChart = new Chart(eventCtx, {
            type: 'doughnut',
            data: {
                labels: ['Weddings', 'Corporate Events', 'Birthday Parties', 'Anniversaries', 'Private Dinners'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: [
                        '#8B1538',
                        '#D4AF37',
                        '#87A96B',
                        '#8B4513',
                        '#F59E0B'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
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
                            usePointStyle: true,
                            color: '#3C2415'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(60, 36, 21, 0.95)',
                        titleColor: '#D4AF37',
                        bodyColor: '#FDF6E3',
                        borderColor: '#D4AF37',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                cutout: '60%'
            }
        });

        // Add real-time updates simulation
        setInterval(() => {
            // Simulate real-time data updates
            const revenueCards = document.querySelectorAll('[class*="card-hover"] p:nth-child(1)');
            // Add subtle animations to indicate live data
            revenueCards.forEach(card => {
                card.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 200);
            });
        }, 30000);

        // Enhanced keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

        // Smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        console.log('🍝 Cafe Lilio Ristorante Admin Dashboard Loaded Successfully!');
    </script>
</body>
</html>