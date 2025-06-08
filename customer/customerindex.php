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
                        'serif': ['Georgia', 'serif'],
                        'script': ['Brush Script MT', 'cursive']
                    }
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(139, 69, 19, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(160, 82, 45, 0.1) 0%, transparent 50%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(93, 47, 15, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #8B4513, #A0522D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes pulse-glow {
            from { box-shadow: 0 4px 20px rgba(139, 69, 19, 0.3); }
            to { box-shadow: 0 8px 40px rgba(139, 69, 19, 0.5); }
        }
    </style>
</head>
<body class="bg-warm-cream min-h-screen">
    <!-- Header -->
    <header class="bg-deep-brown shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-rich-brown rounded-full flex items-center justify-center">
                        <span class="text-warm-cream font-script text-xl">BV</span>
                    </div>
                    <div>
                        <h1 class="text-warm-cream font-script text-2xl">Bella Vista</h1>
                        <p class="text-warm-cream/80 text-sm">Italian & Spanish Cuisine</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#events" class="text-warm-cream hover:text-accent-brown transition-colors">Events</a>
                    <a href="#packages" class="text-warm-cream hover:text-accent-brown transition-colors">Packages</a>
                    <a href="#gallery" class="text-warm-cream hover:text-accent-brown transition-colors">Gallery</a>
                    <a href="#contact" class="text-warm-cream hover:text-accent-brown transition-colors">Contact</a>
                </nav>
                <button id="mobileMenuBtn" class="md:hidden text-warm-cream">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-pattern py-20 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="font-script text-6xl md:text-7xl gradient-text mb-6 floating-animation">
                Memorable Events
            </h2>
            <p class="text-deep-brown text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Experience the perfect blend of Italian passion and Spanish flair for your special occasions
            </p>
            <button id="exploreBtn" class="bg-rich-brown text-warm-cream px-8 py-4 rounded-full text-lg font-semibold hover:bg-accent-brown transition-all pulse-glow">
                Explore Our Events
            </button>
        </div>
    </section>

    <!-- Event Categories -->
    <section id="events" class="py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-4xl font-script text-deep-brown text-center mb-12">Our Event Experiences</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-rich-brown to-accent-brown relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-6xl">🎉</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-2xl font-serif text-deep-brown mb-3">Private Celebrations</h4>
                        <p class="text-gray-600 mb-4">Intimate gatherings for birthdays, anniversaries, and special milestones with authentic Mediterranean flavors.</p>
                        <button class="event-category-btn bg-rich-brown text-warm-cream px-6 py-2 rounded-full hover:bg-accent-brown transition-colors" data-category="celebrations">
                            View Events
                        </button>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-accent-brown to-rich-brown relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-6xl">💼</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-2xl font-serif text-deep-brown mb-3">Corporate Events</h4>
                        <p class="text-gray-600 mb-4">Professional gatherings, team building, and business dinners in an elegant Mediterranean atmosphere.</p>
                        <button class="event-category-btn bg-rich-brown text-warm-cream px-6 py-2 rounded-full hover:bg-accent-brown transition-colors" data-category="corporate">
                            View Events
                        </button>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-deep-brown to-rich-brown relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-6xl">💒</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-2xl font-serif text-deep-brown mb-3">Wedding Events</h4>
                        <p class="text-gray-600 mb-4">Romantic celebrations with traditional Italian and Spanish wedding customs and culinary excellence.</p>
                        <button class="event-category-btn bg-rich-brown text-warm-cream px-6 py-2 rounded-full hover:bg-accent-brown transition-colors" data-category="weddings">
                            View Events
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Listings -->
    <section id="eventListings" class="py-16 px-4 bg-white/50">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-4xl font-script text-deep-brown">Available Events</h3>
                <select id="eventFilter" class="bg-white border-2 border-rich-brown rounded-lg px-4 py-2 text-deep-brown">
                    <option value="all">All Events</option>
                    <option value="celebrations">Private Celebrations</option>
                    <option value="corporate">Corporate Events</option>
                    <option value="weddings">Wedding Events</option>
                </select>
            </div>
            
            <div id="eventGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Events will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Event Details Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 id="modalTitle" class="text-3xl font-script text-deep-brown"></h3>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <div id="modalContent" class="space-y-4">
                    <!-- Modal content will be populated by JavaScript -->
                </div>
                <div class="mt-6 flex space-x-4">
                    <button id="bookEventBtn" class="bg-rich-brown text-warm-cream px-8 py-3 rounded-full hover:bg-accent-brown transition-colors flex-1">
                        Book This Event
                    </button>
                    <button id="inquireBtn" class="border-2 border-rich-brown text-rich-brown px-8 py-3 rounded-full hover:bg-rich-brown hover:text-warm-cream transition-colors flex-1">
                        Make Inquiry
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Form Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-script text-deep-brown">Book Your Event</h3>
                    <button id="closeBookingModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <form id="bookingForm" class="space-y-4">
                    <div>
                        <label class="block text-deep-brown font-serif mb-2">Full Name</label>
                        <input type="text" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-rich-brown outline-none" required>
                    </div>
                    <div>
                        <label class="block text-deep-brown font-serif mb-2">Email</label>
                        <input type="email" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-rich-brown outline-none" required>
                    </div>
                    <div>
                        <label class="block text-deep-brown font-serif mb-2">Phone</label>
                        <input type="tel" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-rich-brown outline-none" required>
                    </div>
                    <div>
                        <label class="block text-deep-brown font-serif mb-2">Preferred Date</label>
                        <input type="date" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-rich-brown outline-none" required>
                    </div>
                    <div>
                        <label class="block text-deep-brown font-serif mb-2">Number of Guests</label>
                        <select class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-rich-brown outline-none" required>
                            <option value="">Select guest count</option>
                            <option value="10-20">10-20 guests</option>
                            <option value="21-50">21-50 guests</option>
                            <option value="51-100">51-100 guests</option>
                            <option value="100+">100+ guests</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-deep-brown font-serif mb-2">Special Requests</label>
                        <textarea class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-rich-brown outline-none h-24" placeholder="Any dietary restrictions, special arrangements, etc."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-rich-brown text-warm-cream py-3 rounded-full hover:bg-accent-brown transition-colors">
                        Submit Booking Request
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-deep-brown text-warm-cream py-12 px-4">
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="font-script text-2xl mb-4">Bella Vista</h4>
                <p class="text-warm-cream/80 mb-4">Bringing the authentic flavors of Italy and Spain to your special moments.</p>
                <div class="flex space-x-4">
                    <div class="w-8 h-8 bg-rich-brown rounded-full flex items-center justify-center cursor-pointer hover:bg-accent-brown transition-colors">
                        <span class="text-xs">f</span>
                    </div>
                    <div class="w-8 h-8 bg-rich-brown rounded-full flex items-center justify-center cursor-pointer hover:bg-accent-brown transition-colors">
                        <span class="text-xs">@</span>
                    </div>
                    <div class="w-8 h-8 bg-rich-brown rounded-full flex items-center justify-center cursor-pointer hover:bg-accent-brown transition-colors">
                        <span class="text-xs">in</span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-serif text-xl mb-4">Contact Info</h4>
                <div class="space-y-2 text-warm-cream/80">
                    <p>📍 123 Mediterranean Ave, City Center</p>
                    <p>📞 (555) 123-4567</p>
                    <p>✉️ events@bellavista.com</p>
                </div>
            </div>
            <div>
                <h4 class="font-serif text-xl mb-4">Operating Hours</h4>
                <div class="space-y-2 text-warm-cream/80">
                    <p>Monday - Thursday: 5:00 PM - 10:00 PM</p>
                    <p>Friday - Saturday: 5:00 PM - 11:00 PM</p>
                    <p>Sunday: 4:00 PM - 9:00 PM</p>
                    <p class="text-accent-brown">Events: By Appointment</p>
                </div>
            </div>
        </div>
        <div class="border-t border-rich-brown mt-8 pt-8 text-center text-warm-cream/60">
            <p>&copy; 2025 Bella Vista Restaurant. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Event data
        const events = {
            celebrations: [
                {
                    id: 1,
                    title: "Birthday Celebration Package",
                    price: "$45/person",
                    duration: "3 hours",
                    capacity: "15-30 guests",
                    description: "A personalized birthday experience featuring a selection of Italian antipasti, Spanish tapas, and a custom birthday dessert.",
                    features: ["Custom birthday cake", "Decorative setup", "Dedicated server", "Photo opportunities", "Wine pairing available"]
                },
                {
                    id: 2,
                    title: "Anniversary Dinner",
                    price: "$65/person",
                    duration: "2.5 hours",
                    capacity: "2-20 guests",
                    description: "Romantic candlelit dinner with a curated menu of Mediterranean classics perfect for celebrating love.",
                    features: ["Romantic ambiance", "Premium wine selection", "Live acoustic music", "Personalized menu", "Rose petals setup"]
                },
                {
                    id: 3,
                    title: "Family Reunion Feast",
                    price: "$38/person",
                    duration: "4 hours",
                    capacity: "20-60 guests",
                    description: "A hearty family-style meal bringing everyone together with traditional recipes passed down through generations.",
                    features: ["Family-style service", "Kids menu available", "Extended dining time", "Photo slideshow setup", "Flexible dietary options"]
                }
            ],
            corporate: [
                {
                    id: 4,
                    title: "Business Lunch Meeting",
                    price: "$35/person",
                    duration: "2 hours",
                    capacity: "8-25 guests",
                    description: "Professional lunch setting with Mediterranean-inspired menu perfect for business discussions.",
                    features: ["Private dining area", "AV equipment", "Wi-Fi access", "Business-friendly atmosphere", "Express service"]
                },
                {
                    id: 5,
                    title: "Team Building Dinner",
                    price: "$42/person",
                    duration: "3 hours",
                    capacity: "15-40 guests",
                    description: "Interactive dining experience with cooking demonstrations and team activities.",
                    features: ["Cooking demonstration", "Team activities", "Welcome drinks", "Group photos", "Customizable menu"]
                },
                {
                    id: 6,
                    title: "Holiday Office Party",
                    price: "$55/person",
                    duration: "4 hours",
                    capacity: "25-80 guests",
                    description: "Festive celebration with holiday-themed decorations and seasonal Mediterranean specialties.",
                    features: ["Holiday decorations", "Entertainment options", "Open bar package", "Dance floor", "Photo booth"]
                }
            ],
            weddings: [
                {
                    id: 7,
                    title: "Intimate Wedding Reception",
                    price: "$85/person",
                    duration: "5 hours",
                    capacity: "30-60 guests",
                    description: "Elegant wedding celebration with authentic Italian and Spanish traditions woven throughout the evening.",
                    features: ["Bridal suite preparation", "Custom wedding cake", "Live music", "Floral arrangements", "Photography assistance"]
                },
                {
                    id: 8,
                    title: "Rehearsal Dinner",
                    price: "$55/person",
                    duration: "3 hours",
                    capacity: "15-40 guests",
                    description: "Relaxed pre-wedding gathering with family-style Mediterranean dishes and warm hospitality.",
                    features: ["Welcome cocktail hour", "Family-style dining", "Toast coordination", "Intimate setting", "Flexible timeline"]
                },
                {
                    id: 9,
                    title: "Engagement Celebration",
                    price: "$48/person",
                    duration: "3 hours",
                    capacity: "20-50 guests",
                    description: "Joyful engagement party with sparkling cocktails and Mediterranean delicacies to celebrate your love story.",
                    features: ["Champagne toast", "Engagement photo display", "Romantic lighting", "Personalized menu", "Gift table setup"]
                }
            ]
        };

        let currentFilter = 'all';
        let selectedEvent = null;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            displayEvents(currentFilter);
            setupEventListeners();
        });

        function setupEventListeners() {
            // Filter events
            document.getElementById('eventFilter').addEventListener('change', function(e) {
                currentFilter = e.target.value;
                displayEvents(currentFilter);
            });

            // Category buttons
            document.querySelectorAll('.event-category-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    currentFilter = category;
                    document.getElementById('eventFilter').value = category;
                    displayEvents(category);
                    document.getElementById('eventListings').scrollIntoView({ behavior: 'smooth' });
                });
            });

            // Explore button
            document.getElementById('exploreBtn').addEventListener('click', function() {
                document.getElementById('events').scrollIntoView({ behavior: 'smooth' });
            });

            // Modal controls
            document.getElementById('closeModal').addEventListener('click', closeEventModal);
            document.getElementById('closeBookingModal').addEventListener('click', closeBookingModal);
            
            document.getElementById('bookEventBtn').addEventListener('click', function() {
                closeEventModal();
                openBookingModal();
            });

            document.getElementById('inquireBtn').addEventListener('click', function() {
                alert('Thank you for your interest! We\'ll contact you within 24 hours to discuss your event details.');
                closeEventModal();
            });

            // Booking form
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your booking request! We\'ll confirm your event details within 24 hours.');
                closeBookingModal();
                this.reset();
            });

            // Close modals when clicking outside
            document.getElementById('eventModal').addEventListener('click', function(e) {
                if (e.target === this) closeEventModal();
            });

            document.getElementById('bookingModal').addEventListener('click', function(e) {
                if (e.target === this) closeBookingModal();
            });
        }

        function displayEvents(filter) {
            const eventGrid = document.getElementById('eventGrid');
            let eventsToShow = [];

            if (filter === 'all') {
                eventsToShow = [...events.celebrations, ...events.corporate, ...events.weddings];
            } else {
                eventsToShow = events[filter] || [];
            }

            eventGrid.innerHTML = eventsToShow.map(event => `
                <div class="card-hover bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer" onclick="openEventModal(${event.id})">
                    <div class="h-40 bg-gradient-to-br from-rich-brown via-accent-brown to-deep-brown relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-3xl text-warm-cream font-script">${event.title}</div>
                                <div class="text-warm-cream/80 text-lg">${event.price}</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">⏱️ ${event.duration}</span>
                            <span class="text-sm text-gray-600">👥 ${event.capacity}</span>
                        </div>
                        <p class="text-gray-700 text-sm line-clamp-2">${event.description}</p>
                        <button class="mt-3 bg-rich-brown text-warm-cream px-4 py-2 rounded-full text-sm hover:bg-accent-brown transition-colors">
                            View Details
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function openEventModal(eventId) {
            const allEvents = [...events.celebrations, ...events.corporate, ...events.weddings];
            selectedEvent = allEvents.find(event => event.id === eventId);
            
            if (!selectedEvent) return;

            document.getElementById('modalTitle').textContent = selectedEvent.title;
            document.getElementById('modalContent').innerHTML = `
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-4 bg-warm-cream rounded-lg">
                        <div class="text-2xl font-script text-rich-brown">${selectedEvent.price}</div>
                        <div class="text-sm text-gray-600">Per Person</div>
                    </div>
                    <div class="text-center p-4 bg-warm-cream rounded-lg">
                        <div class="text-lg font-serif text-rich-brown">${selectedEvent.duration}</div>
                        <div class="text-sm text-gray-600">Duration</div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="text-center p-3 bg-warm-cream rounded-lg">
                        <div class="text-lg font-serif text-rich-brown">${selectedEvent.capacity}</div>
                        <div class="text-sm text-gray-600">Guest Capacity</div>
                    </div>
                </div>
                <div class="mb-6">
                    <h4 class="text-lg font-serif text-deep-brown mb-2">Event Description</h4>
                    <p class="text-gray-700">${selectedEvent.description}</p>
                </div>
                <div>
                    <h4 class="text-lg font-serif text-deep-brown mb-3">What's Included</h4>
                    <ul class="space-y-2">
                        ${selectedEvent.features.map(feature => `
                            <li class="flex items-center text-gray-700">
                                <span class="text-rich-brown mr-2">✓</span>
                                ${feature}
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;

            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('eventModal').classList.add('flex');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
            document.getElementById('eventModal').classList.remove('flex');
        }

        function openBookingModal() {
            document.getElementById('bookingModal').classList.remove('hidden');
            document.getElementById('bookingModal').classList.add('flex');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.getElementById('bookingModal').classList.remove('flex');
        }
    </script>
</body>
</html>