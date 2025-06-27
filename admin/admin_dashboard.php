

<?php
    require_once '../db_connect.php';
    require_once 'admin_auth.php'; 

    // Set the timezone to Philippine Time
    date_default_timezone_set('Asia/Manila');

    // Define page title
    $page_title = "Admin Dashboards";

    // Capture page content
    ob_start();
?>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        /* Add this to your existing style section */
        #profileMenu {
            z-index: 9999 !important;
            transform: translateY(0) !important;
        }

        /* header {
                    z-index: 50;
                } */
                
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
                /* .sidebar-link {
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
                 */
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
                
                /* Improved scrollbar */
                ::-webkit-scrollbar {
                    width: 8px;
                }
                
                ::-webkit-scrollbar-track {
                    background: #E8E0D5;
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb {
                    background: #8B4513;
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb:hover {
                    background: #5D2F0F;
                }

                /* Print-specific styles */
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    #printSection, #printSection * {
                        visibility: visible;
                    }
                    #printSection {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }
                    .print-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    .print-table th,
                    .print-table td {
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: left;
                    }
                    .print-table th {
                        background-color: #f0f0f0;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .print-date {
                        text-align: right;
                        margin-bottom: 20px;
                        font-size: 12px;
                    }
                }

                /* Add these styles to the existing style section */
                /* #sidebar {
                    display: flex;
                    flex-direction: column;
                    height: 100vh;
                    overflow: hidden;
                    transition: width 0.3s ease-in-out;
                    position: relative;
                    z-index: 40;
                }

                #sidebar.collapsed {
                    width: 4rem !important;
                }

                #sidebar .sidebar-header {
                    flex-shrink: 0;
                    padding: 1.5rem;
                    border-bottom: 1px solid rgba(232, 224, 213, 0.2);
                }

                #sidebar.collapsed .sidebar-header {
                    padding: 1.5rem 0.75rem;
                }

                #sidebar nav {
                    flex: 1;
                    overflow-y: auto;
                    padding-right: 4px;
                }

                .sidebar-link {
                    position: relative;
                    transition: all 0.3s ease;
                    white-space: nowrap;
                }

                #sidebar.collapsed .sidebar-link {
                    padding: 0.75rem !important;
                    justify-content: center;
                }

                #sidebar.collapsed .sidebar-link i {
                    margin: 0 !important;
                }

                #sidebar.collapsed .sidebar-text,
                #sidebar.collapsed .nav-title,
                #sidebar.collapsed .nav-subtitle {
                    display: none;
                }

                .sidebar-link .tooltip {
                    position: absolute;
                    left: 100%;
                    top: 50%;
                    transform: translateY(-50%);
                    background: #5D2F0F;
                    color: #E8E0D5;
                    padding: 0.5rem 0.75rem;
                    border-radius: 0.375rem;
                    font-size: 0.875rem;
                    white-space: nowrap;
                    opacity: 0;
                    visibility: hidden;
                    transition: all 0.2s ease;
                    z-index: 50;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    pointer-events: none;
                }

                .sidebar-link .tooltip::before {
                    content: '';
                    position: absolute;
                    right: 100%;
                    top: 50%;
                    transform: translateY(-50%);
                    border: 6px solid transparent;
                    border-right-color: #5D2F0F;
                }

                #sidebar.collapsed .sidebar-link:hover .tooltip {
                    opacity: 1;
                    visibility: visible;
                    left: calc(100% + 0.5rem);
                } */

                /* Active state for sidebar links */
                /* .sidebar-link.active {
                    background: rgba(232, 224, 213, 0.2) !important;
                    color: #E8E0D5 !important;
                } */

                /* Improved hover effects */
                /* .sidebar-link:hover {
                    background: rgba(232, 224, 213, 0.15) !important;
                }

                #sidebar.collapsed .sidebar-link:hover {
                    transform: scale(1.1);
                } */
    </style>





            <!-- Main Content Area -->
                <!-- Revenue Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="dashboard-card fade-in hover-lift rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-br from-deep-brown to-rich-brown p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-warm-cream/80 text-sm font-baskerville">Today's Revenue</p>
                                    <p class="text-xl font-bold text-warm-cream font-baskerville">₱2,450</p>
                                    <p class="text-warm-cream/70 text-xs mt-1 font-baskerville flex items-center">
                                        <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                        +12% from yesterday
                                    </p>
                                </div>
                                <div class="bg-warm-cream/10 p-3 rounded-full">
                                    <i class="fas fa-coins text-3xl text-warm-cream"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card fade-in hover-lift rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-br from-accent-brown to-rich-brown p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-warm-cream/80 text-sm font-baskerville">Weekly Revenue</p>
                                    <p class="text-xl font-bold text-warm-cream font-baskerville">₱18,750</p>
                                    <p class="text-warm-cream/70 text-xs mt-1 font-baskerville flex items-center">
                                        <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                        +8% from last week
                                    </p>
                                </div>
                                <div class="bg-warm-cream/10 p-3 rounded-full">
                                    <i class="fas fa-chart-line text-3xl text-warm-cream"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card fade-in hover-lift rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-br from-deep-brown to-accent-brown p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-warm-cream/80 text-sm font-baskerville">Monthly Revenue</p>
                                    <p class="text-xl font-bold text-warm-cream font-baskerville">₱84,320</p>
                                    <p class="text-warm-cream/70 text-xs mt-1 font-baskerville flex items-center">
                                        <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                        +15% from last month
                                    </p>
                                </div>
                                <div class="bg-warm-cream/10 p-3 rounded-full">
                                    <i class="fas fa-calendar-alt text-3xl text-warm-cream"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card fade-in hover-lift rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-br from-rich-brown to-accent-brown p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-warm-cream/80 text-sm font-baskerville">Yearly Revenue</p>
                                    <p class="text-xl font-bold text-warm-cream font-baskerville">₱950,680</p>
                                    <p class="text-warm-cream/70 text-xs mt-1 font-baskerville flex items-center">
                                        <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                        +22% from last year
                                    </p>
                                </div>
                                <div class="bg-warm-cream/10 p-3 rounded-full">
                                    <i class="fas fa-trophy text-3xl text-warm-cream"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Served -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="dashboard-card fade-in p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm font-baskerville">Today's Orders</p>
                                <p class="text-xl font-bold text-rich-brown font-baskerville mt-1">124</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                        <div class="bg-deep-brown h-2 rounded-full" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-deep-brown/10 p-3 rounded-full">
                                <i class="fas fa-shopping-bag text-3xl text-deep-brown"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card fade-in p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm font-baskerville">Weekly Orders</p>
                                <p class="text-xl font-bold text-rich-brown font-baskerville mt-1">892</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                        <div class="bg-deep-brown h-2 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-deep-brown/10 p-3 rounded-full">
                                <i class="fas fa-clipboard-list text-3xl text-deep-brown"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card fade-in p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm font-baskerville">Monthly Orders</p>
                                <p class="text-xl font-bold text-rich-brown font-baskerville mt-1">3,847</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                        <div class="bg-deep-brown h-2 rounded-full" style="width: 90%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-deep-brown/10 p-3 rounded-full">
                                <i class="fas fa-chart-bar text-3xl text-deep-brown"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card fade-in p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-deep-brown text-sm font-baskerville">Yearly Orders</p>
                                <p class="text-xl font-bold text-rich-brown font-baskerville mt-1">45,320</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                        <div class="bg-deep-brown h-2 rounded-full" style="width: 95%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-deep-brown/10 p-3 rounded-full">
                                <i class="fas fa-award text-3xl text-deep-brown"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Overview -->
                <div class="dashboard-card fade-in bg-white rounded-xl shadow-lg p-8 mb-8">
                    <h3 class="text-2xl font-bold text-deep-brown mb-6 font-playfair">Financial Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="bg-green-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 hover-lift">
                                <i class="fas fa-arrow-up text-2xl text-green-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-deep-brown font-playfair">Revenue</h4>
                            <p class="text-xl font-bold text-green-600 font-baskerville mt-2">₱84,320</p>
                            <p class="text-sm text-rich-brown font-baskerville mt-1">This Month</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-red-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 hover-lift">
                                <i class="fas fa-arrow-down text-2xl text-red-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-deep-brown font-playfair">Expenses</h4>
                            <p class="text-xl font-bold text-red-600 font-baskerville mt-2">₱45,680</p>
                            <p class="text-sm text-rich-brown font-baskerville mt-1">This Month</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-blue-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 hover-lift">
                                <i class="fas fa-chart-pie text-2xl text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-deep-brown font-playfair">Profit</h4>
                            <p class="text-xl font-bold text-blue-600 font-baskerville mt-2">₱38,640</p>
                            <p class="text-sm text-rich-brown font-baskerville mt-1">This Month</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Revenue Analysis Chart -->
                    <div class="dashboard-card fade-in bg-white rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                                <i class="fas fa-chart-line mr-2 text-accent-brown"></i>
                                Revenue Analysis
                            </h3>
                            <button onclick="printChartData('revenueChart', 'Revenue Analysis')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center">
                                <i class="fas fa-download mr-2"></i> Export Data
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Menu Sales Chart -->
                    <div class="dashboard-card fade-in bg-white rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                                <i class="fas fa-utensils mr-2 text-accent-brown"></i>
                                Top Menu Items
                            </h3>
                            <button onclick="printChartData('menuChart', 'Menu Sales Analysis')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center">
                                <i class="fas fa-download mr-2"></i> Export Data
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="menuChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Season Trends and Customer Satisfaction -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Season Trends Chart -->
                    <div class="dashboard-card fade-in bg-white rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                                <i class="fas fa-sun mr-2 text-accent-brown"></i>
                                Seasonal Trends
                            </h3>
                            <button onclick="printChartData('seasonChart', 'Seasonal Revenue Analysis')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center">
                                <i class="fas fa-download mr-2"></i> Export Data
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="seasonChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Customer Satisfaction -->
                    <div class="dashboard-card fade-in bg-white rounded-xl p-6">
                        <h3 class="text-xl font-bold text-deep-brown mb-4 font-playfair flex items-center">
                            <i class="fas fa-smile mr-2 text-accent-brown"></i>
                            Customer Satisfaction
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-rich-brown font-baskerville">Excellent</span>
                                    <span class="text-sm font-bold text-deep-brown font-baskerville">65%</span>
                                </div>
                                <div class="w-full bg-warm-cream/30 rounded-full h-3">
                                    <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: 65%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-rich-brown font-baskerville">Good</span>
                                    <span class="text-sm font-bold text-deep-brown font-baskerville">25%</span>
                                </div>
                                <div class="w-full bg-warm-cream/30 rounded-full h-3">
                                    <div class="bg-blue-500 h-3 rounded-full transition-all duration-500" style="width: 25%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-rich-brown font-baskerville">Average</span>
                                    <span class="text-sm font-bold text-deep-brown font-baskerville">8%</span>
                                </div>
                                <div class="w-full bg-warm-cream/30 rounded-full h-3">
                                    <div class="bg-yellow-500 h-3 rounded-full transition-all duration-500" style="width: 8%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-rich-brown font-baskerville">Poor</span>
                                    <span class="text-sm font-bold text-deep-brown font-baskerville">2%</span>
                                </div>
                                <div class="w-full bg-warm-cream/30 rounded-full h-3">
                                    <div class="bg-red-500 h-3 rounded-full transition-all duration-500" style="width: 2%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-card fade-in bg-white rounded-xl p-6">
                    <h3 class="text-xl font-bold text-deep-brown mb-6 font-playfair flex items-center">
                        <i class="fas fa-clock mr-2 text-accent-brown"></i>
                        Recent Activity
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 p-4 bg-warm-cream/20 rounded-lg hover:bg-warm-cream/30 transition-all duration-300">
                            <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-deep-brown font-playfair">New order #1234 received</p>
                                <p class="text-xs text-rich-brown font-baskerville mt-1">2 Cappuccino, 1 Croissant - ₱385</p>
                            </div>
                            <span class="text-xs text-rich-brown font-baskerville whitespace-nowrap">5 min ago</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-warm-cream/20 rounded-lg hover:bg-warm-cream/30 transition-all duration-300">
                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-deep-brown font-playfair">New employee added</p>
                                <p class="text-xs text-rich-brown font-baskerville mt-1">Maria Santos - Barista</p>
                            </div>
                            <span class="text-xs text-rich-brown font-baskerville whitespace-nowrap">1 hour ago</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-warm-cream/20 rounded-lg hover:bg-warm-cream/30 transition-all duration-300">
                            <div class="bg-orange-100 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-deep-brown font-playfair">Low inventory warning</p>
                                <p class="text-xs text-rich-brown font-baskerville mt-1">Coffee beans running low (5 kg remaining)</p>
                            </div>
                            <span class="text-xs text-rich-brown font-baskerville whitespace-nowrap">3 hours ago</span>
                        </div>
                    </div>
                </div>

