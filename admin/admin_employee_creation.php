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
                // For edit form, exclude the current user's ID
                $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ? AND id != ?");
                $stmt->execute([$username, $cashierId]);
            } else {
                // For create form
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
    }
    // Add this new block for phone number check
    elseif ($_POST['action'] === 'check_phone') {
        $phone = trim($_POST['phone'] ?? '');
        $phone = str_replace('-', '', $phone); // Clean the phone number
        $cashierId = isset($_POST['cashier_id']) ? trim($_POST['cashier_id']) : null;
        
        try {
            if ($cashierId) {
                // For edit form, exclude the current user's ID
                $stmt = $conn->prepare("SELECT id FROM users_tb WHERE contact_number = ? AND id != ?");
                $stmt->execute([$phone, $cashierId]);
            } else {
                // For create form
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

// Handle real-time username check
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'check_username') {
    $username = trim($_POST['username'] ?? '');
    $cashierId = isset($_POST['cashier_id']) ? trim($_POST['cashier_id']) : null;
    
    try {
        if ($cashierId) {
            // For edit form, exclude the current user's ID
            $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ? AND id != ?");
            $stmt->execute([$username, $cashierId]);
        } else {
            // For create form
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
}

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

// Handle cashier update
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
    // Validate password only if provided
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif ($password !== $confirmPassword) {
            $errors['password'] = 'Passwords do not match';
        }
    }

    // Check if username already exists for another user
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users_tb WHERE username = ? AND id != ?");
        $stmt->execute([$username, $cashierId]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }

    // Fetch existing cashier data to compare
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT first_name, middle_name, last_name, suffix, username, contact_number, password FROM users_tb WHERE id = ? AND usertype = 2");
            $stmt->execute([$cashierId]);
            $existingCashier = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingCashier) {
                $errors['database'] = 'Cashier not found';
            } else {
                // Normalize data for comparison
                $existingMiddleName = $existingCashier['middle_name'] ?? '';
                $existingSuffix = $existingCashier['suffix'] ?? '';
                
                // Compare form data with existing data
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
                    // Return JSON response indicating no changes
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'No changes were made to the cashier information'
                    ]);
                    exit;
                } else {
                    // Proceed with update if there are changes
                    try {
                        $updatedAt = (new DateTime())->format('Y-m-d H:i:s');
                        if (!empty($password)) {
                            error_log("Updating with password");
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
                            error_log("Updating without password");
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
                        
                        // Return JSON response indicating success
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

    // If there are errors, return them as JSON
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
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
        
        /* Sidebar improvements */
        .sidebar-link {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #E8E0D5;
            transition: width 0.3s ease;
        }
        
        .sidebar-link:hover::after {
            width: 100%;
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

        /* Modal styling */
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
<body class="bg-warm-cream/50 font-baskerville">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gradient-to-b from-deep-brown via-rich-brown to-accent-brown text-warm-cream transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
            <div class="p-6 border-b border-warm-cream/20">
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
                        <a href="admin_bookings.html" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
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
                        <a href="admin_expenses.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="sidebar-text font-baskerville">Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-warm-cream/10 text-warm-cream hover:bg-warm-cream/20 transition-all duration-200">
                            <i class="fas fa-user-plus w-5"></i>
                            <span class="sidebar-text font-baskerville">Employee Creation</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[100]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-deep-brown hover:text-rich-brown transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-deep-brown font-playfair">Employee Management</h2>
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
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 relative z-0">
                <!-- Employee Management Section -->
                <div class="dashboard-card fade-in bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-deep-brown font-playfair">Employee List</h3>
                        <div class="flex items-center space-x-4">
                            <button id="show-create-modal" class="w-52 h-10 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus"></i>
                                <span class="font-baskerville">Add Employee</span>
                            </button>
                        </div>
                    </div>

                    <!-- Employee Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-rich-brown text-warm-cream">
                                <tr>
                                    <th class="py-3 px-4 text-left font-playfair">Name</th>
                                    <th class="py-3 px-4 text-left font-playfair">Date Created</th>
                                    <th class="py-3 px-4 text-center font-playfair">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, suffix, created_at FROM users_tb WHERE usertype = 2 AND status = 1 ORDER BY created_at DESC");
                                    $stmt->execute();
                                    $cashiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch(PDOException $e) {
                                    $errors['database'] = 'Failed to fetch cashiers: ' . $e->getMessage();
                                }
                                ?>
                                <?php if (empty($cashiers)): ?>
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-gray-500 font-baskerville">No employees found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cashiers as $cashier): ?>
                                        <tr class="border-b border-gray-200 hover:bg-warm-cream/5 transition-colors duration-150">
                                            <td class="py-3 px-4 font-baskerville">
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
                                            <td class="py-3 px-4 font-baskerville">
                                                <?php
                                                $date = new DateTime($cashier['created_at'], new DateTimeZone('Asia/Manila'));
                                                echo $date->format('F d, Y');
                                                ?>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <button onclick="editCashier(<?php echo $cashier['id']; ?>)" class="text-accent-brown hover:text-deep-brown transition-colors duration-200 mx-2">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteCashier(<?php echo $cashier['id']; ?>)" class="text-red-500 hover:text-red-700 transition-colors duration-200 mx-2">
                                                    <i class="fas fa-trash-alt"></i>
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
    <div id="create-cashier-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-4">
        <div class="dashboard-card rounded-lg max-w-2xl w-full modal-container">
            <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Create New Employee</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="cashier-form" class="modal-body px-6 py-4 space-y-6" method="POST" action="">
                <input type="hidden" name="form_type" value="cashier">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="input-group col-span-1">
                        <label for="fname" class="floating-label">First Name *</label>
                        <input type="text" id="fname" name="fname" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="mname" class="floating-label">Middle Name</label>
                        <input type="text" id="mname" name="mname" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                    </div>
                    <div class="input-group col-span-1">
                        <label for="lname" class="floating-label">Last Name *</label>
                        <input type="text" id="lname" name="lname" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="suffix" class="floating-label">Suffix</label>
                        <select id="suffix" name="suffix" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
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
                        <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <p class="username-feedback mt-2 text-sm text-red-600 hidden"></p>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-2">
                        <label for="phone" class="floating-label">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <p class="phone-feedback mt-2 text-sm text-red-600 hidden"></p>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="password" class="floating-label">Password *</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="password-feedback mt-2 text-sm text-red-600 hidden"></p>
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
                            <input type="password" id="confirm-password" name="confirm-password" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                </div>
            </form>

            <div class="modal-footer px-6 py-4 border-t border-gray-200 flex space-x-3">
                <button type="button" id="cancel-create" class="flex-1 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville px-6 py-2">
                    Cancel
                </button>
                <button type="submit" form="cashier-form" class="flex-1 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville px-6 py-2">
                    Create Employee
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Cashier Modal -->
    <div id="edit-cashier-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-4">
        <div class="dashboard-card rounded-lg max-w-2xl w-full modal-container">
            <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Edit Employee</h3>
                <button id="close-edit-modal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="edit-cashier-form" class="modal-body px-6 py-4 space-y-6" method="POST" action="">
                <input type="hidden" name="form_type" value="edit_cashier">
                <input type="hidden" name="cashier_id" id="edit-cashier-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="input-group col-span-1">
                        <label for="edit-fname" class="floating-label">First Name *</label>
                        <input type="text" id="edit-fname" name="fname" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="edit-mname" class="floating-label">Middle Name</label>
                        <input type="text" id="edit-mname" name="mname" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                    </div>
                    <div class="input-group col-span-1">
                        <label for="edit-lname" class="floating-label">Last Name *</label>
                        <input type="text" id="edit-lname" name="lname" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="edit-suffix" class="floating-label">Suffix</label>
                        <select id="edit-suffix" name="suffix" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
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
                        <input type="text" id="edit-username" name="username" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <p class="username-feedback mt-2 text-sm text-red-600 hidden"></p>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-2">
                        <label for="edit-phone" class="floating-label">Phone Number *</label>
                        <input type="tel" id="edit-phone" name="phone" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville" required>
                        <p class="phone-feedback mt-2 text-sm text-red-600 hidden"></p>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="input-group col-span-1">
                        <label for="edit-password" class="floating-label">New Password (optional)</label>
                        <div class="relative">
                            <input type="password" id="edit-password" name="password" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="edit-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="password-feedback mt-2 text-sm text-red-600 hidden"></p>
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
                            <input type="password" id="edit-confirm-password" name="confirm-password" class="w-full px-4 py-2 border border-warm-cream/50 rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent bg-white/50 backdrop-blur-sm font-baskerville">
                            <button type="button" class="absolute right-3 top-2 text-gray-500 hover:text-deep-brown toggle-password" data-target="edit-confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="field-feedback mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                </div>
            </form>

            <div class="modal-footer px-6 py-4 border-t border-gray-200 flex space-x-3">
                <button type="button" id="cancel-edit" class="flex-1 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville px-6 py-2">
                    Cancel
                </button>
                <button type="submit" form="edit-cashier-form" class="flex-1 bg-gradient-to-r from-deep-brown to-rich-brown hover:from-rich-brown hover:to-deep-brown text-warm-cream rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-baskerville px-6 py-2">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[1000] hidden flex items-center justify-center p-4">
        <div class="dashboard-card rounded-lg max-w-md w-full modal-container">
            <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-deep-brown font-playfair">Confirm Deletion</h3>
                <button id="close-delete-modal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="modal-body px-6 py-4">
                <p class="text-gray-700 font-baskerville">Are you sure you want to delete this employee? This action cannot be undone.</p>
            </div>

            <div class="modal-footer px-6 py-4 border-t border-gray-200 flex space-x-3">
                <button type="button" id="cancel-delete" class="flex-1 text-rich-brown border border-rich-brown rounded-lg hover:bg-rich-brown hover:text-warm-cream transition-colors duration-200 font-baskerville px-6 py-2">
                    Cancel
                </button>
                <button type="button" id="confirm-delete" class="flex-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-baskerville px-6 py-2">
                    Delete
                </button>
            </div>
            <input type="hidden" id="employee-to-delete">
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
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');
            
            if (sidebar.classList.contains('w-16')) {
                sidebarTexts.forEach(text => text.style.display = 'none');
            } else {
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

        // Profile dropdown
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        
        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            setTimeout(() => {
                profileMenu.classList.toggle('opacity-0');
            }, 50);
        });

        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('opacity-0');
                setTimeout(() => {
                    profileMenu.classList.add('hidden');
                }, 300);
            }
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

        // Modal handling
        const createModal = document.getElementById('create-cashier-modal');
        const editModal = document.getElementById('edit-cashier-modal');
        const deleteModal = document.getElementById('delete-confirm-modal');
        const showCreateModalBtn = document.getElementById('show-create-modal');
        
        // Show create modal
        showCreateModalBtn.addEventListener('click', () => {
            document.querySelector('.flex-1').classList.add('blur-effect');
            document.querySelector('#sidebar').classList.add('blur-effect');
            createModal.classList.remove('hidden');
            setTimeout(() => {
                createModal.querySelector('.dashboard-card').style.opacity = '1';
                createModal.querySelector('.dashboard-card').style.transform = 'translateY(0)';
            }, 50);
        });

        // Close create modal
        function closeCreateModal() {
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            const modal = createModal;
            modal.querySelector('.dashboard-card').style.opacity = '0';
            modal.querySelector('.dashboard-card').style.transform = 'translateY(20px)';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        document.getElementById('close-modal').addEventListener('click', closeCreateModal);
        document.getElementById('cancel-create').addEventListener('click', closeCreateModal);

        // Close edit modal
        function closeEditModal() {
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            const modal = editModal;
            modal.querySelector('.dashboard-card').style.opacity = '0';
            modal.querySelector('.dashboard-card').style.transform = 'translateY(20px)';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        document.getElementById('close-edit-modal').addEventListener('click', closeEditModal);
        document.getElementById('cancel-edit').addEventListener('click', closeEditModal);

        // Close delete modal
        function closeDeleteModal() {
            document.querySelector('.flex-1').classList.remove('blur-effect');
            document.querySelector('#sidebar').classList.remove('blur-effect');
            const modal = deleteModal;
            modal.querySelector('.dashboard-card').style.opacity = '0';
            modal.querySelector('.dashboard-card').style.transform = 'translateY(20px)';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        document.getElementById('close-delete-modal').addEventListener('click', closeDeleteModal);
        document.getElementById('cancel-delete').addEventListener('click', closeDeleteModal);

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === createModal) {
                closeCreateModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // ... existing form validation and submission code ...
    </script>
</body>
</html>