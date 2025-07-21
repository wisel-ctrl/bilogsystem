<?php
// admin_create.php
session_start();
require_once 'db_connect.php';

$error = '';
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_create'])) {
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $contact_number = str_replace('-', '', trim($_POST['contact_number'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($first_name)) {
        $errors['firstName'] = 'First name is required';
    } elseif (strlen($first_name) < 2) {
        $errors['firstName'] = 'First name must be at least 2 characters';
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $first_name)) {
        $errors['firstName'] = 'First name can only contain letters and spaces';
    }

    if (empty($last_name)) {
        $errors['lastName'] = 'Last name is required';
    } elseif (strlen($last_name) < 2) {
        $errors['lastName'] = 'Last name must be at least 2 characters';
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $last_name)) {
        $errors['lastName'] = 'Last name can only contain letters and spaces';
    }

    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    } elseif (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
        $errors['username'] = 'Username can only contain letters, numbers, and underscores';
    }

    if (empty($contact_number)) {
        $errors['contactNumber'] = 'Contact number is required';
    } elseif (!preg_match('/^[0-9]{11}$/', $contact_number)) {
        $errors['contactNumber'] = 'Contact number must be 11 digits';
    } elseif (!preg_match('/^09\d{9}$/', $contact_number)) {
        $errors['contactNumber'] = 'Please enter a valid Philippine mobile number (09XXXXXXXXX)';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    } elseif ($password !== $confirm_password) {
        $errors['password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        try {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new admin user with usertype = 1
            $stmt = $conn->prepare("INSERT INTO users_tb 
                (first_name, middle_name, last_name, suffix, username, contact_number, password, usertype) 
                VALUES (:first_name, :middle_name, :last_name, :suffix, :username, :contact_number, :password, 1)");
            
            $stmt->execute([
                ':first_name' => $first_name,
                ':middle_name' => $middle_name,
                ':last_name' => $last_name,
                ':suffix' => $suffix,
                ':username' => $username,
                ':contact_number' => $contact_number,
                ':password' => $hashed_password
            ]);
            
            $success = 'Admin account created successfully!';
        } catch(PDOException $e) {
            $errors['database'] = 'Database error: ' . $e->getMessage();
        }
    }

    // Combine errors for display
    if (!empty($errors)) {
        $error = implode('<br>', array_values($errors));
    }
}

// Check if passcode has been verified in this session
$passcode_verified = isset($_SESSION['passcode_verified']) && $_SESSION['passcode_verified'] === true;

