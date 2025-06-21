<?php
require_once 'customer_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
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
            will-change: transform;
        }
        
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        .bg-card {
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            backdrop-filter: blur(8px);
        }

        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #E8E0D5; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #8B4513; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #5D2F0F; }

        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }

        .tab-button.active {
            border-color: #8B4513;
            color: #5D2F0F;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-warm-cream text-deep-brown min-h-screen">
    <!-- Navigation -->
    <nav class="bg-warm-cream text-deep-brown shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex-1 flex items-center justify-start">
                    <a href="/" class="flex items-center space-x-2 hover:opacity-90 transition-opacity" aria-label="Home">
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
                </div>
                <div class="flex-1 flex items-center justify-end">
                    <div class="hidden md:flex items-center space-x-0">
                        <!-- User Profile -->
                        <div class="relative group">
                            <a href="profile.php" class="flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors duration-300 text-deep-brown hover:text-deep-brown/80"
                                    aria-label="User menu">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=E8E0D5&color=5D2F0F" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full border border-deep-brown/30">
                                <span class="font-baskerville">John Doe</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="font-playfair text-4xl font-bold mb-8 text-deep-brown">My Profile</h2>

        <!-- Tab Navigation -->
        <div class="mb-8 border-b border-deep-brown/20">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button id="profile-tab" class="tab-button active py-4 px-1 border-b-2 font-medium text-lg leading-5 transition-colors duration-300" data-tab="profile">
                    Profile Information
                </button>
                <button id="bookings-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-lg leading-5 text-deep-brown/60 hover:text-deep-brown transition-colors duration-300" data-tab="bookings">
                    My Bookings
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- Profile Information Tab -->
            <div id="profile-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-card rounded-xl p-6 shadow-md">
                            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Edit Information</h3>
                            <form class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first-name" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">First Name</label>
                                        <input type="text" id="first-name" value="John" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                    </div>
                                    <div>
                                        <label for="last-name" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">Last Name</label>
                                        <input type="text" id="last-name" value="Doe" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                    </div>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">Email Address</label>
                                    <input type="email" id="email" value="john.doe@example.com" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">Phone Number</label>
                                    <input type="tel" id="phone" value="+1234567890" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div class="pt-4 border-t border-deep-brown/10">
                                    <button type="submit" class="bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="bg-card rounded-xl p-6 shadow-md">
                            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Change Password</h3>
                            <form class="space-y-6">
                                <div>
                                    <label for="current-password" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">Current Password</label>
                                    <input type="password" id="current-password" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div>
                                    <label for="new-password" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">New Password</label>
                                    <input type="password" id="new-password" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div>
                                    <label for="confirm-password" class="block text-sm font-baskerville font-medium text-deep-brown/80 mb-1">Confirm New Password</label>
                                    <input type="password" id="confirm-password" class="w-full px-4 py-2 bg-warm-cream/50 border border-deep-brown/20 rounded-lg focus:ring-rich-brown focus:border-rich-brown">
                                </div>
                                <div class="pt-4 border-t border-deep-brown/10">
                                    <button type="submit" class="bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Bookings Tab -->
            <div id="bookings-content" class="hidden">
                <div class="bg-card rounded-xl p-6 shadow-md">
                    <div class="overflow-x-auto">
                        <table class="w-full" role="table">
                            <thead>
                                <tr class="border-b border-deep-brown/20">
                                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Date & Time</th>
                                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Event Type</th>
                                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Guests</th>
                                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Status</th>
                                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-deep-brown/10 hover:bg-deep-brown/5 transition-colors duration-300">
                                    <td class="py-4 px-4">
                                        <div class="font-baskerville text-deep-brown">March 15, 2024</div>
                                        <div class="text-sm text-deep-brown/60">7:00 PM</div>
                                    </td>
                                    <td class="py-4 px-4 font-baskerville text-deep-brown">Dinner Reservation</td>
                                    <td class="py-4 px-4 font-baskerville text-deep-brown">4</td>
                                    <td class="py-4 px-4">
                                        <span class="bg-warm-cream/50 text-deep-brown px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center border border-deep-brown/10">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Confirmed
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex space-x-2">
                                            <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 p-2 rounded-full hover:bg-warm-cream"><i class="fas fa-edit"></i></button>
                                            <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-warm-cream"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                 <tr class="hover:bg-deep-brown/5 transition-colors duration-300">
                                    <td class="py-4 px-4">
                                        <div class="font-baskerville text-deep-brown">March 20, 2024</div>
                                        <div class="text-sm text-deep-brown/60">6:30 PM</div>
                                    </td>
                                    <td class="py-4 px-4 font-baskerville text-deep-brown">Birthday Celebration</td>
                                    <td class="py-4 px-4 font-baskerville text-deep-brown">12</td>
                                    <td class="py-4 px-4">
                                        <span class="bg-yellow-100/50 text-yellow-800 px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex space-x-2">
                                            <button class="text-deep-brown hover:text-rich-brown transition-colors duration-300 p-2 rounded-full hover:bg-deep-brown/10"><i class="fas fa-edit"></i></button>
                                            <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-red-50"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-deep-brown text-warm-cream mt-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6">
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
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = {
                profile: document.getElementById('profile-content'),
                bookings: document.getElementById('bookings-content'),
            };

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tab;

                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    for (const content in contents) {
                        if (content === target) {
                            contents[content].classList.remove('hidden');
                        } else {
                            contents[content].classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html> 