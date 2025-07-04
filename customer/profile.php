<?php
require_once 'customer_auth.php'; 
require_once '../db_connect.php'; // Include PDO database connection

// Fetch user data from users_tb
$user_id = $_SESSION['user_id']; // Assuming customer_auth.php sets this
try {
    $query = "SELECT first_name, middle_name, last_name, suffix, username, contact_number, profile_picture 
              FROM users_tb 
              WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Construct full name for display
$fullName = ucwords(trim($user['first_name'] . ' ' . $user['last_name']));

// Set profile picture path
$profilePicture = $user['profile_picture'] ? '../images/profile_pictures/' . $user['profile_picture'] : 
    'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=E8E0D5&color=5D2F0F&bold=true&size=128';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-cream': '#E8E0D5',
                        'rich-brown': '#8B4513',
                        'deep-brown': '#5D2F0F',
                        'accent-brown': '#A0522D'
                    },
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'baskerville': ['Libre Baskerville', 'serif']
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        .hover-lift {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        .bg-card {
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #E8E0D5; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #8B4513; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #5D2F0F; }

        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }

        .tab-button.active {
            border-color: #8B4513;
            color: #5D2F0F;
            font-weight: 600;
        }

        input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
</head>
<body class="bg-warm-cream text-deep-brown min-h-screen font-baskerville">
    <!-- Navigation (unchanged) -->
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
                    <a href="#contact" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Contact
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                </div>
                <div class="flex-1 flex items-center justify-end">
                    <button class="md:hidden text-deep-brown hover:text-deep-brown/80 transition-colors duration-300" 
                            aria-label="Toggle menu"
                            id="mobile-menu-button">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div class="hidden md:flex items-center space-x-0">
                        <div class="relative group">
                            <button class="p-2 hover:bg-deep-brown/10 rounded-full transition-colors duration-300" 
                                    aria-label="Notifications"
                                    id="notifications-button">
                                <i class="fas fa-bell text-deep-brown"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div class="absolute right-0 mt-2 w-80 bg-card rounded-lg shadow-lg py-2 hidden group-hover:block border border-deep-brown/10 z-50">
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
                        <div class="relative group">
                            <a href="profile.php" class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                    aria-label="User menu"
                                    id="user-menu-button">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullName); ?>&background=E8E0D5&color=5D2F0F" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full border border-deep-brown/30">
                                <span class="font-baskerville"><?php echo htmlspecialchars($fullName); ?></span>
                                <i class="fas fa-chevron-down text-xs ml-2 transition-transform duration-300 group-hover:rotate-180"></i>
                            </a>
                            <div class="absolute right-0 mt-2 w-48 bg-warm-cream rounded-lg shadow-lg py-2 hidden group-hover:block border border-deep-brown/10 z-50 transition-all duration-300">
                                <a href="profile.php" class="flex items-center px-4 py-2 text-deep-brown hover:bg-rich-brown hover:text-warm-cream transition-colors duration-300">
                                    <i class="fas fa-user-circle w-5"></i>
                                    <span>Profile Settings</span>
                                </a>
                                <a href="#notifications" class="flex items-center px-4 py-2 text-deep-brown hover:bg-rich-brown hover:text-warm-cream transition-colors duration-300">
                                    <i class="fas fa-bell w-5"></i>
                                    <span>Notifications</span>
                                </a>
                                <hr class="my-2 border-deep-brown/20">
                                <a href="../logout.php" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-300">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <a href="#dashboard" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-home w-8"></i> Home
                        </a>
                        <a href="my_reservations.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-calendar-alt w-8"></i> My Reservations
                        </a>
                        <a href="menu.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-utensils w-8"></i> Menu
                        </a>
                        <a href="#contact" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
                            <i class="fas fa-envelope w-8"></i> Contact
                        </a>
                    </div>
                </nav>
                <div class="p-4 border-t border-warm-cream/10">
                    <a href="../logout.php" class="flex items-center text-red-400 hover:text-red-300 transition-colors duration-300">
                        <i class="fas fa-sign-out-alt w-8"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-10">
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-deep-brown">My Account</h2>
            <p class="font-baskerville mt-2 text-deep-brown/70">Manage your profile</p>
        </header>

        <!-- Notification Placeholder -->
        <div id="notification-area" class="relative"></div>

    <!-- Profile Information Tab -->
