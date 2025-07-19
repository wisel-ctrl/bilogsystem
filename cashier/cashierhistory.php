<?php 
require_once 'cashier_auth.php'; 
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History | Sales-History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Libre Baskerville', serif;
            background-color: #fef3c7;
        }
        .font-serif {
            font-family: 'Libre Baskerville', serif;
        }
    </style>
</head>
<body class="min-h-screen">
    <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-amber-100/20 px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="cashierindex.php" class="text-amber-900 hover:text-amber-800 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="hidden sm:block text-xl sm:text-2xl font-bold text-amber-900 font-serif">Sales-History</h2>
            </div>
            <div class="text-sm text-amber-800 font-serif flex-1 text-center mx-4 hidden sm:flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span id="current-date"></span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="../logout.php?usertype=cashier" class="flex items-center space-x-2 hover:bg-amber-100/10 p-2 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-sm font-medium text-amber-900 font-serif">Sign Out</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-amber-900 mb-4 md:mb-0">Sales History</h1>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <span class="hidden sm:flex items-center text-amber-700">From</span>
                        <div class="relative">
                            <input type="date" id="start-date" class="appearance-none bg-amber-50 border border-amber-200 text-amber-900 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <span class="hidden sm:flex items-center text-amber-700">to</span>
                        <div class="relative">
                            <input type="date" id="end-date" class="appearance-none bg-amber-50 border border-amber-200 text-amber-900 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <button id="apply-date-range" class="bg-amber-600 hover:bg-amber-700 text-white py-2 px-4 rounded-lg transition-colors duration-200">
                            Apply
                        </button>
                    </div>
                    
                    <button id="export-button" class="bg-amber-600 hover:bg-amber-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-amber-200">
                    <thead class="bg-amber-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Transaction ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Items</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Discount Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sales-data" class="bg-white divide-y divide-amber-200">
                        <!-- Data will be inserted here by JavaScript -->
                    </tbody>

                </table>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <div id="showing-text" class="text-sm text-amber-700">
                    Loading data...
                </div>
                <div class="flex space-x-2" id="pagination-buttons">
                    <!-- Pagination will be inserted here by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div id="receiptModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <!-- Modal container -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <!-- Header with date and URL -->
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm text-gray-500">www.caffelilio.com</span>
                                    <span id="currentDate" class="text-sm text-gray-500"></span>
                                </div>
                                
                                <!-- Restaurant header -->
                                <div class="text-center mb-6 border-b border-[#d1d5db] pb-4">
                                    <h1 class="font-playfair text-[28px] font-bold text-[#8B4513]">Caffè Lilio Ristorante</h1>
                                    <p class="italic font-arial text-[#8B4513]">Authentic Italian Cuisine in Liliw, Laguna</p>
                                </div>
                                
                                <!-- Receipt content -->
                                <div class="mt-4 w-full max-w-2xl mx-auto">
                                    <!-- Order info -->
                                    <div class="text-center mb-6 border-b border-[#d1d5db] pb-4">
                                        <h4 id="receiptDate" class="font-bold text-xl text-[#8B4513]"></h4>
                                        <p class="text-gray-500">Order #<span id="receiptId"></span></p>
                                    </div>
                                    
                                    <!-- Items list -->
                                    <table class="w-full mb-6 border-collapse">
                                        <thead>
                                            <tr class="bg-[#8B4513] text-white">
                                                <th class="py-2 px-4 text-left font-arial">Item</th>
                                                <th class="py-2 px-4 text-right font-arial">Qty</th>
                                                <th class="py-2 px-4 text-right font-arial">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="receiptItemsList" class="font-arial">
                                            <!-- Items will be inserted here -->
                                        </tbody>
                                    </table>
                                    
                                    <!-- Summary -->
                                    <div class="border-t border-[#d1d5db] pt-4 space-y-2 font-arial">
                                        <!-- Discount row (hidden by default) -->
                                        <div id="discountRow" class="hidden flex justify-between">
                                            <span id="discountType" class="text-right pr-4"></span>
                                            <span class="font-medium">-₱<span id="discountAmount"></span></span>
                                        </div>
                                        
                                        <!-- Total price -->
                                        <div class="flex justify-between font-bold">
                                            <span>Total Price:</span>
                                            <span>₱<span id="totalPrice"></span></span>
                                        </div>
                                        
                                        <!-- Amount paid -->
                                        <div class="flex justify-between">
                                            <span>Amount Paid:</span>
                                            <span>₱<span id="amountPaid"></span></span>
                                        </div>
                                        
                                        <!-- Amount change -->
                                        <div class="flex justify-between">
                                            <span>Amount Change:</span>
                                            <span>₱<span id="amountChange"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer with buttons and contact info -->
                    <div class="bg-gray-50 px-4 py-3 border-t border-[#d1d5db]">
                        <div class="text-center text-gray-500 text-[14px] py-2 mb-2">
                            Contact us: (049) 123-4567 | info@caffelilio.com | P. Burgos St, Liliw, Laguna
                        </div>
                        <div class="sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="printReceipt()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#8B4513] text-base font-medium text-white hover:bg-[#6d360d] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B4513] sm:ml-3 sm:w-auto sm:text-sm">
                                Print Receipt
                            </button>
                            <button type="button" onclick="closeReceiptModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-[#d1d5db] shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-[#fffbeb] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B4513] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>



