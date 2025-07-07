<?php
require_once 'admin_auth.php';
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Define page title
$page_title = "Reports";

// Function to validate dates
function validateDateRange($start_date, $end_date) {
    if (!$start_date || !$end_date) {
        return false;
    }
    $start = DateTime::createFromFormat('Y-m-d', $start_date);
    $end = DateTime::createFromFormat('Y-m-d', $end_date);
    return $start && $end && $start <= $end;
}

// Function to fetch daily revenue data
function getDailyRevenue($conn, $start_date = null, $end_date = null) {
    $sql = "SELECT 
                DATE(order_date) as date,
                SUM(total_amount) as total_revenue,
                COUNT(*) as num_transactions,
                AVG(total_amount) as avg_transaction
            FROM orders
            WHERE status = 'completed'";
    
    $params = [];
    if ($start_date && $end_date && validateDateRange($start_date, $end_date)) {
        $sql .= " AND DATE(order_date) BETWEEN ? AND ?";
        $params = ['ss', $start_date, $end_date];
    } else {
        $sql .= " GROUP BY DATE(order_date) ORDER BY date DESC LIMIT 7";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param(...$params);
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
    
    $params = [];
    if ($year && preg_match('/^\d{4}$/', $year)) {
        $sql .= " AND YEAR(order_date) = ?";
        $params = ['i', $year];
    } else {
        $sql .= " GROUP BY DATE_FORMAT(order_date, '%Y-%m') ORDER BY month DESC LIMIT 12";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param(...$params);
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
    
    $params = [];
    if ($start_date && $end_date && validateDateRange($start_date, $end_date)) {
        $sql .= " WHERE DATE(order_date) BETWEEN ? AND ?";
        $params = ['ss', $start_date, $end_date];
    } else {
        $sql .= " GROUP BY DATE(order_date) ORDER BY date DESC LIMIT 7";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param(...$params);
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
    
    $params = [];
    if ($year && preg_match('/^\d{4}$/', $year)) {
        $sql .= " WHERE YEAR(order_date) = ?";
        $params = ['i', $year];
    } else {
        $sql .= " GROUP BY DATE_FORMAT(order_date, '%Y-%m') ORDER BY month DESC LIMIT 12";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param(...$params);
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
function getCustomerSatisfaction($conn, $period = 'monthly', $year = null) {
    $sql = "";
    $params = [];
    if ($period == 'monthly') {
        $sql = "SELECT 
                    DATE_FORMAT(feedback_date, '%Y-%m') as period,
                    SUM(CASE WHEN rating = 'excellent' THEN 1 ELSE 0 END) / COUNT(*) * 100 as excellent,
                    SUM(CASE WHEN rating = 'good' THEN 1 ELSE 0 END) / COUNT(*) * 100 as good,
                    SUM(CASE WHEN rating = 'average' THEN 1 ELSE 0 END) / COUNT(*) * 100 as average,
                    SUM(CASE WHEN rating = 'poor' THEN 1 ELSE 0 END) / COUNT(*) * 100 as poor,
                    COUNT(*) as total_responses
                FROM customer_feedback";
        if ($year && preg_match('/^\d{4}$/', $year)) {
            $sql .= " WHERE YEAR(feedback_date) = ?";
            $params = ['i', $year];
        }
        $sql .= " GROUP BY DATE_FORMAT(feedback_date, '%Y-%m') ORDER BY period DESC LIMIT 12";
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
    if (!empty($params)) {
        $stmt->bind_param(...$params);
    }
    
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

// Handle AJAX requests
if (isset($_GET['action']) && $_GET['action'] === 'fetch_data') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'data' => [], 'error' => ''];
    
    try {
        $category = $_GET['category'] ?? '';
        $period = $_GET['period'] ?? '';
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        
        switch ($category) {
            case 'revenue':
                if ($period === 'daily') {
                    $response['data'] = getDailyRevenue($conn, $start_date, $end_date);
                } elseif ($period === 'monthly') {
                    $response['data'] = getMonthlyRevenue($conn, $end_date);
                } elseif ($period === 'yearly') {
                    $response['data'] = getYearlyRevenue($conn);
                }
                break;
            case 'orders':
                if ($period === 'daily') {
                    $response['data'] = getDailyOrders($conn, $start_date, $end_date);
                } elseif ($period === 'monthly') {
                    $response['data'] = getMonthlyOrders($conn, $end_date);
                } elseif ($period === 'yearly') {
                    $response['data'] = getYearlyOrders($conn);
                }
                break;
            case 'customer_satisfaction':
                $response['data'] = getCustomerSatisfaction($conn, $period, $end_date);
                break;
        }
        $response['success'] = true;
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }
    
    echo json_encode($response);
    exit;
}

// Fetch initial data
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

    /* Loading state */
    .loading {
        opacity: 0.5;
        pointer-events: none;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #8B4513;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
        display: none;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
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
        position: relative;
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

    /* Error message */
    .error-message {
        background: #ffebee;
        color: #c62828;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
        display: none;
    }

    /* Chart container */
    .chart-container {
        position: relative;
        margin: 20px 0;
        max-width: 800px;
        width: 100%;
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
        .chart-container {
            display: none;
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

        <div class="error-message" id="errorMessage"></div>

        <div class="border-t border-warm-cream/30 pt-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                <div>
                    <label class="block text-sm font-medium text-rich-brown font-baskerville mb-1">Start Date</label>
                    <input type="date" id="startDate" class="w-full p-2 text-sm rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                </div>
                <div>
                    <label class="block text-sm font-medium text-rich-brown font-baskerville mb-1">End Date/Year</label>
                    <input type="text" id="endDate" class="w-full p-2 text-sm rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville" placeholder="YYYY or YYYY-MM-DD">
                </div>
                <div class="flex items-end space-x-2">
                    <button id="applyFilters" class="w-full bg-deep-brown hover:bg-rich-brown text-warm-cream px-3 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center justify-center hover-lift relative">
                        <span class="spinner mr-2" id="applySpinner"></span>
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
                <button onclick="exportToCSV('dailyRevenueTable', 'daily_revenue')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('dailyRevenueTable', 'Daily Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="dailyRevenueChart"></canvas>
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
                <button onclick="exportToCSV('monthlyRevenueTable', 'monthly_revenue')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('monthlyRevenueTable', 'Monthly Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="monthlyRevenueChart"></canvas>
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
                <button onclick="exportToCSV('yearlyRevenueTable', 'yearly_revenue')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('yearlyRevenueTable', 'Yearly Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="yearlyRevenueChart"></canvas>
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
                <button onclick="exportToCSV('dailyOrdersTable', 'daily_orders')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('dailyOrdersTable', 'Daily Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="dailyOrdersChart"></canvas>
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
                <button onclick="exportToCSV('monthlyOrdersTable', 'monthly_orders')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('monthlyOrdersTable', 'Monthly Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="monthlyOrdersChart"></canvas>
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
                <button onclick="exportToCSV('yearlyOrdersTable', 'yearly_orders')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('yearlyOrdersTable', 'Yearly Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="yearlyOrdersChart"></canvas>
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
                <button onclick="exportToCSV('customerSatisfactionTable', 'customer_satisfaction')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-download mr-2"></i> CSV
                </button>
                <button onclick="printTable('customerSatisfactionTable', 'Customer Satisfaction Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="customerSatisfactionChart"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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

        // Initialize charts
        const charts = {};
        function initializeCharts() {
            // Daily Revenue Chart
            charts.dailyRevenue = new Chart(document.getElementById('dailyRevenueChart'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column($daily_revenue, 'date')); ?>,
                    datasets: [{
                        label: 'Total Revenue',
                        data: <?php echo json_encode(array_column($daily_revenue, 'total_revenue')); ?>,
                        borderColor: '#8B4513',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Revenue (₱)' }
                        }
                    }
                }
            });

            // Monthly Revenue Chart
            charts.monthlyRevenue = new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($monthly_revenue, 'month')); ?>,
                    datasets: [{
                        label: 'Total Revenue',
                        data: <?php echo json_encode(array_column($monthly_revenue, 'total_revenue')); ?>,
                        backgroundColor: '#8B4513'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Revenue (₱)' }
                        }
                    }
                }
            });

            // Yearly Revenue Chart
            charts.yearlyRevenue = new Chart(document.getElementById('yearlyRevenueChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($yearly_revenue, 'year')); ?>,
                    datasets: [{
                        label: 'Total Revenue',
                        data: <?php echo json_encode(array_column($yearly_revenue, 'total_revenue')); ?>,
                        backgroundColor: '#8B4513'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Revenue (₱)' }
                        }
                    }
                }
            });

            // Daily Orders Chart
            charts.dailyOrders = new Chart(document.getElementById('dailyOrdersChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($daily_orders, 'date')); ?>,
                    datasets: [
                        {
                            label: 'Total Orders',
                            data: <?php echo json_encode(array_column($daily_orders, 'total_orders')); ?>,
                            backgroundColor: '#8B4513'
                        },
                        {
                            label: 'Completed Orders',
                            data: <?php echo json_encode(array_column($daily_orders, 'completed_orders')); ?>,
                            backgroundColor: '#5D2F0F'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Number of Orders' }
                        }
                    }
                }
            });

            // Monthly Orders Chart
            charts.monthlyOrders = new Chart(document.getElementById('monthlyOrdersChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($monthly_orders, 'month')); ?>,
                    datasets: [
                        {
                            label: 'Total Orders',
                            data: <?php echo json_encode(array_column($monthly_orders, 'total_orders')); ?>,
                            backgroundColor: '#8B4513'
                        },
                        {
                            label: 'Completed Orders',
                            data: <?php echo json_encode(array_column($monthly_orders, 'completed_orders')); ?>,
                            backgroundColor: '#5D2F0F'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Number of Orders' }
                        }
                    }
                }
            });

            // Yearly Orders Chart
            charts.yearlyOrders = new Chart(document.getElementById('yearlyOrdersChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($yearly_orders, 'year')); ?>,
                    datasets: [
                        {
                            label: 'Total Orders',
                            data: <?php echo json_encode(array_column($yearly_orders, 'total_orders')); ?>,
                            backgroundColor: '#8B4513'
                        },
                        {
                            label: 'Completed Orders',
                            data: <?php echo json_encode(array_column($yearly_orders, 'completed_orders')); ?>,
                            backgroundColor: '#5D2F0F'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Number of Orders' }
                        }
                    }
                }
            });

            // Customer Satisfaction Chart
            charts.customerSatisfaction = new Chart(document.getElementById('customerSatisfactionChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($customer_satisfaction, 'period')); ?>,
                    datasets: [
                        {
                            label: 'Excellent',
                            data: <?php echo json_encode(array_map(function($row) { return floatval(str_replace('%', '', $row['excellent'])); }, $customer_satisfaction)); ?>,
                            backgroundColor: '#4CAF50'
                        },
                        {
                            label: 'Good',
                            data: <?php echo json_encode(array_map(function($row) { return floatval(str_replace('%', '', $row['good'])); }, $customer_satisfaction)); ?>,
                            backgroundColor: '#2196F3'
                        },
                        {
                            label: 'Average',
                            data: <?php echo json_encode(array_map(function($row) { return floatval(str_replace('%', '', $row['average'])); }, $customer_satisfaction)); ?>,
                            backgroundColor: '#FF9800'
                        },
                        {
                            label: 'Poor',
                            data: <?php echo json_encode(array_map(function($row) { return floatval(str_replace('%', '', $row['poor'])); }, $customer_satisfaction)); ?>,
                            backgroundColor: '#F44336'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Percentage (%)' }
                        }
                    }
                }
            });
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

        // Validate date inputs
        function validateInputs() {
            const period = document.getElementById('periodFilter').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const errorMessage = document.getElementById('errorMessage');

            if (period === 'daily' && startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                if (start > end) {
                    errorMessage.textContent = 'Start date must be before end date';
                    errorMessage.style.display = 'block';
                    return false;
                }
            }
            if (period === 'yearly' && endDate && !/^\d{4}$/.test(endDate)) {
                errorMessage.textContent = 'Please enter a valid year (YYYY)';
                errorMessage.style.display = 'block';
                return false;
            }
            errorMessage.style.display = 'none';
            return true;
        }

        // Update table data
        function updateTable(tableId, data) {
            const table = document.getElementById(tableId);
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';

            if (tableId.includes('Revenue')) {
                data.forEach(row => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.date || row.month || row.year}</td>
                            <td>₱${row.total_revenue}</td>
                            <td>${row.num_transactions}</td>
                            <td>₱${row.avg_transaction}</td>
                        </tr>
                    `;
                });
                // Update corresponding chart
                const chartId = tableId.replace('Table', 'Chart');
                if (charts[chartId.replace('Chart', '')]) {
                    charts[chartId.replace('Chart', '')].data.labels = data.map(row => row.date || row.month || row.year);
                    charts[chartId.replace('Chart', '')].data.datasets[0].data = data.map(row => parseFloat(row.total_revenue));
                    if (chartId.includes('daily')) {
                        charts[chartId.replace('Chart', '')].data.datasets[0].data = data.map(row => parseFloat(row.total_revenue));
                    }
                    charts[chartId.replace('Chart', '')].update();
                }
            } else if (tableId.includes('Orders')) {
                data.forEach(row => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.date || row.month || row.year}</td>
                            <td>${row.total_orders}</td>
                            <td>${row.completed_orders}</td>
                            <td>${row.pending_orders}</td>
                        </tr>
                    `;
                });
                // Update corresponding chart
                const chartId = tableId.replace('Table', 'Chart');
                if (charts[chartId.replace('Chart', '')]) {
                    charts[chartId.replace('Chart', '')].data.labels = data.map(row => row.date || row.month || row.year);
                    charts[chartId.replace('Chart', '')].data.datasets[0].data = data.map(row => row.total_orders);
                    charts[chartId.replace('Chart', '')].data.datasets[1].data = data.map(row => row.completed_orders);
                    charts[chartId.replace('Chart', '')].update();
                }
            } else if (tableId.includes('customerSatisfaction')) {
                data.forEach(row => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.period}</td>
                            <td>${row.excellent}</td>
                            <td>${row.good}</td>
                            <td>${row.average}</td>
                            <td>${row.poor}</td>
                            <td>${row.total_responses}</td>
                        </tr>
                    `;
                });
                // Update corresponding chart
                if (charts.customerSatisfaction) {
                    charts.customerSatisfaction.data.labels = data.map(row => row.period);
                    charts.customerSatisfaction.data.datasets[0].data = data.map(row => parseFloat(row.excellent.replace('%', '')));
                    charts.customerSatisfaction.data.datasets[1].data = data.map(row => parseFloat(row.good.replace('%', '')));
                    charts.customerSatisfaction.data.datasets[2].data = data.map(row => parseFloat(row.average.replace('%', '')));
                    charts.customerSatisfaction.data.datasets[3].data = data.map(row => parseFloat(row.poor.replace('%', '')));
                    charts.customerSatisfaction.update();
                }
            }
        }

        // Filter tables function
        async function filterTables() {
            if (!validateInputs()) return;

            const category = document.getElementById('categoryFilter').value;
            const period = document.getElementById('periodFilter').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const applyButton = document.getElementById('applyFilters');
            const spinner = document.getElementById('applySpinner');
            const errorMessage = document.getElementById('errorMessage');

            const sections = [
                'dailyRevenueSection',
                'monthlyRevenueSection',
                'yearlyRevenueSection',
                'dailyOrdersSection',
                'monthlyOrdersSection',
                'yearlyOrdersSection',
                'customerSatisfactionSection'
            ];

            // Show loading state
            applyButton.classList.add('loading');
            spinner.style.display = 'inline-block';

            // Hide all sections
            sections.forEach(section => {
                document.getElementById(section).classList.add('hidden');
            });

            try {
                if (category && period) {
                    // Fetch data via AJAX
                    const response = await fetch(`admin_reports.php?action=fetch_data&category=${category}&period=${period}&start_date=${startDate}&end_date=${endDate}`);
                    const result = await response.json();

                    if (result.success) {
                        let targetSection = '';
                        if (category === 'revenue') {
                            if (period === 'daily') {
                                targetSection = 'dailyRevenueSection';
                                updateTable('dailyRevenueTable', result.data);
                            } else if (period === 'monthly') {
                                targetSection = 'monthlyRevenueSection';
                                updateTable('monthlyRevenueTable', result.data);
                            } else if (period === 'yearly') {
                                targetSection = 'yearlyRevenueSection';
                                updateTable('yearlyRevenueTable', result.data);
                            }
                        } else if (category === 'orders') {
                            if (period === 'daily') {
                                targetSection = 'dailyOrdersSection';
                                updateTable('dailyOrdersTable', result.data);
                            } else if (period === 'monthly') {
                                targetSection = 'monthlyOrdersSection';
                                updateTable('monthlyOrdersTable', result.data);
                            } else if (period === 'yearly') {
                                targetSection = 'yearlyOrdersSection';
                                updateTable('yearlyOrdersTable', result.data);
                            }
                        } else if (category === 'customer_satisfaction') {
                            targetSection = 'customerSatisfactionSection';
                            updateTable('customerSatisfactionTable', result.data);
                        }
                        if (targetSection) {
                            document.getElementById(targetSection).classList.remove('hidden');
                        }
                    } else {
                        errorMessage.textContent = result.error || 'Failed to fetch data';
                        errorMessage.style.display = 'block';
                    }
                } else {
                    document.getElementById('customerSatisfactionSection').classList.remove('hidden');
                }
            } catch (error) {
                errorMessage.textContent = 'An error occurred while fetching data';
                errorMessage.style.display = 'block';
            } finally {
                applyButton.classList.remove('loading');
                spinner.style.display = 'none';
            }
        }

        // Reset filters function
        function resetFilters() {
            document.getElementById('categoryFilter').value = 'customer_satisfaction';
            document.getElementById('periodFilter').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.style.display = 'none';

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

            // Reset charts to initial data
            updateTable('dailyRevenueTable', <?php echo json_encode($daily_revenue); ?>);
            updateTable('monthlyRevenueTable', <?php echo json_encode($monthly_revenue); ?>);
            updateTable('yearlyRevenueTable', <?php echo json_encode($yearly_revenue); ?>);
            updateTable('dailyOrdersTable', <?php echo json_encode($daily_orders); ?>);
            updateTable('monthlyOrdersTable', <?php echo json_encode($monthly_orders); ?>);
            updateTable('yearlyOrdersTable', <?php echo json_encode($yearly_orders); ?>);
            updateTable('customerSatisfactionTable', <?php echo json_encode($customer_satisfaction); ?>);
        }

        // Export to CSV function
        function exportToCSV(tableId, filename) {
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            // Add headers
            const headers = Array.from(rows[0].querySelectorAll('th')).map(th => `"${th.textContent}"`);
            csv.push(headers.join(','));

            // Add rows
            for (let i = 1; i < rows.length; i++) {
                const cols = Array.from(rows[i].querySelectorAll('td')).map(td => `"${td.textContent.replace(/"/g, '""')}"`);
                csv.push(cols.join(','));
            }

            // Download CSV
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${filename}_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }

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

        // Event listeners for filter buttons
        document.getElementById('applyFilters').addEventListener('click', filterTables);
        document.getElementById('resetFilters').addEventListener('click', resetFilters);

        // Initialize default state and charts
        initializeCharts();
        filterTables();
    });
</script>

<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>