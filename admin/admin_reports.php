<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'admin_auth.php';
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Define page title
$page_title = "Reports";

function formatWeekPeriod($weekString) {
    // Extract year and week number from "YYYY-WNN" format
    list($year, $week) = explode('-W', $weekString);
    $week = (int)$week;
    // Calculate the start date of the week (Monday)
    $date = new DateTime();
    $date->setISODate($year, $week);
    $startDate = $date->format('F j');
    // Calculate the end date of the week (Sunday)
    $date->modify('+6 days');
    $endDate = $date->format('j, Y');
    return "$startDate - $endDate";
}

// Function to fetch daily orders
function getDailyOrders($conn) {
    try {
        $query = "
            SELECT 
                DATE(s.created_at) as order_date,
                COUNT(DISTINCT o.order_id) as total_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid > 0 THEN o.order_id END) as completed_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid = 0 THEN o.order_id END) as pending_orders
            FROM order_tb o
            JOIN sales_tb s ON o.sales_id = s.sales_id
            WHERE DATE(s.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(s.created_at)
            ORDER BY order_date DESC
            LIMIT 7
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Daily Orders Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch weekly orders
function getWeeklyOrders($conn) {
    try {
        $query = "
            SELECT 
                CONCAT(YEAR(s.created_at), '-W', LPAD(WEEK(s.created_at, 1), 2, '0')) as order_week,
                COUNT(DISTINCT o.order_id) as total_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid > 0 THEN o.order_id END) as completed_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid = 0 THEN o.order_id END) as pending_orders
            FROM order_tb o
            JOIN sales_tb s ON o.sales_id = s.sales_id
            WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
            GROUP BY YEAR(s.created_at), WEEK(s.created_at, 1)
            ORDER BY s.created_at DESC
            LIMIT 12
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Weekly Orders Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch monthly orders
function getMonthlyOrders($conn) {
    try {
        $query = "
            SELECT 
                DATE_FORMAT(s.created_at, '%Y-%m') as order_month,
                COUNT(DISTINCT o.order_id) as total_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid > 0 THEN o.order_id END) as completed_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid = 0 THEN o.order_id END) as pending_orders
            FROM order_tb o
            JOIN sales_tb s ON o.sales_id = s.sales_id
            WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY YEAR(s.created_at), MONTH(s.created_at)
            ORDER BY s.created_at DESC
            LIMIT 12
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Monthly Orders Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch yearly orders
function getYearlyOrders($conn) {
    try {
        $query = "
            SELECT 
                YEAR(s.created_at) as order_year,
                COUNT(DISTINCT o.order_id) as total_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid > 0 THEN o.order_id END) as completed_orders,
                COUNT(DISTINCT CASE WHEN s.amount_paid = 0 THEN o.order_id END) as pending_orders
            FROM order_tb o
            JOIN sales_tb s ON o.sales_id = s.sales_id
            WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
            GROUP BY YEAR(s.created_at)
            ORDER BY order_year DESC
            LIMIT 5
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Yearly Orders Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch daily revenue
function getDailyRevenue($conn) {
    try {
        $query = "
            SELECT 
                DATE(s.created_at) as revenue_date,
                SUM(s.total_price - s.discount_price) as total_revenue,
                COUNT(DISTINCT s.sales_id) as transaction_count,
                IF(COUNT(DISTINCT s.sales_id) > 0, SUM(s.total_price - s.discount_price) / COUNT(DISTINCT s.sales_id), 0) as avg_transaction
            FROM sales_tb s
            WHERE DATE(s.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(s.created_at)
            ORDER BY revenue_date DESC
            LIMIT 7
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Daily Revenue Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch weekly revenue
function getWeeklyRevenue($conn) {
    try {
        $query = "
            SELECT 
                CONCAT(YEAR(s.created_at), '-W', LPAD(WEEK(s.created_at, 1), 2, '0')) as revenue_week,
                SUM(s.total_price - s.discount_price) as total_revenue,
                COUNT(DISTINCT s.sales_id) as transaction_count,
                IF(COUNT(DISTINCT s.sales_id) > 0, SUM(s.total_price - s.discount_price) / COUNT(DISTINCT s.sales_id), 0) as avg_transaction
            FROM sales_tb s
            WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
            GROUP BY YEAR(s.created_at), WEEK(s.created_at, 1) 
            ORDER BY s.created_at DESC
            LIMIT 12
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Weekly Revenue Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch monthly revenue
function getMonthlyRevenue($conn) {
    try {
        $query = "
            SELECT 
                DATE_FORMAT(s.created_at, '%Y-%m') as revenue_month,
                SUM(s.total_price - s.discount_price) as total_revenue,
                COUNT(DISTINCT s.sales_id) as transaction_count,
                IF(COUNT(DISTINCT s.sales_id) > 0, SUM(s.total_price - s.discount_price) / COUNT(DISTINCT s.sales_id), 0) as avg_transaction
            FROM sales_tb s
            WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY YEAR(s.created_at), MONTH(s.created_at)
            ORDER BY s.created_at DESC
            LIMIT 12
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Monthly Revenue Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to fetch yearly revenue
function getYearlyRevenue($conn) {
    try {
        $query = "
            SELECT 
                YEAR(s.created_at) as revenue_year,
                SUM(s.total_price - s.discount_price) as total_revenue,
                COUNT(DISTINCT s.sales_id) as transaction_count,
                IF(COUNT(DISTINCT s.sales_id) > 0, SUM(s.total_price - s.discount_price) / COUNT(DISTINCT s.sales_id), 0) as avg_transaction
            FROM sales_tb s
            WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
            GROUP BY YEAR(s.created_at)
            ORDER BY revenue_year DESC
            LIMIT 5
        ";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Yearly Revenue Query Failed: " . $e->getMessage());
        return [];
    }
}

// Function to generate customer satisfaction report
function generateCustomerSatisfactionReport($conn, $period = 'monthly') {
    try {
        // Initialize table body
        $tableBody = '';

        // Helper function to calculate percentages for a given dataset
        function calculateRatingPercentages($ratings, $total) {
            if ($total == 0) {
                return ['excellent' => 0, 'good' => 0, 'average' => 0, 'poor' => 0, 'total' => 0];
            }
            $excellent = $good = $average = $poor = 0;
            foreach ($ratings as $rating) {
                if ($rating == 5) $excellent++;
                elseif ($rating == 4) $good++;
                elseif ($rating == 3) $average++;
                elseif ($rating == 1 || $rating == 2) $poor++;
            }
            return [
                'excellent' => round(($excellent / $total) * 100, 0),
                'good' => round(($good / $total) * 100, 0),
                'average' => round(($average / $total) * 100, 0),
                'poor' => round(($poor / $total) * 100, 0),
                'total' => $total
            ];
        }

        if ($period == 'daily') {
            $sql = "
                SELECT 
                    DATE(created_at) as period_date,
                    food_rating, ambiance_rating, service_rating, reservation_rating
                FROM ratings
                WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at)
                ORDER BY period_date DESC
                LIMIT 7
            ";
            $stmt = $conn->query($sql);
            $ratingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ratingsData as $row) {
                $allRatings = [
                    $row['food_rating'],
                    $row['ambiance_rating'],
                    $row['service_rating']
                ];
                if ($row['reservation_rating'] > 0) {
                    $allRatings[] = $row['reservation_rating'];
                }
                $stats = calculateRatingPercentages($allRatings, count($allRatings));
                $periodName = date('F j, Y', strtotime($row['period_date']));
                $tableBody .= "
                    <tr>
                        <td>$periodName</td>
                        <td>{$stats['excellent']}%</td>
                        <td>{$stats['good']}%</td>
                        <td>{$stats['average']}%</td>
                        <td>{$stats['poor']}%</td>
                        <td>" . number_format($stats['total']) . "</td>
                    </tr>";
            }
        } elseif ($period == 'weekly') {
            $sql = "
                SELECT 
                    CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at, 1), 2, '0')) as period_week,
                    food_rating, ambiance_rating, service_rating, reservation_rating
                FROM ratings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
                GROUP BY YEAR(created_at), WEEK(created_at, 1)
                ORDER BY created_at DESC
                LIMIT 12
            ";
            $stmt = $conn->query($sql);
            $ratingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ratingsData as $row) {
                $allRatings = [
                    $row['food_rating'],
                    $row['ambiance_rating'],
                    $row['service_rating']
                ];
                if ($row['reservation_rating'] > 0) {
                    $allRatings[] = $row['reservation_rating'];
                }
                $stats = calculateRatingPercentages($allRatings, count($allRatings));
                $periodName = formatWeekPeriod($row['period_week']);
                $tableBody .= "
                    <tr>
                        <td>$periodName</td>
                        <td>{$stats['excellent']}%</td>
                        <td>{$stats['good']}%</td>
                        <td>{$stats['average']}%</td>
                        <td>{$stats['poor']}%</td>
                        <td>" . number_format($stats['total']) . "</td>
                    </tr>";
            }
        } elseif ($period == 'monthly') {
            $sql = "
                SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as period_month,
                    food_rating, ambiance_rating, service_rating, reservation_rating
                FROM ratings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY YEAR(created_at), MONTH(created_at)
                ORDER BY created_at DESC
                LIMIT 12
            ";
            $stmt = $conn->query($sql);
            $ratingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ratingsData as $row) {
                $allRatings = [
                    $row['food_rating'],
                    $row['ambiance_rating'],
                    $row['service_rating']
                ];
                if ($row['reservation_rating'] > 0) {
                    $allRatings[] = $row['reservation_rating'];
                }
                $stats = calculateRatingPercentages($allRatings, count($allRatings));
                $periodName = date('F Y', strtotime($row['period_month'] . '-01'));
                $tableBody .= "
                    <tr>
                        <td>$periodName</td>
                        <td>{$stats['excellent']}%</td>
                        <td>{$stats['good']}%</td>
                        <td>{$stats['average']}%</td>
                        <td>{$stats['poor']}%</td>
                        <td>" . number_format($stats['total']) . "</td>
                    </tr>";
            }
        } elseif ($period == 'yearly') {
            $sql = "
                SELECT 
                    YEAR(created_at) as period_year,
                    food_rating, ambiance_rating, service_rating, reservation_rating
                FROM ratings
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
                GROUP BY YEAR(created_at)
                ORDER BY period_year DESC
                LIMIT 5
            ";
            $stmt = $conn->query($sql);
            $ratingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ratingsData as $row) {
                $allRatings = [
                    $row['food_rating'],
                    $row['ambiance_rating'],
                    $row['service_rating']
                ];
                if ($row['reservation_rating'] > 0) {
                    $allRatings[] = $row['reservation_rating'];
                }
                $stats = calculateRatingPercentages($allRatings, count($allRatings));
                $periodName = $row['period_year'];
                $tableBody .= "
                    <tr>
                        <td>$periodName</td>
                        <td>{$stats['excellent']}%</td>
                        <td>{$stats['good']}%</td>
                        <td>{$stats['average']}%</td>
                        <td>{$stats['poor']}%</td>
                        <td>" . number_format($stats['total']) . "</td>
                    </tr>";
            }
        }

        if (empty($ratingsData)) {
            $tableBody = "<tr><td colspan='6' class='text-center'>No data available</td></tr>";
        }

        return $tableBody;
    } catch (PDOException $e) {
        error_log("Customer Satisfaction Query Failed: " . $e->getMessage());
        return "<tr><td colspan='6'>Error fetching data: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
    }
}

