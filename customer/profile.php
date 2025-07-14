<?php
require_once 'customer_auth.php'; 
require_once '../db_connect.php'; // Include PDO database connection

// Function to format "Member since" date
function formatMemberSince($timestamp) {
    return date('F Y', strtotime($timestamp));
}

// Fetch user data from users_tb
$user_id = $_SESSION['user_id']; // Assuming customer_auth.php sets this
try {
    $query = "SELECT first_name, middle_name, last_name, suffix, username, contact_number, profile_picture, created_at
              FROM users_tb 
              WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Construct full name for display
$fullName = ucwords(trim($user['first_name'] . ' ' . $user['last_name']));

// Set profile picture path
$profilePicture = $user['profile_picture'] ? '../images/profile_pictures/' . $user['profile_picture'] : 
    'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=E8E0D5&color=5D2F0F&bold=true&size=128';
    // Set page title
$page_title = "Profile - Caffè Lilio";

// Capture content
ob_start();
?>




    <!-- Main Content -->
        <header class="mb-10">
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-deep-brown">My Account</h2>
            <p class="font-baskerville mt-2 text-deep-brown/70">Manage your profile</p>
        </header>

        <!-- Notification Placeholder -->
        <div id="notification-area" class="relative"></div>

    <!-- Profile Information Tab -->
<div id="profile-content" class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture Card -->
        <div class="lg:col-span-1">
            <div class="bg-white/90 rounded-2xl p-6 shadow-lg text-center sticky top-28 transition-all duration-300 hover:shadow-xl">
                <div class="relative mx-auto w-40 h-40 group mb-5">
                    <img id="profile-image" src="<?php echo htmlspecialchars($profilePicture); ?>" 
                         alt="Profile" 
                         class="w-full h-full rounded-full border-4 border-white shadow-lg object-cover transition-transform duration-300 group-hover:scale-105">
                    <label for="avatar-upload" class="absolute inset-0 rounded-full bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <i class="fas fa-camera text-2xl text-white mb-1"></i>
                        <span class="text-white font-baskerville text-sm">Change Photo</span>
                        <input type="file" id="avatar-upload" class="hidden" accept="image/*">
                    </label>
                </div>
                
                <h4 class="font-playfair text-2xl font-bold text-deep-brown mb-1"><?php echo htmlspecialchars($fullName); ?></h4>
                <p class="font-baskerville text-sm text-deep-brown/70 mb-4">@<?php echo htmlspecialchars($user['username']); ?></p>
                
                <div class="bg-warm-cream/30 rounded-lg p-3">
                    <p class="font-baskerville text-xs text-deep-brown/80">Member since <?php echo formatMemberSince($user['created_at']); ?></p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-white/90 rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-playfair text-2xl font-bold text-deep-brown">Personal Information</h3>
                    <button type="button" id="edit-profile-btn" class="flex items-center text-accent-brown hover:text-deep-brown transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        <span class="font-baskerville">Edit Profile</span>
                    </button>
                </div>

                <form id="profile-update-form" action="profileAPI/update_profile.php" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">First Name</label>
                            <div class="relative">
                                <input type="text" id="first-name" name="first_name"
                                       value="<?php echo ucwords(strtolower(htmlspecialchars($user['first_name']))); ?>"
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                                       disabled required
                                       pattern="[A-Za-z \-]{2,50}" 
                                       title="Only letters, spaces, and hyphens allowed (2-50 characters)">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-user text-deep-brown/30"></i>
                                </div>
                            </div>
                            <p id="first-name-error" class="text-xs text-red-500 hidden"></p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Middle Name</label>
                            <div class="relative">
                                <input type="text" id="middle-name" name="middle_name"
                                       value="<?php echo ucwords(strtolower(htmlspecialchars($user['middle_name'] ?? ''))); ?>"
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                                       disabled
                                       pattern="[A-Za-zÀ-ÖØ-öø-ÿ \-']{0,50}"
                                       title="Only letters, spaces, hyphens, and apostrophes allowed (max 50 characters)">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-user text-deep-brown/30"></i>
                                </div>
                            </div>
                            <p id="middle-name-error" class="text-xs text-red-500 hidden"></p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Last Name</label>
                            <div class="relative">
                                <input type="text" id="last-name" name="last_name"
                                       value="<?php echo ucwords(strtolower(htmlspecialchars($user['last_name']))); ?>"
                                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all"
                                       disabled required
                                       pattern="[A-Za-zÀ-ÖØ-öø-ÿ \-']{2,50}"
                                       title="Only letters, spaces, hyphens, and apostrophes allowed (2-50 characters)">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-user text-deep-brown/30"></i>
                                </div>
                            </div>
                            <p id="last-name-error" class="text-xs text-red-500 hidden"></p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-deep-brown/80">Suffix</label>
                            <input type="text" id="suffix" name="suffix" value="<?php echo htmlspecialchars($user['suffix'] ?? ''); ?>" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" 
                                   disabled
                                   pattern="[A-Za-z.]{0,10}"
                                   title="Only letters and periods allowed (max 10 characters)">
                            <p id="suffix-error" class="text-xs text-red-500 hidden"></p>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">Username</label>
                        <div class="relative">
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-at text-deep-brown/30"></i>
                            </div>
                        </div>
                        <p id="username-error" class="text-xs text-red-500 hidden"></p>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-deep-brown/80">Phone Number</label>
                        <div class="relative">
                            <input type="tel" id="phone" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" 
                                   class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" disabled required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-phone text-deep-brown/30"></i>
                            </div>
                        </div>
                        <p id="phone-error" class="text-xs text-red-500 hidden"></p>
                    </div>
                    
                    <div class="pt-4 border-t border-warm-cream flex justify-end space-x-3">
                        <button type="button" id="cancel-edit-btn" class="hidden px-5 py-2.5 rounded-lg font-baskerville text-deep-brown hover:bg-warm-cream/50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="save-profile-btn" class="hidden bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

<!-- Change Password Card -->
<div class="bg-white/90 rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
    <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Security Settings</h3>
    
    <form id="password-update-form" action="profileAPI/update_password.php" method="POST" class="space-y-6">
        <div class="space-y-1">
            <label class="block text-sm font-medium text-deep-brown/80">Current Password</label>
            <div class="relative">
                <input type="password" id="current-password" name="current_password" 
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password"></i>
                </div>
            </div>
        </div>
        
        <div class="space-y-1">
            <label class="block text-sm font-medium text-deep-brown/80">New Password</label>
            <div class="relative">
                <input type="password" id="new-password" name="new_password" 
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password"></i>
                </div>
            </div>
            <p class="text-xs text-deep-brown/50 mt-1">Minimum 8 characters, one uppercase letter, and one number</p>
            <ul id="password-requirements" class="text-xs text-deep-brown/70 mt-2 space-y-1">
                <li id="length-check" class="flex items-center"><span class="mr-2">•</span> At least 8 characters</li>
                <li id="uppercase-check" class="flex items-center"><span class="mr-2">•</span> At least one uppercase letter</li>
                <li id="number-check" class="flex items-center"><span class="mr-2">•</span> At least one number</li>
            </ul>
        </div>
        
        <div class="space-y-1">
            <label class="block text-sm font-medium text-deep-brown/80">Confirm New Password</label>
            <div class="relative">
                <input type="password" id="confirm-password" name="confirm_password" 
                       class="w-full px-4 py-3 bg-white border border-warm-cream rounded-lg focus:ring-2 focus:ring-accent-brown focus:border-transparent transition-all" required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye-slash text-deep-brown/30 cursor-pointer toggle-password"></i>
                </div>
            </div>
            <p id="confirm-match" class="text-xs text-deep-brown/50 mt-1 hidden">Passwords must match</p>
        </div>
        
        <div class="pt-4 border-t border-warm-cream text-right">
            <button type="submit" id="submit-button" class="bg-gradient-to-r from-accent-brown to-rich-brown text-white px-6 py-3 rounded-lg font-baskerville hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]" disabled>
                Update Password
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('password-update-form');
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    const submitButton = document.getElementById('submit-button');
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

        // Update visual feedback
        lengthCheck.style.color = isLengthValid ? 'green' : 'inherit';
        uppercaseCheck.style.color = isUppercaseValid ? 'green' : 'inherit';
        numberCheck.style.color = isNumberValid ? 'green' : 'inherit';
        
        confirmMatch.classList.toggle('hidden', password === '' || isMatch);
        confirmMatch.style.color = isMatch ? 'green' : 'red';
        confirmMatch.textContent = isMatch ? 'Passwords match' : 'Passwords do not match';

        // Enable/disable submit button
        submitButton.disabled = !(isLengthValid && isUppercaseValid && isNumberValid && isMatch);
    }

    // Add event listeners for real-time validation
    newPassword.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);

    // Form submission validation
    form.addEventListener('submit', (e) => {
        const password = newPassword.value;
        if (!minLength.test(password) || !hasUppercase.test(password) || !hasNumber.test(password)) {
            e.preventDefault();
            alert('Password must be at least 8 characters long, contain one uppercase letter, and one number.');
        } else if (newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match.');
        }
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.closest('.relative').querySelector('input');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', isPassword);
            icon.classList.toggle('fa-eye-slash', !isPassword);
        });
    });
});
</script>
        </div>
    </div>
