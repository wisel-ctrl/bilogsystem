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
<div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 md:p-8 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <h3 class="text-3xl md:text-2xl font-bold text-deep-brown font-playfair">Expense Records</h3>
        <div class="flex items-center space-x-4">
            <div class="w-64">
                <input type="text" id="expense-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville text-gray-700 placeholder-gray-400 text-sm" placeholder="Search expenses...">
            </div>
            <div class="flex space-x-3">
                <button id="view-archived-btn" class="group w-10 hover:w-40 h-10 bg-warm-cream/100 hover:from-rich-brown hover:to-deep-brown text-rich-brown rounded-lg transition-all duration-400 flex items-center justify-center overflow-hidden shadow-md hover:shadow-lg font-baskerville">
                    <i class="fas fa-archive text-base"></i>
                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 transition-all duration-100">View Archived</span>
                </button>
                <button id="add-expense-btn" class="group w-10 hover:w-40 h-10 bg-warm-cream/100 hover:from-rich-brown hover:to-deep-brown text-rich-brown rounded-lg transition-all duration-400 flex items-center justify-center overflow-hidden shadow-md hover:shadow-lg font-baskerville">
                    <i class="fas fa-plus text-base"></i>
                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 transition-all duration-100">Add Expense</span>
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto display nowrap">
            <thead>
                <tr>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Date</th>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Description</th>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Category</th>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Amount</th>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Notes</th>
                    <th class="p-4 text-center font-semibold text-deep-brown font-playfair w-32">Actions</th>
                </tr>
            </thead>
            <tbody id="expense-table-body">
                <?php if (empty($expenses)): ?>
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 text-base font-baskerville">No expenses found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="p-4 text-gray-700 text-sm font-baskerville">
                                <?php
                                $date = new DateTime($expense['expense_date'], new DateTimeZone('Asia/Manila'));
                                echo $date->format('F d, Y');
                                ?>
                            </td>
                            <td class="p-4 text-gray-700 text-sm font-baskerville"><?php echo htmlspecialchars($expense['description']); ?></td>
                            <td class="p-4 text-gray-700 text-sm font-baskerville"><?php echo htmlspecialchars(ucfirst($expense['category'])); ?></td>
                            <td class="p-4 text-gray-700 text-sm font-baskerville">₱<?php echo number_format($expense['amount'], 2); ?></td>
                            <td class="p-4 text-gray-700 text-sm font-baskerville"><?php echo htmlspecialchars($expense['notes'] ?: '-'); ?></td>
                            <td class="p-4 flex justify-center space-x-3">
                                <button class="group edit-btn w-8 hover:w-32 h-8 bg-warm-cream/80 text-rich-brown hover:text-deep-brown rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform" data-id="<?php echo htmlspecialchars($expense['id']); ?>">
                                    <i class="fas fa-edit text-lg flex-shrink-0"></i>
                                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-300">Edit</span>
                                </button>
                                <button class="group archive-btn w-8 hover:w-32 h-8 bg-blue-100 text-blue-800 hover:text-blue-500 rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform" data-id="<?php echo htmlspecialchars($expense['id']); ?>">
                                    <i class="fas fa-archive text-lg flex-shrink-0"></i>
                                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-300">Archive</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Expense Modal -->
