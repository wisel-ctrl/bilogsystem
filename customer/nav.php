<?php
// Fetch user details for the nav (assuming $conn and $user_id are available from the including file)
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name FROM users_tb WHERE id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

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
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="absolute right-0 mt-2 w-80 bg-card rounded-lg shadow-lg py-2 hidden border border-deep-brown/10 z-50" id="notifications-dropdown">
                            <div class="px-4 py-2 border-b border-deep-brown/10">
                                <h3 class="font-playfair font-bold text-deep-brown">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div class="animate-pulse p-4">
                                    <div class="skeleton-text w-3/4"></div>
                                    <div class="skeleton-text w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                aria-label="User menu"
                                id="user-menu-button">
                            <img src="https://ui-avatars.com/api/?name=<?php echo substr($user['first_name'], 0, 1) . '+' . $user['last_name']; ?>&background=E8E0D5&color=5D2F0F" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-full border border-deep-brown/30">
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
 |                    <div class="space-y-4 Sex                    <a href="customerindex.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
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
                <div class="p-4 border-t border-warm-cream/10">
                    <a href="../logout.php?usertype=customer" class="flex items-center text-red-400 hover:text-red-300 transition-colors duration-300">
                        <i class="fas fa-sign-out-alt w-8"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        document.getElementById('close-mobile-menu').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('hidden');
        });

        // User dropdown toggle
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
            const notificationsButton = document.getElementById('notifications-button');
            const userDropdown = document.getElementById('user-dropdown');
            const notificationsDropdown = document.getElementById('notifications-dropdown');

            if (!userButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
                document.getElementById('chevron-icon').classList.remove('rotate-180');
            }

            if (!notificationsButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
                notificationsDropdown.classList.add('hidden');
            }
        });
    </script>
</nav>