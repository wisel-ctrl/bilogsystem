<?php
require_once 'customer_auth.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(139, 69, 19, 0.2);
        }
    </style>
</head>
<body class="bg-amber-50 text-deep-brown">
    <!-- Navigation -->
    <nav class="bg-deep-brown text-warm-cream shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <div>
                        <h1 class="font-playfair font-bold text-xl">Caffè Lilio</h1>
                        <p class="text-xs tracking-widest">RISTORANTE</p>
                    </div>
                    <div class="hidden md:flex space-x-6">
                        <a href="#dashboard" class="font-baskerville hover:text-warm-cream/80 transition-colors duration-300">Dashboard</a>
                        <a href="#reservations" class="font-baskerville hover:text-warm-cream/80 transition-colors duration-300">My Reservations</a>
                        <a href="#menu" class="font-baskerville hover:text-warm-cream/80 transition-colors duration-300">Menu</a>
                        <a href="#contact" class="font-baskerville hover:text-warm-cream/80 transition-colors duration-300">Contact</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <button class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=E8E0D5&color=8B4513" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-full">
                            <span class="font-baskerville">John Doe</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-warm-cream rounded-lg shadow-lg py-2 hidden group-hover:block">
                            <a href="#profile" class="block px-4 py-2 text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300">Profile Settings</a>
                            <a href="#notifications" class="block px-4 py-2 text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300">Notifications</a>
                            <hr class="my-2 border-deep-brown/20">
                            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-300">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <section class="mb-12">
            <h2 class="font-playfair text-4xl font-bold mb-4">Welcome back, John!</h2>
            <p class="font-baskerville text-lg text-deep-brown/80">Here's what's happening with your reservations and upcoming events.</p>
        </section>

        <!-- Quick Actions -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-warm-cream rounded-xl p-6 shadow-md hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-playfair text-xl font-bold">New Reservation</h3>
                    <span class="text-2xl">📅</span>
                </div>
                <p class="font-baskerville mb-4">Book a table or plan your next event with us.</p>
                <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown transition-colors duration-300">
                    Make Reservation
                </button>
            </div>

            <div class="bg-warm-cream rounded-xl p-6 shadow-md hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-playfair text-xl font-bold">View Menu</h3>
                    <span class="text-2xl">🍽️</span>
                </div>
                <p class="font-baskerville mb-4">Explore our latest dishes and seasonal specials.</p>
                <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown transition-colors duration-300">
                    Browse Menu
                </button>
            </div>

            <div class="bg-warm-cream rounded-xl p-6 shadow-md hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-playfair text-xl font-bold">Contact Support</h3>
                    <span class="text-2xl">💬</span>
                </div>
                <p class="font-baskerville mb-4">Need help? Our team is here to assist you.</p>
                <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown transition-colors duration-300">
                    Get Help
                </button>
            </div>
        </section>

        <!-- Upcoming Reservations -->
        <section class="mb-12">
            <h3 class="font-playfair text-2xl font-bold mb-6">Upcoming Reservations</h3>
            <div class="bg-warm-cream rounded-xl p-6 shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-deep-brown/20">
                                <th class="text-left py-3 px-4 font-baskerville">Date & Time</th>
                                <th class="text-left py-3 px-4 font-baskerville">Event Type</th>
                                <th class="text-left py-3 px-4 font-baskerville">Guests</th>
                                <th class="text-left py-3 px-4 font-baskerville">Status</th>
                                <th class="text-left py-3 px-4 font-baskerville">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-deep-brown/10">
                                <td class="py-4 px-4">
                                    <div class="font-baskerville">March 15, 2024</div>
                                    <div class="text-sm text-deep-brown/60">7:00 PM</div>
                                </td>
                                <td class="py-4 px-4 font-baskerville">Dinner Reservation</td>
                                <td class="py-4 px-4 font-baskerville">4</td>
                                <td class="py-4 px-4">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-baskerville">
                                        Confirmed
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-700 transition-colors duration-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">
                                    <div class="font-baskerville">March 20, 2024</div>
                                    <div class="text-sm text-deep-brown/60">6:30 PM</div>
                                </td>
                                <td class="py-4 px-4 font-baskerville">Birthday Celebration</td>
                                <td class="py-4 px-4 font-baskerville">12</td>
                                <td class="py-4 px-4">
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-baskerville">
                                        Pending
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-700 transition-colors duration-300">
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

        <!-- Special Offers -->
        <section class="mb-12">
            <h3 class="font-playfair text-2xl font-bold mb-6">Special Offers</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-warm-cream rounded-xl overflow-hidden shadow-md hover-lift">
                    <img src="../images/01_buffet.jpg" alt="Special Buffet Offer" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h4 class="font-playfair text-xl font-bold mb-2">Weekend Buffet Special</h4>
                        <p class="font-baskerville mb-4">Enjoy our premium buffet selection at 20% off every weekend.</p>
                        <div class="flex items-center justify-between">
                            <span class="font-baskerville font-bold text-lg">₱1,760/person</span>
                            <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown transition-colors duration-300">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-warm-cream rounded-xl overflow-hidden shadow-md hover-lift">
                    <img src="../images/cheesewheelpasta.jpg" alt="Cheese Wheel Pasta" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h4 class="font-playfair text-xl font-bold mb-2">Cheese Wheel Experience</h4>
                        <p class="font-baskerville mb-4">Try our famous cheese wheel pasta preparation, now with a complimentary glass of wine.</p>
                        <div class="flex items-center justify-between">
                            <span class="font-baskerville font-bold text-lg">₱850/person</span>
                            <button class="bg-deep-brown text-warm-cream px-4 py-2 rounded-lg font-baskerville hover:bg-rich-brown transition-colors duration-300">
                                Reserve Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Activity -->
        <section>
            <h3 class="font-playfair text-2xl font-bold mb-6">Recent Activity</h3>
            <div class="bg-warm-cream rounded-xl p-6 shadow-md">
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="bg-deep-brown/10 rounded-full p-2">
                            <i class="fas fa-calendar-check text-deep-brown"></i>
                        </div>
                        <div>
                            <p class="font-baskerville font-bold">Reservation Confirmed</p>
                            <p class="text-sm text-deep-brown/60">Your reservation for March 15 has been confirmed.</p>
                            <p class="text-sm text-deep-brown/60">2 hours ago</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="bg-deep-brown/10 rounded-full p-2">
                            <i class="fas fa-receipt text-deep-brown"></i>
                        </div>
                        <div>
                            <p class="font-baskerville font-bold">Payment Processed</p>
                            <p class="text-sm text-deep-brown/60">Payment for Birthday Celebration deposit received.</p>
                            <p class="text-sm text-deep-brown/60">1 day ago</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="bg-deep-brown/10 rounded-full p-2">
                            <i class="fas fa-star text-deep-brown"></i>
                        </div>
                        <div>
                            <p class="font-baskerville font-bold">Review Posted</p>
                            <p class="text-sm text-deep-brown/60">Thank you for reviewing your last visit!</p>
                            <p class="text-sm text-deep-brown/60">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-deep-brown text-warm-cream py-8 mt-12">
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
        // Add any necessary JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Handle reservation cancellation
            document.querySelectorAll('.fa-trash').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to cancel this reservation?')) {
                        // Add cancellation logic here
                        console.log('Reservation cancelled');
                    }
                });
            });

            // Example: Handle reservation editing
            document.querySelectorAll('.fa-edit').forEach(button => {
                button.addEventListener('click', function() {
                    // Add edit logic here
                    console.log('Edit reservation');
                });
            });
        });
    </script>
</body>
</html>
