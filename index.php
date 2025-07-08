<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="tailwind.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(232, 224, 213, 0.9);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
        }
        
        .smooth-scroll {
            scroll-behavior: smooth;
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
        
        /* Customer Support Widget Styles */
        .support-widget {
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateX(0);
        }

        .support-widget.closed {
            transform: translateX(calc(100% + 1rem));
            pointer-events: none;
            opacity: 0;
        }

        .support-widget.open {
            transform: translateX(0);
            pointer-events: auto;
            opacity: 1;
        }

        .support-toggle {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="smooth-scroll bg-warm-cream text-deep-brown">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
                        <p class="nav-subtitle text-xs text-warm-cream tracking-widest">RISTORANTE</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#about" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                        About Us
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#menu" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                        Menu & Packages
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#services" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                        What We Offer
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="login.php" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                        Login
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="register.php" class="nav-button font-baskerville bg-warm-cream text-deep-brown px-4 py-2 rounded-full transition-all duration-300">
                        Register
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden focus:outline-none" id="mobile-menu-btn">
                    <div class="w-6 h-6 flex flex-col justify-center space-y-1">
                        <span class="block w-full h-0.5 bg-deep-brown transition-all duration-300"></span>
                        <span class="block w-full h-0.5 bg-deep-brown transition-all duration-300"></span>
                        <span class="block w-full h-0.5 bg-deep-brown transition-all duration-300"></span>
                    </div>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden hidden glass-effect" id="mobile-menu">
            <div class="px-4 py-4 space-y-4">
                <a href="#home" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">Home</a>
                <a href="#about" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">About Us</a>
                <a href="#menu" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">Menu & Packages</a>
                <a href="#services" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">What We Offer</a>
                
                <div class="pt-4 border-t border-deep-brown/10">
                    <a href="login.php" class="block w-full text-left font-baskerville hover:text-rich-brown transition-colors duration-300 mb-3">
                        Login
                    </a>
                    <a href="register.php" class="block w-full font-baskerville bg-deep-brown text-warm-cream px-4 py-2 rounded-full hover:bg-rich-brown transition-all duration-300">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('images/bg4.jpg')] bg-cover bg-center bg-no-repeat blur-sm"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <div class="fade-in">
                <h1 class="font-playfair text-6xl md:text-8xl font-bold text-warm-cream mb-6 leading-tight">
                    Caffè Lilio
                </h1>
                <p class="font-baskerville text-xl md:text-2xl text-warm-cream mb-4 tracking-widest">
                    RISTORANTE
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-8"></div>
                <p class="font-baskerville text-lg md:text-xl text-warm-cream mb-12 max-w-2xl mx-auto leading-relaxed">
                    Savor the Flavors of Spain and Italy
                </p>
                <div class="space-y-4 md:space-y-0 md:space-x-6 md:flex md:justify-center">
                    <a href="register.php" class="bg-gradient-to-r from-warm-cream to-warm-cream text-rich-brown px-8 py-4 rounded-full font-baskerville font-bold hover:shadow-xl transition-all duration-300 hover:scale-105 block w-full md:w-auto text-center">
                        Make Reservation
                    </a>
                    <a href="#menu" class="border-2 border-warm-cream text-warm-cream px-8 py-4 rounded-full font-baskerville font-bold hover:bg-rich-brown hover:text-warm-cream transition-all duration-300 block w-full md:w-auto text-center">
                        View Our Menu
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-12 md:py-20 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16">
        <div class="flex flex-col lg:flex-row gap-8 md:gap-12 lg:gap-16">
            <!-- Left Column - About Content -->
            <div class="fade-in w-full lg:w-1/2">
                <div class="mb-8 md:mb-12">
                    <h2 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-bold text-deep-brown mb-4 md:mb-6 leading-tight">
                        About <span class="text-rich-brown italic">Caffè Lilio</span>
                    </h2>
                    <div class="w-20 md:w-24 h-1.5 bg-gradient-to-r from-rich-brown to-accent-brown mb-6 md:mb-8 rounded-full"></div>
                    
                    <div class="space-y-4 md:space-y-6 font-baskerville text-base md:text-lg text-deep-brown leading-relaxed">
                        <p class="border-l-4 border-rich-brown pl-4 md:pl-6 py-1 italic">
                            Established in March 2021, Caffè Lilio Ristorante holds the distinction of being the first Italian fine dining restaurant in Liliw, Laguna. 
                            The founders aimed to highlight the rich offerings of Liliw, providing both locals and tourists with an authentic Italian dining experience in the heart of the town.
                        </p>
                        
                        <p>
                            Caffè Lilio offers a fusion of Italian and Spanish cuisines, featuring dishes like spaghetti, pizza, and steaks. 
                            The restaurant is also known for its delightful coffee, enhancing the overall dining experience.
                        </p>
                        
                        <p class="bg-warm-cream/50 p-4 md:p-6 rounded-xl border border-warm-cream">
                            Patrons have praised the courteous staff and the establishment's quiet atmosphere, contributing to its high ratings 
                            and reputation as a premier dining destination in Liliw.
                        </p>
                    </div>
                </div>
                
                <!-- Stats Section -->
                <div class="mt-8 md:mt-12 bg-gradient-to-br from-warm-cream/20 to-warm-cream/5 p-4 md:p-8 rounded-2xl border border-warm-cream/30">
                    <div class="grid grid-cols-3 gap-4 md:gap-6">
                        <div class="text-center">
                            <div class="text-3xl md:text-4xl font-playfair font-bold text-rich-brown mb-1 md:mb-2">3+</div>
                            <div class="font-baskerville text-deep-brown uppercase tracking-wider text-xs md:text-sm">Years of Excellence</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl md:text-4xl font-playfair font-bold text-rich-brown mb-1 md:mb-2">5★</div>
                            <div class="font-baskerville text-deep-brown uppercase tracking-wider text-xs md:text-sm">Customer Rating</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl md:text-4xl font-playfair font-bold text-rich-brown mb-1 md:mb-2">30+</div>
                            <div class="font-baskerville text-deep-brown uppercase tracking-wider text-xs md:text-sm">Signature Dishes</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Contact Card -->
            <div class="fade-in w-full lg:w-1/2 mt-8 md:mt-0">
                <div class="relative">
                    <div class="bg-gradient-to-br from-deep-brown/80 to-rich-brown/80 rounded-2xl md:rounded-3xl p-4 md:p-8 shadow-xl md:shadow-2xl">
                        <div class="bg-warm-cream rounded-xl md:rounded-2xl p-4 md:p-8 shadow-inner">
                            <h6 class="font-playfair text-2xl md:text-3xl font-bold text-deep-brown mb-4 md:mb-6 text-center italic">
                                We're Here for You
                            </h6>
                            <div class="text-center text-deep-brown/80 mb-6 md:mb-8 font-baskerville text-sm md:text-base">
                                Online & On-site Experience
                            </div>
                            
                            <div class="space-y-4 md:space-y-6 font-baskerville text-deep-brown text-sm md:text-base">
                                <div class="flex items-start space-x-3 md:space-x-4 p-3 md:p-4 bg-warm-cream/70 rounded-lg hover:bg-warm-cream transition-colors">
                                    <span class="text-xl md:text-2xl text-rich-brown mt-0.5 md:mt-1">📍</span>
                                    <div>
                                        <div class="font-bold uppercase tracking-wider text-xs md:text-sm mb-1">Address</div>
                                        <div class="text-xs md:text-sm">Brgy. Rizal st. cr. 4th St., Liliw Laguna, Liliw, Philippines, 4004</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3 md:space-x-4 p-3 md:p-4 bg-warm-cream/70 rounded-lg hover:bg-warm-cream transition-colors">
                                    <span class="text-xl md:text-2xl text-rich-brown mt-0.5 md:mt-1">📞</span>
                                    <div>
                                        <div class="font-bold uppercase tracking-wider text-xs md:text-sm mb-1">Phone</div>
                                        <div class="text-xs md:text-sm">+49 2542 084</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3 md:space-x-4 p-3 md:p-4 bg-warm-cream/70 rounded-lg hover:bg-warm-cream transition-colors">
                                    <span class="text-xl md:text-2xl text-rich-brown mt-0.5 md:mt-1">✉️</span>
                                    <div>
                                        <div class="font-bold uppercase tracking-wider text-xs md:text-sm mb-1">Email</div>
                                        <div class="text-xs md:text-sm">caffelilio.liliw@gmail.com</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Section -->
                            <div class="mt-6 md:mt-8 rounded-lg md:rounded-xl overflow-hidden shadow-md md:shadow-lg border border-warm-cream/50">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5834.00236310445!2d121.43328019283992!3d14.13211205286109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd5bc02c4f1977%3A0x88727b5a78560087!2sCaff%C3%A8%20Lilio!5e0!3m2!1sen!2sph!4v1744473249809!5m2!1sen!2sph"
                                    width="100%"
                                    height="180"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            
                            <!-- Reservation Button -->
                            <div class="mt-6 md:mt-8">
                                <a href="register.php" class="block w-full bg-gradient-to-r from-rich-brown to-deep-brown text-warm-cream py-3 md:py-4 rounded-lg md:rounded-xl font-baskerville font-bold hover:shadow-md md:hover:shadow-lg transition-all duration-300 text-center hover:from-deep-brown hover:to-rich-brown transform hover:-translate-y-0.5 md:hover:-translate-y-1 text-sm md:text-base">
                                    Make Reservation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="menu" class="py-20 bg-gradient-to-b from-warm-cream to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="font-playfair text-5xl md:text-6xl font-bold text-deep-brown mb-6">Our Menu</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-8"></div>
                <p class="font-baskerville text-xl text-rich-brown max-w-4xl mx-auto leading-relaxed">
                    Discover our carefully curated menu featuring the finest Italian and Spanish dishes. From classic favorites to unique specialties.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Best-Sellers -->
                <a href="index_menu/best_sellers.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/cheesewheelpasta.jpg" alt="Best Sellers" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Best-Sellers</h3>
                        </div>
                    </div>
                </a>

                <!-- Cake & Pastries -->
                <a href="index_menu/cake_pastries.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/cake-pastries.jpg" alt="Cake & Pastries" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Cake & Pastries</h3>
                        </div>
                    </div>
                </a>

                <!-- Italian Dish -->
                <a href="index_menu/italian_dish.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/italian-dish.jpg" alt="Italian Dish" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Italian Dish</h3>
                        </div>
                    </div>
                </a>

                <!-- Spanish Dish -->
                <a href="index_menu/spanish_dish.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/main-course.jpg" alt="All Day Main Course" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Spanish Dish</h3>
                        </div>
                    </div>
                </a>

                <!-- Coffee -->
                <a href="index_menu/coffee.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/coffee.jpg" alt="Coffee" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Coffee</h3>
                        </div>
                    </div>
                </a>

                   <!-- Drinks -->
                <a href="index_menu/drink.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/coffee.jpg" alt="Coffee" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Drinks</h3>
                        </div>
                    </div>
                </a>

      
                <!-- Pizza & Burgers -->
                <a href="index_menu/burger_pizza.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/spanish-dish.jpg" alt="Spanish Dish" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Burgers & Pizza</h3>
                        </div>
                    </div>
                </a>

                <!-- Pasta -->
                <a href="index_menu/pasta.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/burgers-pizza.jpg" alt="Burgers & Pizza" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Pasta</h3>
                        </div>
                    </div>
                </a>

                <!-- Pasta e Caza -->
                <a href="index_menu/pasta_ecaza.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/pasta-salads.jpg" alt="Pasta & Salads" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Pasta e Caza</h3>
                        </div>
                    </div>
                </a>

                <!-- Desserts -->
                <a href="index_menu/dessert.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/desserts.jpg" alt="Desserts" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Desserts</h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="text-center mt-12 fade-in">
                <button class="bg-deep-brown text-warm-cream px-8 py-4 rounded-full font-baskerville font-bold hover:shadow-xl transition-all duration-300 hover:scale-105">
                    Download Full Menu
                </button>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <div class="pt-12 sm:pt-16 md:pt-20 bg-gradient-to-b from-warm-cream to-amber-50">
    <div class="text-center mb-10 sm:mb-12 md:mb-16 fade-in px-4">
            <h2 class="font-playfair text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-deep-brown mb-4 sm:mb-6">Buffet Packages</h2>
            <div class="w-16 sm:w-20 md:w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-6 sm:mb-8"></div>
            <p class="font-baskerville text-base sm:text-lg md:text-xl text-rich-brown max-w-3xl lg:max-w-4xl mx-auto leading-relaxed">
                Choose from our carefully curated packages, perfect for any occasion. From intimate gatherings to grand celebrations.
            </p>
        </div>

    <!-- Packages Carousel Section -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Previous Button -->
        <button id="prevBtnPackages" class="absolute left-4 sm:left-6 lg:left-8 top-1/2 transform -translate-y-1/2 z-10 bg-rich-brown text-warm-cream w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-deep-brown transition-all duration-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Carousel Container -->
        <div class="overflow-hidden px-12 sm:px-14 lg:px-16"> <!-- Added padding to avoid overlap with buttons -->
            <div id="packageCarousel" class="flex transition-transform duration-500 ease-in-out">
                <!-- Slide 1 -->
                <div class="w-full flex-shrink-0 px-2 sm:px-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 justify-center">
                        <div class="w-full max-w-sm mx-auto">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/07_buffet.jpg', 'Buffet 7')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/07_buffet.jpg" alt="Buffet 7" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 7</h3>
                                        <p class="font-baskerville text-sm">₱1,300 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/01_buffet.jpg', 'Buffet 1')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/01_buffet.jpg" alt="Buffet 1" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 1</h3>
                                        <p class="font-baskerville text-sm">₱2,200 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto hidden lg:block">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/02_buffet.jpg', 'Buffet 2')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/02_buffet.jpg" alt="Buffet 2" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 2</h3>
                                        <p class="font-baskerville text-sm">₱1,950 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="w-full flex-shrink-0 px-2 sm:px-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 justify-center">
                        <div class="w-full max-w-sm mx-auto lg:hidden">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/02_buffet.jpg', 'Buffet 2')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/02_buffet.jpg" alt="Buffet 2" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 2</h3>
                                        <p class="font-baskerville text-sm">₱1,950 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/03_buffet.jpg', 'Buffet 3')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/03_buffet.jpg" alt="Buffet 3" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 3</h3>
                                        <p class="font-baskerville text-sm">₱1,500 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/04_buffet.jpg', 'Buffet 4')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/04_buffet.jpg" alt="Buffet 4" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 4</h3>
                                        <p class="font-baskerville text-sm">₱1,300 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto hidden lg:block">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/05_buffet.jpg', 'Buffet 5')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/05_buffet.jpg" alt="Buffet 5" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 5</h3>
                                        <p class="font-baskerville text-sm">₱1,200 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="w-full flex-shrink-0 px-2 sm:px-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 justify-center max-w-4xl mx-auto">
                        <div class="w-full max-w-sm mx-auto lg:hidden">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/05_buffet.jpg', 'Buffet 5')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/05_buffet.jpg" alt="Buffet 5" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 5</h3>
                                        <p class="font-baskerville text-sm">₱1,200 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/06_buffet.jpg', 'Buffet 6')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/06_buffet.jpg" alt="Buffet 6" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Buffet 6</h3>
                                        <p class="font-baskerville text-sm">₱900 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm mx-auto">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/07_buffet.jpg', 'Sit-down Plated')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/07_buffet.jpg" alt="Sit-down Plated" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-3 sm:p-4">
                                        <h3 class="font-playfair text-base sm:text-lg font-bold">Sit-down Plated</h3>
                                        <p class="font-baskerville text-sm">₱900 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Button -->
        <button id="nextBtnPackages" class="absolute right-4 sm:right-6 lg:right-8 top-1/2 transform -translate-y-1/2 z-10 bg-rich-brown text-warm-cream w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-deep-brown transition-all duration-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Carousel Indicators -->
        <div class="flex justify-center mt-6 sm:mt-8 space-x-2">
            <button class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 package-indicator" data-index="0"></button>
            <button class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 package-indicator" data-index="1"></button>
            <button class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 package-indicator" data-index="2"></button>
        </div>
    </div>

    <!-- What We Offer Section -->
    <section id="services" class="py-20 bg-warm-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="font-playfair text-5xl md:text-6xl font-bold text-deep-brown mb-6">What We Offer</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-8"></div>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in">
                    <div class="relative">
                        <!-- Services Carousel Container -->
                        <div class="overflow-hidden rounded-xl shadow-2xl">
                            <div id="servicesCarousel" class="flex transition-transform duration-500 ease-in-out">
                                <!-- Slide 1 -->
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_2.jpg" 
                                         class="w-full h-full object-cover">
                                </div>
                                <!-- Slide 2 -->
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_1.jpg" 
                                         class="w-full h-full object-cover">
                                </div>
                                <!-- Slide 3 -->
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_3.jpg"
                                         class="w-full h-full object-cover">
                                </div>
                                <!-- Slide 4 -->
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_4.jpg"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_5.jpg"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_6.jpg"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_7.jpg"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="w-full flex-shrink-0">
                                    <img src="images/Events_image_8.jpg"
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <!-- Carousel Indicators -->
                        <div class="flex justify-center mt-4 space-x-2">
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="0"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="1"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="2"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="3"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="4"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="5"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="6"></button>
                            <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 services-indicator" data-index="7"></button>
                        </div>
                    </div>
                </div>
                
                <div class="fade-in">
                    <div class="space-y-6 font-baskerville text-lg text-deep-brown leading-relaxed">
                        <p>
                            At Caffè Lilio, we believe that every occasion—big or small—deserves a memorable setting and flavorful experience. 
                            That's why we offer a range of services designed to bring people together through food, ambiance, and thoughtful service.
                        </p>
                        <p>
                            Whether you're planning an intimate family gathering, a corporate event, or a grand celebration, we're here to make it seamless. 
                            Our team provides customizable event packages, catering services, and venue reservations tailored to your guest count and preferred setup.
                        </p>
                        <p>
                            We have various venue options, each with a different ambiance and capacity, so you can choose the space that fits your celebration best. 
                            From classic sit-down dinners to themed celebrations, we're ready to help you design a setting that reflects your taste.
                        </p>
                        <p>
                            Our goal is to make hosting easier for you—whether that means delivering delicious food to your location or taking care of everything 
                            from the table setup to the last toast of the night.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Modal for zoomed image -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center">
        <div class="relative max-w-7xl mx-auto px-4 py-8 w-full h-full flex items-center justify-center">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-warm-cream hover:text-rich-brown transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-h-full max-w-full object-contain">
        </div>
    </div>
    
    
    <!-- Add this inside your body tag, just before the closing </body> -->
    <div class="fixed bottom-6 right-6 z-50 flex items-end space-x-4">
        <!-- Chat Window -->
        <div id="supportWindow" class="support-widget bg-warm-cream rounded-lg overflow-hidden hidden closed flex flex-col" style="height: 500px; width: 320px;">
            <!-- Header -->
            <div class="bg-deep-brown text-warm-cream p-4 flex justify-between items-center">
                <h3 class="font-playfair font-bold">Caffè Lilio Support</h3>
                <button id="closeSupport" class="text-warm-cream hover:text-rich-brown transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Chat Content -->
            <div id="chatContent" class="flex-1 overflow-y-auto p-4 space-y-3">
                <!-- Initial welcome message -->
                <div class="chat-message bot-message bg-deep-brown/10 text-deep-brown p-3 rounded-lg">
                    <p>Welcome to Caffè Lilio Support! 👋 Please select a category below or type your question in the input field.</p>
                    <div class="mt-2 grid grid-cols-1 gap-2">
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="location">📍 Location & Hours</button>
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="reservations">📅 Reservations & Events</button>
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="menu">🍽️ Menu & Dietary</button>
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="contact">📞 Contact Us</button>
                    </div>
                </div>
            </div>
            
            <!-- Input Area -->
            <div class="p-3 border-t border-deep-brown/20" id="inputArea">
                <input type="text" id="userInput" placeholder="Type your question..." class="w-full p-2 border border-deep-brown/30 rounded">
            </div>
        </div>

        <!-- Chat Toggle Button -->
        <div id="supportToggle" class="support-toggle bg-deep-brown text-warm-cream w-16 h-16 rounded-full flex items-center justify-center cursor-pointer hover:bg-rich-brown transition-all duration-300 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </div>
    </div>

    <style>
        /* Update the support widget styles */
        .support-widget {
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateX(0);
        }

        .support-widget.closed {
            transform: translateX(calc(100% + 1rem));
            pointer-events: none;
            opacity: 0;
        }

        .support-widget.open {
            transform: translateX(0);
            pointer-events: auto;
            opacity: 1;
        }

        .support-toggle {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
    
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerOffset = 80; // Adjust this value based on your navbar height
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
        const navLinks = document.querySelectorAll('.nav-link');
        const navUnderlines = document.querySelectorAll('.nav-link span');
        const navTitle = document.querySelector('.nav-title');
        const navSubtitle = document.querySelector('.nav-subtitle');
        const navButton = document.querySelector('.nav-button');

        window.addEventListener('scroll', () => {
            const isHeroSection = window.scrollY === 0;

            if (window.scrollY > 0) {
                navbar.classList.add('glass-effect');
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('glass-effect');
                navbar.classList.remove('shadow-lg');
            }

            // Update nav links color based on section
            navLinks.forEach(link => {
                if (isHeroSection) {
                    link.classList.remove('text-deep-brown', 'hover:text-deep-brown/80');
                    link.classList.add('text-warm-cream', 'hover:text-warm-cream/80');
                } else {
                    link.classList.remove('text-warm-cream', 'hover:text-warm-cream/80');
                    link.classList.add('text-deep-brown', 'hover:text-deep-brown/80');
                }
            });

            // Update underline color based on section
            navUnderlines.forEach(underline => {
                if (isHeroSection) {
                    underline.classList.remove('bg-deep-brown');
                    underline.classList.add('bg-warm-cream');
                } else {
                    underline.classList.remove('bg-warm-cream');
                    underline.classList.add('bg-deep-brown');
                }
            });

            // Update nav title and subtitle colors
            if (isHeroSection) {
                navTitle.classList.remove('text-deep-brown');
                navTitle.classList.add('text-warm-cream');
                navSubtitle.classList.remove('text-deep-brown');
                navSubtitle.classList.add('text-warm-cream');
                // Update register button colors for hero section
                if (navButton) {
                    navButton.classList.remove('bg-deep-brown', 'text-warm-cream');
                    navButton.classList.add('bg-warm-cream', 'text-deep-brown');
                }
            } else {
                navTitle.classList.remove('text-warm-cream');
                navTitle.classList.add('text-deep-brown');
                navSubtitle.classList.remove('text-warm-cream');
                navSubtitle.classList.add('text-deep-brown');
                // Update register button colors for scrolled section
                if (navButton) {
                    navButton.classList.remove('bg-warm-cream', 'text-deep-brown');
                    navButton.classList.add('bg-deep-brown', 'text-warm-cream');
                }
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

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Add active state to navigation links
        const sections = document.querySelectorAll('section[id]');

        window.addEventListener('scroll', () => {
            let current = '';
            const scrollPosition = window.pageYOffset;

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 150; // Adjusted offset for better UX
                const sectionHeight = section.clientHeight;
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                const underline = link.querySelector('span');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                    underline.style.width = '100%';
                } else {
                    underline.style.width = '0';
                }
            });
        });

        // Carousel functionality
        document.addEventListener('DOMContentLoaded', () => {
            const carousel = document.getElementById('menuCarousel');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const indicators = document.querySelectorAll('.carousel-indicator');
            let currentIndex = 0;
            const totalSlides = 6;

            function updateCarousel() {
                const offset = currentIndex * -100;
                carousel.style.transform = `translateX(${offset}%)`;
                
                // Update indicators
                indicators.forEach((indicator, index) => {
                    if (index === currentIndex) {
                        indicator.classList.add('opacity-100');
                        indicator.classList.remove('opacity-50');
                    } else {
                        indicator.classList.add('opacity-50');
                        indicator.classList.remove('opacity-100');
                    }
                });
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }

            // Auto-advance carousel every 7 seconds (increased from 5 to give more reading time)
            let autoAdvance = setInterval(nextSlide, 7000);

            // Event listeners for manual navigation
            prevBtn.addEventListener('click', () => {
                clearInterval(autoAdvance);
                prevSlide();
                autoAdvance = setInterval(nextSlide, 7000);
            });

            nextBtn.addEventListener('click', () => {
                clearInterval(autoAdvance);
                nextSlide();
                autoAdvance = setInterval(nextSlide, 7000);
            });

            // Event listeners for indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    clearInterval(autoAdvance);
                    currentIndex = index;
                    updateCarousel();
                    autoAdvance = setInterval(nextSlide, 7000);
                });
            });

            // Initialize carousel
            updateCarousel();
        });

        // Modal functionality
        function openModal(imageSrc, altText) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageSrc;
            modalImage.alt = altText;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Package carousel functionality
        document.addEventListener('DOMContentLoaded', () => {
            const packageCarousel = document.getElementById('packageCarousel');
            const prevBtnPackages = document.getElementById('prevBtnPackages');
            const nextBtnPackages = document.getElementById('nextBtnPackages');
            const packageIndicators = document.querySelectorAll('.package-indicator');
            let currentPackageIndex = 0;
            const totalPackageSlides = 3;

            function updatePackageCarousel() {
                const offset = currentPackageIndex * -100;
                packageCarousel.style.transform = `translateX(${offset}%)`;
                
                // Update indicators
                packageIndicators.forEach((indicator, index) => {
                    if (index === currentPackageIndex) {
                        indicator.classList.add('opacity-100');
                        indicator.classList.remove('opacity-50');
                    } else {
                        indicator.classList.add('opacity-50');
                        indicator.classList.remove('opacity-100');
                    }
                });
            }

            function nextPackageSlide() {
                currentPackageIndex = (currentPackageIndex + 1) % totalPackageSlides;
                updatePackageCarousel();
            }

            function prevPackageSlide() {
                currentPackageIndex = (currentPackageIndex - 1 + totalPackageSlides) % totalPackageSlides;
                updatePackageCarousel();
            }

            // Auto-advance carousel every 7 seconds
            let autoAdvancePackages = setInterval(nextPackageSlide, 7000);

            // Event listeners for manual navigation
            prevBtnPackages.addEventListener('click', () => {
                clearInterval(autoAdvancePackages);
                prevPackageSlide();
                autoAdvancePackages = setInterval(nextPackageSlide, 7000);
            });

            nextBtnPackages.addEventListener('click', () => {
                clearInterval(autoAdvancePackages);
                nextPackageSlide();
                autoAdvancePackages = setInterval(nextPackageSlide, 7000);
            });

            // Event listeners for indicators
            packageIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    clearInterval(autoAdvancePackages);
                    currentPackageIndex = index;
                    updatePackageCarousel();
                    autoAdvancePackages = setInterval(nextPackageSlide, 7000);
                });
            });

            // Initialize carousel
            updatePackageCarousel();
        });

        // Services carousel functionality
        document.addEventListener('DOMContentLoaded', () => {
            const servicesCarousel = document.getElementById('servicesCarousel');
            const servicesIndicators = document.querySelectorAll('.services-indicator');
            let currentServicesIndex = 0;
            const totalServicesSlides = 4;

            function updateServicesCarousel() {
                const offset = currentServicesIndex * -100;
                servicesCarousel.style.transform = `translateX(${offset}%)`;
                
                // Update indicators
                servicesIndicators.forEach((indicator, index) => {
                    if (index === currentServicesIndex) {
                        indicator.classList.add('opacity-100');
                        indicator.classList.remove('opacity-50');
                    } else {
                        indicator.classList.add('opacity-50');
                        indicator.classList.remove('opacity-100');
                    }
                });
            }

            function nextServicesSlide() {
                currentServicesIndex = (currentServicesIndex + 1) % totalServicesSlides;
                updateServicesCarousel();
            }

            // Auto-advance carousel every 5 seconds
            let autoAdvanceServices = setInterval(nextServicesSlide, 5000);

            // Event listeners for indicators
            servicesIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    clearInterval(autoAdvanceServices);
                    currentServicesIndex = index;
                    updateServicesCarousel();
                    autoAdvanceServices = setInterval(nextServicesSlide, 5000);
                });
            });

            // Initialize carousel
            updateServicesCarousel();
        });

        // FAQ Data (Filtered)
        const faqData = {
            location: [
                {
                    question: "Where is Caffè Lilio located?",
                    answer: "📍 Brgy. Rizal st. cr. 4th St., Liliw, Laguna, Philippines, 4004<br><a href='https://maps.app.goo.gl/QuT5V7PWGJQZRWyN7' class='text-rich-brown underline' target='_blank'>View on Google Maps</a>"
                },
                {
                    question: "What are your opening hours?",
                    answer: "⏰ 9am - 8pm"
                }
            ],
            reservations: [
                {
                    question: "Do I need a reservation to dine?",
                    answer: "📝 Not required for regular dining, but recommended for events. <a href='https://caffelilioristorante.com/register.php' class='text-rich-brown underline' target='_blank'>Register an account to start your reservation</a>."
                },
                {
                    question: "Can you accommodate large groups or events?",
                    answer: "🎉 Yes, we offer event packages."
                }
            ],
            menu: [
                {
                    question: "Do you have vegetarian or vegan options?",
                    answer: "🥗 Yes, we offer vegetarian and vegan dishes."
                },
                {
                    question: "Can I customize dishes for dietary needs?",
                    answer: "👌 Yes, we can adjust dishes for specific preferences."
                }
            ],
            contact: [
                {
                    question: "How can I contact Caffè Lilio?",
                    answer: `
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <span class="mr-2">📱</span>
                                <div>
                                    <a href="https://www.facebook.com/caffelilio.ph" class="text-rich-brown underline" target="_blank">Facebook</a><br>
                                    <a href="https://www.instagram.com/caffelilio.ph/" class="text-rich-brown underline" target="_blank">Instagram</a>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="mr-2">📞</span>
                                <div>+49 2542 084</div>
                            </div>
                            <div class="flex items-start">
                                <span class="mr-2">✉️</span>
                                <div>caffelilio.liliw@gmail.com</div>
                            </div>
                        </div>
                    `
                }
            ]
        };

        // Chatbot functionality with localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const chatContent = document.getElementById('chatContent');
            const supportToggle = document.getElementById('supportToggle');
            const supportWindow = document.getElementById('supportWindow');
            const closeSupport = document.getElementById('closeSupport');
            const inputArea = document.getElementById('inputArea');
            const userInput = document.getElementById('userInput');
            let currentCategory = null; // Track current category

            // Load conversation from localStorage if available
            const loadConversation = () => {
                const savedConvo = localStorage.getItem('caffeLilioChat');
                chatContent.innerHTML = ''; // Clear chat content to avoid duplication
                if (savedConvo && savedConvo.trim() !== '') {
                    chatContent.innerHTML = savedConvo;
                    // Reattach event listeners to any buttons in the saved conversation
                    attachEventListeners();
                } else {
                    // Show initial greeting if no saved conversation
                    showWelcomeMessage();
                }
                // Ensure input area is visible
                inputArea.classList.remove('hidden');
                chatContent.scrollTop = chatContent.scrollHeight; // Scroll to bottom
            };

            // Save conversation to localStorage
            const saveConversation = () => {
                localStorage.setItem('caffeLilioChat', chatContent.innerHTML);
            };

            // Keyword responses
            const keywordResponses = {
                'location': " everlasting 📍 Our location: Brgy. Rizal st. cr. 4th St., Liliw, Laguna<br><a href='https://maps.app.goo.gl/QuT5V7PWGJQZRWyN7' class='text-rich-brown underline' target='_blank'>View on Google Maps</a>",
                'hours': "⏰ Our opening hours are 9am - 8pm daily",
                'menu': "🍽️ You can view our menu in the 'Menu & Packages' section of our website",
                'contact': "📞 You can reach us at +49 2542 084 or caffelilio.liliw@gmail.com",
                'reservation': "📝 You can make a reservation by visiting our website or calling us",
                'hello': "👋 Hello! How can I help you today?",
                'hi': "👋 Hi there! What can I assist you with?",
                'thanks': "😊 You're welcome! Is there anything else I can help with?"
            };

            // Check for keywords in user message
            const checkKeywords = (message) => {
                const lowerMsg = message.toLowerCase();
                for (const [keyword, response] of Object.entries(keywordResponses)) {
                    if (lowerMsg.includes(keyword)) {
                        return response;
                    }
                }
                return null;
            };

            // Add a message to the chat
            const addMessage = (sender, message, isHTML = false) => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${sender}-message bg-deep-brown/${sender === 'bot' ? '10' : '5'} text-deep-brown p-3 rounded-lg mb-2`;
                
                if (isHTML) {
                    messageDiv.innerHTML = message;
                } else {
                    messageDiv.textContent = message;
                }
                
                chatContent.appendChild(messageDiv);
                chatContent.scrollTop = chatContent.scrollHeight;
                saveConversation();
            };

            // Show welcome message with categories
            const showWelcomeMessage = () => {
                const welcomeDiv = document.createElement('div');
                welcomeDiv.className = 'chat-message bot-message bg-deep-brown/10 text-deep-brown p-3 rounded-lg';
                welcomeDiv.innerHTML = `
                    <p>Welcome to Caffè Lilio Support! 👋 Please select a category below or type your question in the input field.</p>
                    <div class="mt-2 grid grid-cols-1 gap-2">
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="location">📍 Location & Hours</button>
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="reservations">📅 Reservations & Events</button>
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="menu">🍽️ Menu & Dietary</button>
                        <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="contact">📞 Contact Us</button>
                    </div>
                `;
                chatContent.appendChild(welcomeDiv);
                chatContent.scrollTop = chatContent.scrollHeight;
                saveConversation();
                
                // Reattach event listeners
                attachEventListeners();
            };

            // Handle user input
            const handleUserInput = () => {
                const message = userInput.value.trim();
                if (message) {
                    addMessage('user', message);
                    userInput.value = '';
                    
                    // Check for keywords first
                    const keywordResponse = checkKeywords(message);
                    if (keywordResponse) {
                        setTimeout(() => {
                            addMessage('bot', keywordResponse, true);
                        }, 500);
                        return;
                    }
                    
                    // If no keywords matched, show default response
                    setTimeout(() => {
                        addMessage('bot', "I'm sorry, I didn't understand that. Could you try asking in a different way or choose from the categories below?", true);
                        showCategories();
                    }, 500);
                }
            };

            // Show categories
            const showCategories = () => {
                const categoriesDiv = document.createElement('div');
                categoriesDiv.className = 'chat-message bot-message bg-deep-brown/10 text-deep-brown p-3 rounded-lg';
                categoriesDiv.innerHTML = `
                    <div class="mt-2 grid grid-cols-1 gap-2">
                        ${Object.keys(faqData).map(category => `
                            <button class="category-btn bg-deep-brown/20 hover:bg-deep-brown/30 p-2 rounded" data-category="${category}">
                                ${category === 'location' ? '📍 Location & Hours' :
                                  category === 'reservations' ? '📅 Reservations & Events' :
                                  category === 'menu' ? '🍽️ Menu & Dietary' :
                                  '📞 Contact Us'}
                            </button>
                        `).join('')}
                    </div>
                `;
                chatContent.appendChild(categoriesDiv);
                chatContent.scrollTop = chatContent.scrollHeight;
                saveConversation();
                
                // Reattach event listeners
                attachEventListeners();
            };

            // Attach event listeners to dynamic elements
            const attachEventListeners = () => {
                // Remove existing event listeners to prevent duplicates
                document.querySelectorAll('.category-btn').forEach(btn => {
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                });
                document.querySelectorAll('.back-btn').forEach(btn => {
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                });
                document.querySelectorAll('.question-btn').forEach(btn => {
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                });
                document.querySelectorAll('.back-to-questions').forEach(btn => {
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                });

                // Add new event listeners
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const category = this.dataset.category;
                        currentCategory = category;
                        showCategoryQuestions(category);
                    });
                });
                
                document.querySelectorAll('.back-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        showCategories();
                    });
                });
                
                document.querySelectorAll('.question-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        showAnswer(this.innerHTML, this.dataset.answer);
                    });
                });
                
                document.querySelectorAll('.back-to-questions').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (currentCategory) {
                            showCategoryQuestions(currentCategory);
                        }
                    });
                });
            };

            // Show questions for a category
            function showCategoryQuestions(category) {
                // Append back button and questions to chat content without clearing
                const questionsDiv = document.createElement('div');
                questionsDiv.className = 'chat-message bot-message bg-deep-brown/10 text-deep-brown p-3 rounded-lg';
                questionsDiv.innerHTML = `
                    <button class="back-btn flex items-center text-rich-brown mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to categories
                    </button>
                    <p>Select a question:</p>
                `;
                chatContent.appendChild(questionsDiv);

                // Add questions for this category
                faqData[category].forEach(item => {
                    const questionDiv = document.createElement('div');
                    questionDiv.className = 'chat-message user-message bg-deep-brown/5 text-deep-brown p-3 rounded-lg cursor-pointer hover:bg-deep-brown/10 transition-colors duration-200 question-btn';
                    questionDiv.innerHTML = item.question;
                    questionDiv.dataset.answer = item.answer;
                    chatContent.appendChild(questionDiv);
                });

                // Scroll to bottom
                chatContent.scrollTop = chatContent.scrollHeight;
                saveConversation();

                // Reattach event listeners
                attachEventListeners();
            }

            // Show selected question and answer only
            function showAnswer(question, answer) {
                // Append question and answer to chat content
                const questionMessage = document.createElement('div');
                questionMessage.className = 'chat-message user-message bg-deep-brown/5 text-deep-brown p-3 rounded-lg';
                questionMessage.innerHTML = question;
                chatContent.appendChild(questionMessage);

                const answerMessage = document.createElement('div');
                answerMessage.className = 'chat-message bot-message bg-deep-brown/10 text-deep-brown p-3 rounded-lg';
                answerMessage.innerHTML = answer;
                chatContent.appendChild(answerMessage);

                const backButton = document.createElement('div');
                backButton.className = 'chat-message bot-message bg-deep-brown/10 text-deep-brown p-3 rounded-lg';
                backButton.innerHTML = `
                    <button class="back-to-questions text-rich-brown underline">
                        ← Back to questions
                    </button>
                `;
                chatContent.appendChild(backButton);

                // Scroll to bottom
                chatContent.scrollTop = chatContent.scrollHeight;
                saveConversation();

                // Reattach event listeners
                attachEventListeners();
            }

            // Toggle chat window
            supportToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (supportWindow.classList.contains('hidden')) {
                    supportWindow.classList.remove('hidden');
                    supportWindow.classList.remove('closed');
                    supportWindow.classList.add('open');
                    loadConversation();
                } else {
                    supportWindow.classList.add('closed');
                    supportWindow.classList.remove('open');
                    setTimeout(() => {
                        supportWindow.classList.add('hidden');
                    }, 300);
                }
            });

            // Close button for chat window
            closeSupport.addEventListener('click', (e) => {
                e.stopPropagation();
                supportWindow.classList.add('closed');
                supportWindow.classList.remove('open');
                setTimeout(() => {
                    supportWindow.classList.add('hidden');
                }, 300);
            });

            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!supportWindow.contains(e.target) && e.target !== supportToggle && !supportWindow.classList.contains('hidden')) {
                    supportWindow.classList.add('closed');
                    supportWindow.classList.remove('open');
                    setTimeout(() => {
                        supportWindow.classList.add('hidden');
                    }, 300);
                }
            });

            // Handle Enter key in input field
            userInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    handleUserInput();
                }
            });

            // Show input area by default
            inputArea.classList.remove('hidden');
            
            // Load any saved conversation when page loads
            loadConversation();
        });
    </script>
    
</body>
</html>