// Encode the main content to make it less readable in "View Page Source"
$main_content = base64_encode('
<div class="container" id="main-content" style="display:none;">
    <div class="header-section">
        <h1>Create Admin Account</h1>
        <p class="subtitle">Set up a new administrative user account</p>
    </div>
    
    ' . ($error ? '<div class="alert alert-error"><svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>' . htmlspecialchars($error) . '</div>' : '') . '
    ' . ($success ? '<div class="alert alert-success"><svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>' . htmlspecialchars($success) . '</div>' : '') . '
    
    <form action="admin_create.php" method="post" class="admin-form" id="admin-form">
        <input type="hidden" name="confirm_create" value="1">
        <div class="form-row">
            <div class="form-group">
                <label for="first_name" class="required">First Name</label>
                <input type="text" id="first_name" name="first_name" required value="' . htmlspecialchars($first_name ?? '') . '">
                <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="first_name_feedback"></p>
            </div>
            
            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" id="middle_name" name="middle_name" value="' . htmlspecialchars($middle_name ?? '') . '">
                <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="middle_name_feedback"></p>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="last_name" class="required">Last Name</label>
                <input type="text" id="last_name" name="last_name" required value="' . htmlspecialchars($last_name ?? '') . '">
                <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="last_name_feedback"></p>
            </div>
            
            <div class="form-group">
                <label for="suffix">Suffix</label>
                <input type="text" id="suffix" name="suffix" placeholder="e.g. Jr, Sr, III" value="' . htmlspecialchars($suffix ?? '') . '">
                <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="suffix_feedback"></p>
            </div>
        </div>
        
        <div class="form-group">
            <label for="username" class="required">Username</label>
            <input type="text" id="username" name="username" required value="' . htmlspecialchars($username ?? '') . '" autocomplete="username">
            <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="username_feedback"></p>
        </div>
                
        <div class="form-group">
            <label for="contact_number" class="required">Contact Number</label>
            <input type="tel" id="contact_number" name="contact_number" required value="' . htmlspecialchars($contact_number ?? '') . '">
            <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="contact_number_feedback"></p>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="password" class="required">Password</label>
                <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
                <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="password_feedback"></p>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="required">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8" autocomplete="new-password">
                <p class="field-feedback mt-1 text-sm text-red-600 hidden" id="confirm_password_feedback"></p>
            </div>
        </div>
        
        <div class="form-group">
            <button type="button" class="btn btn-primary" id="submit-btn" onclick="confirmSubmission()">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                Create Admin Account
            </button>
        </div>
    </form>
</div>
');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #E8E0D5 0%, #D4C4B0 100%);
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(93, 47, 15, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-section {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #E8E0D5;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #5D2F0F;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(93, 47, 15, 0.1);
        }

        .subtitle {
            color: #8B4513;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .admin-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            color: #5D2F0F;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .required:after {
            content: " *";
            color: #dc2626;
        }

        input[type="text"],
        input[type="password"],
        input[type="tel"] {
            padding: 0.75rem 1rem;
            border: 2px solid #E8E0D5;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            color: #5D2F0F;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
            background: rgba(255, 255, 255, 1);
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.4);
        }

        .btn-icon {
            width: 20px;
            height: 20px;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .field-feedback {
            margin-top: 0.25rem;
        }

        .field-error {
            border-color: #dc2626 !important;
        }

        .field-success {
            border-color: #16a34a !important;
        }

        /* Modal styles */
        .modal {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(93, 47, 15, 0.8);
            backdrop-filter: blur(10px);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: linear-gradient(135deg, #fff 0%, #f8f7f5 100%);
            padding: 3rem;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(93, 47, 15, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 90%;
        }

        .modal-header {
            margin-bottom: 2rem;
        }

        .modal-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #5D2F0F;
            margin-bottom: 0.5rem;
        }

        .modal-header p {
            color: #8B4513;
            font-size: 1rem;
        }

        .passcode-container {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .passcode-digit {
            width: 60px;
            height: 70px;
            border: 3px solid #E8E0D5;
            border-radius: 12px;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            color: #5D2F0F;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            outline: none;
        }

        .passcode-digit:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.2);
            background: rgba(255, 255, 255, 1);
            transform: scale(1.05);
        }

        .passcode-digit.filled {
            border-color: #A0522D;
            background: rgba(160, 82, 45, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-modal {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.4);
        }

        .btn-clear {
            background: rgba(139, 69, 19, 0.1);
            color: #8B4513;
            border: 2px solid #E8E0D5;
        }

        .btn-clear:hover {
            background: rgba(139, 69, 19, 0.2);
            border-color: #8B4513;
        }

        .modal-error {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .error-icon {
            width: 16px;
            height: 16px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .passcode-container {
                gap: 0.5rem;
            }
            
            .passcode-digit {
                width: 50px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .modal-content {
                padding: 2rem;
            }
            
            .container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php if (!$passcode_verified): ?>
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Access Control</h2>
                <p>Enter your 6-digit passcode to continue</p>
            </div>
            
            <div class="passcode-container">
                <input type="text" class="passcode-digit" maxlength="1" id="digit1">
                <input type="text" class="passcode-digit" maxlength="1" id="digit2">
                <input type="text" class="passcode-digit" maxlength="1" id="digit3">
                <input type="text" class="passcode-digit" maxlength="1" id="digit4">
                <input type="text" class="passcode-digit" maxlength="1" id="digit5">
                <input type="text" class="passcode-digit" maxlength="1" id="digit6">
            </div>
            
            <div class="modal-actions">
                <button class="btn-modal btn-clear" onclick="clearPasscode()">Clear</button>
                <button class="btn-modal btn-submit" onclick="verifyPasscode()">Submit</button>
            </div>
            
            <div id="modal-error" class="modal-error"></div>
        </div>
    </div>
    <?php endif; ?>
    
    <div id="encoded-content" style="display:none;"><?php echo $main_content; ?></div>
    <div id="content"></div>

    <script>
        // Initialize passcode inputs if modal is present
        <?php if (!$passcode_verified): ?>
        const passcodeInputs = document.querySelectorAll('.passcode-digit');
        
        passcodeInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Add filled class when digit is entered
                if (this.value) {
                    this.classList.add('filled');
                    // Move to next input
                    if (index < passcodeInputs.length - 1) {
                        passcodeInputs[index + 1].focus();
                    }
                } else {
                    this.classList.remove('filled');
                }
                
                // Clear error when user types
                document.getElementById('modal-error').innerHTML = '';
            });
            
            input.addEventListener('keydown', function(e) {
                // Handle backspace
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    passcodeInputs[index - 1].focus();
                    passcodeInputs[index - 1].value = '';
                    passcodeInputs[index - 1].classList.remove('filled');
                }
                
                // Handle Enter key
                if (e.key === 'Enter') {
                    verifyPasscode();
                }
            });
            
            input.addEventListener('focus', function() {
                this.select();
            });
        });

        // Get passcode from inputs
        function getPasscode() {
            return Array.from(passcodeInputs).map(input => input.value).join('');
        }

        // Clear passcode
        function clearPasscode() {
            passcodeInputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled');
            });
            passcodeInputs[0].focus();
            document.getElementById('modal-error').innerHTML = '';
        }

        // Passcode verification
        function verifyPasscode() {
            const passcode = getPasscode();
            const correctPasscode = '072225';
            const modal = document.getElementById('modal');
            const contentDiv = document.getElementById('content');
            const encodedContent = document.getElementById('encoded-content').innerText;
            const modalError = document.getElementById('modal-error');

            if (passcode.length !== 6) {
                modalError.innerHTML = `
                    <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    Please enter all 6 digits
                `;
                return;
            }

            if (passcode === correctPasscode) {
                // Make AJAX call to set session variable
                fetch('set_passcode_session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'passcode_verified=true'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                        contentDiv.innerHTML = decodeContent(encodedContent);
                        document.getElementById('main-content').style.display = 'block';
                        initializeFormValidation();
                    }
                })
                .catch(error => {
                    modalError.innerHTML = `
                        <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        Error verifying passcode. Please try again.
                    `;
                });
            } else {
                modalError.innerHTML = `
                    <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    Incorrect passcode. Please try again.
                `;
                clearPasscode();
            }
        }

        // Prevent right-click and keyboard shortcuts only when passcode modal is active
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'u' || e.key === 's' || e.key === 'i')) {
                e.preventDefault();
            }
        });
        <?php else: ?>
        // No prevention when passcode is verified
        <?php endif; ?>

        // Simple obfuscation function to decode content
        function decodeContent(encoded) {
            return atob(encoded);
        }

        // Initialize form validation
        function initializeFormValidation() {
    const form = document.querySelector('.admin-form');
    if (!form) {
        console.error('Form not found');
        return;
    }

    // Select only inputs with IDs and corresponding feedback elements
    const inputs = Array.from(form.querySelectorAll('input[id]')).filter(input => {
        const feedback = document.getElementById(input.id + '_feedback');
        if (!feedback) {
            console.warn(`Feedback element not found for input ID: ${input.id}`);
            return false;
        }
        return true;
    });

    const submitBtn = document.getElementById('submit-btn');
    if (!submitBtn) {
        console.error('Submit button not found');
        return;
    }

    function validateField(field) {
        const feedback = document.getElementById(field.id + '_feedback');
        if (!field || !feedback) {
            console.error(`Field or feedback element not found for ID: ${field?.id || 'undefined'}`);
            return false;
        }

        let isValid = true;
        let message = '';

        field.classList.remove('field-error', 'field-success');
        feedback.classList.add('hidden');

        // Rest of the validation logic...
        switch (field.name) {
            case 'first_name':
            case 'last_name':
                if (!field.value.trim()) {
                    isValid = false;
                    message = `${field.name === 'first_name' ? 'First' : 'Last'} name is required`;
                } else if (field.value.trim().length < 2) {
                    isValid = false;
                    message = `${field.name === 'first_name' ? 'First' : 'Last'} name must be at least 2 characters`;
                } else if (!/^[A-Za-z\s]+$/.test(field.value.trim())) {
                    isValid = false;
                    message = `${field.name === 'first_name' ? 'First' : 'Last'} name can only contain letters and spaces`;
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

            case 'contact_number':
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
                if (!field.value.trim()) {
                    isValid = false;
                    message = 'Password is required';
                } else if (field.value.length < 8) {
                    isValid = false;
                    message = 'Password must be at least 8 characters';
                }
                break;

            case 'confirm_password':
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

        if (field.value.trim() !== '' || field.hasAttribute('required')) {
            if (isValid) {
                field.classList.add('field-success');
                feedback.classList.add('hidden');
            } else {
                field.classList.add('field-error');
                feedback.textContent = message;
                feedback.classList.remove('hidden');
            }
        }

        return isValid;
    }

    function checkFormValidity() {
        let isValid = true;
        inputs.forEach(input => {
            if (input.hasAttribute('required') || input.value.trim()) {
                if (!validateField(input)) {
                    isValid = false;
                }
            }
        });
        submitBtn.disabled = !isValid;
    }

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.name === 'contact_number') {
                let value = input.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                input.value = value;
            }
            if (['first_name', 'last_name', 'middle_name'].includes(input.name)) {
                let value = input.value;
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
                input.value = value;
            }
            if (input.name === 'username') {
                input.value = input.value.replace(/[^A-Za-z0-9_]/g, '');
            }
            validateField(input);
            checkFormValidity();
        });

        input.addEventListener('blur', () => {
            validateField(input);
            checkFormValidity();
        });

        input.addEventListener('paste', function(e) {
            if (['first_name', 'last_name', 'middle_name'].includes(this.name)) {
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
                validateField(this);
                checkFormValidity();
            }
        });
    });

    // Initialize validation on page load
    checkFormValidity();
}

        // SweetAlert confirmation
        function confirmSubmission() {
            Swal.fire({
                title: 'Confirm Account Creation',
                text: 'Are you sure you want to create this admin account?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, create it!',
                cancelButtonText: 'No, cancel',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-clear'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('admin-form').submit();
                }
            });
        }

        // Display form if passcode is already verified
        <?php if ($passcode_verified): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const contentDiv = document.getElementById('content');
            const encodedContent = document.getElementById('encoded-content').innerText;
            contentDiv.innerHTML = decodeContent(encodedContent);
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
                mainContent.style.display = 'block';
                document.body.style.overflow = 'auto';
                const form = document.querySelector('.admin-form');
                if (form) {
                    initializeFormValidation();
                } else {
                    console.error('Form not found');
                }
            } else {
                console.error('Main content not found');
            }
        });
        <?php else: ?>
        // Focus first input on load for passcode modal
        window.addEventListener('load', function() {
            passcodeInputs[0].focus();
        });
        <?php endif; ?>
    </script>
</body>
</html>