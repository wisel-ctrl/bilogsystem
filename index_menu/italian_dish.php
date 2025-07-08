<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Italian Dish Menu - Caffè Lilio</title>
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
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
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
    </style>
</head>
<body class="smooth-scroll bg-warm-cream text-deep-brown">
    <!-- Navigation (Reused from index.php) -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <h1 class="nav-title font-playfair font-bold text-xl text-[#FFF8E7]">Caffè Lilio</h1>
                        <p class="nav-subtitle text-xs text-[#FFF8E7] tracking-widest">RISTORANTE</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="/index.php#home" class="nav-link font-baskerville text-[#FFF8E7] hover:text-[#FFF8E7]/80 transition-colors duration-300 relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/index.php#about" class="nav-link font-baskerville text-[#FFF8E7] hover:text-[#FFF8E7]/80 transition-colors duration-300 relative group">
                        About Us
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/index.php#menu" class="nav-link font-baskerville text-[#FFF8E7] hover:text-[#FFF8E7]/80 transition-colors duration-300 relative group">
                        Menu & Packages
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/index.php#services" class="nav-link font-baskerville text-[#FFF8E7] hover:text-[#FFF8E7]/80 transition-colors duration-300 relative group">
                        What We Offer
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="login.php" class="nav-link font-baskerville text-[#FFF8E7] hover:text-[#FFF8E7]/80 transition-colors duration-300 relative group">
                        Login
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="register.php" class="nav-button font-baskerville bg-[#FFF8E7] text-[#3C2F2F] px-4 py-2 rounded-full transition-all duration-300">
                        Register
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden focus:outline-none" id="mobile-menu-btn">
                    <div class="w-6 h-6 flex flex-col justify-center space-y-1">
                        <span class="block w-full h-0.5 bg-[#3C2F2F] transition-all duration-300"></span>
                        <span class="block w-full h-0.5 bg-[#3C2F2F] transition-all duration-300"></span>
                        <span class="block w-full h-0.5 bg-[#3C2F2F] transition-all duration-300"></span>
                    </div>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-[#FFF8E7] backdrop-blur-md bg-opacity-90" id="mobile-menu">
            <div class="px-4 py-4 space-y-4">
                <a href="/index.php#home" class="block font-baskerville text-[#3C2F2F] hover:text-[#8B5A2B] transition-colors duration-300">Home</a>
                <a href="/index.php#about" class="block font-baskerville text-[#3C2F2F] hover:text-[#8B5A2B] transition-colors duration-300">About Us</a>
                <a href="/index.php#menu" class="block font-baskerville text-[#3C2F2F] hover:text-[#8B5A2B] transition-colors duration-300">Menu & Packages</a>
                <a href="/index.php#services" class="block font-baskerville text-[#3C2F2F] hover:text-[#8B5A2B] transition-colors duration-300">What We Offer</a>
                
                <div class="pt-4 border-t border-[#3C2F2F]/10">
                    <a href="login.php" class="block w-full text-left font-baskerville text-[#3C2F2F] hover:text-[#8B5A2B] transition-colors duration-300 mb-3">
                        Login
                    </a>
                    <a href="register.php" class="block w-full font-baskerville bg-[#3C2F2F] text-[#FFF8E7] px-4 py-2 rounded-full hover:bg-[#8B5A2B] transition-all duration-300">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Italian Dish Menu Section -->
    <section id="italian-dish" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h1 class="font-playfair text-5xl md:text-6xl font-bold text-[#3C2F2F] mb-6">Italian Dish Menu</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-[#8B5A2B] to-[#6B4E31] mx-auto mb-8"></div>
                <p class="font-baskerville text-xl text-[#4A3728] max-w-4xl mx-auto leading-relaxed">
                    Indulge in our authentic Italian dishes, crafted with the finest ingredients and traditional recipes.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Menu Item 1 -->
                <div class="hover-lift fade-in">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                        <img src="images/italian/margherita-pizza.jpg" alt="Margherita Pizza" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-playfair text-xl font-bold text-[#3C2F2F] mb-2">Margherita Pizza</h3>
                            <div class="text-lg text-[#8B5A2B] mb-3">$12.99</div>
                            <p class="text-sm text-[#4A3728] leading-relaxed">
                                Classic Italian pizza with fresh tomatoes, mozzarella, basil, and a drizzle of olive oil.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Menu Item 2 -->
                <div class="hover-lift fade-in">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                        <img src="images/italian/spaghetti-carbonara.jpg" alt="Spaghetti Carbonara" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-playfair text-xl font-bold text-[#3C2F2F] mb-2">Spaghetti Carbonara</h3>
                            <div class="text-lg text-[#8B5A2B] mb-3">$14.50</div>
                            <p class="text-sm text-[#4A3728] leading-relaxed">
                                Creamy pasta with pancetta, egg, Parmesan cheese, and a touch of black pepper.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Menu Item 3 -->
                <div class="hover-lift fade-in">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                        <img src="/images/lasagna.jpg" alt="Lasagna" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-playfair text-xl font-bold text-[#3C2F2F] mb-2">Lasagna</h3>
                            <div class="text-lg text-[#8B5A2B] mb-3">$16.75</div>
                            <p class="text-sm text-[#4A3728] leading-relaxed">
                                Layers of pasta, rich meat sauce, béchamel, and melted mozzarella cheese.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Menu Item 4 -->
                <div class="hover-lift fade-in">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                        <img src="images/italian/risotto-mushroom.jpg" alt="Mushroom Risotto" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-playfair text-xl font-bold text-[#3C2F2F] mb-2">Mushroom Risotto</h3>
                            <div class="text-lg text-[#8B5A2B] mb-3">$15.25</div>
                            <p class="text-sm text-[#4A3728] leading-relaxed">
                                Creamy Arborio rice with wild mushrooms, Parmesan, and a hint of white wine.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Menu Item 5 -->
                <div class="hover-lift fade-in">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                        <img src="images/italian/tiramisu.jpg" alt="Tiramisu" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-playfair text-xl font-bold text-[#3C2F2F] mb-2">Tiramisu</h3>
                            <div class="text-lg text-[#8B5A2B] mb-3">$8.99</div>
                            <p class="text-sm text-[#4A3728] leading-relaxed">
                                Traditional Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



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

    // Navbar fixed background and text color (no scroll effect)
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const navUnderlines = document.querySelectorAll('.nav-link span');
    const navTitle = document.querySelector('.nav-title');
    const navSubtitle = document.querySelector('.nav-subtitle');
    const navButton = document.querySelector('.nav-button');

    // Set fixed style immediately and remove scroll listener
    navbar.classList.add('bg-[#FFF8E7]', 'shadow-lg');
    navbar.classList.remove('bg-[#FFF8E7]/90', 'backdrop-blur-md');

    navLinks.forEach(link => {
        link.classList.remove('text-[#FFF8E7]', 'hover:text-[#FFF8E7]/80');
        link.classList.add('text-[#3C2F2F]', 'hover:text-[#3C2F2F]/80');
    });

    navUnderlines.forEach(underline => {
        underline.classList.remove('bg-[#FFF8E7]');
        underline.classList.add('bg-[#3C2F2F]');
    });

    navTitle.classList.remove('text-[#FFF8E7]');
    navTitle.classList.add('text-[#3C2F2F]');

    navSubtitle.classList.remove('text-[#FFF8E7]');
    navSubtitle.classList.add('text-[#3C2F2F]');

    if (navButton) {
        navButton.classList.remove('bg-[#FFF8E7]', 'text-[#3C2F2F]');
        navButton.classList.add('bg-[#3C2F2F]', 'text-[#FFF8E7]');
    }

    // Disable scroll listener since we don't want to change anything on scroll
    // window.addEventListener('scroll', ...) — removed

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
</script>


</body>
</html>