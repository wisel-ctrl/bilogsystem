<?php
require_once 'customer_auth.php';
// Set page title
$page_title = "Menu - Caffè Lilio";

// Capture content
ob_start();
?>


    <!-- Loading Progress Bar -->
    <div id="nprogress-container"></div>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Menu Section -->
        <section class="mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <h2 class="font-playfair text-3xl sm:text-4xl font-bold text-deep-brown">Our Menu</h2>
                <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 flex items-center justify-center sm:justify-start space-x-2 p-2 rounded-lg hover:bg-deep-brown/5"
                        data-tippy-content="Refresh menu"
                        id="refresh-btn">
                    <i class="fas fa-sync-alt"></i>
                    <span class="font-baskerville text-sm">Refresh</span>
                </button>
            </div>
            
            <div class="bg-card rounded-xl p-4 sm:p-6 shadow-md">
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
        </section>
    </main>

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
                                menuItem.className = 'menu-card bg-card rounded-xl shadow-md hover-lift group relative overflow-hidden';
                                menuItem.innerHTML = `
                                    <div class="package-badge">
                                        ${package.type || 'Package'}
                                    </div>
                                    <div class="menu-card-content p-6">
                                        <h4 class="font-playfair text-xl font-bold mb-3 text-deep-brown pr-20">
                                            ${package.package_name || 'Menu Item'}
                                        </h4>
                                        <p class="font-baskerville text-deep-brown/80 menu-card-description mb-4">
                                            ${package.package_description || 'Delicious menu item description.'}
                                        </p>
                                        <div class="menu-card-footer">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="price-display font-baskerville">
                                                    ₱${parseFloat(package.price || 0).toFixed(2)}
                                                </span>
                                                <div class="text-sm text-deep-brown/60 font-baskerville">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Available
                                                </div>
                                            </div>
                                            <button class="order-btn btn-primary bg-rich-brown text-warm-cream rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 group"
                                                    onclick="orderItem('${package.package_name}', ${package.price})"
                                                    aria-label="Order ${package.package_name}">
                                                <span>Order Now</span>
                                                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
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
    </script>
<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>