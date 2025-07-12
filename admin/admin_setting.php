<?php
require_once 'admin_auth.php';
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle photo upload (must come first since it exits early)
    if (isset($_FILES['profile_photo'])) {
        try {
            $targetDir = "../images/profile_pictures/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['profile_photo']['name']);
            $targetFile = $targetDir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            
            // Check if image file is a actual image
            $check = getimagesize($_FILES['profile_photo']['tmp_name']);
            if ($check === false) {
                throw new Exception("File is not an image.");
            }
            
            // Check file size (max 2MB)
            if ($_FILES['profile_photo']['size'] > 2000000) {
                throw new Exception("Sorry, your file is too large. Max 2MB allowed.");
            }
            
            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            }
            
            // Upload file
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile)) {
                // Update database
                $stmt = $conn->prepare("UPDATE users_tb SET profile_picture = :photo WHERE id = :user_id");
                $stmt->execute([
                    ':photo' => $fileName,
                    ':user_id' => $_SESSION['user_id']
                ]);
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => "Profile photo updated successfully!"]);
                    exit();
                } else {
                    $_SESSION['success_message'] = "Profile photo updated successfully!";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                }
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit();
            } else {
                $_SESSION['error_message'] = $e->getMessage();
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }
    
    // Handle profile update
    if (isset($_POST['first_name'])) {
        try {
            // Get current user data for comparison
            $currentUser = $conn->prepare("SELECT first_name, middle_name, last_name, suffix, username, contact_number FROM users_tb WHERE id = :user_id");
            $currentUser->execute([':user_id' => $_SESSION['user_id']]);
            $currentData = $currentUser->fetch();
            
            // Check if any field actually changed
            $hasChanges = false;
            $fieldsToCheck = ['first_name', 'middle_name', 'last_name', 'suffix', 'username', 'contact_number'];
            foreach ($fieldsToCheck as $field) {
                if ($_POST[$field] != $currentData[$field]) {
                    $hasChanges = true;
                    break;
                }
            }
            
            if (!$hasChanges) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => "No changes detected. Profile information remains the same."]);
                    exit();
                } else {
                    $_SESSION['error_message'] = "No changes detected. Profile information remains the same.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                }
            }
            
            // Validate inputs
            $errors = [];
            
            // Function to check if string contains only spaces or special characters
            function isInvalidString($str) {
                return preg_match('/^[\s\W]+$/', $str) || trim($str) === '';
            }
            
            // Validate required fields
            if (isInvalidString($_POST['first_name'])) {
                $errors[] = "First name cannot be empty or contain only spaces/special characters.";
            }
            
            if (isInvalidString($_POST['last_name'])) {
                $errors[] = "Last name cannot be empty or contain only spaces/special characters.";
            }
            
            if (isInvalidString($_POST['username'])) {
                $errors[] = "Username cannot be empty or contain only spaces/special characters.";
            }
            
            if (isInvalidString($_POST['contact_number'])) {
                $errors[] = "Phone number cannot be empty or contain only spaces/special characters.";
            }
            
            // Validate phone number format (basic validation)
            if (!preg_match('/^[0-9+\- ]+$/', $_POST['contact_number'])) {
                $errors[] = "Phone number can only contain numbers, plus sign, hyphens, and spaces.";
            }
            
            if (!preg_match('/^09[0-9]{9}$/', $_POST['contact_number'])) {
        $errors[] = "Phone number must be a valid Philippine number (11 digits starting with 09).";
    }
            
            if (!empty($errors)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => implode("\n", $errors)]);
                    exit();
                } else {
                    $_SESSION['error_message'] = implode("<br>", $errors);
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                }
            }
            
            $stmt = $conn->prepare("UPDATE users_tb SET 
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                suffix = :suffix,
                username = :username,
                contact_number = :contact_number
                WHERE id = :user_id");
            
            $stmt->execute([
                ':first_name' => $_POST['first_name'],
                ':middle_name' => $_POST['middle_name'],
                ':last_name' => $_POST['last_name'],
                ':suffix' => $_POST['suffix'],
                ':username' => $_POST['username'],
                ':contact_number' => $_POST['contact_number'],
                ':user_id' => $_SESSION['user_id']
            ]);
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => "Profile updated successfully!"]);
                exit();
            } else {
                $_SESSION['success_message'] = "Profile updated successfully!";
            }
        } catch (PDOException $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => "Error updating profile: " . $e->getMessage()]);
                exit();
            } else {
                $_SESSION['error_message'] = "Error updating profile: " . $e->getMessage();
            }
        }
    }
        
    // Handle password change
    if (isset($_POST['current_password'])) {
        try {
            // First verify current password
            $stmt = $conn->prepare("SELECT password FROM users_tb WHERE id = :user_id");
            $stmt->execute([':user_id' => $_SESSION['user_id']]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($_POST['current_password'], $user['password'])) {
                // Update password
                $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users_tb SET password = :password WHERE id = :user_id");
                $stmt->execute([
                    ':password' => $newPassword,
                    ':user_id' => $_SESSION['user_id']
                ]);
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => "Password updated successfully!"]);
                    exit();
                } else {
                    $_SESSION['success_message'] = "Password updated successfully!";
                }
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => "Current password is incorrect"]);
                    exit();
                } else {
                    $_SESSION['error_message'] = "Current password is incorrect";
                }
            }
        } catch (PDOException $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => "Error updating password: " . $e->getMessage()]);
                exit();
            } else {
                $_SESSION['error_message'] = "Error updating password: " . $e->getMessage();
            }
        }
    }

    
    // Redirect to prevent form resubmission (for non-AJAX requests)
    if (!$isAjax) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}


