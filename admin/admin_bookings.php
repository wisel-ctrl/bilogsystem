<?php
    require_once '../db_connect.php';

    // Set the timezone to Philippine Time
    date_default_timezone_set('Asia/Manila');

    // Define page title
    $page_title = "Menu Management";

    // Capture page content
    ob_start();
?>




<style>
            #profileMenu {
            z-index: 49 !important;
            transform: translateY(0) !important;
        }
                        @media (max-width: 640px) {
                            table {
                                display: block;
                                overflow-x: auto;
                                white-space: nowrap;
                            }
                            thead {
                                display: none;
                            }
                            tbody tr {
                                display: block;
                                margin-bottom: 1rem;
                                border-bottom: 2px solid #e5e7eb;
                            }
                            tbody td {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                padding: 0.75rem 1rem;
                                text-align: left;
                            }
                            tbody td:before {
                                content: attr(data-label);
                                font-weight: 600;
                                color: #374151;
                                width: 40%;
                                min-width: 120px;
                            }
                        }
                    </style>

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
                                <!-- Sample booking data -->
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Maria Santos</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 915 123 4567</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Wedding Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 15, 2024 - 6:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openBookingDetails('BOOK001', 'Maria Santos', '+63 915 123 4567', 'Wedding Package', '₱25,000', 'Dec 15, 2024 - 6:00 PM', '50', 'Please prepare vegetarian options for 5 guests.')"
                                        class="flex items-center justify-center space-x-2 px-3 py-1.5 bg-accent-brown/10 text-accent-brown hover:bg-accent-brown/20 hover:scale-105 hover:text-deep-brown rounded-lg transition-all duration-200 ease-in-out">
                                        <i class="fas fa-eye text-sm"></i>
                                        <span>Details</span>
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
                                            class="flex items-center justify-center space-x-2 px-3 py-1.5 bg-accent-brown/10 text-accent-brown hover:bg-accent-brown/20 hover:scale-105 hover:text-deep-brown rounded-lg transition-all duration-200 ease-in-out">
                                            <i class="fas fa-eye text-sm"></i>
                                            <span>Details</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ana Rodriguez</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+63 922 456 7890</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Corporate Event Package</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dec 18, 2024 - 12:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button 
                                            onclick="openBookingDetails('BOOK003', 'Ana Rodriguez', '+63 922 456 7890', 'Corporate Event Package', '₱18,500', 'Dec 18, 2024 - 12:00 PM', '40', 'Company year-end party. Please prepare presentation setup and microphone.')"
                                            class="flex items-center justify-center space-x-2 px-3 py-1.5 bg-accent-brown/10 text-accent-brown hover:bg-accent-brown/20 hover:scale-105 hover:text-deep-brown rounded-lg transition-all duration-200 ease-in-out">
                                            <i class="fas fa-eye text-sm"></i>
                                            <span>Details</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Add pagination controls for Restaurant Bookings -->
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
                <div class="dashboard-card animate-on-scroll mt-8 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-deep-brown flex items-center">
                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                            Accepted Bookings
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white shadow-sm rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Phone</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Menu</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Date & Time</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="accepted-bookings-table">
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">John Smith</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">+63 912 345 6789</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Corporate Lunch Package</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Dec 10, 2024 - 12:00 PM</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button onclick="markAsDone('BOOK004')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors duration-150">
                                            <i class="fas fa-check-circle mr-1.5"></i> Mark as Done
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">Sarah Johnson</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">+63 918 765 4321</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Birthday Party Package</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Dec 8, 2024 - 3:00 PM</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button disabled class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                            <i class="fas fa-check-circle mr-1.5"></i> Completed
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        

                        <!-- Add pagination controls for Accepted Bookings -->
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

        <!-- MODAL SECTION -->
         <!-- Booking Details Modal -->
                <div id="booking-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <div class="modal-header p-6 border-b border-warm-cream/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Booking Details</h3>
                                <button onclick="closeBookingModal()" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body flex-1 overflow-y-auto p-6 space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Booking ID</label>
                                    <p id="modal-booking-id" class="text-lg font-semibold text-rich-brown font-baskerville bg-gray-50 p-2 rounded"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Client Name</label>
                                    <p id="modal-client-name" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Phone Number</label>
                                    <p id="modal-phone" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Menu Name</label>
                                    <p id="modal-menu" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Price</label>
                                    <p id="modal-price" class="text-lg font-semibold text-accent-brown font-baskerville"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Requested DateTime</label>
                                    <p id="modal-datetime" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Number of Pax</label>
                                <p id="modal-pax" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Client Notes</label>
                                <p id="modal-notes" class="text-lg font-semibold text-rich-brown font-baskerville bg-gray-50 p-3 rounded"></p>
                            </div>
                        </div>
                        <div class="modal-footer p-6 border-t border-warm-cream/20">
                            <div class="flex justify-end space-x-3">
                                <button onclick="acceptBooking()" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                    <i class="fas fa-check mr-2"></i>Accept
                                </button>
                                <button onclick="openDeclineModal()" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                                    <i class="fas fa-times mr-2"></i>Decline
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Decline Reason Modal -->
                <div id="decline-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <div class="modal-header p-6 border-b border-warm-cream/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Decline Booking</h3>
                                <button onclick="closeDeclineModal()" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body flex-1 overflow-y-auto p-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Reason for declining:</label>
                                    <textarea id="decline-reason" rows="4" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Please provide a reason for declining this booking..." oninput="validateDeclineReason(this)"></textarea>
                                    <p id="decline-reason-error" class="text-red-500 text-sm mt-1 hidden"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer p-6 border-t border-warm-cream/20">
                            <div class="flex justify-end space-x-3">
                                <button onclick="closeDeclineModal()" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                                    Cancel
                                </button>
                                <button onclick="confirmDecline()" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                    Confirm Decline
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Modal -->
                <div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-8">
                    <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-sm w-full flex flex-col">
                        <div class="modal-body p-6 text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                                <i class="fas fa-check text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-deep-brown font-playfair mb-2">Success!</h3>
                            <p id="success-message" class="text-lg text-rich-brown font-baskerville mb-6"></p>
                            <button onclick="closeSuccessModal()" class="w-full px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                                OK
                            </button>
                        </div>
                    </div>
                </div>



