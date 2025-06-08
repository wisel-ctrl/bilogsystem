<?php
require_once 'customer_auth.php'; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bella Vita - Italian & Spanish Cuisine Events</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap');
        
        .fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .fade-in-delay-1 { animation-delay: 0.2s; }
        .fade-in-delay-2 { animation-delay: 0.4s; }
        .fade-in-delay-3 { animation-delay: 0.6s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(232, 224, 213, 0.8);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #8B4513, #A0522D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body class="bg-warm-cream font-serif">
    <!-- Header -->
    <header class="bg-deep-brown text-warm-cream shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="text-3xl">🍝</div>
                    <div>
                        <h1 class="text-2xl font-bold font-script">Bella Vita</h1>
                        <p class="text-sm opacity-90">Italian & Spanish Cuisine</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="#home" class="hover:text-accent-brown transition-colors">Home</a>
                    <a href="#menu" class="hover:text-accent-brown transition-colors">Menu</a>
                    <a href="#events" class="hover:text-accent-brown transition-colors">Events</a>
                    <a href="#packages" class="hover:text-accent-brown transition-colors">Packages</a>
                    <a href="#contact" class="hover:text-accent-brown transition-colors">Contact</a>
                </nav>
                <button class="md:hidden text-2xl">☰</button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-rich-brown/20 to-accent-brown/30"></div>
        <div class="relative z-10 text-center px-6 max-w-4xl">
            <h1 class="text-6xl md:text-8xl font-script text-deep-brown mb-6 fade-in floating">
                Bella Vita
            </h1>
            <p class="text-xl md:text-2xl text-rich-brown mb-8 fade-in fade-in-delay-1">
                Authentic Italian & Spanish Flavors for Your Special Moments
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in fade-in-delay-2">
                <button onclick="scrollToSection('events')" class="bg-rich-brown text-warm-cream px-8 py-4 rounded-full hover:bg-deep-brown transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Plan Your Event
                </button>
                <button onclick="scrollToSection('menu')" class="border-2 border-rich-brown text-rich-brown px-8 py-4 rounded-full hover:bg-rich-brown hover:text-warm-cream transition-all duration-300 transform hover:scale-105">
                    View Menu
                </button>
            </div>
        </div>
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-rich-brown rounded-full flex justify-center">
                <div class="w-1 h-3 bg-rich-brown rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Menu Highlights -->
    <section id="menu" class="py-20 bg-gradient-to-b from-warm-cream to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-script gradient-text mb-4">Our Signature Dishes</h2>
                <p class="text-xl text-rich-brown max-w-2xl mx-auto">
                    Discover the authentic flavors of Italy and Spain, crafted with passion and tradition
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="hover-lift bg-white rounded-2xl shadow-lg p-6 border-2 border-warm-cream">
                    <div class="text-4xl mb-4 text-center">🍝</div>
                    <h3 class="text-2xl font-bold text-deep-brown mb-3">Italian Classics</h3>
                    <ul class="text-rich-brown space-y-2">
                        <li>• Homemade Pasta Carbonara</li>
                        <li>• Wood-fired Margherita Pizza</li>
                        <li>• Osso Buco alla Milanese</li>
                        <li>• Tiramisu</li>
                    </ul>
                </div>
                
                <div class="hover-lift bg-white rounded-2xl shadow-lg p-6 border-2 border-warm-cream">
                    <div class="text-4xl mb-4 text-center">🥘</div>
                    <h3 class="text-2xl font-bold text-deep-brown mb-3">Spanish Delights</h3>
                    <ul class="text-rich-brown space-y-2">
                        <li>• Authentic Paella Valenciana</li>
                        <li>• Jamón Ibérico Selection</li>
                        <li>• Patatas Bravas</li>
                        <li>• Churros con Chocolate</li>
                    </ul>
                </div>
                
                <div class="hover-lift bg-white rounded-2xl shadow-lg p-6 border-2 border-warm-cream">
                    <div class="text-4xl mb-4 text-center">🍷</div>
                    <h3 class="text-2xl font-bold text-deep-brown mb-3">Wine & Beverages</h3>
                    <ul class="text-rich-brown space-y-2">
                        <li>• Premium Italian Wines</li>
                        <li>• Spanish Rioja Selection</li>
                        <li>• Craft Sangria</li>
                        <li>• Espresso & Italian Coffee</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Packages -->
    <section id="events" class="py-20 bg-deep-brown text-warm-cream">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-script mb-4">Event Packages</h2>
                <p class="text-xl max-w-2xl mx-auto opacity-90">
                    Let us make your special occasion unforgettable with our tailored event packages
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Intimate Dinner Package -->
                <div class="glass-effect rounded-2xl p-8 hover-lift border border-warm-cream/20">
                    <div class="text-center mb-6">
                        <div class="text-5xl mb-4">💕</div>
                        <h3 class="text-2xl font-bold mb-2">Intimate Dinner</h3>
                        <p class="text-3xl font-bold text-accent-brown">$80/person</p>
                    </div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>3-course meal</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Wine pairing</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Candlelit ambiance</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Reserved seating</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>2-10 guests</li>
                    </ul>
                    <button onclick="openBookingModal('intimate')" class="w-full bg-accent-brown hover:bg-rich-brown text-warm-cream py-3 rounded-full transition-colors">
                        Book Now
                    </button>
                </div>
                
                <!-- Family Celebration Package -->
                <div class="glass-effect rounded-2xl p-8 hover-lift border border-warm-cream/20 pulse-animation">
                    <div class="text-center mb-6">
                        <div class="text-5xl mb-4">👨‍👩‍👧‍👦</div>
                        <h3 class="text-2xl font-bold mb-2">Family Celebration</h3>
                        <p class="text-3xl font-bold text-accent-brown">$65/person</p>
                        <span class="bg-accent-brown text-warm-cream px-3 py-1 rounded-full text-sm">Popular</span>
                    </div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Family-style dining</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Kids menu available</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Private dining area</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Birthday cake option</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>10-25 guests</li>
                    </ul>
                    <button onclick="openBookingModal('family')" class="w-full bg-accent-brown hover:bg-rich-brown text-warm-cream py-3 rounded-full transition-colors">
                        Book Now
                    </button>
                </div>
                
                <!-- Corporate Event Package -->
                <div class="glass-effect rounded-2xl p-8 hover-lift border border-warm-cream/20">
                    <div class="text-center mb-6">
                        <div class="text-5xl mb-4">💼</div>
                        <h3 class="text-2xl font-bold mb-2">Corporate Event</h3>
                        <p class="text-3xl font-bold text-accent-brown">$95/person</p>
                    </div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Business lunch/dinner</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>AV equipment</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Private meeting room</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>Catering options</li>
                        <li class="flex items-center"><span class="text-accent-brown mr-2">✓</span>15-50 guests</li>
                    </ul>
                    <button onclick="openBookingModal('corporate')" class="w-full bg-accent-brown hover:bg-rich-brown text-warm-cream py-3 rounded-full transition-colors">
                        Book Now
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Food Packages -->
    <section id="packages" class="py-20 bg-gradient-to-b from-warm-cream to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-script gradient-text mb-4">Catering Packages</h2>
                <p class="text-xl text-rich-brown max-w-2xl mx-auto">
                    Take our authentic flavors to your location with our professional catering services
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Italian Package -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover-lift border-2 border-warm-cream">
                    <div class="flex items-center mb-6">
                        <div class="text-6xl mr-4">🇮🇹</div>
                        <div>
                            <h3 class="text-3xl font-bold text-deep-brown">Italian Package</h3>
                            <p class="text-accent-brown">Minimum 20 people</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-xl font-bold text-rich-brown mb-3">Appetizers</h4>
                            <p class="text-gray-700">Bruschetta, Antipasto platter, Caprese skewers</p>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-rich-brown mb-3">Main Courses</h4>
                            <p class="text-gray-700">Pasta selection, Wood-fired pizza, Chicken Parmigiana</p>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-rich-brown mb-3">Desserts</h4>
                            <p class="text-gray-700">Tiramisu, Cannoli, Gelato selection</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t-2 border-warm-cream">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-deep-brown">From $45/person</span>
                        </div>
                        <button onclick="openBookingModal('italian-catering')" class="w-full bg-rich-brown hover:bg-deep-brown text-warm-cream py-3 rounded-full transition-colors">
                            Request Quote
                        </button>
                    </div>
                </div>
                
                <!-- Spanish Package -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover-lift border-2 border-warm-cream">
                    <div class="flex items-center mb-6">
                        <div class="text-6xl mr-4">🇪🇸</div>
                        <div>
                            <h3 class="text-3xl font-bold text-deep-brown">Spanish Package</h3>
                            <p class="text-accent-brown">Minimum 20 people</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-xl font-bold text-rich-brown mb-3">Tapas Selection</h4>
                            <p class="text-gray-700">Patatas bravas, Croquetas, Jamón Ibérico</p>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-rich-brown mb-3">Main Courses</h4>
                            <p class="text-gray-700">Paella Valenciana, Grilled seafood, Chorizo platter</p>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-rich-brown mb-3">Desserts</h4>
                            <p class="text-gray-700">Churros, Flan, Crema Catalana</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t-2 border-warm-cream">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-deep-brown">From $50/person</span>
                        </div>
                        <button onclick="openBookingModal('spanish-catering')" class="w-full bg-rich-brown hover:bg-deep-brown text-warm-cream py-3 rounded-full transition-colors">
                            Request Quote
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-deep-brown text-warm-cream">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-script mb-4">Contact Us</h2>
                <p class="text-xl opacity-90">Ready to plan your perfect event? Get in touch with our team</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto">
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="text-2xl">📍</div>
                        <div>
                            <h4 class="font-bold">Address</h4>
                            <p class="opacity-90">123 Culinary Street, Food District<br>City, State 12345</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-2xl">📞</div>
                        <div>
                            <h4 class="font-bold">Phone</h4>
                            <p class="opacity-90">(555) 123-4567</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-2xl">✉️</div>
                        <div>
                            <h4 class="font-bold">Email</h4>
                            <p class="opacity-90">events@bellavita.com</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-2xl">🕒</div>
                        <div>
                            <h4 class="font-bold">Hours</h4>
                            <p class="opacity-90">Mon-Thu: 11AM-10PM<br>Fri-Sat: 11AM-11PM<br>Sun: 12PM-9PM</p>
                        </div>
                    </div>
                </div>
                
                <div class="glass-effect p-6 rounded-2xl">
                    <h3 class="text-2xl font-bold mb-4">Send us a message</h3>
                    <form class="space-y-4">
                        <input type="text" placeholder="Your Name" class="w-full p-3 rounded-lg bg-white/20 border border-warm-cream/30 text-warm-cream placeholder-warm-cream/70">
                        <input type="email" placeholder="Your Email" class="w-full p-3 rounded-lg bg-white/20 border border-warm-cream/30 text-warm-cream placeholder-warm-cream/70">
                        <textarea placeholder="Your Message" rows="4" class="w-full p-3 rounded-lg bg-white/20 border border-warm-cream/30 text-warm-cream placeholder-warm-cream/70"></textarea>
                        <button type="submit" class="w-full bg-accent-brown hover:bg-rich-brown text-warm-cream py-3 rounded-lg transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-warm-cream py-8">
        <div class="container mx-auto px-6 text-center">
            <div class="flex justify-center items-center space-x-4 mb-4">
                <div class="text-2xl">🍝</div>
                <h3 class="text-xl font-script">Bella Vita</h3>
            </div>
            <p class="opacity-80 mb-4">Authentic Italian & Spanish Cuisine</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="hover:text-accent-brown transition-colors">Facebook</a>
                <a href="#" class="hover:text-accent-brown transition-colors">Instagram</a>
                <a href="#" class="hover:text-accent-brown transition-colors">Twitter</a>
            </div>
            <p class="mt-4 text-sm opacity-60">&copy; 2025 Bella Vita. All rights reserved.</p>
        </div>
    </footer>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-deep-brown">Book Your Event</h3>
                <button onclick="closeBookingModal()" class="text-2xl text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            
            <form id="bookingForm" class="space-y-4">
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Package Type</label>
                    <input type="text" id="packageType" readonly class="w-full p-3 rounded-lg border-2 border-warm-cream bg-gray-50">
                </div>
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Your Name</label>
                    <input type="text" required class="w-full p-3 rounded-lg border-2 border-warm-cream focus:border-rich-brown">
                </div>
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Email</label>
                    <input type="email" required class="w-full p-3 rounded-lg border-2 border-warm-cream focus:border-rich-brown">
                </div>
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Phone</label>
                    <input type="tel" required class="w-full p-3 rounded-lg border-2 border-warm-cream focus:border-rich-brown">
                </div>
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Event Date</label>
                    <input type="date" required class="w-full p-3 rounded-lg border-2 border-warm-cream focus:border-rich-brown">
                </div>
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Number of Guests</label>
                    <input type="number" required min="1" class="w-full p-3 rounded-lg border-2 border-warm-cream focus:border-rich-brown">
                </div>
                <div>
                    <label class="block text-rich-brown font-bold mb-2">Special Requests</label>
                    <textarea rows="3" class="w-full p-3 rounded-lg border-2 border-warm-cream focus:border-rich-brown"></textarea>
                </div>
                <button type="submit" class="w-full bg-rich-brown hover:bg-deep-brown text-warm-cream py-3 rounded-lg transition-colors">
                    Submit Booking Request
                </button>
            </form>
        </div>
    </div>

    <script>
        // Smooth scrolling
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Modal functions
        function openBookingModal(packageType) {
            const modal = document.getElementById('bookingModal');
            const packageInput = document.getElementById('packageType');
            
            const packageNames = {
                'intimate': 'Intimate Dinner Package',
                'family': 'Family Celebration Package',
                'corporate': 'Corporate Event Package',
                'italian-catering': 'Italian Catering Package',
                'spanish-catering': 'Spanish Catering Package'
            };
            
            packageInput.value = packageNames[packageType] || packageType;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingModal() {
            const modal = document.getElementById('bookingModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('bookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you! Your booking request has been submitted. We will contact you within 24 hours to confirm the details.');
            closeBookingModal();
            this.reset();
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe elements for animations
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.hover-lift, .glass-effect');
            animatedElements.forEach(el => observer.observe(el));
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('shadow-2xl');
            } else {
                header.classList.remove('shadow-2xl');
            }
        });
    </script>
</body>
</html>