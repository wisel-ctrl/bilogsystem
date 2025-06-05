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
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="italian-dish">Italian Dishes</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="spanish-dish">Spanish Dishes</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="salad">House Salads</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="pizza">Pizza</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="burgers">Burgers</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="pasta">Pasta</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="pasta_caza">Pasta e Caza</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="dessert">Desserts</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="main-course">Main Courses</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="drinks">Drinks</button>
                    </li>
                    <li>
                        <button class="category-btn w-full text-left px-3 py-2 bg-accent-brown text-warm-cream rounded hover:bg-deep-brown transition" data-category="coffee">Coffee</button>
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
                        <span id="subtotal">₱0.00</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-rich-brown">
                        <span class="font-bold">Tax (10%):</span>
                        <span id="tax">₱0.00</span>
                    </div>
                    <div class="flex justify-between py-2 font-bold text-lg">
                        <span>Total:</span>
                        <span id="total">₱0.00</span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button id="clear-cart" class="flex-1 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">Clear</button>
                    <button id="checkout" class="flex-1 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Checkout</button>
                </div>
            </div>
        </div>

        <!-- Discount Modal -->
        <div id="discount-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg w-96">
                <h3 class="text-xl font-bold text-rich-brown mb-4">Payment Details</h3>
                
                <!-- Discount Options -->
                <div class="space-y-3 mb-4">
                    <h4 class="font-semibold">Discount Type:</h4>
                    <div class="flex items-center">
                        <input type="radio" id="none" name="discount" value="none" checked class="mr-2">
                        <label for="none">None</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="senior" name="discount" value="senior" class="mr-2">
                        <label for="senior">Senior Citizen (20%)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="pwd" name="discount" value="PWD" class="mr-2">
                        <label for="pwd">PWD (20%)</label>
                    </div>
                </div>
                
                <!-- Payment Amount Input -->
                <div class="mb-4">
                    <label for="payment-amount" class="block font-semibold mb-1">Amount Paid:</label>
                    <input type="number" id="payment-amount" class="w-full p-2 border rounded" min="0" step="0.01">
                    <p id="payment-error" class="text-red-500 text-sm mt-1 hidden">Payment amount must be positive and cover the total amount</p>
                </div>
                
                <!-- Summary Display -->
                <div class="border-t pt-3 mb-4">
                    <h4 class="font-semibold mb-2">Payment Summary:</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal" class="text-right">₱0.00</span>
                        
                        <span>Tax (10%):</span>
                        <span id="summary-tax" class="text-right">₱0.00</span>
                        
                        <span>Discount:</span>
                        <span id="summary-discount" class="text-right">₱0.00</span>
                        
                        <span class="font-semibold">Total:</span>
                        <span id="summary-total" class="text-right font-semibold">₱0.00</span>
                        
                        <span>Amount Paid:</span>
                        <span id="summary-paid" class="text-right">₱0.00</span>
                        
                        <span class="font-semibold">Change:</span>
                        <span id="summary-change" class="text-right font-semibold">₱0.00</span>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button id="cancel-discount" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button id="apply-discount" class="px-4 py-2 bg-rich-brown text-white rounded hover:bg-deep-brown">Complete Payment</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Sample menu data
        let menuItems = [];

        async function fetchMenuItems() {
            try {
                const response = await fetch('posFunctions/get_menu.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                menuItems = await response.json();
                renderMenuItems('all'); // Render all items by default
            } catch (error) {
                console.error('Error fetching menu items:', error);
                // You might want to show an error message to the user here
            }
        }

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
            fetchMenuItems(); // This will now fetch from the server
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
                            <span class="text-lg font-bold text-accent-brown">₱${item.price.toFixed(2)}</span>
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
                        <div class="font-bold">₱${(item.price * item.quantity).toFixed(2)}</div>
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
            
            subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
            taxElement.textContent = `₱${tax.toFixed(2)}`;
            totalElement.textContent = `₱${total.toFixed(2)}`;
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
        async function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            // Calculate initial totals
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.10;
            const totalBeforeDiscount = subtotal + tax;
            
            // Show discount modal
            const modal = document.getElementById('discount-modal');
            modal.classList.remove('hidden');
            document.getElementById('none').checked = true;

            // Update summary display initially
            document.getElementById('summary-subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('summary-tax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('summary-total').textContent = `₱${totalBeforeDiscount.toFixed(2)}`;

            // Reset payment amount and error
            document.getElementById('payment-amount').value = '';
            document.getElementById('payment-error').classList.add('hidden');
            
            // Function to update payment summary
            function updateSummary() {
                const selectedDiscount = document.querySelector('input[name="discount"]:checked').value;
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const paymentError = document.getElementById('payment-error');
                
                // Calculate discount
                let discountPrice = 0;
                if (selectedDiscount === 'senior' || selectedDiscount === 'PWD') {
                    discountPrice = totalBeforeDiscount * 0.20;
                }
                const finalTotal = totalBeforeDiscount - discountPrice;
                
                // Calculate change
                const change = paymentAmount - finalTotal;

                // Validate payment amount
                if (paymentAmount < 0) {
                    paymentError.textContent = "Payment amount cannot be negative";
                    paymentError.classList.remove('hidden');
                    document.getElementById('apply-discount').disabled = true;
                } else if (paymentAmount > 0 && paymentAmount < finalTotal) {
                    paymentError.textContent = "Payment amount must cover the total amount";
                    paymentError.classList.remove('hidden');
                    document.getElementById('apply-discount').disabled = true;
                } else {
                    paymentError.classList.add('hidden');
                    document.getElementById('apply-discount').disabled = change < 0;
                }
                
                // Update display
                document.getElementById('summary-discount').textContent = `₱${discountPrice.toFixed(2)}`;
                document.getElementById('summary-total').textContent = `₱${finalTotal.toFixed(2)}`;
                document.getElementById('summary-paid').textContent = `₱${paymentAmount.toFixed(2)}`;
                document.getElementById('summary-change').textContent = `₱${change.toFixed(2)}`;
                
                
            }
            
            // Add event listeners for real-time updates
            document.querySelectorAll('input[name="discount"]').forEach(radio => {
                radio.addEventListener('change', updateSummary);
            });
            document.getElementById('payment-amount').addEventListener('input', updateSummary);
            
            // Wait for user to complete payment
            const paymentData = await new Promise((resolve) => {
                document.getElementById('apply-discount').addEventListener('click', () => {
                    const selectedDiscount = document.querySelector('input[name="discount"]:checked').value;
                    const paymentAmount = parseFloat(document.getElementById('payment-amount').value);

                    // Validate payment amount again before proceeding
                    if (isNaN(paymentAmount)) {
                        alert('Please enter a valid payment amount');
                        return;
                    }
                    
                    if (paymentAmount < 0) {
                        alert('Payment amount cannot be negative');
                        return;
                    }
                    
                    // Calculate final totals
                    let discountPrice = 0;
                    if (selectedDiscount === 'senior' || selectedDiscount === 'PWD') {
                        discountPrice = totalBeforeDiscount * 0.20;
                    }
                    const finalTotal = totalBeforeDiscount - discountPrice;
                    const change = paymentAmount - finalTotal;
                    
                    modal.classList.add('hidden');
                    resolve({
                        discountType: selectedDiscount,
                        discountPrice,
                        paymentAmount,
                        change,
                        finalTotal
                    });
                });
                
                document.getElementById('cancel-discount').addEventListener('click', () => {
                    modal.classList.add('hidden');
                    resolve(null); // Indicates cancellation
                });
            });
            
            if (!paymentData) return; // User cancelled
            
            // Prepare data for backend
            const orderData = {
                total_price: paymentData.finalTotal,
                discount_type: paymentData.discountType,
                discount_price: paymentData.discountPrice,
                amount_paid: paymentData.paymentAmount,
                amount_change: paymentData.change,
                items: cart.map(item => ({
                    dish_id: item.id,
                    price: item.price,
                    quantity: item.quantity
                }))
            };
            
            // Send to backend
            try {
                const response = await fetch('posFunctions/process_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });
                
                if (!response.ok) {
                    throw new Error('Failed to process order');
                }
                
                const result = await response.json();
                if (result.success) {
                    alert(`Order #${result.sales_id} placed successfully!\nTotal: ₱${paymentData.finalTotal.toFixed(2)}\nChange: ₱${paymentData.change.toFixed(2)}`);
                    clearCart();
                    document.getElementById('none').checked = true;
                } else {
                    alert('Error processing order: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Checkout error:', error);
                alert('Error processing order. Please try again.');
            }
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
