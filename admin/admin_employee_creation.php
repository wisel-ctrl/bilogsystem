<?php
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

$registration_success = false;
$update_success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'cashier') {
    // Get form data
    $firstName = trim($_POST['fname'] ?? '');
    $middleName = trim($_POST['mname'] ?? '');
    $lastName = trim($_POST['lname'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contactNumber = str_replace('-', '', trim($_POST['phone'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    
    // Basic validation
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

    // Check if username already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }

    // If no errors, register cashier
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $usertype = 2; // Cashier account
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
            
            $registration_success = true;
        } catch(PDOException $e) {
            $errors['database'] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

// Handle cashier update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'edit_cashier') {
    $cashierId = trim($_POST['cashier_id'] ?? '');
    $firstName = trim($_POST['fname'] ?? '');
    $middleName = trim($_POST['mname'] ?? '');
    $lastName = trim($_POST['lname'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contactNumber = str_replace('-', '', trim($_POST['phone'] ?? ''));
    
    // Validation
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

    // Check if username already exists for another user
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ? AND id != ?");
        $stmt->execute([$username, $cashierId]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }

    // If no errors, update cashier
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE users_tb SET 
                first_name = ?, middle_name = ?, last_name = ?, suffix = ?, 
                username = ?, contact_number = ?, updated_at = ? 
                WHERE id = ? AND usertype = 2");
            
            $updatedAt = (new DateTime())->format('Y-m-d H:i:s');
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
            
            $update_success = true;
        } catch(PDOException $e) {
            $errors['database'] = 'Update failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
        
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .modal-hidden {
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
        }
        
        .field-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        
        .field-success {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .floating-label {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        .input-group.has-content input,
        .input-group.has-content select {
            background: rgba(255, 255, 255, 0.9);
            border-color: #8B4513;
        }
        
        .input-group input:focus,
        .input-group select:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }
        
        .input-group input::placeholder {
            color: rgba(93, 47, 15, 0.6);
            opacity: 1;
        }
        
        .input-group input:focus::placeholder {
            color: transparent;
        }
        
        .password-strength {
            display: flex;
            space-x: 1px;
            margin-bottom: 0.25rem;
        }
        
        .password-strength div {
            height: 0.25rem;
            flex: 1;
            border-radius: 0.125rem;
        }
    </style>
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
                    <li><a href="admin_dashboard.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream transition-colors duration-200"><i class="fas fa-chart-pie w-5"></i><span class="sidebar-text">Dashboard</span></a></li>
                    <li><a href="admin_bookings.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200"><i class="fas fa-calendar-check w-5"></i><span class="sidebar-text">Booking Requests</span></a></li>
                    <li><a href="admin_menu.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200"><i class="fas fa-utensils w-5"></i><span class="sidebar-text">Menu Management</span></a></li>
                    <li><a href="admin_inventory.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200"><i class="fas fa-boxes w-5"></i><span class="sidebar-text">Inventory</span></a></li>
                    <li><a href="admin_expenses.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200"><i class="fas fa-receipt w-5"></i><span class="sidebar-text">Expenses</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-accent-brown text-warm-cream/80 hover:text-warm-cream transition-colors duration-200"><i class="fas fa-user-plus w-5"></i><span class="sidebar-text">Employee Creation</span></a></li>
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
                        <h2 class="text-2xl font-bold text-deep-brown font-script">Employee Management</h2>
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
                        <h3 class="text-xl font-bold text-deep-brown">Cashier Management</h3>
                        <div class="flex space-x-2">
                            <button id="view-archived-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-archive mr-2"></i> View Archived
                            </button>
                            <button id="create-cashier-btn" class="bg-accent-brown hover:bg-deep-brown text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-user-plus mr-2"></i> Create Cashier
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-rich-brown text-warm-cream">
                                <tr>
                                    <th class="py-3 px-4 text-left">Name</th>
                                    <th class="py-3 px-4 text-left">Date Created</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, suffix, created_at FROM users_tb WHERE usertype = 2 AND status = 1 ORDER BY created_at DESC");
                                $stmt->execute();
                                $cashiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            } catch(PDOException $e) {
                                $errors['database'] = 'Failed to fetch cashiers: ' . $e->getMessage();
                            }
                            ?>
                            
                            <tbody class="divide-y divide-gray-200" id="cashier-table-body">
                                <?php if (empty($cashiers)): ?>
                                    <tr>
                                        <td colspan="3" class="py-3 px-4 text-center text-gray-500">No cashier accounts found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cashiers as $cashier): ?>
                                        <tr>
                                            <td class="py-3 px-4">
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
                                            <td class="py-3 px-4">
                                                <?php
                                                $date = new DateTime($cashier['created_at'], new DateTimeZone('Asia/Manila'));
                                                echo $date->format('F d, Y');
                                                ?>
                                            </td>
                                            <td class="py-3 px-4 flex justify-center space-x-2">
                                                <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200" data-id="<?php echo htmlspecialchars($cashier['id']); ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="archive-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition-colors duration-200" data-id="<?php echo htmlspecialchars($cashier['id']); ?>">
                                                    <i class="fas fa-archive"></i> Archive
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Create Cashier Modal -->
    <div id="create-cashier-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center border-b p-4 bg-rich-brown text-warm-cream rounded-t-lg">
                <h3 class="text-lg font-bold">Create New Cashier</h3>
                <button id="close-modal" class="text-warm-cream hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="cashier-form" class="p-6" method="POST" action="">
                <input type="hidden" name="form_type" value="cashier">
                <?php if (!empty($errors) && isset($_POST['form_type']) && $_POST['form_type'] === 'cashier'): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">Please fix the following issues:</span>
                        <ul class="mt-2 list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ($registration_success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">Cashier registration successful!</span>
                    </div>
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="input-group col-span-1">
                        <label for="fname" class="floating-label">First Name *</label>
                        <input type="text" id="fname" name="fname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="mname" class="floating-label">Middle Name</label>
                        <input type="text" id="mname" name="mname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                    </div>
                    <div class="input-group col-span-1">
                        <label for="lname" class="floating-label">Last Name *</label>
                        <input type="text" id="lname" name="lname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="suffix" class="floating-label">Suffix</label>
                        <select id="suffix" name="suffix" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                            <option value="">None</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                    <div class="input-group col-span-2">
                        <label for="username" class="floating-label">Username *</label>
                        <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-2">
                        <label for="phone" class="floating-label">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="password" class="floating-label">Password *</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength mt-2 flex space-x-1">
                            <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                            <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                            <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                            <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        </div>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="confirm-password" class="floating-label">Confirm Password *</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="cancel-btn" class="px-4 py-2 border border-gray-300 rounded-md text-deep-brown hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-accent-brown text-white rounded-md hover:bg-deep-brown transition-colors duration-200" disabled>
                        Create Cashier
                    </button>
                </div>
            </form>
        </div>
    </div>

<!-- Edit Cashier Modal -->
<div id="edit-cashier-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b p-4 bg-rich-brown text-warm-cream rounded-t-lg">
            <h3 class="text-lg font-bold">Edit Cashier</h3>
            <button id="close-edit-modal" class="text-warm-cream hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="edit-cashier-form" class="p-6" method="POST" action="">
            <input type="hidden" name="form_type" value="edit_cashier">
            <input type="hidden" name="cashier_id" id="edit-cashier-id">
            <?php if (!empty($errors) && isset($_POST['form_type']) && $_POST['form_type'] === 'edit_cashier'): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">Please fix the following issues:</span>
                    <ul class="mt-2 list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($update_success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">Cashier updated successfully!</span>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="input-group col-span-1">
                    <label for="edit-fname" class="floating-label">First Name *</label>
                    <input type="text" id="edit-fname" name="fname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1">
                    <label for="edit-mname" class="floating-label">Middle Name</label>
                    <input type="text" id="edit-mname" name="mname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                </div>
                <div class="input-group col-span-1">
                    <label for="edit-lname" class="floating-label">Last Name *</label>
                    <input type="text" id="edit-lname" name="lname" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1">
                    <label for="edit-suffix" class="floating-label">Suffix</label>
                    <select id="edit-suffix" name="suffix" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                        <option value="">None</option>
                        <option value="Jr.">Jr.</option>
                        <option value="Sr.">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                </div>
                <div class="input-group col-span-2">
                    <label for="edit-username" class="floating-label">Username *</label>
                    <input type="text" id="edit-username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-2">
                    <label for="edit-phone" class="floating-label">Phone Number *</label>
                    <input type="tel" id="edit-phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown" required>
                    <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1">
                    <label for="edit-password" class="floating-label">New Password</label>
                    <div class="relative">
                        <input type="password" id="edit-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                        <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="edit-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength mt-2 flex space-x-1">
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                        <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                </div>
                <div class="input-group col-span-1">
                    <label for="edit-confirm-password" class="floating-label">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" id="edit-confirm-password" name="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent-brown">
                        <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="edit-confirm-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancel-edit-btn" class="px-4 py-2 border border-gray-300 rounded-md text-deep-brown hover:bg-gray-100 transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit" id="submit-edit-btn" class="px-4 py-2 bg-accent-brown text-white rounded-md hover:bg-deep-brown transition-colors duration-200">
                    Update Cashier
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Archived Accounts Modal -->
    <div id="archived-accounts-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4">
            <div class="flex justify-between items-center border-b p-4 bg-rich-brown text-warm-cream rounded-t-lg">
                <h3 class="text-lg font-bold">Archived Accounts</h3>
                <button id="close-archived-modal" class="text-warm-cream hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-rich-brown text-warm-cream">
                            <tr>
                                <th class="py-3 px-4 text-left">Name</th>
                                <th class="py-3 px-4 text-left">Username</th>
                                <th class="py-3 px-4 text-left">Date Created</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="archived-table-body">
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, suffix, username, created_at FROM users_tb WHERE usertype = 2 AND status = 0 ORDER BY created_at DESC");
                                $stmt->execute();
                                $archived_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            } catch(PDOException $e) {
                                $errors['database'] = 'Failed to fetch archived users: ' . $e->getMessage();
                            }
                            ?>
                            <?php if (empty($archived_users)): ?>
                                <tr>
                                    <td colspan="4" class="py-3 px-4 text-center text-gray-500">No archived accounts found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($archived_users as $user): ?>
                                    <tr>
                                        <td class="py-3 px-4">
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
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td class="py-3 px-4">
                                            <?php
                                            $date = new DateTime($user['created_at'], new DateTimeZone('Asia/Manila'));
                                            echo $date->format('F d, Y');
                                            ?>
                                        </td>
                                        <td class="py-3 px-4 flex justify-center">
                                            <button class="unarchive-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition-colors duration-200" data-id="<?php echo htmlspecialchars($user['id']); ?>">
                                                <i class="fas fa-undo"></i> Unarchive
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex justify-end p-4 border-t border-gray-200">
                <button id="cancel-archived-btn" class="px-4 py-2 border border-gray-300 rounded-md text-deep-brown hover:bg-gray-100 transition-colors duration-200">
                    Close
                </button>
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
}, { threshold: 0.1 });
animateElements.forEach(element => observer.observe(element));

// Cashier Management Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
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

    // Initialize floating labels
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

    // Toggle modal visibility
    function toggleModal(modal) {
        modal.classList.toggle('modal-hidden');
        if (!modal.classList.contains('modal-hidden') && modal === createModal) {
            createForm.reset();
            createInputs.forEach(input => validateField(input, createForm));
            checkFormValidity(createForm);
            updatePasswordStrength({ score: 0 });
        }
        if (!modal.classList.contains('modal-hidden') && modal === editModal) {
            editInputs.forEach(input => validateField(input, editForm));
            checkFormValidity(editForm);
        }
    }

    // Event listeners for create cashier modal
    createBtn.addEventListener('click', () => toggleModal(createModal));
    closeCreateModalBtn.addEventListener('click', () => toggleModal(createModal));
    cancelCreateBtn.addEventListener('click', () => toggleModal(createModal));
    
    createModal.addEventListener('click', function(e) {
        if (e.target === createModal) {
            toggleModal(createModal);
        }
    });

    // Event listeners for edit cashier modal
    closeEditModalBtn.addEventListener('click', () => toggleModal(editModal));
    cancelEditBtn.addEventListener('click', () => toggleModal(editModal));
    
    editModal.addEventListener('click', function(e) {
        if (e.target === editModal) {
            toggleModal(editModal);
        }
    });

    // Event listeners for archived accounts modal
    viewArchivedBtn.addEventListener('click', () => toggleModal(archivedModal));
    closeArchivedModalBtn.addEventListener('click', () => toggleModal(archivedModal));
    cancelArchivedBtn.addEventListener('click', () => toggleModal(archivedModal));
    
    archivedModal.addEventListener('click', function(e) {
        if (e.target === archivedModal) {
            toggleModal(archivedModal);
        }
    });

    // Validation functions
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
                const strength = checkPasswordStrength(field.value);
                updatePasswordStrength(strength);
                if (!field.value.trim()) {
                    isValid = false;
                    message = 'Password is required';
                } else if (field.value.length < 8) {
                    isValid = false;
                    message = 'Password must be at least 8 characters';
                } else if (strength.score < 3) {
                    isValid = false;
                    message = 'Password is too weak. Include uppercase, lowercase, number, and special character';
                }
                break;

            case 'confirm-password':
                const password = document.getElementById('password').value;
                if (!field.value.trim()) {
                    isValid = false;
                    message = 'Confirm password is required';
                } else if (field.value !== password) {
                    isValid = false;
                    message = 'Passwords do not match';
                }
                break;
        }

        if (field.value.trim() !== '') {
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
        const inputs = form.querySelectorAll('input[required], select[required]');
        const submitBtn = form.querySelector('[type="submit"]');
        let isValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                isValid = false;
            }
            if (!validateField(input, form)) {
                isValid = false;
            }
        });

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
        if (errorFields.length > 0) {
            alert('Please fix all errors before submitting.');
            return;
        }
        
        const formData = new FormData(createForm);
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    });

    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        checkFormValidity(editForm);
        
        const errorFields = editForm.querySelectorAll('.field-error');
        if (errorFields.length > 0) {
            alert('Please fix all errors before submitting.');
            return;
        }
        
        const formData = new FormData(editForm);
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    });

    // Input validation and handling
    function setupInputValidation(inputs, form) {
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                validateField(input, form);
                checkFormValidity(form);
            });
            input.addEventListener('blur', () => {
                validateField(input, form);
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
        });
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
                        
                        // Update floating labels
                        editInputs.forEach(input => {
                            const inputGroup = input.closest('.input-group');
                            if (input.value.trim() !== '') {
                                inputGroup.classList.add('has-content');
                            } else {
                                inputGroup.classList.remove('has-content');
                            }
                            validateField(input, editForm);
                        });
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
</body>
</html>