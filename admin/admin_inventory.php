
<?php
    require_once '../db_connect.php';

    // Set the timezone to Philippine Time
    date_default_timezone_set('Asia/Manila');

    // Define page title
    $page_title = "Inventory Management";

    // Capture page content
    ob_start();
?>


       <!-- Inventory Management Section -->
            <!-- Add Ingredient Modal -->
    <div id="add-ingredient-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-8">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="modal-header p-6 border-b border-warm-cream/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Add New Ingredient</h3>
                    <button id="close-add-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="add-ingredient-form" class="modal-body flex-1 overflow-y-auto p-6 space-y-6">
                <div>
                    <label for="ingredient-name" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Name</label>
                    <input type="text" id="ingredient-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter ingredient name" required>
                </div>

                <div>
                    <label for="ingredient-category" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
                    <select id="ingredient-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <option value="">Select category</option>
                        <option value="produce" title="Fresh fruits and vegetables">Produce</option>
                        <option value="protein" title="Meat, poultry, seafood, and plant proteins">Proteins</option>
                        <option value="dairy" title="Milk, cheese, butter, etc.">Dairy</option>
                        <option value="grains" title="Rice, pasta, flour, bread, etc.">Grains & Starches</option>
                        <option value="spices_herbs" title="Seasoning and herbs for flavor">Spices & Herbs</option>
                        <option value="oils_fats" title="Cooking oils, butter, and fats">Oils & Fats</option>
                        <option value="condiments" title="Sauces, vinegar, and condiments">Condiments & Sauces</option>
                        <option value="baking" title="Flour, sugar, yeast, baking powder, etc.">Baking Ingredients</option>
                        <option value="beverages" title="Juices, soft drinks, tea, coffee, etc.">Beverages</option>
                        <option value="canned" title="Canned fruits, beans, jams, etc.">Canned & Preserved Goods</option>
                        <option value="frozen" title="Frozen vegetables, meats, pre-cooked items">Frozen Ingredients</option>
                        <option value="nuts_seeds" title="Nuts, seeds, legumes, beans">Nuts, Seeds & Legumes</option>
                        <option value="other">Other..</option>
                    </select>
                </div>

                <div>
                    <label for="ingredient-quantity" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Quantity</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" id="ingredient-quantity" placeholder="e.g. 1.5" step="0.01" min="0"
                            class="w-full px-4 py-2 pr-12 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500 text-sm">
                            Kg/L
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">1 kg = 1000 grams</p>
                </div>

                <div>
                    <label for="ingredient-price" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (per unit)</label>
                    <input type="text" id="ingredient-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Enter price" required>
                </div>
            </form>

            <div class="modal-footer p-6 border-t border-warm-cream/20">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-add-ingredient" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="submit" form="add-ingredient-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                        Add Ingredient
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Ingredient Modal -->
    <div id="edit-ingredient-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-8">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="modal-header p-6 border-b border-warm-cream/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Ingredient</h3>
                    <button id="close-edit-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="edit-ingredient-form" class="modal-body flex-1 overflow-y-auto p-6 space-y-6">
                <input type="hidden" id="edit-ingredient-id">

                <div>
                    <label for="edit-ingredient-name" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Name</label>
                    <input type="text" id="edit-ingredient-name" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>

                <div>
                    <label for="edit-ingredient-category" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Category</label>
                    <select id="edit-ingredient-category" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                        <option value="produce" title="Fresh fruits and vegetables">Produce</option>
                        <option value="protein" title="Meat, poultry, seafood, and plant proteins">Proteins</option>
                        <option value="dairy" title="Milk, cheese, butter, etc.">Dairy</option>
                        <option value="grains" title="Rice, pasta, flour, bread, etc.">Grains & Starches</option>
                        <option value="spices_herbs" title="Seasoning and herbs for flavor">Spices & Herbs</option>
                        <option value="oils_fats" title="Cooking oils, butter, and fats">Oils & Fats</option>
                        <option value="condiments" title="Sauces, vinegar, and condiments">Condiments & Sauces</option>
                        <option value="baking" title="Flour, sugar, yeast, baking powder, etc.">Baking Ingredients</option>
                        <option value="beverages" title="Juices, soft drinks, tea, coffee, etc.">Beverages</option>
                        <option value="canned" title="Canned fruits, beans, jams, etc.">Canned & Preserved Goods</option>
                        <option value="frozen" title="Frozen vegetables, meats, pre-cooked items">Frozen Ingredients</option>
                        <option value="nuts_seeds" title="Nuts, seeds, legumes, beans">Nuts, Seeds & Legumes</option>
                        <option value="other">Other..</option>
                    </select>
                </div>

                <div>
                    <label for="edit-ingredient-quantity" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Quantity</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" id="edit-ingredient-quantity" step="0.01" min="0" class="w-full px-4 py-2 pr-12 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500 text-sm">
                            Kg/L
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-ingredient-price" class="block text-sm font-medium text-deep-brown mb-2 font-baskerville">Price (per unit)</label>
                    <input type="text" id="edit-ingredient-price" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                </div>
            </form>

            <div class="modal-footer p-6 border-t border-warm-cream/20">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-edit-ingredient" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="submit" form="edit-ingredient-form" class="px-6 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-8">
        <div class="bg-white/95 backdrop-blur-md rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col">
            <div class="modal-header p-6 border-b border-warm-cream/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-deep-brown font-playfair">Confirm Deletion</h3>
                    <button id="close-delete-modal" class="text-rich-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body flex-1 overflow-y-auto p-6">
                <p class="text-gray-700 font-baskerville">Are you sure you want to delete this ingredient? This action cannot be undone.</p>
            </div>

            <div class="modal-footer p-6 border-t border-warm-cream/20">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-delete" class="px-6 py-2 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville">
                        Cancel
                    </button>
                    <button type="button" id="confirm-delete" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-baskerville">
                        Delete
                    </button>
                </div>
            </div>
            <input type="hidden" id="ingredient-to-delete">
        </div>
    </div>
    
    <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Inventory Items</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-64">
                                <input type="text" id="inventory-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Search ingredients...">
                            </div>
                            <button id="add-ingredient-btn" class="group w-10 hover:w-52 h-10 bg-warm-cream text-rich-brown rounded-lg transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden shadow-md hover:shadow-lg">
                                <i class="fas fa-plus text-lg flex-shrink-0"></i>
                                <span class="font-baskerville opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-75">Add Ingredient</span>
                            </button>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="overflow-x-auto">
                        <table id="ingredients-table" class="w-full table-auto display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Name</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Category</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Quantity</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Total Price</th>
                                    <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
    
    </div>
