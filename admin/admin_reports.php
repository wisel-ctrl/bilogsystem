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

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Main Content Area -->
<div class="p-6">
    <!-- Filter Section -->
    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-filter mr-2 text-accent-brown"></i>
            Report Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Report Type</label>
                <select class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
                    <option>Revenue Analysis</option>
                    <option>Menu Sales</option>
                    <option>Seasonal Trends</option>
                    <option>Customer Satisfaction</option>
                    <option>Order Summary</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Date Range</label>
                <select class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
                    <option>Today</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>This Year</option>
                    <option>Custom Range</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Start Date</label>
                <input type="date" class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">End Date</label>
                <input type="date" class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown hover:-translate-y-1 transition-all flex items-center">
                <i class="fas fa-search mr-2"></i>
                Generate Report
            </button>
            <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown hover:-translate-y-1 transition-all flex items-center" onclick="printReport()">
                <i class="fas fa-download mr-2"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
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

        <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
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

        <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
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

    <!-- Detailed Report Tables -->
    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Revenue Report
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-deep-brown text-warm-cream">
                        <th class="p-3 text-left">Period</th>
                        <th class="p-3 text-left">Revenue</th>
                        <th class="p-3 text-left">Growth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Today</td>
                        <td class="p-3 border-b border-warm-cream/50">₱2,450</td>
                        <td class="p-3 border-b border-warm-cream/50 text-green-600">+12%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">This Week</td>
                        <td class="p-3 border-b border-warm-cream/50">₱18,750</td>
                        <td class="p-3 border-b border-warm-cream/50 text-green-600">+8%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">This Month</td>
                        <td class="p-3 border-b border-warm-cream/50">₱84,320</td>
                        <td class="p-3 border-b border-warm-cream/50 text-green-600">+15%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">This Year</td>
                        <td class="p-3 border-b border-warm-cream/50">₱950,680</td>
                        <td class="p-3 border-b border-warm-cream/50 text-green-600">+22%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Order Report
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-deep-brown text-warm-cream">
                        <th class="p-3 text-left">Period</th>
                        <th class="p-3 text-left">Orders</th>
                        <th class="p-3 text-left">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Today</td>
                        <td class="p-3 border-b border-warm-cream/50">124</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                <div class="bg-deep-brown h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">This Week</td>
                        <td class="p-3 border-b border-warm-cream/50">892</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                <div class="bg-deep-brown h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">This Month</td>
                        <td class="p-3 border-b border-warm-cream/50">3,847</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                <div class="bg-deep-brown h-2 rounded-full" style="width: 90%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">This Year</td>
                        <td class="p-3 border-b border-warm-cream/50">45,320</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/50 h-2 rounded-full">
                                <div class="bg-deep-brown h-2 rounded-full" style="width: 95%"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Customer Satisfaction Report
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-deep-brown text-warm-cream">
                        <th class="p-3 text-left">Rating</th>
                        <th class="p-3 text-left">Percentage</th>
                        <th class="p-3 text-left">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Excellent</td>
                        <td class="p-3 border-b border-warm-cream/50">65%</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/30 h-3 rounded-full">
                                <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: 65%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Good</td>
                        <td class="p-3 border-b border-warm-cream/50">25%</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/30 h-3 rounded-full">
                                <div class="bg-blue-500 h-3 rounded-full transition-all duration-500" style="width: 25%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Average</td>
                        <td class="p-3 border-b border-warm-cream/50">8%</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/30 h-3 rounded-full">
                                <div class="bg-yellow-500 h-3 rounded-full transition-all duration-500" style="width: 8%"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Poor</td>
                        <td class="p-3 border-b border-warm-cream/50">2%</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="w-full bg-warm-cream/30 h-3 rounded-full">
                                <div class="bg-red-500 h-3 rounded-full transition-all duration-500" style="width: 2%"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Top Menu Items Report
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-deep-brown text-warm-cream">
                        <th class="p-3 text-left">Item</th>
                        <th class="p-3 text-left">Sales Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Cappuccino</td>
                        <td class="p-3 border-b border-warm-cream/50">35%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Latte</td>
                        <td class="p-3 border-b border-warm-cream/50">25%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Americano</td>
                        <td class="p-3 border-b border-warm-cream/50">15%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Espresso</td>
                        <td class="p-3 border-b border-warm-cream/50">10%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Frappe</td>
                        <td class="p-3 border-b border-warm-cream/50">8%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">Others</td>
                        <td class="p-3 border-b border-warm-cream/50">7%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Detailed Transaction Report
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-deep-brown text-warm-cream">
                        <th class="p-3 text-left">Transaction ID</th>
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Items</th>
                        <th class="p-3 text-left">Amount</th>
                        <th class="p-3 text-left">Customer</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">TX1234</td>
                        <td class="p-3 border-b border-warm-cream/50">2025-07-01</td>
                        <td class="p-3 border-b border-warm-cream/50">2 Cappuccino, 1 Croissant</td>
                        <td class="p-3 border-b border-warm-cream/50">₱385</td>
                        <td class="p-3 border-b border-warm-cream/50">John Doe</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Completed</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">TX1235</td>
                        <td class="p-3 border-b border-warm-cream/50">2025-07-01</td>
                        <tdrender: System: Here's the refactored `admin_reports.php` using Tailwind CSS and focusing on tables instead of charts, maintaining the visual style and layout of the original dashboard:

