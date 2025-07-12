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
    /* Custom DataTables styles */
    .dataTables_wrapper .dataTables_filter {
        float: right !important;
        margin-bottom: 1rem !important;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        background-color: white;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        width: 220px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' /%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        padding-right: 2.5rem;
    }
    
    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: #d97706;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
    }
    
    .dataTables_wrapper .dataTables_length {
        float: left !important;
        margin-bottom: 1rem !important;
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 2rem 0.5rem 1rem;
        font-size: 0.875rem;
        background-color: white;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7' /%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }
    
    /* Filter menu styles */
    .filter-menu-container {
        position: relative;
        display: inline-block;
        margin-left: 1rem;
    }
    
    .filter-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .filter-menu-btn:hover {
        background-color: #f3f4f6;
        color: #4b5563;
    }
    
    .filter-menu-btn.active {
        background-color: #fef3c7;
        border-color: #f59e0b;
        color: #d97706;
    }
    
    .filter-menu-dropdown {
        position: absolute;
        right: 0;
        z-index: 10;
        margin-top: 0.5rem;
        width: 200px;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        background-color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
    }
    
    .filter-menu-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .filter-menu-dropdown-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
    }
    
    .filter-menu-dropdown-body {
        padding: 0.5rem 0;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .filter-menu-option {
        display: block;
        width: 100%;
        padding: 0.5rem 1rem;
        text-align: left;
        font-size: 0.875rem;
        color: #374151;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.1s ease;
    }
    
    .filter-menu-option:hover {
        background-color: #f3f4f6;
    }
    
    .filter-menu-option.active {
        background-color: #fef3c7;
        color: #d97706;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_filter {
            float: none !important;
            text-align: left !important;
            margin-top: 1rem;
        }
        
        .dataTables_wrapper .dataTables_length {
            float: none !important;
        }
        
        .filter-menu-container {
            margin-left: 0;
            margin-top: 1rem;
        }
    }
