<?php 
require_once 'cashier_auth.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        'serif': ['Georgia', 'serif'],
                        'script': ['Brush Script MT', 'cursive']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-warm-cream font-serif min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-rich-brown">Restaurant POS System</h1>
            <a href="../logout.php" class="bg-rich-brown hover:bg-deep-brown text-warm-cream px-4 py-2 rounded-lg transition-colors duration-300 font-baskerville">
                Sign Out
            </a>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Section 1: Sidebar with category filters -->
            <div class="w-full lg:w-1/6 bg-rich-brown p-4 rounded-lg shadow-lg">
                <h2 class="text-2xl text-warm-cream mb-4 font-bold">Categories</h2>
                <ul class="space-y-2">
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="all">All Items</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="main">Main Courses</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="dessert">Desserts</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="drink">Drinks</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="appetizer">Appetizers</button>
                    </li>
                </ul>
            </div>
            
            <!-- Section 2: Main dishes display -->
            <div class="w-full lg:w-3/6 p-4">
                <h2 class="text-2xl text-rich-brown mb-4 font-bold">Menu Items</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="menu-items">
                    <!-- Menu items will be dynamically inserted here -->
                </div>
            </div>
            
            <!-- Section 3: Cart section -->
            <div class="w-full lg:w-2/6 bg-rich-brown p-4 rounded-lg shadow-lg">
                <h2 class="text-2xl text-warm-cream mb-4 font-bold">Order Summary</h2>
                <div class="bg-warm-cream p-3 rounded mb-4">
                    <div id="cart-items" class="space-y-2 max-h-96 overflow-y-auto">
                        <!-- Cart items will be dynamically inserted here -->
                        <p class="text-center text-gray-500 py-4">Your cart is empty</p>
                    </div>
                </div>
                
                <div class="bg-warm-cream p-3 rounded mb-4">
                    <div class="flex justify-between py-2 border-b border-rich-brown">
                        <span class="font-bold">Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-rich-brown">
                        <span class="font-bold">Tax (10%):</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="flex justify-between py-2 font-bold text-lg">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button id="clear-cart" class="flex-1 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">Clear</button>
                    <button id="checkout" class="flex-1 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample menu data
        const menuItems = [
            {
                id: 1,
                name: "Grilled Salmon",
                description: "Fresh salmon with lemon butter sauce",
                price: 18.99,
                category: "main",
                image: "https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 2,
                name: "Beef Steak",
                description: "Juicy ribeye steak with mashed potatoes",
                price: 24.99,
                category: "main",
                image: "https://images.unsplash.com/photo-1603360946369-dc9bb6258143?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 3,
                name: "Chocolate Cake",
                description: "Rich chocolate cake with raspberry sauce",
                price: 8.99,
                category: "dessert",
                image: "https://images.unsplash.com/photo-1571115177098-24ec42ed204d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 4,
                name: "Tiramisu",
                description: "Classic Italian dessert with coffee flavor",
                price: 7.99,
                category: "dessert",
                image: "https://images.unsplash.com/photo-1535920527002-b35e96722eb9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 5,
                name: "Red Wine",
                description: "Glass of premium house red wine",
                price: 9.99,
                category: "drink",
                image: "https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 6,
                name: "Iced Tea",
                description: "Fresh brewed iced tea with lemon",
                price: 3.99,
                category: "drink",
                image: "https://images.unsplash.com/photo-1568430462989-44163eb1752f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 7,
                name: "Bruschetta",
                description: "Toasted bread with tomatoes and basil",
                price: 6.99,
                category: "appetizer",
                image: "https://images.unsplash.com/photo-1529563021893-cc83c992d00d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            },
            {
                id: 8,
                name: "Caesar Salad",
                description: "Classic Caesar with homemade dressing",
                price: 9.99,
                category: "appetizer",
                image: "https://images.unsplash.com/photo-1546793665-c74683f339c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            }
        ];

        // Cart state
        let cart = [];

        // DOM elements
        const menuItemsContainer = document.getElementById('menu-items');
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalElement = document.getElementById('subtotal');
        const taxElement = document.getElementById('tax');
        const totalElement = document.getElementById('total');
        const clearCartBtn = document.getElementById('clear-cart');
        const checkoutBtn = document.getElementById('checkout');
        const categoryBtns = document.querySelectorAll('.category-btn');

        // Initialize the page
        function init() {
            renderMenuItems('all');
            setupEventListeners();
        }

        // Render menu items based on category
        function renderMenuItems(category) {
            menuItemsContainer.innerHTML = '';
            
            const filteredItems = category === 'all' 
                ? menuItems 
                : menuItems.filter(item => item.category === category);
            
            if (filteredItems.length === 0) {
                menuItemsContainer.innerHTML = '<p class="col-span-2 text-center text-gray-500 py-4">No items in this category</p>';
                return;
            }
            
            filteredItems.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition cursor-pointer';
                itemElement.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-rich-brown">${item.name}</h3>
                        <p class="text-gray-600 mb-2">${item.description}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-accent-brown">$${item.price.toFixed(2)}</span>
                            <button class="add-to-cart bg-rich-brown text-warm-cream px-3 py-1 rounded hover:bg-deep-brown transition" data-id="${item.id}">Add</button>
                        </div>
                    </div>
                `;
                menuItemsContainer.appendChild(itemElement);
            });
        }

        // Render cart items
        function renderCart() {
            cartItemsContainer.innerHTML = '';
            
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Your cart is empty</p>';
                updateTotals();
                return;
            }
            
            cart.forEach(item => {
                const cartItemElement = document.createElement('div');
                cartItemElement.className = 'flex justify-between items-center border-b border-rich-brown pb-2';
                cartItemElement.innerHTML = `
                    <div class="flex-1">
                        <h4 class="font-bold">${item.name}</h4>
                        <div class="flex items-center mt-1">
                            <button class="quantity-btn decrease px-2 bg-gray-200 rounded" data-id="${item.id}">-</button>
                            <span class="mx-2">${item.quantity}</span>
                            <button class="quantity-btn increase px-2 bg-gray-200 rounded" data-id="${item.id}">+</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">$${(item.price * item.quantity).toFixed(2)}</div>
                        <button class="remove-item text-xs text-red-500 hover:text-red-700" data-id="${item.id}">Remove</button>
                    </div>
                `;
                cartItemsContainer.appendChild(cartItemElement);
            });
            
            updateTotals();
        }

        // Update totals in cart
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.10; // 10% tax
            const total = subtotal + tax;
            
            subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
            taxElement.textContent = `$${tax.toFixed(2)}`;
            totalElement.textContent = `$${total.toFixed(2)}`;
        }

        // Add item to cart
        function addToCart(itemId) {
            const menuItem = menuItems.find(item => item.id === itemId);
            
            if (!menuItem) return;
            
            const existingItem = cart.find(item => item.id === itemId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    ...menuItem,
                    quantity: 1
                });
            }
            
            renderCart();
        }

        // Remove item from cart
        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            renderCart();
        }

        // Update item quantity in cart
        function updateQuantity(itemId, change) {
            const cartItem = cart.find(item => item.id === itemId);
            
            if (!cartItem) return;
            
            cartItem.quantity += change;
            
            if (cartItem.quantity <= 0) {
                removeFromCart(itemId);
            } else {
                renderCart();
            }
        }

        // Clear cart
        function clearCart() {
            cart = [];
            renderCart();
        }

        // Checkout
        function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            alert(`Order placed! Total: $${totalElement.textContent.substring(1)}`);
            clearCart();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Category filter buttons
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.dataset.category;
                    renderMenuItems(category);
                    
                    // Update active button style
                    categoryBtns.forEach(b => b.classList.remove('bg-deep-brown'));
                    btn.classList.add('bg-deep-brown');
                });
            });
            
            // Add to cart buttons (delegated)
            menuItemsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('add-to-cart')) {
                    const itemId = parseInt(e.target.dataset.id);
                    addToCart(itemId);
                }
            });
            
            // Cart quantity buttons (delegated)
            cartItemsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('quantity-btn')) {
                    const itemId = parseInt(e.target.dataset.id);
                    const isIncrease = e.target.classList.contains('increase');
                    updateQuantity(itemId, isIncrease ? 1 : -1);
                }
                
                if (e.target.classList.contains('remove-item')) {
                    const itemId = parseInt(e.target.dataset.id);
                    removeFromCart(itemId);
                }
            });
            
            // Clear cart button
            clearCartBtn.addEventListener('click', clearCart);
            
            // Checkout button
            checkoutBtn.addEventListener('click', checkout);
        }

        // Initialize the application
        init();
    </script>
</body>
</html>