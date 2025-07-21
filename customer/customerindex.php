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
        <a href="bookingpage.php" class="btn-primary bg-warm-cream text-deep-brown px-6 py-3 rounded-lg font-baskerville hover:bg-warm-cream/50 transition-all duration-300 w-full flex items-center justify-center space-x-2">
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
        <a href="menu.php" class="btn-primary bg-warm-cream text-deep-brown px-6 py-3 rounded-lg font-baskerville hover:bg-warm-cream/50 transition-all duration-300 w-full flex items-center justify-center space-x-2">
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
        <a href="contact.php" class="btn-primary bg-warm-cream text-deep-brown px-6 py-3 rounded-lg font-baskerville hover:bg-warm-cream/50 transition-all duration-300 w-full flex items-center justify-center space-x-2">
            <span>Get Help</span>
            <i class="fas fa-headset ml-2"></i>
        </a>
    </div>
</section>

<!-- Upcoming Reservations -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-playfair text-2xl font-bold text-deep-brown">Upcoming Reservations</h3>
    </div>
    <div class="bg-card rounded-xl p-6 shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed" role="table">
                <thead>
                    <tr class="border-b border-deep-brown/20">
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown w-1/4" scope="col">Date & Time</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown w-1/4" scope="col">Event Type</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown w-1/4" scope="col">Guests</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown w-1/4" scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch accepted reservations from database that are in the future
                    try {
                        $current_datetime = date('Y-m-d H:i:s');
                        $stmt = $conn->prepare("SELECT 
                            booking_id, 
                            event, 
                            pax, 
                            reservation_datetime, 
                            booking_status 
                            FROM booking_tb 
                            WHERE customer_id = :customer_id 
                            AND booking_status = 'accepted'
                            AND reservation_datetime > :current_datetime
                            ORDER BY reservation_datetime ASC");
                        $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                        $stmt->bindParam(':current_datetime', $current_datetime);
                        $stmt->execute();
                        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($reservations) > 0) {
                            foreach ($reservations as $reservation) {
                                $reservation_datetime = new DateTime($reservation['reservation_datetime']);
                                $formatted_date = $reservation_datetime->format('F j, Y');
                                $formatted_time = $reservation_datetime->format('g:i A');
                                ?>
                                <tr class="border-b border-deep-brown/10 hover:bg-deep-brown/5 transition-colors duration-300">
                                    <td class="py-4 px-4 w-1/4">
                                        <div class="font-baskerville text-deep-brown"><?php echo htmlspecialchars($formatted_date); ?></div>
                                        <div class="text-sm text-deep-brown/60"><?php echo htmlspecialchars($formatted_time); ?></div>
                                    </td>
                                    <td class="py-4 px-4 font-baskerville text-deep-brown w-1/4"><?php echo htmlspecialchars($reservation['event']); ?></td>
                                    <td class="py-4 px-4 font-baskerville text-deep-brown w-1/4"><?php echo htmlspecialchars($reservation['pax']); ?></td>
                                    <td class="py-4 px-4 w-1/4">
                                        <span class="bg-warm-cream/50 text-deep-brown px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center border border-deep-brown/10">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Confirmed
                                        </span>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="py-8 text-center text-deep-brown/60 font-baskerville">
                                    No upcoming accepted reservations found.
                                </td>
                            </tr>
                            <?php
                        }
                    } catch(Exception $e) {
                        ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-red-500 font-baskerville">
                                Error loading reservations: <?php echo htmlspecialchars($e->getMessage()); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
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
    <div id="special-offers-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Special offers will be populated here -->
    </div>
</section>

<!-- Recent Activity -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-playfair text-2xl font-bold text-deep-brown">Recent Activity</h3>
    </div>
    
    <div class="bg-white/90 rounded-xl p-6 shadow-lg">
        <div class="space-y-4">
            <?php
            // Fetch recent notifications from database
            try {
                $stmt = $conn->prepare("SELECT message, created_at 
                                      FROM notifications_tb 
                                      WHERE user_id = :user_id 
                                      ORDER BY created_at DESC 
                                      LIMIT 3");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($notifications) > 0) {
                    foreach ($notifications as $notification) {
                        $created_at = new DateTime($notification['created_at']);
                        $formatted_time = $created_at->format('M j, g:i A');
                        ?>
                        <div class="flex items-start p-4 border-b border-deep-brown/10 last:border-0">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-rich-brown/10 flex items-center justify-center text-rich-brown mr-4">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-baskerville text-deep-brown"><?php echo htmlspecialchars($notification['message']); ?></p>
                                <p class="text-sm text-deep-brown/50 mt-1"><?php echo htmlspecialchars($formatted_time); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="p-4 text-center text-deep-brown/60 font-baskerville">
                        No recent activity found.
                    </div>
                    <?php
                }
            } catch(Exception $e) {
                ?>
                <div class="p-4 text-center text-red-500 font-baskerville">
                    Error loading recent activity: <?php echo htmlspecialchars($e->getMessage()); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<script>
    // Function to fetch and render special offers
async function fetchSpecialOffers() {
    try {
        const response = await fetch('customerindex/get_menu_packages.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const result = await response.json();
        
        if (result.status !== 'success') {
            throw new Error(result.message || 'Failed to fetch special offers');
        }
        
        const packages = result.data;
        const container = document.getElementById('special-offers-container');
        container.innerHTML = ''; // Clear existing content
        
        if (packages.length === 0) {
            container.innerHTML = '<p class="text-gray-500">No special offers available.</p>';
            return;
        }
        
        packages.forEach(package => {
            // Create card element
            const card = document.createElement('div');
            card.className = 'bg-card rounded-xl overflow-hidden shadow-md hover-lift group';
            
            // Format rating stars
            const rating = package.rating || 4.5; // Default rating if not provided
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            let starsHTML = '';
            for (let i = 0; i < fullStars; i++) {
                starsHTML += '<i class="fas fa-star text-yellow-500"></i>';
            }
            if (hasHalfStar) {
                starsHTML += '<i class="fas fa-star-half-alt text-yellow-500"></i>';
            }
            for (let i = fullStars + (hasHalfStar ? 1 : 0); i < 5; i++) {
                starsHTML += '<i class="far fa-star text-yellow-500"></i>';
            }
            
            // Set image path (adjust base path if needed)
            const imagePath = package.image_path ? `/Uploads/${package.image_path}` : '../images/default_offer.jpg';
            
            card.innerHTML = `
                <div class="relative">
                    <img src="${imagePath}" alt="${package.package_name}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute top-4 right-4 bg-${package.discount === '20% OFF' ? 'red-500' : 'deep-brown'} text-white px-3 py-1 rounded-full text-sm font-bold">
                        ${package.discount || 'SPECIAL'}
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="font-playfair text-xl font-bold mb-2 text-deep-brown">${package.package_name}</h4>
                    <p class="font-baskerville mb-4 text-deep-brown/80">${package.package_description || 'No description available'}</p>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="font-baskerville font-bold text-lg text-deep-brown">₱${parseFloat(package.price).toFixed(2)}/person</span>
                            <div class="flex items-center space-x-1">
                                ${starsHTML}
                                <span class="text-sm text-deep-brown/60 ml-1">(${rating.toFixed(1)})</span>
                            </div>
                        </div>
                        <a href="bookingpage.php?package_id=${package.package_id}" class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2 group">
                            <span>${package.type === 'buffet' ? 'Book Now' : 'Reserve Now'}</span>
                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            `;
            
            container.appendChild(card);
        });
    } catch (error) {
        console.error('Error fetching special offers:', error);
        const container = document.getElementById('special-offers-container');
        container.innerHTML = '<p class="text-red-500">Failed to load special offers. Please try again later.</p>';
    }
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', fetchSpecialOffers);
    </script>
<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>