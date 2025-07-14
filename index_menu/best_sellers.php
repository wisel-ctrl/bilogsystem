<?php
require_once '../db_connect.php';

try {
    // Query to fetch dishes ordered by total quantity
    $stmt = $conn->prepare("
        SELECT 
            d.dish_name,
            d.dish_description,
            d.price,
            d.dish_pic_url,
            GROUP_CONCAT(i.ingredient_name ORDER BY i.ingredient_name SEPARATOR ', ') AS ingredients,
            SUM(o.quantity) AS total_quantity
        FROM order_tb o
        JOIN dishes_tb d ON o.dish_id = d.dish_id
        LEFT JOIN dish_ingredients di ON d.dish_id = di.dish_id
        LEFT JOIN ingredients_tb i ON di.ingredient_id = i.ingredient_id
        WHERE d.status = 'active'
        GROUP BY d.dish_id, d.dish_name, d.dish_description, d.price, d.dish_pic_url
        ORDER BY total_quantity DESC
    ");
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best-Seller Menu - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../tailwind.js"></script>

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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 90%;
            width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            cursor: pointer;
            font-size: 24px;
            color: #3C2F2F;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="smooth-scroll bg-warm-cream text-deep-brown">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <h1 class="nav-title font-playfair font-bold text-xl text-deep-brown">Caffè Lilio</h1>
                        <p class="nav-subtitle text-xs text-rich-brown/80 tracking-widest">RISTORANTE</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="/index.php#home" class="nav-link font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/index.php#about" class="nav-link font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        About Us
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/index.php#menu" class="nav-link font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Menu & Packages
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/index.php#services" class="nav-link font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        What We Offer
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 text-deep-brown transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="../login.php" class="nav-link font-baskerville text-deep-brown hover:text-deep-brown/80 transition-colors duration-300 relative group">
                        Login
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#FFF8E7] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="../register.php" class="nav-button font-baskerville bg-deep-brown text-warm-cream px-4 py-2 rounded-full transition-all duration-300">
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
                    <a href="../login.php" class="block w-full text-left font-baskerville text-[#3C2F2F] hover:text-[#8B5A2B] transition-colors duration-300 mb-3">
                        Login
                    </a>
                    <a href="../register.php" class="block w-full font-baskerville bg-[#3C2F2F] text-[#FFF8E7] px-4 py-2 rounded-full hover:bg-[#8B5A2B] transition-all duration-300">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section id="best-sellers" class="pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-extrabold text-deep-brown mb-4 tracking-tight">Best Sellers</h1>
                <div class="w-32 h-1 bg-gradient-to-r from-amber-600 to-amber-800 mx-auto mb-6"></div>
                <p class="font-baskerville text-lg sm:text-xl text-rich-brown/80 max-w-3xl mx-auto leading-relaxed">
                    Discover our most popular dishes, loved by our guests for their authentic flavors and quality ingredients.
                </p>
            </div>

            <div id="menu-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 auto-rows-fr">
                <?php foreach ($dishes as $dish): ?>
                    <div class="menu-card hover:scale-[1.02] transition-transform duration-200 col-span-1" 
                        data-ingredients="<?php echo htmlspecialchars($dish['ingredients'] ?? 'No ingredients listed'); ?>" 
                        data-description="<?php echo htmlspecialchars($dish['dish_description']); ?>">
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100 h-full flex flex-col">
                            <img src="<?php echo htmlspecialchars($dish['dish_pic_url']); ?>" alt="<?php echo htmlspecialchars($dish['dish_name']); ?>" class="w-full h-48 object-cover">
                            <div class="p-4 flex flex-col">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($dish['dish_name']); ?></h3>
                                    <span class="font-medium text-amber-600">₱<?php echo number_format($dish['price'], 2); ?></span>
                                </div>
                                <button class="view-ingredients mt-3 self-start text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors underline underline-offset-4">
                                    View details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="menu-modal" class="modal">
        <div class="modal-content font-baskerville">
            <span class="modal-close">×</span>
            <h3 id="modal-title" class="font-playfair text-xl sm:text-2xl font-bold text-gray-900 mb-4"></h3>
            <h4 class="text-lg font-semibold text-amber-600 mb-2">Description</h4>
            <p id="modal-description" class="text-sm text-gray-600 leading-relaxed mb-4"></p>
            <h4 class="text-lg font-semibold text-amber-600 mb-2">Ingredients</h4>
            <p id="modal-ingredients" class="text-sm text-gray-600 leading-relaxed"></p>
        </div>
    </div>

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

        window.addEventListener('scroll', () => {
            if (window.scrollY > 0) {
                navbar.classList.add('backdrop-blur-md', 'bg-[#FFF8E7]/90', 'shadow-lg');
            } else {
                navbar.classList.remove('backdrop-blur-md', 'bg-[#FFF8E7]/90', 'shadow-lg');
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

        // Modal functionality
        const modal = document.getElementById('menu-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalIngredients = document.getElementById('modal-ingredients');
        const modalDescription = document.getElementById('modal-description');
        const closeModal = document.querySelector('.modal-close');

        document.querySelectorAll('.view-ingredients').forEach(button => {
            button.addEventListener('click', () => {
                const card = button.closest('.menu-card');
                const dishName = card.querySelector('h3').textContent;
                const ingredients = card.getAttribute('data-ingredients');
                const description = card.getAttribute('data-description');
                modalTitle.textContent = dishName;
                modalIngredients.textContent = ingredients;
                modalDescription.textContent = description;
                modal.classList.add('active');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.classList.remove('active');
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                modal.classList.remove('active');
            }
        });

        // Adjust card widths
        function adjustCardWidths() {
            const grid = document.getElementById('menu-grid');
            const cards = Array.from(grid.querySelectorAll('.menu-card'));
            const breakpoints = {
                sm: { cols: 2, minWidth: 640 },
                lg: { cols: 4, minWidth: 1024 }
            };

            const windowWidth = window.innerWidth;
            let colsPerRow = windowWidth < 640 ? 2 : 1;
            if (windowWidth >= breakpoints.lg.minWidth) {
                colsPerRow = breakpoints.lg.cols;
            } else if (windowWidth >= breakpoints.sm.minWidth) {
                colsPerRow = breakpoints.sm.cols;
            }

            cards.forEach(card => {
                card.classList.remove('col-span-1', 'col-span-2', 'sm:col-span-1', 'sm:col-span-2', 'lg:col-span-1', 'lg:col-span-2');
                card.classList.add('col-span-1');
            });

            for (let i = 0; i < cards.length; i += colsPerRow) {
                const rowCards = cards.slice(i, i + colsPerRow);
                if (rowCards.length < colsPerRow && rowCards.length > 0) {
                    if (colsPerRow === breakpoints.lg.cols && rowCards.length === 3) {
                        rowCards.forEach((card, index) => {
                            if (index < 2) {
                                card.classList.add('lg:col-span-1');
                            } else {
                                card.classList.add('lg:col-span-2');
                            }
                        });
                    } else if (colsPerRow === breakpoints.lg.cols && rowCards.length === 2) {
                        rowCards.forEach(card => card.classList.add('lg:col-span-2'));
                    } else if (colsPerRow === breakpoints.lg.cols && rowCards.length === 1) {
                        rowCards[0].classList.add('lg:col-span-2');
                    } else if (colsPerRow === breakpoints.sm.cols && rowCards.length === 1) {
                        rowCards[0].classList.add('sm:col-span-2');
                    } else if (colsPerRow === 2 && rowCards.length === 1 && windowWidth < 640) {
                        rowCards[0].classList.add('col-span-2');
                    } else {
                        rowCards.forEach(card => card.classList.add(colsPerRow === breakpoints.lg.cols ? 'lg:col-span-1' : 'col-span-1'));
                    }
                } else {
                    rowCards.forEach(card => card.classList.add(colsPerRow === breakpoints.lg.cols ? 'lg:col-span-1' : 'col-span-1'));
                }
            }
        }

        window.addEventListener('load', adjustCardWidths);
        window.addEventListener('resize', adjustCardWidths);
    </script>
</body>
</html>