<?php
$page_title = "Italian Dish - Caffè Lilio";


ob_start();
?>
  

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

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        const navLinks = document.querySelectorAll('.nav-link');
        const navUnderlines = document.querySelectorAll('.nav-link span');
        const navTitle = document.querySelector('.nav-title');
        const navSubtitle = document.querySelector('.nav-subtitle');
        const navButton = document.querySelector('.nav-button');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 0) {
                navbar.classList.add('backdrop-blur-md', 'bg-[#FFF8E7]/90', 'shadow-lg');
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
            } else {
                navbar.classList.remove('backdrop-blur-md', 'bg-[#FFF8E7]/90', 'shadow-lg');
                navLinks.forEach(link => {
                    link.classList.remove('text-[#3C2F2F]', 'hover:text-[#3C2F2F]/80');
                    link.classList.add('text-[#FFF8E7]', 'hover:text-[#FFF8E7]/80');
                });
                navUnderlines.forEach(underline => {
                    underline.classList.remove('bg-[#3C2F2F]');
                    underline.classList.add('bg-[#FFF8E7]');
                });
                navTitle.classList.remove('text-[#3C2F2F]');
                navTitle.classList.add('text-[#FFF8E7]');
                navSubtitle.classList.remove('text-[#3C2F2F]');
                navSubtitle.classList.add('text-[#FFF8E7]');
                if (navButton) {
                    navButton.classList.remove('bg-[#3C2F2F]', 'text-[#FFF8E7]');
                    navButton.classList.add('bg-[#FFF8E7]', 'text-[#3C2F2F]');
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
    </script>
<?php
$content = ob_get_clean();
include 'index_layout.php';
?>