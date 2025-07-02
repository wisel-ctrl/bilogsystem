<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';

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
    die("Error fetching user data: " . $e->getMessage());
}

// Set page title
$page_title = "Customer Dashboard - Caffè Lilio";

// Capture content
ob_start();
?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        /* Enhanced hover effect */
        .hover-lift {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        /* Improved background gradients */
        .bg-warm-gradient {
            background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
        }

        .bg-card {
            /* background: linear-gradient(145deg, #E8E0D5, #d6c7b6); */
            background: white;
            backdrop-filter: blur(8px);
        }

        .bg-nav {
            background: linear-gradient(to bottom, #5D2F0F, #4a260d);
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Loading skeleton animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Accessibility improvements */
        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
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

        /* Toast notification styles */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            background: #E8E0D5;
            box-shadow: 0 4px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Button animations */
        /* .btn-primary {
            position: relative;
            overflow: hidden;
            background: #8B4513;
            color: #E8E0D5;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(232, 224, 213, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:active::after {
            width: 200%;
            height: 200%;
        } */

        /* Improved mobile menu */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        /* Skeleton loading placeholder */
        .skeleton-text {
            height: 1em;
            background: #e0e0e0;
            margin: 0.5em 0;
            border-radius: 4px;
        }

        /* Improved form inputs */
        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }
    </style>
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

<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>