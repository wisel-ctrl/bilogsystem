<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Animation classes */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Delay classes for staggered animations */
        .delay-100 {
            transition-delay: 100ms;
        }
        .delay-200 {
            transition-delay: 200ms;
        }
        .delay-300 {
            transition-delay: 300ms;
        }
        .delay-400 {
            transition-delay: 400ms;
        }
        
        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.75rem;
            border: 1px solid #d1d5db;
            margin-left: 0.25rem;
            border-radius: 0.375rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #A0522D;
            color: white !important;
            border-color: #A0522D;
        }
    </style>
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
<body class="bg-warm-cream font-serif">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown to-rich-brown text-white transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-6 border-b border-accent-brown">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-coffee text-2xl text-warm-cream"></i>
                    <h1 id="cafe-title" class="text-xl font-bold text-warm-cream font-script">Cafe Lilio</h1>
                </div>
            </div>
            
            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream transition-colors duration-200">
                            <i class="fas fa-chart-pie w-5"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookings.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span class="sidebar-text">Booking Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_menu.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text">Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-boxes w-5"></i>
                            <span class="sidebar-text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_expenses.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="sidebar-text">Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_employee_creation.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200">
                            <i class="fas fa-user-plus w-5"></i>
                            <span class="sidebar-text">Employee Creation</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-deep-brown font-script">Inventory Management</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-rich-brown">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span id="current-date"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-8 h-8 rounded-full border-2 border-accent-brown">
                            <span class="text-sm font-medium text-deep-brown">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <div class="bg-white rounded-lg shadow-md p-6 animate-on-scroll">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-deep-brown">Inventory Items</h3>
                        <button id="add-ingredient-btn" class="bg-accent-brown hover:bg-deep-brown text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Ingredient
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="ingredients-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-warm-cream">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-deep-brown uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-deep-brown uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-deep-brown uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-deep-brown uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-deep-brown uppercase tracking-wider">Total Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-deep-brown uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Ingredient Modal -->
    <div id="add-ingredient-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-deep-brown">Add New Ingredient</h3>
                    <button id="close-add-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="add-ingredient-form">
                    <div class="mb-4">
                        <label for="ingredient-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="ingredient-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    
                    <div class="mb-4">
                        <label for="ingredient-category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="ingredient-category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                            <option value="">Select a category</option>
                            <option value="Coffee">Coffee</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Flavoring">Flavoring</option>
                            <option value="Bakery">Bakery</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="ingredient-quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="text" id="ingredient-quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    
                    <div class="mb-4">
                        <label for="ingredient-price" class="block text-sm font-medium text-gray-700 mb-1">Price (per unit)</label>
                        <input type="text" id="ingredient-price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="cancel-add-ingredient" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-accent-brown text-white rounded-md hover:bg-deep-brown transition-colors duration-200">
                            Add Ingredient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Ingredient Modal -->
    <div id="edit-ingredient-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-deep-brown">Edit Ingredient</h3>
                    <button id="close-edit-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="edit-ingredient-form">
                    <input type="hidden" id="edit-ingredient-id">
                    
                    <div class="mb-4">
                        <label for="edit-ingredient-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="edit-ingredient-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-ingredient-category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="edit-ingredient-category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                            <option value="">Select a category</option>
                            <option value="Coffee">Coffee</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Flavoring">Flavoring</option>
                            <option value="Bakery">Bakery</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-ingredient-quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="text" id="edit-ingredient-quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-ingredient-price" class="block text-sm font-medium text-gray-700 mb-1">Price (per unit)</label>
                        <input type="text" id="edit-ingredient-price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="cancel-edit-ingredient" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-accent-brown text-white rounded-md hover:bg-deep-brown transition-colors duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-deep-brown">Confirm Deletion</h3>
                    <button id="close-delete-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-700">Are you sure you want to delete this ingredient? This action cannot be undone.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-delete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="button" id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </div>
                <input type="hidden" id="ingredient-to-delete">
            </div>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const cafeTitle = document.getElementById('cafe-title');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');
            
            if (sidebar.classList.contains('w-16')) {
                cafeTitle.style.display = 'none';
                sidebarTexts.forEach(text => text.style.display = 'none');
            } else {
                cafeTitle.style.display = 'block';
                sidebarTexts.forEach(text => text.style.display = 'block');
            }
        });

        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Scroll animation observer
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, {
            threshold: 0.1
        });

        animateElements.forEach(element => {
            observer.observe(element);
        });

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#ingredients-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "fetch_ingredient.php",
                    "type": "POST"
                },
                "columns": [
                    { "data": "ingredient_name" },
                    { "data": "category" },
                    { "data": "quantity" },
                    { 
                        "data": "price",
                        "render": function(data, type, row) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        "data": "total_price",
                        "render": function(data, type, row) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        "data": "ingredient_id",
                        "render": function(data, type, row) {
                            return `
                                <button onclick="editIngredient(${data})" class="text-accent-brown hover:text-deep-brown mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete(${data})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            `;
                        },
                        "orderable": false
                    }
                ],
                "order": [[0, 'asc']],
                "responsive": true,
                "dom": '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search ingredients...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });

            // Refresh table after adding/editing
            window.refreshTable = function() {
                table.ajax.reload(null, false);
            };
        });

        // Modal handling
        const addIngredientBtn = document.getElementById('add-ingredient-btn');
        const addIngredientModal = document.getElementById('add-ingredient-modal');
        const addIngredientForm = document.getElementById('add-ingredient-form');
        const closeAddModal = document.getElementById('close-add-modal');
        const cancelAddIngredient = document.getElementById('cancel-add-ingredient');
        
        const editIngredientModal = document.getElementById('edit-ingredient-modal');
        const closeEditModal = document.getElementById('close-edit-modal');
        const cancelEditIngredient = document.getElementById('cancel-edit-ingredient');
        
        const deleteConfirmModal = document.getElementById('delete-confirm-modal');
        const closeDeleteModal = document.getElementById('close-delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDeleteBtn = document.getElementById('confirm-delete');

        // Show add ingredient modal
        addIngredientBtn.addEventListener('click', () => {
            addIngredientModal.classList.remove('hidden');
        });

        // Close add ingredient modal
        closeAddModal.addEventListener('click', () => {
            addIngredientModal.classList.add('hidden');
        });

        cancelAddIngredient.addEventListener('click', () => {
            addIngredientModal.classList.add('hidden');
        });

        // Close edit ingredient modal
        closeEditModal.addEventListener('click', () => {
            editIngredientModal.classList.add('hidden');
        });

        cancelEditIngredient.addEventListener('click', () => {
            editIngredientModal.classList.add('hidden');
        });

        // Close delete confirmation modal
        closeDeleteModal.addEventListener('click', () => {
            deleteConfirmModal.classList.add('hidden');
        });

        cancelDelete.addEventListener('click', () => {
            deleteConfirmModal.classList.add('hidden');
        });

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === addIngredientModal) {
                addIngredientModal.classList.add('hidden');
            }
            if (event.target === editIngredientModal) {
                editIngredientModal.classList.add('hidden');
            }
            if (event.target === deleteConfirmModal) {
                deleteConfirmModal.classList.add('hidden');
            }
        });

        // Form submissions
        document.getElementById('add-ingredient-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('ingredient-name').value.trim();
            const category = document.getElementById('ingredient-category').value;
            const quantity = parseFloat(document.getElementById('ingredient-quantity').value);
            const price = parseFloat(document.getElementById('ingredient-price').value);
            
            // Simple validation
            if (!name || !category || isNaN(quantity) || isNaN(price)) {
                alert('Please fill all fields with valid values');
                return;
            }
            
            try {
                // Send data to server
                const response = await fetch('inventory_handlers/add_ingredient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ingredient_name: name,
                        category: category,
                        quantity: quantity,
                        price: price
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to add ingredient');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and reset form
                    addIngredientModal.classList.add('hidden');
                    addIngredientForm.reset();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Ingredient added successfully!');
                } else {
                    throw new Error(result.message || 'Failed to add ingredient');
                }
                
            } catch (error) {
                console.error('Error adding ingredient:', error);
                alert('Error adding ingredient: ' + error.message);
            }
        });

        // Function to open the edit modal with ingredient data
        window.editIngredient = async function(ingredientId) {
            try {
                // Fetch ingredient data
                const response = await fetch(`inventory_handlers/get_ingredient.php?id=${ingredientId}`);
                
                if (!response.ok) {
                    throw new Error('Failed to fetch ingredient data');
                }
                
                const ingredient = await response.json();
                
                if (!ingredient) {
                    throw new Error('Ingredient not found');
                }
                
                // Populate the form
                document.getElementById('edit-ingredient-id').value = ingredient.ingredient_id;
                document.getElementById('edit-ingredient-name').value = ingredient.ingredient_name;
                document.getElementById('edit-ingredient-category').value = ingredient.category;
                document.getElementById('edit-ingredient-quantity').value = ingredient.quantity;
                document.getElementById('edit-ingredient-price').value = ingredient.price;
                
                // Show the modal
                editIngredientModal.classList.remove('hidden');
                
            } catch (error) {
                console.error('Error fetching ingredient:', error);
                alert('Error fetching ingredient data: ' + error.message);
            }
        };

        // Form submission handler for edit
        document.getElementById('edit-ingredient-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                ingredient_id: document.getElementById('edit-ingredient-id').value,
                ingredient_name: document.getElementById('edit-ingredient-name').value,
                category: document.getElementById('edit-ingredient-category').value,
                quantity: document.getElementById('edit-ingredient-quantity').value,
                price: document.getElementById('edit-ingredient-price').value
            };
            
            try {
                // Send data to server
                const response = await fetch('inventory_handlers/update_ingredient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) {
                    throw new Error('Failed to update ingredient');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and refresh the ingredients list
                    editIngredientModal.classList.add('hidden');
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Ingredient updated successfully!');
                } else {
                    throw new Error(result.message || 'Failed to update ingredient');
                }
                
            } catch (error) {
                console.error('Error updating ingredient:', error);
                alert('Error updating ingredient: ' + error.message);
            }
        });

        // Delete confirmation
        window.confirmDelete = function(ingredientId) {
            document.getElementById('ingredient-to-delete').value = ingredientId;
            deleteConfirmModal.classList.remove('hidden');
        };

        // Delete handler
        confirmDeleteBtn.addEventListener('click', async function() {
            const ingredientId = document.getElementById('ingredient-to-delete').value;
            
            try {
                const response = await fetch('inventory_handlers/delete_ingredient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ingredient_id: ingredientId })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to delete ingredient');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and refresh the ingredients list
                    deleteConfirmModal.classList.add('hidden');
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    alert('Ingredient deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete ingredient');
                }
                
            } catch (error) {
                console.error('Error deleting ingredient:', error);
                alert('Error deleting ingredient: ' + error.message);
            }
        });
    </script>
</body>
</html>