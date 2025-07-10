<?php
require_once 'admin_auth.php';
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');



try {
    // Fetch Daily Revenue
    $daily_query = "SELECT DATE(s.created_at) as date, 
                           SUM(o.price * o.quantity) as total_revenue,
                           COUNT(DISTINCT o.sales_id) as transactions,
                           IF(COUNT(DISTINCT o.sales_id) > 0, 
                              SUM(o.price * o.quantity) / COUNT(DISTINCT o.sales_id), 
                              0) as avg_transaction
                    FROM order_tb o
                    JOIN sales_tb s ON o.sales_id = s.sales_id
                    WHERE DATE(s.created_at) >= CURDATE() - INTERVAL 7 DAY
                    GROUP BY DATE(s.created_at)
                    ORDER BY DATE(s.created_at) DESC";
    $daily_result = $conn->query($daily_query);

    // Fetch Monthly Revenue
    $monthly_query = "SELECT DATE_FORMAT(s.created_at, '%Y-%m') as month, 
                            SUM(o.price * o.quantity) as total_revenue,
                            COUNT(DISTINCT o.sales_id) as transactions,
                            IF(COUNT(DISTINCT o.sales_id) > 0, 
                               SUM(o.price * o.quantity) / COUNT(DISTINCT o.sales_id), 
                               0) as avg_transaction
                     FROM order_tb o
                     JOIN sales_tb s ON o.sales_id = s.sales_id
                     WHERE YEAR(s.created_at) = YEAR(CURDATE())
                     GROUP BY DATE_FORMAT(s.created_at, '%Y-%m')
                     ORDER BY DATE_FORMAT(s.created_at, '%Y-%m') DESC";
    $monthly_result = $conn->query($monthly_query);

    // Fetch Yearly Revenue
    $yearly_query = "SELECT YEAR(s.created_at) as year, 
                           SUM(o.price * o.quantity) as total_revenue,
                           COUNT(DISTINCT o.sales_id) as transactions,
                           IF(COUNT(DISTINCT o.sales_id) > 0, 
                              SUM(o.price * o.quantity) / COUNT(DISTINCT o.sales_id), 
                              0) as avg_transaction
                    FROM order_tb o
                    JOIN sales_tb s ON o.sales_id = s.sales_id
                    GROUP BY YEAR(s.created_at)
                    ORDER BY YEAR(s.created_at) DESC";
    $yearly_result = $conn->query($yearly_query);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Define page title
$page_title = "Reports";

// Capture page content
ob_start();
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
    
    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-baskerville { font-family: 'Libre Baskerville', serif; }

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

    /* Table styles */
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
    }

    .report-table th,
    .report-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid rgba(232, 224, 213, 0.5);
    }

    .report-table th {
        background: #8B4513;
        color: #E8E0D5;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .report-table tr:hover {
        background: rgba(232, 224, 213, 0.2);
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
</style>

<!-- Main Content Area -->
<div class="p-6">
<div class="dashboard-card fade-in bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
        <div class="flex items-center mb-3 md:mb-0">
            <h3 class="text-2xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-file-alt mr-2 text-accent-brown"></i>
                Reports
            </h3>
        </div>
    </div>

    <div class="border-t border-warm-cream/30 pt-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-1">Period</label>
                <select id="periodFilter" class="w-full p-2 text-sm rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                    <option value="">--</option>
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-1">Category</label>
                <select id="categoryFilter" class="w-full p-2 text-sm rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                    <option value="customer_satisfaction">Customer Satisfaction</option>
                    <option value="revenue">Revenue</option>
                    <option value="orders">Orders</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button id="applyFilters" class="w-full bg-deep-brown hover:bg-rich-brown text-warm-cream px-3 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center justify-center hover-lift">
                    <i class="fas fa-filter mr-2"></i> Apply
                </button>
                <button id="resetFilters" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center justify-center hover-lift">
                    <i class="fas fa-undo mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Daily Revenue Table -->
    <div id="dailyRevenueSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-coins mr-2 text-accent-brown"></i>
                Daily Revenue
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('dailyRevenueTable', 'Daily Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="dailyRevenueTable" class="report-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Revenue</th>
                        <th>Number of Transactions</th>
                        <th>Average Transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $daily_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('F j, Y', strtotime($row['date'])); ?></td>
                            <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                            <td><?php echo number_format($row['transactions']); ?></td>
                            <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Revenue Table -->
    <div id="monthlyRevenueSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-calendar-alt mr-2 text-accent-brown"></i>
                Monthly Revenue
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('monthlyRevenueTable', 'Monthly Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="monthlyRevenueTable" class="report-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Revenue</th>
                        <th>Number of Transactions</th>
                        <th>Average Transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $monthly_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('F Y', strtotime($row['month'] . '-01')); ?></td>
                            <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                            <td><?php echo number_format($row['transactions']); ?></td>
                            <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Yearly Revenue Table -->
    <div id="yearlyRevenueSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-trophy mr-2 text-accent-brown"></i>
                Yearly Revenue
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('yearlyRevenueTable', 'Yearly Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="yearlyRevenueTable" class="report-table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Total Revenue</th>
                        <th>Number of Transactions</th>
                        <th>Average Transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $yearly_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['year']; ?></td>
                            <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                            <td><?php echo number_format($row['transactions']); ?></td>
                            <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Orders Table -->
    <div id="dailyOrdersSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-shopping-bag mr-2 text-accent-brown"></i>
                Daily Orders
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('dailyOrdersTable', 'Daily Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="dailyOrdersTable" class="report-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Orders</th>
                        <th>Completed Orders</th>
                        <th>Pending Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025-07-04</td>
                        <td>124</td>
                        <td>100</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>2025-07-03</td>
                        <td>110</td>
                        <td>90</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>2025-07-02</td>
                        <td>115</td>
                        <td>95</td>
                        <td>20</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Orders Table -->
    <div id="monthlyOrdersSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-accent-brown"></i>
                Monthly Orders
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('monthlyOrdersTable', 'Monthly Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="monthlyOrdersTable" class="report-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Orders</th>
                        <th>Completed Orders</th>
                        <th>Pending Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>July 2025</td>
                        <td>3,847</td>
                        <td>3,500</td>
                        <td>347</td>
                    </tr>
                    <tr>
                        <td>June 2025</td>
                        <td>3,600</td>
                        <td>3,300</td>
                        <td>300</td>
                    </tr>
                    <tr>
                        <td>May 2025</td>
                        <td>3,750</td>
                        <td>3,400</td>
                        <td>350</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Yearly Orders Table -->
    <div id="yearlyOrdersSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-award mr-2 text-accent-brown"></i>
                Yearly Orders
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('yearlyOrdersTable', 'Yearly Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="yearlyOrdersTable" class="report-table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Total Orders</th>
                        <th>Completed Orders</th>
                        <th>Pending Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025</td>
                        <td>45,320</td>
                        <td>42,000</td>
                        <td>3,320</td>
                    </tr>
                    <tr>
                        <td>2024</td>
                        <td>38,000</td>
                        <td>35,000</td>
                        <td>3,000</td>
                    </tr>
                    <tr>
                        <td>2023</td>
                        <td>35,000</td>
                        <td>32,000</td>
                        <td>3,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Satisfaction Table -->
    <div id="customerSatisfactionSection" class="dashboard-card fade-in bg-white rounded-xl p-6 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-smile mr-2 text-accent-brown"></i>
                Customer Satisfaction
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('customerSatisfactionTable', 'Customer Satisfaction Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="customerSatisfactionTable" class="report-table">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Excellent</th>
                        <th>Good</th>
                        <th>Average</th>
                        <th>Poor</th>
                        <th>Total Responses</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>July 2025</td>
                        <td>65%</td>
                        <td>25%</td>
                        <td>8%</td>
                        <td>2%</td>
                        <td>1,000</td>
                    </tr>
                    <tr>
                        <td>June 2025</td>
                        <td>60%</td>
                        <td>28%</td>
                        <td>10%</td>
                        <td>2%</td>
                        <td>950</td>
                    </tr>
                    <tr>
                        <td>2025</td>
                        <td>62%</td>
                        <td>26%</td>
                        <td>9%</td>
                        <td>3%</td>
                        <td>12,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



<script>
    // Initialize sidebar state
    document.addEventListener('DOMContentLoaded', () => {
        // Set initial active link for Reports
        const reportsLink = document.querySelector('.sidebar-link[href="admin_reports.php"]');
        if (reportsLink) {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            reportsLink.classList.add('active');
        }

        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

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

        // Filter tables function
        function filterTables() {
            const category = document.getElementById('categoryFilter').value;
            const period = document.getElementById('periodFilter').value;
            const sections = [
                'dailyRevenueSection',
                'monthlyRevenueSection',
                'yearlyRevenueSection',
                'dailyOrdersSection',
                'monthlyOrdersSection',
                'yearlyOrdersSection',
                'customerSatisfactionSection'
            ];

            // Hide all sections
            sections.forEach(section => {
                document.getElementById(section).classList.add('hidden');
            });

            // Show relevant section based on filters
            if (!category && !period) {
                // If both filters are "All", show only Customer Satisfaction
                document.getElementById('customerSatisfactionSection').classList.remove('hidden');
            } else {
                let targetSection = '';
                if (category === 'revenue') {
                    if (period === 'daily') targetSection = 'dailyRevenueSection';
                    else if (period === 'monthly') targetSection = 'monthlyRevenueSection';
                    else if (period === 'yearly') targetSection = 'yearlyRevenueSection';
                } else if (category === 'orders') {
                    if (period === 'daily') targetSection = 'dailyOrdersSection';
                    else if (period === 'monthly') targetSection = 'monthlyOrdersSection';
                    else if (period === 'yearly') targetSection = 'yearlyOrdersSection';
                } else if (category === 'customer_satisfaction') {
                    targetSection = 'customerSatisfactionSection';
                } else if (category === '' && period) {
                    // If category is "All" but period is selected, show all tables for that period
                    if (period === 'daily') {
                        document.getElementById('dailyRevenueSection').classList.remove('hidden');
                        document.getElementById('dailyOrdersSection').classList.remove('hidden');
                    } else if (period === 'monthly') {
                        document.getElementById('monthlyRevenueSection').classList.remove('hidden');
                        document.getElementById('monthlyOrdersSection').classList.remove('hidden');
                    } else if (period === 'yearly') {
                        document.getElementById('yearlyRevenueSection').classList.remove('hidden');
                        document.getElementById('yearlyOrdersSection').classList.remove('hidden');
                    }
                }
                if (targetSection) {
                    document.getElementById(targetSection).classList.remove('hidden');
                }
            }
        }

        // Reset filters function
        function resetFilters() {
            document.getElementById('categoryFilter').value = 'customer_satisfaction';
            document.getElementById('periodFilter').value = '';
            const sections = [
                'dailyRevenueSection',
                'monthlyRevenueSection',
                'yearlyRevenueSection',
                'dailyOrdersSection',
                'monthlyOrdersSection',
                'yearlyOrdersSection',
                'customerSatisfactionSection'
            ];
            // Hide all sections except Customer Satisfaction
            sections.forEach(section => {
                document.getElementById(section).classList.add('hidden');
            });
            document.getElementById('customerSatisfactionSection').classList.remove('hidden');
        }

        // Event listeners for filter buttons
        document.getElementById('applyFilters').addEventListener('click', filterTables);
        document.getElementById('resetFilters').addEventListener('click', resetFilters);

        // Initialize default state
        filterTables();
    });

    // Print table function
    function printTable(tableId, title) {
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll('tbody tr');
        
        // Create print section
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
                    ${table.querySelector('thead').innerHTML}
                </thead>
                <tbody>
                    ${Array.from(rows).map(row => `<tr>${row.innerHTML}</tr>`).join('')}
                </tbody>
            </table>
        `;
        
        // Add total row for revenue tables
        if (tableId.includes('Revenue')) {
            let totalRevenue = 0;
            let totalTransactions = 0;
            rows.forEach(row => {
                const revenue = parseFloat(row.cells[1].textContent.replace(/[₱,]/g, '')) || 0;
                const transactions = parseInt(row.cells[2].textContent.replace(/,/g, '')) || 0;
                totalRevenue += revenue;
                totalTransactions += transactions;
            });
            const avgTransaction = totalTransactions > 0 ? (totalRevenue / totalTransactions).toFixed(2) : '0.00';
            tableHtml += `
                <tfoot>
                    <tr style="font-weight: bold; background-color: #f5f5f5;">
                        <td>Total</td>
                        <td>₱${totalRevenue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td>${totalTransactions.toLocaleString()}</td>
                        <td>₱${avgTransaction}</td>
                    </tr>
                </tfoot>
            `;
        }
        
        // Set content and print
        printSection.innerHTML = tableHtml;
        window.print();
    }
</script>

<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>