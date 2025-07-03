<?php
    require_once 'admin_auth.php';
    require_once '../db_connect.php';
    
    // Set the timezone to Philippine Time
    date_default_timezone_set('Asia/Manila');

    // Define page title
    $page_title = "Reports";

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

    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(93, 47, 15, 0.15);
    }

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

    #profileMenu {
        z-index: 49 !important;
        transform: translateY(0) !important;
    }

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

    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(232, 224, 213, 0.5);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .filter-section select,
    .filter-section input {
        border: 1px solid #E8E0D5;
        border-radius: 8px;
        padding: 0.5rem;
        background: #fff;
        font-family: 'Libre Baskerville', serif;
        transition: all 0.3s ease;
    }

    .filter-section select:focus,
    .filter-section input:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
    }

    .report-button {
        background: #8B4513;
        color: #E8E0D5;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-family: 'Libre Baskerville', serif;
        transition: all 0.3s ease;
    }

    .report-button:hover {
        background: #5D2F0F;
        transform: translateY(-2px);
    }
</style>

<!-- Main Content Area -->
<div class="p-6">
    <!-- Filter Section -->
    <div class="filter-section dashboard-card fade-in mb-8">
        <h3 class="text-xl font-bold text-deep-brown mb-6 font-playfair flex items-center">
            <i class="fas fa-filter mr-2 text-accent-brown"></i>
            Report Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Report Type</label>
                <select class="w-full">
                    <option>Revenue Analysis</option>
                    <option>Menu Sales</option>
                    <option>Seasonal Trends</option>
                    <option>Customer Satisfaction</option>
                    <option>Order Summary</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Date Range</label>
                <select class="w-full">
                    <option>Today</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>This Year</option>
                    <option>Custom Range</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Start Date</label>
                <input type="date" class="w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">End Date</label>
                <input type="date" class="w-full">
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <button class="report-button flex items-center">
                <i class="fas fa-search mr-2"></i>
                Generate Report
            </button>
            <button class="report-button flex items-center" onclick="printReport()">
                <i class="fas fa-download mr-2"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="dashboard-card fade-in p-6 rounded-xl">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Revenue Summary</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Total Revenue</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">₱84,320</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Average Daily</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">₱2,811</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Growth</span>
                    <span class="text-lg font-bold text-green-600 font-baskerville">+15%</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card fade-in p-6 rounded-xl">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Order Summary</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Total Orders</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">3,847</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Average Order Value</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">₱219</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Peak Day</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">Friday</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card fade-in p-6 rounded-xl">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Customer Feedback</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Satisfaction Rate</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">92%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Positive Reviews</span>
                    <span class="text-lg font-bold text-green-600 font-baskerville">1,245</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Response Rate</span>
                    <span class="text-lg font-bold text-deep-brown font-baskerville">85%</span>
                </div>
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
                <button onclick="printChartData('revenueReportChart', 'Revenue Analysis Report')" class="report-button flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            </div>
            <div class="chart-container">
                <canvas id="revenueReportChart"></canvas>
            </div>
        </div>

        <!-- Menu Sales Chart -->
        <div class="dashboard-card fade-in bg-white rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                    <i class="fas fa-utensils mr-2 text-accent-brown"></i>
                    Top Menu Items
                </h3>
                <button onclick="printChartData('menuReportChart', 'Menu Sales Report')" class="report-button flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            </div>
            <div class="chart-container">
                <canvas id="menuReportChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Seasonal Trends Chart -->
        <div class="dashboard-card fade-in bg-white rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                    <i class="fas fa-sun mr-2 text-accent-brown"></i>
                    Seasonal Trends
                </h3>
                <button onclick="printChartData('seasonReportChart', 'Seasonal Trends Report')" class="report-button flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            </div>
            <div class="chart-container">
                <canvas id="seasonReportChart"></canvas>
            </div>
        </div>

        <!-- Customer Satisfaction Chart -->
        <div class="dashboard-card fade-in bg-white rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                    <i class="fas fa-smile mr-2 text-accent-brown"></i>
                    Customer Satisfaction
                </h3>
                <button onclick="printChartData('satisfactionReportChart', 'Customer Satisfaction Report')" class="report-button flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            </div>
            <div class="chart-container">
                <canvas id="satisfactionReportChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="dashboard-card fade-in bg-white rounded-xl p-6">
        <h3 class="text-xl font-bold text-deep-brown mb-6 font-playfair flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Detailed Transaction Report
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full print-table">
                <thead>
                    <tr>
                        <th class="bg-deep-brown text-warm-cream p-3">Transaction ID</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Date</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Items</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Amount</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Customer</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">TX1234</td>
                        <td class="p-3">2025-07-01</td>
                        <td class="p-3">2 Cappuccino, 1 Croissant</td>
                        <td class="p-3">₱385</td>
                        <td class="p-3">John Doe</td>
                        <td class="p-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Completed</span></td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">TX1235</td>
                        <td class="p-3">2025-07-01</td>
                        <td class="p-3">1 Latte, 2 Muffins</td>
                        <td class="p-3">₱420</td>
                        <td class="p-3">Jane Smith</td>
                        <td class="p-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Completed</span></td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">TX1236</td>
                        <td class="p-3">2025-07-01</td>
                        <td class="p-3">1 Espresso</td>
                        <td class="p-3">₱150</td>
                        <td class="p-3">Mike Johnson</td>
                        <td class="p-3"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    $page_content = ob_get_clean();

    // Capture page-specific scripts
    ob_start();
