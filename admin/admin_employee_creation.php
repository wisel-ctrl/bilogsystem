<?php
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

$registration_success = false;
$update_success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'check_username') {
        $username = trim($_POST['username'] ?? '');
        $cashierId = isset($_POST['cashier_id']) ? trim($_POST['cashier_id']) : null;
        
        try {
            if ($cashierId) {
                $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ? AND id != ?");
                $stmt->execute([$username, $cashierId]);
            } else {
                $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ?");
                $stmt->execute([$username]);
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'exists' => $stmt->rowCount() > 0,
                'message' => $stmt->rowCount() > 0 ? 'Username already taken' : 'Username available'
            ]);
            exit;
        } catch(PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'exists' => false,
                'error' => 'Database error: ' . $e->getMessage()
            ]);
            exit;
        }
    } elseif ($_POST['action'] === 'check_phone') {
        $phone = trim($_POST['phone'] ?? '');
        $phone = str_replace('-', '', $phone);
        $cashierId = isset($_POST['cashier_id']) ? trim($_POST['cashier_id']) : null;
        
        try {
            if ($cashierId) {
                $stmt = $conn->prepare("SELECT id FROM users_tb WHERE contact_number = ? AND id != ?");
                $stmt->execute([$phone, $cashierId]);
            } else {
                $stmt = $conn->prepare("SELECT id FROM users_tb WHERE contact_number = ?");
                $stmt->execute([$phone]);
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'exists' => $stmt->rowCount() > 0,
                'message' => $stmt->rowCount() > 0 ? 'Phone number already in use' : 'Phone number available'
            ]);
            exit;
        } catch(PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'exists' => false,
                'error' => 'Database error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'cashier') {
    $firstName = trim($_POST['fname'] ?? '');
    $middleName = trim($_POST['mname'] ?? '');
    $lastName = trim($_POST['lname'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contactNumber = str_replace('-', '', trim($_POST['phone'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    
    if (empty($firstName)) $errors['firstName'] = 'First name is required';
    if (empty($lastName)) $errors['lastName'] = 'Last name is required';
    if (empty($username)) $errors['username'] = 'Username is required';
    if (empty($contactNumber)) {
        $errors['contactNumber'] = 'Contact number is required';
    } elseif (!preg_match('/^[0-9]{11}$/', $contactNumber)) {
        $errors['contactNumber'] = 'Contact number must be 11 digits';
    } elseif (!preg_match('/^09\d{9}$/', $contactNumber)) {
        $errors['contactNumber'] = 'Please enter a valid Philippine mobile number (09XXXXXXXXX)';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    } elseif ($password !== $confirmPassword) {
        $errors['password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }

    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $usertype = 2;
            $createdAt = (new DateTime())->format('Y-m-d H:i:s');
            
            $stmt = $conn->prepare("INSERT INTO users_tb 
                (first_name, middle_name, last_name, suffix, username, contact_number, password, usertype, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $firstName,
                $middleName,
                $lastName,
                $suffix,
                $username,
                $contactNumber,
                $hashedPassword,
                $usertype,
                $createdAt
            ]);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Cashier account created successfully'
            ]);
            exit;
        } catch(PDOException $e) {
            $errors['database'] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'edit_cashier') {
    $cashierId = trim($_POST['cashier_id'] ?? '');
    $firstName = trim($_POST['fname'] ?? '');
    $middleName = trim($_POST['mname'] ?? '');
    $lastName = trim($_POST['lname'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contactNumber = str_replace('-', '', trim($_POST['phone'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    
    if (empty($firstName)) $errors['firstName'] = 'First name is required';
    if (empty($lastName)) $errors['lastName'] = 'Last name is required';
    if (empty($username)) $errors['username'] = 'Username is required';
    if (empty($contactNumber)) {
        $errors['contactNumber'] = 'Contact number is required';
    } elseif (!preg_match('/^[0-9]{11}$/', $contactNumber)) {
        $errors['contactNumber'] = 'Contact number must be 11 digits';
    } elseif (!preg_match('/^09\d{9}$/', $contactNumber)) {
        $errors['contactNumber'] = 'Please enter a valid Philippine mobile number (09XXXXXXXXX)';
    }
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif ($password !== $confirmPassword) {
            $errors['password'] = 'Passwords do not match';
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ? AND id != ?");
        $stmt->execute([$username, $cashierId]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT first_name, middle_name, last_name, suffix, username, contact_number, password FROM users_tb WHERE id = ? AND usertype = 2");
            $stmt->execute([$cashierId]);
            $existingCashier = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingCashier) {
                $errors['database'] = 'Cashier not found';
            } else {
                $existingMiddleName = $existingCashier['middle_name'] ?? '';
                $existingSuffix = $existingCashier['suffix'] ?? '';
                
                $noChanges = (
                    $firstName === $existingCashier['first_name'] &&
                    $middleName === $existingMiddleName &&
                    $lastName === $existingCashier['last_name'] &&
                    $suffix === $existingSuffix &&
                    $username === $existingCashier['username'] &&
                    $contactNumber === $existingCashier['contact_number'] &&
                    empty($password)
                );

                if ($noChanges) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'No changes were made to the cashier information'
                    ]);
                    exit;
                } else {
                    try {
                        $updatedAt = (new DateTime())->format('Y-m-d H:i:s');
                        if (!empty($password)) {
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            $stmt = $conn->prepare("UPDATE users_tb SET 
                                first_name = ?, middle_name = ?, last_name = ?, suffix = ?, 
                                username = ?, contact_number = ?, password = ?, updated_at = ? 
                                WHERE id = ? AND usertype = 2");
                            $stmt->execute([
                                $firstName,
                                $middleName,
                                $lastName,
                                $suffix,
                                $username,
                                $contactNumber,
                                $hashedPassword,
                                $updatedAt,
                                $cashierId
                            ]);
                        } else {
                            $stmt = $conn->prepare("UPDATE users_tb SET 
                                first_name = ?, middle_name = ?, last_name = ?, suffix = ?, 
                                username = ?, contact_number = ?, updated_at = ? 
                                WHERE id = ? AND usertype = 2");
                            $stmt->execute([
                                $firstName,
                                $middleName,
                                $lastName,
                                $suffix,
                                $username,
                                $contactNumber,
                                $updatedAt,
                                $cashierId
                            ]);
                        }
                        
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'message' => 'Cashier updated successfully'
                        ]);
                        exit;
                    } catch(PDOException $e) {
                        $errors['database'] = 'Update failed: ' . $e->getMessage();
                    }
                }
            }
        } catch(PDOException $e) {
            $errors['database'] = 'Failed to fetch cashier data: ' . $e->getMessage();
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

// Fetch cashiers for display
try {
    $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, suffix, created_at FROM users_tb WHERE usertype = 2 AND status = 1 ORDER BY created_at DESC");
    $stmt->execute();
    $cashiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $errors['database'] = 'Failed to fetch cashiers: ' . $e->getMessage();
}

// Fetch archived users
try {
    $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, suffix, username, created_at FROM users_tb WHERE usertype = 2 AND status = 0 ORDER BY created_at DESC");
    $stmt->execute();
    $archived_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $errors['database'] = 'Failed to fetch archived users: ' . $e->getMessage();
}

// Define page title and content
$page_title = "Employee Management";

ob_start();
?>
<div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 md:p-8 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <h3 class="text-3xl md:text-2xl font-bold text-deep-brown font-playfair">Cashier Management</h3>
        <div class="flex items-center space-x-4">
            <div class="w-64">
                <input type="text" id="cashier-search" class="w-full h-10 px-4 border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville text-gray-700 placeholder-gray-400 text-sm" placeholder="Search cashiers...">
            </div>
            <div class="flex space-x-3">
                <button id="view-archived-btn" class="group w-10 hover:w-40 h-10 bg-warm-cream/100 hover:from-rich-brown hover:to-deep-brown text-rich-brown rounded-lg transition-all duration-400 flex items-center justify-center overflow-hidden shadow-md hover:shadow-lg font-baskerville">
                    <i class="fas fa-archive text-base"></i>
                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 transition-all duration-100">View Archived</span>
                </button>
                <button id="create-cashier-btn" class="group w-10 hover:w-40 h-10 bg-warm-cream/100 hover:from-rich-brown hover:to-deep-brown text-rich-brown rounded-lg transition-all duration-400 flex items-center justify-center overflow-hidden shadow-md hover:shadow-lg font-baskerville">
                    <i class="fas fa-user-plus text-base"></i>
                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 transition-all duration-100">Add Cashier</span>
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full table-auto display nowrap">
            <thead>
                <tr>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">No.</th>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Name</th>
                    <th class="p-4 text-left font-semibold text-deep-brown font-playfair">Date Created</th>
                    <th class="p-4 text-center font-semibold text-deep-brown font-playfair w-32">Actions</th>
                </tr>
            </thead>
            <tbody id="cashier-table-body">
                <?php if (empty($cashiers)): ?>
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500 text-base font-baskerville">No cashier accounts found.</td>
                    </tr>
                <?php else: ?>
                    <?php $counter = 1; ?>
                    <?php foreach ($cashiers as $cashier): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="p-4 text-gray-700 text-sm font-baskerville"><?php echo $counter; ?></td>
                            <td class="p-4 text-gray-700 text-sm font-baskerville">
                                <?php
                                $fullName = htmlspecialchars($cashier['first_name']);
                                if (!empty($cashier['middle_name'])) {
                                    $fullName .= ' ' . htmlspecialchars($cashier['middle_name']);
                                }
                                $fullName .= ' ' . htmlspecialchars($cashier['last_name']);
                                if (!empty($cashier['suffix'])) {
                                    $fullName .= ' ' . htmlspecialchars($cashier['suffix']);
                                }
                                echo $fullName;
                                ?>
                            </td>
                            <td class="p-4 text-gray-700 text-sm font-baskerville">
                                <?php
                                $date = new DateTime($cashier['created_at'], new DateTimeZone('Asia/Manila'));
                                echo $date->format('F d, Y');
                                ?>
                            </td>
                            <td class="p-4 flex justify-center space-x-3">
                                <button class="group edit-btn w-8 hover:w-32 h-8 bg-warm-cream/80 text-rich-brown hover:text-deep-brown rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform" data-id="<?php echo htmlspecialchars($cashier['id']); ?>">
                                    <i class="fas fa-edit text-lg flex-shrink-0"></i>
                                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-300">Edit</span>
                                </button>
                                <button class="group archive-btn w-8 hover:w-32 h-8 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 hover:text-blue-500 rounded-full transition-all duration-300 ease-in-out flex items-center justify-center overflow-hidden transform" data-id="<?php echo htmlspecialchars($cashier['id']); ?>">
                                    <i class="fas fa-archive text-lg flex-shrink-0"></i>
                                    <span class="opacity-0 group-hover:opacity-100 w-0 group-hover:w-auto ml-0 group-hover:ml-2 whitespace-nowrap transition-all duration-300 ease-in-out delay-300">Archive</span>
                                </button>
                            </td>
                        </tr>
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Cashier Modal -->
<div id="create-cashier-modal" class="fixed inset-0 z-[100] flex items-start justify-center bg-black bg-opacity-60 transition-opacity duration-300 modal modal-hidden pt-16">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 overflow-y-auto max-h-[85vh]">
        <div class="flex justify-between items-center bg-amber-800 text-white p-5 rounded-t-xl">
            <h3 class="text-xl md:text-2xl font-semibold">Add New Cashier</h3>
            <button id="close-modal" class="text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form id="cashier-form" class="p-6 md:p-8" method="POST" action="">
            <input type="hidden" name="form_type" value="cashier">
            <?php if (!empty($errors) && isset($_POST['form_type']) && $_POST['form_type'] === 'cashier'): ?>
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
            
            <?php if ($registration_success): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong class="font-semibold">Success!</strong>
                    </div>
                    <span class="block mt-1">Cashier registration successful!</span>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="input-group">
                    <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" id="fname" name="fname" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="mname" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" id="mname" name="mname" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                </div>
                <div class="input-group">
                    <label for="lname" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" id="lname" name="lname" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                    <select id="suffix" name="suffix" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        <option value="">None</option>
                        <option value="Jr.">Jr.</option>
                        <option value="Sr.">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                </div>
                <div class="input-group col-span-2">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <p class="username-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-2">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <p class="phone-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                        <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-amber-700 toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="password-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="password-strength mt-2 flex space-x-1">
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                    <div class="relative">
                        <input type="password" id="confirm-password" name="confirm-password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                        <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-amber-700 toggle-password" data-target="confirm-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="confirm-password-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" id="cancel-btn" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-300 font-medium">
                    Cancel
                </button>
                <button type="submit" id="submit-btn" class="px-5 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-300 font-medium" disabled>
                    Add Cashier
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Cashier Modal -->
<div id="edit-cashier-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300 modal modal-hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 overflow-y-auto max-h-[85vh]">
        <div class="flex justify-between items-center bg-amber-800 text-white p-5 rounded-t-xl">
            <h3 class="text-xl md:text-2xl font-semibold">Edit Cashier</h3>
            <button id="close-edit-modal" class="text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form id="edit-cashier-form" class="p-6 md:p-8" method="POST" action="">
            <?php if (!empty($errors) && isset($errors['no_changes'])): ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong class="font-semibold">Notice!</strong>
                    </div>
                    <span class="block mt-1"><?php echo htmlspecialchars($errors['no_changes']); ?></span>
                </div>
            <?php endif; ?>
            
            <input type="hidden" name="form_type" value="edit_cashier">
            <input type="hidden" name="cashier_id" id="edit-cashier-id">
            <?php if (!empty($errors) && isset($_POST['form_type']) && $_POST['form_type'] === 'edit_cashier'): ?>
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
                    <span class="block mt-1">Cashier updated successfully!</span>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="input-group">
                    <label for="edit-fname" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" id="edit-fname" name="fname" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="edit-mname" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" id="edit-mname" name="mname" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                </div>
                <div class="input-group">
                    <label for="edit-lname" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" id="edit-lname" name="lname" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group">
                    <label for="edit-suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                    <select id="edit-suffix" name="suffix" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        <option value="">None</option>
                        <option value="Jr.">Jr.</option>
                        <option value="Sr.">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                </div>
                <div class="input-group col-span-2">
                    <label for="edit-username" class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" id="edit-username" name="username" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <p class="username-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-2">
                    <label for="edit-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" id="edit-phone" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" required>
                    <p class="phone-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-2">
                    <button type="button" id="toggle-password-btn" class="mb-4 px-5 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-300 font-medium">
                        Change Password?
                    </button>
                </div>
                <div class="input-group col-span-1 hidden" id="edit-password-group">
                    <label for="edit-password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="relative">
                        <input type="password" id="edit-password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-amber-700 toggle-password" data-target="edit-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="password-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="password-strength mt-2 flex space-x-1">
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1 hidden" id="edit-confirm-password-group">
                    <label for="edit-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" id="edit-confirm-password" name="confirm-password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-amber-700 toggle-password" data-target="edit-confirm-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="confirm-password-feedback mt-1 text-sm text-red-600 hidden"></p>
                    <div class="field-feedback mt-1 text-sm text-red-600 hidden"></div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" id="cancel-edit-btn" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-300 font-medium">
                    Cancel
                </button>
                <button type="submit" id="submit-edit-btn" class="px-5 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-300 font-medium">
                    Update Cashier
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Archived Accounts Modal -->
<div id="archived-accounts-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300 modal modal-hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 transform transition-all duration-300">
        <div class="flex justify-between items-center bg-amber-900 text-white p-5 rounded-t-xl">
            <h3 class="text-xl md:text-2xl font-semibold">Archived Accounts</h3>
            <button id="close-archived-modal" class="text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 md:p-8 max-h-[70vh] overflow-y-auto">
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="text-amber-800">
                        <tr>
                            <th class="py-4 px-6 text-left text-md font-bold">Name</th>
                            <th class="py-4 px-6 text-left text-md font-bold">Username</th>
                            <th class="py-4 px-6 text-left text-md font-bold">Date Created</th>
                            <th class="py-4 px-6 text-center text-md font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="archived-table-body">
                        <?php if (empty($archived_users)): ?>
                            <tr>
                                <td colspan="4" class="py-6 px-6 text-center text-gray-500 text-base">No archived accounts found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($archived_users as $user): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-4 px-6 text-gray-700 text-sm">
                                        <?php
                                        $fullName = htmlspecialchars($user['first_name']);
                                        if (!empty($user['middle_name'])) {
                                            $fullName .= ' ' . htmlspecialchars($user['middle_name']);
                                        }
                                        $fullName .= ' ' . htmlspecialchars($user['last_name']);
                                        if (!empty($user['suffix'])) {
                                            $fullName .= ' ' . htmlspecialchars($user['suffix']);
                                        }
                                        echo $fullName;
                                        ?>
                                    </td>
                                    <td class="py-4 px-6 text-gray-700 text-sm"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="py-4 px-6 text-gray-700 text-sm">
                                        <?php
                                        $date = new DateTime($user['created_at'], new DateTimeZone('Asia/Manila'));
                                        echo $date->format('F d, Y');
                                        ?>
                                    </td>
                                    <td class="py-4 px-6 flex justify-center">
                                        <button class="unarchive-btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center text-sm font-medium" data-id="<?php echo htmlspecialchars($user['id']); ?>">
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
    const sidebarTexts = document.querySelectorAll('.sidebar-text');

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

    // Cashier Management Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const createModal = document.getElementById('create-cashier-modal');
        const editModal = document.getElementById('edit-cashier-modal');
        const archivedModal = document.getElementById('archived-accounts-modal');
        const createBtn = document.getElementById('create-cashier-btn');
        const viewArchivedBtn = document.getElementById('view-archived-btn');
        const closeCreateModalBtn = document.getElementById('close-modal');
        const cancelCreateBtn = document.getElementById('cancel-btn');
        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const closeArchivedModalBtn = document.getElementById('close-archived-modal');
        const cancelArchivedBtn = document.getElementById('cancel-archived-btn');
        const createForm = document.getElementById('cashier-form');
        const editForm = document.getElementById('edit-cashier-form');
        const createInputs = createForm.querySelectorAll('input[required], select[required]');
        const editInputs = editForm.querySelectorAll('input[required], select[required]');
        const togglePasswordBtn = document.getElementById('toggle-password-btn');
        const passwordGroup = document.getElementById('edit-password-group');
        const confirmPasswordGroup = document.getElementById('edit-confirm-password-group');
        const passwordInput = document.getElementById('edit-password');
        const confirmPasswordInput = document.getElementById('edit-confirm-password');

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
                createInputs.forEach(input => validateField(input, createForm));
                checkFormValidity(createForm);
                updatePasswordStrength({ score: 0 });
                createForm.querySelectorAll('.username-feedback, .password-feedback, .confirm-password-feedback').forEach(fb => {
                    fb.classList.add('hidden');
                    fb.textContent = '';
                });
            }
            if (!modal.classList.contains('modal-hidden') && modal === editModal) {
                passwordInput.value = '';
                confirmPasswordInput.value = '';
                passwordGroup.classList.add('hidden');
                confirmPasswordGroup.classList.add('hidden');
                passwordInput.removeAttribute('required');
                confirmPasswordInput.removeAttribute('required');
                updatePasswordStrength({ score: 0 });
                editInputs.forEach(input => validateField(input, editForm));
                checkFormValidity(editForm);
                editForm.querySelectorAll('.username-feedback, .password-feedback, .confirm-password-feedback').forEach(fb => {
                    fb.classList.add('hidden');
                    fb.textContent = '';
                });
            }
        }

        togglePasswordBtn.addEventListener('click', () => {
            const isHidden = passwordGroup.classList.contains('hidden');
            passwordGroup.classList.toggle('hidden');
            confirmPasswordGroup.classList.toggle('hidden');
            if (isHidden) {
                passwordInput.setAttribute('required', '');
                confirmPasswordInput.setAttribute('required', '');
                togglePasswordBtn.textContent = 'Cancel Password Change';
                validateField(passwordInput, editForm);
                validateField(confirmPasswordInput, editForm);
            } else {
                passwordInput.removeAttribute('required');
                confirmPasswordInput.removeAttribute('required');
                passwordInput.value = '';
                confirmPasswordInput.value = '';
                passwordInput.classList.remove('field-error', 'field-success');
                confirmPasswordInput.classList.remove('field-error', 'field-success');
                const feedbacks = editForm.querySelectorAll('.password-feedback, .confirm-password-feedback');
                feedbacks.forEach(fb => {
                    fb.classList.add('hidden');
                    fb.textContent = '';
                });
                togglePasswordBtn.textContent = 'Change Password?';
            }
            checkFormValidity(editForm);
        });

        createBtn.addEventListener('click', () => toggleModal(createModal));
        closeCreateModalBtn.addEventListener('click', () => toggleModal(createModal));
        cancelCreateBtn.addEventListener('click', () => toggleModal(createModal));
        closeEditModalBtn.addEventListener('click', () => toggleModal(editModal));
        cancelEditBtn.addEventListener('click', () => toggleModal(editModal));
        viewArchivedBtn.addEventListener('click', () => toggleModal(archivedModal));
        closeArchivedModalBtn.addEventListener('click', () => toggleModal(archivedModal));
        cancelArchivedBtn.addEventListener('click', () => toggleModal(archivedModal));
        
        archivedModal.addEventListener('click', function(e) {
            if (e.target === archivedModal) {
                toggleModal(archivedModal);
            }
        });

        function checkUsernameAvailability(username, form, cashierId = null) {
            const feedback = form.querySelector('.username-feedback');
            const input = form.querySelector('[name="username"]');
            if (username.length >= 3 && /^[A-Za-z0-9_]+$/.test(username)) {
                const formData = new FormData();
                formData.append('action', 'check_username');
                formData.append('username', username);
                if (cashierId) {
                    formData.append('cashier_id', cashierId);
                }
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
                    if (data.exists) {
                        input.classList.remove('field-success');
                        input.classList.add('field-error');
                        feedback.classList.add('text-red-600');
                        feedback.textContent = data.message;
                    } else {
                        input.classList.remove('field-error');
                        input.classList.add('field-success');
                        feedback.classList.add('text-green-600');
                        feedback.textContent = data.message;
                    }
                    checkFormValidity(form);
                })
                .catch(error => {
                    feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
                    feedback.classList.add('text-red-600');
                    feedback.textContent = 'Error checking username';
                    input.classList.add('field-error');
                    checkFormValidity(form);
                });
            } else {
                feedback.classList.add('hidden');
                feedback.textContent = '';
                validateField(input, form);
            }
        }
        
        function checkPhoneAvailability(phone, form, cashierId = null) {
            const feedback = form.querySelector('.phone-feedback');
            const input = form.querySelector('[name="phone"]');
            const cleanNumber = phone.replace(/\D/g, '');
            if (cleanNumber.length < 11) {
                feedback.classList.add('hidden');
                feedback.textContent = '';
                validateField(input, form);
                return;
            }
            if (cleanNumber.length === 11 && /^09\d{9}$/.test(cleanNumber)) {
                const formData = new FormData();
                formData.append('action', 'check_phone');
                formData.append('phone', cleanNumber);
                if (cashierId) {
                    formData.append('cashier_id', cashierId);
                }
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
                    if (data.exists) {
                        input.classList.remove('field-success');
                        input.classList.add('field-error');
                        feedback.classList.add('text-red-600');
                        feedback.textContent = data.message;
                    } else {
                        input.classList.remove('field-error');
                        input.classList.add('field-success');
                        feedback.classList.add('text-green-600');
                        feedback.textContent = data.message;
                    }
                    checkFormValidity(form);
                })
                .catch(error => {
                    feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
                    feedback.classList.add('text-red-600');
                    feedback.textContent = 'Error checking phone number';
                    input.classList.add('field-error');
                    checkFormValidity(form);
                });
            } else {
                feedback.classList.add('hidden');
                feedback.textContent = '';
                validateField(input, form);
            }
        }

        function validateField(field, form) {
            const inputGroup = field.closest('.input-group');
            const feedback = inputGroup?.querySelector('.field-feedback');
            let isValid = true;
            let message = '';

            field.classList.remove('field-error', 'field-success');
            feedback?.classList.add('hidden');

            switch (field.name) {
                case 'fname':
                case 'lname':
                    if (!field.value.trim()) {
                        isValid = false;
                        message = `${field.name === 'fname' ? 'First' : 'Last'} name is required`;
                    } else if (field.value.trim().length < 2) {
                        isValid = false;
                        message = 'Name must be at least 2 characters';
                    } else if (!/^[A-Za-z\s]+$/.test(field.value.trim())) {
                        isValid = false;
                        message = 'Name can only contain letters and spaces';
                    }
                    break;

                case 'username':
                    if (!field.value.trim()) {
                        isValid = false;
                        message = 'Username is required';
                    } else if (field.value.trim().length < 3) {
                        isValid = false;
                        message = 'Username must be at least 3 characters';
                    } else if (!/^[A-Za-z0-9_]+$/.test(field.value.trim())) {
                        isValid = false;
                        message = 'Username can only contain letters, numbers, and underscores';
                    }
                    break;

                case 'phone':
                    const cleanNumber = field.value.replace(/\D/g, '');
                    if (!cleanNumber) {
                        isValid = false;
                        message = 'Contact number is required';
                    } else if (cleanNumber.length !== 11) {
                        isValid = false;
                        message = 'Contact number must be 11 digits';
                    } else if (!/^09\d{9}$/.test(cleanNumber)) {
                        isValid = false;
                        message = 'Please enter a valid Philippine mobile number (09XXXXXXXXX)';
                    }
                    break;

                case 'password':
                    if (field.hasAttribute('required')) {
                        const strength = checkPasswordStrength(field.value);
                        updatePasswordStrength(strength);
                        const feedback = form.querySelector('.password-feedback');
                        feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
                        if (!field.value.trim()) {
                            isValid = false;
                            message = 'Password is required';
                            feedback.classList.add('text-red-600');
                            feedback.textContent = message;
                        } else if (field.value.length < 8) {
                            isValid = false;
                            message = 'Password must be at least 8 characters';
                            feedback.classList.add('text-red-600');
                            feedback.textContent = message;
                        } else {
                            feedback.classList.add('text-green-600');
                            feedback.textContent = 'Password is valid';
                        }
                    }
                    break;

                case 'confirm-password':
                    if (field.hasAttribute('required')) {
                        const password = form.querySelector('#' + (form.id === 'edit-cashier-form' ? 'edit-password' : 'password')).value;
                        const feedback = form.querySelector('.confirm-password-feedback');
                        feedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
                        if (!field.value.trim()) {
                            isValid = false;
                            message = 'Confirm password is required';
                            feedback.classList.add('text-red-600');
                            feedback.textContent = message;
                        } else if (field.value !== password) {
                            isValid = false;
                            message = 'Passwords do not match';
                            feedback.classList.add('text-red-600');
                            feedback.textContent = message;
                        } else {
                            feedback.classList.add('text-green-600');
                            feedback.textContent = 'Passwords match';
                        }
                    }
                    break;
            }

            if (field.value.trim() !== '' && field.name !== 'username' && field.name !== 'password' && field.name !== 'confirm-password') {
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

        function checkPasswordStrength(password) {
            let score = 0;
            const checks = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            Object.values(checks).forEach(check => {
                if (check) score++;
            });

            return { score, checks };
        }

        function updatePasswordStrength(strength) {
            const strengthBars = document.querySelectorAll('.password-strength > div');
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            
            strengthBars.forEach((bar, index) => {
                bar.className = 'h-1 flex-1 rounded';
                if (index < strength.score) {
                    bar.classList.add(colors[Math.min(strength.score - 1, 3)]);
                } else {
                    bar.classList.add('bg-gray-200');
                }
            });
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
            
            // Check username availability
            const usernameFeedback = form.querySelector('.username-feedback');
            if (usernameFeedback && usernameFeedback.classList.contains('text-red-600')) {
                isValid = false;
            }
            
            // Check phone number availability
            const phoneFeedback = form.querySelector('.phone-feedback');
            if (phoneFeedback && phoneFeedback.classList.contains('text-red-600')) {
                isValid = false;
            }
            
            submitBtn.disabled = !isValid;
        }

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        // Form submission
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            checkFormValidity(createForm);
            
            const errorFields = createForm.querySelectorAll('.field-error');
            const usernameFeedback = createForm.querySelector('.username-feedback');
            if (errorFields.length > 0 || (usernameFeedback && usernameFeedback.classList.contains('text-red-600'))) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fix all errors before submitting.',
                    icon: 'error',
                    confirmButtonColor: '#8B4513'
                });
                return;
            }
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to create this cashier account?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B4513',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, create it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(createForm);
                    fetch('', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Cashier account created successfully!',
                                icon: 'success',
                                confirmButtonColor: '#8B4513'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to create cashier account.',
                                icon: 'error',
                                confirmButtonColor: '#8B4513'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + error.message,
                            icon: 'error',
                            confirmButtonColor: '#8B4513'
                        });
                    });
                }
            });
        });

        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            checkFormValidity(editForm);
            
            const errorFields = editForm.querySelectorAll('.field-error');
            const usernameFeedback = editForm.querySelector('.username-feedback');
            if (errorFields.length > 0 || (usernameFeedback && usernameFeedback.classList.contains('text-red-600'))) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fix all errors before submitting.',
                    icon: 'error',
                    confirmButtonColor: '#8B4513'
                });
                return;
            }
            
            Swal.fire({
                title: 'Confirm Changes',
                text: 'Are you sure you want to save the changes to this cashier account?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B4513',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, save changes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(editForm);
                    fetch('', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#8B4513'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: data.message.includes('No changes') ? 'No Changes!' : 'Error!',
                                text: data.message,
                                icon: data.message.includes('No changes') ? 'warning' : 'error',
                                confirmButtonColor: '#8B4513'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + error.message,
                            icon: 'error',
                            confirmButtonColor: '#8B4513'
                        });
                    });
                }
            });
        });

        // Input validation and handling
        function setupInputValidation(inputs, form) {
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    validateField(input, form);
                    checkFormValidity(form);
                    if (input.name === 'username') {
                        const cashierId = form.id === 'edit-cashier-form' ? document.getElementById('edit-cashier-id').value : null;
                        checkUsernameAvailability(input.value, form, cashierId);
                    }
                    if (input.name === 'phone') {
                        const cashierId = form.id === 'edit-cashier-form' ? document.getElementById('edit-cashier-id').value : null;
                        checkPhoneAvailability(input.value, form, cashierId);
                    }
                });
                input.addEventListener('blur', () => {
                    validateField(input, form);
                    if (input.name === 'username') {
                        const cashierId = form.id === 'edit-cashier-form' ? document.getElementById('edit-cashier-id').value : null;
                        checkUsernameAvailability(input.value, form, cashierId);
                    }
                    if (input.name === 'phone') {
                        const cashierId = form.id === 'edit-cashier-form' ? document.getElementById('edit-cashier-id').value : null;
                        checkPhoneAvailability(input.value, form, cashierId);
                    }
                });
            });

            const phoneInput = form.querySelector('[name="phone"]');
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                e.target.value = value;
                validateField(e.target, form);
                checkFormValidity(form);
            });

            ['fname', 'lname', 'mname'].forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', function(e) {
                        let value = e.target.value;
                        if (value.length > 0) {
                            value = value.charAt(0).toUpperCase() + value.slice(1);
                        }
                        value = value.replace(/\s+/g, ' ').replace(/[^A-Za-z\s]/g, '');
                        if (value.startsWith(' ')) {
                            value = value.trimStart();
                        }
                        if (value.length < 2 && value.includes(' ')) {
                            value = value.replace(/\s/g, '');
                        }
                        e.target.value = value;
                        validateField(e.target, form);
                        checkFormValidity(form);
                    });

                    field.addEventListener('paste', function(e) {
                        e.preventDefault();
                        let pastedText = (e.clipboardData || window.clipboardData).getData('text');
                        pastedText = pastedText.replace(/[^A-Za-z\s]/g, '').replace(/\s+/g, ' ').trim();
                        if (pastedText.length > 0) {
                            pastedText = pastedText.charAt(0).toUpperCase() + pastedText.slice(1);
                        }
                        if (this.value.length < 2) {
                            pastedText = pastedText.replace(/\s/g, '');
                        }
                        this.value = pastedText;
                        validateField(this, form);
                        checkFormValidity(form);
                    });
                }
            });

            const usernameInput = form.querySelector('[name="username"]');
            usernameInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^A-Za-z0-9_]/g, '');
                validateField(e.target, form);
                checkFormValidity(form);
                const cashierId = form.id === 'edit-cashier-form' ? document.getElementById('edit-cashier-id').value : null;
                checkUsernameAvailability(e.target.value, form, cashierId);
            });

            const passwordInput = form.querySelector('[name="password"]');
            const confirmPasswordInput = form.querySelector('[name="confirm-password"]');
            if (passwordInput) {
                passwordInput.addEventListener('input', () => {
                    validateField(passwordInput, form);
                    checkFormValidity(form);
                    const strength = checkPasswordStrength(passwordInput.value);
                    updatePasswordStrength(strength);
                });
            }
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', () => {
                    validateField(confirmPasswordInput, form);
                    checkFormValidity(form);
                });
            }
        }

        // Initialize input validation for both forms
        initFloatingLabels(createForm, createInputs);
        initFloatingLabels(editForm, editInputs);
        setupInputValidation(createInputs, createForm);
        setupInputValidation(editInputs, editForm);

        // Add event listeners to edit, archive, and unarchive buttons
        function addButtonListeners() {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cashierId = this.getAttribute('data-id');
                    fetch(`cashierAccountManagement/get_cashier.php?id=${encodeURIComponent(cashierId)}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('edit-cashier-id').value = cashierId;
                            document.getElementById('edit-fname').value = data.cashier.first_name || '';
                            document.getElementById('edit-mname').value = data.cashier.middle_name || '';
                            document.getElementById('edit-lname').value = data.cashier.last_name || '';
                            document.getElementById('edit-suffix').value = data.cashier.suffix || '';
                            document.getElementById('edit-username').value = data.cashier.username || '';
                            document.getElementById('edit-phone').value = data.cashier.contact_number || '';
                            
                            // Update floating labels and validate
                            editInputs.forEach(input => {
                                const inputGroup = input.closest('.input-group');
                                if (input.value.trim() !== '') {
                                    inputGroup.classList.add('has-content');
                                } else {
                                    inputGroup.classList.remove('has-content');
                                }
                                validateField(input, editForm);
                            });
                            // Trigger username availability check
                            checkUsernameAvailability(data.cashier.username, editForm, cashierId);
                            checkFormValidity(editForm);
                            toggleModal(editModal);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#8B4513'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + error.message,
                            icon: 'error',
                            confirmButtonColor: '#8B4513'
                        });
                    });
                });
            });
            
            document.querySelectorAll('.archive-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cashierId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to archive this cashier account?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8B4513',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, archive it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('cashierAccountManagement/archive_cashier.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `id=${encodeURIComponent(cashierId)}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Archived!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#8B4513'
                                    }).then(() => {
                                        this.closest('tr').remove();
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message,
                                        icon: 'error',
                                        confirmButtonColor: '#8B4513'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred: ' + error.message,
                                    icon: 'error',
                                    confirmButtonColor: '#8B4513'
                                });
                            });
                        }
                    });
                });
            });

            document.querySelectorAll('.unarchive-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to unarchive this customer account?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#8B4513',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, unarchive it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('cashierAccountManagement/unarchive_user.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `id=${encodeURIComponent(userId)}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Unarchived!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#8B4513'
                                    }).then(() => {
                                        this.closest('tr').remove();
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message,
                                        icon: 'error',
                                        confirmButtonColor: '#8B4513'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred: ' + error.message,
                                    icon: 'error',
                                    confirmButtonColor: '#8B4513'
                                });
                            });
                        }
                    });
                });
            });
        }
        
        addButtonListeners();
    });
    </script>
<?php
ob_end();

?>