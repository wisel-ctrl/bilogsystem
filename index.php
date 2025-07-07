<?php
ob_start();
?>
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
                <a href="/menu/best-sellers" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/cheesewheelpasta.jpg" alt="Best Sellers" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Best-Sellers</h3>
                        </div>
                    </div>
                </a>

                <!-- Cake & Pastries -->
                <a href="/menu/cake-pastries" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/cake-pastries.jpg" alt="Cake & Pastries" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Cake & Pastries</h3>
                        </div>
                    </div>
                </a>

                <!-- Italian Dish -->
                <a href="/index_menu/italian_dish.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/italian-dish.jpg" alt="Italian Dish" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Italian Dish</h3>
                        </div>
                    </div>
                </a>

                <!-- All Day Main Course -->
                <a href="/menu/main-course" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/main-course.jpg" alt="All Day Main Course" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">All Day Main Course</h3>
                        </div>
                    </div>
                </a>

                <!-- Coffee -->
                <a href="/menu/coffee" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/coffee.jpg" alt="Coffee" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Coffee</h3>
                        </div>
                    </div>
                </a>

      
                <!-- Spanish Dish -->
                <a href="/menu/spanish-dish" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/spanish-dish.jpg" alt="Spanish Dish" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Spanish Dish</h3>
                        </div>
                    </div>
                </a>

                <!-- Burgers & Pizza -->
                <a href="/menu/burgers-pizza" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/burgers-pizza.jpg" alt="Burgers & Pizza" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Burgers & Pizza</h3>
                        </div>
                    </div>
                </a>

                <!-- Pasta & Salads -->
                <a href="/menu/pasta-salads" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                    <div class="aspect-[3/4] relative">
                        <img src="images/pasta-salads.jpg" alt="Pasta & Salads" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                            <h3 class="font-playfair text-2xl font-bold text-warm-cream text-center px-4">Pasta & Salads</h3>
                        </div>
                    </div>
                </a>

                <!-- Desserts -->
                <a href="/menu/desserts" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
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
    
    <?php
// Capture content and include layout
$content = ob_get_clean();
include 'index_layout.php';
?>