
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            willожалуй

            will-change: transform;
        }

        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        .bg-warm-gradient {
            background: linear。例えば

            linear-gradient(135deg, #E8E0D5, #d4c8b9);
        }

        .bg-card {
            background: white;
            backdrop-filter: blur(8px);
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
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

        .star-rating .fa-star {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star-rating .fa-star:hover,
        .star-rating .fa-star.active {
            color: #FBBF24 !important;
        }
    </style>
</head>
<body class="bg-warm-cream/50 text-deep-brown min-h-screen">
    <!-- Navigation -->
    <nav class="bg-warm-cream text-deep-brown shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex-1 flex items-center justify-start">
                    <a href="customerindex.php" class="flex items-center space-x-2 hover:opacity-90 transition-opacity" aria-label="Home">
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
                        Reservations
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <a href="menu.php" class="font-baskerville hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Menu
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                    <!-- <a href="ratings.php" class="font-baskerville text-rich-brown transition-colors duration-300 relative group">
                        Rate Us
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-deep-brown transform scale-x-100 transition-transform duration-300"></span>
                    </a> -->
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
                                    <div class="p-4 border-b border-deep-brown/10">
                                        <p class="font-baskerville text-deep-brown">New special offer available!</p>
                                        <p class="text-sm text-deep-brown/60">Check out our weekend buffet special.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative group">
                            <a href="profile.php" class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                    aria-label="User menu"
                                    id="user-menu-button">
                                <img src="https://ui-avatars.com/api/?name=User+Name&background=E8E0D5&color=5D2F0F" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full border border-deep-brown/30">
                                <span class="font-baskerville">User Name</span>
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
                        <!-- <a href="ratings.php" class="block font-baskerville text-rich-brown transition-colors duration-300 py-2">
                            <i class="fas fa-star w-8"></i> Rate Us
                        </a> -->
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Ratings Header -->
        <section class="mb-12 animate-fade-in">
            <h2 class="font-playfair text-4xl font-bold mb-4 text-deep-brown">Rate Your Experience</h2>
            <p class="font-baskerville text-lg text-deep-brown/80">We value your feedback! Please rate our food and reservation experience.</p>
        </section>

        <!-- Ratings Form -->
        <section class="bg-card rounded-xl p-6 shadow-md hover-lift mb-12">
            <h3 class="font-playfair text-2xl font-bold mb-6 text-deep-brown">Submit Your Rating</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Food Rating -->
                <div class="space-y-4">
                    <h4 class="font-baskerville text-xl font-bold text-deep-brown">Food Quality</h4>
                    <div class="star-rating flex space-x-2" data-tippy-content="Rate the food quality">
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                    </div>
                    <textarea class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-rich-brown focus:ring-2 focus:ring-rich-brown/20 transition-all"
                              placeholder="Tell us about the food..." rows="4"></textarea>
                </div>
                <!-- Reservation Rating -->
                <div class="space-y-4">
                    <h4 class="font-baskerville text-xl font-bold text-deep-brown">Reservation Experience</h4>
                    <div class="star-rating flex space-x-2" data-tippy-content="Rate the reservation experience">
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                        <i class="fas fa-star text-2xl text-deep-brown/50"></i>
                    </div>
                    <textarea class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-rich-brown focus:ring-2 focus:ring-rich-brown/20 transition-all"
                              placeholder="Tell us about your reservation experience..." rows="4"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2 group">
                    <span>Submit Ratings</span>
                    <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                </button>
            </div>
        </section>

        <!-- Recent Reviews -->
        <section class="mb-12">
            <h3 class="font-playfair text-2xl font-bold mb-6 text-deep-brown">Recent Reviews</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-card rounded-xl p-6 shadow-md hover-lift">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-baskerville font-bold text-deep-brown">Amazing Food!</h4>
                        <div class="flex space-x-1">
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                        </div>
                    </div>
                    <p class="font-baskerville text-deep-brown/80 mb-4">The pasta was divine, and the dessert was a perfect finish. Highly recommend!</p>
                    <div class="flex items-center space-x-2">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=E8E0D5&color=5D2F0F" alt="Profile" class="w-8 h-8 rounded-full">
                        <span class="font-baskerville text-sm text-deep-brown/60">John Doe • 2 days ago</span>
                    </div>
                </div>
                <div class="bg-card rounded-xl p-6 shadow-md hover-lift">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-baskerville font-bold text-deep-brown">Smooth Reservation Process</h4>
                        <div class="flex space-x-1">
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-deep-brown/50"></i>
                        </div>
                    </div>
                    <p class="font-baskerville text-deep-brown/80 mb-4">Booking was easy, and the staff was very accommodating. Will return!</p>
                    <div class="flex items-center space-x-2">
                        <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=E8E0D5&color=5D2F0F" alt="Profile" class="w-8 h-8 rounded-full">
                        <span class="font-baskerville text-sm text-deep-brown/60">Jane Smith • 3 days ago</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
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
                            <a href="menu.php" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Our Menu</a>
                            <a href="my_reservations.php" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Reservations</a>
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
            // Initialize tooltips
            tippy('[data-tippy-content]', {
                theme: 'custom',
                animation: 'scale',
                duration: [200, 150],
                placement: 'bottom'
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

            // Star rating functionality
            document.querySelectorAll('.star-rating').forEach(ratingGroup => {
                const stars = ratingGroup.querySelectorAll('.fa-star');
                stars.forEach((star, index) => {
                    star.addEventListener('click', () => {
                        stars.forEach((s, i) => {
                            if (i <= index) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });
                    });
                    star.addEventListener('mouseover', () => {
                        stars.forEach((s, i) => {
                            if (i <= index) {
                                s.classList.add('hover');
                            } else {
                                s.classList.remove('hover');
                            }
                        });
                    });
                    star.addEventListener('mouseout', () => {
                        stars.forEach(s => s.classList.remove('hover'));
                    });
                });
            });
        });
    </script>
</body>
</html>