</div>

    <!-- Footer (unchanged) -->
    <!-- <footer class="bg-deep-brown text-warm-cream relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-8 left-8 w-32 h-32 border border-warm-cream rounded-full"></div>
            <div class="absolute bottom-12 right-12 w-24 h-24 border border-warm-cream rounded-full"></div>
            <div class="absolute top-1/2 left-1/4 w-2 h-2 bg-warm-cream rounded-full"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-warm-cream rounded-full"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            <div class="py-2">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center space-x-3 mt-4 mb-4">
                        <div>
                            <h2 class="font-playfair font-bold text-2xl tracking-tight">Caffè Lilio</h2>
                            <p class="text-xs tracking-[0.2em] text-warm-cream/80 uppercase font-inter font-light">Ristorante</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Contact
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="space-y-3 font-inter text-sm">
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-map-marker-alt text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">123 Restaurant St., Food District</p>
                            </div>
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-phone text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">+63 912 345 6789</p>
                            </div>
                            <div class="flex items-center space-x-2 group">
                                <i class="fas fa-envelope text-warm-cream/70 w-4"></i>
                                <p class="text-warm-cream/90">info@caffelilio.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Navigate
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <nav class="space-y-2 font-inter text-sm">
                            <a href="#about" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">About Us</a>
                            <a href="#menu" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Our Menu</a>
                            <a href="#reservations" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Reservations</a>
                            <a href="#contact" class="block text-warm-cream/90 hover:text-warm-cream transition-colors">Visit Us</a>
                        </nav>
                    </div>
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Hours
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="space-y-2 font-inter text-sm">
                            <div class="flex justify-between">
                                <span class="text-warm-cream/90">Mon - Fri</span>
                                <span class="text-warm-cream">11AM - 10PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-warm-cream/90">Sat - Sun</span>
                                <span class="text-warm-cream">10AM - 11PM</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <h3 class="font-playfair font-semibold text-lg mb-4 relative">
                            Connect
                            <div class="absolute -bottom-1 left-0 w-6 h-0.5 bg-warm-cream/60"></div>
                        </h3>
                        <div class="flex space-x-3 mb-4">
                            <a href="https://web.facebook.com/caffelilio.ph" target="_blank" 
                               class="w-10 h-10 bg-warm-cream/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-warm-cream/20 hover:bg-warm-cream/20 transition-colors">
                                <i class="fab fa-facebook-f text-warm-cream text-sm"></i>
                            </a>
                            <a href="https://www.instagram.com/caffelilio.ph/" target="_blank" 
                               class="w-10 h-10 bg-warm-cream/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-warm-cream/20 hover:bg-warm-cream/20 transition-colors">
                                <i class="fab fa-instagram text-warm-cream text-sm"></i>
                            </a>
                        </div>
                        <div class="space-y-2">
                            <p class="text-warm-cream/80 text-xs font-inter">Stay updated</p>
                            <div class="flex">
                                <input type="email" placeholder="Email" 
                                       class="flex-1 px-3 py-2 bg-warm-cream/10 border border-warm-cream/20 rounded-l text-sm text-warm-cream placeholder-warm-cream/50 focus:outline-none focus:border-warm-cream/40 backdrop-blur-sm">
                                <button class="px-3 py-2 bg-warm-cream/20 border border-warm-cream/20 border-l-0 rounded-r hover:bg-warm-cream/30 transition-colors backdrop-blur-sm">
                                    <i class="fas fa-arrow-right text-warm-cream text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-warm-cream/10 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <p class="font-inter text-warm-cream/70 text-xs">
                        © 2024 Caffè Lilio Ristorante. All rights reserved.
                    </p>
                    <div class="flex space-x-4 text-xs font-inter">
                        <a href="#privacy" class="text-warm-cream/70 hover:text-warm-cream transition-colors">Privacy</a>
                        <a href="#terms" class="text-warm-cream/70 hover:text-warm-cream transition-colors">Terms</a>
                    </div>
                </div>
            </div>
        </div>
    </footer> -->

