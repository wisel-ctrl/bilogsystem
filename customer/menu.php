<?php
require_once 'customer_auth.php';
// Set page title
$page_title = "Menu - Caffè Lilio";

// Capture content
ob_start();
?>

<section id="menu" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="font-serif text-4xl md:text-5xl font-bold text-brown-900 mb-6">Our Menu</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-800 to-amber-600 mx-auto mb-8"></div>
            <p class="font-serif text-lg text-brown-800 max-w-4xl mx-auto leading-relaxed">
                Discover our carefully curated menu featuring the finest Italian and Spanish dishes. From classic favorites to unique specialties.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Best-Sellers -->
            <a href="/customer/customer_menu/best_sellers.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/Calamari.jpg" alt="Best Sellers" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Best-Sellers</h3>
                    </div>
                </div>
            </a>

            <!-- Main Course -->
            <a href="../customer_menu/main_course.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/maincourse.jpg" alt="Main Course" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Main Course</h3>
                    </div>
                </div>
            </a>

            <!-- Salad -->
            <a href="../customer_menu/salad.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/Salad.jpg" alt="Salad" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">House Salad</h3>
                    </div>
                </div>
            </a>

            <!-- Italian Dish -->
            <a href="../customer_menu/italian_dish.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/italian_dish.jpg" alt="Italian Dish" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Italian Dish</h3>
                    </div>
                </div>
            </a>

            <!-- Spanish Dish -->
            <a href="../customer_menu/spanish_dish.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/spanish_dish.jpg" alt="Spanish Dish" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Spanish Dish</h3>
                    </div>
                </div>
            </a>

            <!-- Coffee -->
            <a href="../customer_menu/coffee.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/coffee.png" alt="Coffee" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Coffee</h3>
                    </div>
                </div>
            </a>

            <!-- Drinks -->
            <a href="../customer_menu/drinks.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/drinks.png" alt="Drinks" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Drinks</h3>
                    </div>
                </div>
            </a>

            <!-- Pizza & Burgers -->
            <a href="../customer_menu/burger_pizza.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/burgerpizza.png" alt="Burgers & Pizza" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Burgers & Pizza</h3>
                    </div>
                </div>
            </a>

            <!-- Pasta -->
            <a href="../customer_menu/pasta.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/Carbonara.jpg" alt="Pasta" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Pasta</h3>
                    </div>
                </div>
            </a>

            <!-- Pasta e Caza -->
            <a href="../customer_menu/pasta_ecaza.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/pastaEcaza.png" alt="Pasta e Caza" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Pasta e Caza</h3>
                    </div>
                </div>
            </a>

            <!-- Desserts -->
            <a href="../customer_menu/desserts.php" class="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer">
                <div class="aspect-[3/4] relative">
                    <img src="../images/desserts.jpg" alt="Desserts" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <h3 class="font-serif text-xl font-bold text-amber-50 text-center px-4">Desserts</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>



<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>