</style>

                <div class="dashboard-card animate-on-scroll bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-deep-brown flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-accent-brown"></i>
                            Restaurant Bookings
                        </h3>
                    </div>
                    <div class="overflow-x-auto p-4">
                        <table id="restaurant-bookings-table" class="w-full stripe hover" style="width:100%">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pax</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation DateTime</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
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
                    <div class="overflow-x-auto p-4">
                        <div class="flex justify-end mb-4">
                            <div class="w-full md:w-64">
                                <label for="accepted-bookings-search" class="sr-only">Search</label>
                                <input type="search" id="accepted-bookings-search" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Search bookings...">
                            </div>
                        </div>
                        
                        <table id="accepted-bookings-table" class="w-full border-collapse bg-white shadow-sm rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Phone</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Menu</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Pax</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Date & Time</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <!-- DataTables will populate this automatically -->
                            </tbody>
                        </table>
                        
                        <div class="flex flex-col md:flex-row justify-between items-center mt-4 text-sm text-gray-700">
                            <div id="accepted-bookings-info" class="mb-2 md:mb-0"></div>
                            <div id="accepted-bookings-pagination" class="inline-flex rounded-md shadow-sm"></div>
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
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Booking Age</label>
                                    <p id="modal-booking-age" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Client Name</label>
                                    <p id="modal-client-name" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Phone Number</label>
                                    <p id="modal-phone" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Selected Hall(s)</label>
                                    <p id="modal-halls" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Event Type</label>
                                    <p id="modal-event" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Menu Name</label>
                                    <p id="modal-menu" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Number of Pax</label>
                                    <p id="modal-pax" class="text-lg font-semibold text-rich-brown font-baskerville"></p>
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
                                <label class="block text-sm font-medium text-deep-brown mb-1 font-baskerville">Payment Receipt</label>
                                <div id="modal-receipt-container" class="mt-2 border-2 border-dashed border-gray-300 rounded-lg p-4 flex justify-center">
                                    <img id="modal-receipt-image" src="" alt="Payment Receipt" class="max-h-64 object-contain hidden">
                                    <p id="modal-no-receipt" class="text-gray-500 italic">No receipt uploaded</p>
                                </div>
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
                                    <label class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Common reasons:</label>
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <button onclick="selectDeclineReason('The selected dates are not available.')" class="px-4 py-2 border border-warm-cream/50 rounded-lg hover:bg-warm-cream/20 transition-colors duration-200 text-left font-baskerville text-rich-brown">
                                            Dates not available
                                        </button>
                                        <button onclick="selectDeclineReason('The property is undergoing maintenance during the requested period.')" class="px-4 py-2 border border-warm-cream/50 rounded-lg hover:bg-warm-cream/20 transition-colors duration-200 text-left font-baskerville text-rich-brown">
                                            Property maintenance
                                        </button>
                                        <button onclick="selectDeclineReason('The property is not suitable for the number of guests or purpose of stay.')" class="px-4 py-2 border border-warm-cream/50 rounded-lg hover:bg-warm-cream/20 transition-colors duration-200 text-left font-baskerville text-rich-brown">
                                            Not suitable for needs
                                        </button>
                                        <button onclick="selectDeclineReason('We are unable to accommodate the booking at this time.')" class="px-4 py-2 border border-warm-cream/50 rounded-lg hover:bg-warm-cream/20 transition-colors duration-200 text-left font-baskerville text-rich-brown">
                                            Unable to accommodate
                                        </button>
                                    </div>
                                    
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
        function openBookingDetails(bookingId) {
            // Add blur to main content
            document.querySelector('.flex-1').classList.add('blur-effect');
            document.querySelector('#sidebar').classList.add('blur-effect');
            
            // Show loading state
            const modal = document.getElementById('booking-modal');
            modal.dataset.bookingId = bookingId;
            modal.classList.remove('hidden');
            
            // Save the original modal content
            const originalModalContent = modal.querySelector('.modal-body').innerHTML;
            
            // Show loading spinner
            modal.querySelector('.modal-body').innerHTML = '<div class="flex justify-center items-center h-full"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-deep-brown"></div></div>';
            
            // Fetch booking details
            fetch(`booking_handlers/get_booking_details.php?booking_id=${bookingId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Booking Details: ', data);
                    if (data.success) {
                        const booking = data.data;
                        
                        // Restore original modal content
                        modal.querySelector('.modal-body').innerHTML = originalModalContent;
                        
                        // Populate modal fields
                        document.getElementById('modal-booking-id').textContent = booking.booking_id;
                        document.getElementById('modal-booking-age').textContent = booking.booking_age;
                        document.getElementById('modal-client-name').textContent = booking.customer_name;
                        document.getElementById('modal-phone').textContent = booking.contact_number;
                         // Format event_hall as bullet points
                        const hallsElement = document.getElementById('modal-halls');
                        if (Array.isArray(booking.event_hall)) {
                            // Create bullet list for array
                            hallsElement.innerHTML = booking.event_hall.map(hall => `- ${hall}`).join('<br>');
                        } else if (typeof booking.event_hall === 'string') {
                            // Handle case where it might already be a string
                            try {
                                // Try to parse if it's a JSON string
                                const hallsArray = JSON.parse(booking.event_hall);
                                hallsElement.innerHTML = hallsArray.map(hall => `- ${hall}`).join('<br>');
                            } catch (e) {
                                // If not JSON, display as is
                                hallsElement.textContent = booking.event_hall;
                            }
                        } else {
                            hallsElement.textContent = 'No hall specified';
                        }
                        document.getElementById('modal-event').textContent = booking.event;
                        document.getElementById('modal-menu').textContent = booking.package_name;
                        document.getElementById('modal-pax').textContent = booking.pax;
                        document.getElementById('modal-price').textContent = `₱${booking.totalPrice}`;
                        document.getElementById('modal-datetime').textContent = booking.reservation_datetime;
                        document.getElementById('modal-notes').textContent = booking.notes || 'No notes provided';
                        
                        // Handle payment receipt
                        const receiptImg = document.getElementById('modal-receipt-image');
                        const noReceipt = document.getElementById('modal-no-receipt');
                        
                        if (booking.downpayment_img) {
                            receiptImg.src = `../images/payment_proofs/${booking.downpayment_img}`;
                            receiptImg.classList.remove('hidden');
                            noReceipt.classList.add('hidden');
                        } else {
                            receiptImg.classList.add('hidden');
                            noReceipt.classList.remove('hidden');
                        }
                    } else {
                        // Show error message
                        modal.querySelector('.modal-body').innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-4"></i>
                                <h3 class="text-xl font-bold text-deep-brown mb-2">Error loading booking details</h3>
                                <p class="text-rich-brown">${data.message}</p>
                                <button onclick="closeBookingModal()" class="mt-4 px-4 py-2 bg-deep-brown text-warm-cream rounded hover:bg-rich-brown transition-colors">
                                    Close
                                </button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modal.querySelector('.modal-body').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-4"></i>
                            <h3 class="text-xl font-bold text-deep-brown mb-2">Network Error</h3>
                            <p class="text-rich-brown">Failed to fetch booking details. Please try again.</p>
                            <button onclick="closeBookingModal()" class="mt-4 px-4 py-2 bg-deep-brown text-warm-cream rounded hover:bg-rich-brown transition-colors">
                                Close
                            </button>
                        </div>
                    `;
                });
        }

        function openDeclineModal() {
            // Hide booking modal, show decline modal with transition
            const bookingModal = document.getElementById('booking-modal');
            const bookingId = bookingModal.dataset.bookingId;
            const declineModal = document.getElementById('decline-modal');

            declineModal.dataset.bookingId = bookingId;
            
            bookingModal.classList.add('hidden');
            declineModal.classList.remove('hidden');
            declineModal.querySelector('.bg-white\\/95').classList.add('modal-content');
        }

        function acceptBooking() {
            const modal = document.getElementById('booking-modal');
            const bookingId = modal.dataset.bookingId;

            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to accept this booking!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, accept it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Accepting booking:', bookingId);

                    // Send AJAX request to accept the booking
                    fetch('booking_handlers/accept_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `booking_id=${encodeURIComponent(bookingId)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide booking modal
                            modal.classList.add('hidden');
                            
                            // Show success message with SweetAlert
                            // Swal.fire(
                            //     'Accepted!',
                            //     data.message,
                            //     'success'
                            // );
                            
                            // Alternatively, you can keep your existing success modal if preferred
                            
                            const successModal = document.getElementById('success-modal');
                            document.getElementById('success-message').textContent = data.message;
                            successModal.classList.remove('hidden');
                            setTimeout(() => {
                                successModal.querySelector('.dashboard-card').style.opacity = '1';
                                successModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
                            }, 50);

                             $('#restaurant-bookings-table').DataTable().ajax.reload(null, false);
                            
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An error occurred while accepting the booking',
                            'error'
                        );
                    });
                }
            });
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

        function selectDeclineReason(reason) {
            const textarea = document.getElementById('decline-reason');
            textarea.value = reason;
            // Optional: Trigger validation if needed
            validateDeclineReason(textarea);
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
            const declineModal = document.getElementById('decline-modal');
            const bookingId = declineModal.dataset.bookingId;
            
            if (reason.length < 10) {
                errorElement.textContent = 'Please provide a reason with at least 10 characters';
                errorElement.classList.remove('hidden');
                return;
            }
            
            // If validation passes, send the decline request
            fetch('booking_handlers/decline_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `booking_id=${encodeURIComponent(bookingId)}&reason=${encodeURIComponent(reason)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeclineModal();
                    const successModal = document.getElementById('success-modal');
                    document.getElementById('success-message').textContent = 'Booking has been declined!';
                    successModal.classList.remove('hidden');
                    setTimeout(() => {
                        successModal.querySelector('.dashboard-card').style.opacity = '1';
                        successModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
                    }, 50);
                    
                    // Optional: Refresh the bookings list or update the UI
                     $('#restaurant-bookings-table').DataTable().ajax.reload(null, false);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while declining the booking');
            });
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

    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var restaurantTable = $('#restaurant-bookings-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'booking_handlers/fetch_booking.php',
                type: 'POST',
                data: {
                    status: 'pending'
                },
                error: function(xhr, error, thrown) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Error loading data. See console for details.');
                }
            },
            columns: [
                { data: 'customer_name', name: 'customer_name' },
                { data: 'contact_number', name: 'contact_number' },
                { data: 'package_name', name: 'package_name' },
                { data: 'pax', name: 'pax' },
                { 
                    data: 'reservation_datetime', 
                    name: 'reservation_datetime',
                    render: function(data, type, row) {
                        return formatDateTime(data);
                    }
                },
                { 
                    data: 'booking_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <button onclick="openBookingDetails('${row.booking_id}')" class="flex items-center justify-center space-x-2 px-3 py-1.5 bg-accent-brown/10 text-accent-brown hover:bg-accent-brown/20 hover:scale-105 hover:text-deep-brown rounded-lg transition-all duration-200 ease-in-out">
                                <i class="fas fa-eye text-sm"></i>
                                <span>Details</span>
                            </button>
                        `;
                    }
                }
            ],
            responsive: true,
            order: [[4, 'desc']],
            dom: '<"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4"<"flex items-center gap-4"l><"flex items-center gap-4"f>>rt<"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-4"ip>',
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search bookings...",
                lengthMenu: "Show _MENU_",
                info: "Showing _START_ to _END_ of _TOTAL_ bookings",
                infoEmpty: "No bookings available",
                infoFiltered: "(filtered from _MAX_ total bookings)",
                zeroRecords: "No matching bookings found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
        
        // Move the search label inside the input as placeholder
        $('.dataTables_filter label').contents().filter(function() {
            return this.nodeType === 3;
        }).remove();
    });

    // Helper function to format datetime
    function formatDateTime(datetimeString) {
        if (!datetimeString) return '';
        
        const date = new Date(datetimeString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Helper function to escape single quotes for JS
    function escapeSingleQuote(str) {
        return str.replace(/'/g, "\\'");
    }
    </script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#accepted-bookings-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'booking_handlers/accepted_bookings.php', // Your backend endpoint
            type: 'GET',
            data: function(d) {
                // You can add additional parameters if needed
                d.booking_status = 'accepted';
            }
        },
        columns: [
            { data: 'full_name', name: 'full_name' },
            { data: 'contact_number', name: 'contact_number' },
            { data: 'package_name', name: 'package_name' },
            { data: 'pax', name: 'pax' },
            { 
                data: 'reservation_datetime', 
                name: 'reservation_datetime',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        // Format the date for display
                        return formatDateTime(data);
                    }
                    return data;
                }
            },
            {
                data: 'booking_id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <button onclick="markAsDone('${data}')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors duration-150">
                            <i class="fas fa-check-circle mr-1.5"></i> Mark as Done
                        </button>
                    `;
                }
            }
        ],
        pageLength: 10,
        dom: '<"top">rt<"bottom"lip><"clear">',
        language: {
            info: "_START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)"
        }
    });
    
    // Connect the search box with debounce for better performance
    var searchTimeout;
    $('#accepted-bookings-search').on('keyup', function(){
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function(){
            table.search($(this).val()).draw();
        }.bind(this), 500);
    });
});
</script>
<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>