// Handle filter parameters
$period = isset($_GET['period']) ? $_GET['period'] : 'daily';
$category = isset($_GET['category']) ? $_GET['category'] : 'revenue';

// Fetch data based on filters
$daily_orders = $period == 'daily' && in_array($category, ['orders', '']) ? getDailyOrders($conn) : [];
$weekly_orders = $period == 'weekly' && in_array($category, ['orders', '']) ? getWeeklyOrders($conn) : [];
$monthly_orders = $period == 'monthly' && in_array($category, ['orders', '']) ? getMonthlyOrders($conn) : [];
$yearly_orders = $period == 'yearly' && in_array($category, ['orders', '']) ? getYearlyOrders($conn) : [];
$daily_revenue = $period == 'daily' && in_array($category, ['revenue', '']) ? getDailyRevenue($conn) : [];
$weekly_revenue = $period == 'weekly' && in_array($category, ['revenue', '']) ? getWeeklyRevenue($conn) : [];
$monthly_revenue = $period == 'monthly' && in_array($category, ['revenue', '']) ? getMonthlyRevenue($conn) : [];
$yearly_revenue = $period == 'yearly' && in_array($category, ['revenue', '']) ? getYearlyRevenue($conn) : [];
$customerSatisfactionTableBody = in_array($category, ['customer_satisfaction', '']) ? generateCustomerSatisfactionReport($conn, $period) : '';

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
                        <option value="weekly">Weekly</option>
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
                    <?php if (empty($daily_revenue)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daily_revenue as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('F j, Y', strtotime($row['revenue_date']))); ?></td>
                                <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                                <td><?php echo number_format($row['transaction_count']); ?></td>
                                <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Weekly Revenue Table -->
    <div id="weeklyRevenueSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-calendar-week mr-2 text-accent-brown"></i>
                Weekly Revenue
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('weeklyRevenueTable', 'Weekly Revenue Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="weeklyRevenueTable" class="report-table">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Total Revenue</th>
                        <th>Number of Transactions</th>
                        <th>Average Transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($weekly_revenue)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($weekly_revenue as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(formatWeekPeriod($row['revenue_week'])); ?></td>
                                <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                                <td><?php echo number_format($row['transaction_count']); ?></td>
                                <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <?php if (empty($monthly_revenue)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($monthly_revenue as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('F Y', strtotime($row['revenue_month'] . '-01'))); ?></td>
                                <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                                <td><?php echo number_format($row['transaction_count']); ?></td>
                                <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <?php if (empty($yearly_revenue)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($yearly_revenue as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['revenue_year']); ?></td>
                                <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                                <td><?php echo number_format($row['transaction_count']); ?></td>
                                <td>₱<?php echo number_format($row['avg_transaction'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <?php if (empty($daily_orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daily_orders as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('F j, Y', strtotime($row['order_date']))); ?></td>
                                <td><?php echo number_format($row['total_orders']); ?></td>
                                <td><?php echo number_format($row['completed_orders']); ?></td>
                                <td><?php echo number_format($row['pending_orders']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

    <!-- Weekly Orders Table -->
    <div id="weeklyOrdersSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-calendar-week mr-2 text-accent-brown"></i>
                Weekly Orders
            </h3>
            <div class="space-x-2">
                <button onclick="printTable('weeklyOrdersTable', 'Weekly Orders Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="weeklyOrdersTable" class="report-table">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Total Orders</th>
                        <th>Completed Orders</th>
                        <th>Pending Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($weekly_orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($weekly_orders as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(formatWeekPeriod($row['order_week'])); ?></td>
                                <td><?php echo number_format($row['total_orders']); ?></td>
                                <td><?php echo number_format($row['completed_orders']); ?></td>
                                <td><?php echo number_format($row['pending_orders']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <?php if (empty($monthly_orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($monthly_orders as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('F Y', strtotime($row['order_month'] . '-01'))); ?></td>
                                <td><?php echo number_format($row['total_orders']); ?></td>
                                <td><?php echo number_format($row['completed_orders']); ?></td>
                                <td><?php echo number_format($row['pending_orders']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <?php if (empty($yearly_orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($yearly_orders as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['order_year']); ?></td>
                                <td><?php echo number_format($row['total_orders']); ?></td>
                                <td><?php echo number_format($row['completed_orders']); ?></td>
                                <td><?php echo number_format($row['pending_orders']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

       <!-- Customer Satisfaction Table -->
       <div id="customerSatisfactionSection" class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8 <?php echo in_array($category, ['customer_satisfaction', '']) ? '' : 'hidden'; ?>">
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
                            <?php echo $customerSatisfactionTableBody; ?>
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
                'weeklyRevenueSection',
                'monthlyRevenueSection',
                'yearlyRevenueSection',
                'dailyOrdersSection',
                'weeklyOrdersSection',
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
                    else if (period === 'weekly') targetSection = 'weeklyRevenueSection';
                    else if (period === 'monthly') targetSection = 'monthlyRevenueSection';
                    else if (period === 'yearly') targetSection = 'yearlyRevenueSection';
                } else if (category === 'orders') {
                    if (period === 'daily') targetSection = 'dailyOrdersSection';
                    else if (period === 'weekly') targetSection = 'weeklyOrdersSection';
                    else if (period === 'monthly') targetSection = 'monthlyOrdersSection';
                    else if (period === 'yearly') targetSection = 'yearlyOrdersSection';
                } else if (category === 'customer_satisfaction') {
                    targetSection = 'customerSatisfactionSection';
                } else if (category === '' && period) {
                    // If category is "All" but period is selected, show all tables for that period
                    if (period === 'daily') {
                        document.getElementById('dailyRevenueSection').classList.remove('hidden');
                        document.getElementById('dailyOrdersSection').classList.remove('hidden');
                    } else if (period === 'weekly') {
                        document.getElementById('weeklyRevenueSection').classList.remove('hidden'); // Added
                        document.getElementById('weeklyOrdersSection').classList.remove('hidden');
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
            const sections = [
                'dailyRevenueSection',
                'monthlyRevenueSection',
                'yearlyRevenueSection',
                'dailyOrdersSection',
                'weeklyOrdersSection',
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