// Get current user data
try {
    $stmt = $conn->prepare("SELECT * FROM users_tb WHERE id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception("User not found");
    }
} catch (Exception $e) {
    die("Error fetching user data: " . $e->getMessage());
}

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    try {
        $targetDir = "../images/profile_pictures/"; // Changed to correct directory
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['profile_photo']['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['profile_photo']['tmp_name']);
        if ($check === false) {
            throw new Exception("File is not an image.");
        }
        
        // Check file size (max 2MB)
        if ($_FILES['profile_photo']['size'] > 2000000) {
            throw new Exception("Sorry, your file is too large. Max 2MB allowed.");
        }
        
        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }
        
        // Upload file
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile)) {
            // Update database - changed to profile_picture to match your DB
            $stmt = $conn->prepare("UPDATE users_tb SET profile_picture = :photo WHERE id = :user_id");
            $stmt->execute([
                ':photo' => $fileName,
                ':user_id' => $_SESSION['user_id']
            ]);
            
            // Return JSON response
            echo json_encode(['success' => true, 'message' => "Profile photo updated successfully!"]);
            exit();
        } else {
            throw new Exception("Sorry, there was an error uploading your file.");
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }
}

// Capture page content
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'baskerville': ['Libre Baskerville', 'serif'],
                    },
                    colors: {
                        'deep-brown': '#8B4513',
                        'accent-brown': '#A0522D',
                        'rich-brown': '#6B3410',
                        'warm-cream': '#F5F5DC',
                        'light-brown': '#D2B48C',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f5f5dc 0%, #e6d3a3 100%);
            min-height: 100vh;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        .profile-photo:hover {
            transform: scale(1.05);
        }
        .photo-upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }
        .photo-container:hover .photo-upload-overlay {
            opacity: 1;
        }
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Toast notification styles - bottom right */
    .custom-alert {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        max-width: 350px;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.3s ease;
        animation: slideInUp 0.3s ease-out forwards;
    }

    .custom-alert.success {
        background-color: #22C55E;
        color: white;
    }

    .custom-alert.error {
        background-color: #EF4444;
        color: white;
    }

    .custom-alert i {
        margin-right: 12px;
        font-size: 1.2rem;
    }

    @keyframes slideInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    
    
    .toggle-password {
        transition: all 0.2s ease;
    }
    .toggle-password:hover {
        transform: scale(1.1);
    }

    </style>
