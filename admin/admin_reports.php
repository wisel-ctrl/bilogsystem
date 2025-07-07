<?php
require_once 'admin_auth.php';
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Define page title
$page_title = "Reports";

// Function to fetch daily revenue data
function getDailyRevenue($conn, $start_date = null, $end_date = null) {
    $sql = "SELECT 
                DATE(order_date) as date,
                SUM(total_amount) as total_revenue,
                COUNT(*) as num_transactions,
                AVG(total_amount) as avg_transaction
            FROM orders
            WHERE status = 'completed'";
    
    if ($start_date && $end_date) {
        $sql .= " AND DATE(order_date) BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $start_date, $end_date);
    } else {
        $sql .= " GROUP BY DATE(order_date) ORDER BY date DESC LIMIT 7";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'date' => $row['date'],
            'total_revenue' => number_format($row['total_revenue'], 2),
            'num_transactions' => $row['num_transactions'],
            'avg_transaction' => number_format($row['avg_transaction'], 2)
        ];
    }
    $stmt->close();
    return $data;
}

// Function to fetch monthly revenue data
function getMonthlyRevenue($conn, $year = null) {
    $sql = "SELECT 
                DATE_FORMAT(order_date, '%Y-%m') as month,
                SUM(total_amount) as total_revenue,
                COUNT(*) as num_transactions,
                AVG(total_amount) as avg_transaction
            FROM orders
            WHERE status = 'completed'";
    
    if ($year) {
        $sql .= " AND YEAR(order_date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $year);
    } else {
        $sql .= " GROUP BY DATE_FORMAT(order_date, '%Y-%m') ORDER BY month DESC LIMIT 12";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'month' => date('F Y', strtotime($row['month'] . '-01')),
            'total_revenue' => number_format($row['total_revenue'], 2),
            'num_transactions' => $row['num_transactions'],
            'avg_transaction' => number_format($row['avg_transaction'], 2)
        ];
    }
    $stmt->close();
    return $data;
}

// Function to fetch yearly revenue data
function getYearlyRevenue($conn) {
    $sql = "SELECT 
                YEAR(order_date) as year,
                SUM(total_amount) as total_revenue,
                COUNT(*) as num_transactions,
                AVG(total_amount) as avg_transaction
            FROM orders
            WHERE status = 'completed'
            GROUP BY YEAR(order_date)
            ORDER BY year DESC
            LIMIT 5";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'year' => $row['year'],
            'total_revenue' => number_format($row['total_revenue'], 2),
            'num_transactions' => $row['num_transactions'],
            'avg_transaction' => number_format($row['avg_transaction'], 2)
        ];
    }
    $stmt->close();
    return $data;
}

// Function to fetch daily orders data
function getDailyOrders($conn, $start_date = null, $end_date = null) {
    $sql = "SELECT 
                DATE(order_date) as date,
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders
            FROM orders";
    
    if ($start_date && $end_date) {
        $sql .= " WHERE DATE(order_date) BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $start_date, $end_date);
    } else {
        $sql .= " GROUP BY DATE(order_date) ORDER BY date DESC LIMIT 7";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'date' => $row['date'],
            'total_orders' => $row['total_orders'],
            'completed_orders' => $row['completed_orders'],
            'pending_orders' => $row['pending_orders']
        ];
    }
    $stmt->close();
    return $data;
}

// Function to fetch monthly orders data
function getMonthlyOrders($conn, $year = null) {
    $sql = "SELECT 
                DATE_FORMAT(order_date, '%Y-%m') as month,
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders
            FROM orders";
    
    if ($year) {
        $sql .= " WHERE YEAR(order_date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $year);
    } else {
        $sql .= " GROUP BY DATE_FORMAT(order_date, '%Y-%m') ORDER BY month DESC LIMIT 12";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'month' => date('F Y', strtotime($row['month'] . '-01')),
            'total_orders' => $row['total_orders'],
            'completed_orders' => $row['completed_orders'],
            'pending_orders' => $row['pending_orders']
        ];
    }
    $stmt->close();
    return $data;
}

// Function to fetch yearly orders data
function getYearlyOrders($conn) {
    $sql = "SELECT 
                YEAR(order_date) as year,
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders
            FROM orders
            GROUP BY YEAR(order_date)
            ORDER BY year DESC
            LIMIT 5";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'year' => $row['year'],
            'total_orders' => $row['total_orders'],
            'completed_orders' => $row['completed_orders'],
            'pending_orders' => $row['pending_orders']
        ];
    }
    $stmt->close();
    return $data;
}

