<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        'serif': ['Georgia', 'serif'],
                        'script': ['Brush Script MT', 'cursive']
                    }
                }
            }
        }
    </script>
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
                    <button class="bg-gradient-to-r from-warm-cream to-warm-cream text-rich-brown px-8 py-4 rounded-full font-baskerville font-bold hover:shadow-xl transition-all duration-300 hover:scale-105 block w-full md:w-auto">
                        Make Reservation
                    </button>
                    <a href="#menu" class="border-2 border-warm-cream text-warm-cream px-8 py-4 rounded-full font-baskerville font-bold hover:bg-rich-brown hover:text-warm-cream transition-all duration-300 block w-full md:w-auto text-center">
                        View Our Menu
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in">
                    <h2 class="font-playfair text-5xl md:text-6xl font-bold text-deep-brown mb-8">About Caffè Lilio</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mb-8"></div>
                    <div class="space-y-6 font-baskerville text-lg text-deep-brown leading-relaxed">
                        <p>
                            Established in March 2021, Caffè Lilio Ristorante holds the distinction of being the first Italian fine dining restaurant in Liliw, Laguna. 
                            The founders aimed to highlight the rich offerings of Liliw, providing both locals and tourists with an authentic Italian dining experience in the heart of the town.
                        </p>
                        <p>
                            Caffè Lilio offers a fusion of Italian and Spanish cuisines, featuring dishes like spaghetti, pizza, and steaks. 
                            The restaurant is also known for its delightful coffee, enhancing the overall dining experience.
                        </p>
                        <p>
                            Patrons have praised the courteous staff and the establishment's quiet atmosphere, contributing to its high ratings 
                            and reputation as a premier dining destination in Liliw.
                        </p>
                    </div>
                    
                    <div class="mt-12 grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-playfair font-bold text-rich-brown mb-2">3+</div>
                            <div class="font-baskerville text-deep-brown">Years of Excellence</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-playfair font-bold text-rich-brown mb-2">5★</div>
                            <div class="font-baskerville text-deep-brown">Customer Rating</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-playfair font-bold text-rich-brown mb-2">30+</div>
                            <div class="font-baskerville text-deep-brown">Signature Dishes</div>
                        </div>
                    </div>
                </div>
                
                <div class="fade-in">
                    <div class="relative">
                        <div class="bg-deep-brown/50 rounded-3xl p-8 shadow-2xl">
                            <div class="bg-warm-cream rounded-2xl p-8">
                                <h6 class="font-playfair text-2xl font-bold text-deep-brown mb-6 text-center">
                                    We're Here for You – Online & On-site
                                </h6>
                                
                                <div class="space-y-4 font-baskerville text-deep-brown">
                                    <div class="flex items-start space-x-3">
                                        <span class="text-rich-brown mt-1">📍</span>
                                        <div>
                                            <div class="font-bold">Address</div>
                                            <div>Brgy. Rizal st. cr. 4th St., Liliw Laguna, Liliw, Philippines, 4004</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start space-x-3">
                                        <span class="text-rich-brown mt-1">📞</span>
                                        <div>
                                            <div class="font-bold">Phone</div>
                                            <div>+49 2542 084</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start space-x-3">
                                        <span class="text-rich-brown mt-1">✉️</span>
                                        <div>
                                            <div class="font-bold">Email</div>
                                            <div>caffelilio.liliw@gmail.com</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Section -->
                                <div class="mt-6">
                                    <section class="map w-full rounded-xl overflow-hidden shadow-lg">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5834.00236310445!2d121.43328019283992!3d14.13211205286109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd5bc02c4f1977%3A0x88727b5a78560087!2sCaff%C3%A8%20Lilio!5e0!3m2!1sen!2sph!4v1744473249809!5m2!1sen!2sph"
                                            width="100%"
                                            height="250"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    </section>
                                </div>
                                
                                <div class="mt-6">
                                    <button class="w-full bg-deep-brown text-warm-cream py-3 rounded-xl font-baskerville font-bold hover:shadow-lg transition-all duration-300">
                                        Make Reservation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-20 bg-gradient-to-b from-warm-cream to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="font-playfair text-5xl md:text-6xl font-bold text-deep-brown mb-6">Our Menu</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-8"></div>
                <p class="font-baskerville text-xl text-rich-brown max-w-4xl mx-auto leading-relaxed">
                    Discover our carefully curated menu featuring the finest Italian and Spanish dishes. From classic favorites to unique specialties.
                </p>
            </div>

            <div class="relative">
                <!-- Previous Button -->
                <button id="prevBtn" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 z-10 bg-rich-brown text-warm-cream w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-deep-brown transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Carousel Container -->
                <div class="overflow-hidden">
                    <div id="menuCarousel" class="flex transition-transform duration-500 ease-in-out">
                        <!-- Slide 1 -->
                        <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/01_bestseller.jpg', 'Best Sellers Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/01_bestseller.jpg" alt="Best Sellers Menu" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/02_italian.jpg', 'Italian Dishes Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/02_italian.jpg" alt="Italian Dishes Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/03_spanish.jpg', 'Spanish Dishes Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/03_spanish.jpg" alt="Spanish Dishes Menu" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/04_salad_soup.jpg', 'Salads and Soups Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/04_salad_soup.jpg" alt="Salads and Soups Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/05_burger.jpg', 'Burgers Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/05_burger.jpg" alt="Burgers Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/06_pizza.jpg', 'Pizza Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/06_pizza.jpg" alt="Pizza Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/07_pasta.jpg', 'Pasta Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/07_pasta.jpg" alt="Pasta Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/08_pasta_2.jpg', 'Special Pasta Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/08_pasta_2.jpg" alt="Special Pasta Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/09_dessert.jpg', 'Dessert Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/09_dessert.jpg" alt="Dessert Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 4 -->
                        <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/10_maincourse_fish.jpg', 'Fish Main Course Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/10_maincourse_fish.jpg" alt="Fish Main Course Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/11_maincourse_chicken.jpg', 'Chicken Main Course Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/11_maincourse_chicken.jpg" alt="Chicken Main Course Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/12_maincourse_pork.jpg', 'Pork Main Course Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/12_maincourse_pork.jpg" alt="Pork Main Course Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 5 -->
                        <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/13_maincourse_beef.jpg', 'Beef Main Course Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/13_maincourse_beef.jpg" alt="Beef Main Course Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/14_drinks.jpg', 'Drinks Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/14_drinks.jpg" alt="Drinks Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/15_coffee.jpg', 'Coffee Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/15_coffee.jpg" alt="Coffee Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 6 -->
                        <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/16_liquor.jpg', 'Liquor Menu')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/16_liquor.jpg" alt="Liquor Menu" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/cheesewheelpasta.jpg', 'Cheese Wheel Pasta')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/cheesewheelpasta.jpg" alt="Cheese Wheel Pasta" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="bg-deep-brown/40 rounded-xl p-2 shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/cochinillo.jpg', 'Cochinillo')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/cochinillo.jpg" alt="Cochinillo" class="w-full h-full object-cover">
                                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Button -->
                <button id="nextBtn" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 z-10 bg-rich-brown text-warm-cream w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-deep-brown transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Carousel Indicators -->
                <div class="flex justify-center mt-8 space-x-2">
                    <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 carousel-indicator" data-index="0"></button>
                    <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 carousel-indicator" data-index="1"></button>
                    <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 carousel-indicator" data-index="2"></button>
                    <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 carousel-indicator" data-index="3"></button>
                    <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 carousel-indicator" data-index="4"></button>
                    <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 carousel-indicator" data-index="5"></button>
                    
                </div>
            </div>
            
            <div class="text-center mt-12 fade-in">
                <button class="bg-deep-brown text-warm-cream px-8 py-4 rounded-full font-baskerville font-bold hover:shadow-xl transition-all duration-300 hover:scale-105">
                    Download Full Menu
                </button>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <div class="py-20 bg-gradient-to-b from-warm-cream to-amber-50">
        <div class="text-center mb-16 fade-in">
            <h2 class="font-playfair text-5xl md:text-6xl font-bold text-deep-brown mb-6">Buffet Packages</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-rich-brown to-accent-brown mx-auto mb-8"></div>
            <p class="font-baskerville text-xl text-rich-brown max-w-4xl mx-auto leading-relaxed">
                Choose from our carefully curated packages, perfect for any occasion. From intimate gatherings to grand celebrations.
            </p>
        </div>

        <!-- Packages Carousel Section -->
        <div class="relative max-w-6xl mx-auto">
            <!-- Previous Button -->
            <button id="prevBtnPackages" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 z-10 bg-rich-brown text-warm-cream w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-deep-brown transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Carousel Container -->
            <div class="overflow-hidden">
                <div id="packageCarousel" class="flex transition-transform duration-500 ease-in-out">
                    <!-- Slide 1 -->
                    <div class="w-full flex-shrink-0 px-4 flex justify-center space-x-4">
                        <div class="w-1/3">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/07_buffet.jpg', 'Buffet 7')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/07_buffet.jpg" alt="Buffet 7" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                        <h3 class="font-playfair text-lg font-bold">Buffet 7</h3>
                                        <p class="font-baskerville text-sm">₱1,300 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/01_buffet.jpg', 'Buffet 1')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/01_buffet.jpg" alt="Buffet 1" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                        <h3 class="font-playfair text-lg font-bold">Buffet 1</h3>
                                        <p class="font-baskerville text-sm">₱2,200 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/02_buffet.jpg', 'Buffet 2')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/02_buffet.jpg" alt="Buffet 2" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                        <h3 class="font-playfair text-lg font-bold">Buffet 2</h3>
                                        <p class="font-baskerville text-sm">₱1,950 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="w-full flex-shrink-0 px-4 flex space-x-4">
                        <div class="w-1/3">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/03_buffet.jpg', 'Buffet 3')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/03_buffet.jpg" alt="Buffet 3" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                        <h3 class="font-playfair text-lg font-bold">Buffet 3</h3>
                                        <p class="font-baskerville text-sm">₱1,500 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/04_buffet.jpg', 'Buffet 4')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/04_buffet.jpg" alt="Buffet 4" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                        <h3 class="font-playfair text-lg font-bold">Buffet 4</h3>
                                        <p class="font-baskerville text-sm">₱1,300 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3">
                            <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/05_buffet.jpg', 'Buffet 5')">
                                <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                    <img src="images/05_buffet.jpg" alt="Buffet 5" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                        <h3 class="font-playfair text-lg font-bold">Buffet 5</h3>
                                        <p class="font-baskerville text-sm">₱1,200 +10% Service Charge</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="flex justify-center items-center gap-8 max-w-4xl mx-auto">
                            <div class="w-1/2 max-w-sm">
                                <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/06_buffet.jpg', 'Buffet 6')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/06_buffet.jpg" alt="Buffet 6" class="w-full h-full object-cover">
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                            <h3 class="font-playfair text-lg font-bold">Buffet 6</h3>
                                            <p class="font-baskerville text-sm">₱900 +10% Service Charge</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/2 max-w-sm">
                                <div class="shadow-lg hover-lift fade-in cursor-pointer transform hover:scale-[1.02] transition-all duration-300" onclick="openModal('images/07_buffet.jpg', 'Sit-down Plated')">
                                    <div class="aspect-[3/4] relative overflow-hidden rounded-lg">
                                        <img src="images/07_buffet.jpg" alt="Sit-down Plated" class="w-full h-full object-cover">
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-warm-cream p-4">
                                            <h3 class="font-playfair text-lg font-bold">Sit-down Plated</h3>
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
            <button id="nextBtnPackages" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 z-10 bg-rich-brown text-warm-cream w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-deep-brown transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Carousel Indicators -->
            <div class="flex justify-center mt-8 space-x-2">
                <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 package-indicator" data-index="0"></button>
                <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 package-indicator" data-index="1"></button>
                <button class="w-3 h-3 rounded-full bg-rich-brown opacity-50 transition-opacity duration-300 package-indicator" data-index="2"></button>
            </div>
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
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center">
                        <span class="text-warm-cream font-playfair font-bold text-xl">CL</span>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-xl">Caffè Lilio</h3>
                        <p class="text-xs tracking-widest opacity-75">RISTORANTE</p>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-6 mb-8">
    <a href="https://web.facebook.com/caffelilio.ph" class="text-warm-cream hover:text-rich-brown transition-colors duration-300">
        <i class="fab fa-facebook text-2xl"></i>
    </a>
    <a href="https://www.instagram.com/caffelilio.ph/" class="text-warm-cream hover:text-rich-brown transition-colors duration-300">
        <i class="fab fa-instagram text-2xl"></i>
    </a>
</div>
                
                <div class="border-t border-rich-brown pt-8">
                    <p class="font-baskerville opacity-75">
                        © 2024 Caffè Lilio Ristorante. All rights reserved. | 
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