<?php
require_once 'customer_auth.php';


require_once '../db_connect.php';

// Fetch all active GCash QR codes
$stmt = $conn->prepare("SELECT qr_image, gcash_number FROM gcash_qr WHERE is_active = 1");
$stmt->execute();
$gcashQRs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set page title
$page_title = "Bookings - Caffè Lilio";

// Capture content
ob_start();
?>




<style>
    /* Add to your CSS */
.availability-checking {
    position: relative;
}
.availability-checking::after {
    content: '';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid rgba(0,0,0,0.1);
    border-radius: 50%;
    border-top-color: #5F4B32;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}

#qrCodeModal {
    transition: opacity 0.3s ease;
}
</style>



    <!-- Main Content -->
        <!-- Menu Section -->
        <section class="mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <h2 class="font-playfair text-3xl sm:text-4xl font-bold text-deep-brown">Our Packages</h2>
                <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 flex items-center justify-center sm:justify-start space-x-2 p-2 rounded-lg hover:bg-deep-brown/5"
                        data-tippy-content="Refresh menu"
                        id="refresh-btn">
                    <i class="fas fa-sync-alt"></i>
                    <span class="font-baskerville text-sm">Refresh</span>
                </button>
            </div>
            
            <div class="rounded-xl p-4 sm:p-6 shadow-md">
                <!-- Loading State -->
                <div id="menu-loading" class="menu-grid">
                    <div class="loading-card skeleton">
                        <div class="loading-header skeleton"></div>
                        <div class="loading-text w-3/4 skeleton"></div>
                        <div class="loading-text w-1/2 skeleton"></div>
                        <div class="loading-text w-2/3 skeleton"></div>
                        <div class="loading-button skeleton"></div>
                    </div>
                    <div class="loading-card skeleton">
                        <div class="loading-header skeleton"></div>
                        <div class="loading-text w-4/5 skeleton"></div>
                        <div class="loading-text w-3/5 skeleton"></div>
                        <div class="loading-text w-3/4 skeleton"></div>
                        <div class="loading-button skeleton"></div>
                    </div>
                    <div class="loading-card skeleton">
                        <div class="loading-header skeleton"></div>
                        <div class="loading-text w-2/3 skeleton"></div>
                        <div class="loading-text w-4/5 skeleton"></div>
                        <div class="loading-text w-1/2 skeleton"></div>
                        <div class="loading-button skeleton"></div>
                    </div>
                </div>

                <!-- Menu Container -->
                <div id="menu-container" class="hidden menu-grid"></div>

                <!-- Empty State -->
                <div id="menu-empty" class="hidden text-center py-12">
                    <i class="fas fa-utensils text-6xl text-deep-brown/30 mb-4"></i>
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">No Menu Items Available</h3>
                    <p class="font-baskerville text-deep-brown/70 mb-4">Check back later for our delicious offerings.</p>
                    <button class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300"
                            onclick="loadMenu()">
                        Try Again
                    </button>
                </div>
            </div>

            <!-- Fullscreen QR Code Modal -->
            <div id="qrCodeModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black bg-opacity-75">
                <div class="relative bg-white p-4 rounded-lg max-w-[90vw] max-h-[90vh] flex flex-col items-center">
                    <button onclick="closeQRModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                    <img id="fullscreenQR" src="" alt="Fullscreen QR Code" class="max-w-full max-h-[80vh] object-contain">
                    <p class="mt-2 text-center text-gray-700" id="qrCodeNumber"></p>
                </div>
            </div>
        </section>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            tippy('[data-tippy-content]', {
                theme: 'custom',
                animation: 'scale',
                duration: [200, 150],
                placement: 'bottom'
            });

            // Initialize loading bar
            NProgress.configure({ 
                showSpinner: false,
                minimum: 0.3,
                easing: 'ease',
                speed: 500
            });

            // Mobile menu functionality
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            function toggleMobileMenu() {
                mobileMenu.classList.toggle('open');
                document.body.classList.toggle('overflow-hidden');
            }

            mobileMenuButton.addEventListener('click', toggleMobileMenu);
            closeMobileMenu.addEventListener('click', toggleMobileMenu);

            // Close mobile menu when clicking outside
            mobileMenu.addEventListener('click', function(e) {
                if (e.target === mobileMenu) {
                    toggleMobileMenu();
                }
            });

            // Show loading bar on navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    NProgress.start();

                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }

                    // Simulate loading time
                    setTimeout(() => {
                        NProgress.done();
                    }, 500);
                });
            });

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                        <span class="font-baskerville">${message}</span>
                    </div>
                `;
                document.getElementById('toast-container').appendChild(toast);
                
                // Show toast
                setTimeout(() => toast.classList.add('show'), 100);
                
                // Remove toast
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Global function to show toast (accessible from other functions)
            window.showToast = showToast;

            // Fetch and display menu packages
            function loadMenu() {
                    const menuContainer = document.getElementById('menu-container');
                    const loadingContainer = document.getElementById('menu-loading');
                    const emptyContainer = document.getElementById('menu-empty');

                    // Show loading state
                    loadingContainer.classList.remove('hidden');
                    menuContainer.classList.add('hidden');
                    emptyContainer.classList.add('hidden');

                    fetch('customerindex/get_menu_packages.php')
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success' && data.data && data.data.length > 0) {
                                loadingContainer.classList.add('hidden');
                                menuContainer.classList.remove('hidden');
                                menuContainer.innerHTML = '';
                                
                                data.data.forEach(package => {
                                    const menuItem = document.createElement('div');
                                    menuItem.className = 'bg-card rounded-xl shadow-md hover-lift group relative overflow-hidden';
                                    
                                    // Set image path (adjust base path if needed)
                                    const imagePath = package.image_path ? `/Uploads/${package.image_path}` : '../images/default_offer.jpg';
                                    
                                    menuItem.innerHTML = `
                                        <div class="relative">
                                            <img src="${imagePath}" alt="${package.package_name || 'Menu Item'}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                                            <div class="package-badge absolute top-4 right-4 bg-deep-brown text-white px-3 py-1 rounded-full text-sm font-bold">
                                                ${package.type || 'Package'}
                                            </div>
                                        </div>
                                        <div class="menu-card-content p-6">
                                            <h4 class="font-playfair text-xl font-bold mb-3 text-deep-brown">
                                                ${package.package_name || 'Menu Item'}
                                            </h4>
                                            <p class="font-baskerville text-deep-brown/80 menu-card-description mb-4">
                                                ${package.package_description || 'Delicious menu item description.'}
                                            </p>
                                            <div class="menu-card-footer">
                                                <div class="flex items-center justify-between mb-4">
                                                    <span class="price-display font-baskerville">
                                                        ₱${parseFloat(package.price || 0).toFixed(2)} <span class="text-sm">per pax</span>
                                                    </span>
                                                    <div class="text-sm text-deep-brown/60 font-baskerville">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Available
                                                    </div>
                                                </div>
                                              <a href="javascript:void(0)" 
                                                class="reserve-btn bg-rich-brown text-warm-cream px-6 py-3 rounded-full font-baskerville text-base font-semibold flex items-center gap-3 hover:bg-deep-brown shadow-md hover:shadow-xl transition-all duration-300 group hover:scale-105 focus:ring-2 focus:ring-deep-brown focus:outline-none"
                                                onclick="showPackageDetails(${package.package_id})"
                                                aria-label="Reserve ${package.package_name}">
                                                <span class="tracking-wide">Reserve Now</span>
                                                <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1 group-hover:scale-110"></i>
                                            </a>

                                            </div>
                                        </div>
                                    `;
                                    menuContainer.appendChild(menuItem);
                                });
                            } else {
                                loadingContainer.classList.add('hidden');
                                emptyContainer.classList.remove('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching menu data:', error);
                            loadingContainer.classList.add('hidden');
                            emptyContainer.classList.remove('hidden');
                            showToast('Error loading menu. Please try again.', 'error');
                        })
                        .finally(() => {
                            NProgress.done();
                        });
                }
            function showPackageDetails(packageId) {
                // Fetch package details
                fetch('bookingAPI/get_package_dishes.php?package_id=' + packageId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.data && data.data.length > 0) {
                            const package = data.data[0];
                            
                            // Group dishes by category in the order we want
                            // const categoriesOrder = ['house-salad', 'spanish-dish', 'italian-dish', 'burgers', 'pizza', 'Pasta', 'pasta_caza', 'main-course', 'drinks', 'coffee', 'desserts'];
                            const categoriesOrder = ['house_salad', 'spanish_dish', 'italian_dish', 'burger_pizza', 'pasta', 'pasta_caza', 'main_course', 'drinks', 'coffee', 'desserts'];
                            const dishesByCategory = {};
                            
                            // Initialize categories
                            categoriesOrder.forEach(category => {
                                dishesByCategory[category] = [];
                            });
                            
                            // Group dishes
                            data.data.forEach(dish => {
                                if (!dishesByCategory[dish.dish_category]) {
                                    dishesByCategory[dish.dish_category] = [];
                                }
                                dishesByCategory[dish.dish_category].push(dish);
                            });
                            
                            // Create modal content
                            let modalContent = `
                                <div class="package-details-modal">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-2xl font-playfair font-bold text-rich-brown">${package.package_name}</h3>
                                            <div class="flex items-center mt-1 text-amber-600">
                                                <i class="fas fa-tag mr-2"></i>
                                                <span class="font-semibold">₱ ${package.price}</span>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    
                                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-amber-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-amber-700">${package.package_description}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="dishes-list">
                            `;
                            
                            // Add dishes by category
                            categoriesOrder.forEach(category => {
                                if (dishesByCategory[category] && dishesByCategory[category].length > 0) {
                                    modalContent += `
                                        <div class="dish-category mb-6">
                                            <h4 class="text-xl font-playfair font-semibold mb-3 capitalize text-rich-brown flex items-center">
                                                <i class="fas ${getCategoryIcon(category)} mr-2"></i>
                                                ${category.replace(/-/g, ' ')}
                                            </h4>
                                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    `;
                                    
                                    dishesByCategory[category].forEach(dish => {
                                        modalContent += `
                                            <li class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg">
                                                <span class="font-medium text-gray-800">${dish.dish_name}</span>
                                                <span class="text-sm bg-amber-100 text-amber-800 px-2 py-1 rounded-full">x${dish.quantity}</span>
                                            </li>
                                        `;
                                    });
                                    
                                    modalContent += `
                                            </ul>
                                        </div>
                                    `;
                                }
                            });
                            
                            modalContent += `
                                    </div>
                                    
                                    <div class="modal-actions flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                                        <button onclick="closeModal()" class="btn-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition flex items-center">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancel
                                        </button>
                                        <button onclick="showReservationForm('${packageId}', ${package.price})" class="btn-reserve px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition flex items-center">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Reserve Now
                                        </button>
                                    </div>
                                </div>
                            `;
                            
                            // Show modal
                            showModal('Package Details', modalContent);
                        } else {
                            showToast('Error loading package details.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching package details:', error);
                        showToast('Error loading package details.', 'error');
                    });
            }

            window.showPackageDetails = showPackageDetails; 

            function getCategoryIcon(category) {
                const icons = {
                    'house_salad': 'fa-leaf',
                    'spanish_dish': 'fa-pepper-hot',
                    'italian_dish': 'fa-pizza-slice',
                    'burger_pizza': 'fa-hamburger',
                    // 'pizza': 'fa-pizza-slice',
                    'pasta': 'fa-utensils',
                    'pasta_caza': 'fa-utensils',
                    'main_course': 'fa-drumstick-bite',
                    'drinks': 'fa-glass-martini-alt',
                    'coffee': 'fa-coffee',
                    'desserts': 'fa-ice-cream'
                };
                return icons[category] || 'fa-utensils';
            }

            window.getCategoryIcon = getCategoryIcon;

            // Global function to calculate and display total amount
            window.calculateTotal = function() {
                const packagePrice = parseFloat(document.querySelector('input[name="package_price"]').value);
                const numberOfPax = parseInt(document.getElementById('numberOfPax').value) || 0;
                const totalAmount = packagePrice * numberOfPax;
                const downpaymentAmount = totalAmount * 0.5; // 50% downpayment
                
                document.getElementById('totalAmountDisplay').textContent = totalAmount.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                document.getElementById('downpaymentAmountDisplay').textContent = downpaymentAmount.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function showReservationForm(packageId, packagePrice) {
                let isTimeSlotAvailable = false;

                const formHtml = `
                    <div class="reservation-form">
                        <form id="reservationForm" enctype="multipart/form-data">
                            <input type="hidden" name="package_price" value="${packagePrice}">
                            
                            <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div class="flex items-center text-blue-800 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span class="font-medium">Package Price</span>
                                </div>
                                <div class="text-2xl font-bold text-rich-brown">₱${packagePrice}</div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="reservationDate" class="block text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-rich-brown"></i>
                                        Reservation Date
                                    </label>
                                    <input type="datetime-local" id="reservationDate" name="reservationDate" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-rich-brown focus:border-rich-brown" required>
                                    <p id="dateError" class="text-red-500 text-sm mt-1 hidden">Please select a future date and time.</p>
                                </div>
                                
                                <div>
                                    <label for="numberOfPax" class="block text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-users mr-2 text-rich-brown"></i>
                                        Number of Pax
                                    </label>
                                    <input type="number" id="numberOfPax" name="numberOfPax" min="1" max="95"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-rich-brown focus:border-rich-brown" required
                                        oninput="handlePaxChange()">
                                    <p id="paxError" class="text-red-500 text-sm mt-1 hidden">Please enter a number between 1 and 95.</p>
                                    <p id="paxMaxInfo" class="text-gray-500 text-sm mt-1">Maximum capacity is 95 pax (50 in Main Hall, 15 in Private Hall, 30 in Al Fresco)</p>
                                </div>
                            </div>
                            
                            <!-- Hall Venue Selection -->
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-building mr-2 text-rich-brown"></i>
                                    Hall Venue
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="privateHall" name="hallVenue[]" value="Private Hall" 
                                            class="mr-2 h-5 w-5 text-rich-brown focus:ring-rich-brown border-gray-300 rounded"
                                            onchange="handleHallSelection()">
                                        <label for="privateHall" class="text-gray-700">Private Hall (max 15 pax)</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="mainHall" name="hallVenue[]" value="Main Hall" 
                                            class="mr-2 h-5 w-5 text-rich-brown focus:ring-rich-brown border-gray-300 rounded"
                                            onchange="handleHallSelection()">
                                        <label for="mainHall" class="text-gray-700">Main Hall (max 50 pax)</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="alFresco" name="hallVenue[]" value="Al Fresco" 
                                            class="mr-2 h-5 w-5 text-rich-brown focus:ring-rich-brown border-gray-300 rounded"
                                            onchange="handleHallSelection()">
                                        <label for="alFresco" class="text-gray-700">Al Fresco (max 30 pax)</label>
                                    </div>
                                </div>
                                <p id="hallError" class="text-red-500 text-sm mt-1 hidden">Please select hall(s) that can accommodate your party size.</p>
                            </div>
                            
                            <!-- Total Amount and Downpayment Section -->
                            <div class="mb-6 bg-green-50 p-4 rounded-lg border border-green-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-gray-700 mb-1">Total Amount:</div>
                                        <div id="totalAmountDisplay" class="text-2xl font-bold text-green-700">$${packagePrice}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-700 mb-1">Downpayment Required (50%):</div>
                                        <div id="downpaymentAmountDisplay" class="text-2xl font-bold text-green-700">$${(packagePrice * 0.5).toFixed(2)}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- GCash Payment Section -->
                            <div class="mb-6 bg-purple-50 p-4 rounded-lg border border-purple-100">
                                <div class="flex items-center text-purple-800 mb-3">
                                    <i class="fas fa-mobile-alt mr-2"></i>
                                    <span class="font-medium">GCash Payment</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-700 mb-3">Please scan a QR code to pay the downpayment amount.</p>
                                        <div class="text-sm text-gray-600 mb-3">
                                            <p class="font-medium">Instructions:</p>
                                            <ol class="list-decimal list-inside space-y-1">
                                                <li>Open GCash app</li>
                                                <li>Tap "Scan QR"</li>
                                                <li>Scan one of the QR codes below</li>
                                                <li>Enter the downpayment amount</li>
                                                <li>Complete the payment</li>
                                            </ol>
                                        </div>
                                        <?php if (count($gcashQRs) > 1): ?>
                                            <button type="button" onclick="toggleQRCodes()" 
                                                class="mt-3 px-4 py-2 bg-purple-100 text-purple-800 rounded-lg hover:bg-purple-200 transition flex items-center">
                                                <i class="fas fa-qrcode mr-2"></i>
                                                Show All QR Codes
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex justify-center">
                                        <div id="qrCodeContainer" class="border-2 border-purple-200 p-2 rounded-lg bg-white">
                                            <?php if (!empty($gcashQRs)): ?>
                                                <!-- Display first QR code by default -->
                                                <img src="../images/gcash_qr/<?php echo htmlspecialchars($gcashQRs[0]['qr_image']); ?>" 
                                                     alt="GCash QR Code" 
                                                     class="w-48 h-48 object-contain active-qr cursor-pointer">
                                                <p class="text-center text-sm text-gray-600 mt-2">
                                                    GCash Number: <?php echo htmlspecialchars($gcashQRs[0]['gcash_number']); ?>
                                                </p>
                                                <!-- Hidden QR codes -->
                                                <div id="additionalQRCodes" class="hidden mt-4 space-y-4">
                                                    <?php for ($i = 1; $i < count($gcashQRs); $i++): ?>
                                                        <div>
                                                            <img src="../images/gcash_qr/<?php echo htmlspecialchars($gcashQRs[$i]['qr_image']); ?>" 
                                                                 alt="GCash QR Code" 
                                                                 class="w-48 h-48 object-contain cursor-pointer">
                                                            <p class="text-center text-sm text-gray-600 mt-2">
                                                                GCash Number: <?php echo htmlspecialchars($gcashQRs[$i]['gcash_number']); ?>
                                                            </p>
                                                        </div>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-center text-red-500">No active GCash QR codes available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label for="eventType" class="block text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-calendar-check mr-2 text-rich-brown"></i>
                                    Event Type
                                </label>
                                <div class="relative">
                                    <input type="text" id="eventType" name="eventType" list="eventSuggestions"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-rich-brown focus:border-rich-brown" 
                                        placeholder="e.g. Birthday, Anniversary, etc.">
                                    <datalist id="eventSuggestions">
                                        <option value="Birthday">
                                        <option value="Anniversary">
                                        <option value="Wedding">
                                        <option value="Family Gathering">
                                        <option value="Business Meeting">
                                        <option value="Date Night">
                                        <option value="Casual Dining">
                                    </datalist>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-edit mr-2 text-rich-brown"></i>
                                    Additional Notes
                                </label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-rich-brown focus:border-rich-brown" 
                                    placeholder="Any special requests or additional information..."></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label for="paymentProof" class="block text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-receipt mr-2 text-rich-brown"></i>
                                    Downpayment Proof (50% of package price)
                                </label>
                                
                                <div class="flex flex-col md:flex-row gap-4">
                                    <!-- Normal file input -->
                                    <div class="w-full">
                                        <input type="file" id="paymentProof" name="paymentProof" 
                                            accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-rich-brown focus:border-rich-brown" required>
                                    </div>
                                    
                                    <!-- Image preview container -->
                                    <div id="imagePreviewContainer" class="w-full hidden">
                                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-2 h-32">
                                            <img id="imagePreview" class="w-full h-full object-contain" src="" alt="Payment proof preview">
                                            <button type="button" onclick="clearImagePreview()" 
                                                class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full p-1 hover:bg-opacity-70 transition">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-actions flex justify-end gap-3 pt-4 border-t border-gray-200">
                                <button type="button" onclick="closeModal()" class="btn-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition flex items-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel
                                </button>
                                <button type="submit" class="btn-reserve px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Confirm Reservation
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                
                // Show modal
                showModal('Complete Reservation', formHtml);
                
                // Add form submission handler
                document.getElementById('reservationForm')?.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    // Double-check availability before submission
                    const reservationDate = document.getElementById('reservationDate').value;
                    if (!reservationDate || !isTimeSlotAvailable) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Unavailable Reservation Date',
                            text: 'Kindly select a different date and time for your reservation. Thank you!',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#D69E2E' // Optional: Gold theme to match your palette
                        });
                        return;
                    }

                    
                    // Validate inputs before submission
                    if (validateReservationForm()) {
                        submitReservation(packageId);
                    }
                });
                
                // Handle image preview when file is selected
                document.getElementById('paymentProof')?.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const previewContainer = document.getElementById('imagePreviewContainer');
                    const previewImage = document.getElementById('imagePreview');
                    
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            previewImage.src = event.target.result;
                            previewContainer.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.classList.add('hidden');
                    }
                });
                
                const reservationDateInput = document.getElementById('reservationDate');
                if (reservationDateInput) {
                    // Set minimum date/time to current moment
                    const now = new Date();
                    // Adjust for timezone offset
                    const timezoneOffset = now.getTimezoneOffset() * 60000;
                    const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
                    reservationDateInput.min = localISOTime;
                    
                    // Add event listener to validate time when changed
                    reservationDateInput.addEventListener('change', async function() {
                        if (!this.value) return;
                        
                        const dateError = document.getElementById('dateError');
                        const submitButton = document.querySelector('.btn-reserve');
                        
                        // Disable submit button while checking
                        submitButton.disabled = true;
                        
                        // Add loading indicator
                        this.classList.add('availability-checking');
                        
                        if (!validateReservationTime(this.value)) {
                            dateError.textContent = 'Reservations are only available between 10 AM and 10 PM.';
                            dateError.classList.remove('hidden');
                            this.classList.add('border-red-500');
                            this.classList.remove('availability-checking');
                            isTimeSlotAvailable = false;
                            submitButton.disabled = true;
                            return;
                        }
                        
                        try {
                            const availability = await checkAvailability(this.value);
                            if (!availability.available) {
                                dateError.textContent = availability.message || 'This time slot is fully booked. Please choose another time.';
                                dateError.classList.remove('hidden');
                                this.classList.add('border-red-500');
                                isTimeSlotAvailable = false;
                                submitButton.disabled = true;
                            } else {
                                dateError.classList.add('hidden');
                                this.classList.remove('border-red-500');
                                isTimeSlotAvailable = true;
                                submitButton.disabled = false;
                            }
                        } catch (error) {
                            dateError.textContent = 'Error checking availability. Please try again.';
                            dateError.classList.remove('hidden');
                            this.classList.add('border-red-500');
                            isTimeSlotAvailable = false;
                            submitButton.disabled = true;
                        } finally {
                            this.classList.remove('availability-checking');
                        }
                    });
                }
                
                // Initialize the total amount display
                calculateTotal();
                setupQRCodeClickHandlers();
                closeModal();
            }

            window.handlePaxChange = function() {
                const paxInput = document.getElementById('numberOfPax');
                const paxError = document.getElementById('paxError');
                const privateHall = document.getElementById('privateHall');
                const mainHall = document.getElementById('mainHall');
                const alFresco = document.getElementById('alFresco');
                const hallError = document.getElementById('hallError');
                
                // Validate pax input
                const paxValue = parseInt(paxInput.value);
                if (paxValue < 1 || paxValue > 95) {
                    paxError.classList.remove('hidden');
                } else {
                    paxError.classList.add('hidden');
                }
                
                // Automatically suggest halls based on pax
                if (paxValue > 15) {
                    mainHall.checked = true;
                    if (paxValue > 50) {
                        alFresco.checked = true;
                        if (paxValue > 80) {
                            privateHall.checked = true;
                        }
                    }
                    showToast('Suggested halls selected based on party size', 'info');
                }
                
                // Validate if selected halls can accommodate the pax
                const privateSelected = privateHall.checked;
                const mainSelected = mainHall.checked;
                const alFrescoSelected = alFresco.checked;
                
                const totalCapacity = (privateSelected ? 15 : 0) + (mainSelected ? 50 : 0) + (alFrescoSelected ? 30 : 0);
                
                if (paxValue > 0 && paxValue > totalCapacity) {
                    hallError.classList.remove('hidden');
                } else {
                    hallError.classList.add('hidden');
                }
                
                // Recalculate total
                calculateTotal();
            }

            window.handleHallSelection = function() {
                const paxInput = document.getElementById('numberOfPax');
                const privateHall = document.getElementById('privateHall');
                const mainHall = document.getElementById('mainHall');
                const alFresco = document.getElementById('alFresco');
                const hallError = document.getElementById('hallError');
                
                const paxValue = parseInt(paxInput.value) || 0;
                const privateSelected = privateHall.checked;
                const mainSelected = mainHall.checked;
                const alFrescoSelected = alFresco.checked;
                
                // Calculate total capacity of selected halls
                const totalCapacity = (privateSelected ? 15 : 0) + (mainSelected ? 50 : 0) + (alFrescoSelected ? 30 : 0);
                
                // Validate if selected halls can accommodate the pax
                if (paxValue > 0 && paxValue > totalCapacity) {
                    hallError.classList.remove('hidden');
                } else {
                    hallError.classList.add('hidden');
                }
                
                // Recalculate total
                calculateTotal();
            }

            // Add clearImagePreview function to window
            window.clearImagePreview = function() {
                const fileInput = document.getElementById('paymentProof');
                const previewContainer = document.getElementById('imagePreviewContainer');
                
                fileInput.value = '';
                previewContainer.classList.add('hidden');
            };

            window.showReservationForm = showReservationForm;

            // Add these functions to your JavaScript
            function showQRModal(imageSrc, gcashNumber = '') {
                const modal = document.getElementById('qrCodeModal');
                const fullscreenImg = document.getElementById('fullscreenQR');
                const qrNumberDisplay = document.getElementById('qrCodeNumber');
                
                fullscreenImg.src = imageSrc;
                if (gcashNumber) {
                    qrNumberDisplay.textContent = `GCash Number: ${gcashNumber}`;
                } else {
                    qrNumberDisplay.textContent = '';
                }
                modal.classList.remove('hidden');
            }

            window.showQRModal = showQRModal;

            function closeQRModal() {
                document.getElementById('qrCodeModal').classList.add('hidden');
            }

            window.closeQRModal = closeQRModal;

            // Add click event listeners to all QR code images
            function setupQRCodeClickHandlers() {
                const qrImages = document.querySelectorAll('#qrCodeContainer img');
                qrImages.forEach(img => {
                    img.style.cursor = 'pointer';
                    // Find the associated GCash number (it's in the next sibling p element)
                    const gcashNumber = img.nextElementSibling?.textContent?.replace('GCash Number: ', '') || '';
                    img.addEventListener('click', function() {
                        showQRModal(this.src, gcashNumber);
                    });
                });
            }

            window.setupQRCodeClickHandlers = setupQRCodeClickHandlers;

            window.validateReservationForm = async function() {
                let isValid = true;
                
                // Validate number of pax
                const numberOfPax = document.getElementById('numberOfPax');
                const paxError = document.getElementById('paxError');
                if (numberOfPax.value <= 0 || isNaN(numberOfPax.value) || numberOfPax.value > 95) {
                    paxError.classList.remove('hidden');
                    numberOfPax.classList.add('border-red-500');
                    isValid = false;
                } else {
                    paxError.classList.add('hidden');
                    numberOfPax.classList.remove('border-red-500');
                }
                
                // Validate reservation date
                const reservationDate = document.getElementById('reservationDate');
                const dateError = document.getElementById('dateError');
                const selectedDate = new Date(reservationDate.value);
                const now = new Date();
                
                if (!reservationDate.value || selectedDate <= now) {
                    dateError.textContent = 'Please select a future date and time.';
                    dateError.classList.remove('hidden');
                    reservationDate.classList.add('border-red-500');
                    isValid = false;
                } else if (!validateReservationTime(reservationDate.value)) {
                    dateError.textContent = 'Reservations are only available between 10 AM and 10 PM.';
                    dateError.classList.remove('hidden');
                    reservationDate.classList.add('border-red-500');
                    isValid = false;
                } else {
                    // Check availability with server
                    try {
                        NProgress.start();
                        const availability = await checkAvailability(reservationDate.value);
                        
                        if (!availability.available) {
                            dateError.textContent = availability.message || 'This time slot is fully booked. Please choose another time.';
                            dateError.classList.remove('hidden');
                            reservationDate.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            dateError.classList.add('hidden');
                            reservationDate.classList.remove('border-red-500');
                        }
                    } catch (error) {
                        console.error('Availability check error:', error);
                        dateError.textContent = 'Error checking availability. Please try again.';
                        dateError.classList.remove('hidden');
                        reservationDate.classList.add('border-red-500');
                        isValid = false;
                    } finally {
                        NProgress.done();
                    }
                }
                
                return isValid;
            }

            window.validateReservationTime = function(dateTimeString) {
                if (!dateTimeString) return false;
                
                const selectedDate = new Date(dateTimeString);
                const hours = selectedDate.getHours();
                
                // Validate time is between 10 AM (10) and 10 PM (22)
                return hours >= 10 && hours < 22;
            }

            async function checkAvailability(reservationDateTime) {
                try {
                    const response = await fetch('reservationsAPI/check_availability.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            reservation_datetime: reservationDateTime
                        })
                    });
                    
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error checking availability:', error);
                    return { available: false, message: 'Error checking availability' };
                }
            }

            window.checkAvailability = checkAvailability;

            function submitReservation(packageId) {
                const form = document.getElementById('reservationForm');
                const formData = new FormData(form);
                formData.append('package_id', packageId);
                
                // Get the pax value
                const paxValue = parseInt(formData.get('numberOfPax') || 0);
                
                // Get selected halls
                const privateHall = document.getElementById('privateHall').checked;
                const mainHall = document.getElementById('mainHall').checked;
                const alFresco = document.getElementById('alFresco').checked;
                
                // Clear any existing hall venue entries
                formData.delete('hallVenue[]');
                
                // Add selected halls to form data
                if (privateHall) formData.append('hallVenue[]', 'Private Hall');
                if (mainHall) formData.append('hallVenue[]', 'Main Hall');
                if (alFresco) formData.append('hallVenue[]', 'Al Fresco');
                
                // Validate that selected halls can accommodate the pax
                const totalCapacity = (privateHall ? 15 : 0) + (mainHall ? 50 : 0) + (alFresco ? 30 : 0);
                if (paxValue > totalCapacity) {
                    showToast('Selected halls cannot accommodate the number of guests', 'error');
                    return;
                }
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                submitBtn.disabled = true;

                const fileInput = document.getElementById('paymentProof');
                if (fileInput.files.length > 0) {
                    formData.append('payment_proof', fileInput.files[0]);
                } else {
                    showToast('Please upload payment proof', 'error');
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    return;
                }

                // Debug: Log form data
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                fetch('bookingAPI/submit_reservation.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast('Reservation submitted successfully!', 'success');
                        closeAllModals();
                        form.reset();
                        document.getElementById('imagePreviewContainer')?.classList.add('hidden');
                    } else {
                        showToast(data.message || 'Error submitting reservation', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error submitting reservation:', error);
                    showToast('Error submitting reservation', 'error');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            }

            window.submitReservation = submitReservation;

            function showModal(title, content) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 animate-fadeIn';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-slideUp">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-2xl font-bold text-rich-brown">${title}</h2>
                                <button onclick="closeModal()" class="text-gray-500 hover:text-rich-brown transition">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            ${content}
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                document.body.style.overflow = 'hidden';
                
                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            function closeModal() {
                const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
                if (modal) {
                    modal.classList.add('animate-fadeOut');
                    modal.querySelector('.animate-slideUp').classList.add('animate-slideDown');
                    
                    setTimeout(() => {
                        document.body.removeChild(modal);
                        document.body.style.overflow = '';
                    }, 300);
                }
            }

            window.closeModal = closeModal;

            function closeAllModals() {
                const modals = document.querySelectorAll('.fixed.inset-0.bg-black.bg-opacity-50');
                modals.forEach(modal => {
                    modal.classList.add('animate-fadeOut');
                    const modalContent = modal.querySelector('.animate-slideUp');
                    if (modalContent) {
                        modalContent.classList.add('animate-slideDown');
                    }
                    
                    setTimeout(() => {
                        if (modal.parentNode) {
                            modal.parentNode.removeChild(modal);
                        }
                    }, 300);
                });
                
                document.body.style.overflow = '';
            }

            // Make the new function available globally
            window.closeAllModals = closeAllModals;

            // Order item function
            function orderItem(itemName, price) {
                showToast(`Added "${itemName}" to your order!`, 'success');
                // Here you would typically add the item to cart or redirect to order page
                console.log(`Ordering: ${itemName} - ₱${price}`);
            }

            // Make functions global
            window.loadMenu = loadMenu;
            window.orderItem = orderItem;

            // Initialize menu loading
            NProgress.start();
            loadMenu();

            // Refresh button functionality
            document.getElementById('refresh-btn').addEventListener('click', () => {
                NProgress.start();
                const refreshIcon = document.querySelector('#refresh-btn i');
                refreshIcon.classList.add('fa-spin');
                
                loadMenu();
                
                setTimeout(() => {
                    refreshIcon.classList.remove('fa-spin');
                    showToast('Menu refreshed successfully!', 'success');
                }, 1000);
            });

            // Initialize dynamic content loading
            function loadNotifications() {
                const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
                if (notificationsContainer) {
                    setTimeout(() => {
                        notificationsContainer.innerHTML = `
                            <div class="p-4 border-b border-deep-brown/10">
                                <p class="font-baskerville text-deep-brown">New menu items available!</p>
                                <p class="text-sm text-deep-brown/60">Check out our latest dishes.</p>
                            </div>
                            <div class="p-4">
                                <p class="font-baskerville text-deep-brown">Reservation confirmed</p>
                                <p class="text-sm text-deep-brown/60">Your table is ready for tonight.</p>
                            </div>
                        `;
                    }, 1000);
                }
            }

            loadNotifications();

            // Add keyboard navigation support
            document.addEventListener('keydown', function(e) {
                // ESC key closes mobile menu
                if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                    toggleMobileMenu();
                }
            });

            // Add smooth scroll behavior for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading state management
            let isLoading = false;

            function setLoading(loading) {
                isLoading = loading;
                const refreshBtn = document.getElementById('refresh-btn');
                const refreshIcon = refreshBtn.querySelector('i');
                
                if (loading) {
                    refreshBtn.disabled = true;
                    refreshIcon.classList.add('fa-spin');
                    refreshBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    refreshBtn.disabled = false;
                    refreshIcon.classList.remove('fa-spin');
                    refreshBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Enhanced error handling
            function handleError(error, userMessage) {
                console.error('Error:', error);
                showToast(userMessage || 'Something went wrong. Please try again.', 'error');
                setLoading(false);
            }

            // Add intersection observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe menu cards for animation
            function observeMenuCards() {
                document.querySelectorAll('.menu-card').forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(card);
                });
            }

            // Update loadMenu function to include animations
            const originalLoadMenu = loadMenu;
            window.loadMenu = function() {
                setLoading(true);
                originalLoadMenu();
                
                // Add a small delay to ensure DOM is updated before observing
                setTimeout(() => {
                    observeMenuCards();
                    setLoading(false);
                }, 100);
            };

            // Add responsive image loading for future enhancements
            function loadImage(src, placeholder) {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.onload = () => resolve(src);
                    img.onerror = () => resolve(placeholder);
                    img.src = src;
                });
            }

            // Add touch gesture support for mobile
            let touchStartY = 0;
            let touchEndY = 0;

            document.addEventListener('touchstart', function(e) {
                touchStartY = e.changedTouches[0].screenY;
            });

            document.addEventListener('touchend', function(e) {
                touchEndY = e.changedTouches[0].screenY;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartY - touchEndY;
                
                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe up - could trigger refresh or other action
                        console.log('Swipe up detected');
                    } else {
                        // Swipe down - could trigger refresh
                        console.log('Swipe down detected');
                    }
                }
            }

            // Add performance monitoring
            function measurePerformance() {
                if (typeof performance !== 'undefined' && performance.mark) {
                    performance.mark('menu-load-start');
                    
                    // Measure when menu loading is complete
                    const originalDone = NProgress.done;
                    NProgress.done = function() {
                        originalDone.call(this);
                        performance.mark('menu-load-end');
                        performance.measure('menu-load', 'menu-load-start', 'menu-load-end');
                    };
                }
            }

            measurePerformance();

            // Add accessibility improvements
            function enhanceAccessibility() {
                // Add skip to main content link
                const skipLink = document.createElement('a');
                skipLink.href = '#main-content';
                skipLink.textContent = 'Skip to main content';
                skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-rich-brown text-warm-cream px-4 py-2 rounded z-50';
                document.body.insertBefore(skipLink, document.body.firstChild);

                // Add main landmark
                const main = document.querySelector('main');
                if (main) {
                    main.id = 'main-content';
                    main.setAttribute('role', 'main');
                }

                // Enhance button accessibility
                document.querySelectorAll('button').forEach(button => {
                    if (!button.getAttribute('aria-label') && !button.textContent.trim()) {
                        button.setAttribute('aria-label', 'Button');
                    }
                });
            }

            enhanceAccessibility();

            // Add print styles support
            function addPrintSupport() {
                const printStyles = document.createElement('style');
                printStyles.textContent = `
                    @media print {
                        .mobile-menu, nav, #toast-container, #nprogress-container {
                            display: none !important;
                        }
                        
                        .menu-card {
                            break-inside: avoid;
                            margin-bottom: 1rem;
                        }
                        
                        .hover-lift:hover {
                            transform: none;
                            box-shadow: none;
                        }
                        
                        body {
                            background: white !important;
                            color: black !important;
                        }
                    }
                `;
                document.head.appendChild(printStyles);
            }

            addPrintSupport();

            console.log('Menu page initialized successfully');
        });
        
        function toggleQRCodes() {
            const additionalQRCodes = document.getElementById('additionalQRCodes');
            const toggleButton = document.querySelector('button[onclick="toggleQRCodes()"]');
            
            if (additionalQRCodes.classList.contains('hidden')) {
                additionalQRCodes.classList.remove('hidden');
                toggleButton.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Hide QR Codes';
                setupQRCodeClickHandlers();
            } else {
                additionalQRCodes.classList.add('hidden');
                toggleButton.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Show All QR Codes';
            }
        }

    </script>
    
    <?php
$content = ob_get_clean();
include 'layout_customer.php';
?>