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
                <button id="sidebar-toggle" class="text-amber-900 hover:text-amber-800 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
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
                    <div class="relative">
                        <select class="appearance-none bg-amber-50 border border-amber-200 text-amber-900 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option>Today</option>
                            <option>Yesterday</option>
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Custom Range</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-amber-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
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
                    <tbody class="bg-white divide-y divide-amber-200">
                        <!-- Sample row 1 -->
                        <tr class="hover:bg-amber-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">#TRX-2023-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Oct 15, 2023 10:30 AM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">3 items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">$45.99</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Senior</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">
                                <button class="text-amber-600 hover:text-amber-800 mr-3">View</button>
                                <button class="text-amber-600 hover:text-amber-800">Print</button>
                            </td>
                        </tr>
                        
                        <!-- Sample row 2 -->
                        <tr class="hover:bg-amber-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">#TRX-2023-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Oct 15, 2023 11:45 AM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">5 items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">$72.50</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">PWD</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">
                                <button class="text-amber-600 hover:text-amber-800 mr-3">View</button>
                                <button class="text-amber-600 hover:text-amber-800">Print</button>
                            </td>
                        </tr>
                        
                        <!-- Sample row 3 -->
                        <tr class="hover:bg-amber-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">#TRX-2023-003</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Oct 15, 2023 2:15 PM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">2 items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">$28.75</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">None</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">
                                <button class="text-amber-600 hover:text-amber-800 mr-3">View</button>
                                <button class="text-amber-600 hover:text-amber-800">Print</button>
                            </td>
                        </tr>
                        
                        <!-- Sample row 4 -->
                        <tr class="hover:bg-amber-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">#TRX-2023-004</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Oct 14, 2023 5:30 PM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">7 items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">$112.20</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Senior</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">
                                <button class="text-amber-600 hover:text-amber-800 mr-3">View</button>
                                <button class="text-amber-600 hover:text-amber-800">Print</button>
                            </td>
                        </tr>
                        
                        <!-- Sample row 5 -->
                        <tr class="hover:bg-amber-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">#TRX-2023-005</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">Oct 14, 2023 3:10 PM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">1 item</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">$15.99</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">None</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-800">
                                <button class="text-amber-600 hover:text-amber-800 mr-3">View</button>
                                <button class="text-amber-600 hover:text-amber-800">Print</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-amber-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">24</span> transactions
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50 disabled:opacity-50" disabled>
                        Previous
                    </button>
                    <button class="px-3 py-1 rounded-lg border border-amber-200 bg-amber-600 text-white hover:bg-amber-700">
                        1
                    </button>
                    <button class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50">
                        2
                    </button>
                    <button class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50">
                        3
                    </button>
                    <button class="px-3 py-1 rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-50">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Set current date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const currentDate = new Date().toLocaleDateString('en-US', options);
        document.getElementById('current-date').textContent = currentDate;
        
        // Simple sidebar toggle functionality (would need actual sidebar element)
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            console.log('Sidebar toggle clicked');
            // In a real implementation, you would toggle a sidebar here
        });
    </script>
</body>
</html>