<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Start output buffering to catch potential output issues
ob_start();

try {
    // Include required files
    if (!file_exists('customer_auth.php') || !file_exists('../db_connect.php')) {
        throw new Exception('Required file(s) not found: customer_auth.php or db_connect.php');
    }
    require_once 'customer_auth.php';
    require_once '../db_connect.php';

    // Check if session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verify user session
    if (!isset($_SESSION['user_id'])) {
        error_log('Session user_id not set. Redirecting to login.');
        header('Location: login.php'); // Adjust to your login page
        exit;
    }

    // Fetch bookings for the logged-in user using PDO
    $user_id = $_SESSION['user_id'];
    if (!isset($conn) || !$conn instanceof PDO) {
        throw new Exception('Invalid PDO database connection');
    }

    $query = "SELECT booking_id, reservation_datetime, event, pax, booking_status 
          FROM booking_tb 
          WHERE customer_id = :user_id 
          AND booking_status IN ('pending', 'accepted') 
          ORDER BY reservation_datetime ASC";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Query preparation failed: ' . implode(' | ', $conn->errorInfo()));
    }
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    if (!$stmt->execute()) {
        throw new Exception('Query execution failed: ' . implode(' | ', $stmt->errorInfo()));
    }
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} catch (Exception $e) {
    // Log the error
    error_log('Error in my_reservations.php: ' . $e->getMessage());
    
    // Display user-friendly error
    http_response_code(500);
    echo '<!DOCTYPE html><html><body><h1>Error</h1><p>An error occurred: ' . htmlspecialchars($e->getMessage()) . '</p><p>Please try again later or contact support.</p></body></html>';
    ob_end_flush();
    exit;
}

// Flush output buffer
ob_end_flush();

// Set page title
$page_title = "My Reservations - Caffè Lilio";

// Capture content
ob_start();
?>


<style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }
        
        .hover-lift {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
        }

        .bg-warm-gradient {
            background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
        }

        .bg-card {
            background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
            backdrop-filter: blur(8px);
        }

        .bg-nav {
            background: linear-gradient(to bottom, #5D2F0F, #4a260d);
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        :focus {
            outline: 2px solid #8B4513;
            outline-offset: 2px;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
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

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            background: #E8E0D5;
            box-shadow: 0 4px 12px rgba(93, 47, 15, 0.15);
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            background: #8B4513;
            color: #E8E0D5;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(232, 224, 213, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:active::after {
            width: 200%;
            height: 200%;
        }

        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        .skeleton-text {
            height: 1em;
            background: #e0e0e0;
            margin: 0.5em 0;
            border-radius: 4px;
        }

        input, select, textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }

        .otp-input {
            width: 3rem;
            height: 3rem;
            text-align: center;
            font-size: 1.25rem;
            border: 1px solid #8B4513;
            border-radius: 0.375rem;
        }

        .otp-input:focus {
            outline: none;
            border-color: #5D2F0F;
            box-shadow: 0 0 0 2px rgba(93, 47, 15, 0.2);
        }
    </style>

    <!-- Loading Progress Bar -->
    <div id="nprogress-container"></div>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
        <!-- Upcoming Reservations -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown">Upcoming Reservations</h3>
                <a href="bookingpage.php" class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2">
                    <span>Make New Reservation</span>
                    <i class="fas fa-calendar-plus"></i>
                </a>
            </div>
            <div class="bg-white/50 rounded-xl p-6 shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full" role="table">
                        <thead>
                            <tr class="border-b border-deep-brown/20">
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Date & Time</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Event Type</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Guests</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Status</th>
                                <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="5" class="py-4 px-4 text-center font-baskerville text-deep-brown">
                                        No upcoming reservations found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr class="border-b border-deep-brown/10 hover:bg-deep-brown/5 transition-colors duration-300">
                                        <td class="py-4 px-4">
                                            <div class="font-baskerville text-deep-brown">
                                                <?php echo date('F d, Y', strtotime($booking['reservation_datetime'])); ?>
                                            </div>
                                            <div class="text-sm text-deep-brown/60">
                                                <?php echo date('h:i A', strtotime($booking['reservation_datetime'])); ?>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-baskerville text-deep-brown">
                                            <?php echo htmlspecialchars($booking['event'] ?? 'Not Specified'); ?>
                                        </td>
                                        <td class="py-4 px-4 font-baskerville text-deep-brown">
                                            <?php echo $booking['pax']; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center border border-deep-brown/10
                                                <?php echo $booking['booking_status'] === 'accepted' ? 'bg-warm-cream/50 text-deep-brown' : 'bg-yellow-100/50 text-yellow-800'; ?>">
                                                <span class="w-2 h-2 rounded-full mr-2 
                                                    <?php echo $booking['booking_status'] === 'accepted' ? 'bg-green-500' : 'bg-yellow-500'; ?>">
                                                </span>
                                                <?php echo ucfirst($booking['booking_status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex space-x-2">
                                                <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-red-50 cancel-reservation"
                                                        data-booking-id="<?php echo $booking['booking_id']; ?>"
                                                        data-tippy-content="Cancel reservation">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            tippy('[data-tippy-content]', {
                theme: 'custom',
                animation: 'scale',
                duration: [200, 150],
                placement: 'bottom'
            });

            // Initialize loading bar
            NProgress.configure({ 
                showSpinner: false,
                minimum: 0.3,
                easing: 'ease',
                speed: 500
            });

            // Mobile menu functionality
            // const mobileMenu = document.getElementById('mobile-menu');
            // const mobileMenuButton = document.getElementById('mobile-menu-button');
            // const closeMobileMenu = document.getElementById('close-mobile-menu');

            // function toggleMobileMenu() {
            //     mobileMenu.classList.toggle('open');
            //     document.body.classList.toggle('overflow-hidden');
            // }

            // mobileMenuButton.addEventListener('click', toggleMobileMenu);
            // closeMobileMenu.addEventListener('click', toggleMobileMenu);

            // Handle regular links
            document.querySelectorAll('a[href$=".php"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    NProgress.start();
                });
            });

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.getElementById('toast-container').appendChild(toast);
                
                setTimeout(() => toast.classList.add('show'), 100);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Handle reservation cancellation
            // Handle reservation cancellation
