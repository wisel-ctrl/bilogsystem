<?php
require_once 'customer_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
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

        .bg-warm-gradient {
            background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
        }

        .bg-card {
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            backdrop-filter: blur(8px);
        }

        .bg-nav {
            background: linear-gradient(to bottom, #5D2F0F, #4a260d);
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

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

        .btn-primary {
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
        }

        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        .skeleton-text {
            height: 1em;
            background: #e0e0e0;
            margin: 0.5em 0;
            border-radius: 4px;
        }

        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }
    </style>
</head>
<body class="bg-warm-cream text-deep-brown min-h-screen">
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
                    <a href="#contact" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
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

                        <!-- User Profile -->
                        <div class="relative group">
                            <a href="profile.php" class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                    aria-label="User menu"
                                    id="user-menu-button">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=E8E0D5&color=5D2F0F" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full border border-deep-brown/30">
                                <span class="font-baskerville">John Doe</span>
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

        <!-- Mobile Menu -->
        <div class="md:hidden mobile-menu fixed inset-0 bg-warm-cream/95 z-40" id="mobile-menu">
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
                        <a href="customerindex.php" class="block font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 py-2">
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

    <!-- Loading Progress Bar -->
    <div id="nprogress-container"></div>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Upcoming Reservations -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown">Upcoming Reservations</h3>
                <a href="bookingpage.php" class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2">
                    <span>Make New Reservation</span>
                    <i class="fas fa-calendar-plus"></i>
                </a>
            </div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown"></h3>
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

            // Handle regular links
            document.querySelectorAll('a[href$=".php"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    NProgress.start();
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
                
                setTimeout(() => toast.classList.add('show'), 100);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Handle reservation cancellation
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

            // Load notifications
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

            loadNotifications();
        });
    </script>
</body>
</html>