<div id="create-expense-modal" class="fixed inset-0 z-[100] flex items-start justify-center bg-black bg-opacity-60 transition-opacity duration-300 modal modal-hidden pt-16">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 overflow-y-auto max-h-[85vh]">
        <div class="flex justify-between items-center bg-amber-800 text-white p-5 rounded-t-xl">
            <h3 class="text-xl md:text-2xl font-semibold">Add New Expense</h3>
            <button id="close-modal" class="text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="expense-form" class="p-6 md:p-8" method="POST" action="">
            <input type="hidden" name="form_type" value="expense">
            <?php if (!empty($errors) && isset($_POST['form_type']) && $_POST['form_type'] === 'expense'): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong class="font-semibold">Error!</strong>
                    </div>
                    <span class="block mt-1">Please fix the following issues:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($creation_success): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong class="font-semibold">Success!</strong>
                    </div>
                    <span class="block mt-1">Expense created successfully!</span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="input-group col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <input type="text" id="description" name="description" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="category" name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                        <option value="">Select Category</option>
                        <option value="utilities">Utilities</option>
                        <option value="rent">Rent</option>
                        <option value="salaries">Salaries</option>
                        <option value="equipment">Equipment</option>
                        <option value="ingredients">Ingredients</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount *</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" id="expense_date" name="expense_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" id="cancel-btn" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-300 font-medium">
                    Cancel
                </button>
                <button type="submit" id="submit-btn" class="px-5 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-300 font-medium" disabled>
                    Add Expense
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Expense Modal -->
<div id="edit-expense-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300 modal modal-hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 overflow-y-auto max-h-[85vh]">
        <div class="flex justify-between items-center bg-amber-800 text-white p-5 rounded-t-xl">
            <h3 class="text-xl md:text-2xl font-semibold">Edit Expense</h3>
            <button id="close-edit-modal" class="text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="edit-expense-form" class="p-6 md:p-8" method="POST" action="">
            <input type="hidden" name="form_type" value="edit_expense">
            <input type="hidden" name="expense_id" id="edit-expense-id">
            <?php if (!empty($errors) && isset($_POST['form_type']) && $_POST['form_type'] === 'edit_expense'): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong class="font-semibold">Error!</strong>
                    </div>
                    <span class="block mt-1">Please fix the following issues:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($update_success): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong class="font-semibold">Success!</strong>
                    </div>
                    <span class="block mt-1">Expense updated successfully!</span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="input-group col-span-2">
                    <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <input type="text" id="edit-description" name="description" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="edit-category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="edit-category" name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                        <option value="">Select Category</option>
                        <option value="utilities">Utilities</option>
                        <option value="rent">Rent</option>
                        <option value="salaries">Salaries</option>
                        <option value="equipment">Equipment</option>
                        <option value="ingredients">Ingredients</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="edit-amount" class="block text-sm font-medium text-gray-700 mb-1">Amount *</label>
                    <input type="number" id="edit-amount" name="amount" step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="edit-expense_date" class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" id="edit-expense_date" name="expense_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-2">
                    <label for="edit-notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="edit-notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" id="cancel-edit-btn" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-300 font-medium">
                    Cancel
                </button>
                <button type="submit" id="submit-edit-btn" class="px-5 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-300 font-medium">
                    Update Expense
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Archived Expenses Modal -->
<div id="archived-expenses-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300 modal modal-hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 transform transition-all duration-300">
        <div class="flex justify-between items-center bg-amber-900 text-white p-5 rounded-t-xl">
            <h3 class="text-xl md:text-2xl font-semibold">Archived Expenses</h3>
            <button id="close-archived-modal" class="text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 md:p-8 max-h-[70vh] overflow-y-auto">
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="text-amber-800">
                        <tr>
                            <th class="py-4 px-6 text-left text-md font-bold">Date</th>
                            <th class="py-4 px-6 text-left text-md font-bold">Description</th>
                            <th class="py-4 px-6 text-left text-md font-bold">Category</th>
                            <th class="py-4 px-6 text-left text-md font-bold">Amount</th>
                            <th class="py-4 px-6 text-left text-md font-bold">Notes</th>
                            <th class="py-4 px-6 text-center text-md font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="archived-table-body">
                        <?php if (empty($archived_expenses)): ?>
                            <tr>
                                <td colspan="6" class="py-6 px-6 text-center text-gray-500 text-base">No archived expenses found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($archived_expenses as $expense): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6 text-gray-700 text-sm">
                                        <?php
                                        $date = new DateTime($expense['expense_date'], new DateTimeZone('Asia/Manila'));
                                        echo $date->format('F d, Y');
                                        ?>
                                    </td>
                                    <td class="py-4 px-6 text-gray-700 text-sm"><?php echo htmlspecialchars($expense['description']); ?></td>
                                    <td class="py-4 px-6 text-gray-700 text-sm"><?php echo htmlspecialchars(ucfirst($expense['category'])); ?></td>
                                    <td class="py-4 px-6 text-gray-700 text-sm">₱<?php echo number_format($expense['amount'], 2); ?></td>
                                    <td class="py-4 px-6 text-gray-700 text-sm"><?php echo htmlspecialchars($expense['notes'] ?: '-'); ?></td>
                                    <td class="py-4 px-6 flex justify-center">
                                        <button class="unarchive-btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center text-sm font-medium" data-id="<?php echo htmlspecialchars($expense['id']); ?>">
                                            <i class="fas fa-undo mr-2"></i> Unarchive
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex justify-end p-5 border-t border-gray-200">
            <button id="cancel-archived-btn" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-300 font-medium">
                Close
            </button>
        </div>
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

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('collapsed');
    });

    // Set current date
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
    }, { threshold: 0.1 });
    animateElements.forEach(element => observer.observe(element));

    // Expense Management Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const createModal = document.getElementById('create-expense-modal');
        const editModal = document.getElementById('edit-expense-modal');
        const archivedModal = document.getElementById('archived-expenses-modal');
        const addBtn = document.getElementById('add-expense-btn');
        const viewArchivedBtn = document.getElementById('view-archived-btn');
        const closeCreateModalBtn = document.getElementById('close-modal');
        const cancelCreateBtn = document.getElementById('cancel-btn');
        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const closeArchivedModalBtn = document.getElementById('close-archived-modal');
        const cancelArchivedBtn = document.getElementById('cancel-archived-btn');
        const createForm = document.getElementById('expense-form');
        const editForm = document.getElementById('edit-expense-form');
        const createInputs = createForm.querySelectorAll('input[required], select[required]');
        const editInputs = editForm.querySelectorAll('input[required], select[required]');

        function initFloatingLabels(form, inputs) {
            inputs.forEach(input => {
                const inputGroup = input.closest('.input-group');
                if (input.value.trim() !== '') {
                    inputGroup?.classList.add('has-content');
                }
                input.addEventListener('input', () => {
                    if (input.value.trim() !== '') {
                        inputGroup?.classList.add('has-content');
                    } else {
                        inputGroup?.classList.remove('has-content');
                    }
                    validateField(input, form);
                    checkFormValidity(form);
                });
                input.addEventListener('blur', () => {
                    validateField(input, form);
                });
            });
        }

        function toggleModal(modal) {
            modal.classList.toggle('modal-hidden');
            if (!modal.classList.contains('modal-hidden') && modal === createModal) {
                createForm.reset();
                const today = new Date().toISOString().split('T')[0];
                createForm.querySelector('#expense_date').value = today;
                createInputs.forEach(input => validateField(input, createForm));
                checkFormValidity(createForm);
                createForm.querySelectorAll('.field-feedback').forEach(fb => {
                    fb.classList.add('hidden');
                    fb.textContent = '';
                });
            }
            if (!modal.classList.contains('modal-hidden') && modal === editModal) {
                editInputs.forEach(input => validateField(input, editForm));
                checkFormValidity(editForm);
                editForm.querySelectorAll('.field-feedback').forEach(fb => {
                    fb.classList.add('hidden');
                    fb.textContent = '';
                });
            }
        }

        addBtn.addEventListener('click', () => toggleModal(createModal));
        closeCreateModalBtn.addEventListener('click', () => toggleModal(createModal));
        cancelCreateBtn.addEventListener('click', () => toggleModal(createModal));
        closeEditModalBtn.addEventListener('click', () => toggleModal(editModal));
        cancelEditBtn.addEventListener('click', () => toggleModal(editModal));
        viewArchivedBtn.addEventListener('click', () => toggleModal(archivedModal));
        closeArchivedModalBtn.addEventListener('click', () => toggleModal(archivedModal));
        cancelArchivedBtn.addEventListener('click', () => toggleModal(archivedModal));

        createModal.addEventListener('click', function(e) {
            if (e.target === createModal) {
                toggleModal(createModal);
            }
        });

        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                toggleModal(editModal);
            }
        });

        archivedModal.addEventListener('click', function(e) {
            if (e.target === archivedModal) {
                toggleModal(archivedModal);
            }
        });

        function validateField(field, form) {
            const inputGroup = field.closest('.input-group');
            const feedback = inputGroup?.querySelector('.field-feedback');
            let isValid = true;
            let message = '';

            field.classList.remove('field-error', 'field-success');
            feedback?.classList.add('hidden');

            switch (field.name) {
                case 'description':
                    if (!field.value.trim()) {
                        isValid = false;
                        message = 'Description is required';
                    } else if (field.value.trim().length < 3) {
                        isValid = false;
                        message = 'Description must be at least 3 characters';
                    }
                    break;

                case 'category':
                    if (!field.value) {
                        isValid = false;
                        message = 'Category is required';
                    }
                    break;

                case 'amount':
                    const amount = parseFloat(field.value);
                    if (!field.value.trim()) {
                        isValid = false;
                        message = 'Amount is required';
                    } else if (isNaN(amount) || amount <= 0) {
                        isValid = false;
                        message = 'Amount must be a positive number';
                    }
                    break;

                case 'expense_date':
                    if (!field.value) {
                        isValid = false;
                        message = 'Date is required';
                    } else if (!/^\d{4}-\d{2}-\d{2}$/.test(field.value)) {
                        isValid = false;
                        message = 'Invalid date format';
                    }
                    break;
            }

            if (field.value.trim() !== '' || field.hasAttribute('required')) {
                if (isValid) {
                    field.classList.add('field-success');
                    if (message) {
                        feedback.textContent = message;
                        feedback.className = 'field-feedback mt-2 text-sm text-green-600';
                        feedback.classList.remove('hidden');
                    }
                } else {
                    field.classList.add('field-error');
                    feedback.textContent = message;
                    feedback.className = 'field-feedback mt-2 text-sm text-red-600';
                    feedback.classList.remove('hidden');
                }
            }

            return isValid;
        }

        function checkFormValidity(form) {
            const inputs = form.querySelectorAll('input, select');
            const submitBtn = form.querySelector('[type="submit"]');
            let isValid = true;

            inputs.forEach(input => {
                if (input.hasAttribute('required') || input.value.trim()) {
                    if (!validateField(input, form)) {
                        isValid = false;
                    }
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        isValid = false;
                    }
                }
            });

            submitBtn.disabled = !isValid;
        }

        // Initialize forms
        initFloatingLabels(createForm, createInputs);
        initFloatingLabels(editForm, editInputs);

        // Form submission handling
        createForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(createForm);
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        confirmButtonColor: '#8B4513'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: data.errors ? Object.values(data.errors).join('<br>') : data.message,
                        confirmButtonColor: '#8B4513'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.',
                    confirmButtonColor: '#8B4513'
                });
            });
        });

        editForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(editForm);
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        confirmButtonColor: '#8B4513'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: data.message === 'No changes were made to the expense information' ? 'warning' : 'error',
                        title: data.message === 'No changes were made to the expense information' ? 'No Changes' : 'Error',
                        html: data.errors ? Object.values(data.errors).join('<br>') : data.message,
                        confirmButtonColor: '#8B4513'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.',
                    confirmButtonColor: '#8B4513'
                });
            });
        });

        // Edit button handling
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const expenseId = button.dataset.id;
                fetch('expense_handlers/get_expense.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: expenseId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const expense = data.expense;
                        editForm.querySelector('#edit-expense-id').value = expense.id;
                        editForm.querySelector('#edit-description').value = expense.description;
                        editForm.querySelector('#edit-category').value = expense.category;
                        editForm.querySelector('#edit-amount').value = expense.amount;
                        editForm.querySelector('#edit-expense_date').value = expense.expense_date;
                        editForm.querySelector('#edit-notes').value = expense.notes || '';
                        toggleModal(editModal);
                        editInputs.forEach(input => validateField(input, editForm));
                        checkFormValidity(editForm);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#8B4513'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch expense data.',
                        confirmButtonColor: '#8B4513'
                    });
                });
            });
        });

        // Archive button handling
        document.querySelectorAll('.archive-btn').forEach(button => {
            button.addEventListener('click', () => {
                const expenseId = button.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will archive the expense.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, archive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('expense_handlers/archive_expense.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: expenseId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Archived',
                                    text: data.message,
                                    confirmButtonColor: '#8B4513'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                    confirmButtonColor: '#8B4513'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while archiving the expense.',
                                confirmButtonColor: '#8B4513'
                            });
                        });
                    }
                });
            });
        });

        // Unarchive button handling
        document.querySelectorAll('.unarchive-btn').forEach(button => {
            button.addEventListener('click', () => {
                const expenseId = button.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will restore the expense.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('expense_handlers/unarchive_expense.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: expenseId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Restored',
                                    text: data.message,
                                    confirmButtonColor: '#8B4513'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                    confirmButtonColor: '#8B4513'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while restoring the expense.',
                                confirmButtonColor: '#8B4513'
                            });
                        });
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('expense-search').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#expense-table-body tr');
            rows.forEach(row => {
                const description = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                row.style.display = description.includes(searchTerm) || category.includes(searchTerm) ? '' : 'none';
            });
        });

        // Profile dropdown
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            profileMenu.classList.toggle('opacity-0');
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileMenu.classList.add('opacity-0');
            }
        });
    });
</script>
<?php
$page_scripts = ob_get_clean();

// Include the layout
include 'layout.php';
?>