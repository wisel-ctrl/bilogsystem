<?php
require_once 'customer_auth.php'; ?>
<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>
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
        
        /* Update navigation transition styles */
        nav {
            transition: all 0.3s ease-in-out;
        }
        
        nav.scrolled {
            background-color: rgba(232, 224, 213, 0.95);
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        nav.scrolled .nav-link,
        nav.scrolled .nav-title,
        nav.scrolled .nav-subtitle {
            color: #5D2F0F;
        }
        
        nav:not(.scrolled) {
            background-color: rgba(93, 47, 15, 0.8); /* Semi-transparent deep brown */
            backdrop-filter: blur(4px);
        }
        
        nav:not(.scrolled) .nav-link,
        nav:not(.scrolled) .nav-title,
        nav:not(.scrolled) .nav-subtitle {
            color: #E8E0D5;
        }
        
        nav:not(.scrolled) .nav-link:hover {
            color: rgba(232, 224, 213, 0.8);
        }
        
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
                
                <!-- Profile Dropdown -->
                <div class="hidden md:block relative">
                    <button id="profileDropdownBtn" class="flex items-center space-x-2 text-warm-cream hover:text-warm-cream/80 transition-colors duration-300">
                        <span class="font-baskerville">My Profile</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 rounded-xl overflow-hidden shadow-lg glass-effect border border-accent-brown/20 z-50">
                        <a href="profile.php" class="block px-4 py-3 text-deep-brown hover:bg-accent-brown hover:text-warm-cream transition-colors duration-300">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>View Profile</span>
                            </div>
                        </a>
                        <a href="../logout.php" class="block px-4 py-3 text-deep-brown hover:bg-accent-brown hover:text-warm-cream transition-colors duration-300">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Logout</span>
                            </div>
                        </a>
                    </div>
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
                    <a href="profile.php" class="block w-full text-left font-baskerville hover:text-rich-brown transition-colors duration-300 mb-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>My Profile</span>
                        </div>
                    </a>
                    <a href="../logout.php" class="block w-full font-baskerville bg-deep-brown text-warm-cream px-4 py-2 rounded-full hover:bg-rich-brown transition-all duration-300">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
        <div class="absolute inset-0 bg-gradient-to-br from-warm-cream via-warm-cream to-accent-brown/10"></div>
        <div class="floating absolute top-1/4 left-1/4 w-32 h-32 bg-rich-brown/10 rounded-full blur-xl"></div>
        <div class="floating absolute bottom-1/4 right-1/4 w-48 h-48 bg-accent-brown/10 rounded-full blur-xl" style="animation-delay: -3s;"></div>
        
        <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
            <h1 class="text-3xl md:text-6xl font-script text-deep-brown mb-6 animate-fade-in">
            <h1 class="text-3xl md:text-6xl font-playfair text-deep-brown mb-6 animate-fade-in">
                Savor the Flavors of Spain and Italy
            </h1>
            <button onclick="scrollToSection('events')" class="bg-gradient-to-r from-rich-brown to-accent-brown text-warm-cream font-playfair px-12 py-4 rounded-full text-lg font-semibold hover-lift shadow-2xl">
                Plan Your Event
            </button>
        </div>
    </section>



    <!-- Menu Section -->
    <section id="menu" class="py-20 bg-gradient-to-b from-warm-cream to-warm-cream/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-playfair text-deep-brown mb-4">Our Menu</h2>
                <p class="text-xl text-accent-brown max-w-2xl mx-auto">Experience our carefully curated selection of authentic Italian and Spanish dishes</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <!-- À La Carte -->
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
                    <h3 class="text-2xl font-semibold text-deep-brown mb-4 text-center">Event Packages</h3>
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
                    <h3 class="text-4xl font-playfair text-deep-brown mb-4">Our Signature Dishes</h3>
                    <p class="text-xl text-accent-brown">Authentic Italian and Spanish flavors crafted with passion</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-12 mb-12">
                    <div>
                        <h4 class="text-2xl font-semibold text-rich-brown mb-6 border-b-2 border-accent-brown/30 pb-2">Italian Specialties</h4>
                        <div class="space-y-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Risotto ai Funghi Porcini</h5>
                                    <p class="text-accent-brown">Creamy Arborio rice with wild porcini mushrooms and truffle oil</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">₱580</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Osso Buco alla Milanese</h5>
                                    <p class="text-accent-brown">Braised veal shanks with saffron risotto and gremolata</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">₱850</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Tiramisu della Casa</h5>
                                    <p class="text-accent-brown">Traditional mascarpone dessert with espresso and cocoa</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">₱280</span>
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
                                <span class="text-rich-brown font-bold ml-4">₱750</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Jamón Ibérico Selection</h5>
                                    <p class="text-accent-brown">Premium acorn-fed ham with Manchego cheese and quince</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">₱980</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-semibold text-deep-brown">Crema Catalana</h5>
                                    <p class="text-accent-brown">Creamy custard with caramelized sugar and cinnamon</p>
                                </div>
                                <span class="text-rich-brown font-bold ml-4">₱250</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Food Packages -->
            <div id="packages-content" class="hidden">
                <div class="text-center mb-12">
                    <h3 class="text-4xl font-playfair text-deep-brown mb-4">Curated Dining Packages</h3>
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
                            <span class="text-3xl font-bold text-rich-brown">₱2,500</span>
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
                            <span class="text-3xl font-bold text-rich-brown">₱3,800</span>
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
                            <span class="text-3xl font-bold text-rich-brown">₱850</span>
                            <p class="text-sm text-accent-brown">per person</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Packages -->
            <div id="events-content" class="hidden">
                <div class="text-center mb-12">
                    <h3 class="text-4xl font-playfair text-deep-brown mb-4">Complete Event Packages</h3>
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
                            <span class="text-4xl font-bold text-rich-brown">₱1,800</span>
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
                            <span class="text-4xl font-bold text-rich-brown">₱2,500</span>
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
                    <p class="text-accent-brown">123 Gat Tayaw St.<br>Liliw, Laguna 4004</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-deep-brown mb-2">Call Us</h4>
                    <p class="text-accent-brown">+63 49 123 4567<br>Mon-Sun: 10:00-22:00</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-warm-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-deep-brown mb-2">Email Us</h4>
                    <p class="text-accent-brown">events@caffelilio.com<br>info@caffelilio.com</p>
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
                        © 2024 Caffè Lilio Ristorante. All rights reserved. | 
                        <span class="italic">Authentically Italian and Spanish since 2021</span>
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
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            // Mobile menu implementation would go here
            alert('Mobile menu functionality - implement dropdown menu');
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
            const scrollPosition = window.scrollY;
            
            if (scrollPosition > 50) {
                nav.classList.add('scrolled');
                nav.classList.remove('glass-effect');
            } else {
                nav.classList.remove('scrolled');
                nav.classList.add('glass-effect');
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

        // Initialize Profile Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdownBtn = document.getElementById('profileDropdownBtn');
            const profileDropdown = document.getElementById('profileDropdown');
            let isDropdownOpen = false;

            if (profileDropdownBtn && profileDropdown) {
                profileDropdownBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    isDropdownOpen = !isDropdownOpen;
                    if (isDropdownOpen) {
                        profileDropdown.classList.remove('hidden');
                        profileDropdown.classList.add('animate-fade-in');
                    } else {
                        profileDropdown.classList.add('hidden');
                        profileDropdown.classList.remove('animate-fade-in');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (isDropdownOpen && !profileDropdown.contains(e.target) && e.target !== profileDropdownBtn) {
                        isDropdownOpen = false;
                        profileDropdown.classList.add('hidden');
                        profileDropdown.classList.remove('animate-fade-in');
                    }
                });

                // Update dropdown colors based on scroll position
                window.addEventListener('scroll', () => {
                    const isHeroSection = window.scrollY === 0;
                    
                    if (isHeroSection) {
                        profileDropdownBtn.classList.remove('text-deep-brown');
                        profileDropdownBtn.classList.add('text-warm-cream');
                    } else {
                        profileDropdownBtn.classList.remove('text-warm-cream');
                        profileDropdownBtn.classList.add('text-deep-brown');
                    }
                });
            }
        });
    </script>
</body>
</html>