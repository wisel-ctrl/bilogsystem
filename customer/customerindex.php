<?php
require_once 'customer_auth.php'; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bella Vista - Event Management</title>
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
    <nav class="glass-effect fixed w-full top-0 z-50 border-b border-accent-brown/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-rich-brown to-deep-brown rounded-full flex items-center justify-center">
                        <span class="text-warm-cream font-playfair text-xl">BV</span>
                    </div>
                    <h1 class="text-2xl font-playfair text-deep-brown">Bella Vista</h1>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-deep-brown hover:text-rich-brown transition-colors duration-300 font-semibold">Home</a>
                    <a href="#menu" class="text-deep-brown hover:text-rich-brown transition-colors duration-300 font-semibold">Menu</a>
                    <a href="#events" class="text-deep-brown hover:text-rich-brown transition-colors duration-300 font-semibold">Events</a>
                    <a href="#contact" class="text-deep-brown hover:text-rich-brown transition-colors duration-300 font-semibold">Contact</a>
                </div>
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg bg-rich-brown text-warm-cream">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
        <div class="absolute inset-0 bg-gradient-to-br from-warm-cream via-warm-cream to-accent-brown/10"></div>
        <div class="floating absolute top-1/4 left-1/4 w-32 h-32 bg-rich-brown/10 rounded-full blur-xl"></div>
        <div class="floating absolute bottom-1/4 right-1/4 w-48 h-48 bg-accent-brown/10 rounded-full blur-xl" style="animation-delay: -3s;"></div>
        
        <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
            <h1 class="text-6xl md:text-8xl font-playfair text-deep-brown mb-6 animate-fade-in">
                Unforgettable Events
            </h1>
            <p class="text-xl md:text-2xl text-accent-brown mb-8 animate-slide-in max-w-2xl mx-auto">
                Experience the authentic flavors of Italy and Spain in an atmosphere designed for your most special moments
            </p>
            <button onclick="scrollToSection('events')" class="bg-gradient-to-r from-rich-brown to-accent-brown text-warm-cream px-12 py-4 rounded-full text-lg font-semibold hover-lift shadow-2xl">
                Plan Your Event
            </button>
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
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-rich-brown to-accent-brown rounded-full flex items-center justify-center">
                            <span class="text-warm-cream font-playfair text-xl">BV</span>
                        </div>
                        <h3 class="text-2xl font-playfair">Bella Vista</h3>
                    </div>
                    <p class="text-warm-cream/80 mb-4 max-w-md">Where authentic Italian and Spanish flavors meet exceptional hospitality. Creating unforgettable moments one celebration at a time.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-rich-brown rounded-full flex items-center justify-center hover:bg-accent-brown transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-rich-brown rounded-full flex items-center justify-center hover:bg-accent-brown transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.118.112.219.083.402-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z.017-.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-rich-brown rounded-full flex items-center justify-center hover:bg-accent-brown transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-warm-cream/80">
                        <li><a href="#home" class="hover:text-warm-cream transition-colors">Home</a></li>
                        <li><a href="#menu" class="hover:text-warm-cream transition-colors">Menu</a></li>
                        <li><a href="#events" class="hover:text-warm-cream transition-colors">Events</a></li>
                        <li><a href="#contact" class="hover:text-warm-cream transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-warm-cream/80">
                        <li>Private Dining</li>
                        <li>Wedding Events</li>
                        <li>Corporate Catering</li>
                        <li>Wine Tastings</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-warm-cream/20 mt-8 pt-8 text-center text-warm-cream/60">
                <p>&copy; 2025 Bella Vista Restaurant. All rights reserved. | Privacy Policy | Terms of Service</p>
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
            if (window.scrollY > 100) {
                nav.classList.add('backdrop-blur-md');
                nav.classList.remove('glass-effect');
            } else {
                nav.classList.remove('backdrop-blur-md');
                nav.classList.add('glass-effect');
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            const elementsToAnimate = document.querySelectorAll('.hover-lift, .glass-effect');
            elementsToAnimate.forEach(el => observer.observe(el));
        });

        // Add smooth hover effects to navigation links
        document.querySelectorAll('nav a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                scrollToSection(targetId);
            });
        });
    </script>
</body>
</html>