<?php
require_once 'admin_auth.php';
require_once '../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Function to format "Member since" date
function formatMemberSince($timestamp) {
    return date('F Y', strtotime($timestamp));
}

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
            
            // Check if image file is an actual image
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
            
            // Validate phone number format
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

// Capture page content
ob_start();
?>

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


<style>
    .custom-alert {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px;
    border-radius: 8px;
    z-index: 1000;
    animation: fadeIn 0.3s ease forwards;
}
.custom-alert.success {
    background-color: #22c55e;
    color: white;
}
.custom-alert.error {
    background-color: #ef4444;
    color: white;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
}
</style>


<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Photo Section -->
        <div class="lg:col-span-1">
            <div class="bg-white/95 rounded-3xl p-10 shadow-xl transition-all duration-500 hover:shadow-2xl slide-in">
                <h3 class="font-playfair text-3xl font-bold text-deep-brown mb-8 text-center tracking-tight">Profile Photo</h3>
                
                <div class="flex flex-col items-center space-y-6">
                    <div class="photo-container relative group">
                        <?php 
                        $photoPath = !empty($user['profile_picture']) 
                            ? '../images/profile_pictures/' . htmlspecialchars($user['profile_picture'])
                            : 'https://via.placeholder.com/120x120/D2B48C/FFFFFF?text=Admin';
                        ?>
                        <img id="profile-photo" src="<?php echo $photoPath; ?>" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-accent-brown shadow-md group-hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                             <div class="photo-upload-overlay absolute inset-0 rounded-full bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                                <i class="fas fa-camera text-white text-2xl transform group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="text-white text-sm mt-1">Change Profile</span>
                            </div>

                        <input type="file" id="photo-upload" accept="image/*" class="hidden">
                    </div>
                    
                    <div class="text-center space-y-2">
                        <h4 class="font-baskerville text-2xl font-semibold text-deep-brown tracking-wide">Administrator</h4>
                        <p class="text-accent-brown text-sm font-medium">Member since <?php echo formatMemberSince($user['created_at']); ?></p>
                    </div>
                    
                    <!-- <button id="change-photo-btn" class="bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-full font-baskerville text-base font-medium hover:shadow-xl hover:bg-gradient-to-r hover:from-rich-brown hover:to-accent-brown transition-all duration-300 transform hover:scale-105 focus:ring-2 focus:ring-accent-brown focus:ring-offset-2">
                        Change Photo
                    </button> -->
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
                            <label class="block text-sm font-medium text-deep-brown/选">Middle Name</label>
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
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"  required>
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
                                    required
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
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password">
                                <i class="fas fa-eye-slash text-deep-brown/30 hover:text-deep-brown transition-colors"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">New Password</label>
                        <div class="relative">
                            <input type="password" id="new-password" name="new_password" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password">
                                <i class="fas fa-eye-slash text-deep-brown/30 hover:text-deep-brown transition-colors"></i>
                            </button>
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
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password">
                                <i class="fas fa-eye-slash text-deep-brown/30 hover:text-deep-brown transition-colors"></i>
                            </button>
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
    
    
<!-- GCash QR Section -->
<div class="mt-8">
    <div class="bg-white/90 rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl slide-in">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-playfair text-2xl font-bold text-deep-brown">GCash QR Codes</h3>
            <button type="button" id="add-gcash-btn" class="flex items-center text-accent-brown hover:text-deep-brown transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                <span class="font-baskerville">Add GCash QR</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gcash-qr-container">
            <?php
            // Fetch existing GCash QR codes
            try {
                $stmt = $conn->query("SELECT * FROM gcash_qr ORDER BY created_at DESC");
                $gcashQrs = $stmt->fetchAll();
                
                if (empty($gcashQrs)) {
                    echo '<p class="text-deep-brown/70 col-span-full text-center py-8">No GCash QR codes found.</p>';
                } else {
                    foreach ($gcashQrs as $qr) {
                        echo '
                        <div class="bg-white/95 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-lg">
                            <div class="flex flex-col items-center">
                                <div class="mb-4 w-full flex justify-center">
                                    <img src="../images/gcash_qr/'.htmlspecialchars($qr['qr_image']).'" 
                                         class="w-48 h-48 object-contain cursor-pointer hover:scale-105 transition-transform duration-300 qr-image"
                                         data-number="'.htmlspecialchars($qr['gcash_number']).'">
                                </div>
                                <div class="text-center w-full">
                                    <p class="text-deep-brown font-medium mb-2">'.htmlspecialchars($qr['gcash_number']).'</p>
                                    <button class="delete-qr text-red-500 hover:text-red-700 text-sm font-baskerville transition-colors duration-200" data-id="'.$qr['id'].'">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>';
                    }
                }
            } catch (PDOException $e) {
                echo '<p class="text-red-500 col-span-full text-center py-8">Error loading GCash QR codes: '.htmlspecialchars($e->getMessage()).'</p>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Add GCash QR Modal -->
<div id="gcash-modal" class="fixed inset-0 bg-black/50 z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 animate-fade-in transform transition-all duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-playfair text-xl font-bold text-deep-brown">Add GCash QR</h3>
            <button id="close-gcash-modal" class="text-deep-brown/50 hover:text-deep-brown transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="gcash-qr-form" enctype="multipart/form-data">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-deep-brown/80 mb-1">GCash Number</label>
                    <input type="text" name="gcash_number" id="gcash-number" 
                           class="w-full px-4 py-2 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                           placeholder="09XXXXXXXXX" required
                           pattern="09[0-9]{9}"
                           maxlength="11">
                    <p class="text-xs text-deep-brown/50 mt-1">Must be 11 digits starting with 09</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-deep-brown/80 mb-1">QR Code Image</label>
                    <div class="border-2 border-dashed border-warm-cream rounded-lg p-4 text-center transition-colors duration-300 hover:border-accent-brown">
                        <input type="file" name="qr_image" id="qr-image" 
                               class="hidden" accept="image/*" required>
                        <div id="qr-upload-area" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-accent-brown mb-2 transition-transform duration-300 hover:scale-110"></i>
                            <p class="text-sm text-deep-brown/70">Click to upload QR image</p>
                            <p class="text-xs text-deep-brown/50 mt-1">Max 2MB (JPG, PNG, GIF)</p>
                        </div>
                        <div id="qr-preview" class="hidden mt-4">
                            <img id="qr-preview-image" class="mx-auto max-h-40 rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancel-gcash-btn" class="px-4 py-2 rounded-lg font-baskerville text-deep-brown hover:bg-warm-cream/50 transition-all duration-300">
                    Cancel
                </button>
                <button type="submit" class="bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-2 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                    Save QR Code
                </button>
            </div>
        </form>
    </div>
</div>

<!-- QR Image Modal -->
<div id="qr-modal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-2xl w-full">
        <button id="close-qr-modal" class="absolute -top-10 right-0 text-white hover:text-accent-brown z-[60]">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <div class="bg-white p-4 rounded-lg flex flex-col items-center">
            <img id="modal-qr-image" class="max-w-full max-h-[80vh] object-contain">
            <div class="mt-2 text-center text-deep-brown font-medium" id="modal-qr-number"></div>
        </div>
    </div>
</div>
    
    
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Profile Photo Upload
    const photoUpload = document.getElementById('photo-upload');
    const profilePhoto = document.getElementById('profile-photo');
    // const changePhotoBtn = document.getElementById('change-photo-btn');
    const photoContainer = document.querySelector('.photo-container');

    // Trigger file input when clicking change photo button or overlay
    // changePhotoBtn.addEventListener('click', () => {
    //     photoUpload.click();
    // });

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
                        const headerProfilePic = document.getElementById('header-profile-pic');
                        if (headerProfilePic) {
                            headerProfilePic.src = e.target.result;
                        }
                        
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
                        return false;
                    }
                    if (!/^09[0-9]{9}$/.test(this.value)) {
                        this.classList.add('border-red-500');
                        document.getElementById('phone-error').textContent = 'Phone number must be 11 digits starting with 09 (e.g., 09123456789).';
                        document.getElementById('phone-error').classList.remove('hidden');
                        return false;
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
            input.classList.remove('border-red-500');
            const errorElement = document.getElementById(`${input.id}-error`);
            if (errorElement) errorElement.classList.add('hidden');
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
        
        // Validate phone number format
        if (!/^09[0-9]{9}$/.test(phone)) {
            errors.push("Phone number must be a valid Philippine number (11 digits starting with 09).");
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

    // Improved Password Toggle Functionality
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const input = button.closest('.relative').querySelector('input');
            const icon = button.querySelector('i');
            const isPassword = input.type === 'password';
            
            // Toggle input type
            input.type = isPassword ? 'text' : 'password';
            
            // Toggle icon classes
            icon.classList.toggle('fa-eye-slash', !isPassword);
            icon.classList.toggle('fa-eye', isPassword);
            
            // Change icon color for better visibility
            icon.classList.toggle('text-deep-brown/30', isPassword);
            icon.classList.toggle('text-deep-brown', !isPassword);
            
            // Ensure focus remains on input
            input.focus();
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


// Helper function to capitalize the first letter of each word
function capitalizeWords(str) {
    if (!str) return str; // Return empty string if input is empty
    return str
        .toLowerCase() // Convert to lowercase first
        .split(' ') // Split by spaces
        .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize first letter
        .join(' '); // Join back with spaces
}

// Real-time Form Validation
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const editButton = document.getElementById('edit-profile-btn');
    const saveButton = document.getElementById('save-profile-btn');
    const profileForm = document.getElementById('profile-update-form');
    const profileFormInputs = document.querySelectorAll('#profile-update-form input');

    // Validation Patterns (from customer side)
    const patterns = {
        name: /^[A-Za-zÀ-ÖØ-öø-ÿ \-']{2,50}$/,
        optionalName: /^[A-Za-zÀ-ÖØ-öø-ÿ \-']{0,50}$/,
        suffix: /^[A-Za-z.]{0,10}$/,
        username: /^[A-Za-z][A-Za-z0-9_.]{3,29}$/,
        phone: /^(0[0-9]{10}|\+63[0-9]{10})$/
    };

    // Track availability status
    let isPhoneAvailable = true; // Assume true initially
    let isUsernameAvailable = true; // Assume true initially

    // Enable edit mode with validation
    editButton.addEventListener('click', () => {
        profileFormInputs.forEach(input => {
            input.disabled = false;
            // Trigger validation when enabling fields
            input.addEventListener('input', validateField);
        });
        editButton.classList.add('hidden');
        saveButton.classList.remove('hidden');
    });

    // Field Validation Function (from customer side)
    function validateField(e) {
        const field = e.target;
        const fieldId = field.id;
        const errorElement = document.getElementById(`${fieldId}-error`);
        let isValid = true;
        let errorMessage = '';

        // Capitalize first letter for name fields
        if (['first-name', 'middle-name', 'last-name'].includes(fieldId)) {
            field.value = capitalizeWords(field.value.trim());
        } else {
            // Trim whitespace for other fields
            field.value = field.value.trim();
        }

        switch(fieldId) {
            case 'first-name':
            case 'last-name':
                if (!patterns.name.test(field.value)) {
                    errorMessage = 'Only letters, spaces, hyphens, and apostrophes allowed (2-50 characters)';
                    isValid = false;
                }
                break;
                
            case 'middle-name':
                if (field.value && !patterns.optionalName.test(field.value)) {
                    errorMessage = 'Only letters, spaces, hyphens, and apostrophes allowed (max 50 characters)';
                    isValid = false;
                }
                break;
                
            case 'suffix':
                if (field.value && !patterns.suffix.test(field.value)) {
                    errorMessage = 'Only letters and periods allowed (max 10 characters)';
                    isValid = false;
                }
                break;
                
            case 'username':
                if (!patterns.username.test(field.value)) {
                    errorMessage = '4-30 chars, start with letter, only letters, numbers, _ and .';
                    isValid = false;
                    isUsernameAvailable = false; // Reset availability
                } else {
                    // Check username availability
                    checkFieldAvailability('username', field.value, (available) => {
                        isUsernameAvailable = available;
                        validateForm(); // Re-validate form after availability check
                    });
                }
                break;
                
            case 'phone':
                if (!patterns.phone.test(field.value)) {
                    errorMessage = 'Must be exactly 11 digits starting with 0 or 12 digits starting with +63';
                    isValid = false;
                    isPhoneAvailable = false; // Reset availability
                } else {
                    // Check phone availability
                    checkFieldAvailability('contact_number', field.value, (available) => {
                        isPhoneAvailable = available;
                        validateForm(); // Re-validate form after availability check
                    });
                }
                break;
        }

        // Update UI
        if (isValid) {
            field.classList.remove('border-red-500');
            field.classList.add('border-warm-cream');
            errorElement.classList.add('hidden');
        } else {
            field.classList.remove('border-warm-cream');
            field.classList.add('border-red-500');
            errorElement.textContent = errorMessage;
            errorElement.classList.remove('hidden');
        }

        // Validate form to update save button state
        validateForm();
    }

    // Check if field value is already taken (from customer side)
    function checkFieldAvailability(fieldName, value, callback) {
        if (!value) {
            callback(true); // Empty value is considered available
            return;
        }

        fetch('../customer/profileAPI/check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                field: fieldName,
                value: value,
                current_user_id: <?php echo $_SESSION['user_id']; ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            const fieldId = fieldName === 'contact_number' ? 'phone' : fieldName;
            const errorElement = document.getElementById(`${fieldId}-error`);
            
            if (!data.available) {
                document.getElementById(fieldId).classList.add('border-red-500');
                errorElement.textContent = `This ${fieldName.replace('_', ' ')} is already in use`;
                errorElement.classList.remove('hidden');
                callback(false);
            } else {
                // Only hide error if no other validation error exists
                if (!errorElement.textContent.includes('allowed') && !errorElement.textContent.includes('chars') && !errorElement.textContent.includes('digits')) {
                    errorElement.classList.add('hidden');
                }
                document.getElementById(fieldId).classList.remove('border-red-500');
                document.getElementById(fieldId).classList.add('border-warm-cream');
                callback(true);
            }
        })
        .catch(error => {
            console.error('Availability check failed:', error);
            callback(false); // Assume unavailable on error
        });
    }

    // Validate entire form (from customer side)
    function validateForm() {
        let isFormValid = true;
        
        profileFormInputs.forEach(input => {
            if (input.required && !input.value.trim()) {
                isFormValid = false;
            }
            
            if (input.id === 'first-name' || input.id === 'last-name') {
                if (!patterns.name.test(input.value)) {
                    isFormValid = false;
                }
            }
            
            if (input.id === 'middle-name' && input.value && !patterns.optionalName.test(input.value)) {
                isFormValid = false;
            }
            
            if (input.id === 'username') {
                if (!patterns.username.test(input.value) || !isUsernameAvailable) {
                    isFormValid = false;
                }
            }
            
            if (input.id === 'phone') {
                if (!patterns.phone.test(input.value) || !isPhoneAvailable) {
                    isFormValid = false;
                }
            }
            
            if (input.id === 'suffix' && input.value && !patterns.suffix.test(input.value)) {
                isFormValid = false;
            }
            
            // Check for visible error messages
            const errorElement = document.getElementById(`${input.id}-error`);
            if (errorElement && !errorElement.classList.contains('hidden')) {
                isFormValid = false;
            }
        });
        
        saveButton.disabled = !isFormValid;
    }
    
    // Cancel button functionality
    const cancelEditBtn = document.getElementById('cancel-edit-btn');
    cancelEditBtn.addEventListener('click', () => {
        profileFormInputs.forEach(input => {
            input.disabled = true;
            input.classList.remove('border-red-500');
            const errorElement = document.getElementById(`${input.id}-error`);
            if (errorElement) errorElement.classList.add('hidden');
        });
        editButton.classList.remove('hidden');
        cancelEditBtn.classList.add('hidden');
        saveButton.classList.add('hidden');
    });
});


// GCash QR Functionality
document.addEventListener('DOMContentLoaded', () => {
    // Helper function to show alerts for GCash QR section
    function showGcashAlert(message, type) {
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

    // GCash QR Functionality
    const gcashModal = document.getElementById('gcash-modal');
    const addGcashBtn = document.getElementById('add-gcash-btn');
    const closeGcashModal = document.getElementById('close-gcash-modal');
    const cancelGcashBtn = document.getElementById('cancel-gcash-btn');
    const gcashForm = document.getElementById('gcash-qr-form');
    const qrUploadArea = document.getElementById('qr-upload-area');
    const qrImageInput = document.getElementById('qr-image');
    const qrPreview = document.getElementById('qr-preview');
    const qrPreviewImage = document.getElementById('qr-preview-image');
    const qrModal = document.getElementById('qr-modal');
    const modalQrImage = document.getElementById('modal-qr-image');
    const modalQrNumber = document.getElementById('modal-qr-number');
    const closeQrModal = document.getElementById('close-qr-modal');
    const gcashContainer = document.getElementById('gcash-qr-container');

    // Show add GCash modal
    addGcashBtn.addEventListener('click', () => {
        gcashModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    });

    // Close add GCash modal
    function closeGcashModalFunc() {
        gcashModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        gcashForm.reset();
        qrPreview.classList.add('hidden');
        qrUploadArea.classList.remove('hidden');
        qrPreviewImage.src = '';
    }

    closeGcashModal.addEventListener('click', closeGcashModalFunc);
    cancelGcashBtn.addEventListener('click', closeGcashModalFunc);

    // Handle QR image upload preview
    qrUploadArea.addEventListener('click', () => {
        qrImageInput.click();
    });

    qrImageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            // Validate file
            if (file.size > 2000000) { // 2MB
                showGcashAlert('File is too large. Max 2MB allowed.', 'error');
                return;
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showGcashAlert('Only JPG, PNG, and GIF files are allowed.', 'error');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = (event) => {
                qrPreviewImage.src = event.target.result;
                qrPreview.classList.remove('hidden');
                qrUploadArea.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle GCash form submission
    gcashForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate GCash number
        const gcashNumber = document.getElementById('gcash-number').value;
        if (!/^09[0-9]{9}$/.test(gcashNumber)) {
            showGcashAlert('GCash number must be 11 digits starting with 09.', 'error');
            return;
        }

        // Validate image
        if (!qrImageInput.files || qrImageInput.files.length === 0) {
            showGcashAlert('Please upload a QR code image.', 'error');
            return;
        }

        // Show confirmation dialog
        const { isConfirmed } = await Swal.fire({
            title: 'Add GCash QR',
            text: 'Are you sure you want to add this GCash QR code?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#A0522D',
            cancelButtonColor: '#6B3410',
            confirmButtonText: 'Yes, add it!',
            cancelButtonText: 'No, cancel'
        });
        
        if (!isConfirmed) {
            return;
        }

        const formData = new FormData(gcashForm);
        const submitBtn = gcashForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        try {
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            submitBtn.disabled = true;

            const response = await fetch('admin_handle_gcash.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            if (data.success) {
                showGcashAlert('GCash QR added successfully!', 'success');
                closeGcashModalFunc();
                setTimeout(() => {
                    console.log('Reloading page after successful QR addition');
                    window.location.reload();
                }, 2000); // Ensure toast is visible
            } else {
                showGcashAlert(data.message || 'Error adding GCash QR', 'error');
            }
        } catch (error) {
            console.error('Add QR Error:', error);
            showGcashAlert('An error occurred while adding GCash QR. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });

    // Show QR in modal when clicked
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('qr-image')) {
            modalQrImage.src = e.target.src;
            modalQrNumber.textContent = e.target.dataset.number;
            qrModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    });

    // Close QR modal
    closeQrModal.addEventListener('click', () => {
        qrModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    });

    // Delete QR code
    document.addEventListener('click', async (e) => {
        if (e.target.classList.contains('delete-qr') || e.target.closest('.delete-qr')) {
            const button = e.target.classList.contains('delete-qr') ? e.target : e.target.closest('.delete-qr');
            const id = button.dataset.id;
            
            const { isConfirmed } = await Swal.fire({
                title: 'Delete QR Code',
                text: 'Are you sure you want to delete this QR code? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#A0522D',
                cancelButtonColor: '#6B3410',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            });
            
            if (!isConfirmed) return;
            
            try {
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...';
                button.disabled = true;

                const response = await fetch('admin_handle_gcash.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=delete&id=${id}`
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                
                if (data.success) {
                    showGcashAlert('GCash QR deleted successfully!', 'success');
                    // Remove the QR card from the UI
                    button.closest('.bg-white\\/95').remove();
                    // If no more QRs, show message
                    if (document.querySelectorAll('#gcash-qr-container .bg-white\\/95').length === 0) {
                        gcashContainer.innerHTML = '<p class="text-deep-brown/70 col-span-full text-center py-8">No GCash QR codes found.</p>';
                    }
                    setTimeout(() => {
                        console.log('Reloading page after successful QR deletion');
                        window.location.reload();
                    }, 2000); // Ensure toast is visible
                } else {
                    showGcashAlert(data.message || 'Error deleting GCash QR', 'error');
                    button.innerHTML = '<i class="fas fa-trash mr-1"></i> Delete';
                    button.disabled = false;
                }
            } catch (error) {
                console.error('Delete QR Error:', error);
                showGcashAlert('An error occurred while deleting GCash QR. Please try again.', 'error');
                button.innerHTML = '<i class="fas fa-trash mr-1"></i> Delete';
                button.disabled = false;
            }
        }
    });

    // Function to fetch and update GCash QR list
    async function fetchGCashQRs() {
        try {
            const response = await fetch('admin_handle_gcash.php?action=fetch');
            const data = await response.json();
            
            if (data.success) {
                if (data.qrCodes.length === 0) {
                    gcashContainer.innerHTML = '<p class="text-deep-brown/70 col-span-full text-center py-8">No GCash QR codes found.</p>';
                } else {
                    let html = '';
                    data.qrCodes.forEach(qr => {
                        html += `
                        <div class="bg-white/95 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-lg">
                            <div class="flex flex-col items-center">
                                <div class="mb-4 w-full flex justify-center">
                                    <img src="../images/gcash_qr/${qr.qr_image}" 
                                         class="w-48 h-48 object-contain cursor-pointer hover:scale-105 transition-transform duration-300 qr-image"
                                         data-number="${qr.gcash_number}">
                                </div>
                                <div class="text-center w-full">
                                    <p class="text-deep-brown font-medium mb-2">${qr.gcash_number}</p>
                                    <button class="delete-qr text-red-500 hover:text-red-700 text-sm font-baskerville transition-colors duration-200" data-id="${qr.id}">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>`;
                    });
                    gcashContainer.innerHTML = html;
                }
            } else {
                showGcashAlert(data.message || 'Error loading GCash QR codes', 'error');
            }
        } catch (error) {
            console.error('Fetch QR Error:', error);
            showGcashAlert('An error occurred while loading GCash QR codes.', 'error');
        }
    }
});
</script>


<?php
$page_content = ob_get_clean();
$page_scripts = ''; // No additional scripts needed since they're included above
include 'layout.php';
?>