<?php
    $page_content = ob_get_clean();

    // Capture page-specific scripts
    ob_start();
?>

    <script>
        // Sidebar Toggle with smooth animation
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const cafeTitle = document.querySelector('.nav-title');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        // Profile Dropdown functionality
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        const dropdownIcon = profileDropdown.querySelector('.fa-chevron-down');

        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            setTimeout(() => {
                profileMenu.classList.toggle('opacity-0');
                dropdownIcon.classList.toggle('rotate-180');
            }, 50);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden', 'opacity-0');
                dropdownIcon.classList.remove('rotate-180');
            }
        });
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
        }

        // Update event listeners
        sidebarToggle.addEventListener('click', toggleSidebar);

        // Update sidebar link click handler
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                // Remove active class from all links
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                link.classList.add('active');
            });
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', () => {
            // Set initial active link based on current page
            const currentPath = window.location.pathname;
            const currentLink = document.querySelector(`.sidebar-link[href="${currentPath.split('/').pop()}"]`);
            if (currentLink) {
                currentLink.classList.add('active');
            }
        });

        // Set current date with improved formatting
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', options);

        // Scroll animation observer with improved timing
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animated');
                    }, index * 100); // Staggered animation
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(element => {
            observer.observe(element);
        });

        // Update modal functions with improved transitions
        function openBookingDetails(bookingId, clientName, phone, menu, price, datetime, pax, notes) {
            // Add blur to main content
            document.querySelector('.flex-1').classList.add('blur-effect');
            document.querySelector('#sidebar').classList.add('blur-effect');
            
            // Set modal fields
            document.getElementById('modal-booking-id').textContent = bookingId;
            document.getElementById('modal-client-name').textContent = clientName;
            document.getElementById('modal-phone').textContent = phone;
            document.getElementById('modal-menu').textContent = menu;
            document.getElementById('modal-price').textContent = price;
            document.getElementById('modal-datetime').textContent = datetime;
            document.getElementById('modal-pax').textContent = pax;
            document.getElementById('modal-notes').textContent = notes;
            
            // Show modal with fade effect
            const modal = document.getElementById('booking-modal');
            modal.classList.remove('hidden');
            modal.querySelector('.bg-white\\/95').classList.add('modal-content');
        }

        function openDeclineModal() {
            // Hide booking modal, show decline modal with transition
            const bookingModal = document.getElementById('booking-modal');
            const declineModal = document.getElementById('decline-modal');
            
            bookingModal.classList.add('hidden');
            declineModal.classList.remove('hidden');
            declineModal.querySelector('.bg-white\\/95').classList.add('modal-content');
        }

        function acceptBooking() {
            // Hide booking modal
            document.getElementById('booking-modal').classList.add('hidden');
            // Show success modal with message and animation
            const successModal = document.getElementById('success-modal');
            document.getElementById('success-message').textContent = 'Booking has been accepted!';
            successModal.classList.remove('hidden');
            setTimeout(() => {
                successModal.querySelector('.dashboard-card').style.opacity = '1';
                successModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
            }, 50);
        }

        function closeBookingModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            
            const modal = document.getElementById('booking-modal');
            modal.classList.add('hidden');
        }

        function closeDeclineModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            
            const modal = document.getElementById('decline-modal');
            modal.classList.add('hidden');
        }

        function closeSuccessModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            
            const modal = document.getElementById('success-modal');
            modal.classList.add('hidden');
        }

        function markAsDone(bookingId) {
            // Show success modal with message and animation
            const successModal = document.getElementById('success-modal');
            document.getElementById('success-message').textContent = 'Booking has been marked as completed!';
            successModal.classList.remove('hidden');
            setTimeout(() => {
                successModal.querySelector('.dashboard-card').style.opacity = '1';
                successModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
            }, 50);
            
            // Update the status in the table with transition
            const row = document.querySelector(`button[onclick="markAsDone('${bookingId}')"]`).closest('tr');
            const statusCell = row.querySelector('td:nth-child(5)');
            const actionCell = row.querySelector('td:nth-child(6)');
            
            statusCell.style.transition = 'opacity 0.3s ease';
            actionCell.style.transition = 'opacity 0.3s ease';
            statusCell.style.opacity = '0';
            actionCell.style.opacity = '0';
            
            setTimeout(() => {
                statusCell.innerHTML = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>';
                actionCell.innerHTML = '<button disabled class="text-gray-400 cursor-not-allowed"><i class="fas fa-check-circle mr-1"></i>Completed</button>';
                statusCell.style.opacity = '1';
                actionCell.style.opacity = '1';
            }, 300);
        }

        // Add this new function for decline reason validation
        function validateDeclineReason(textarea) {
            const errorElement = document.getElementById('decline-reason-error');
            let value = textarea.value;
            
            // Remove any numbers
            value = value.replace(/[0-9]/g, '');
            
            // Handle first character capitalization
            if (value.length === 1) {
                value = value.toUpperCase();
            }
            
            // Handle spaces
            if (value.length < 2 && value.includes(' ')) {
                value = value.replace(/\s/g, '');
                errorElement.textContent = 'Please enter at least 2 characters before using spaces';
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
            
            // Remove consecutive spaces
            value = value.replace(/\s+/g, ' ');
            
            // Update the textarea value if it changed
            if (value !== textarea.value) {
                textarea.value = value;
            }
        }

        // Modify the confirmDecline function to include validation
        function confirmDecline() {
            const reason = document.getElementById('decline-reason').value.trim();
            const errorElement = document.getElementById('decline-reason-error');
            
            if (reason.length < 10) {
                errorElement.textContent = 'Please provide a reason with at least 10 characters';
                errorElement.classList.remove('hidden');
                return;
            }
            
            // If validation passes, proceed with decline
            closeDeclineModal();
            const successModal = document.getElementById('success-modal');
            document.getElementById('success-message').textContent = 'Booking has been declined!';
            successModal.classList.remove('hidden');
            setTimeout(() => {
                successModal.querySelector('.dashboard-card').style.opacity = '1';
                successModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
            }, 50);
        }

        // Add pagination state management
        const paginationState = {
            restaurant: {
                currentPage: 1,
                itemsPerPage: 5,
                totalItems: 0,
                data: []
            },
            accepted: {
                currentPage: 1,
                itemsPerPage: 5,
                totalItems: 0,
                data: []
            }
        };

        // Function to initialize pagination
        function initializePagination() {
            // Get all rows from both tables
            const restaurantRows = Array.from(document.querySelectorAll('#bookings-table tr'));
            const acceptedRows = Array.from(document.querySelectorAll('#accepted-bookings-table tr'));

            // Store the data
            paginationState.restaurant.data = restaurantRows;
            paginationState.accepted.data = acceptedRows;
            paginationState.restaurant.totalItems = restaurantRows.length;
            paginationState.accepted.totalItems = acceptedRows.length;

            // Initialize both tables
            updateTableDisplay('restaurant');
            updateTableDisplay('accepted');
        }

        // Function to update table display
        function updateTableDisplay(tableType) {
            const state = paginationState[tableType];
            const tableBody = document.getElementById(tableType === 'restaurant' ? 'bookings-table' : 'accepted-bookings-table');
            const startIndex = (state.currentPage - 1) * state.itemsPerPage;
            const endIndex = Math.min(startIndex + state.itemsPerPage, state.totalItems);

            // Clear current table content
            tableBody.innerHTML = '';

            // Add visible rows
            for (let i = startIndex; i < endIndex; i++) {
                if (state.data[i]) {
                    tableBody.appendChild(state.data[i].cloneNode(true));
                }
            }

            // Update pagination info
            document.getElementById(`${tableType}-bookings-start`).textContent = state.totalItems === 0 ? 0 : startIndex + 1;
            document.getElementById(`${tableType}-bookings-end`).textContent = endIndex;
            document.getElementById(`${tableType}-bookings-total`).textContent = state.totalItems;

            // Update pagination buttons
            updatePaginationButtons(tableType);
            updatePaginationNumbers(tableType);
        }

        // Function to update pagination buttons
        function updatePaginationButtons(tableType) {
            const state = paginationState[tableType];
            const firstBtn = document.getElementById(`${tableType}-first-btn`);
            const prevBtn = document.getElementById(`${tableType}-prev-btn`);
            const nextBtn = document.getElementById(`${tableType}-next-btn`);
            const lastBtn = document.getElementById(`${tableType}-last-btn`);
            const totalPages = Math.ceil(state.totalItems / state.itemsPerPage);

            // Disable first and prev buttons on first page
            firstBtn.disabled = state.currentPage === 1;
            prevBtn.disabled = state.currentPage === 1;

            // Disable next and last buttons on last page
            nextBtn.disabled = state.currentPage >= totalPages;
            lastBtn.disabled = state.currentPage >= totalPages;
        }

        // Function to update pagination numbers
        function updatePaginationNumbers(tableType) {
            const state = paginationState[tableType];
            const container = document.getElementById(`${tableType}-pagination-numbers`);
            const totalPages = Math.ceil(state.totalItems / state.itemsPerPage);
            
            container.innerHTML = '';
            
            // Show max 5 page numbers
            let startPage = Math.max(1, state.currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.className = `px-3 py-1 rounded border text-sm ${
                    i === state.currentPage
                        ? 'bg-accent-brown text-white border-accent-brown'
                        : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                }`;
                button.textContent = i;
                button.onclick = () => changePage(tableType, i);
                container.appendChild(button);
            }
        }

        // Function to change page
        function changePage(tableType, action) {
            const state = paginationState[tableType];
            const totalPages = Math.ceil(state.totalItems / state.itemsPerPage);

            switch(action) {
                case 'first':
                    state.currentPage = 1;
                    break;
                case 'prev':
                    if (state.currentPage > 1) {
                        state.currentPage--;
                    }
                    break;
                case 'next':
                    if (state.currentPage < totalPages) {
                        state.currentPage++;
                    }
                    break;
                case 'last':
                    state.currentPage = totalPages;
                    break;
                default:
                    if (typeof action === 'number') {
                        state.currentPage = action;
                    }
            }

            updateTableDisplay(tableType);
        }

        // Initialize pagination when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            initializePagination();
            // ... existing DOMContentLoaded code ...
        });


        
    </script>
<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>