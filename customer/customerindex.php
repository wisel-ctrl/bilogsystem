<?php
require_once 'customer_auth.php';
require_once '../db_connect.php'; // Use your PDO connection file

// Fetch user details from database
try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT first_name, last_name FROM users_tb WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        throw new Exception("User not found");
    }
} catch(Exception $e) {
    // Handle error (you might want to redirect or show an error message)
    die("Error fetching user data: " . $e->getMessage());
}
// Start output buffering to capture the page-specific content
ob_start();
?>



    <!-- Loading Progress Bar -->
    <div id="nprogress-container"></div>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

        <!-- Welcome Section -->
        <section class="mb-12 animate-fade-in">
            <h2 class="font-playfair text-4xl font-bold mb-4 text-deep-brown">Welcome back, <span class="text-gradient"><?php echo htmlspecialchars(ucfirst($user['first_name'])); ?></span>!</h2>
            <p class="font-baskerville text-lg text-deep-brown/80">Here's what's happening with your reservations and upcoming events.</p>
        </section>

        <!-- Quick Actions -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-rich-brown backdrop-blur-md rounded-xl p-6 shadow-md hover-lift border border-deep-brown/10" data-tippy-content="Make a new reservation">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-playfair text-xl font-bold text-warm-cream">New Reservation</h3>
                    <span class="text-2xl transform transition-transform group-hover:rotate-12">📅</span>
                </div>
                <p class="font-baskerville mb-4 text-warm-cream">Book a table or plan your next event with us.</p>
                <a href="bookingpage.php" class="btn-primary bg-warm-cream text-deep-brown px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 w-full flex items-center justify-center space-x-2">
                    <span>Make Reservation</span>
                    <i class="fas fa-arrow-right transition-transform transform group-hover:translate-x-1"></i>
                </a>
            </div>

            <div class="bg-rich-brown rounded-xl p-6 shadow-md hover-lift" data-tippy-content="View our current menu">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-playfair text-xl font-bold text-warm-cream">View Menu</h3>
                    <span class="text-2xl transform transition-transform group-hover:rotate-12">🍽️</span>
                </div>
                <p class="font-baskerville mb-4 text-warm-cream">Explore our latest dishes and seasonal specials.</p>
                <a href="menu.php" class="btn-primary bg-warm-cream text-deep-brown px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 w-full flex items-center justify-center space-x-2">
                    <span>Browse Menu</span>
                    <i class="fas fa-utensils ml-2"></i>
                </a>
            </div>

            <div class="bg-rich-brown rounded-xl p-6 shadow-md hover-lift" data-tippy-content="Get assistance from our support team">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-playfair text-xl font-bold text-warm-cream">Contact Support</h3>
                    <span class="text-2xl transform transition-transform group-hover:rotate-12">💬</span>
                </div>
                <p class="font-baskerville mb-4 text-warm-cream">Need help? Our team is here to assist you.</p>
                <button class="btn-primary bg-warm-cream text-deep-brown px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 w-full flex items-center justify-center space-x-2">
                    <span>Get Help</span>
                    <i class="fas fa-headset ml-2"></i>
                </button>
            </div>
        </section>

        <!-- Upcoming Reservations -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown">Upcoming Reservations</h3>
                <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 flex items-center space-x-2"
                        data-tippy-content="Refresh reservations">
                    <i class="fas fa-sync-alt"></i>
                    <span class="font-baskerville text-sm">Refresh</span>
                </button>
            </div>
            <div class="bg-card rounded-xl p-6 shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full" role="table">
                        <thead>
                            <tr class="border-b border-deep-brown/20">
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Date & Time</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Event Type</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Guests</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Status</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-deep-brown/10 hover:bg-deep-brown/5 transition-colors duration-300">
                                <td class="py-4 px-4">
                                    <div class="font-baskerville text-deep-brown">March 15, 2024</div>
                                    <div class="text-sm text-deep-brown/60">7:00 PM</div>
                                </td>
                                <td class="py-4 px-4 font-baskerville text-deep-brown">Dinner Reservation</td>
                                <td class="py-4 px-4 font-baskerville text-deep-brown">4</td>
                                <td class="py-4 px-4">
                                    <span class="bg-warm-cream/50 text-deep-brown px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center border border-deep-brown/10">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Confirmed
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 p-2 rounded-full hover:bg-warm-cream"
                                                data-tippy-content="Edit reservation">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-warm-cream"
                                                data-tippy-content="Cancel reservation">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-deep-brown/5 transition-colors duration-300">
                                <td class="py-4 px-4">
                                    <div class="font-baskerville text-deep-brown">March 20, 2024</div>
                                    <div class="text-sm text-deep-brown/60">6:30 PM</div>
                                </td>
                                <td class="py-4 px-4 font-baskerville text-deep-brown">Birthday Celebration</td>
                                <td class="py-4 px-4 font-baskerville text-deep-brown">12</td>
                                <td class="py-4 px-4">
                                    <span class="bg-yellow-100/50 text-yellow-800 px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                        Pending
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 p-2 rounded-full hover:bg-deep-brown/10"
                                                data-tippy-content="Edit reservation">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-red-50"
                                                data-tippy-content="Cancel reservation">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Special Offers -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown">Special Offers</h3>
                <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 flex items-center space-x-2"
                        data-tippy-content="View all offers">
                    <span class="font-baskerville text-sm">View All</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-card rounded-xl overflow-hidden shadow-md hover-lift group">
                    <div class="relative">
                        <img src="../images/01_buffet.jpg" alt="Special Buffet Offer" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            20% OFF
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="font-playfair text-xl font-bold mb-2 text-deep-brown">Weekend Buffet Special</h4>
                        <p class="font-baskerville mb-4 text-deep-brown/80">Enjoy our premium buffet selection at 20% off every weekend.</p>
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="font-baskerville font-bold text-lg text-deep-brown">₱1,760/person</span>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star-half-alt text-yellow-500"></i>
                                    <span class="text-sm text-deep-brown/60 ml-1">(4.8)</span>
                                </div>
                            </div>
                            <button class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2 group">
                                <span>Book Now</span>
                                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-xl overflow-hidden shadow-md hover-lift group">
                    <div class="relative">
                        <img src="../images/cheesewheelpasta.jpg" alt="Cheese Wheel Pasta" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute top-4 right-4 bg-deep-brown text-warm-cream px-3 py-1 rounded-full text-sm font-bold">
                            SPECIAL
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="font-playfair text-xl font-bold mb-2 text-deep-brown">Cheese Wheel Experience</h4>
                        <p class="font-baskerville mb-4 text-deep-brown/80">Try our famous cheese wheel pasta preparation, now with a complimentary glass of wine.</p>
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="font-baskerville font-bold text-lg text-deep-brown">₱850/person</span>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <span class="text-sm text-deep-brown/60 ml-1">(5.0)</span>
                                </div>
                            </div>
                            <button class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2 group">
                                <span>Reserve Now</span>
                                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown">Recent Activity</h3>
                <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 flex items-center space-x-2"
                        data-tippy-content="View all activity">
                    <span class="font-baskerville text-sm">View All</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="bg-white/80 rounded-xl p-6 shadow-md">
                <div class="space-y-4">
                    <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-deep-brown/5 transition-colors duration-300">
                        <div class="bg-deep-brown/10 rounded-full p-3 flex-shrink-0">
                            <i class="fas fa-calendar-check text-deep-brown text-lg"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between">
                                <p class="font-baskerville font-bold text-deep-brown">Reservation Confirmed</p>
                                <span class="text-sm text-deep-brown/60">2 hours ago</span>
                            </div>
                            <p class="text-sm text-deep-brown/60 mt-1">Your reservation for March 15 has been confirmed.</p>
                            <button class="mt-2 text-deep-brown hover:text-rich-brown transition-colors duration-300 text-sm flex items-center space-x-1">
                                <span>View Details</span>
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-deep-brown/5 transition-colors duration-300">
                        <div class="bg-deep-brown/10 rounded-full p-3 flex-shrink-0">
                            <i class="fas fa-receipt text-deep-brown text-lg"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between">
                                <p class="font-baskerville font-bold text-deep-brown">Payment Processed</p>
                                <span class="text-sm text-deep-brown/60">1 day ago</span>
                            </div>
                            <p class="text-sm text-deep-brown/60 mt-1">Payment for Birthday Celebration deposit received.</p>
                            <button class="mt-2 text-deep-brown hover:text-rich-brown transition-colors duration-300 text-sm flex items-center space-x-1">
                                <span>View Receipt</span>
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-deep-brown/5 transition-colors duration-300">
                        <div class="bg-deep-brown/10 rounded-full p-3 flex-shrink-0">
                            <i class="fas fa-star text-deep-brown text-lg"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between">
                                <p class="font-baskerville font-bold text-deep-brown">Review Posted</p>
                                <span class="text-sm text-deep-brown/60">3 days ago</span>
                            </div>
                            <p class="text-sm text-deep-brown/60 mt-1">Thank you for reviewing your last visit!</p>
                            <button class="mt-2 text-deep-brown hover:text-rich-brown transition-colors duration-300 text-sm flex items-center space-x-1">
                                <span>View Review</span>
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <!-- Modern Footer -->
    <footer class="bg-deep-brown text-warm-cream relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-8 left-8 w-32 h-32 border border-warm-cream rounded-full"></div>
            <div class="absolute bottom-12 right-12 w-24 h-24 border border-warm-cream rounded-full"></div>
            <div class="absolute top-1/2 left-1/4 w-2 h-2 bg-warm-cream rounded-full"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-warm-cream rounded-full"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="py-2">
                <!-- Brand Section -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center space-x-3 mt-4 mb-4">
                        <div>
                            <h2 class="font-playfair font-bold text-2xl tracking-tight">Caffè Lilio</h2>
                            <p class="text-xs tracking-[0.2em] text-warm-cream/80 uppercase font-inter font-light">Ristorante</p>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    <!-- Contact Info -->
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Contact
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="space-y-3 font-inter text-sm">
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-map-marker-alt text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">123 Restaurant St., Food District</p>
                            </div>
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-phone text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">+63 912 345 6789</p>
                            </div>
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-envelope text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">info@caffelilio.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Navigate
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <nav class="space-y-2 font-inter text-sm">
                            <a href="#about" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">About Us</a>
                            <a href="menu.php" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Our Menu</a>
                            <a href="my_reservations.php" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Reservations</a>
                            <a href="#contact" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Visit Us</a>
                        </nav>
                    </div>

                    <!-- Hours -->
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Hours
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="space-y-2 font-inter text-sm">
                            <div class="flex justify-between">
                                <span class="text-warm-cream/90">Mon - Fri</span>
                                <span class="text-warm-cream">11AM - 10PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-warm-cream/90">Sat - Sun</span>
                                <span class="text-warm-cream">10AM - 11PM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Social & Connect -->
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Connect
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        
                        <!-- Social Links -->
                        <div class="flex space-x-3 mb-4">
                            <a href="https://web.facebook.com/caffelilio.ph" target="_blank" 
                               class="w-10 h-10 bg-warm-cream/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-warm-cream/20 hover:bg-warm-cream/20 transition-colors">
                                <i class="fab fa-facebook-f text-warm-cream text-sm"></i>
                            </a>
                            <a href="https://www.instagram.com/caffelilio.ph/" target="_blank" 
                               class="w-10 h-10 bg-warm-cream/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-warm-cream/20 hover:bg-warm-cream/20 transition-colors">
                                <i class="fab fa-instagram text-warm-cream text-sm"></i>
                            </a>
                        </div>

                        <!-- Simple Newsletter -->
                        <div class="space-y-2">
                            <p class="text-warm-cream/80 text-xs font-inter">Stay updated</p>
                            <div class="flex">
                                <input type="email" placeholder="Email" 
                                       class="flex-1 px-3 py-2 bg-warm-cream/10 border border-warm-cream/20 rounded-l text-sm text-warm-cream placeholder-warm-cream/50 focus:outline-none focus:border-warm-cream/40 backdrop-blur-sm">
                                <button class="px-3 py-2 bg-warm-cream/20 border border-warm-cream/20 border-l-0 rounded-r hover:bg-warm-cream/30 transition-colors backdrop-blur-sm">
                                    <i class="fas fa-arrow-right text-warm-cream text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-warm-cream/10 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <p class="font-inter text-warm-cream/70 text-xs">
                        © 2024 Caffè Lilio Ristorante. All rights reserved.
                    </p>
                    <div class="flex space-x-4 text-xs font-inter">
                        <a href="#privacy" class="text-warm-cream/70 hover:text-warm-cream transition-colors">Privacy</a>
                        <a href="#terms" class="text-warm-cream/70 hover:text-warm-cream transition-colors">Terms</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php
    $page_content = ob_get_clean();

    // Capture page-specific scripts
    ob_start();
