<?php
require_once '../db_connect.php';

try {
    // Query to fetch dishes with dish_category = 'best_seller' and their ingredients
    $stmt = $conn->prepare("
        SELECT 
            d.dish_name,
            d.dish_description,
            d.price,
            d.dish_pic_url,
            GROUP_CONCAT(i.ingredient_name ORDER BY i.ingredient_name SEPARATOR ', ') AS ingredients
        FROM best_seller_tb bs
        JOIN dishes_tb d ON bs.dish_id = d.dish_id
        LEFT JOIN dish_ingredients di ON d.dish_id = di.dish_id
        LEFT JOIN ingredients_tb i ON di.ingredient_id = i.ingredient_id
        WHERE bs.status = 'show' AND d.status = 'active'
        GROUP BY d.dish_id, d.dish_name, d.dish_description, d.price, d.dish_pic_url
    ");
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Fetch user details for the nav (assuming $conn and $user_id are available from the including file)
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name, profile_picture FROM users_tb WHERE id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get unread notifications count
$notificationStmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM notifications_tb WHERE user_id = :user_id AND is_read = FALSE");
$notificationStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$notificationStmt->execute();
$notificationCount = $notificationStmt->fetch(PDO::FETCH_ASSOC)['unread_count'];

// Construct full name for fallback avatar
$fullName = ucwords(trim($user['first_name'] . ' ' . $user['last_name']));

// Set profile picture with a fallback
$profilePicture = $user['profile_picture'] ? '../images/profile_pictures/' . htmlspecialchars($user['profile_picture']) : 
    'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=E8E0D5&color=5D2F0F&bold=true&size=128';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best-Seller Menu - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 90%;
            width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            cursor: pointer;
            font-size: 24px;
            color: #3C2F2F;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="smooth-scroll bg-warm-cream text-deep-brown">
    <!-- Navigation -->
    <nav class="bg-warm-cream text-deep-brown shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex-1 flex items-center justify-start">
                <a href="/" class="flex items-center space-x-2 hover:opacity-90 transition-opacity" aria-label="Home">
                    <div>
                        <h1 class="font-playfair font-bold text-xl text-deep-brown">Caffè Lilio</h1>
                        <p class="text-xs tracking-widest text-deep-brown/90">RISTORANTE</p>
                    </div>
                </a>
            </div>
            <!-- Desktop Navigation -->
            <div class="hidden md:flex flex-1 justify-center space-x-8">
                <a href="customerindex.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                    Home
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="my_reservations.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                    My Reservations
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="menu.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                    Menu
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="contact.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                    Contact
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
            </div>
            <div class="flex-1 flex items-center justify-end">
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-deep-brown hover:text-deep-brown/80 transition-colors duration-300" 
                        aria-label="Toggle menu"
                        id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

                <div class="hidden md:flex items-center space-x-0">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 hover:bg-deep-brown/10 rounded-full transition-colors duration-300" 
                                aria-label="Notifications"
                                id="notifications-button">
                            <i class="fas fa-bell text-deep-brown"></i>
                            <?php if ($notificationCount > 0): ?>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full notification-badge"></span>
                            <?php endif; ?>
                        </button>
                        <div class="absolute right-0 mt-2 w-80 bg-card rounded-lg shadow-lg py-2 hidden border border-deep-brown/10 z-50" id="notifications-dropdown">
                            <div class="px-4 py-2 border-b border-deep-brown/10">
                                <h3 class="font-playfair font-bold text-deep-brown">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto" id="notifications-list">
                                <div class="animate-pulse p-4">
                                    <div class="skeleton-text w-3/4"></div>
                                    <div class="skeleton-text w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="relative">
                        <!-- Navigation Button with Profile Picture -->
                        <button class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                aria-label="User menu"
                                id="user-menu-button">
                            <img src="<?php echo htmlspecialchars($profilePicture); ?>" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-full border border-deep-brown/30 object-cover"
                                 id="nav-profile-image">
                            <span class="font-baskerville"><?php echo htmlspecialchars(ucfirst($user['first_name'])) . ' ' . htmlspecialchars(ucfirst($user['last_name'])); ?></span>
                            <i class="fas fa-chevron-down text-xs ml-2 transition-transform duration-300" id="chevron-icon"></i>
                        </button>

                        <div class="absolute right-0 mt-2 w-48 bg-warm-cream rounded-lg shadow-lg py-2 hidden border border-deep-brown/10 z-50 transition-all duration-300" id="user-dropdown">
                            <a href="profile.php" class="flex items-center px-4 py-2 text-deep-brown hover:bg-rich-brown hover:text-warm-cream transition-colors duration-300">
                                <i class="fas fa-user-circle w-5"></i>
                                <span>Profile Settings</span>
                            </a>
                            <a href="#notifications" class="flex items-center px-4 py-2 text-deep-brown hover:bg-rich-brown hover:text-warm-cream transition-colors duration-300">
                                <i class="fas fa-bell w-5"></i>
                                <span>Notifications</span>
                            </a>
                            <hr class="my-2 border-deep-brown/20">
                            <a href="../logout.php?usertype=customer" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-300">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden mobile-menu fixed inset-0 bg-warm-cream/95 z-40 hidden" id="mobile-menu">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-center p-4 border-b border-deep-brown/10">
                    <h2 class="font-playfair text-xl text-deep-brown">Menu</h2>
                    <button class="text-deep-brown hover:text-deep-brown/80 transition-colors duration-300" 
                            aria-label="Close menu"
                            id="close-mobile-menu">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto p-4">
                    <div class="space-y-4">
                        <!-- User Profile Section -->
                        <div class="flex items-center space-x-3 p-2 border-b border-deep-brown/10">
                            <img src="<?php echo htmlspecialchars($profilePicture); ?>" 
                                 alt="Profile" 
                                 class="w-10 h-10 rounded-full border border-deep-brown/30 object-cover"
                                 id="mobile-nav-profile-image">
                            <div>
                                <p class="font-baskerville text-deep-brown"><?php echo htmlspecialchars(ucfirst($user['first_name'])) . ' ' . htmlspecialchars(ucfirst($user['last_name'])); ?></p>
                                <button class="flex items-center text-deep-brown hover:text-deep-brown/80 text-sm font-baskerville mt-1" 
                                        id="mobile-user-menu-button">
                                    Account Options
                                    <i class="fas fa-chevron-down text-xs ml-2 transition-transform duration-300" id="mobile-chevron-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="pl-4 space-y-2 hidden" id="mobile-user-dropdown">
                            <a href="profile.php" class="flex items-center text-deep-brown hover:text-deep-brown/80 transition-colors duration-300">
                                <i class="fas fa-user-circle w-8"></i> Profile Settings
                            </a>
                            <a href="#notifications" class="flex items-center text-deep-brown hover:text-deep-brown/80 transition-colors duration-300">
                                <i class="fas fa-bell w-8"></i> Notifications
                            </a>
                            <a href="../logout.php?usertype=customer" class="flex items-center text-red-400 hover:text-red-300 transition-colors duration-300">
                                <i class="fas fa-sign-out-alt w-8"></i> Logout
                            </a>
                        </div>
                        <!-- Navigation Links -->
                        <a href="customerindex.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-home w-8"></i> Home
                        </a>
                        <a href="my_reservations.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-calendar-alt w-8"></i> My Reservations
                        </a>
                        <a href="menu.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-utensils w-8"></i> Menu
                        </a>
                        <a href="contact.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-envelope w-8"></i> Contact
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <script>
        
        // Load notifications when dropdown is opened
        document.getElementById('notifications-button').addEventListener('click', function() {
            loadNotifications();
        });

        function loadNotifications() {
            const notificationsContainer = document.getElementById('notifications-list');
            
            // Show loading state
            notificationsContainer.innerHTML = `
                <div class="animate-pulse p-4">
                    <div class="skeleton-text w-3/4"></div>
                    <div class="skeleton-text w-1/2"></div>
                </div>
            `;
            
            // Fetch notifications via AJAX
            fetch('get_notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let html = '';
                        data.forEach(notification => {
                            html += `
                                <div class="p-4 border-b border-deep-brown/10 ${notification.is_read ? '' : 'bg-warm-cream/50'}">
                                    <p class="font-baskerville text-deep-brown">${notification.message}</p>
                                    <p class="text-sm text-deep-brown/60">${notification.time_ago}</p>
                                </div>
                            `;
                        });
                        notificationsContainer.innerHTML = html;
                        
                        // Mark notifications as read
                        markNotificationsAsRead();
                    } else {
                        notificationsContainer.innerHTML = '<div class="p-4 text-center text-deep-brown/60">No new notifications</div>';
                    }
                })
                .catch(error => {
                    notificationsContainer.innerHTML = '<div class="p-4 text-center text-red-500">Error loading notifications</div>';
                    console.error('Error:', error);
                });
        }

        function markNotificationsAsRead() {
            fetch('mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove notification badge
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        badge.remove();
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

                
                // Mobile menu toggle
                document.getElementById('mobile-menu-button').addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.toggle('hidden');
                });

                document.getElementById('close-mobile-menu').addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.add('hidden');
                    // Reset mobile user dropdown when closing menu
                    document.getElementById('mobile-user-dropdown').classList.add('hidden');
                    document.getElementById('mobile-chevron-icon').classList.remove('rotate-180');
                });

                // User dropdown toggle (desktop)
                document.getElementById('user-menu-button').addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = document.getElementById('user-dropdown');
                    const chevron = document.getElementById('chevron-icon');
                    const isHidden = dropdown.classList.contains('hidden');
                    
                    // Close notifications dropdown if open
                    document.getElementById('notifications-dropdown').classList.add('hidden');
                    
                    dropdown.classList.toggle('hidden', !isHidden);
                    chevron.classList.toggle('rotate-180', isHidden);
                });

                // User dropdown toggle (mobile)
                document.getElementById('mobile-user-menu-button').addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = document.getElementById('mobile-user-dropdown');
                    const chevron = document.getElementById('mobile-chevron-icon');
                    const isHidden = dropdown.classList.contains('hidden');
                    
                    dropdown.classList.toggle('hidden', !isHidden);
                    chevron.classList.toggle('rotate-180', isHidden);
                });

                // Notifications dropdown toggle
                document.getElementById('notifications-button').addEventListener('click', function() {
                    const dropdown = document.getElementById('notifications-dropdown');
                    const isHidden = dropdown.classList.contains('hidden');
                    
                    // Close user dropdown if open
                    document.getElementById('user-dropdown').classList.add('hidden');
                    document.getElementById('chevron-icon').classList.remove('rotate-180');
                    
                    dropdown.classList.toggle('hidden', !isHidden);
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', function(event) {
                    const userButton = document.getElementById('user-menu-button');
                    const mobileUserButton = document.getElementById('mobile-user-menu-button');
                    const notificationsButton = document.getElementById('notifications-button');
                    const userDropdown = document.getElementById('user-dropdown');
                    const mobileUserDropdown = document.getElementById('mobile-user-dropdown');
                    const notificationsDropdown = document.getElementById('notifications-dropdown');

                    if (!userButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                        document.getElementById('chevron-icon').classList.remove('rotate-180');
                    }

                    if (!mobileUserButton.contains(event.target) && !mobileUserDropdown.contains(event.target)) {
                        mobileUserDropdown.classList.add('hidden');
                        document.getElementById('mobile-chevron-icon').classList.remove('rotate-180');
                    }

                    if (!notificationsButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
                        notificationsDropdown.classList.add('hidden');
                    }
                });
    </script>
