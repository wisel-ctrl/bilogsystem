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
    <!-- Header Section -->
    <div class="dashboard-card fade-in bg-white rounded-xl shadow-lg p-8 mb-8">
        <h3 class="text-2xl font-bold text-deep-brown mb-6 font-playfair flex items-center">
            <i class="fas fa-file-alt mr-2 text-accent-brown"></i>
            Reports
        </h3>
        <p class="text-rich-brown font-baskerville">Generate and view detailed reports for transactions, user activity, and form requests.</p>
    </div>

    <!-- Filters Section -->
    <div class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8">
        <h4 class="text-lg font-semibold text-deep-brown font-playfair mb-4">Filters</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Date Range</label>
                <div class="flex space-x-2">
                    <input type="date" class="w-full p-2 rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                    <span class="text-rich-brown font-baskerville self-center">to</span>
                    <input type="date" class="w-full p-2 rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Status</label>
                <select class="w-full p-2 rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-rich-brown font-baskerville mb-2">Category</label>
                <select class="w-full p-2 rounded-lg border border-warm-cream/50 focus:ring-2 focus:ring-deep-brown focus:outline-none font-baskerville">
                    <option value="">All Categories</option>
                    <option value="transactions">Transactions</option>
                    <option value="user_activity">User Activity</option>
                    <option value="form_requests">Form Requests</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end space-x-2">
            <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                <i class="fas fa-filter mr-2"></i> Apply Filters
            </button>
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                <i class="fas fa-undo mr-2"></i> Reset
            </button>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-shopping-cart mr-2 text-accent-brown"></i>
                Transactions
            </h3>
            <div class="space-x-2">
                <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                </button>
                <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
                <button onclick="printTable('transactionsTable', 'Transactions Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="transactionsTable" class="report-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#1234</td>
                        <td>2025-07-04</td>
                        <td>Juan Dela Cruz</td>
                        <td>₱385</td>
                        <td><span class="text-green-600 font-baskerville">Completed</span></td>
                    </tr>
                    <tr>
                        <td>#1235</td>
                        <td>2025-07-04</td>
                        <td>Maria Santos</td>
                        <td>₱250</td>
                        <td><span class="text-yellow-600 font-baskerville">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#1236</td>
                        <td>2025-07-03</td>
                        <td>Pedro Reyes</td>
                        <td>₱450</td>
                        <td><span class="text-red-600 font-baskerville">Cancelled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Activity Table -->
    <div class="dashboard-card fade-in bg-white rounded-xl p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-user mr-2 text-accent-brown"></i>
                User Activity
            </h3>
            <div class="space-x-2">
                <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                </button>
                <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
                <button onclick="printTable('userActivityTable', 'User Activity Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="userActivityTable" class="report-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>U001</td>
                        <td>Maria Santos</td>
                        <td>Barista</td>
                        <td>Logged In</td>
                        <td>2025-07-04 08:00 AM</td>
                    </tr>
                    <tr>
                        <td>U002</td>
                        <td>Juan Dela Cruz</td>
                        <td>Manager</td>
                        <td>Updated Inventory</td>
                        <td>2025-07-04 07:30 AM</td>
                    </tr>
                    <tr>
                        <td>U003</td>
                        <td>Pedro Reyes</td>
                        <td>Cashier</td>
                        <td>Processed Payment</td>
                        <td>2025-07-03 06:45 PM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Requests Table -->
    <div class="dashboard-card fade-in bg-white rounded-xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-deep-brown font-playfair flex items-center">
                <i class="fas fa-file-alt mr-2 text-accent-brown"></i>
                Form Requests
            </h3>
            <div class="space-x-2">
                <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                </button>
                <button class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
                <button onclick="printTable('formRequestsTable', 'Form Requests Report')" class="bg-deep-brown hover:bg-rich-brown text-warm-cream px-4 py-2 rounded-lg text-sm font-baskerville transition-all duration-300 flex items-center hover-lift">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="formRequestsTable" class="report-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Request Type</th>
                        <th>Submitted By</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>R001</td>
                        <td>Leave Request</td>
                        <td>Maria Santos</td>
                        <td>2025-07-04</td>
                        <td><span class="text-yellow-600 font-baskerville">Pending</span></td>
                    </tr>
                    <tr>
                        <td>R002</td>
                        <td>Inventory Request</td>
                        <td>Juan Dela Cruz</td>
                        <td>2025-07-03</td>
                        <td><span class="text-green-600 font-baskerville">Approved</span></td>
                    </tr>
                    <tr>
                        <td>R003</td>
                        <td>Maintenance Request</td>
                        <td>Pedro Reyes</td>
                        <td>2025-07-02</td>
                        <td><span class="text-red-600 font-baskerville">Rejected</span></td>
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
    // Initialize sidebar state
    document.addEventListener('DOMContentLoaded', () => {
        // Set initial active link for Reports
        const reportsLink = document.querySelector('.sidebar-link[href="admin_reports.php"]');
        if (reportsLink) {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            reportsLink.classList.add('active');
        }
    });

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