</head>
<body class="gradient-bg">
    
    <?php if (isset($_SESSION['success_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showAlert('<?php echo addslashes($_SESSION['success_message']); ?>', 'success');
        });
    </script>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showAlert('<?php echo addslashes($_SESSION['error_message']); ?>', 'error');
        });
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

    <div class="container mx-auto px-4 py-8 max-w-6xl">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Photo Section -->
            <div class="lg:col-span-1">
                <div class="bg-white/90 rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl slide-in">
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6 text-center">Profile Photo</h3>
                    
                    <div class="flex flex-col items-center space-y-4">
                        <div class="photo-container relative">
                            <?php 
                            $photoPath = !empty($user['profile_picture']) 
                                ? '../images/profile_pictures/' . htmlspecialchars($user['profile_picture'])
                                : 'https://via.placeholder.com/120x120/D2B48C/FFFFFF?text=Admin';
                            ?>
                            <img id="profile-photo" src="<?php echo $photoPath; ?>" 
                                  class="profile-photo border-4 border-accent-brown shadow-lg">
                            <div class="photo-upload-overlay">
                                <i class="fas fa-camera text-white text-2xl"></i>
                            </div>
                            <input type="file" id="photo-upload" accept="image/*" class="hidden">
                        </div>
                        
                        <div class="text-center">
                            <h4 class="font-baskerville text-xl font-bold text-deep-brown">Administrator</h4>
                            <p class="text-accent-brown text-sm">Member since June 2025</p>
                        </div>
                        
                        <button id="change-photo-btn" class="bg-gradient-to-r from-accent-brown to-rich-brown text-white px-4 py-2 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                            Change Photo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Card -->
<div class="bg-white/90 rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl slide-in">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-playfair text-2xl font-bold text-deep-brown">Personal Information</h3>
        <button type="button" id="edit-profile-btn" class="flex items-center text-accent-brown hover:text-deep-brown transition-colors duration-200">
            <i class="fas fa-edit mr-2"></i>
            <span class="font-baskerville">Edit Profile</span>
        </button>
    </div>

    <form id="profile-update-form" class="space-y-6" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-deep-brown/80">First Name</label>
                <div class="relative">
                    <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                           class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                           disabled required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-user text-deep-brown/30"></i>
                    </div>
                </div>
                <p class="text-xs text-deep-brown/50 mt-1 hidden" id="first-name-error">First name cannot be empty or contain only spaces/special characters.</p>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-deep-brown/80">Middle Name</label>
                <input type="text" id="middle-name" name="middle_name" value="<?php echo htmlspecialchars($user['middle_name'] ?? ''); ?>"
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                       disabled>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-deep-brown/80">Last Name</label>
                <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                       disabled required>
                <p class="text-xs text-deep-brown/50 mt-1 hidden" id="last-name-error">Last name cannot be empty or contain only spaces/special characters.</p>
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-deep-brown/80">Suffix</label>
                <input type="text" id="suffix" name="suffix" value="<?php echo htmlspecialchars($user['suffix'] ?? ''); ?>"
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled>
            </div>
        </div>
        
        <div class="space-y-1">
            <label class="block text-sm font-medium text-deep-brown/80">Username</label>
            <div class="relative">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="fas fa-at text-deep-brown/30"></i>
                </div>
            </div>
            <p class="text-xs text-deep-brown/50 mt-1 hidden" id="username-error">Username cannot be empty or contain only spaces/special characters.</p>
        </div>
        
        <div class="space-y-1">
            <label class="block text-sm font-medium text-deep-brown/80">Phone Number</label>
            <div class="relative">
                <input type="tel" id="phone" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" 
                       disabled required
                       pattern="[0-9]*"
                       inputmode="numeric"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="fas fa-phone text-deep-brown/30"></i>
                </div>
            </div>
            <p class="text-xs text-deep-brown/50 mt-1 hidden" id="phone-error">Phone number must be 11 digits starting with 09 (e.g., 09123456789).</p>
        </div>   
        
        <div class="pt-4 border-t border-warm-cream flex justify-end space-x-3">
            <button type="button" id="cancel-edit-btn" class="hidden px-5 py-2.5 rounded-lg font-baskerville text-deep-brown hover:bg-warm-cream/50 transition-all fade-in">
                Cancel
            </button>
            <button type="submit" id="save-profile-btn" class="hidden bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] fade-in">
                Save Changes
            </button>
        </div>
    </form>