<script>
        document.addEventListener('DOMContentLoaded', function() {
            NProgress.start();
            
            // Profile picture upload functionality
            const avatarUpload = document.getElementById('avatar-upload');
            const profileImage = document.getElementById('profile-image');
            
            avatarUpload.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload a JPEG, PNG, GIF, or WEBP image.',
                confirmButtonColor: '#8B4513'
            });
            return;
        }
        
        // Validate file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please upload an image smaller than 2MB.',
                confirmButtonColor: '#8B4513'
            });
            return;
        }
        
        // Create a smaller preview image
        const reader = new FileReader();
        reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    // Create a canvas to resize the image
                    const canvas = document.createElement('canvas');
                    const maxWidth = 300; // Reduced size for preview
                    const maxHeight = 300;
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Use the resized image for preview
                    const resizedDataUrl = canvas.toDataURL(file.type);
                    profileImage.src = resizedDataUrl;
                    
                    // Ask for confirmation with the resized image
                    Swal.fire({
                        title: 'Update Profile Picture?',
                        text: 'Are you sure you want to upload this image as your new profile picture?',
                        imageUrl: resizedDataUrl,
                        imageWidth: width,
                        imageHeight: height,
                        imageAlt: 'Profile picture preview',
                        showCancelButton: true,
                        confirmButtonColor: '#8B4513',
                        cancelButtonColor: '#A0522D',
                        confirmButtonText: 'Yes, upload it!',
                        cancelButtonText: 'No, cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            uploadProfilePicture(file);
                        } else {
                            // Reset to original image if cancelled
                            profileImage.src = '<?php echo htmlspecialchars($profilePicture); ?>';
                            avatarUpload.value = '';
                        }
                    });
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
            
            function uploadProfilePicture(file) {
                NProgress.start();
                const formData = new FormData();
                formData.append('profile_picture', file);
                formData.append('user_id', <?php echo $user_id; ?>);
                
                fetch('profileAPI/upload_profile_picture.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    NProgress.done();
                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Profile Picture Updated',
                            text: 'Your profile picture has been updated successfully!',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#ffffff',
                            color: '#5D2F0F'
                        });
                        
                        // Update profile picture in navigation
                        document.querySelector('#user-menu-button img').src = data.new_image_path + '?t=' + new Date().getTime();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to update profile picture.',
                            confirmButtonColor: '#8B4513'
                        });
                        // Revert to original image
                        profileImage.src = '<?php echo htmlspecialchars($profilePicture); ?>';
                    }
                    avatarUpload.value = '';
                })
                .catch(error => {
                    NProgress.done();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while uploading the profile picture.',
                        confirmButtonColor: '#8B4513'
                    });
                    // Revert to original image
                    profileImage.src = '<?php echo htmlspecialchars($profilePicture); ?>';
                    avatarUpload.value = '';
                });
            }


            // Toggle edit mode for profile form
            const editButton = document.getElementById('edit-profile-btn');
            const saveButton = document.getElementById('save-profile-btn');
            const profileForm = document.getElementById('profile-update-form');
            const profileFormInputs = document.querySelectorAll('#profile-update-form input');

            editButton.addEventListener('click', () => {
                // Enable all input fields
                profileFormInputs.forEach(input => input.disabled = false);
                // Hide Edit button, show Save button
                editButton.classList.add('hidden');
                saveButton.classList.remove('hidden');
            });

            // Profile form submission with SweetAlert confirmation
            profileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Confirm Changes',
                    text: 'Are you sure you want to save these changes to your profile?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#A0522D',
                    confirmButtonText: 'Yes, Save',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        NProgress.start();
                        const formData = new FormData(profileForm);
                        
                        fetch('profileAPI/update_profile.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            NProgress.done();
                            if (data.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Profile Updated',
                                    text: 'Your profile information has been updated successfully!',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    background: '#ffffff',
                                    color: '#5D2F0F'
                                });
                                // Disable inputs again
                                profileFormInputs.forEach(input => input.disabled = true);
                                // Show Edit button, hide Save button
                                editButton.classList.remove('hidden');
                                saveButton.classList.add('hidden');
                                // Update displayed username and full name
                                document.querySelector('.font-baskerville.text-sm.text-deep-brown\\/70').textContent = formData.get('username');
                                const fullName = `${formData.get('first_name')} ${formData.get('middle_name') ? formData.get('middle_name') + ' ' : ''}${formData.get('last_name')} ${formData.get('suffix') || ''}`.trim();
                                document.querySelector('.font-playfair.text-xl.font-bold').textContent = fullName;
                                document.querySelector('#user-menu-button img').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=E8E0D5&color=5D2F0F`;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to update profile.',
                                    confirmButtonColor: '#8B4513'
                                });
                            }
                        })
                        .catch(error => {
                            NProgress.done();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while updating the profile.',
                                confirmButtonColor: '#8B4513'
                            });
                        });
                    }
                });
            });

            // Password form submission
            document.getElementById('password-update-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const newPassword = document.getElementById('new-password').value;
                const confirmPassword = document.getElementById('confirm-password').value;

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'New passwords do not match.',
                        confirmButtonColor: '#8B4513'
                    });
                    return;
                }

                NProgress.start();
                const formData = new FormData(e.target);
                Swal.fire({
                    title: 'Confirm Password Change',
                    text: 'Are you sure you want to update your password?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8B4513',
                    cancelButtonColor: '#A0522D',
                    confirmButtonText: 'Yes, Update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('profileAPI/update_password.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            NProgress.done();
                            if (data.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Password Updated',
                                    text: 'Your password has been updated successfully!',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    background: '#ffffff',
                                    color: '#5D2F0F'
                                });
                                document.getElementById('password-update-form').reset();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to update password.',
                                    confirmButtonColor: '#8B4513'
                                });
                            }
                        })
                        .catch(error => {
                            NProgress.done();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while updating the password.',
                                confirmButtonColor: '#8B4513'
                            });
                        });
                    }
                });
            });

            // Mobile menu toggle
            // const mobileMenuButton = document.getElementById('mobile-menu-button');
            // const mobileMenu = document.getElementById('mobile-menu');
            // const closeMobileMenu = document.getElementById('close-mobile-menu');

            // mobileMenuButton.addEventListener('click', () => {
            //     mobileMenu.classList.toggle('hidden');
            // });

            // closeMobileMenu.addEventListener('click', () => {
            //     mobileMenu.classList.add('hidden');
            // });

            // User menu dropdown
            // const userMenuButton = document.getElementById('user-menu-button');
            // const userMenu = document.querySelector('#user-menu-button + .absolute');
            // const menuChevron = userMenuButton.querySelector('i.fas.fa-chevron-down');

            // userMenuButton.addEventListener('click', () => {
            //     const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';
            //     userMenuButton.setAttribute('aria-expanded', !isExpanded);
            //     userMenu.classList.toggle('hidden');
            //     userMenu.classList.toggle('opacity-0');
            //     userMenu.classList.toggle('scale-95');
            //     menuChevron.classList.toggle('rotate-180');
            // });

            // document.addEventListener('click', (event) => {
            //     if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
            //         userMenuButton.setAttribute('aria-expanded', 'false');
            //         userMenu.classList.add('hidden', 'opacity-0', 'scale-95');
            //         menuChevron.classList.remove('rotate-180');
            //     }
            // });

            tippy('[data-tippy-content]', {
                animation: 'scale-subtle',
                theme: 'light-border',
            });

            window.onload = function() {
                NProgress.done();
            };
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

    // Validation Patterns
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

    // Enable edit mode
    editButton.addEventListener('click', () => {
        profileFormInputs.forEach(input => {
            input.disabled = false;
            // Trigger validation when enabling fields
            input.addEventListener('input', validateField);
        });
        editButton.classList.add('hidden');
        saveButton.classList.remove('hidden');
    });

    // Field Validation Function
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

    // Check if field value is already taken
    function checkFieldAvailability(fieldName, value, callback) {
        if (!value) {
            callback(true); // Empty value is considered available
            return;
        }

        fetch('profileAPI/check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                field: fieldName,
                value: value,
                current_user_id: <?php echo $user_id; ?>
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

    // Validate entire form
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
});
</script>

<?php
// Capture content and include layout
$content = ob_get_clean();
include 'layout_customer.php';
?>