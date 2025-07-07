<?php
// Start output buffering to capture content
ob_start();
?>
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
    <!-- Include Navigation -->
    <?php include 'nav.php'; ?>

    <!-- Content Placeholder -->
    <?php
    // Output the content defined in the specific page
    echo $content ?? '<!-- Page content goes here -->';
    ?>

    <!-- Footer -->
    <footer class="bg-deep-brown text-warm-cream py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div>
                        <h3 class="font-playfair font-bold text-xl sm:text-2xl">Caffè Lilio</h3>
                        <p class="text-xs sm:text-sm tracking-widest opacity-75">RISTORANTE</p>
                    </div>
                </div>
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
                <div class="border-t border-rich-brown pt-6 sm:pt-8">
                    <p class="font-baskerville text-sm sm:text-base opacity-75">
                        © 2025 Caffè Lilio Ristorante. All rights reserved. | 
                        <span class="italic">Authentically Italian and Spanish since 2021</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Support Widget -->
    <div class="fixed bottom-6 right-6 z-50 flex items-end space-x-4">
        <div id="supportWindow" class="support-widget bg-warm-cream rounded-lg overflow-hidden hidden closed flex flex-col" style="height: 500px; width: 320px;">
            <div class="bg-deep-brown text-warm-cream p-4 flex justify-between items-center">
                <h3 class="font-playfair font-bold">Caffè Lilio Support</h3>
                <button id="closeSupport" class="text-warm-cream hover:text-rich-brown transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="chatContent" class="flex-1 overflow-y-auto p-4 space-y-3">
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
            <div class="p-3 border-t border-deep-brown/20" id="inputArea">
                <input type="text" id="userInput" placeholder="Type your question..." class="w-full p-2 border border-deep-brown/30 rounded">
            </div>
        </div>
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
                    const headerOffset = 80;
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

            navLinks.forEach(link => {
                if (isHeroSection) {
                    link.classList.remove('text-deep-brown', 'hover:text-deep-brown/80');
                    link.classList.add('text-warm-cream', 'hover:text-warm-cream/80');
                } else {
                    link.classList.remove('text-warm-cream', 'hover:text-warm-cream/80');
                    link.classList.add('text-deep-brown', 'hover:text-deep-brown/80');
                }
            });

            navUnderlines.forEach(underline => {
                if (isHeroSection) {
                    underline.classList.remove('bg-deep-brown');
                    underline.classList.add('bg-warm-cream');
                } else {
                    underline.classList.remove('bg-warm-cream');
                    underline.classList.add('bg-deep-brown');
                }
            });

            if (isHeroSection) {
                navTitle.classList.remove('text-deep-brown');
                navTitle.classList.add('text-warm-cream');
                navSubtitle.classList.remove('text-deep-brown');
                navSubtitle.classList.add('text-warm-cream');
                if (navButton) {
                    navButton.classList.remove('bg-deep-brown', 'text-warm-cream');
                    navButton.classList.add('bg-warm-cream', 'text-deep-brown');
                }
            } else {
                navTitle.classList.remove('text-warm-cream');
                navTitle.classList.add('text-deep-brown');
                navSubtitle.classList.remove('text-warm-cream');
                navSubtitle.classList.add('text-deep-brown');
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

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Add active state to navigation links
        const sections = document.querySelectorAll('section[id]');

        window.addEventListener('scroll', () => {
            let就被

System: You are Grok 3 built by xAI.

### Updated Response with Complete Implementation

To implement a simple template system in plain PHP for your customer-side website, we’ll create a structure similar to Laravel’s Blade templating system. The navigation bar will be extracted into a separate `nav.php` file, a main layout file `layout_customer.php` will include it and provide a placeholder for page-specific content, and individual pages (`index.php`, `my_reservations.php`, `menu.php`) will use this layout to inject their content. Below, I’ll provide the complete code for each file, ensuring no repetition of the navigation code and maintaining the functionality from your provided `index.php`.

---

### 1. Navigation Bar (`nav.php`)
This file contains the navigation bar HTML extracted from your `index.php`.

<xaiArtifact artifact_id="cbb1a788-a1fb-4e48-8e6a-d469dfb9ecff" artifact_version_id="214a977e-c67e-4266-9e45-0e7169abe696" title="nav.php" contentType="text/html">
<?php
// Define navigation links
$navLinks = [
    ['href' => '#home', 'label' => 'Home'],
    ['href' => '#about', 'label' => 'About Us'],
    ['href' => '#menu', 'label' => 'Menu & Packages'],
    ['href' => '#services', 'label' => 'What We Offer'],
];
?>

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
                <?php foreach ($navLinks as $link): ?>
                    <a href="<?php echo htmlspecialchars($link['href']); ?>" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                        <?php echo htmlspecialchars($link['label']); ?>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                    </a>
                <?php endforeach; ?>
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
            <?php foreach ($navLinks as $link): ?>
                <a href="<?php echo htmlspecialchars($link['href']); ?>" class="block font-baskerville hover:text-rich-brown transition-colors duration-300"><?php echo htmlspecialchars($link['label']); ?></a>
            <?php endforeach; ?>
            
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