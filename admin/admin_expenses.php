<?php
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

$creation_success = false;
$update_success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'expense') {
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $expense_date = trim($_POST['expense_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($description)) $errors['description'] = 'Description is required';
    if (empty($category)) $errors['category'] = 'Category is required';
    if (empty($amount)) {
        $errors['amount'] = 'Amount is required';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = 'Amount must be a positive number';
    }
    if (empty($expense_date)) {
        $errors['expense_date'] = 'Date is required';
    } elseif (!DateTime::createFromFormat('Y-m-d', $expense_date)) {
        $errors['expense_date'] = 'Invalid date format';
    }

    if (empty($errors)) {
        try {
            $createdAt = (new DateTime())->format('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO expenses_tb (description, category, amount, expense_date, notes, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$description, $category, $amount, $expense_date, $notes, $createdAt]);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Expense created successfully'
            ]);
            exit;
        } catch(PDOException $e) {
            $errors['database'] = 'Creation failed: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'edit_expense') {
    $expenseId = trim($_POST['expense_id'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $expense_date = trim($_POST['expense_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($description)) $errors['description'] = 'Description is required';
    if (empty($category)) $errors['category'] = 'Category is required';
    if (empty($amount)) {
        $errors['amount'] = 'Amount is required';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = 'Amount must be a positive number';
    }
    if (empty($expense_date)) {
        $errors['expense_date'] = 'Date is required';
    } elseif (!DateTime::createFromFormat('Y-m-d', $expense_date)) {
        $errors['expense_date'] = 'Invalid date format';
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT description, category, amount, expense_date, notes FROM expenses_tb WHERE id = ? AND status = 1");
            $stmt->execute([$expenseId]);
            $existingExpense = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingExpense) {
                $errors['database'] = 'Expense not found';
            } else {
                $noChanges = (
                    $description === $existingExpense['description'] &&
                    $category === $existingExpense['category'] &&
                    $amount == $existingExpense['amount'] &&
                    $expense_date === $existingExpense['expense_date'] &&
                    $notes === $existingExpense['notes']
                );

                if ($noChanges) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'No changes were made to the expense information'
                    ]);
                    exit;
                } else {
                    try {
                        $updatedAt = (new DateTime())->format('Y-m-d H:i:s');
                        $stmt = $conn->prepare("UPDATE expenses_tb SET description = ?, category = ?, amount = ?, expense_date = ?, notes = ?, updated_at = ? WHERE id = ?");
                        $stmt->execute([$description, $category, $amount, $expense_date, $notes, $updatedAt, $expenseId]);

                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'message' => 'Expense updated successfully'
                        ]);
                        exit;
                    } catch(PDOException $e) {
                        $errors['database'] = 'Update failed: ' . $e->getMessage();
                    }
                }
            }
        } catch(PDOException $e) {
            $errors['database'] = 'Failed to fetch expense data: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        exit;
    }
}

// Fetch expenses for display
try {
    $stmt = $conn->prepare("SELECT id, description, category, amount, expense_date, notes, created_at FROM expenses_tb WHERE status = 1 ORDER BY expense_date DESC");
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $errors['database'] = 'Failed to fetch expenses: ' . $e->getMessage();
}

// Fetch archived expenses
try {
    $stmt = $conn->prepare("SELECT id, description, category, amount, expense_date, notes, created_at FROM expenses_tb WHERE status = 0 ORDER BY expense_date DESC");
    $stmt->execute();
    $archived_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $errors['database'] = 'Failed to fetch archived expenses: ' . $e->getMessage();
}

// Define page title and content
$page_title = "Expense Management";

ob_start();
?>
    
    <!-- Modal for adding expenses -->
    <div id="expense-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full ">
                <div class="modal-header bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-deep-brown font-playfair">
                                Add New Expense
                            </h3>
                            <div class="mt-4">
                                <form id="expense-form">
                                    <div class="mb-4">
                                        <label for="expense-name" class="block text-sm font-medium text-rich-brown font-baskerville">Expense Name</label>
                                        <input type="text" id="expense-name" name="expense-name" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="expense-category" class="block text-sm font-medium text-rich-brown font-baskerville">Category</label>
                                        <select id="expense-category" name="expense-category" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                            <option value="utilities">Utilities</option>
                                            <option value="rent">Rent</option>
                                            <option value="salaries">Salaries</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="ingredients">Ingredients</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="expense-amount" class="block text-sm font-medium text-rich-brown font-baskerville">Amount</label>
                                        <input type="number" id="expense-amount" name="expense-amount" step="0.01" min="0" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="expense-notes" class="block text-sm font-medium text-rich-brown font-baskerville">Notes</label>
                                        <textarea id="expense-notes" name="expense-notes" rows="3" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="save-expense" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream text-base font-medium transition-all duration-200 sm:ml-3 sm:w-auto sm:text-sm font-baskerville">
                        Save Expense
                    </button>
                    <button type="button" id="cancel-expense" class="mt-3 w-full inline-flex justify-center rounded-lg border border-warm-cream shadow-sm px-4 py-2 bg-white text-base font-medium text-deep-brown hover:bg-warm-cream/10 transition-all duration-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm font-baskerville">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing expenses -->
    <div id="edit-expense-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="modal-header bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-deep-brown font-playfair">
                                Edit Expense
                            </h3>
                            <div class="mt-4">
                                <form id="edit-expense-form">
                                    <input type="hidden" id="edit-expense-id">
                                    <div class="mb-4">
                                        <label for="edit-expense-name" class="block text-sm font-medium text-rich-brown font-baskerville">Expense Name</label>
                                        <input type="text" id="edit-expense-name" name="edit-expense-name" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit-expense-category" class="block text-sm font-medium text-rich-brown font-baskerville">Category</label>
                                        <select id="edit-expense-category" name="edit-expense-category" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                            <option value="utilities">Utilities</option>
                                            <option value="rent">Rent</option>
                                            <option value="salaries">Salaries</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="ingredients">Ingredients</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit-expense-amount" class="block text-sm font-medium text-rich-brown font-baskerville">Amount</label>
                                        <input type="number" id="edit-expense-amount" name="edit-expense-amount" step="0.01" min="0" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit-expense-notes" class="block text-sm font-medium text-rich-brown font-baskerville">Notes</label>
                                        <textarea id="edit-expense-notes" name="edit-expense-notes" rows="3" class="mt-1 p-2 w-full border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="update-expense" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream text-base font-medium transition-all duration-200 sm:ml-3 sm:w-auto sm:text-sm font-baskerville">
                        Update Expense
                    </button>
                    <button type="button" id="cancel-edit-expense" class="mt-3 w-full inline-flex justify-center rounded-lg border border-warm-cream shadow-sm px-4 py-2 bg-white text-base font-medium text-deep-brown hover:bg-warm-cream/10 transition-all duration-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm font-baskerville">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown to-rich-brown text-white transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
        <div class="sidebar-header p-6 border-b border-warm-cream/20">
                <div>
                    <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
                    <p class="nav-subtitle text-xs text-warm-cream tracking-widest">RISTORANTE</p>
                </div>
            </div>
            
            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-chart-pie w-5"></i>
                            <span class="sidebar-text font-baskerville">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookings.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span class="sidebar-text font-baskerville">Booking Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_menu.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-utensils w-5"></i>
                            <span class="sidebar-text font-baskerville">Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inventory.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-boxes w-5"></i>
                            <span class="sidebar-text font-baskerville">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-warm-cream/10 text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="sidebar-text font-baskerville">Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_employee_creation.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-user-plus w-5"></i>
                            <span class="sidebar-text font-baskerville">Our Employee</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[50]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-deep-brown hover:text-rich-brown transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- <h2 class="text-2xl font-bold text-deep-brown font-playfair">Expense Management</h2> -->
                    </div>
                    <div class="text-sm text-rich-brown font-baskerville flex-1 text-center mx-4">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span id="current-date"></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="profileDropdown" class="flex items-center space-x-2 hover:bg-warm-cream/10 p-2 rounded-lg transition-all duration-200">
                                <div class="w-10 h-10 rounded-full border-2 border-accent-brown overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-medium text-deep-brown font-baskerville">Admin</span>
                                <i class="fas fa-chevron-down text-deep-brown text-sm transition-transform duration-200"></i>
                            </button>
                            <div id="profileMenu" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden transform opacity-0 transition-all duration-200">
                                <a href="../logout.php" class="flex items-center space-x-2 px-4 py-2 text-sm text-deep-brown hover:bg-warm-cream/10 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Sign Out</span>
                                </a>
                            </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 relative z-0">
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Expense Records</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-64">
                                <input type="text" id="expense-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" placeholder="Search expenses...">
                            </div>
                            <button id="add-expense-btn" class="group w-10 hover:w-52 h-10 bg-warm-cream text-rich-brown rounded-lg transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden shadow-md hover:shadow-lg">
                                <i class="fas fa-plus text-lg flex-shrink-0"></i>
                                <span class="font-baskerville opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-75">Add New Expense</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                    <table id="expenses-table" class="w-full table-auto display nowrap" style="width:100%">
    <thead>
        <tr>
            <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Date</th>
            <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Expense Name</th>
            <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Category</th>
            <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Amount</th>
            <th class="text-left p-4 font-semibold text-deep-brown font-playfair">Notes</th>
            <th class="text-left p-4 font-semibold text-deep-brown font-playfair w-32">Actions</th>
        </tr>
    </thead>
    <tbody>
      
    </tbody>
</table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php
$page_content = ob_get_clean();

// Page-specific scripts
ob_start();
?>
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

        // Expense Modal functionality
        const expenseModal = document.getElementById('expense-modal');
        const addExpenseBtn = document.getElementById('add-expense-btn');
        const cancelExpenseBtn = document.getElementById('cancel-expense');
        const saveExpenseBtn = document.getElementById('save-expense');

        // Open modal
        addExpenseBtn.addEventListener('click', () => {
            expenseModal.classList.remove('hidden');
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('expense-date').value = today;
        });

        // Close modal
        function closeModal() {
            expenseModal.classList.add('hidden');
            // Reset form
            document.getElementById('expense-form').reset();
        }

        // Close modal when clicking cancel
        cancelExpenseBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside the modal content
        expenseModal.addEventListener('click', (e) => {
            if (e.target === expenseModal) {
                closeModal();
            }
        });

        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#expenses-table').DataTable({
                dom: '<"overflow-x-auto"rt><"flex flex-col sm:flex-row justify-between items-center mt-2"<"text-sm text-gray-600"i><"mt-2 sm:mt-0"p>>',
                ajax: {
                    url: 'expense_handlers/get_expenses.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'created_at' },
                    { 
                        data: 'expense_name',
                        render: function(data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    { 
                        data: 'expense_category',
                        render: function(data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    { 
                        data: 'amount',
                        render: function(data, type, row) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'notes' },
                    {
                        data: 'expense_id',
                        render: function(data, type, row) {
                            return `
                                <div class="flex space-x-2">
                                        <!-- Edit Button -->
                                        <button 
                                            class="group edit-btn w-8 hover:w-24 h-8 bg-warm-cream/80 text-rich-brown hover:text-deep-brown rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform hover:scale-105" 
                                            onclick="editExpense(${data})">
                                            <i class="fas fa-edit text-base flex-shrink-0"></i>
                                            <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-75">Edit</span>
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            class="group delete-btn w-8 hover:w-28 h-8 bg-red-100 hover:bg-red-200 text-red-500 hover:text-red-700 rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform hover:scale-105" 
                                            onclick="deleteExpense(${data})">
                                            <i class="fas fa-trash-alt text-base flex-shrink-0"></i>
                                            <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-75">Delete</span>
                                        </button>
                                    </div>
                            `;
                        },
                        "orderable": false
                    }
                ],
                order: [[0, 'desc']], // Sort by date descending by default
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

            // Add search functionality
            $('#expense-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Refresh table every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 30000);
        });

        // Add expense
        document.getElementById('save-expense').addEventListener('click', function() {
            const name = document.getElementById('expense-name').value.trim();
            const category = document.getElementById('expense-category').value;
            const amount = parseFloat(document.getElementById('expense-amount').value);
            
            // Validation
            if (!name) {
                alert('Expense name is required');
                return;
            }
            
            if (!category) {
                alert('Category is required');
                return;
            }
            
            if (isNaN(amount)) {
                alert('Amount must be a valid number');
                return;
            }
            
            if (amount <= 0) {
                alert('Amount must be greater than 0');
                return;
            }

            const formData = {
                expense_name: name,
                expense_category: category,
                amount: amount,
                notes: document.getElementById('expense-notes').value.trim()
            };

            fetch('expense_handlers/add_expense.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    $('#expenses-table').DataTable().ajax.reload();
                    closeModal();
                    alert('Expense added successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the expense.');
            });
        });

        // Edit expense
        function editExpense(expenseId) {
            fetch('expense_handlers/get_expense.php?id=' + expenseId)
            .then(response => response.json())
            .then(data => {
                if(data) {
                    document.getElementById('edit-expense-id').value = data.expense_id;
                    document.getElementById('edit-expense-name').value = data.expense_name;
                    document.getElementById('edit-expense-category').value = data.expense_category;
                    document.getElementById('edit-expense-amount').value = data.amount;
                    document.getElementById('edit-expense-notes').value = data.notes;
                    
                    document.getElementById('edit-expense-modal').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching expense data.');
            });
        }

        // Update expense
        document.getElementById('update-expense').addEventListener('click', function() {
            const name = document.getElementById('edit-expense-name').value.trim();
            const category = document.getElementById('edit-expense-category').value;
            const amount = parseFloat(document.getElementById('edit-expense-amount').value);
            
            // Validation
            if (!name) {
                alert('Expense name is required');
                return;
            }
            
            if (!category) {
                alert('Category is required');
                return;
            }
            
            if (isNaN(amount)) {
                alert('Amount must be a valid number');
                return;
            }
            
            if (amount <= 0) {
                alert('Amount must be greater than 0');
                return;
            }

            const formData = {
                expense_id: document.getElementById('edit-expense-id').value,
                expense_name: name,
                expense_category: category,
                amount: amount,
                notes: document.getElementById('edit-expense-notes').value.trim()
            };

            fetch('expense_handlers/update_expense.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    $('#expenses-table').DataTable().ajax.reload();
                    document.getElementById('edit-expense-modal').classList.add('hidden');
                    alert('Expense updated successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the expense.');
            });
        });

        // Delete expense (actually just hide it)
        function deleteExpense(expenseId) {
            if(confirm('Are you sure you want to delete this expense?')) {
                fetch('expense_handlers/delete_expense.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ expense_id: expenseId })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        $('#expenses-table').DataTable().ajax.reload();
                        alert('Expense deleted successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the expense.');
                });
            }
        }

        // Close edit modal
        document.getElementById('cancel-edit-expense').addEventListener('click', function() {
            document.getElementById('edit-expense-modal').classList.add('hidden');
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !expenseModal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Add profile dropdown functionality
        document.getElementById('profileDropdown').addEventListener('click', function() {
            const menu = document.getElementById('profileMenu');
            const icon = this.querySelector('.fa-chevron-down');
            
            menu.classList.toggle('hidden');
            menu.classList.toggle('opacity-0');
            icon.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const menu = document.getElementById('profileMenu');
            
            if (!dropdown.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                menu.classList.add('opacity-0');
                dropdown.querySelector('.fa-chevron-down').style.transform = 'rotate(0deg)';
            }
        });
    </script>
<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>