<?php
require_once 'customer_auth.php'; 


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

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-confirmed { background-color: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }
        .status-pending { background-color: #FEF3C7; color: #92400E; border: 1px solid #FBBF24; }
        .status-completed { background-color: #DBEAFE; color: #1E40AF; border: 1px solid #93C5FD; }
        .status-cancelled { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }

    </style>
</head>
<body class="bg-warm-cream text-deep-brown min-h-screen font-baskerville">
    <!-- Navigation -->
    <nav class="bg-warm-cream/80 text-deep-brown shadow-lg sticky top-0 z-50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <div class="flex-shrink-0">
                    <a href="customerindex.php" class="flex items-center space-x-2 hover:opacity-90 transition-opacity" aria-label="Home">
                        <div>
                            <h1 class="font-playfair font-bold text-xl text-deep-brown">Caffè Lilio</h1>
                            <p class="text-xs tracking-widest text-deep-brown/90">RISTORANTE</p>
                        </div>
                    </a>
                </div>
                <div class="hidden md:flex flex-1 justify-center items-center space-x-8">
                    <a href="customerindex.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group py-2">
                        Home
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                </div>
                <div class="flex-shrink-0 flex items-center justify-end">
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 rounded-full p-1 transition-all duration-300 hover:bg-deep-brown/10" aria-label="User menu" aria-haspopup="true" aria-expanded="false">
                             <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullName); ?>&background=E8E0D5&color=5D2F0F&bold=true" 
                                 alt="Profile" 
                                 class="w-9 h-9 rounded-full border-2 border-deep-brown/30">
                            <span class="hidden lg:inline font-baskerville text-sm font-medium"><?php echo htmlspecialchars($fullName); ?></span>
                            <i id="menu-chevron" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                        </button>
                        
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-warm-cream rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none origin-top-right transition-all duration-300 transform opacity-0 scale-95 hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                            <a href="profile.php" class="block px-4 py-2 text-sm font-baskerville text-deep-brown bg-deep-brown/10" role="menuitem">My Profile</a>
                            <a href="../logout.php" class="block px-4 py-2 text-sm font-baskerville text-deep-brown hover:bg-deep-brown/10" role="menuitem">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-10">
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-deep-brown">My Account</h2>
            <p class="font-baskerville mt-2 text-deep-brown/70">Manage your profile, password, and view your booking history.</p>
        </header>

        <!-- Notification Placeholder -->
        <div id="notification-area"></div>

        <!-- Tab Navigation -->
        <div class="mb-8 border-b border-deep-brown/20">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button id="profile-tab" class="tab-button active py-4 px-1 border-b-2 font-medium text-lg leading-5 transition-colors duration-300" data-tab="profile">
                    Profile Settings
                </button>
                <button id="bookings-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-lg leading-5 text-deep-brown/60 hover:text-deep-brown transition-colors duration-300" data-tab="bookings">
                    My Bookings
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- Profile Information Tab -->
            <div id="profile-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-1">
                        <div class="bg-card rounded-xl p-6 shadow-md text-center sticky top-28">
                            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-4">Profile Picture</h3>
                             <div class="relative inline-block group mb-4">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullName); ?>&background=E8E0D5&color=5D2F0F&bold=true&size=128" 
                                     alt="Profile" 
                                     class="w-32 h-32 rounded-full border-4 border-white shadow-lg mx-auto">
                                <div class="absolute inset-0 rounded-full bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <label for="avatar-upload" class="text-white font-baskerville cursor-pointer text-center">
                                        <i class="fas fa-camera fa-2x"></i>
                                        <span class="block mt-1 text-sm">Change</span>
                                    </label>
                                    <input type="file" id="avatar-upload" class="hidden" accept="image/*">
                                </div>
                            </div>
                            <h4 class="font-playfair text-xl font-bold text-deep-brown"><?php echo htmlspecialchars($fullName); ?></h4>
                            <p class="font-baskerville text-sm text-deep-brown/70"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-card rounded-xl p-6 shadow-md">
                            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Personal Information</h3>
                            <form id="profile-update-form" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first-name" class="block text-sm font-medium text-deep-brown/80 mb-1">First Name</label>
                                        <input type="text" id="first-name" value="<?php echo htmlspecialchars($user['firstname']); ?>" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                    </div>
                                    <div>
                                        <label for="last-name" class="block text-sm font-medium text-deep-brown/80 mb-1">Last Name</label>
                                        <input type="text" id="last-name" value="<?php echo htmlspecialchars($user['lastname']); ?>" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                    </div>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-deep-brown/80 mb-1">Email Address</label>
                                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-deep-brown/80 mb-1">Phone Number</label>
                                    <input type="tel" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div class="pt-4 border-t border-deep-brown/10 text-right">
                                    <button type="submit" class="bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-card rounded-xl p-6 shadow-md">
                            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Change Password</h3>
                            <form id="password-update-form" class="space-y-6">
                                <div>
                                    <label for="current-password" class="block text-sm font-medium text-deep-brown/80 mb-1">Current Password</label>
                                    <input type="password" id="current-password" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown" required>
                                </div>
                                <div>
                                    <label for="new-password" class="block text-sm font-medium text-deep-brown/80 mb-1">New Password</label>
                                    <input type="password" id="new-password" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown" required>
                                </div>
                                <div>
                                    <label for="confirm-password" class="block text-sm font-medium text-deep-brown/80 mb-1">Confirm New Password</label>
                                    <input type="password" id="confirm-password" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown" required>
                                </div>
                                <div class="pt-4 border-t border-deep-brown/10 text-right">
                                    <button type="submit" class="bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Bookings Tab -->
            <div id="bookings-content" class="hidden">
                <div class="bg-card rounded-xl p-6 shadow-md">
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Your Bookings</h3>
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full min-w-[640px]" role="table">
                            <thead>
                                <tr class="border-b border-deep-brown/20">
                                    <th class="text-left py-3 px-4 font-semibold text-deep-brown" scope="col">Date & Time</th>
                                    <th class="text-left py-3 px-4 font-semibold text-deep-brown" scope="col">Event Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-deep-brown" scope="col">Guests</th>
                                    <th class="text-left py-3 px-4 font-semibold text-deep-brown" scope="col">Status</th>
                                    <th class="text-right py-3 px-4 font-semibold text-deep-brown" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-deep-brown/10">
                                <?php foreach ($bookings as $booking): ?>
                                <tr class="hover:bg-deep-brown/5 transition-colors duration-300">
                                    <td class="py-4 px-4">
                                        <div class="font-medium text-deep-brown"><?php echo date('F j, Y', strtotime($booking['date'])); ?></div>
                                        <div class="text-sm text-deep-brown/60"><?php echo date('g:i A', strtotime($booking['date'])); ?></div>
                                    </td>
                                    <td class="py-4 px-4 text-deep-brown"><?php echo htmlspecialchars($booking['type']); ?></td>
                                    <td class="py-4 px-4 text-deep-brown"><?php echo $booking['guests']; ?></td>
                                    <td class="py-4 px-4">
                                        <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                            <?php echo htmlspecialchars($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="flex space-x-2 justify-end">
                                            <button data-tippy-content="Edit Booking" class="text-deep-brown hover:text-rich-brown transition-colors duration-300 p-2 rounded-full hover:bg-warm-cream"><i class="fas fa-edit"></i></button>
                                            <button data-tippy-content="Cancel Booking" class="text-red-600 hover:text-red-800 transition-colors duration-300 p-2 rounded-full hover:bg-warm-cream"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-4">
                        <?php foreach ($bookings as $booking): ?>
                        <div class="bg-warm-cream/50 p-4 rounded-lg border border-deep-brown/10 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-deep-brown"><?php echo htmlspecialchars($booking['type']); ?></p>
                                    <p class="text-sm text-deep-brown/60"><?php echo date('F j, Y', strtotime($booking['date'])); ?> at <?php echo date('g:i A', strtotime($booking['date'])); ?></p>
                                </div>
                                <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo htmlspecialchars($booking['status']); ?>
                                </span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-deep-brown/10 flex justify-between items-center">
                                <p class="text-sm text-deep-brown"><?php echo $booking['guests']; ?> Guests</p>
                                <div class="flex space-x-2">
                                    <button data-tippy-content="Edit Booking" class="text-deep-brown hover:text-rich-brown transition-colors duration-300 p-2 rounded-full hover:bg-deep-brown/10"><i class="fas fa-edit"></i></button>
                                    <button data-tippy-content="Cancel Booking" class="text-red-600 hover:text-red-800 transition-colors duration-300 p-2 rounded-full hover:bg-red-50"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-deep-brown text-warm-cream mt-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6">
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
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            NProgress.start();

            // Tab functionality
            const tabs = document.querySelectorAll('.tab-button');
            const contents = {
                profile: document.getElementById('profile-content'),
                bookings: document.getElementById('bookings-content'),
            };

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tab;

                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    for (const content in contents) {
                        contents[content].classList.toggle('hidden', content !== target);
                    }
                });
            });

            // User menu dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            const menuChevron = document.getElementById('menu-chevron');

            userMenuButton.addEventListener('click', () => {
                const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';
                userMenuButton.setAttribute('aria-expanded', !isExpanded);
                userMenu.classList.toggle('hidden');
                userMenu.classList.toggle('opacity-0');
                userMenu.classList.toggle('scale-95');
                menuChevron.classList.toggle('rotate-180');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenuButton.setAttribute('aria-expanded', 'false');
                    userMenu.classList.add('hidden', 'opacity-0', 'scale-95');
                    menuChevron.classList.remove('rotate-180');
                }
            });

            // Tooltips
            tippy('[data-tippy-content]', {
                animation: 'scale-subtle',
                theme: 'light-border',
            });

            // Fake form submission feedback
            const showNotification = (message, type = 'success') => {
                const bgColor = type === 'success' ? 'bg-green-100 border-green-200' : 'bg-red-100 border-red-200';
                const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';
                
                const notification = `
                    <div class="mb-8 p-4 rounded-lg ${bgColor} ${textColor} border flex justify-between items-center animate-fade-in-down">
                        <p>${message}</p>
                        <button onclick="this.parentElement.remove()" class="text-xl">&times;</button>
                    </div>
                `;
                document.getElementById('notification-area').innerHTML = notification;
            };

            document.getElementById('profile-update-form').addEventListener('submit', (e) => {
                e.preventDefault();
                NProgress.start();
                setTimeout(() => {
                    showNotification('Profile information updated successfully!');
                    NProgress.done();
                }, 1000);
            });
            
            document.getElementById('password-update-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const newPassword = document.getElementById('new-password').value;
                const confirmPassword = document.getElementById('confirm-password').value;

                if (newPassword !== confirmPassword) {
                    showNotification('New passwords do not match.', 'error');
                    return;
                }
                
                NProgress.start();
                setTimeout(() => {
                    showNotification('Password updated successfully!');
                    document.getElementById('password-update-form').reset();
                    NProgress.done();
                }, 1000);
            });


            window.onload = function() {
                NProgress.done();
            };
        });
    </script>
</body>
</html> 