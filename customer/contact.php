<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';

// Set page title
$page_title = "Contact Us - Caffè Lilio";

// Capture content
ob_start();
?>

<!-- Hero Section -->
<section class="relative bg-warm-gradient py-12 mb-12 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 border border-deep-brown/20 rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 border border-deep-brown/20 rounded-full animate-pulse"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-playfair text-5xl font-bold text-deep-brown mb-4 animate-fade-in-down">Contact Caffè Lilio</h2>
        <p class="font-baskerville text-xl text-deep-brown/80 max-w-2xl mx-auto animate-fade-in-up">
            Have questions or feedback? We're here to help you plan your perfect dining experience.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="mb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white/70 rounded-xl p-8 shadow-lg hover-lift border border-deep-brown/10 animate-fade-in-left">
            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Send Us a Message</h3>
            <form id="contact-form" class="space-y-6">
                <div class="relative">
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-transparent focus:outline-none focus:border-rich-brown transition-all peer"
                           placeholder="Your Name" data-tippy-content="Enter your full name">
                    <label for="name" class="absolute left-4 -top-2.5 px-1 bg-white/70 text-deep-brown/80 text-sm font-baskerville transition-all duration-300 peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-sm">
                        Name
                    </label>
                    <p class="text-red-600 text-sm mt-1 hidden" id="name-error">Name must be at least 2 characters</p>
                </div>
                <div class="relative">
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-transparent focus:outline-none focus:border-rich-brown transition-all peer"
                           placeholder="Your Email" data-tippy-content="Enter a valid email address">
                    <label for="email" class="absolute left-4 -top-2.5 px-1 bg-white/70 text-deep-brown/80 text-sm font-baskerville transition-all duration-300 peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-sm">
                        Email
                    </label>
                    <p class="text-red-600 text-sm mt-1 hidden" id="email-error">Please enter a valid email address</p>
                </div>
                <div class="relative">
                    <input type="text" id="subject" name="subject" required
                           class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-transparent focus:outline-none focus:border-rich-brown transition-all peer"
                           placeholder="Subject" data-tippy-content="Enter the subject of your message">
                    <label for="subject" class="absolute left-4 -top-2.5 px-1 bg-white/70 text-deep-brown/80 text-sm font-baskerville transition-all duration-300 peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-sm">
                        Subject
                    </label>
                    <p class="text-red-600 text-sm mt-1 hidden" id="subject-error">Subject must be at least 5 characters</p>
                </div>
                <div class="relative">
                    <textarea id="message" name="message" required rows="5"
                              class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-transparent focus:outline-none focus:border-rich-brown transition-all peer"
                              placeholder="Your Message" data-tippy-content="Enter your message or inquiry"></textarea>
                    <label for="message" class="absolute left-4 -top-2.5 px-1 bg-white/70 text-deep-brown/80 text-sm font-baskerville transition-all duration-300 peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-sm">
                        Message
                    </label>
                    <p class="text-red-600 text-sm mt-1 hidden" id="message-error">Message must be at least 10 characters</p>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="clear-form"
                            class="px-4 py-3 rounded-lg text-deep-brown border border-deep-brown/20 hover:bg-deep-brown/10 transition-all duration-300 font-baskerville flex items-center space-x-2"
                            data-tippy-content="Clear all form fields">
                        <span>Clear Form</span>
                        <i class="fas fa-eraser"></i>
                    </button>
                    <button type="submit" id="submit-form"
                            class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            data-tippy-content="Send your message">
                        <span>Send Message</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="bg-rich-brown rounded-xl p-8 shadow-lg hover-lift text-warm-cream animate-fade-in-right">
            <h3 class="font-playfair text-2xl font-bold mb-6">Visit or Call Us</h3>
            <div class="space-y-4">
                <div class="flex items-center space-x-3 group">
                    <i class="fas fa-map-marker-alt text-warm-cream/70 w-6 group-hover:text-warm-cream transition-colors"></i>
                    <p class="font-baskerville group-hover:text-warm-cream/90 transition-colors">Brgy. Rizal St. cr. 4th St., Liliw, Laguna, Philippines, 4004</p>
                </div>
                <div class="flex items-center space-x-3 group">
                    <i class="fas fa-phone text-warm-cream/70 w-6 group-hover:text-warm-cream transition-colors"></i>
                    <a href="tel:+492542084" class="font-baskerville group-hover:text-warm-cream/90 transition-colors">+49 2542 084</a>
                </div>
                <div class="flex items-center space-x-3 group">
                    <i class="fas fa-envelope text-warm-cream/70 w-6 group-hover:text-warm-cream transition-colors"></i>
                    <a href="mailto:caffelilio.liliw@gmail.com" class="font-baskerville group-hover:text-warm-cream/90 transition-colors">caffelilio.liliw@gmail.com</a>
                </div>
                <div class="flex items-start space-x-3 group">
                    <i class="fas fa-clock text-warm-cream/70 w-6 group-hover:text-warm-cream transition-colors"></i>
                    <div>
                        <p class="font-baskerville group-hover:text-warm-cream/90 transition-colors">Mon - Fri: 11AM - 10PM</p>
                        <p class="font-baskerville group-hover:text-warm-cream/90 transition-colors">Sat - Sun: 10AM - 11PM</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h4 class="font-playfair text-lg font-semibold mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="https://web.facebook.com/caffelilio.ph" target="_blank"
                       class="w-12 h-12 bg-warm-cream/20 rounded-full flex items-center justify-center border border-warm-cream/30 hover:bg-warm-cream/40 hover:scale-110 transition-all duration-300"
                       data-tippy-content="Follow us on Facebook">
                        <i class="fab fa-facebook-f text-warm-cream text-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/caffelilio.ph/" target="_blank"
                       class="w-12 h-12 bg-warm-cream/20 rounded-full flex items-center justify-center border border-warm-cream/30 hover:bg-warm-cream/40 hover:scale-110 transition-all duration-300"
                       data-tippy-content="Follow us on Instagram">
                        <i class="fab fa-instagram text-warm-cream text-lg"></i>
                    </a>
                    <a href="https://twitter.com/caffelilio" target="_blank"
                       class="w-12 h-12 bg-warm-cream/20 rounded-full flex items-center justify-center border border-warm-cream/30 hover:bg-warm-cream/40 hover:scale-110 transition-all duration-300"
                       data-tippy-content="Follow us on Twitter">
                        <i class="fab fa-twitter text-warm-cream text-lg"></i>
                    </a>
                </div>
            </div>
            <!-- Map Section -->
            <div class="mt-6">
                <h4 class="font-playfair text-lg font-semibold mb-4">Find Us</h4>
                <div class="rounded-lg overflow-hidden shadow-md border border-warm-cream/50">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5834.00236310445!2d121.43328019283992!3d14.13211205286109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd5bc02c4f1977%3A0x88727b5a78560087!2sCaff%C3%A8%20Lilio!5e0!3m2!1sen!2sph!4v1744473249809!5m2!1sen!2sph"
                            width="100%"
                            height="180"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="fade-in"></iframe>
                </div>
                <button id="get-directions"
                        class="mt-4 w-full btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300"
                        data-tippy-content="Get directions to Caffè Lilio">
                    Get Directions
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Page-specific CSS -->
<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    .animate-fade-in-left {
        animation: fadeInLeft 0.6s ease-out;
    }
    .animate-fade-in-right {
        animation: fadeInRight 0.6s ease-out;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .warm-gradient {
        background: linear-gradient(to bottom, #E8E0D5, #F5F0E9);
    }
</style>

<!-- Page-specific JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
<script>
    // Initialize Tippy.js for tooltips
    tippy('[data-tippy-content]', {
        theme: 'light',
        animation: 'scale',
        placement: 'top'
    });

    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const subjectInput = document.getElementById('subject');
        const messageInput = document.getElementById('message');
        const submitButton = document.getElementById('submit-form');
        const clearButton = document.getElementById('clear-form');
        const directionsButton = document.getElementById('get-directions');

        // Auto-focus on name input
        nameInput.focus();

        // Real-time validation
        const validateInput = (input, errorElement, validator, errorMessage) => {
            const error = document.getElementById(errorElement);
            if (!validator(input.value)) {
                input.classList.add('border-red-600');
                input.classList.remove('border-deep-brown/20');
                error.classList.remove('hidden');
                error.textContent = errorMessage;
                return false;
            } else {
                input.classList.remove('border-red-600');
                input.classList.add('border-deep-brown/20');
                error.classList.add('hidden');
                return true;
            }
        };

        const validators = {
            name: value => value.trim().length >= 2,
            email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            subject: value => value.trim().length >= 5,
            message: value => value.trim().length >= 10
        };

        // Input event listeners for real-time validation
        nameInput.addEventListener('input', () => {
            validateInput(nameInput, 'name-error', validators.name, 'Name must be at least 2 characters');
            updateSubmitButton();
        });
        emailInput.addEventListener('input', () => {
            validateInput(emailInput, 'email-error', validators.email, 'Please enter a valid email address');
            updateSubmitButton();
        });
        subjectInput.addEventListener('input', () => {
            validateInput(subjectInput, 'subject-error', validators.subject, 'Subject must be at least 5 characters');
            updateSubmitButton();
        });
        messageInput.addEventListener('input', () => {
            validateInput(messageInput, 'message-error', validators.message, 'Message must be at least 10 characters');
            updateSubmitButton();
        });

        // Enable/disable submit button based on validation
        function updateSubmitButton() {
            const isValid = validators.name(nameInput.value) &&
                           validators.email(emailInput.value) &&
                           validators.subject(subjectInput.value) &&
                           validators.message(messageInput.value);
            submitButton.disabled = !isValid;
        }

        // Clear form with confirmation
        clearButton.addEventListener('click', () => {
            Swal.fire({
                title: 'Clear Form?',
                text: 'This will reset all fields. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Clear',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn-primary bg-rich-brown text-warm-cream px-4 py-2 rounded-lg',
                    cancelButton: 'px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    contactForm.reset();
                    nameInput.focus();
                    document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));
                    submitButton.disabled = true;
                    showToast('Form cleared successfully', 'success');
                }
            });
        });

        // Form submission
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const isValid = validators.name(nameInput.value) &&
                           validators.email(emailInput.value) &&
                           validators.subject(subjectInput.value) &&
                           validators.message(messageInput.value);

            if (!isValid) {
                showToast('Please fill out all fields correctly', 'error');
                return;
            }

            NProgress.start();
            submitButton.disabled = true;
            submitButton.innerHTML = '<span>Sending...</span><i class="fas fa-spinner fa-spin ml-2"></i>';

            // Simulate form submission (replace with actual fetch to contact_handler.php)
            fetch('contact_handler.php', {
                method: 'POST',
                body: new FormData(contactForm)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                NProgress.done();
                submitButton.disabled = false;
                submitButton.innerHTML = '<span>Send Message</span><i class="fas fa-paper-plane ml-2"></i>';

                if (data.success) {
                    Swal.fire({
                        title: 'Message Sent!',
                        text: 'Thank you for contacting us. We’ll get back to you soon.',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: 'btn-primary bg-rich-brown text-warm-cream px-4 py-2 rounded-lg'
                        }
                    });
                    contactForm.reset();
                    nameInput.focus();
                    submitButton.disabled = true;
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to send message. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        customClass: {
                            confirmButton: 'btn-primary bg-rich-brown text-warm-cream px-4 py-2 rounded-lg'
                        }
                    });
                }
            })
            .catch(error => {
                NProgress.done();
                submitButton.disabled = false;
                submitButton.innerHTML = '<span>Send Message</span><i class="fas fa-paper-plane ml-2"></i>';
                Swal.fire({
                    title: 'Error!',
                    text: 'Error sending message: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    customClass: {
                        confirmButton: 'btn-primary bg-rich-brown text-warm-cream px-4 py-2 rounded-lg'
                    }
                });
            });
        });

        // Get Directions functionality
        directionsButton.addEventListener('click', () => {
            Swal.fire({
                title: 'Get Directions to Caffè Lilio',
                text: 'Enter your starting location or open Google Maps directly.',
                input: 'text',
                inputPlaceholder: 'Enter your starting address',
                showCancelButton: true,
                confirmButtonText: 'Get Directions',
                cancelButtonText: 'Open Google Maps',
                customClass: {
                    confirmButton: 'btn-primary bg-rich-brown text-warm-cream px-4 py-2 rounded-lg',
                    cancelButton: 'px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10'
                },
                inputValidator: (value) => {
                    if (value && value.trim().length < 3) {
                        return 'Please enter a valid starting address';
                    }
                }
            }).then((result) => {
                const destination = encodeURIComponent('Brgy. Rizal St. cr. 4th St., Liliw, Laguna, Philippines, 4004');
                if (result.isConfirmed && result.value) {
                    const origin = encodeURIComponent(result.value);
                    window.open(`https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&travelmode=driving`, '_blank');
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                    window.open('https://maps.app.goo.gl/QuT5V7PWGJQZRWyN7', '_blank');
                }
            });
        });

        // Smooth scroll to form if query parameter ?form=1 is present
        if (window.location.search.includes('form=1')) {
            document.querySelector('#contact-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    });

    // Toast notification function (assumed to be in layout_customer.php)
    function showToast(message, type) {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: type === 'success' ? '#E8E0D5' : '#FEE2E2',
            customClass: {
                popup: 'font-baskerville text-deep-brown'
            }
        });
    }
</script>

<?php
// Capture content and include layout
$content = ob_get_clean();
include 'layout_customer.php';
?>