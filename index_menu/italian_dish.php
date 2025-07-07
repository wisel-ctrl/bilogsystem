<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';

// Set page title
$page_title = "Italian Dish Menu - Caffè Lilio";

// Capture content
ob_start();
?>

<!-- Hero Section with ::after overlay -->
<section class="relative py-12 mb-12 overflow-hidden bg-cover bg-center rounded-md" 
         style="background-image: url(/images/italian-hero.jpg);">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black opacity-60"></div>
    
    <!-- Decorative elements -->
    <div class="absolute inset-0 opacity-10 z-10">
        <div class="absolute top-10 left-10 w-32 h-32 border border-deep-brown/20 rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 border border-deep-brown/20 rounded-full animate-pulse"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-20">
        <h2 class="font-playfair text-5xl font-bold text-warm-cream mb-4 animate-fade-in-down">Italian Dish Menu</h2>
        <p class="font-baskerville text-xl text-warm-cream max-w-2xl mx-auto animate-fade-in-up">
            Savor the authentic flavors of Italy with our carefully crafted dishes, made with the freshest ingredients.
        </p>
    </div>
</section>

<!-- Menu Section -->
<section class="mb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Menu Item 1 -->
        <div class="bg-white/70 rounded-xl p-6 shadow-lg hover-lift border border-deep-brown/10 animate-fade-in-up">
            <div class="relative">
                <img src="/images/italian/margherita-pizza.jpg" alt="Margherita Pizza" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">Margherita Pizza</h3>
                <div class="font-baskerville text-lg text-rich-brown mb-2">$12.99</div>
                <p class="font-baskerville text-sm text-deep-brown/80 leading-relaxed">
                    Classic Italian pizza with fresh tomatoes, mozzarella, basil, and a drizzle of olive oil.
                </p>
            </div>
        </div>
        <!-- Menu Item 2 -->
        <div class="bg-white/70 rounded-xl p-6 shadow-lg hover-lift border border-deep-brown/10 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="relative">
                <img src="/images/italian/spaghetti-carbonara.jpg" alt="Spaghetti Carbonara" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">Spaghetti Carbonara</h3>
                <div class="font-baskerville text-lg text-rich-brown mb-2">$14.50</div>
                <p class="font-baskerville text-sm text-deep-brown/80 leading-relaxed">
                    Creamy pasta with pancetta, egg, Parmesan cheese, and a touch of black pepper.
                </p>
            </div>
        </div>
        <!-- Menu Item 3 -->
        <div class="bg-white/70 rounded-xl p-6 shadow-lg hover-lift border border-deep-brown/10 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="relative">
                <img src="/images/italian/lasagna.jpg" alt="Lasagna" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">Lasagna</h3>
                <div class="font-baskerville text-lg text-rich-brown mb-2">$16.75</div>
                <p class="font-baskerville text-sm text-deep-brown/80 leading-relaxed">
                    Layers of pasta, rich meat sauce, béchamel, and melted mozzarella cheese.
                </p>
            </div>
        </div>
        <!-- Menu Item 4 -->
        <div class="bg-white/70 rounded-xl p-6 shadow-lg hover-lift border border-deep-brown/10 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="relative">
                <img src="/images/italian/risotto-mushroom.jpg" alt="Mushroom Risotto" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">Mushroom Risotto</h3>
                <div class="font-baskerville text-lg text-rich-brown mb-2">$15.25</div>
                <p class="font-baskerville text-sm text-deep-brown/80 leading-relaxed">
                    Creamy Arborio rice with wild mushrooms, Parmesan, and a hint of white wine.
                </p>
            </div>
        </div>
        <!-- Menu Item 5 -->
        <div class="bg-white/70 rounded-xl p-6 shadow-lg hover-lift border border-deep-brown/10 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="relative">
                <img src="/images/italian/tiramisu.jpg" alt="Tiramisu" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-2">Tiramisu</h3>
                <div class="font-baskerville text-lg text-rich-brown mb-2">$8.99</div>
                <p class="font-baskerville text-sm text-deep-brown/80 leading-relaxed">
                    Traditional Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Page-specific CSS -->
<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<!-- Page-specific JavaScript -->
<script>
    // Intersection Observer for fade-in animations
    document.addEventListener('DOMContentLoaded', function() {
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
        document.querySelectorAll('.animate-fade-in-up').forEach(el => observer.observe(el));
    });
</script>

<?php
// Capture content and include layout
$content = ob_get_clean();
include 'layout_customer.php';
?>