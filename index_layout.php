

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
    <?php include 'index_nav.php'; ?>

    <!-- Content Placeholder -->
    <?php echo $content; ?>

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
    
    <!-- Support Widget -->
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

    <!-- JavaScript -->
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