<?php
    $page_content = ob_get_clean();

    // Capture page-specific scripts
    ob_start();
?>


<script>
        // Update sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const titleFull = document.querySelector('.nav-title');
        const titleShort = document.querySelector('.nav-title-short');

         function toggleSidebar() {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            // Show initials, hide full name
            titleFull.classList.add('hidden');
            titleShort.classList.remove('hidden');
        } else {
            // Show full name, hide initials
            titleFull.classList.remove('hidden');
            titleShort.classList.add('hidden');
        }
    }

        // Update event listeners
        sidebarToggle.addEventListener('click', toggleSidebar);

        // Update sidebar link click handler
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                // Remove active class from all links
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                link.classList.add('active');
            });
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', () => {
            // Set initial active link
            const dashboardLink = document.querySelector('.sidebar-link[href="#"]');
            if (dashboardLink) {
                dashboardLink.classList.add('active');
            }
        });

        // Set current date with improved formatting
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', options);

        // Scroll animation observer with improved timing
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animated');
                    }, index * 100); // Staggered animation
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(element => {
            observer.observe(element);
        });

        // Chart.js configurations with improved styling
        Chart.defaults.font.family = "'Libre Baskerville', serif";
        Chart.defaults.color = '#5D2F0F';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(93, 47, 15, 0.8)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.titleFont = {
            family: "'Playfair Display', serif",
            size: 14,
            weight: 'bold'
        };

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
                    fill: true,
                    pointBackgroundColor: '#8B4513',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
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
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 60000,
                        grid: {
                            color: 'rgba(232, 224, 213, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            },
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
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
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
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
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true
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
                    ],
                    borderRadius: 8,
                    borderWidth: 0
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
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 150000,
                        grid: {
                            color: 'rgba(232, 224, 213, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            },
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

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

        function printChartData(chartId, title) {
            const chart = Chart.getChart(chartId);
            const data = chart.data;
            
            // Create print section if it doesn't exist
            let printSection = document.getElementById('printSection');
            if (!printSection) {
                printSection = document.createElement('div');
                printSection.id = 'printSection';
                document.body.appendChild(printSection);
            }
            
            // Generate table HTML
            let tableHtml = `
                <div class="print-header">
                    <h1 style="font-size: 24px; font-weight: bold; font-family: 'Playfair Display', serif;">${title}</h1>
                    <h2 style="font-size: 16px; color: #666; margin-top: 5px;">Caffè Lilio</h2>
                </div>
                <div class="print-date">
                    Generated on: ${new Date().toLocaleString()}
                </div>
                <table class="print-table">
                    <thead>
                        <tr>
                            <th style="background-color: #8B4513; color: white;">Category</th>
                            <th style="background-color: #8B4513; color: white;">Value</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            // Add data rows
            data.labels.forEach((label, index) => {
                const value = data.datasets[0].data[index];
                tableHtml += `
                    <tr>
                        <td>${label}</td>
                        <td>${chartId === 'revenueChart' || chartId === 'seasonChart' ? '₱' + value.toLocaleString() : value}</td>
                    </tr>
                `;
            });
            
            // Add total if applicable
            if (chartId === 'revenueChart' || chartId === 'seasonChart') {
                const total = data.datasets[0].data.reduce((sum, value) => sum + value, 0);
                tableHtml += `
                    <tr style="font-weight: bold; background-color: #f5f5f5;">
                        <td>Total</td>
                        <td>₱${total.toLocaleString()}</td>
                    </tr>
                `;
            }
            
            tableHtml += `
                    </tbody>
                </table>
            `;
            
            // Set the content and print
            printSection.innerHTML = tableHtml;
            window.print();
        }
</script>

<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>