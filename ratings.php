<?php
// Start output buffering to prevent unwanted output
ob_start();

// Include database connection
require_once 'db_connect.php';

// Enable error reporting for debugging (log errors, don't display)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
error_reporting(E_ALL);

// Include customer authentication (optional for non-logged-in users)
session_name('CUSTOMER_SESSION');
session_start();
$user_id = isset($_SESSION['user_id']) && $_SESSION['usertype'] == 3 ? $_SESSION['user_id'] : 'anonymous';

// Initialize variables for form processing
$errors = [];
$success = false;

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Log request details for debugging
        file_put_contents('debug.log', "Request Method: {$_SERVER['REQUEST_METHOD']}\nHeaders: " . print_r(getallheaders(), true) . "\nPOST Data: " . print_r($_POST, true) . "\nUser ID: $user_id\n", FILE_APPEND);

        // Sanitize and validate input
        $food_rating = filter_input(INPUT_POST, 'food_rating', FILTER_VALIDATE_INT);
        $ambiance_rating = filter_input(INPUT_POST, 'ambiance_rating', FILTER_VALIDATE_INT);
        $reservation_rating = filter_input(INPUT_POST, 'reservation_rating', FILTER_VALIDATE_INT) ?: 0;
        $service_rating = filter_input(INPUT_POST, 'service_rating', FILTER_VALIDATE_INT);
        $general_comment = filter_input(INPUT_POST, 'general_comment', FILTER_SANITIZE_STRING);

        // Validate required fields
        if ($food_rating === false || $food_rating < 1 || $food_rating > 5) {
            $errors[] = 'Food rating must be between 1 and 5';
        }
        if ($ambiance_rating === false || $ambiance_rating < 1 || $ambiance_rating > 5) {
            $errors[] = 'Ambiance rating must be between 1 and 5';
        }
        if ($service_rating === false || $service_rating < 1 || $service_rating > 5) {
            $errors[] = 'Service rating must be between 1 and 5';
        }
        if (empty($general_comment)) {
            $errors[] = 'Comment is required';
        }

        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['error' => implode(', ', $errors)]);
                ob_end_flush();
                exit;
            }
        } else {
            // Prepare and execute SQL statement
            $stmt = $conn->prepare("
                INSERT INTO ratings (food_rating, ambiance_rating, reservation_rating, service_rating, general_comment, user_id)
                VALUES (:food, :ambiance, :reservation, :service, :comment, :user_id)
            ");

            $stmt->execute([
                ':food' => $food_rating,
                ':ambiance' => $ambiance_rating,
                ':reservation' => $reservation_rating,
                ':service' => $service_rating,
                ':comment' => $general_comment,
                ':user_id' => $user_id
            ]);

            $success = true;

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                ob_end_flush();
                exit;
            }
        }
    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
        file_put_contents('php_errors.log', "PDOException: " . $e->getMessage() . "\n", FILE_APPEND);
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Database error']);
            ob_end_flush();
            exit;
        }
    } catch (Exception $e) {
        $errors[] = 'Unexpected error: ' . $e->getMessage();
        file_put_contents('php_errors.log', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Unexpected error']);
            ob_end_flush();
            exit;
        }
    }
}

