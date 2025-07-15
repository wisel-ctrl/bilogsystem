<?php
// Initialize database connection (update with your credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating_value = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $review_text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
    $booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : null; // Adjust based on how you get this
    $customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : null; // Adjust based on how you get this
    $submitted_via = isset($_POST['submitted_via']) ? $_POST['submitted_via'] : 'booking';

    // Validate inputs
    if ($rating_value >= 1 && $rating_value <= 5 && !empty($review_text)) {
        try {
            $stmt = $conn->prepare("INSERT INTO ratings_tb (booking_id, rating_value, submitted_via, customer_id, review_text, rated_at) 
                                    VALUES (:booking_id, :rating_value, :submitted_via, :customer_id, :review_text, NOW())");
            $stmt->execute([
                ':booking_id' => $booking_id,
                ':rating_value' => $rating_value,
                ':submitted_via' => $submitted_via,
                ':customer_id' => $customer_id,
                ':review_text' => $review_text
            ]);
            // Success: JavaScript will show the modal
        } catch(PDOException $e) {
            echo "<script>alert('Error saving rating: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Please provide a valid rating and review.');</script>";
    }
}
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
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .star-rating .fa-star:hover,
        .star-rating .fa-star.active {
            color: #FBBF24 !important;
            transform: scale(1.2);
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
    </style>
</head>
<body class="text-deep-brown">
    <main class="w-full max-w-lg mx-auto px-4 py-6">
        <section class="bg-card rounded-xl p-6 shadow-md hover-lift">
            <div class="text-center mb-6">
                <h2 class="font-playfair text-3xl font-bold text-deep-brown">Rate Your Visit</h2>
                <p class="font-baskerville text-base text-deep-brown/80 mt-2">Your feedback helps us make every moment at Caffè Lilio unforgettable!</p>
            </div>
            <form id="ratingForm" method="POST" class="space-y-6">
                <div class="space-y-6">
                    <!-- Overall Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Overall Experience <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="rating" id="rating" value="0" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5"></i>
                        </div>
                        <div id="rating-error" class="text-red-500 text-sm hidden text-center">Please provide a rating</div>
                        <textarea name="review_text" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all"
                                  placeholder="Share your thoughts about your experience" rows="5" required></textarea>
                        <div id="review-text-error" class="text-red-500 text-sm hidden text-center">Please share your feedback</div>
                        <!-- Hidden fields for booking_id, customer_id, and submitted_via -->
                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars(isset($_GET['booking_id']) ? $_GET['booking_id'] : ''); ?>">
                        <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars(isset($_GET['customer_id']) ? $_GET['customer_id'] : ''); ?>">
                        <input type="hidden" name="submitted_via" value="booking"> <!-- Adjust as needed -->
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

        <!-- Thank You Modal -->
        <div class="modal" id="successModal">
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
                <button id="closeModal" class="btn-primary px-8 py-3 rounded-lg font-baskerville text-lg hover:bg-accent-brown transition-all duration-300 flex items-center mx-auto space-x-2 group">
                    <span>Close</span>
                    <i class="fas fa-times transition-transform group-hover:scale-110"></i>
                </button>
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
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.remove('text-deep-brown/30');
                            s.classList.add('text-yellow-500');
                        } else {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-deep-brown/30');
                        }
                    });
                    document.getElementById('rating').value = rating;
                    document.getElementById('rating-error').classList.add('hidden');
                });
            });

            // Modal functionality
            const modal = document.getElementById('successModal');
            const closeModalButton = document.getElementById('closeModal');

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
                stars.forEach(star => {
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-deep-brown/30');
                });
            }

            closeModalButton.addEventListener('click', hideModal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });

            // Form validation
            const form = document.getElementById('ratingForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                let isValid = true;

                // Validate rating
                const rating = document.getElementById('rating').value;
                if (rating === '0') {
                    document.getElementById('rating-error').classList.remove('hidden');
                    isValid = false;
                } else {
                    document.getElementById('rating-error').classList.add('hidden');
                }

                // Validate review text
                const reviewText = form.elements['review_text'].value.trim();
                if (reviewText === '') {
                    document.getElementById('review-text-error').classList.remove('hidden');
                    isValid = false;
                } else {
                    document.getElementById('review-text-error').classList.add('hidden');
                }

                if (isValid) {
                    // Submit the form (PHP will handle the rest)
                    form.submit();
                    showModal();
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