document.querySelectorAll('.cancel-reservation').forEach(button => {
    button.addEventListener('click', function() {
        const bookingId = this.getAttribute('data-booking-id');
        
        // Initial confirmation dialog
        const confirmDialog = document.createElement('div');
        confirmDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        confirmDialog.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Cancel Reservation?</h3>
                <p class="text-deep-brown/80 mb-6">Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                <div class="flex justify-end space-x-4">
                    <button class="px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300" id="cancel-dialog">
                        Keep Reservation
                    </button>
                    <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors duration-300" id="confirm-cancel">
                        Yes, Cancel
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(confirmDialog);
        document.body.classList.add('overflow-hidden');

        document.getElementById('cancel-dialog').addEventListener('click', () => {
            confirmDialog.remove();
            document.body.classList.remove('overflow-hidden');
        });

        document.getElementById('confirm-cancel').addEventListener('click', () => {
            confirmDialog.remove();
            NProgress.start();

            // Request OTP
            fetch('reservationsAPI/otp_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=generate_otp&booking_id=${bookingId}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                NProgress.done();
                if (!data.success) {
                    showToast(data.message || 'Failed to send OTP', 'error');
                    document.body.classList.remove('overflow-hidden');
                    return;
                }

                // Show OTP input modal
                const otpDialog = document.createElement('div');
                otpDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                otpDialog.innerHTML = `
                    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                        <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Enter OTP</h3>
                        <p class="text-deep-brown/80 mb-4">An OTP has been sent to your registered phone number.</p>
                        <form id="otp-form">
                            <div class="flex justify-center space-x-2 mb-6">
                                <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                            </div>
                            <p class="text-deep-brown/80 mb-4 text-sm">Didn't receive OTP? <a href="#" id="resend-otp" class="text-rich-brown hover:underline">Resend OTP</a></p>
                            <div class="flex justify-end space-x-4">
                                <button type="button" class="px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300" id="cancel-otp">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition-colors duration-300" id="verify-otp">
                                    Verify OTP
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                
                document.body.appendChild(otpDialog);

                // Handle OTP input
                const otpInputs = otpDialog.querySelectorAll('.otp-input');
                otpInputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    });
                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace' && !e.target.value && index > 0) {
                            otpInputs[index - 1].focus();
                        }
                    });
                });

                // Cancel OTP dialog
                document.getElementById('cancel-otp').addEventListener('click', () => {
                    otpDialog.remove();
                    document.body.classList.remove('overflow-hidden');
                });

                // Resend OTP
                document.getElementById('resend-otp').addEventListener('click', (e) => {
                    e.preventDefault();
                    NProgress.start();
                    fetch('reservationsAPI/otp_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=generate_otp&booking_id=${bookingId}`
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        NProgress.done();
                        if (data.success) {
                            showToast('New OTP has been sent to your phone', 'success');
                            // Clear existing OTP inputs
                            otpInputs.forEach(input => input.value = '');
                            otpInputs[0].focus();
                        } else {
                            showToast(data.message || 'Failed to resend OTP', 'error');
                        }
                    })
                    .catch(error => {
                        NProgress.done();
                        showToast('Error resending OTP: ' + error.message, 'error');
                    });
                });
                
                // Verify OTP
                document.getElementById('otp-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    const otp = Array.from(otpInputs).map(input => input.value).join('');
                    if (otp.length !== 6) {
                        showToast('Please enter a complete 6-digit OTP', 'error');
                        return;
                    }
                
                    NProgress.start();
                    fetch('reservationsAPI/otp_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=verify_otp&otp=${otp}`
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        NProgress.done();
                        if (data.success) {
                            // Show success SweetAlert with countdown
                            let timerInterval;
                            Swal.fire({
                                title: 'Success!',
                                text: 'Reservation cancelled successfully',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                willClose: () => {
                                    clearInterval(timerInterval);
                                    otpDialog.remove();
                                    document.body.classList.remove('overflow-hidden');
                                    window.location.reload();
                                }
                            });
                        } else {
                            // Show error SweetAlert
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Invalid OTP. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            }).then(() => {
                                // Clear OTP inputs and focus on first input
                                otpInputs.forEach(input => input.value = '');
                                otpInputs[0].focus();
                            });
                        }
                    })
                    .catch(error => {
                        NProgress.done();
                        showToast('Error verifying OTP: ' + error.message, 'error');
                    });
                });


            })
            .catch(error => {
                document.body.classList.remove('overflow-hidden');
                NProgress.done();
                showToast('Error sending OTP: ' + error.message, 'error');
            });
        });
    });
});

            // Load notifications
            function loadNotifications() {
                const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
                setTimeout(() => {
                    notificationsContainer.innerHTML = `
                        <div class="p-4 border-b border-deep-brown/10">
                            <p class="font-baskerville text-deep-brown">New special offer available!</p>
                            <p class="text-sm text-deep-brown/60">Check out our weekend buffet special.</p>
                        </div>
                    `;
                }, 1000);
            }

            loadNotifications();
        });
    </script>
<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>