// Clean output buffer for non-AJAX requests
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Your Experience - Caffè Lilio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-cream': '#F5F0E8',
                        'rich-brown': '#7A3B0A',
                        'deep-brown': '#4A2A0A',
                        'accent-brown': '#9B5C2F'
                    },
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'baskerville': ['Libre Baskerville', 'serif']
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(74, 42, 10, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #F5F0E8;
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 90%;
            width: 450px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(74, 42, 10, 0.25);
            border: 1px solid rgba(122, 59, 10, 0.1);
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .modal.show .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-icon {
            font-size: 4rem;
            color: #9B5C2F;
            margin-bottom: 1.5rem;
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }

        body {
            background: linear-gradient(135deg, #F5F0E8, #E8E0D5);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-baskerville { font-family: 'Libre Baskerville', serif; }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(74, 42, 10, 0.15);
        }

        .bg-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
        }

        .star-rating .fa-star {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star-rating .fa-star:hover,
        .star-rating .fa-star.active {
            color: #FBBF24 !important;
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            background: #7A3B0A;
            color: #F5F0E8;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #9B5C2F;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(245, 240, 232, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:active::after {
            width: 200%;
            height: 200%;
        }

        textarea {
            resize: none;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #F5F0E8;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #7A3B0A;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #4A2A0A;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            text-align: center;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="text-deep-brown">
    <?php if (!empty($errors) && !$isAjax): ?>
        <div class="error-message">
            <?php echo implode(', ', $errors); ?>
        </div>
    <?php endif; ?>
    <main class="w-full max-w-lg mx-auto px-4 py-6">
        <section class="bg-card rounded-xl p-6 shadow-md hover-lift">
            <div class="text-center mb-6">
                <h2 class="font-playfair text-3xl font-bold text-deep-brown">Rate Your Visit</h2>
                <p class="font-baskerville text-base text-deep-brown/80 mt-2">Your feedback helps us make every moment at Caffè Lilio unforgettable!</p>
            </div>
            <form id="ratingForm" action="ratings.php" method="POST" class="space-y-6">
                <div class="space-y-6">
                    <!-- Food Quality Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Food Quality <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="food_rating" id="food_rating" value="<?php echo isset($_POST['food_rating']) ? htmlspecialchars($_POST['food_rating']) : '0'; ?>" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="food"></i>
                        </div>
                        <div id="food-error" class="text-red-500 text-sm hidden text-center">Please rate the food quality</div>
                    </div>

                    <!-- Ambiance Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Ambiance <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="ambiance_rating" id="ambiance_rating" value="<?php echo isset($_POST['ambiance_rating']) ? htmlspecialchars($_POST['ambiance_rating']) : '0'; ?>" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="ambiance"></i>
                        </div>
                        <div id="ambiance-error" class="text-red-500 text-sm hidden text-center">Please rate the ambiance</div>
                    </div>

                    <!-- Reservation Experience Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Reservation Experience</h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="reservation_rating" id="reservation_rating" value="<?php echo isset($_POST['reservation_rating']) ? htmlspecialchars($_POST['reservation_rating']) : '0'; ?>">
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="reservation"></i>
                        </div>
                    </div>

                    <!-- Service Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Service <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="service_rating" id="service_rating" value="<?php echo isset($_POST['service_rating']) ? htmlspecialchars($_POST['service_rating']) : '0'; ?>" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="service"></i>
                        </div>
                        <div id="service-error" class="text-red-500 text-sm hidden text-center">Please rate the service</div>
                    </div>

                    <!-- Single Comment Section -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Comments <span class="text-red-500">*</span></h4>
                        <textarea name="general_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all"
                                  placeholder="Please share your thoughts about your experience" rows="5" required><?php echo isset($_POST['general_comment']) ? htmlspecialchars($_POST['general_comment']) : ''; ?></textarea>
                        <div id="general-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about your experience</div>
                    </div>
                </div>

                <div class="flex justify-center mt-8">
                    <button type="submit" class="btn-primary px-8 py-3 rounded-lg font-baskerville text-lg hover:bg-accent-brown transition-all duration-300 flex items-center space-x-2 group">
                        <span>Submit Feedback</span>
                        <i class="fas fa-check transition-transform group-hover:scale-110"></i>
                    </button>
                </div>
            </form>
        </section>

        <div class="modal <?php echo $success && !$isAjax ? 'show' : ''; ?>" id="successModal">
            <div class="modal-content">
                <div class="modal-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="font-playfair text-3xl font-bold text-deep-brown mb-3">Thank You!</h3>
                <p class="font-baskerville text-deep-brown/80 mb-6 text-lg leading-relaxed">
                    We truly appreciate you taking the time to share your experience with us.<br>
                    Your feedback helps us continue to improve and provide exceptional service.
                </p>
                <p class="font-baskerville italic text-accent-brown mb-8">
                    We hope to welcome you back to Caffè Lilio soon!
                </p>
                <a href="index.php"
                    class="btn-primary px-8 py-3 rounded-lg font-baskerville text-lg
                            hover:bg-accent-brown transition-all duration-300
                            flex items-center justify-center mx-auto space-x-2 group
                            focus:outline-none focus:ring-2 focus:ring-accent-brown focus:ring-opacity-50
                            active:bg-accent-brown-dark cursor-pointer"
                    aria-label="Close and return to home page"
                    title="Return to home page">
                        <span class="text-center">Close</span>
                        <i class="fas fa-times transition-transform group-hover:scale-110" aria-hidden="true"></i>
                    </a>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Star rating functionality
            const stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    const category = this.getAttribute('data-category');
                    const starsInCategory = document.querySelectorAll(`.star[data-category="${category}"]`);
                    
                    starsInCategory.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.remove('text-deep-brown/30');
                            s.classList.add('text-yellow-500');
                        } else {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-deep-brown/30');
                        }
                    });
                    
                    document.getElementById(`${category}_rating`).value = rating;
                    const errorElement = document.getElementById(`${category}-error`);
                    if (errorElement) errorElement.classList.add('hidden');
                });
            });

            // Modal functionality
            const modal = document.getElementById('successModal');
            // const closeModalButton = document.getElementById('closeModal');
            
            function showModal() {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';
            }
            
            function hideModal() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Reset form and stars
                document.getElementById('ratingForm').reset();
                document.querySelectorAll('.star').forEach(star => {
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-deep-brown/30');
                });
            }
            
            // closeModalButton.addEventListener('click', hideModal);
            
            // modal.addEventListener('click', function(e) {
            //     if (e.target === modal) {
            //         hideModal();
            //     }
            // });

            // Form submission with AJAX
            const form = document.getElementById('ratingForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                let isValid = true;
                
                // Client-side validation
                const requiredRatings = ['food', 'ambiance', 'service'];
                requiredRatings.forEach(category => {
                    const rating = document.getElementById(`${category}_rating`).value;
                    if (rating === '0') {
                        document.getElementById(`${category}-error`).classList.remove('hidden');
                        isValid = false;
                    } else {
                        document.getElementById(`${category}-error`).classList.add('hidden');
                    }
                });
                
                const comment = form.elements['general_comment'].value.trim();
                if (comment === '') {
                    document.getElementById('general-comment-error').classList.remove('hidden');
                    isValid = false;
                } else {
                    document.getElementById('general-comment-error').classList.add('hidden');
                }
                
                if (isValid) {
                    const formData = new FormData(form);
                    fetch('ratings.php', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showModal();
                        } else {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'error-message';
                            errorDiv.textContent = data.error || 'An error occurred while submitting your feedback.';
                            form.appendChild(errorDiv);
                            setTimeout(() => errorDiv.remove(), 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.textContent = `Submission failed: ${error.message}`;
                        form.appendChild(errorDiv);
                        setTimeout(() => errorDiv.remove(), 5000);
                    });
                }
            });

            // Close modal when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    hideModal();
                }
            });
        });
    </script>
</body>
</html>