<?php
    $page_content = ob_get_clean();

    // Capture page-specific styles
    ob_start();
?>
<style>
    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-baskerville { font-family: 'Libre Baskerville', serif; }

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
    .delay-100 { transition-delay: 100ms; }
    .delay-200 { transition-delay: 200ms; }
    .delay-300 { transition-delay: 300ms; }
    .delay-400 { transition-delay: 400ms; }

    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Improved hover effects */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(93, 47, 15, 0.15);
    }
    
    /* Card styles */
    .dashboard-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(232, 224, 213, 0.5);
        box-shadow: 0 4px 6px rgba(93, 47, 15, 0.1);
        transition: all 0.3s ease;
    }
    
    .dashboard-card:hover {
        box-shadow: 0 8px 12px rgba(93, 47, 15, 0.15);
        transform: translateY(-2px);
    }
    
    
    
    /* Animation classes */
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Profile menu */
    /* #profileMenu {
        z-index: 9999 !important;
        transform: translateY(0) !important;
    }
 */


    /* DataTables custom styling */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        width: 100%;
        max-width: 300px;
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0.5rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.5rem;
        margin: 0 0.125rem;
        border-radius: 0.25rem;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151 !important;
        font-size: 0.875rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #8B4513 !important;
        color: white !important;
        border-color: #8B4513;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        border-color: #e5e7eb;
        color: #374151 !important;
    }
    
    .dataTables_wrapper .dataTables_info {
        padding: 0.5rem 0;
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* Fix sorting icons */
    table.dataTable thead th {
        position: relative;
        background-image: none !important;
    }
   
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        position: absolute;
        right: 8px;
        color: #A0522D;
    }

    table.dataTable thead th.sorting:after {
        content: "↕";
        opacity: 0.4;
    }
    
    table.dataTable thead th.sorting_asc:after {
        content: "↑";
    }
    
    table.dataTable thead th.sorting_desc:after {
        content: "↓";
    }

    /* Modal z-index */
    /* #add-ingredient-modal, #edit-ingredient-modal, #delete-confirm-modal {
        z-index: 1000 !important;
    } */

    /* Ensure main content doesn't create stacking context */
    .flex-1 {
        position: static;
    }

   

    /* Blur effect */
    .blur-effect {
        filter: blur(5px);
        transition: filter 0.3s ease;
        pointer-events: none;
    }

    /* Modal container */
    .modal-container {
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .modal-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 11;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .modal-body {
        flex: 1;
        overflow-y: auto;
    }

    .modal-footer {
        position: sticky;
        bottom: 0;
        background: white;
        z-index: 10;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    /* Improved scrollbar for modal body */
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #8B4513;
        border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #5D2F0F;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    thead {
        background-color: #f9fafb;
    }

    thead th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #5D2F0F;
        border-bottom: 2px solid #e5e7eb;
    }

    tbody tr {
        transition: background-color 0.2s ease;
    }

    tbody tr:hover {
        background-color: #f3f4f6;
    }

    tbody td {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Status badge styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Action button styles */
    .action-btn {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background-color: #f3f4f6;
    }

    .action-btn i {
        font-size: 1rem;
    }

    /* Improved scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
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
</style>

<?php
    $page_styles = ob_get_clean();

    // Capture page-specific scripts
    ob_start();
?>
    <script>
        // Sidebar Toggle
        // const sidebar = document.getElementById('sidebar');
        // const sidebarToggle = document.getElementById('sidebar-toggle');
        // const cafeTitle = document.getElementById('cafe-title');
        // const sidebarTexts = document.querySelectorAll('.sidebar-text');

 
          // Set current date with improved formatting
          const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', options);

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
                    "url": "inventory_handlers/fetch_ingredient.php",
                    "type": "POST",
                    "data": function(d) {
                        // Add a custom parameter to indicate we want to prioritize out-of-stock items
                        d.prioritizeOutOfStock = true;
                    }
                },
                "columns": [
                    {
                        "data": "ingredient_name",
                        "render": function(data, type, row) {
                            return data.replace(/\w\S*/g, function(txt) {
                                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                            });
                        }
                    },
                    { "data": "category" },
                    { 
                        "data": "quantity", 
                        "render": function(data, type, row) {
                            // Display "Out of Stock" for zero quantity
                            if (parseFloat(data) <= 0) {
                                return '<span class="text-red-500 font-semibold">Out of Stock</span>';
                            }
                            return parseFloat(data).toFixed(2) + ' kg';
                        },
                        "createdCell": function(td, cellData, rowData, row, col) {
                            // Add class for zero quantity cells
                            if (parseFloat(cellData) <= 0) {
                                $(td).addClass('bg-red-50');
                            }
                        }
                    },
                    { 
                        "data": "price",
                        "render": function(data, type, row) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        "data": "total_price",
                        "render": function(data, type, row) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        "data": "ingredient_id",
                        "render": function(data, type, row) {
                            return `
                                <div class="flex space-x-2">
                                    <!-- Edit Button -->
                                    <button 
                                        class="group edit-btn w-8 hover:w-24 h-8 bg-warm-cream/80 text-rich-brown hover:text-deep-brown rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform hover:scale-[1.03]"
                                        onclick="editIngredient(${data})">
                                        <i class="fas fa-edit text-base flex-shrink-0"></i>
                                        <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-75">Edit</span>
                                    </button>

                                    <!-- Delete Button -->
                                    <button 
                                        class="group delete-btn w-8 hover:w-28 h-8 bg-red-100 hover:bg-red-200 text-red-500 hover:text-red-700 rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform hover:scale-[1.03]"
                                        onclick="confirmDelete(${data})">
                                        <i class="fas fa-trash-alt text-base flex-shrink-0"></i>
                                        <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-75">Delete</span>
                                    </button>
                                </div>
                            `;
                        },
                        "orderable": false
                    }
                ],
                "order": [[2, 'asc']],
                "responsive": true,
                "dom": '<"overflow-x-auto"rt><"flex flex-col sm:flex-row justify-between items-center mt-2"<"text-sm text-gray-600"i><"mt-2 sm:mt-0"p>>',
                "lengthChange": false,
                "pageLength": 10,
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });

            // Connect custom search input to DataTable
            $('#inventory-search').on('keyup', function() {
                table.search(this.value).draw();
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
            // Add blur to main content
            document.querySelector('.flex-1').classList.add('blur-effect');
            
            // Show modal
            addIngredientModal.classList.remove('hidden');
        });

        // Close add ingredient modal
        function closeAddIngredientModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            
            // Hide modal
            addIngredientModal.classList.add('hidden');
            
            // Reset form
            addIngredientForm.reset();
        }

        closeAddModal.addEventListener('click', closeAddIngredientModal);
        cancelAddIngredient.addEventListener('click', closeAddIngredientModal);

        // Close edit ingredient modal
        function closeEditIngredientModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            
            // Hide modal
            editIngredientModal.classList.add('hidden');
            
            // Reset form
            document.getElementById('edit-ingredient-form').reset();
        }

        closeEditModal.addEventListener('click', closeEditIngredientModal);
        cancelEditIngredient.addEventListener('click', closeEditIngredientModal);

        // Close delete confirmation modal
        function closeDeleteConfirmModal() {
            // Remove blur from main content
            document.querySelector('.flex-1').classList.remove('blur-effect');
            
            // Hide modal
            deleteConfirmModal.classList.add('hidden');
            
            // Clear the ingredient ID
            document.getElementById('ingredient-to-delete').value = '';
        }

        closeDeleteModal.addEventListener('click', closeDeleteConfirmModal);
        cancelDelete.addEventListener('click', closeDeleteConfirmModal);

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === addIngredientModal) {
                closeAddIngredientModal();
            }
            if (event.target === editIngredientModal) {
                closeEditIngredientModal();
            }
            if (event.target === deleteConfirmModal) {
                closeDeleteConfirmModal();
            }
        });

        // Close modals when pressing Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                if (!addIngredientModal.classList.contains('hidden')) {
                    closeAddIngredientModal();
                }
                if (!editIngredientModal.classList.contains('hidden')) {
                    closeEditIngredientModal();
                }
                if (!deleteConfirmModal.classList.contains('hidden')) {
                    closeDeleteConfirmModal();
                }
            }
        });

        // Form submissions
        document.getElementById('add-ingredient-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('ingredient-name').value.trim();
            const category = document.getElementById('ingredient-category').value;
            const quantityInput = document.getElementById('ingredient-quantity').value;
            const priceInput = document.getElementById('ingredient-price').value;
            
            // Validate quantity
            if (!/^\d*\.?\d+$/.test(quantityInput)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Quantity must be a positive number (letters and symbols not allowed)'
                });
                return;
            }
            
            const quantity = parseFloat(quantityInput);
            if (quantity <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Quantity must be greater than zero'
                });
                return;
            }
            
            // Validate price
            if (!/^\d*\.?\d+$/.test(priceInput)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Price',
                    text: 'Price must be a positive number (letters and symbols not allowed)'
                });
                return;
            }
            
            const price = parseFloat(priceInput);
            if (price < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Price',
                    text: 'Price cannot be negative'
                });
                return;
            }
            
            // Basic validation for other fields
            if (!name || !category) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please fill all fields'
                });
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
                    closeAddIngredientModal();
                    addIngredientForm.reset();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Ingredient added successfully!'
                    });
                } else {
                    throw new Error(result.message || 'Failed to add ingredient');
                }
                
            } catch (error) {
                console.error('Error adding ingredient:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error adding ingredient: ' + error.message
                });
            }
        });

        // Function to open the edit modal with ingredient data
        window.editIngredient = async function(ingredientId) {
            try {
                // Validate ingredientId
                if (!ingredientId) {
                    throw new Error('Ingredient ID is required');
                }

                // Fetch ingredient data
                const response = await fetch(`inventory_handlers/get_ingredient.php?id=${ingredientId}`);
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    throw new Error(errorData?.error || `HTTP error! status: ${response.status}`);
                }
                
                const ingredient = await response.json();
                
                // Debug: log the received data
                console.log('Received ingredient data:', ingredient);
                
                // Populate the form - add null checks
                if (ingredient) {
                    document.getElementById('edit-ingredient-id').value = ingredient.ingredient_id || '';
                    document.getElementById('edit-ingredient-name').value = ingredient.ingredient_name || '';
                    document.getElementById('edit-ingredient-category').value = ingredient.category || '';
                    document.getElementById('edit-ingredient-quantity').value = ingredient.quantity || '';
                    document.getElementById('edit-ingredient-price').value = ingredient.price || '';
                    
                    // Add blur to main content
                    document.querySelector('.flex-1').classList.add('blur-effect');
                    
                    // Show modal with fade effect
                    editIngredientModal.classList.remove('hidden');
                    setTimeout(() => {
                        editIngredientModal.querySelector('.dashboard-card').style.opacity = '1';
                        editIngredientModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    throw new Error('Ingredient data is empty');
                }
                
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
                    closeEditIngredientModal();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Ingredient updated successfully!'
                    });
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
            
            // Add blur to main content
            document.querySelector('.flex-1').classList.add('blur-effect');
            
            // Show modal with fade effect
            deleteConfirmModal.classList.remove('hidden');
            setTimeout(() => {
                deleteConfirmModal.querySelector('.dashboard-card').style.opacity = '1';
                deleteConfirmModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
            }, 50);
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
                    closeDeleteConfirmModal();
                    
                    // Refresh the DataTable
                    refreshTable();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Ingredient deleted successfully!'
                    });
                } else {
                    throw new Error(result.message || 'Failed to delete ingredient');
                }
                
            } catch (error) {
                console.error('Error deleting ingredient:', error);
                alert('Error deleting ingredient: ' + error.message);
            }
        });
    </script>
<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>