<?php
require_once 'customer_auth.php'; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(232, 224, 213, 0.8);
        }
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(93, 47, 15, 0.3);
        }
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-warm-cream font-baskerville">
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 transition-all duration-300" id="navbar">
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
                                    <a href="register.php" class="block w-full bg-deep-brown text-warm-cream py-3 rounded-xl font-baskerville font-bold hover:shadow-lg transition-all duration-300 text-center">
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

    <!-- Event Categories -->
    <section id="events" class="py-20 bg-gradient-to-b from-warm-cream to-warm-cream/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-playfair text-deep-brown mb-4">Choose Your Experience</h2>
                <p class="text-xl text-accent-brown max-w-2xl mx-auto">From intimate gatherings to grand celebrations, we craft every detail to perfection</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <!-- Individual Food Items -->
                <div class="glass-effect rounded-3xl p-8 hover-lift border border-accent-brown/20">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-deep-brown mb-4 text-center">À La Carte</h3>
                    <p class="text-accent-brown text-center mb-6">Handpicked specialties from our authentic Italian and Spanish kitchen</p>
                    <button onclick="showCategory('alacarte')" class="w-full bg-rich-brown text-warm-cream py-3 rounded-xl hover:bg-accent-brown transition-colors duration-300 font-semibold">
                        Explore Menu
                    </button>
                </div>

                <!-- Food Packages -->
                <div class="glass-effect rounded-3xl p-8 hover-lift border border-accent-brown/20">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-deep-brown mb-4 text-center">Food Packages</h3>
                    <p class="text-accent-brown text-center mb-6">Curated dining experiences perfect for groups and special occasions</p>
                    <button onclick="showCategory('packages')" class="w-full bg-rich-brown text-warm-cream py-3 rounded-xl hover:bg-accent-brown transition-colors duration-300 font-semibold">
                        View Packages
                    </button>
                </div>

                <!-- Event Packages -->
                <div class="glass-effect rounded-3xl p-8 hover-lift border border-accent-brown/20">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-deep-brown mb-4 text-center">Full Events</h3>
                    <p class="text-accent-brown text-center mb-6">Complete celebration packages with dining, decor, and entertainment</p>
                    <button onclick="showCategory('events')" class="w-full bg-rich-brown text-warm-cream py-3 rounded-xl hover:bg-accent-brown transition-colors duration-300 font-semibold">
                        Plan Event
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Content Area -->
    <section id="content-area" class="py-20 bg-warm-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- À La Carte Menu -->
            <div id="alacarte-content" class="hidden">
                <div class="text-center mb-12">
                    <h3 class="text-4xl font-script text-deep-brown mb-4">Signature Dishes</h3>
                    <p class="text-xl text-accent-brown">Authentic flavors crafted with passion</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-12 mb-12">
                    <div>
                        <h4 class="text-2xl font-semibold text-rich-brown mb-6 border-b-2 border-accent-brown/30 pb-2">Italian Classics</h4>
                        <div class="space-y-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Risotto ai Funghi Porcini</h5>
                                    <p class="text-accent-brown">Creamy Arborio rice with wild porcini mushrooms and truffle oil</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">€28</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Osso Buco alla Milanese</h5>
                                    <p class="text-accent-brown">Braised veal shanks with saffron risotto and gremolata</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">€35</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Tiramisu della Casa</h5>
                                    <p class="text-accent-brown">Traditional mascarpone dessert with espresso and cocoa</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">€12</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-2xl font-semibold text-rich-brown mb-6 border-b-2 border-accent-brown/30 pb-2">Spanish Favorites</h4>
                        <div class="space-y-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Paella Valenciana</h5>
                                    <p class="text-accent-brown">Traditional saffron rice with chicken, rabbit, and vegetables</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">€32</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Jamón Ibérico Selection</h5>
                                    <p class="text-accent-brown">Premium acorn-fed ham with Manchego cheese and quince</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">€42</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Crema Catalana</h5>
                                    <p class="text-accent-brown">Creamy custard with caramelized sugar and cinnamon</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">€10</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Food Packages -->
            <div id="packages-content" class="hidden">
                <div class="text-center mb-12">
                    <h3 class="text-4xl font-script text-deep-brown mb-4">Curated Dining Packages</h3>
                    <p class="text-xl text-accent-brown">Perfect combinations for every occasion</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-effect rounded-2xl p-8 border border-accent-brown/20 hover-lift">
                        <h4 class="text-2xl font-semibold text-deep-brown mb-4">Romantic Dinner</h4>
                        <p class="text-accent-brown mb-6">Perfect for two, includes appetizer, main course, dessert, and wine pairing</p>
                        <ul class="text-sm text-deep-brown space-y-2 mb-6">
                            <li>• Antipasti selection for two</li>
                            <li>• Choice of signature main courses</li>
                            <li>• Shared dessert</li>
                            <li>• Bottle of Italian or Spanish wine</li>
                        </ul>
                        <div class="text-center">
                            <span class="text-3xl font-bold text-rich-brown">€85</span>
                            <p class="text-sm text-accent-brown">per couple</p>
                        </div>
                    </div>
                    
                    <div class="glass-effect rounded-2xl p-8 border border-accent-brown/20 hover-lift">
                        <h4 class="text-2xl font-semibold text-deep-brown mb-4">Family Feast</h4>
                        <p class="text-accent-brown mb-6">Generous portions for 4-6 people with variety and sharing dishes</p>
                        <ul class="text-sm text-deep-brown space-y-2 mb-6">
                            <li>• Large antipasti platter</li>
                            <li>• Paella for the table</li>
                            <li>• Pasta course to share</li>
                            <li>• Selection of desserts</li>
                        </ul>
                        <div class="text-center">
                            <span class="text-3xl font-bold text-rich-brown">€180</span>
                            <p class="text-sm text-accent-brown">serves 4-6</p>
                        </div>
                    </div>
                    
                    <div class="glass-effect rounded-2xl p-8 border border-accent-brown/20 hover-lift">
                        <h4 class="text-2xl font-semibold text-deep-brown mb-4">Business Lunch</h4>
                        <p class="text-accent-brown mb-6">Professional dining experience with efficient service</p>
                        <ul class="text-sm text-deep-brown space-y-2 mb-6">
                            <li>• Express antipasti</li>
                            <li>• Choice of signature mains</li>
                            <li>• Coffee and petit fours</li>
                            <li>• 90-minute time slot</li>
                        </ul>
                        <div class="text-center">
                            <span class="text-3xl font-bold text-rich-brown">€35</span>
                            <p class="text-sm text-accent-brown">per person</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Packages -->
            <div id="events-content" class="hidden">
                <div class="text-center mb-12">
                    <h3 class="text-4xl font-script text-deep-brown mb-4">Complete Event Packages</h3>
                    <p class="text-xl text-accent-brown">Everything you need for unforgettable celebrations</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="glass-effect rounded-2xl p-8 border border-accent-brown/20">
                        <h4 class="text-3xl font-semibold text-deep-brown mb-6">Intimate Celebration</h4>
                        <p class="text-accent-brown mb-6">Perfect for birthdays, anniversaries, or small gatherings (15-30 guests)</p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Private dining area with custom decorations</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">3-course menu with wine pairing</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Professional photography (1 hour)</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Dedicated event coordinator</span>
                            </div>
                        </div>
                        
                        <div class="text-center mb-6">
                            <span class="text-4xl font-bold text-rich-brown">€75</span>
                            <p class="text-accent-brown">per person</p>
                        </div>
                        
                        <button onclick="openBookingForm('intimate')" class="w-full bg-rich-brown text-warm-cream py-3 rounded-xl hover:bg-accent-brown transition-colors duration-300 font-semibold">
                            Book Now
                        </button>
                    </div>
                    
                    <div class="glass-effect rounded-2xl p-8 border border-accent-brown/20">
                        <h4 class="text-3xl font-semibold text-deep-brown mb-6">Grand Celebration</h4>
                        <p class="text-accent-brown mb-6">Luxury events for weddings, corporate events, or large parties (50+ guests)</p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Full venue exclusive use</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">5-course chef's tasting menu</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Live acoustic music performance</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Premium bar service with sommelier</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-rich-brown rounded-full"></div>
                                <span class="text-deep-brown">Complete event planning and coordination</span>
                            </div>
                        </div>
                        
                        <div class="text-center mb-6">
                            <span class="text-4xl font-bold text-rich-brown">€125</span>
                            <p class="text-accent-brown">per person</p>
                        </div>
                        
                        <button onclick="openBookingForm('grand')" class="w-full bg-rich-brown text-warm-cream py-3 rounded-xl hover:bg-accent-brown transition-colors duration-300 font-semibold">
                            Book Now
                        </button>
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
                        <!-- Menu slides content -->
                        <!-- Note: The actual menu slides content will be preserved from the original file -->
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
                                <!-- Service images content -->
                                <!-- Note: The actual service images content will be preserved from the original file -->
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

    <!-- Booking Modal -->
    <div id="booking-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="glass-effect rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-3xl font-playfair text-deep-brown">Book Your Event</h3>
                    <button onclick="closeBookingForm()" class="text-accent-brown hover:text-deep-brown transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="booking-form" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-deep-brown font-semibold mb-2">First Name</label>
                            <input type="text" class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-deep-brown font-semibold mb-2">Last Name</label>
                            <input type="text" class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors" required>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-deep-brown font-semibold mb-2">Email</label>
                            <input type="email" class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-deep-brown font-semibold mb-2">Phone</label>
                            <input type="tel" class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors" required>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-deep-brown font-semibold mb-2">Event Date</label>
                            <input type="date" class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-deep-brown font-semibold mb-2">Number of Guests</label>
                            <select class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors" required>
                                <option value="">Select guest count</option>
                                <option value="15-20">15-20 guests</option>
                                <option value="21-30">21-30 guests</option>
                                <option value="31-50">31-50 guests</option>
                                <option value="51-100">51-100 guests</option>
                                <option value="100+">100+ guests</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-deep-brown font-semibold mb-2">Special Requirements</label>
                        <textarea rows="4" class="w-full p-3 rounded-xl border border-accent-brown/30 bg-warm-cream/50 focus:outline-none focus:border-rich-brown transition-colors resize-none" placeholder="Dietary restrictions, decorations, entertainment preferences..."></textarea>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="button" onclick="closeBookingForm()" class="flex-1 border-2 border-accent-brown text-accent-brown py-3 rounded-xl hover:bg-accent-brown hover:text-warm-cream transition-colors duration-300 font-semibold">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-rich-brown text-warm-cream py-3 rounded-xl hover:bg-accent-brown transition-colors duration-300 font-semibold">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gradient-to-b from-warm-cream to-accent-brown/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-playfair text-deep-brown mb-4">Get in Touch</h2>
                <p class="text-xl text-accent-brown">Let's create something extraordinary together</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-deep-brown mb-2">Visit Us</h4>
                    <p class="text-accent-brown">Via Roma 123<br>Milano, Italy 20121</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-deep-brown mb-2">Call Us</h4>
                    <p class="text-accent-brown">+39 02 1234 5678<br>Mon-Sun: 11:00-23:00</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-deep-brown mb-2">Email Us</h4>
                    <p class="text-accent-brown">events@bellavista.com<br>info@bellavista.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-deep-brown text-warm-cream py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div>
                        <h3 class="font-playfair font-bold text-xl">Caffè Lilio</h3>
                        <p class="text-xs tracking-widest opacity-75">RISTORANTE</p>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-6 mb-8">
                    <a href="https://web.facebook.com/caffelilio.ph" target="_blank" class="text-warm-cream hover:text-rich-brown transition-colors duration-300">
                        <i class="fab fa-facebook-f text-2xl"></i>
                    </a>
                    <a href="https://www.instagram.com/caffelilio.ph/" target="_blank" class="text-warm-cream hover:text-rich-brown transition-colors duration-300">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                </div>
                
                <div class="border-t border-rich-brown pt-8">
                    <p class="font-baskerville opacity-75">
                        © 2024 Caffè Lilio Ristorante. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Global state
        let currentCategory = null;
        let bookingData = {};

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            const spans = mobileMenuBtn.querySelectorAll('span');
            spans.forEach((span, index) => {
                if (mobileMenu.classList.contains('hidden')) {
                    span.style.transform = 'none';
                } else {
                    if (index === 0) span.style.transform = 'translateY(8px) rotate(45deg)';
                    if (index === 1) span.style.opacity = '0';
                    if (index === 2) span.style.transform = 'translateY(-8px) rotate(-45deg)';
                }
            });
        });

        // Smooth scrolling function
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                element.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Show category content
        function showCategory(category) {
            // Hide all content sections
            const contentSections = ['alacarte-content', 'packages-content', 'events-content'];
            contentSections.forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });

            // Show selected content
            const targetContent = category + '-content';
            const element = document.getElementById(targetContent);
            if (element) {
                element.classList.remove('hidden');
                element.classList.add('animate-fade-in');
                
                // Scroll to content area
                document.getElementById('content-area').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            currentCategory = category;
        }

        // Open booking form
        function openBookingForm(packageType) {
            bookingData.packageType = packageType;
            document.getElementById('booking-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        // Close booking form
        function closeBookingForm() {
            document.getElementById('booking-modal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
            document.getElementById('booking-form').reset();
        }

        // Handle form submission
        document.getElementById('booking-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Add package type to data
            data.packageType = bookingData.packageType;
            
            // Simulate form submission
            showSuccessMessage();
            closeBookingForm();
        });

        // Show success message
        function showSuccessMessage() {
            // Create and show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-fade-in';
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold">Booking Request Sent!</h4>
                        <p class="text-sm">We'll contact you within 24 hours to confirm your event details.</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Close modal when clicking outside
        document.getElementById('booking-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingForm();
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.style.backgroundColor = 'rgba(93, 47, 15, 0.95)';
            } else {
                nav.style.backgroundColor = 'transparent';
            }
        });

        // Menu Carousel
        let currentMenuSlide = 0;
        const menuCarousel = document.getElementById('menuCarousel');
        const menuSlides = menuCarousel ? menuCarousel.children.length : 0;
        const menuIndicators = document.querySelectorAll('.carousel-indicator');

        function updateMenuCarousel() {
            if (menuCarousel) {
                menuCarousel.style.transform = `translateX(-${currentMenuSlide * 100}%)`;
                menuIndicators.forEach((indicator, index) => {
                    indicator.style.opacity = index === currentMenuSlide ? '1' : '0.5';
                });
            }
        }

        document.getElementById('prevBtn')?.addEventListener('click', () => {
            if (currentMenuSlide > 0) {
                currentMenuSlide--;
                updateMenuCarousel();
            }
        });

        document.getElementById('nextBtn')?.addEventListener('click', () => {
            if (currentMenuSlide < menuSlides - 1) {
                currentMenuSlide++;
                updateMenuCarousel();
            }
        });

        menuIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentMenuSlide = index;
                updateMenuCarousel();
            });
        });

        // Services Carousel
        let currentServiceSlide = 0;
        const servicesCarousel = document.getElementById('servicesCarousel');
        const serviceSlides = servicesCarousel ? servicesCarousel.children.length : 0;
        const serviceIndicators = document.querySelectorAll('.services-indicator');

        function updateServicesCarousel() {
            if (servicesCarousel) {
                servicesCarousel.style.transform = `translateX(-${currentServiceSlide * 100}%)`;
                serviceIndicators.forEach((indicator, index) => {
                    indicator.style.opacity = index === currentServiceSlide ? '1' : '0.5';
                });
            }
        }

        serviceIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentServiceSlide = index;
                updateServicesCarousel();
            });
        });

        // Auto-advance services carousel
        setInterval(() => {
            if (currentServiceSlide < serviceSlides - 1) {
                currentServiceSlide++;
            } else {
                currentServiceSlide = 0;
            }
            updateServicesCarousel();
        }, 5000);

        // Fade-in animation
        const fadeElements = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        fadeElements.forEach(element => {
            observer.observe(element);
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenuBtn.click();
                    }
                }
            });
        });

        // Initialize carousels
        updateMenuCarousel();
        updateServicesCarousel();
    </script>
</body>
</html>