</div>

                <!-- Security Settings Card -->
                <div class="bg-white/90 rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl slide-in">
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Security Settings</h3>
                    
                    <form id="password-update-form" class="space-y-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Current Password</label>
                            <div class="relative">
                                <input type="password" id="current-password" name="current_password" 
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password hover:text-deep-brown transition-colors"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">New Password</label>
                            <div class="relative">
                                <input type="password" id="new-password" name="new_password" 
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password hover:text-deep-brown transition-colors"></i>
                                </div>
                            </div>
                            <p class="text-xs text-deep-brown/50 mt-1">Minimum 8 characters, one uppercase letter, and one number</p>
                            <ul id="password-requirements" class="text-xs text-deep-brown/70 mt-2 space-y-1">
                                <li id="length-check" class="flex items-center transition-colors duration-200"><span class="mr-2">•</span> At least 8 characters</li>
                                <li id="uppercase-check" class="flex items-center transition-colors duration-200"><span class="mr-2">•</span> At least one uppercase letter</li>
                                <li id="number-check" class="flex items-center transition-colors duration-200"><span class="mr-2">•</span> At least one number</li>
                            </ul>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="confirm-password" name="confirm_password" 
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password hover:text-deep-brown transition-colors"></i>
                                </div>
                            </div>
                            <p id="confirm-match" class="text-xs text-deep-brown/50 mt-1 hidden transition-all duration-200">Passwords must match</p>
                        </div>
                        
                        <div class="pt-4 border-t border-warm-cream text-right">
                            <button type="submit" id="submit-password-btn" class="bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" disabled>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <?php
        $page_content = ob_get_clean();
    
        // Capture page-specific scripts
        ob_start();
    ?>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Profile Photo Upload
            const photoUpload = document.getElementById('photo-upload');
            const profilePhoto = document.getElementById('profile-photo');
            const changePhotoBtn = document.getElementById('change-photo-btn');
            const photoContainer = document.querySelector('.photo-container');

            // Trigger file input when clicking change photo button or overlay
            changePhotoBtn.addEventListener('click', () => {
                photoUpload.click();
            });

            photoContainer.addEventListener('click', () => {
                photoUpload.click();
            });

            // Handle photo upload
            photoUpload.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (file) {
        // Check file size (max 2MB)
        if (file.size > 2000000) {
            showAlert('File is too large. Max 2MB allowed.', 'error');
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            showAlert('Only JPG, PNG, and GIF files are allowed.', 'error');
            return;
        }
        
        // Show confirmation dialog
        const { isConfirmed } = await Swal.fire({
            title: 'Update Profile Photo',
            text: 'Are you sure you want to change your profile picture?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#A0522D',
            cancelButtonColor: '#6B3410',
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel'
        });
        
        if (!isConfirmed) {
            return;
        }
        
        // Show loading state
        profilePhoto.classList.add('opacity-50');
        const loadingIcon = document.createElement('div');
        loadingIcon.className = 'absolute inset-0 flex items-center justify-center';
        loadingIcon.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl text-deep-brown"></i>';
        photoContainer.appendChild(loadingIcon);
        
        // Prepare form data
        const formData = new FormData();
        formData.append('profile_photo', file);
        
        // Upload via AJAX
        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Oops, we didn't get JSON!");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePhoto.src = e.target.result;
                    profilePhoto.classList.add('fade-in');
                    
                    // Also update the header profile picture
                    document.getElementById('header-profile-pic').src = e.target.result;
                    
                    showAlert(data.message, 'success');
                };
                reader.readAsDataURL(file);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showAlert('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            profilePhoto.classList.remove('opacity-50');
            if (loadingIcon.parentNode === photoContainer) {
                photoContainer.removeChild(loadingIcon);
            }
        });
    }
});

            // Profile Edit Functionality
            const editProfileBtn = document.getElementById('edit-profile-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-btn');
            const saveProfileBtn = document.getElementById('save-profile-btn');
            const profileForm = document.getElementById('profile-update-form');
            const profileInputs = profileForm.querySelectorAll('input');

            function validateProfileField(field, errorId) {
                const value = field.value;
                const errorElement = document.getElementById(errorId);
                
                if (/^[\s\W]+$/.test(value) || value.trim() === '') {
                    field.classList.add('border-red-500');
                    errorElement.classList.remove('hidden');
                    return false;
                } else {
                    field.classList.remove('border-red-500');
                    errorElement.classList.add('hidden');
                    return true;
                }
            }
            
            // Add event listeners for real-time validation
            editProfileBtn.addEventListener('click', () => {
                profileInputs.forEach(input => {
                    input.disabled = false;
                    input.classList.add('fade-in');
                    
                    // Add input event listeners for validation
                    if (input.id === 'first-name') {
                        input.addEventListener('input', () => validateProfileField(input, 'first-name-error'));
                    } else if (input.id === 'last-name') {
                        input.addEventListener('input', () => validateProfileField(input, 'last-name-error'));
                    } else if (input.id === 'username') {
                        input.addEventListener('input', () => validateProfileField(input, 'username-error'));
                    } else if (input.id === 'phone') {
                        input.addEventListener('input', function() {
                            const isValid = validateProfileField(this, 'phone-error');
                            if (!/^[0-9+\- ]*$/.test(this.value)) {
                                this.classList.add('border-red-500');
                                document.getElementById('phone-error').textContent = 'Phone number can only contain numbers, plus sign, hyphens, and spaces.';
                                document.getElementById('phone-error').classList.remove('hidden');
                                isValid = false;
                            }
                            return isValid;
                        });
                    }
                });
                editProfileBtn.style.display = 'none';
                cancelEditBtn.classList.remove('hidden');
                saveProfileBtn.classList.remove('hidden');
            });

            cancelEditBtn.addEventListener('click', () => {
                profileInputs.forEach(input => {
                    input.disabled = true;
                });
                editProfileBtn.style.display = 'flex';
                cancelEditBtn.classList.add('hidden');
                saveProfileBtn.classList.add('hidden');
            });

            // Update the profile form submission handler
            profileForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                // Client-side validation
                const firstName = document.getElementById('first-name').value;
                const lastName = document.getElementById('last-name').value;
                const username = document.getElementById('username').value;
                const phone = document.getElementById('phone').value;
                
                // Function to check if string contains only spaces or special characters
                function isInvalidString(str) {
                    return /^[\s\W]+$/.test(str) || str.trim() === '';
                }
                
                // Validate required fields
                const errors = [];
                if (isInvalidString(firstName)) {
                    errors.push("First name cannot be empty or contain only spaces/special characters.");
                }
                
                if (isInvalidString(lastName)) {
                    errors.push("Last name cannot be empty or contain only spaces/special characters.");
                }
                
                if (isInvalidString(username)) {
                    errors.push("Username cannot be empty or contain only spaces/special characters.");
                }
                
                if (isInvalidString(phone)) {
                    errors.push("Phone number cannot be empty or contain only spaces/special characters.");
                }
                
                // Validate phone number format (basic validation)
                if (!/^[0-9+\- ]+$/.test(phone)) {
                    errors.push("Phone number can only contain numbers, plus sign, hyphens, and spaces.");
                }
                
                if (errors.length > 0) {
                    showAlert(errors.join("\n"), 'error');
                    return;
                }
                
                // Show confirmation dialog
                const { isConfirmed } = await Swal.fire({
                    title: 'Confirm Changes',
                    text: 'Are you sure you want to update your profile information?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#A0522D',
                    cancelButtonColor: '#6B3410',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel'
                });
                
                if (!isConfirmed) {
                    return;
                }
                
                // Add save animation
                saveProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                saveProfileBtn.disabled = true;
                
                // Submit form via AJAX
                fetch('', {
                    method: 'POST',
                    body: new FormData(profileForm),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        saveProfileBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Saved!';
                        setTimeout(() => {
                            saveProfileBtn.innerHTML = 'Save Changes';
                            saveProfileBtn.disabled = false;
                            cancelEditBtn.click(); // Reset form
                            showAlert(data.message || 'Profile updated successfully!', 'success');
                        }, 1500);
                    } else {
                        saveProfileBtn.innerHTML = 'Save Changes';
                        saveProfileBtn.disabled = false;
                        showAlert(data.message || 'Error updating profile', 'error');
                    }
                })
                .catch(error => {
                    saveProfileBtn.innerHTML = 'Save Changes';
                    saveProfileBtn.disabled = false;
                    showAlert('An error occurred. Please try again.', 'error');
                    console.error('Error:', error);
                });
            });


            // Password Update Functionality
            const passwordForm = document.getElementById('password-update-form');
            const newPassword = document.getElementById('new-password');
            const confirmPassword = document.getElementById('confirm-password');
            const submitPasswordBtn = document.getElementById('submit-password-btn');
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const numberCheck = document.getElementById('number-check');
            const confirmMatch = document.getElementById('confirm-match');

            // Password validation regex
            const minLength = /.{8,}/;
            const hasUppercase = /[A-Z]/;
            const hasNumber = /[0-9]/;

            function validatePassword() {
                const password = newPassword.value;
                const confirm = confirmPassword.value;
                
                // Check requirements
                const isLengthValid = minLength.test(password);
                const isUppercaseValid = hasUppercase.test(password);
                const isNumberValid = hasNumber.test(password);
                const isMatch = password === confirm && password !== '';

                // Update visual feedback with smooth transitions
                lengthCheck.style.color = isLengthValid ? '#22c55e' : 'inherit';
                uppercaseCheck.style.color = isUppercaseValid ? '#22c55e' : 'inherit';
                numberCheck.style.color = isNumberValid ? '#22c55e' : 'inherit';
                
                if (confirm !== '') {
                    confirmMatch.classList.remove('hidden');
                    confirmMatch.style.color = isMatch ? '#22c55e' : '#ef4444';
                    confirmMatch.textContent = isMatch ? 'Passwords match' : 'Passwords do not match';
                } else {
                    confirmMatch.classList.add('hidden');
                }

                // Enable/disable submit button
                submitPasswordBtn.disabled = !(isLengthValid && isUppercaseValid && isNumberValid && isMatch);
            }

            // Add event listeners for real-time validation
            newPassword.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', validatePassword);

            // Form submission
            passwordForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const password = newPassword.value;
    if (!minLength.test(password) || !hasUppercase.test(password) || !hasNumber.test(password)) {
        showAlert('Password must meet all requirements.', 'error');
        return;
    }
    if (newPassword.value !== confirmPassword.value) {
        showAlert('Passwords do not match.', 'error');
        return;
    }
    
    // Show confirmation dialog
    const { isConfirmed } = await Swal.fire({
        title: 'Change Password',
        text: 'Are you sure you want to change your password?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#A0522D',
        cancelButtonColor: '#6B3410',
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'No, cancel'
    });
    
    if (!isConfirmed) {
        return;
    }
    
    // Add update animation
    submitPasswordBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitPasswordBtn.disabled = true;
    
    // Submit form via AJAX
    fetch('', {
        method: 'POST',
        body: new FormData(passwordForm),
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            submitPasswordBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Updated!';
            setTimeout(() => {
                submitPasswordBtn.innerHTML = 'Update Password';
                passwordForm.reset();
                validatePassword();
                showAlert(data.message || 'Password updated successfully!', 'success');
            }, 1500);
        } else {
            submitPasswordBtn.innerHTML = 'Update Password';
            submitPasswordBtn.disabled = false;
            showAlert(data.message || 'Error updating password', 'error');
        }
    })
    .catch(error => {
        submitPasswordBtn.innerHTML = 'Update Password';
        submitPasswordBtn.disabled = false;
        showAlert('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    });
});
            
            
            // Helper function to show alerts
            function showAlert(message, type) {
                // Remove any existing alerts
                const existingAlert = document.querySelector('.custom-alert');
                if (existingAlert) {
                    existingAlert.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => existingAlert.remove(), 300);
                }
                
                const alert = document.createElement('div');
                alert.className = `custom-alert ${type === 'success' ? 'success' : 'error'}`;
                alert.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(alert);
                
                // Auto-remove after 5 seconds with fade out animation
                setTimeout(() => {
                    alert.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            }

            // Toggle password visibility for all password fields
            document.querySelectorAll('.toggle-password').forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.closest('.relative').querySelector('input');
                    const isPassword = input.type === 'password';
                    
                    // Toggle input type
                    input.type = isPassword ? 'text' : 'password';
                    
                    // Toggle icon classes
                    this.classList.toggle('fa-eye-slash', !isPassword);
                    this.classList.toggle('fa-eye', isPassword);
                    
                    // Change icon color for better visibility
                    this.classList.toggle('text-deep-brown/30', isPassword);
                    this.classList.toggle('text-deep-brown', !isPassword);
                });
            });

            // Add smooth scroll animations for cards
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('slide-in');
                    }
                });
            });

            document.querySelectorAll('.bg-white\\/90').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
    
    <script>
        // Update sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const titleFull = document.querySelector('.nav-title');
        const titleShort = document.querySelector('.nav-title-short');
        // Profile Dropdown functionality
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        const dropdownIcon = profileDropdown.querySelector('.fa-chevron-down');

        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            setTimeout(() => {
                profileMenu.classList.toggle('opacity-0');
                dropdownIcon.classList.toggle('rotate-180');
            }, 50);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden', 'opacity-0');
                dropdownIcon.classList.remove('rotate-180');
            }
        });
         function toggleSidebar() {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            // Show initials, hide full name
            titleFull.classList.add('hidden');
            titleShort.classList.remove('hidden');
        } else {
            // Show full name, hide initials
            titleFull.classList.remove('hidden');
            titleShort.classList.add('hidden');
        }
    }

        // Update event listeners
        sidebarToggle.addEventListener('click', toggleSidebar);

        // Update sidebar link click handler
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                // Remove active class from all links
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                link.classList.add('active');
            });
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', () => {
            // Set initial active link
            const dashboardLink = document.querySelector('.sidebar-link[href="#"]');
            if (dashboardLink) {
                dashboardLink.classList.add('active');
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

        // Scroll animation observer with improved timing
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animated');
                    }, index * 100); // Staggered animation
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(element => {
            observer.observe(element);
        });

        // Chart.js configurations with improved styling
        Chart.defaults.font.family = "'Libre Baskerville', serif";
        Chart.defaults.color = '#5D2F0F';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(93, 47, 15, 0.8)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.titleFont = {
            family: "'Playfair Display', serif",
            size: 14,
            weight: 'bold'
        };

        // Revenue Analysis Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [65000, 72000, 68000, 78000, 82000, 84000],
                    borderColor: '#8B4513',
                    backgroundColor: 'rgba(139, 69, 19, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#8B4513',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 60000,
                        grid: {
                            color: 'rgba(232, 224, 213, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            },
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Menu Sales Chart
        const menuCtx = document.getElementById('menuChart').getContext('2d');
        const menuChart = new Chart(menuCtx, {
            type: 'doughnut',
            data: {
                labels: ['Cappuccino', 'Latte', 'Americano', 'Espresso', 'Frappe', 'Others'],
                datasets: [{
                    data: [35, 25, 15, 10, 8, 7],
                    backgroundColor: [
                        '#8B4513',
                        '#A0522D',
                        '#5D2F0F',
                        '#E8E0D5',
                        '#D2B48C',
                        '#CD853F'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Season Trends Chart
        const seasonCtx = document.getElementById('seasonChart').getContext('2d');
        const seasonChart = new Chart(seasonCtx, {
            type: 'bar',
            data: {
                labels: ['Spring', 'Summer', 'Fall', 'Winter'],
                datasets: [{
                    label: 'Revenue',
                    data: [180000, 250000, 220000, 200000],
                    backgroundColor: [
                        '#8B4513',
                        '#A0522D',
                        '#5D2F0F',
                        '#E8E0D5'
                    ],
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 150000,
                        grid: {
                            color: 'rgba(232, 224, 213, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            },
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });



        function printChartData(chartId, title) {
            const chart = Chart.getChart(chartId);
            const data = chart.data;
            
            // Create print section if it doesn't exist
            let printSection = document.getElementById('printSection');
            if (!printSection) {
                printSection = document.createElement('div');
                printSection.id = 'printSection';
                document.body.appendChild(printSection);
            }
            
            // Generate table HTML
            let tableHtml = `
                <div class="print-header">
                    <h1 style="font-size: 24px; font-weight: bold; font-family: 'Playfair Display', serif;">${title}</h1>
                    <h2 style="font-size: 16px; color: #666; margin-top: 5px;">Caffè Lilio</h2>
                </div>
                <div class="print-date">
                    Generated on: ${new Date().toLocaleString()}
                </div>
                <table class="print-table">
                    <thead>
                        <tr>
                            <th style="background-color: #8B4513; color: white;">Category</th>
                            <th style="background-color: #8B4513; color: white;">Value</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            // Add data rows
            data.labels.forEach((label, index) => {
                const value = data.datasets[0].data[index];
                tableHtml += `
                    <tr>
                        <td>${label}</td>
                        <td>${chartId === 'revenueChart' || chartId === 'seasonChart' ? '₱' + value.toLocaleString() : value}</td>
                    </tr>
                `;
            });
            
            // Add total if applicable
            if (chartId === 'revenueChart' || chartId === 'seasonChart') {
                const total = data.datasets[0].data.reduce((sum, value) => sum + value, 0);
                tableHtml += `
                    <tr style="font-weight: bold; background-color: #f5f5f5;">
                        <td>Total</td>
                        <td>₱${total.toLocaleString()}</td>
                    </tr>
                `;
            }
            
            tableHtml += `
                    </tbody>
                </table>
            `;
            
            // Set the content and print
            printSection.innerHTML = tableHtml;
            window.print();
        }
</script>
    
    <?php
    $page_scripts = ob_get_clean();
    
    // Include the layout
    include 'layout.php';
    ?>
</body>
</html>