// Function to fetch customer satisfaction data
function getCustomerSatisfaction($conn, $period = 'monthly') {
    $sql = "";
    if ($period == 'monthly') {
        $sql = "SELECT 
                    DATE_FORMAT(feedback_date, '%Y-%m') as period,
                    SUM(CASE WHEN rating = 'excellent' THEN 1 ELSE 0 END) / COUNT(*) * 100 as excellent,
                    SUM(CASE WHEN rating = 'good' THEN 1 ELSE 0 END) / COUNT(*) * 100 as good,
                    SUM(CASE WHEN rating = 'average' THEN 1 ELSE 0 END) / COUNT(*) * 100 as average,
                    SUM(CASE WHEN rating = 'poor' THEN 1 ELSE 0 END) / COUNT(*) * 100 as poor,
                    COUNT(*) as total_responses
                FROM customer_feedback
                GROUP BY DATE_FORMAT(feedback_date, '%Y-%m')
                ORDER BY period DESC
                LIMIT 12";
    } else {
        $sql = "SELECT 
                    YEAR(feedback_date) as period,
                    SUM(CASE WHEN rating = 'excellent' THEN 1 ELSE 0 END) / COUNT(*) * 100 as excellent,
                    SUM(CASE WHEN rating = 'good' THEN 1 ELSE 0 END) / COUNT(*) * 100 as good,
                    SUM(CASE WHEN rating = 'average' THEN 1 ELSE 0 END) / COUNT(*) * 100 as average,
                    SUM(CASE WHEN rating = 'poor' THEN 1 ELSE 0 END) / COUNT(*) * 100 as poor,
                    COUNT(*) as total_responses
                FROM customer_feedback
                GROUP BY YEAR(feedback_date)
                ORDER BY period DESC
                LIMIT 5";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'period' => $period == 'monthly' ? date('F Y', strtotime($row['period'] . '-01')) : $row['period'],
            'excellent' => number_format($row['excellent'], 1) . '%',
            'good' => number_format($row['good'], 1) . '%',
            'average' => number_format($row['average'], 1) . '%',
            'poor' => number_format($row['poor'], 1) . '%',
            'total_responses' => $row['total_responses']
        ];
    }
    $stmt->close();
    return $data;
}

// Fetch data for all tables
$daily_revenue = getDailyRevenue($conn);
$monthly_revenue = getMonthlyRevenue($conn);
$yearly_revenue = getYearlyRevenue($conn);
$daily_orders = getDailyOrders($conn);
$monthly_orders = getMonthlyOrders($conn);
$yearly_orders = getYearlyOrders($conn);
$customer_satisfaction = getCustomerSatisfaction($conn);

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
                    <option value="">Customer Satisfaction</option>
                    <option value="revenue">Revenue</option>
                    <option value="orders">Orders</option>
                    <option value="customer_satisfaction">Customer Satisfaction</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-1">Start Date</label>
                <input type="date" id="startDate" class="w-full p-2 text-sm rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-1">End Date/Year</label>
                <input type="text" id="endDate" class="w-full p-2 text-sm rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville" placeholder="YYYY or YYYY-MM-DD">
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
                    <?php foreach ($daily_revenue as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td>₱<?php echo htmlspecialchars($row['total_revenue']); ?></td>
                            <td><?php echo htmlspecialchars($row['num_transactions']); ?></td>
                            <td>₱<?php echo htmlspecialchars($row['avg_transaction']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                    <?php foreach ($monthly_revenue as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['month']); ?></td>
                            <td>₱<?php echo htmlspecialchars($row['total_revenue']); ?></td>
                            <td><?php echo htmlspecialchars($row['num_transactions']); ?></td>
                            <td>₱<?php echo htmlspecialchars($row['avg_transaction']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                    <?php foreach ($yearly_revenue as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td>₱<?php echo htmlspecialchars($row['total_revenue']); ?></td>
                            <td><?php echo htmlspecialchars($row['num_transactions']); ?></td>
                            <td>₱<?php echo htmlspecialchars($row['avg_transaction']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                    <?php foreach ($daily_orders as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_orders']); ?></td>
                            <td><?php echo htmlspecialchars($row['completed_orders']); ?></td>
                            <td><?php echo htmlspecialchars($row['pending_orders']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                    <?php foreach ($monthly_orders as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['month']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_orders']); ?></td>
                            <td><?php echo htmlspecialchars($row['completed_orders']); ?></td>
                            <td><?php echo htmlspecialchars($row['pending_orders']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                    <?php foreach ($yearly_orders as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_orders']); ?></td>
                            <td><?php echo htmlspecialchars($row['completed_orders']); ?></td>
                            <td><?php echo htmlspecialchars($row['pending_orders']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Satisfaction Table -->
    <div id="customerSatisfactionSection" class="dashboard-card fade-in bg-white rounded-xl p-6">
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
                    <?php foreach ($customer_satisfaction as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['period']); ?></td>
                            <td><?php echo htmlspecialchars($row['excellent']); ?></td>
                            <td><?php echo htmlspecialchars($row['good']); ?></td>
                            <td><?php echo htmlspecialchars($row['average']); ?></td>
                            <td><?php echo htmlspecialchars($row['poor']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_responses']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                // If both filters are "All", show only Daily Revenue
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
            document.getElementById('categoryFilter').value = '';
            document.getElementById('periodFilter').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            const sections = [
                'dailyRevenueSection',
                'monthlyRevenueSection',
                'yearlyRevenueSection',
                'dailyOrdersSection',
                'monthlyOrdersSection',
                'yearlyOrdersSection',
                'customerSatisfactionSection'
            ];
            // Hide all sections except Daily Revenue
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