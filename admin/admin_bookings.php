<?php
$page_title = 'Booking Requests';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-cream': '#E8E0D5',
                        'rich-brown': '#8B4513',
                        'deep-brown': '#5D2F0F',
                        'accent-brown': '#A0522D'
                    },
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'baskerville': ['Libre Baskerville', 'serif']
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');

        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }

        /* Blur effect for modal background */
        .blur-effect {
            filter: blur(5px);
            transition: filter 0.3s ease;
            pointer-events: none;
        }

        /* Dashboard card styles */
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

        /* Animation for cards */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Delay classes for staggered animations */
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }

        /* Scrollbar styles */
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

        /* Modal styles */
        #booking-modal, #decline-modal, #success-modal {
            z-index: 1000 !important;
        }

        .modal-container {
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        .modal-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            background: white;
            z-index: 10;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }

        /* Modal animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .modal-backdrop {
            transition: opacity 0.3s ease;
        }

        .modal-backdrop.hidden {
            opacity: 0;
        }
    </style>
</head>
<body class="bg-warm-cream/50 font-baskerville">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <?php include '../includes/header.php'; ?>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <!-- Restaurant Bookings Section -->
                <div class="dashboard-card animate-on-scroll rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-deep-brown flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-accent-brown"></i>
                            Restaurant Bookings
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested DateTime</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="bookings-table">
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Maria Santos</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 915 123 4567</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Wedding Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 15, 2024 - 6:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openBookingDetails('BOOK001', 'Maria Santos', '+63 915 123 4567', 'Wedding Package', '₱25,000', 'Dec 15, 2024 - 6:00 PM', '50', 'Please prepare vegetarian options for 5 guests.')" 
                                                class="text-accent-brown hover:text-deep-brown transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>View Details
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Juan Dela Cruz</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 917 987 6543</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Birthday Party Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 20, 2024 - 2:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openBookingDetails('BOOK002', 'Juan Dela Cruz', '+63 917 987 6543', 'Birthday Party Package', '₱15,000', 'Dec 20, 2024 - 2:00 PM', '30', 'Need sound system for karaoke. Birthday celebrant is turning 50.')" 
                                                class="text-accent-brown hover:text-deep-brown transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>View Details
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ana Rodriguez</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 922 456 7890</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Corporate Event Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 18, 2024 - 12:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openBookingDetails('BOOK003', 'Ana Rodriguez', '+63 922 456 7890', 'Corporate Event Package', '₱18,500', 'Dec 18, 2024 - 12:00 PM', '40', 'Company year-end party. Please prepare presentation setup and microphone.')" 
                                                class="text-accent-brown hover:text-deep-brown transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>View Details
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing <span id="restaurant-bookings-start">1</span> to <span id="restaurant-bookings-end">5</span> of <span id="restaurant-bookings-total">0</span> entries
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="changePage('restaurant', 'first')" id="restaurant-first-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &lt;&lt;
                                </button>
                                <button onclick="changePage('restaurant', 'prev')" id="restaurant-prev-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &lt;
                                </button>
                                <div id="restaurant-pagination-numbers" class="flex space-x-1">
                                    <!-- Pagination numbers will be inserted here -->
                                </div>
                                <button onclick="changePage('restaurant', 'next')" id="restaurant-next-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &gt;
                                </button>
                                <button onclick="changePage('restaurant', 'last')" id="restaurant-last-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &gt;&gt;
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accepted Bookings Section -->
                <div class="dashboard-card animate-on-scroll mt-8 rounded-lg shadow-sm delay-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-deep-brown flex items-center">
                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                            Accepted Bookings
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled DateTime</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="accepted-bookings-table">
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">John Smith</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 912 345 6789</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Corporate Lunch Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 10, 2024 - 12:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="markAsDone('BOOK004')" class="text-green-600 hover:text-green-800 transition-colors duration-200">
                                            <i class="fas fa-check-circle mr-1"></i>Mark as Done
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Sarah Johnson</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 918 765 4321</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Birthday Party Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 8, 2024 - 3:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button disabled class="text-gray-400 cursor-not-allowed">
                                            <i class="fas fa-check-circle mr-1"></i>Completed
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing <span id="accepted-bookings-start">1</span> to <span id="accepted-bookings-end">5</span> of <span id="accepted-bookings-total">0</span> entries
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="changePage('accepted', 'first')" id="accepted-first-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &lt;&lt;
                                </button>
                                <button onclick="changePage('accepted', 'prev')" id="accepted-prev-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &lt;
                                </button>
                                <div id="accepted-pagination-numbers" class="flex space-x-1">
                                    <!-- Pagination numbers will be inserted here -->
                                </div>
                                <button onclick="changePage('accepted', 'next')" id="accepted-next-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &gt;
                                </button>
                                <button onclick="changePage('accepted', 'last')" id="accepted-last-btn" class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    &gt;&gt;
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- MODAL SECTION -->
        <!-- Booking Details Modal -->
        <div id="booking-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm modal-backdrop z-[1000] hidden flex items-center justify-center p-8">
            <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full modal-container modal-content">
                <div class="modal-header p-6 border-b border-warm-cream/20">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Booking Details</h3>
                        <button onclick="closeBookingModal()" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Booking ID</label>
                            <p id="modal-booking-id" class="text-lg font-semibold text-rich-brown