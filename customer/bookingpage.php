<?php
require_once 'customer_auth.php';
// Set page title
$page_title = "Bookings - Caffè Lilio";

// Capture content
ob_start();
?>

<!-- Main Content -->
<section class="mb-12">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <h2 class="font-playfair text-4xl sm:text-5xl font-bold text-deep-brown bg-gradient-to-r from-rich-brown to-amber-600 bg-clip-text text-transparent">Our Menu</h2>
        <button class="text-deep-brown hover:text-white transition-colors duration-300 flex items-center justify-center sm:justify-start space-x-2 p-3 rounded-lg bg-gradient-to-r from-amber-100 to-amber-200 hover:from-rich-brown hover:to-deep-brown shadow-md"
                data-tippy-content="Refresh menu"
                id="refresh-btn">
            <i class="fas fa-sync-alt"></i>
            <span class="font-baskerville text-sm font-medium">Refresh</span>
        </button>
    </div>
    
    <div class="bg-card rounded-2xl p-6 sm:p-8 shadow-xl border border-amber-100/50">
        <!-- Loading State -->
        <div id="menu-loading" class="menu-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="loading-card skeleton bg-gray-100 rounded-xl p-6 animate-pulse">
                <div class="loading-header skeleton h-6 w-3/4 bg-gray-200 rounded mb-4"></div>
                <div class="loading-text w-3/4 h-4 bg-gray-200 rounded mb-2"></div>
                <div class="loading-text w-1/2 h-4 bg-gray-200 rounded mb-2"></div>
                <div class="loading-text w-2/3 h-4 bg-gray-200 rounded mb-4"></div>
                <div class="loading-button skeleton h-10 w-full bg-gray-200 rounded-lg"></div>
            </div>
            <div class="loading-card skeleton bg-gray-100 rounded-xl p-6 animate-pulse">
                <div class="loading-header skeleton h-6 w-3/4 bg-gray-200 rounded mb-4"></div>
                <div class="loading-text w-4/5 h-4 bg-gray-200 rounded mb-2"></div>
                <div class="loading-text w-3/5 h-4 bg-gray-200 rounded mb-2"></div>
                <div class="loading-text w-3/4 h-4 bg-gray-200 rounded mb-4"></div>
                <div class="loading-button skeleton h-10 w-full bg-gray-200 rounded-lg"></div>
            </div>
            <div class="loading-card skeleton bg-gray-100 rounded-xl p-6 animate-pulse">
                <div class="loading-header skeleton h-6 w-3/4 bg-gray-200 rounded mb-4"></div>
                <div class="loading-text w-2/3 h-4 bg-gray-200 rounded mb-2"></div>
                <div class="loading-text w-4/5 h-4 bg-gray-200 rounded mb-2"></div>
                <div class="loading-text w-1/2 h-4 bg-gray-200 rounded mb-4"></div>
                <div class="loading-button skeleton h-10 w-full bg-gray-200 rounded-lg"></div>
            </div>
        </div>

        <!-- Menu Container -->
        <div id="menu-container" class="hidden menu-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

        <!-- Empty State -->
        <div id="menu-empty" class="hidden text-center py-12 bg-gray-50 rounded-xl">
            <i class="fas fa-utensils text-6xl text-amber-400 mb-4"></i>
            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">No Menu Items Available</h3>
            <p class="font-baskerville text-deep-brown/70 mb-4">Check back later for our delicious offerings.</p>
            <button class="btn-primary bg-gradient-to-r from-rich-brown to-amber-600 text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:from-deep-brown hover:to-amber-700 transition-all duration-300 shadow-md"
                    onclick="loadMenu()">
                Try Again
            </button>
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
            toast.className = `toast ${type} fixed bottom-4 right-4 bg-gradient-to-r ${type === 'success' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600'} text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300`;
            toast.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
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

        // Global function to show toast
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
                            menuItem.className = 'menu-card bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden';
                            menuItem.innerHTML = `
                                <div class="package-badge absolute top-4 left-4 bg-amber-400 text-white px-3 py-1 rounded-full text-sm font-baskerville">
                                    ${package.type || 'Package'}
                                </div>
                                <div class="menu-card-content p-6 flex flex-col justify-between h-full">
                                    <div>
                                        <h4 class="font-playfair text-2xl font-bold mb-3 text-deep-brown bg-gradient-to-r from-rich-brown to-amber-600 bg-clip-text text-transparent">
                                            ${package.package_name || 'Menu Item'}
                                        </h4>
                                        <p class="font-baskerville text-deep-brown/80 menu-card-description mb-4 line-clamp-3 text-base leading-relaxed">
                                            ${package.package_description || 'Delicious menu item description.'}
                                        </p>
                                    </div>
                                    <div class="menu-card-footer">
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="price-display font-baskerville text-lg text-amber-600 font-semibold">
                                                ₱${parseFloat(package.price || 0).toFixed(2)} <span class="text-sm text-deep-brown/60">per pax</span>
                                            </span>
                                            <div class="text-sm text-deep-brown/60 font-baskerville">
                                                <i class="fas fa-clock mr-1"></i>
                                                Available
                                            </div>
                                        </div>
                                        <button class="reserve-btn w-full bg-gradient-to-r from-rich-brown to-amber-600 text-warm-cream px-5 py-3 rounded-full font-baskerville text-base font-semibold flex items-center justify-center gap-2 hover:from-deep-brown hover:to-amber-700 shadow-md hover:shadow-lg transition-all duration-300 group"
                                                onclick="showPackageDetails(${package.package_id})"
                                                aria-label="Reserve ${package.package_name}">
                                            <span>Reserve Now</span>
                                            <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </button>
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
            fetch('bookingAPI/get_package_dishes.php?package_id=' + packageId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.data && data.data.length > 0) {
                        const package = data.data[0];
                        
                        const categoriesOrder = ['house-salad', 'spanish-dish', 'italian-dish', 'burgers', 'pizza', 'Pasta', 'pasta_caza', 'main-course', 'drinks', 'coffee', 'desserts'];
                        const dishesByCategory = {};
                        
                        categoriesOrder.forEach(category => {
                            dishesByCategory[category] = [];
                        });
                        
                        data.data.forEach(dish => {
                            if (!dishesByCategory[dish.dish_category]) {
                                dishesByCategory[dish.dish_category] = [];
                            }
                            dishesByCategory[dish.dish_category].push(dish);
                        });
                        
                        let modalContent = `
                            <div class="package-details-modal">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-3xl font-playfair font-bold text-rich-brown bg-gradient-to-r from-rich-brown to-amber-600 bg-clip-text text-transparent">${package.package_name}</h3>
                                        <div class="flex items-center mt-2 text-amber-600">
                                            <i class="fas fa-tag mr-2"></i>
                                            <span class="font-semibold text-lg">$${package.price}</span>
                                        </div>
                                    </div>
                                    <button onclick="closeModal()" class="text-gray-500 hover:text-rich-brown transition">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                
                                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-lg shadow-sm">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-amber-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-amber-700 font-baskerville">${package.package_description}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="dishes-list">
                        `;
                        
                        categoriesOrder.forEach(category => {
                            if (dishesByCategory[category] && dishesByCategory[category].length > 0) {
                                modalContent += `
                                    <div class="dish-category mb-6">
                                        <h4 class="text-xl font-playfair font-semibold mb-3 capitalize text-rich-brown flex items-center">
                                            <i class="fas ${getCategoryIcon(category)} mr-2 text-amber-600"></i>
                                            ${category.replace(/-/g, ' ')}
                                        </h4>
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                `;
                                
                                dishesByCategory[category].forEach(dish => {
                                    modalContent += `
                                        <li class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg shadow-sm hover:bg-amber-50 transition">
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
                                    <button onclick="showReservationForm('${packageId}', ${package.price})" class="btn-reserve px-4 py-2 rounded-lg bg-gradient-to-r from-rich-brown to-amber-600 text-white hover:from-deep-brown hover:to-amber-700 transition flex items-center shadow-md">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        Reserve Now
                                    </button>
                                </div>
                            </div>
                        `;
                        
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
                'house-salad': 'fa-leaf',
                'spanish-dish': 'fa-pepper-hot',
                'italian-dish': 'fa-pizza-slice',
                'burgers': 'fa-hamburger',
                'pizza': 'fa-pizza-slice',
                'Pasta': 'fa-utensils',
                'pasta_caza': 'fa-utensils',
                'main-course': 'fa-drumstick-bite',
                'drinks': 'fa-glass-martini-alt',
                'coffee': 'fa-coffee',
                'desserts': 'fa-ice-cream'
            };
            return icons[category] || 'fa-utensils';
        }

        window.getCategoryIcon = getCategoryIcon;

        window.calculateTotal = function() {
            const packagePrice = parseFloat(document.querySelector('input[name="package_price"]').value);
            const numberOfPax = parseInt(document.getElementById('numberOfPax').value) || 0;
            const totalAmount = packagePrice * numberOfPax;
            const downpaymentAmount = totalAmount * 0.5;
            
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
            const formHtml = `
                <div class="reservation-form">
                    <form id="reservationForm" enctype="multipart/form-data">
                        <input type="hidden" name="package_price" value="${packagePrice}">
                        
                        <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm">
                            <div class="flex items-center text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="font-medium">Package Price</span>
                            </div>
                            <div class="text-2xl font-bold text-rich-brown bg-gradient-to-r from-rich-brown to-amber-600 bg-clip-text text-transparent">₱${packagePrice}</div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="reservationDate" class="block text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-calendar-day mr-2 text-amber-600"></i>
                                    Reservation Date
                                </label>
                                <input type="datetime-local" id="reservationDate" name="reservationDate" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 bg-white shadow-sm" required>
                                <p id="dateError" class="text-red-500 text-sm mt-1 hidden">Please select a future date and time.</p>
                            </div>
                            
                            <div>
                                <label for="numberOfPax" class="block text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-users mr-2 text-amber-600"></i>
                                    Number of Pax
                                </label>
                                <input type="number" id="numberOfPax" name="numberOfPax" min="1" max="95"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 bg-white shadow-sm" required
                                    oninput="handlePaxChange()">
                                <p id="paxError" class="text-red-500 text-sm mt-1 hidden">Please enter a number between 1 and 95.</p>
                                <p id="paxMaxInfo" class="text-gray-500 text-sm mt-1">Maximum capacity is 95 pax (50 in Main Hall, 15 in Private Hall, 30 in Al Fresco)</p>
                            </div>
                        </div>
                        
                        <!-- Hall Venue Selection -->
                        <div class="mb-4">
                            <label class="block text-gray-7
                            00 mb-2 flex items-center">
                                <i class="fas fa-building mr-2 text-amber-600"></i>
                                Hall Venue
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="privateHall" name="hallVenue[]" value="Private Hall" 
                                        class="mr-2 h-5 w-5 text-amber-600 focus:ring-amber-400 border-gray-300 rounded"
                                        onchange="handleHallSelection()">
                                    <label for="privateHall" class="text-gray-700">Private Hall (max 15 pax)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="mainHall" name="hallVenue[]" value="Main Hall" 
                                        class="mr-2 h-5 w-5 text-amber-600 focus:ring-amber-400 border-gray-300 rounded"
                                        onchange="handleHallSelection()">
                                    <label for="mainHall" class="text-gray-700">Main Hall (max 50 pax)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="alFresco" name="hallVenue[]" value="Al Fresco" 
                                        class="mr-2 h-5 w-5 text-amber-600 focus:ring-amber-400 border-gray-300 rounded"
                                        onchange="handleHallSelection()">
                                    <label for="alFresco" class="text-gray-700">Al Fresco (max 30 pax)</label>
                                </div>
                            </div>
                            <p id="hallError" class="text-red-500 text-sm mt-1 hidden">Please select hall(s) that can accommodate your party size.</p>
                        </div>
                        
                        <!-- Total Amount and Downpayment Section -->
                        <div class="mb-6 bg-green-50 p-4 rounded-lg border border-green-100 shadow-sm">
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
                        <div class="mb-6 bg-purple-50 p-4 rounded-lg border border-purple-100 shadow-sm">
                            <div class="flex items-center text-purple-800 mb-3">
                                <i class="fas fa-mobile-alt mr-2"></i>
                                <span class="font-medium">GCash Payment</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-700 mb-3">Please scan the QR code to pay the downpayment amount.</p>
                                    <div class="text-sm text-gray-600 mb-3">
                                        <p class="font-medium">Instructions:</p>
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Open GCash app</li>
                                            <li>Tap "Scan QR"</li>
                                            <li>Scan the code on the right</li>
                                            <li>Enter the downpayment amount</li>
                                            <li>Complete the payment</li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <div class="border-2 border-purple-200 p-2 rounded-lg bg-white shadow-md">
                                        <img src="../images/gcashtrial.jpg" alt="GCash QR Code" class="w-48 h-48 object-contain">
                                        <p class="text-center text-sm text-gray-600 mt-2">Scan this QR code to pay</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="eventType" class="block text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-check mr-2 text-amber-600"></i>
                                Event Type
                            </label>
                            <div class="relative">
                                <input type="text" id="eventType" name="eventType" list="eventSuggestions"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 bg-white shadow-sm" 
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
                                <i class="fas fa-edit mr-2 text-amber-600"></i>
                                Additional Notes
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 bg-white shadow-sm" 
                                placeholder="Any special requests or additional information..."></textarea>
                        </div>
                        
                        <div class="mb-6">
                            <label for="paymentProof" class="block text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-receipt mr-2 text-amber-600"></i>
                                Downpayment Proof (50% of package price)
                            </label>
                            
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Normal file input -->
                                <div class="w-full">
                                    <input type="file" id="paymentProof" name="paymentProof" 
                                        accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 bg-white shadow-sm" required>
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
                            <button type="submit" class="btn-reserve px-4 py-2 rounded-lg bg-gradient-to-r from-rich-brown to-amber-600 text-white hover:from-deep-brown hover:to-amber-700 transition flex items-center shadow-md">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirm Reservation
                            </button>
                        </div>
                    </form>
                </div>
            `;
            
            showModal('Complete Reservation', formHtml);
            
            document.getElementById('reservationForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateReservationForm()) {
                    submitReservation(packageId);
                }
            });
            
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
            
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
            document.getElementById('reservationDate').min = localISOTime;
            
            calculateTotal();
        }

        window.handlePaxChange = function() {
            const paxInput = document.getElementById('numberOfPax');
            const paxError = document.getElementById('paxError');
            const privateHall = document.getElementById('privateHall');
            const mainHall = document.getElementById('mainHall');
            const alFresco = document.getElementById('alFresco');
            const hallError = document.getElementById('hallError');
            
            const paxValue = parseInt(paxInput.value);
            if (paxValue < 1 || paxValue > 95) {
                paxError.classList.remove('hidden');
            } else {
                paxError.classList.add('hidden');
            }
            
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
            
            const privateSelected = privateHall.checked;
            const mainSelected = mainHall.checked;
            const alFrescoSelected = alFresco.checked;
            
            const totalCapacity = (privateSelected ? 15 : 0) + (mainSelected ? 50 : 0) + (alFrescoSelected ? 30 : 0);
            
            if (paxValue > 0 && paxValue > totalCapacity) {
                hallError.classList.remove('hidden');
            } else {
                hallError.classList.add('hidden');
            }
            
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
            
            const totalCapacity = (privateSelected ? 15 : 0) + (mainSelected ? 50 : 0) + (alFrescoSelected ? 30 : 0);
            
            if (paxValue > 0 && paxValue > totalCapacity) {
                hallError.classList.remove('hidden');
            } else {
                hallError.classList.add('hidden');
            }
            
            calculateTotal();
        }

        window.clearImagePreview = function() {
            const fileInput = document.getElementById('paymentProof');
            const previewContainer = document.getElementById('imagePreviewContainer');
            
            fileInput.value = '';
            previewContainer.classList.add('hidden');
        };

        window.showReservationForm = showReservationForm;

        window.validateReservationForm = function() {
            let isValid = true;
            
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
            
            const reservationDate = document.getElementById('reservationDate');
            const dateError = document.getElementById('dateError');
            const selectedDate = new Date(reservationDate.value);
            const now = new Date();
            
            if (!reservationDate.value || selectedDate <= now) {
                dateError.classList.remove('hidden');
                reservationDate.classList.add('border-red-500');
                isValid = false;
            } else {
                dateError.classList.add('hidden');
                reservationDate.classList.remove('border-red-500');
            }
            
            return isValid;
        }

        function submitReservation(packageId) {
            const form = document.getElementById('reservationForm');
            const formData = new FormData(form);
            formData.append('package_id', packageId);
            
            const paxValue = parseInt(formData.get('numberOfPax') || 0);
            
            const privateHall = document.getElementById('privateHall').checked;
            const mainHall = document.getElementById('mainHall').checked;
            const alFresco = document.getElementById('alFresco').checked;
            
            formData.delete('hallVenue[]');
            
            if (privateHall) formData.append('hallVenue[]', 'Private Hall');
            if (mainHall) formData.append('hallVenue[]', 'Main Hall');
            if (alFresco) formData.append('hallVenue[]', 'Al Fresco');
            
            const totalCapacity = (privateHall ? 15 : 0) + (mainHall ? 50 : 0) + (alFresco ? 30 : 0);
            if (paxValue > totalCapacity) {
                showToast('Selected halls cannot accommodate the number of guests', 'error');
                return;
            }
            
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
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-slideUp border border-amber-100/50">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-rich-brown bg-gradient-to-r from-rich-brown to-amber-600 bg-clip-text text-transparent">${title}</h2>
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

        window.closeAllModals = closeAllModals;

        function orderItem(itemName, price) {
            showToast(`Added "${itemName}" to your order!`, 'success');
            console.log(`Ordering: ${itemName} - ₱${price}`);
        }

        window.loadMenu = loadMenu;
        window.orderItem = orderItem;

        NProgress.start();
        loadMenu();

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

        function loadNotifications() {
            const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
            if (notificationsContainer) {
                setTimeout(() => {
                    notificationsContainer.innerHTML = `
                        <div class="p-4 border-b border-deep-brown/10 bg-amber-50">
                            <p class="font-baskerville text-deep-brown">New menu items available!</p>
                            <p class="text-sm text-deep-brown/60">Check out our latest dishes.</p>
                        </div>
                        <div class="p-4 bg-amber-50">
                            <p class="font-baskerville text-deep-brown">Reservation confirmed</p>
                            <p class="text-sm text-deep-brown/60">Your table is ready for tonight.</p>
                        </div>
                    `;
                }, 1000);
            }
        }

        loadNotifications();

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                toggleMobileMenu();
            }
        });

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

        function handleError(error, userMessage) {
            console.error('Error:', error);
            showToast(userMessage || 'Something went wrong. Please try again.', 'error');
            setLoading(false);
        }

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

        function observeMenuCards() {
            document.querySelectorAll('.menu-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        }

        const originalLoadMenu = loadMenu;
        window.loadMenu = function() {
            setLoading(true);
            originalLoadMenu();
            
            setTimeout(() => {
                observeMenuCards();
                setLoading(false);
            }, 100);
        };

        function loadImage(src, placeholder) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(src);
                img.onerror = () => resolve(placeholder);
                img.src = src;
            });
        }

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
                    console.log('Swipe up detected');
                } else {
                    console.log('Swipe down detected');
                }
            }
        }

        function measurePerformance() {
            if (typeof performance !== 'undefined' && performance.mark) {
                performance.mark('menu-load-start');
                
                const originalDone = NProgress.done;
                NProgress.done = function() {
                    originalDone.call(this);
                    performance.mark('menu-load-end');
                    performance.measure('menu-load', 'menu-load-start', 'menu-load-end');
                };
            }
        }

        measurePerformance();

        function enhanceAccessibility() {
            const skipLink = document.createElement('a');
            skipLink.href = '#main-content';
            skipLink.textContent = 'Skip to main content';
            skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-gradient-to-r from-rich-brown to-amber-600 text-warm-cream px-4 py-2 rounded z-50';
            document.body.insertBefore(skipLink, document.body.firstChild);

            const main = document.querySelector('main');
            if (main) {
                main.id = 'main-content';
                main.setAttribute('role', 'main');
            }

            document.querySelectorAll('button').forEach(button => {
                if (!button.getAttribute('aria-label') && !button.textContent.trim()) {
                    button.setAttribute('aria-label', 'Button');
                }
            });
        }

        enhanceAccessibility();

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
</script>

<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>