<script>
    const userId = <?php echo json_encode($userId); ?>;
    console.log("Logged in User ID:", userId);
    
    // Set current date
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const currentDate = new Date().toLocaleDateString('en-US', options);
    document.getElementById('current-date').textContent = currentDate;

    // Sales History Data Fetching and Display
    let currentPage = 1;
    const itemsPerPage = 5;
    let allSalesData = [];
    let filteredData = [];

    // Format date to be more readable
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    // Format currency
    function formatCurrency(amount) {
        const formattedAmount = parseFloat(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return '₱ ' + formattedAmount;
    }


    let dateFilterActive = false;
    let currentStartDate = null;
    let currentEndDate = null;

    // Add this function to your JavaScript
    async function fetchSalesDataByDateRange(startDate, endDate) {
        try {
            const response = await fetch(`historyFunctions/fetch_sales.php?start_date=${startDate}&end_date=${endDate}`);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            allSalesData = data;
            filteredData = [...allSalesData];
            renderSalesData();
            renderPagination();
        } catch (error) {
            console.error('Error fetching sales data:', error);
            document.getElementById('sales-data').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-amber-800">Error loading sales data. Please try again.</td>
                </tr>
            `;
        }
    }

    // Modify your existing fetchSalesData function to handle both cases
    async function fetchSalesData() {
        try {
            let url = 'historyFunctions/fetch_sales.php';
            if (dateFilterActive && currentStartDate && currentEndDate) {
                url += `?start_date=${currentStartDate}&end_date=${currentEndDate}`;
            }
            
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            allSalesData = data;
            filteredData = [...allSalesData];
            renderSalesData();
            renderPagination();
        } catch (error) {
            console.error('Error fetching sales data:', error);
            document.getElementById('sales-data').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-amber-800">Error loading sales data. Please try again.</td>
                </tr>
            `;
        }
    }

    // Add this event listener to your DOMContentLoaded section
    document.getElementById('apply-date-range').addEventListener('click', () => {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        
        if (startDate && endDate) {
            dateFilterActive = true;
            currentStartDate = startDate;
            currentEndDate = endDate;
            fetchSalesDataByDateRange(startDate, endDate);
        } else {
            dateFilterActive = false;
            currentStartDate = null;
            currentEndDate = null;
            fetchSalesData();
        }
    });

    // Render sales data
    function renderSalesData() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentData = filteredData.slice(startIndex, endIndex);
        
        const salesTable = document.getElementById('sales-data');
        
        if (currentData.length === 0) {
            salesTable.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-amber-800">No sales records found.</td>
                </tr>
            `;
            return;
        }
        
        salesTable.innerHTML = currentData.map(sale => `
            <tr class="hover:bg-amber-50/50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">#${sale.sales_id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">${formatDate(sale.created_at)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">${sale.items}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">${formatCurrency(sale.total_price)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">${sale.discount_type || 'None'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">
                    ${sale.items === 'past data' ? 
                        '<span class="text-amber-400">No details</span>' : 
                        `<button onclick="openReceiptModal(${sale.sales_id})" class="text-amber-600 hover:text-amber-800 mr-3">View</button>`
                    }
                </td>
            </tr>
        `).join('');
        
        // Update showing text
        const totalItems = filteredData.length;
        const startItem = startIndex + 1;
        const endItem = Math.min(endIndex, totalItems);
        document.getElementById('showing-text').textContent = 
            `Showing ${startItem} to ${endItem} of ${totalItems} transactions`;
    }

    // Render pagination buttons
    function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        const paginationContainer = document.getElementById('pagination-buttons');
        
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }
        
        let buttons = '';
        const maxVisiblePages = 5;
        let startPage, endPage;
        
        // Calculate the range of pages to show
        if (totalPages <= maxVisiblePages) {
            startPage = 1;
            endPage = totalPages;
        } else {
            // Show pages around current page
            const maxPagesBeforeCurrent = Math.floor(maxVisiblePages / 2);
            const maxPagesAfterCurrent = Math.ceil(maxVisiblePages / 2) - 1;
            
            if (currentPage <= maxPagesBeforeCurrent) {
                // Near the beginning
                startPage = 1;
                endPage = maxVisiblePages;
            } else if (currentPage + maxPagesAfterCurrent >= totalPages) {
                // Near the end
                startPage = totalPages - maxVisiblePages + 1;
                endPage = totalPages;
            } else {
                // Somewhere in the middle
                startPage = currentPage - maxPagesBeforeCurrent;
                endPage = currentPage + maxPagesAfterCurrent;
            }
        }
        
        // First page and Previous buttons
        buttons += `
            <button onclick="changePage(1)" 
                class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                ${currentPage === 1 ? 'disabled' : ''}>
                &lt;&lt;
            </button>
            <button onclick="changePage(${currentPage - 1})" 
                class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                ${currentPage === 1 ? 'disabled' : ''}>
                &lt;
            </button>
        `;
        
        // Page buttons - only show the calculated range
        for (let i = startPage; i <= endPage; i++) {
            buttons += `
                <button onclick="changePage(${i})" 
                    class="px-3 py-1 rounded-lg border border-amber-200 ${currentPage === i ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-white text-amber-700 hover:bg-amber-50'}">
                    ${i}
                </button>
            `;
        }
        
        // Next and Last page buttons
        buttons += `
            <button onclick="changePage(${currentPage + 1})" 
                class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" 
                ${currentPage === totalPages ? 'disabled' : ''}>
                &gt;
            </button>
            <button onclick="changePage(${totalPages})" 
                class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" 
                ${currentPage === totalPages ? 'disabled' : ''}>
                &gt;&gt;
            </button>
        `;
        
        paginationContainer.innerHTML = buttons;
    }

    // Change page function
    function changePage(newPage) {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        if (newPage < 1 || newPage > totalPages) return;
        
        currentPage = newPage;
        renderSalesData();
        renderPagination();
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date().toISOString().split('T')[0];
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('start-date').value = today;
        document.getElementById('end-date').value = today;
        document.getElementById('currentDate').textContent = formatDate(today);;

        fetchSalesData();
        
        // Set up periodic refresh every 10 seconds
        setInterval(fetchSalesData, 10000);
        
        // Add search functionality (you can add a search input to your HTML)
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Search transactions...';
        searchInput.className = 'border border-amber-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500';
        searchInput.style.marginRight = '10px';
        
        // Insert search input (you might want to position it differently)
        const headerDiv = document.querySelector('.flex.flex-col.md:flex-row.md:items-center.md:justify-between.mb-6');
        headerDiv.insertBefore(searchInput, headerDiv.children[1]);
        
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            
            if (searchTerm.trim() === '') {
                filteredData = [...allSalesData];
            } else {
                filteredData = allSalesData.filter(sale => 
                    sale.sales_id.toLowerCase().includes(searchTerm) ||
                    sale.discount_type?.toLowerCase().includes(searchTerm) ||
                    sale.items.toLowerCase().includes(searchTerm) ||
                    sale.total_price.toString().includes(searchTerm) ||
                    formatDate(sale.created_at).toLowerCase().includes(searchTerm)
                );
            }
            
            currentPage = 1;
            renderSalesData();
            renderPagination();
        });
    });

    // Export/Print functionality
    document.getElementById('export-button').addEventListener('click', () => {
        // Create a printable version of the table
        const printWindow = window.open('', '_blank');
        
        // Get the filtered data (or all data if no filter is applied)
        const dataToExport = filteredData.length > 0 ? filteredData : allSalesData;
        
        // Get the date range text
        let dateRangeText = '';
        if (dateFilterActive && currentStartDate && currentEndDate) {
            dateRangeText = `From ${formatDateForExport(currentStartDate)} to ${formatDateForExport(currentEndDate)}`;
        } else {
            dateRangeText = 'All dates';
        }
        
        // Format date for export (simpler format)
        function formatDateForExport(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }
        
        // Create the HTML content for printing with the header and footer from code 1
        let printContent = `
            <html>
                <head>
                    <title>Sales History Export</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .print-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #8B4513; padding-bottom: 15px; }
                        .print-header h1 { font-size: 28px; font-weight: bold; font-family: 'Playfair Display', serif; color: #8B4513; margin-bottom: 5px; }
                        .print-header h2 { font-size: 20px; color: #333; margin-top: 0; font-style: italic; }
                        .print-meta { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 12px; color: #666; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
                        th { background-color: #8B4513; color: white; }
                        tr:nth-child(even) { background-color: #fffbeb; }
                        .print-footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 14px; color: #555; }
                        .date-range { text-align: center; margin-bottom: 20px; color: #78350f; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>Caffè Lilio Ristorante</h1>
                        <h2>Authentic Italian Cuisine in Liliw, Laguna</h2>
                        <div style="margin-top: 10px;">
                            <span style="font-size: 14px; color: #666;">Sales History Report</span>
                        </div>
                    </div>
                    <div class="print-meta">
                        <div>Website: https://caffelilioristorante.com/</div>
                        <div>Generated on: ${new Date().toLocaleString()}</div>
                    </div>
                    <div class="date-range">${dateRangeText}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date & Time</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Discount Type</th>
                            </tr>
                        </thead>
                        <tbody>
        `;
        
        // Add the data rows
        dataToExport.forEach(sale => {
            printContent += `
                <tr>
                    <td>#${sale.sales_id}</td>
                    <td>${formatDate(sale.created_at)}</td>
                    <td>${sale.items}</td>
                    <td>${formatCurrency(sale.total_price)}</td>
                    <td>${sale.discount_type || 'None'}</td>
                </tr>
            `;
        });
        
        // Close the HTML with the footer from code 1
        printContent += `
                        </tbody>
                    </table>
                    <div class="print-footer">
                        <p>For more inquiries, please contact us:</p>
                        <p style="margin: 5px 0;">Email: caffelilio.liliw@gmail.com</p>
                        <p style="margin: 5px 0;">Facebook: Caffè Lilio Ristorante | Liliw</p>
                        <p style="margin-top: 15px; font-style: italic;">Grazie per aver scelto Caffè Lilio!</p>
                    </div>
                </body>
            </html>
        `;
        
        // Write the content to the new window and print
        printWindow.document.open();
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for the content to load before printing
        printWindow.onload = function() {
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        };
    });

    // Open modal function
    function openReceiptModal(sales_id) {
        // Show loading state
        
        document.getElementById('receiptItemsList').innerHTML = `
            <div class="text-center py-4">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading receipt...
            </div>
        `;
        
        // Show modal
        document.getElementById('receiptModal').classList.remove('hidden');
        
        // Fetch receipt data
        fetch('historyFunctions/fetch_reciept.php', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `sales_id=${sales_id}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
            // Update modal title
            
            
            // Set receipt header info
            document.getElementById('receiptId').textContent = data.data.sales_id;
            document.getElementById('receiptDate').textContent = formatDate(data.data.created_at);
            
            // Build items list and calculate subtotal
            let itemsHtml = '';
            let subtotal = 0;
            
            data.data.items.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                itemsHtml += `
                <div class="flex justify-between">
                    <span>${item.dish_name} (${item.quantity})</span>
                    <span>₱${itemTotal.toFixed(2)}</span>
                </div>
                `;
            });
            
            document.getElementById('receiptItemsList').innerHTML = itemsHtml;
            
            // Calculate service charge (10% of subtotal)
            const serviceCharge = subtotal * 0.10;
            const totalBeforeDiscount = subtotal + serviceCharge;
            
            // Add service charge row
            itemsHtml += `
                <div class="flex justify-between border-t mt-2 pt-2">
                    <span>Subtotal:</span>
                    <span>₱${subtotal.toFixed(2)}</span>
                </div>
                <div class="flex justify-between">
                    <span>Service Charge (10%):</span>
                    <span>₱${serviceCharge.toFixed(2)}</span>
                </div>
            `;
            
            document.getElementById('receiptItemsList').innerHTML = itemsHtml;
            
            // Set summary information
            document.getElementById('totalPrice').textContent = totalBeforeDiscount.toFixed(2);
            document.getElementById('amountPaid').textContent = data.data.amount_paid.toFixed(2);
            document.getElementById('amountChange').textContent = data.data.amount_change.toFixed(2);
            
            // Handle discount if exists
            const discountRow = document.getElementById('discountRow');
            if(data.data.discount_type && data.data.discount_price > 0) {
                discountRow.classList.remove('hidden');
                document.getElementById('discountType').textContent = "Discount: " + (data.data.discount_type || 'None');
                document.getElementById('discountAmount').textContent = data.data.discount_price.toFixed(2);
                
                // Update total price with discount
                const totalAfterDiscount = totalBeforeDiscount - data.data.discount_price;
                document.getElementById('totalPrice').textContent = totalAfterDiscount.toFixed(2);
            } else {
                discountRow.classList.add('hidden');
            }
            } else {
            alert('Error: ' + data.message);
            closeReceiptModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error fetching receipt');
            closeReceiptModal();
        });
    }

    // Close modal function
    function closeReceiptModal() {
    document.getElementById('receiptModal').classList.add('hidden');
    }

    

</script>

</body>
</html>