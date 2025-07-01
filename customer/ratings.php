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

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(74, 42, 10, 0.4);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #F5F0E8;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 90%;
            width: 400px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(74, 42, 10, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s ease-in-out;
        }

        .modal.show {
            display: flex;
        }

        .modal.show .modal-content {
            transform: translateY(0);
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
            <form id="ratingForm" class="space-y-6">
                <div class="space-y-6">
                    <!-- Food Quality Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Food Quality <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="food_rating" id="food_rating" value="0" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="food"></i>
                        </div>
                        <div id="food-error" class="text-red-500 text-sm hidden text-center">Please rate the food quality</div>
                        <textarea name="food_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all"
                                  placeholder="What did you think of the food?" rows="3" required></textarea>
                        <div id="food-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about the food</div>
                    </div>

                    <!-- Ambiance Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Ambiance <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="ambiance_rating" id="ambiance_rating" value="0" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="ambiance"></i>
                        </div>
                        <div id="ambiance-error" class="text-red-500 text-sm hidden text-center">Please rate the ambiance</div>
                        <textarea name="ambiance_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all"
                                  placeholder="How was the atmosphere?" rows="3" required></textarea>
                        <div id="ambiance-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about the ambiance</div>
                    </div>

                    <!-- Reservation Experience Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Reservation Experience</h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="reservation_rating" id="reservation_rating" value="0">
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="reservation"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="reservation"></i>
                        </div>
                        <textarea name="reservation_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all"
                                  placeholder="How was your reservation process?" rows="3"></textarea>
                    </div>

                    <!-- Service Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Service <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="service_rating" id="service_rating" value="0" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="1" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="2" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="3" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="4" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 star" data-rating="5" data-category="service"></i>
                        </div>
                        <div id="service-error" class="text-red-500 text-sm hidden text-center">Please rate the service</div>
                        <textarea name="service_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all"
                                  placeholder="How was the service?" rows="3" required></textarea>
                        <div id="service-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about the service</div>
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

        <!-- Modal -->
        <div class="modal" id="successModal">
            <div class="modal-content">
                <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-4">Thank You!</h3>
                <p class="font-baskerville text-deep-brown/80 mb-6">Your feedback means the world to us! We’re excited to make your next visit to Caffè Lilio even better.</p>
                <button id="closeModal" class="btn-primary px-6 py-2 rounded-lg font-baskerville text-base hover:bg-accent-brown transition-all duration-300 flex items-center mx-auto space-x-2 group">
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
            const closeModalButton = document.getElementById('closeModal');
            
            function showModal() {
                modal.classList.add('show');
                document.body.classList.add('overflow-hidden');
            }
            
            function hideModal() {
                modal.classList.remove('show');
                document.body.classList.remove('overflow-hidden');
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
                
                const requiredComments = ['food_comment', 'ambiance_comment', 'service_comment'];
                requiredComments.forEach(name => {
                    const comment = form.elements[name].value.trim();
                    if (comment === '') {
                        document.getElementById(`${name}-error`).classList.remove('hidden');
                        isValid = false;
                    } else {
                        document.getElementById(`${name}-error`).classList.add('hidden');
                    }
                });
                
                if (isValid) {
                    showModal();
                    form.reset();
                    document.querySelectorAll('.star').forEach(star => {
                        star.classList.remove('text-yellow-500');
                        star.classList.add('text-deep-brown/30');
                    });
                }
            });
        });
    </script>
</body>
</html>