?>

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
            
            // Handle regular links (like menu.php)
            document.querySelectorAll('a[href$=".php"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    NProgress.start();
                    // Let the default link behavior happen
                });
            });

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                        <span>${message}</span>
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

            // Handle reservation cancellation with improved UX
            document.querySelectorAll('.fa-trash').forEach(button => {
                button.addEventListener('click', function() {
                    const confirmDialog = document.createElement('div');
                    confirmDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                    confirmDialog.innerHTML = `
                        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                            <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Cancel Reservation?</h3>
                            <p class="text-deep-brown/80 mb-6">Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                            <div class="flex justify-end space-x-4">
                                <button class="px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300" id="cancel-dialog">
                                    Keep Reservation
                                </button>
                                <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors duration-300" id="confirm-cancel">
                                    Yes, Cancel
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(confirmDialog);
                    document.body.classList.add('overflow-hidden');

                    document.getElementById('cancel-dialog').addEventListener('click', () => {
                        confirmDialog.remove();
                        document.body.classList.remove('overflow-hidden');
                    });

                    document.getElementById('confirm-cancel').addEventListener('click', () => {
                        NProgress.start();
                        setTimeout(() => {
                            confirmDialog.remove();
                            document.body.classList.remove('overflow-hidden');
                            showToast('Reservation cancelled successfully');
                            NProgress.done();
                        }, 1000);
                    });
                });
            });

            // Handle reservation editing
            document.querySelectorAll('.fa-edit').forEach(button => {
                button.addEventListener('click', function() {
                    showToast('Opening reservation editor...', 'success');
                    NProgress.start();
                    
                    setTimeout(() => {
                        NProgress.done();
                    }, 1000);
                });
            });

            // Add smooth scroll behavior
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
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

            // Simulate loading states for dynamic content
            function loadNotifications() {
                const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
                setTimeout(() => {
                    notificationsContainer.innerHTML = `
                        <div class="p-4 border-b border-deep-brown/10">
                            <p class="font-baskerville text-deep-brown">New special offer available!</p>
                            <p class="text-sm text-deep-brown/60">Check out our weekend buffet special.</p>
                        </div>
                    `;
                }, 1000);
            }

            // Initialize dynamic content loading
            loadNotifications();
        });
    </script>
<?php
// Capture the content and include the layout
$content = ob_get_clean();
require_once 'layout_customer.php';
?>