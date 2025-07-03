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

    .table-container {
        overflow-x: auto;
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

    <!-- Summary Tables -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="dashboard-card fade-in p-6 rounded-xl">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Revenue Summary</h4>
            <div class="table-container">
                <table class="w-full print-table">
                    <thead>
                        <tr>
                            <th class="bg-deep-brown text-warm-cream p-3">Metric</th>
                            <th class="bg-deep-brown text-warm-cream p-3">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Today's Revenue</td>
                            <td class="p-3">₱2,450</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Weekly Revenue</td>
                            <td class="p-3">₱18,750</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Monthly Revenue</td>
                            <td class="p-3">₱84,320</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Yearly Revenue</td>
                            <td class="p-3">₱950,680</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card fade-in p-6 rounded-xl">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb- лично</h4>
            <div class="table-container">
                <table class="w-full print-table">
                    <thead>
                        <tr>
                            <th class="bg-deep-brown text-warm-cream p-3">Metric</th>
                            <th class="bg-deep-brown text-warm-cream p-3">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Today's Orders</td>
                            <td class="p-3">124</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Weekly Orders</td>
                            <td class="p-3">892</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Monthly Orders</td>
                            <td class="p-3">3,847</td>
                        </tr>
                        leaks
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Yearly Orders</td>
                            <td class="p-3">45,320</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card fade-in p-6 rounded-xl">
            <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Customer Satisfaction</h4>
            <div class="table-container">
                <table class="w-full print-table">
                    <thead>
                        <tr>
                            <th class="bg-deep-brown text-warm-cream p-3">Rating</th>
                            <th class="bg-deep-brown text-warm-cream p-3">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Excellent</td>
                            <td class="p-3">65%</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Good</td>
                            <td class="p-3">25%</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Average</td>
                            <td class="p-3">8%</td>
                        </tr>
                        <tr class="hover:bg-warm-cream/20 transition-all">
                            <td class="p-3">Poor</td>
                            <td class="p
-3">2%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Financial Overview Table -->
    <div class="dashboard-card fade-in p-6 rounded-xl mb-8">
        <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Financial Overview</h4>
        <div class="table-container">
            <table class="w-full print-table">
                <thead>
                    <tr>
                        <th class="bg-deep-brown text-warm-cream p-3">Metric</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Revenue</td>
                        <td class="p-3">₱84,320</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Expenses</td>
                        <td class="p-3">₱45,680</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Profit</td>
                        <td class="p-3">₱38,640</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Menu Sales Table -->
    <div class="dashboard-card fade-in p-6 rounded-xl mb-8">
        <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Top Menu Items</h4>
        <div class="table-container">
            <table class="w-full print-table">
                <thead>
                    <tr>
                        <th class="bg-deep-brown text-warm-cream p-3">Item</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Cappuccino</td>
                        <td class="p-3">35%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Latte</td>
                        <td class="p-3">25%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Americano</td>
                        <td class="p-3">15%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Espresso</td>
                        <td class="p-3">10%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Frappe</td>
                        <td class="p-3">8%</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Others</td>
                        <td class="p-3">7%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Seasonal Trends Table -->
    <div class="dashboard-card fade-in p-6 rounded-xl mb-8">
        <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Seasonal Trends</h4>
        <div class="table-container">
            <table class="w-full print-table">
                <thead>
                    <tr>
                        <th class="bg-deep-brown text-warm-cream p-3">Season</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Spring</td>
                        <td class="p-3">₱180,000</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Summer</td>
                        <td class="p-3">₱250,000</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Fall</td>
                        <td class="p-3">₱220,000</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Winter</td>
                        <td class="p-3">₱200,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="dashboard-card fade-in p-6 rounded-xl">
        <h4 class="text-lg font-bold text-deep-brown font-playfair mb-4">Recent Activity</h4>
        <div class="table-container">
            <table class="w-full print-table">
                <thead>
                    <tr>
                        <th class="bg-deep-brown text-warm-cream p-3">Activity</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Details</th>
                        <th class="bg-deep-brown text-warm-cream p-3">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">New order #1234 received</td>
                        <td class="p-3">2 Cappuccino, 1 Croissant - ₱385</td>
                        <td class="p-3">5 min ago</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">New employee added</td>
                        <td class="p-3">Maria Santos - Barista</td>
                        <td class="p-3">1 hour ago</td>
                    </tr>
                    <tr class="hover:bg-warm-cream/20 transition-all">
                        <td class="p-3">Low inventory warning</td>
                        <td class="p-3">Coffee beans running low (5 kg remaining)</td>
                        <td class="p-3">3 hours ago</td>
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
        
        let tablesHtml = '';
        document.querySelectorAll('.print-table').forEach((table, index) => {
            const title = table.closest('.dashboard-card').querySelector('h4').textContent;
            tablesHtml += `
                <div class="print-header">
                    <h1 style="font-size: 24px; font-weight: bold; font-family: 'Playfair Display', serif;">${title}</h1>
                    <h2 style="font-size: 16px; color: #666; margin-top: 5px;">Caffè Lilio</h2>
                </div>
                ${table.outerHTML}
                <div style="page-break-after: always;"></div>
            `;
        });
        
        printSection.innerHTML = `
            <div class="print-date">
                Generated on: ${new Date().toLocaleString()}
            </div>
            ${tablesHtml}
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