<xaiArtifact artifact_id="a3a23ac8-02b5-496a-a2e7-0ce74643aa93" artifact_version_id="83b6e9e7-a863-47ff-8d77-db9536c72beb" title="admin_reports.php" contentType="text/php">
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

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<style>
    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-baskerville { font-family: 'Libre Baskerville', serif; }
    
    /* Custom colors to match dashboard */
    :root {
        --deep-brown: #5D2F0F;
        --rich-brown: #8B4513;
        --accent-brown: #A0522D;
        --warm-cream: #E8E0D5;
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
    <!-- Filter Section -->
    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-filter mr-2 text-accent-brown"></i>
            Report Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Report Type</label>
                <select class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
                    <option>Revenue Analysis</option>
                    <option>Menu Sales</option>
                    <option>Seasonal Trends</option>
                    <option>Customer Satisfaction</option>
                    <option>Order Summary</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Date Range</label>
                <select class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
                    <option>Today</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>This Year</option>
                    <option>Custom Range</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Start Date</label>
                <input type="date" class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">End Date</label>
                <input type="date" class="w-full border border-warm-cream rounded-lg p-2 bg-white font-baskerville focus:outline-none focus:ring-2 focus:ring-deep-brown focus:border-deep-brown transition-all">
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown hover:-translate-y-1 transition-all flex items-center">
                <i class="fas fa-search mr-2"></i>
                Generate Report
            </button>
            <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown hover:-translate-y-1 transition-all flex items-center" onclick="printReport()">
                <i class="fas fa-download mr-2"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
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

        <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Order Summary</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rich-brown font-baskerville">Total Orders</span>
                    <span class="text-lg font-bold text sequences
                        <td class="p-3 border-b border-warm-cream/50">2025-07-01</td>
                        <td class="p-3 border-b border-warm-cream/50">1 Latte, 2 Muffins</td>
                        <td class="p-3 border-b border-warm-cream/50">₱420</td>
                        <td class="p-3 border-b border-warm-cream/50">Jane Smith</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Completed</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">TX1236</td>
                        <td class="p-3 border-b border-warm-cream/50">2025-07-01</td>
                        <td class="p-3 border-b border-warm-cream/50">1 Espresso</td>
                        <td class="p-3 border-b border-warm-cream/50">₱150</td>
                        <td class="p-3 border-b border-warm-cream/50">Mike Johnson</td>
                        <td class="p-3 border-b border-warm-cream/50">
                            <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">Pending</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white bg-opacity-95 border border-warm-cream/50 rounded-xl shadow-lg p-6 mb-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <h3 class="text-xl font-bold text-deep-brown font-playfair mb-6 flex items-center">
            <i class="fas fa-table mr-2 text-accent-brown"></i>
            Recent Activity
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-deep-brown text-warm-cream">
                        <th class="p-3 text-left">Activity</th>
                        <th class="p-3 text-left">Details</th>
                        <th class="p-3 text-left">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full w-8 h-8 flex items-center justify-center mr-2">
                                    <i class="fas fa-shopping-cart text-green-600"></i>
                                </div>
                                New Order
                            </div>
                        </td>
                        <td class="p-3 border-b border-warm-cream/50">Order #1234: 2 Cappuccino, 1 Croissant - ₱385</td>
                        <td class="p-3 border-b border-warm-cream/50">5 min ago</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full w-8 h-8 flex items-center justify-center mr-2">
                                    <i class="fas fa-user-plus text-blue-600"></i>
                                </div>
                                New Employee
                            </div>
                        </td>
                        <td class="p-3 border-b border-warm-cream/50">Maria Santos - Barista</td>
                        <td class="p-3 border-b border-warm-cream/50">1 hour ago</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3 border-b border-warm-cream/50">
                            <div class="flex items-center">
                                <div class="bg-orange-100 rounded-full w-8 h-8 flex items-center justify-center mr-2">
                                    <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                </div>
                                Inventory Alert
                            </div>
                        </td>
                        <td class="p-3 border-b border-warm-cream/50">Coffee beans running low (5 kg)</td>
                        <td class="p-3 border-b border-warm-cream/50">3 hours ago</td>
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
    function printReport() {
        let printSection = document.getElementById('printSection');
        if (!printSection) {
            printSection = document.createElement('div');
            printSection.id = 'printSection';
            document.body.appendChild(printSection);
        }
        
        let tableHtml = `
            <div class="print-header">
                <h1 style="font-size: 24px; font-weight: bold; font-family: 'Playfair Display', serif;">Comprehensive Report</h1>
                <h2 style="font-size: 16px; color: #666; margin-top: 5px;">Caffè Lilio</h2>
            </div>
            <div class="print-date">
                Generated on: ${new Date().toLocaleString()}
            </div>
        `;
        
        // Combine all tables
        document.querySelectorAll('table').forEach((table, index) => {
            const title = table.closest('div').querySelector('h3').textContent.trim();
            tableHtml += `
                <h3 style="font-size: 18px; font-family: 'Playfair Display', serif; margin: 20px 0 10px;">${title}</h3>
                ${table.outerHTML}
            `;
        });
        
        printSection.innerHTML = tableHtml;
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
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                }, index * 100);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.bg-white').forEach(element => {
        element.classList.add('opacity-0', 'translate-y-5', 'transition-all', 'duration-500');
        observer.observe(element);
    });
</script>

<?php
    // Include the layout
    include 'layout.php';
?>