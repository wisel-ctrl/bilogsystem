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
</head>
<body class="bg-gradient-to-br from-warm-cream to-[rgb(232,224,213)] min-h-screen flex flex-col items-center justify-center p-4 text-deep-brown">
    <main class="w-full max-w-lg mx-auto px-4 py-6">
        <section class="bg-white/95 backdrop-blur-lg rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1 hover:shadow-lg">
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
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="1" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="2" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="3" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="4" data-category="food"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="5" data-category="food"></i>
                        </div>
                        <div id="food-error" class="text-red-500 text-sm hidden text-center">Please rate the food quality</div>
                        <textarea name="food_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all resize-none"
                                  placeholder="What did you think of the food?" rows="3" required></textarea>
                        <div id="food-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about the food</div>
                    </div>

                    <!-- Ambiance Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Ambiance <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="ambiance_rating" id="ambiance_rating" value="0" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="1" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="2" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="3" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="4" data-category="ambiance"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="5" data-category="ambiance"></i>
                        </div>
                        <div id="ambiance-error" class="text-red-500 text-sm hidden text-center">Please rate the ambiance</div>
                        <textarea name="ambiance_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all resize-none"
                                  placeholder="How was the atmosphere?" rows="3" required></textarea>
                        <div id="ambiance-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about the ambiance</div>
                    </div>

                    <!-- Reservation Experience Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Reservation Experience</h4>
                        <div class="space-y-3">
                            <div class="star-rating flex justify-center space-x-3">
                                <input type="hidden" name="reservation_rating" id="reservation_rating" value="0">
                                <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="1" data-category="reservation"></i>
                                <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="2" data-category="reservation"></i>
                                <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="3" data-category="reservation"></i>
                                <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="4" data-category="reservation"></i>
                                <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="5" data-category="reservation"></i>
                            </div>
                            <textarea name="reservation_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all resize-none"
                                      placeholder="How was your reservation process?" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Service Rating -->
                    <div class="space-y-3">
                        <h4 class="font-baskerville text-lg font-bold text-deep-brown">Service <span class="text-red-500">*</span></h4>
                        <div class="star-rating flex justify-center space-x-3">
                            <input type="hidden" name="service_rating" id="service_rating" value="0" required>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="1" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="2" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="3" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="4" data-category="service"></i>
                            <i class="fas fa-star text-3xl text-deep-brown/30 cursor-pointer transition-all hover:scale-125 hover:text-yellow-500" data-rating="5" data-category="service"></i>
                        </div>
                        <div id="service-error" class="text-red-500 text-sm hidden text-center">Please rate the service</div>
                        <textarea name="service_comment" class="w-full p-3 border border-deep-brown/20 rounded-lg focus:border-accent-brown focus:ring-2 focus:ring-accent-brown/20 transition-all resize-none"
                                  placeholder="How was the service?" rows="3" required></textarea>
                        <div id="service-comment-error" class="text-red-500 text-sm hidden text-center">Please share your thoughts about the service</div>
                    </div>
                </div>

                <div class="flex justify-center mt-8">
                    <button type="submit" class="relative overflow-hidden bg-rich-brown text-warm-cream px-8 py-3 rounded-lg font-baskerville text-lg hover:bg-accent-brown transition-all duration-300 flex items-center space-x-2 group">
                        <span>Submit Feedback</span>
                        <i class="fas fa-check transition-transform group-hover:scale-110"></i>
                        <span class="absolute inset-0 bg-warm-cream/20 rounded-full scale-0 group-active:scale-[2] transition-transform duration-600 origin-center"></span>
                    </button>
                </div>
            </form>
        </section>

        <!-- Enhanced Thank You Modal -->
        <div class="fixed inset-0 bg-deep-brown/40 backdrop-blur-sm hidden items-center justify-center z-[1000]" id="successModal">
            <div class="bg-warm-cream rounded-2xl p-10 max-w-[90%] w-[450px] text-center shadow-2xl border border-rich-brown/10 opacity-0 -translate-y-5 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                <div class="text-6xl text-accent-brown mb-6 animate-[bounce_0.6s_ease]">
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
                <button id="closeModal" class="relative overflow-hidden bg-rich-brown text-warm-cream px-8 py-3 rounded-lg font-baskerville text-lg hover:bg-accent-brown transition-all duration-300 flex items-center mx-auto space-x-2 group">
                    <span>Close</span>
                    <i class="fas fa-times transition-transform group-hover:scale-110"></i>
                    <span class="absolute inset-0 bg-warm-cream/20 rounded-full scale-0 group-active:scale-[2] transition-transform duration-600 origin-center"></span>
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
                        s.classList.toggle('text-yellow-500', index < rating);
                        s.classList.toggle('text-deep-brown/30', index >= rating);
                        s.classList.toggle('scale-125', index < rating);
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
                modal.classList.remove('hidden');
                modal.querySelector('.opacity-0').classList.remove('opacity-0', '-translate-y-5');
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';
            }
            
            function hideModal() {
                modal.classList.add('hidden');
                modal.querySelector('.transition-all').classList.add('opacity-0', '-translate-y-5');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Reset form and stars
                document.getElementById('ratingForm').reset();
                document.querySelectorAll('.star').forEach(star => {
                    star.classList.remove('text-yellow-500', 'scale-125');
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
                }
            });

            // Close modal when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });
        });
    </script>
</body>
</html>