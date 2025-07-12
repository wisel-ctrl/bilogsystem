<?php
require_once 'customer_auth.php';
// Set page title
$page_title = "Menu - Caffè Lilio";

// Capture content
ob_start();
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
    
    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-baskerville { font-family: 'Libre Baskerville', serif; }
    
    /* Enhanced hover effect */
    .hover-lift {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .hover-lift:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
    }

    /* Improved background gradients */
    .bg-warm-gradient {
        background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
    }

    .bg-card {
        background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
        backdrop-filter: blur(8px);
    }

    .bg-nav {
        background: linear-gradient(to bottom, #5D2F0F, #4a260d);
    }

    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    /* Loading skeleton animation */
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    .skeleton {
        background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }

    /* Accessibility improvements */
    :focus {
        outline: 2px solid #8B4513;
        outline-offset: 2px;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #E8E0D5;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #8B4513;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #5D2F0F;
    }

    /* Toast notification styles */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 1rem;
        border-radius: 8px;
        background: #E8E0D5;
        box-shadow: 0 4px 12px rgba(93, 47, 15, 0.15);
        transform: translateY(100%);
        opacity: 0;
        transition: all 0.3s ease-in-out;
        z-index: 1000;
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    /* Button animations */
    .btn-primary {
        position: relative;
        overflow: hidden;
        background: #8B4513;
        color: #E8E0D5;
    }

    .btn-primary::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(232, 224, 213, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-primary:active::after {
        width: 200%;
        height: 200%;
    }

    /* Skeleton loading placeholder */
    .skeleton-text {
        height: 1em;
        background: #e0e0e0;
        margin: 0.5em 0;
        border-radius: 4px;
    }

    /* Improved form inputs */
    input, select, textarea {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #8B4513;
        box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
    }

    /* Responsive grid improvements */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .menu-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 640px) {
        .menu-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    }

    /* Loading states */
    .loading-card {
        aspect-ratio: 3/4;
        background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
        border-radius: 0.75rem;
        padding: 1.5rem;
    }

    .loading-header {
        height: 1.5rem;
        background: #d4c8b9;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }

    .loading-text {
        height: 1rem;
        background: #d4c8b9;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }
</style>

<!-- Main Content -->
<section id="menu" class="py-12 sm:py-20 bg-gradient-to-b from-amber-50 to-amber-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-16 animate-fade-in">
            <h2 class="font-playfair text-3xl sm:text-4xl md:text-5xl font-bold text-brown-900 mb-6">Our Menu</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-800 to-amber-600 mx-auto mb-8"></div>
            <p class="font-baskerville text-base sm:text-lg text-brown-800 max-w-4xl mx-auto leading-relaxed">
                Discover our carefully curated menu featuring the finest Italian and Spanish dishes. From classic favorites to unique specialties.
            </p>
        </div>

        <!-- Loading State -->
        <div id="menu-loading" class="menu-grid">
            <div class="loading-card skeleton"></div>
            <div class="loading-card skeleton"></div>
            <div class="loading-card skeleton"></div>
            <div class="loading-card skeleton"></div>
        </div>

        <!-- Menu Container -->
        <div id="menu-container" class="hidden menu-grid"></div>

        <!-- Empty State -->
        <div id="menu-empty" class="hidden text-center py-12">
            <i class="fas fa-utensils text-6xl text-brown-900/30 mb-4"></i>
            <h3 class="font-playfair text-2xl font-bold text-brown-900 mb-2">No Menu Categories Available</h3>
            <p class="font-baskerville text-brown-900/70 mb-4">Check back later for our delicious offerings.</p>
            <button class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300"
                    onclick="loadMenu()">
                Try Again
            </button>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    tippy('[data-tippy-content]', {
        theme: 'custom',
        animation: 'scale',
        duration: [200, 150],
        placement: 'bottom'
    });

    // Initialize loading bar
    NProgress.configure({ 
        showSpinner: false,
        minimum: 0.3,
        easing: 'ease',
        speed: 500
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                <span class="font-baskerville">${message}</span>
            </div>
        `;
        document.getElementById('toast-container').appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove toast
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Global function to show toast
    window.showToast = showToast;

    // Menu categories configuration
    const menuCategories = [
        {
            name: 'Best-Sellers',
            href: 'index_menu/best_sellers.php',
            image: 'images/Calamari.jpg',
            alt: 'Best Sellers'
        },
        {
            name: 'Main Course',
            href: 'index_menu/main_course.php',
            image: 'images/maincourse.jpg',
            alt: 'Main Course'
        },
        {
            name: 'House Salad',
            href: 'index_menu/salad.php',
            image: 'images/Salad.jpg',
            alt: 'Salad'
        },
        {
            name: 'Italian Dish',
            href: 'index_menu/italian_dish.php',
            image: 'images/italian_dish.jpg',
            alt: 'Italian Dish'
        },
        {
            name: 'Spanish Dish',
            href: 'index_menu/spanish_dish.php',
            image: 'images/spanish_dish.jpg',
            alt: 'Spanish Dish'
        },
        {
            name: 'Coffee',
            href: 'index_menu/coffee.php',
            image: 'images/coffee.png',
            alt: 'Coffee'
        },
        {
            name: 'Drinks',
            href: 'index_menu/drinks.php',
            image: 'images/drinks.png',
            alt: 'Drinks'
        },
        {
            name: 'Burgers & Pizza',
            href: 'index_menu/burger_pizza.php',
            image: 'images/burgerpizza.png',
            alt: 'Burgers & Pizza'
        },
        {
            name: 'Pasta',
            href: 'index_menu/pasta.php',
            image: 'images/Carbonara.jpg',
            alt: 'Pasta'
        },
        {
            name: 'Pasta e Caza',
            href: 'index_menu/pasta_ecaza.php',
            image: 'images/pastaEcaza.png',
            alt: 'Pasta e Caza'
        },
        {
            name: 'Desserts',
            href: 'index_menu/desserts.php',
            image: 'images/desserts.jpg',
            alt: 'Desserts'
        }
    ];

    // Fetch and display menu categories
    function loadMenu() {
        const menuContainer = document.getElementById('menu-container');
        const loadingContainer = document.getElementById('menu-loading');
        const emptyContainer = document.getElementById('menu-empty');

        // Show loading state
        loadingContainer.classList.remove('hidden');
        menuContainer.classList.add('hidden');
        emptyContainer.classList.add('hidden');

        // Simulate fetch for categories (using static configuration)
        setTimeout(() => {
            if (menuCategories.length > 0) {
                loadingContainer.classList.add('hidden');
                menuContainer.classList.remove('hidden');
                menuContainer.innerHTML = '';
                
                menuCategories.forEach(category => {
                    const menuItem = document.createElement('a');
                    menuItem.href = category.href;
                    menuItem.className = 'relative rounded-xl overflow-hidden shadow-lg group cursor-pointer hover-lift';
                    menuItem.innerHTML = `
                        <div class="aspect-[3/4] relative">
                            <img src="${category.image}" alt="${category.alt}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                                <h3 class="font-playfair text-lg sm:text-xl font-bold text-amber-50 text-center px-4">${category.name}</h3>
                            </div>
                        </div>
                    `;
                    menuContainer.appendChild(menuItem);
                });
            } else {
                loadingContainer.classList.add('hidden');
                emptyContainer.classList.remove('hidden');
            }
            NProgress.done();
        }, 500); // Simulate network delay
    }

    // Initialize menu loading
    NProgress.start();
    loadMenu();

    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe menu cards for animation
    function observeMenuCards() {
        document.querySelectorAll('.menu-grid > a').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    }

    // Update loadMenu to include animations
    const originalLoadMenu = loadMenu;
    window.loadMenu = function() {
        setLoading(true);
        originalLoadMenu();
        
        setTimeout(() => {
            observeMenuCards();
            setLoading(false);
        }, 100);
    };

    // Add loading state management
    let isLoading = false;

    function setLoading(loading) {
        isLoading = loading;
        const refreshBtn = document.getElementById('refresh-btn');
        if (refreshBtn) {
            const refreshIcon = refreshBtn.querySelector('i');
            if (loading) {
                refreshBtn.disabled = true;
                refreshIcon.classList.add('fa-spin');
                refreshBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                refreshBtn.disabled = false;
                refreshIcon.classList.remove('fa-spin');
                refreshBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    // Enhanced error handling
    function handleError(error, userMessage) {
        console.error('Error:', error);
        showToast(userMessage || 'Something went wrong. Please try again.', 'error');
        setLoading(false);
    }

    // Add accessibility improvements
    function enhanceAccessibility() {
        const main = document.querySelector('main');
        if (main) {
            main.id = 'main-content';
            main.setAttribute('role', 'main');
        }

        document.querySelectorAll('a').forEach(link => {
            if (!link.getAttribute('aria-label') && link.textContent.trim()) {
                link.setAttribute('aria-label', `Navigate to ${link.textContent.trim()}`);
            }
        });
    }

    enhanceAccessibility();

    // Add print styles support
    function addPrintSupport() {
        const printStyles = document.createElement('style');
        printStyles.textContent = `
            @media print {
                .bg-warm-gradient, .bg-card, nav, #toast-container, #nprogress-container {
                    display: none !important;
                }
                
                .menu-grid > a {
                    break-inside: avoid;
                    margin-bottom: 1rem;
                }
                
                .hover-lift:hover {
                    transform: none;
                    box-shadow: none;
                }
                
                body {
                    background: white !important;
                    color: black !important;
                }
                
                img {
                    display: none;
                }
                
                .menu-grid > a > div > div {
                    background: none !important;
                    color: black !important;
                }
            }
        `;
        document.head.appendChild(printStyles);
    }

    addPrintSupport();
});
</script>

<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>