</nav>

    <section id="best-sellers" class="pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-extrabold text-deep-brown mb-4 tracking-tight">Best Sellers</h1>
                <div class="w-32 h-1 bg-gradient-to-r from-amber-600 to-amber-800 mx-auto mb-6"></div>
                <p class="font-baskerville text-lg sm:text-xl text-rich-brown/80 max-w-3xl mx-auto leading-relaxed">
                    Discover our most popular dishes, loved by our guests for their authentic flavors and quality ingredients.
                </p>
            </div>

            <div id="menu-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 auto-rows-fr">
                <?php foreach ($dishes as $dish): ?>
                    <div class="menu-card hover:scale-[1.02] transition-transform duration-200 col-span-1" 
                        data-ingredients="<?php echo htmlspecialchars($dish['ingredients'] ?? 'No ingredients listed'); ?>" 
                        data-description="<?php echo htmlspecialchars($dish['dish_description']); ?>">
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100 h-full flex flex-col">
                            <img src="<?php echo htmlspecialchars($dish['dish_pic_url']); ?>" alt="<?php echo htmlspecialchars($dish['dish_name']); ?>" class="w-full h-48 object-cover">
                            <div class="p-4 flex flex-col">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($dish['dish_name']); ?></h3>
                                    <span class="font-medium text-amber-600">₱<?php echo number_format($dish['price'], 2); ?></span>
                                </div>
                                <button class="view-ingredients mt-3 self-start text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors underline underline-offset-4">
                                    View details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="menu-modal" class="modal">
        <div class="modal-content font-baskerville">
            <span class="modal-close">×</span>
            <h3 id="modal-title" class="font-playfair text-xl sm:text-2xl font-bold text-gray-900 mb-4"></h3>
            <h4 class="text-lg font-semibold text-amber-600 mb-2">Description</h4>
            <p id="modal-description" class="text-sm text-gray-600 leading-relaxed mb-4"></p>
            <h4 class="text-lg font-semibold text-amber-600 mb-2">Ingredients</h4>
            <p id="modal-ingredients" class="text-sm text-gray-600 leading-relaxed"></p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-deep-brown text-warm-cream py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Logo and Tagline -->
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div>
                        <h3 class="font-playfair font-bold text-xl sm:text-2xl">Caffè Lilio</h3>
                        <p class="text-xs sm:text-sm tracking-widest opacity-75">RISTORANTE</p>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="flex justify-center space-x-6 sm:space-x-8 mb-8">
                    <a href="https://web.facebook.com/caffelilio.ph" target="_blank" class="text-warm-cream hover:text-rich-brown transition-colors duration-300 focus:outline-none">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/caffelilio.ph/" target="_blank" class="text-warm-cream hover:text-rich-brown transition-colors duration-300 focus:outline-none">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465.66.255 1.216.567 1.772 1.123.556.556.868 1.112 1.123 1.772.247.636.416 1.363.465 2.427.048 1.024.06 1.379.06 3.808 0 2.43-.013 2.784-.06 3.808-.049 1.064-.218 1.791-.465 2.427-.255.66-.567 1.216-1.123 1.772-.556.556-1.112.868-1.772 1.123-.636.247-1.363.416-2.427.465-1.024.048-1.379.06-3.808.06-2.43 0-2.784-.013-3.808-.06-1.064-.049-1.791-.218-2.427-.465-.66-.255-1.216-.567-1.772-1.123-.556-.556-.868-1.112-1.123-1.772-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.379-.06-3.808 0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427.255-.66.567-1.216 1.123-1.772.556-.556 1.112-.868 1.772-1.123.636-.247 1.363-.416 2.427-.465 1.024-.048 1.379-.06 3.808-.06zm0-1.315c-2.486 0-2.847.013-3.846.06-1.07.05-1.791.222-2.423.475-.662.262-1.223.582-1.785 1.144-.562.562-.882 1.123-1.144 1.785-.253.632-.425 1.353-.475 2.423-.047.999-.06 1.36-.06 3.846s.013 2.847.06 3.846c.05 1.07.222 1.791.475 2.423.262.662.582 1.223 1.144 1.785.562.562 1.123.882 1.785 1.144.632.253 1.353.425 2.423.475.999.047 1.36.06 3.846.06s2.847-.013 3.846-.06c1.07-.05 1.791-.222 2.423-.475.662-.262 1.223-.582 1.785-1.144.562-.562.882-1.123 1.144-1.785.253-.632.425-1.353.475-2.423.047-.999.06-1.36.06-3.846s-.013-2.847-.06-3.846c-.05-1.07-.222-1.791-.475-2.423-.262-.662-.582-1.223-1.144-1.785-.562-.562-1.123-.882-1.785-1.144-.632-.253-1.353-.425-2.423-.475-1.024-.047-1.379-.06-3.846-.06zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.441s.645 1.441 1.441 1.441 1.441-.645 1.441-1.441-.645-1.441-1.441-1.441z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <!-- Copyright and Tagline -->
                <div class="border-t border-rich-brown pt-6 sm:pt-8">
                    <p class="font-baskerville text-sm sm:text-base opacity-75">
                        © 2025 Caffè Lilio Ristorante. All rights reserved. | 
                        <span class="italic">Authentically Italian and Spanish since 2021</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 0) {
                navbar.classList.add('backdrop-blur-md', 'bg-[#FFF8E7]/90', 'shadow-lg');
            } else {
                navbar.classList.remove('backdrop-blur-md', 'bg-[#FFF8E7]/90', 'shadow-lg');
            }
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Modal functionality
        const modal = document.getElementById('menu-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalIngredients = document.getElementById('modal-ingredients');
        const modalDescription = document.getElementById('modal-description');
        const closeModal = document.querySelector('.modal-close');

        document.querySelectorAll('.view-ingredients').forEach(button => {
            button.addEventListener('click', () => {
                const card = button.closest('.menu-card');
                const dishName = card.querySelector('h3').textContent;
                const ingredients = card.getAttribute('data-ingredients');
                const description = card.getAttribute('data-description');
                modalTitle.textContent = dishName;
                modalIngredients.textContent = ingredients;
                modalDescription.textContent = description;
                modal.classList.add('active');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.classList.remove('active');
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                modal.classList.remove('active');
            }
        });

        // Adjust card widths
        function adjustCardWidths() {
            const grid = document.getElementById('menu-grid');
            const cards = Array.from(grid.querySelectorAll('.menu-card'));
            const breakpoints = {
                sm: { cols: 2, minWidth: 640 },
                lg: { cols: 4, minWidth: 1024 }
            };

            const windowWidth = window.innerWidth;
            let colsPerRow = windowWidth < 640 ? 2 : 1;
            if (windowWidth >= breakpoints.lg.minWidth) {
                colsPerRow = breakpoints.lg.cols;
            } else if (windowWidth >= breakpoints.sm.minWidth) {
                colsPerRow = breakpoints.sm.cols;
            }

            cards.forEach(card => {
                card.classList.remove('col-span-1', 'col-span-2', 'sm:col-span-1', 'sm:col-span-2', 'lg:col-span-1', 'lg:col-span-2');
                card.classList.add('col-span-1');
            });

            for (let i = 0; i < cards.length; i += colsPerRow) {
                const rowCards = cards.slice(i, i + colsPerRow);
                if (rowCards.length < colsPerRow && rowCards.length > 0) {
                    if (colsPerRow === breakpoints.lg.cols && rowCards.length === 3) {
                        rowCards.forEach((card, index) => {
                            if (index < 2) {
                                card.classList.add('lg:col-span-1');
                            } else {
                                card.classList.add('lg:col-span-2');
                            }
                        });
                    } else if (colsPerRow === breakpoints.lg.cols && rowCards.length === 2) {
                        rowCards.forEach(card => card.classList.add('lg:col-span-2'));
                    } else if (colsPerRow === breakpoints.lg.cols && rowCards.length === 1) {
                        rowCards[0].classList.add('lg:col-span-2');
                    } else if (colsPerRow === breakpoints.sm.cols && rowCards.length === 1) {
                        rowCards[0].classList.add('sm:col-span-2');
                    } else if (colsPerRow === 2 && rowCards.length === 1 && windowWidth < 640) {
                        rowCards[0].classList.add('col-span-2');
                    } else {
                        rowCards.forEach(card => card.classList.add(colsPerRow === breakpoints.lg.cols ? 'lg:col-span-1' : 'col-span-1'));
                    }
                } else {
                    rowCards.forEach(card => card.classList.add(colsPerRow === breakpoints.lg.cols ? 'lg:col-span-1' : 'col-span-1'));
                }
            }
        }

        window.addEventListener('load', adjustCardWidths);
        window.addEventListener('resize', adjustCardWidths);
    </script>
</body>
</html>