?>

<script>
    // Chart.js configurations
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
    const revenueReportCtx = document.getElementById('revenueReportChart').getContext('2d');
    const revenueReportChart = new Chart(revenueReportCtx, {
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
                legend: { display: false },
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
                    grid: { color: 'rgba(232, 224, 213, 0.3)', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { padding: 10 }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Menu Sales Chart
    const menuReportCtx = document.getElementById('menuReportChart').getContext('2d');
    const menuReportChart = new Chart(menuReportCtx, {
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

    // Seasonal Trends Chart
    const seasonReportCtx = document.getElementById('seasonReportChart').getContext('2d');
    const seasonReportChart = new Chart(seasonReportCtx, {
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
                legend: { display: false },
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
                    grid: { color: 'rgba(232, 224, 213, 0.3)', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { padding: 10 }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Customer Satisfaction Chart
    const satisfactionReportCtx = document.getElementById('satisfactionReportChart').getContext('2d');
    const satisfactionReportChart = new Chart(satisfactionReportCtx, {
        type: 'bar',
        data: {
            labels: ['Excellent', 'Good', 'Average', 'Poor'],
            datasets: [{
                label: 'Satisfaction',
                data: [65, 25, 8, 2],
                backgroundColor: [
                    '#10B981',
                    '#3B82F6',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderRadius: 8,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(232, 224, 213, 0.3)', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { padding: 10 }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });

    function printChartData(chartId, title) {
        const chart = Chart.getChart(chartId);
        const data = chart.data;
        
        let printSection = document.getElementById('printSection');
        if (!printSection) {
            printSection = document.createElement('div');
            printSection.id = 'printSection';
            document.body.appendChild(printSection);
        }
        
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
        
        data.labels.forEach((label, index) => {
            const value = data.datasets[0].data[index];
            tableHtml += `
                <tr>
                    <td>${label}</td>
                    <td>${chartId.includes('revenue') || chartId.includes('season') ? '₱' + value.toLocaleString() : value + (chartId.includes('satisfaction') ? '%' : '')}</td>
                </tr>
            `;
        });
        
        if (chartId.includes('revenue') || chartId.includes('season')) {
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
        
        printSection.innerHTML = tableHtml;
        window.print();
    }

    function printReport() {
        let printSection = document.getElementById('printSection');
        if (!printSection) {
            printSection = document.createElement('div');
            printSection.id = 'printSection';
            document.body.appendChild(printSection);
        }
        
        const table = document.querySelector('.print-table').outerHTML;
        printSection.innerHTML = `
            <div class="print-header">
                <h1 style="font-size: 24px; font-weight: bold; font-family: 'Playfair Display', serif;">Detailed Transaction Report</h1>
                <h2 style="font-size: 16px; color: #666; margin-top: 5px;">Caffè Lilio</h2>
            </div>
            <div class="print-date">
                Generated on: ${new Date().toLocaleString()}
            </div>
            ${table}
        `;
        window.print();
    }

    // Scroll animation observer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('animated');
                }, index * 100);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(element => {
        observer.observe(element);
    });
</script>

<?php
    // Include the layout
    include 'layout.php';
?>