<div id="profile-content" class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture Card -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-warm-cream/20 to-white rounded-2xl p-6 shadow-lg text-center sticky top-28 transition-all duration-300 hover:shadow-xl">
                <div class="relative mx-auto w-40 h-40 group mb-5">
                    <img id="profile-image" src="<?php echo htmlspecialchars($profilePicture); ?>" 
                         alt="Profile" 
                         class="w-full h-full rounded-full border-4 border-white shadow-lg object-cover transition-transform duration-300 group-hover:scale-105">
                    <label for="avatar-upload" class="absolute inset-0 rounded-full bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <i class="fas fa-camera text-2xl text-white mb-1"></i>
                        <span class="text-white font-baskerville text-sm">Change Photo</span>
                        <input type="file" id="avatar-upload" class="hidden" accept="image/*">
                    </label>
                </div>
                
                <h4 class="font-playfair text-2xl font-bold text-deep-brown mb-1"><?php echo htmlspecialchars($fullName); ?></h4>
                <p class="font-baskerville text-sm text-deep-brown/70 mb-4">@<?php echo htmlspecialchars($user['username']); ?></p>
                
                <div class="bg-warm-cream/30 rounded-lg p-3">
                    <p class="font-baskerville text-xs text-deep-brown/80">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-gradient-to-br from-warm-cream/10 to-white rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown">Personal Information</h3>
                    <button type="button" id="edit-profile-btn" class="flex items-center text-accent-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        <span class="font-baskerville">Edit Profile</span>
                    </button>
                </div>

                <form id="profile-update-form" action="profileAPI/update_profile.php" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">First Name</label>
                            <div class="relative">
                                <input type="text" id="first-name" name="first_name"
                                       value="<?php echo ucwords(strtolower(htmlspecialchars($user['first_name']))); ?>"
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                                       disabled required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-user text-deep-brown/30"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Middle Name</label>
                            <input type="text" id="middle-name" name="middle_name"
                                   value="<?php echo ucwords(strtolower(htmlspecialchars($user['middle_name'] ?? ''))); ?>"
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                                   disabled>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Last Name</label>
                            <input type="text" id="last-name" name="last_name"
                                   value="<?php echo ucwords(strtolower(htmlspecialchars($user['last_name']))); ?>"
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                                   disabled required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Suffix</label>
                            <input type="text" id="suffix" name="suffix" value="<?php echo htmlspecialchars($user['suffix'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">Username</label>
                        <div class="relative">
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-at text-deep-brown/30"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">Phone Number</label>
                        <div class="relative">
                            <input type="tel" id="phone" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-phone text-deep-brown/30"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-warm-cream flex justify-end space-x-3">
                        <button type="button" id="cancel-edit-btn" class="hidden px-5 py-2.5 rounded-lg font-baskerville text-deep-brown hover:bg-warm-cream/50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="save-profile-btn" class="hidden bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="bg-gradient-to-br from-warm-cream/10 to-white rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Security Settings</h3>
                
                <form id="password-update-form" action="profileAPI/update_password.php" method="POST" class="space-y-6">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current-password" name="current_password" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">New Password</label>
                        <div class="relative">
                            <input type="password" id="new-password" name="new_password" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password"></i>
                            </div>
                        </div>
                        <p class="text-xs text-deep-brown/50 mt-1">Minimum 8 characters with at least one number</p>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="confirm_password" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-warm-cream text-right">
                        <button type="submit" class="bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </main>

    <!-- Footer (unchanged) -->
    <footer class="bg-deep-brown text-warm-cream relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-8 left-8 w-32 h-32 border border-warm-cream rounded-full"></div>
            <div class="absolute bottom-12 right-12 w-24 h-24 border border-warm-cream rounded-full"></div>
            <div class="absolute top-1/2 left-1/4 w-2 h-2 bg-warm-cream rounded-full"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-warm-cream rounded-full"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            <div class="py-2">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center space-x-3 mt-4 mb-4">
                        <div>
                            <h2 class="font-playfair font-bold text-2xl tracking-tight">Caffè Lilio</h2>
                            <p class="text-xs tracking-[0.2em] text-warm-cream/80 uppercase font-inter font-light">Ristorante</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
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
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Navigate
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <nav class="space-y-2 font-inter text-sm">
                            <a href="#about" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">About Us</a>
                            <a href="#menu" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Our Menu</a>
                            <a href="#reservations" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Reservations</a>
                            <a href="#contact" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Visit Us</a>
                        </nav>
                    </div>
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
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Connect
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            NProgress.start();
            
            // Profile picture upload functionality
            const avatarUpload = document.getElementById('avatar-upload');
            const profileImage = document.getElementById('profile-image');
            
            avatarUpload.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload a JPEG, PNG, GIF, or WEBP image.',
                confirmButtonColor: '#8B4513'
            });
            return;
        }
        
        // Validate file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please upload an image smaller than 2MB.',
                confirmButtonColor: '#8B4513'
            });
            return;
        }
        
        // Create a smaller preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Create a canvas to resize the image
                const canvas = document.createElement('canvas');
                const maxWidth = 300; // Reduced size for preview
                const maxHeight = 300;
                let width = img.width;
                let height = img.height;
                
                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }
                
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Use the resized image for preview
                const resizedDataUrl = canvas.toDataURL(file.type);
                profileImage.src = resizedDataUrl;
                
                // Ask for confirmation with the resized image
                Swal.fire({
                    title: 'Update Profile Picture?',
                    text: 'Are you sure you want to upload this image as your new profile picture?',
                    imageUrl: resizedDataUrl,
                    imageWidth: width,
                    imageHeight: height,
                    imageAlt: 'Profile picture preview',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#A0522D',
                    confirmButtonText: 'Yes, upload it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        uploadProfilePicture(file);
                    } else {
                        // Reset to original image if cancelled
                        profileImage.src = '<?php echo htmlspecialchars($profilePicture); ?>';
                        avatarUpload.value = '';
                    }
                });
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
            
            function uploadProfilePicture(file) {
                NProgress.start();
                const formData = new FormData();
                formData.append('profile_picture', file);
                formData.append('user_id', <?php echo $user_id; ?>);
                
                fetch('profileAPI/upload_profile_picture.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    NProgress.done();
                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Profile Picture Updated',
                            text: 'Your profile picture has been updated successfully!',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#E8E0D5',
                            color: '#5D2F0F'
                        });
                        
                        // Update profile picture in navigation
                        document.querySelector('#user-menu-button img').src = data.new_image_path + '?t=' + new Date().getTime();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to update profile picture.',
                            confirmButtonColor: '#8B4513'
                        });
                        // Revert to original image
                        profileImage.src = '<?php echo htmlspecialchars($profilePicture); ?>';
                    }
                    avatarUpload.value = '';
                })
                .catch(error => {
                    NProgress.done();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while uploading the profile picture.',
                        confirmButtonColor: '#8B4513'
                    });
                    // Revert to original image
                    profileImage.src = '<?php echo htmlspecialchars($profilePicture); ?>';
                    avatarUpload.value = '';
                });
            }


            // Toggle edit mode for profile form
            const editButton = document.getElementById('edit-profile-btn');
            const saveButton = document.getElementById('save-profile-btn');
            const profileForm = document.getElementById('profile-update-form');
            const profileFormInputs = document.querySelectorAll('#profile-update-form input');

            editButton.addEventListener('click', () => {
                // Enable all input fields
                profileFormInputs.forEach(input => input.disabled = false);
                // Hide Edit button, show Save button
                editButton.classList.add('hidden');
                saveButton.classList.remove('hidden');
            });

            // Profile form submission with SweetAlert confirmation
            profileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Confirm Changes',
                    text: 'Are you sure you want to save these changes to your profile?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#A0522D',
                    confirmButtonText: 'Yes, Save',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        NProgress.start();
                        const formData = new FormData(profileForm);
                        
                        fetch('profileAPI/update_profile.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            NProgress.done();
                            if (data.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Profile Updated',
                                    text: 'Your profile information has been updated successfully!',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    background: '#E8E0D5',
                                    color: '#5D2F0F'
                                });
                                // Disable inputs again
                                profileFormInputs.forEach(input => input.disabled = true);
                                // Show Edit button, hide Save button
                                editButton.classList.remove('hidden');
                                saveButton.classList.add('hidden');
                                // Update displayed username and full name
                                document.querySelector('.font-baskerville.text-sm.text-deep-brown\\/70').textContent = formData.get('username');
                                const fullName = `${formData.get('first_name')} ${formData.get('middle_name') ? formData.get('middle_name') + ' ' : ''}${formData.get('last_name')} ${formData.get('suffix') || ''}`.trim();
                                document.querySelector('.font-playfair.text-xl.font-bold').textContent = fullName;
                                document.querySelector('#user-menu-button img').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=E8E0D5&color=5D2F0F`;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to update profile.',
                                    confirmButtonColor: '#8B4513'
                                });
                            }
                        })
                        .catch(error => {
                            NProgress.done();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while updating the profile.',
                                confirmButtonColor: '#8B4513'
                            });
                        });
                    }
                });
            });

            // Password form submission
            document.getElementById('password-update-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const newPassword = document.getElementById('new-password').value;
                const confirmPassword = document.getElementById('confirm-password').value;

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'New passwords do not match.',
                        confirmButtonColor: '#8B4513'
                    });
                    return;
                }

                NProgress.start();
                const formData = new FormData(e.target);
                Swal.fire({
                    title: 'Confirm Password Change',
                    text: 'Are you sure you want to update your password?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#A0522D',
                    confirmButtonText: 'Yes, Update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('profileAPI/update_password.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            NProgress.done();
                            if (data.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Password Updated',
                                    text: 'Your password has been updated successfully!',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    background: '#E8E0D5',
                                    color: '#5D2F0F'
                                });
                                document.getElementById('password-update-form').reset();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to update password.',
                                    confirmButtonColor: '#8B4513'
                                });
                            }
                        })
                        .catch(error => {
                            NProgress.done();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while updating the password.',
                                confirmButtonColor: '#8B4513'
                            });
                        });
                    }
                });
            });

            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            closeMobileMenu.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });

            // User menu dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.querySelector('#user-menu-button + .absolute');
            const menuChevron = userMenuButton.querySelector('i.fas.fa-chevron-down');

            userMenuButton.addEventListener('click', () => {
                const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';
                userMenuButton.setAttribute('aria-expanded', !isExpanded);
                userMenu.classList.toggle('hidden');
                userMenu.classList.toggle('opacity-0');
                userMenu.classList.toggle('scale-95');
                menuChevron.classList.toggle('rotate-180');
            });

            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenuButton.setAttribute('aria-expanded', 'false');
                    userMenu.classList.add('hidden', 'opacity-0', 'scale-95');
                    menuChevron.classList.remove('rotate-180');
                }
            });

            tippy('[data-tippy-content]', {
                animation: 'scale-subtle',
                theme: 'light-border',
            });

            window.onload = function() {
                NProgress.done();
            };
        });
    </script>
</body>
</html>