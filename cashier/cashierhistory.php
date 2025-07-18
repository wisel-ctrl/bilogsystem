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
                    
                    <button class="bg-amber-600 hover:bg-amber-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
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
        return '$' + parseFloat(amount).toFixed(2);
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
                        `<button class="text-amber-600 hover:text-amber-800 mr-3">View</button>`
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
        document.getElementById('start-date').value = today;
        document.getElementById('end-date').value = today;

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
</script>
</body>
</html>