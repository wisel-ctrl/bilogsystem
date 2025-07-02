<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';

// Set page title
$page_title = "Contact Us - Caffè Lilio";

// Capture content
ob_start();
?>

<!-- Contact Section -->
<section class="mb-12">
    <div class="text-center mb-8">
        <h2 class="font-playfair text-4xl font-bold text-deep-brown">Get in Touch</h2>
        <p class="font-baskerville text-lg text-deep-brown/80 mt-2">
            We'd love to hear from you! Reach out with any questions or feedback.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white/50 rounded-xl p-8 shadow-md hover-lift border border-deep-brown/10">
            <h3 class="font-playfair text-2xl font-bold text-deep-brown mb-6">Send Us a Message</h3>
            <form id="contact-form" class="space-y-6">
                <div>
                    <label for="name" class="block font-baskerville text-deep-brown mb-2">Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-deep-brown/50 focus:outline-none focus:border-rich-brown transition-all"
                           placeholder="Your Name">
                </div>
                <div>
                    <label for="email" class="block font-baskerville text-deep-brown mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-deep-brown/50 focus:outline-none focus:border-rich-brown transition-all"
                           placeholder="Your Email">
                </div>
                <div>
                    <label for="subject" class="block font-baskerville text-deep-brown mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-deep-brown/50 focus:outline-none focus:border-rich-brown transition-all"
                           placeholder="Subject">
                </div>
                <div>
                    <label for="message" class="block font-baskerville text-deep-brown mb-2">Message</label>
                    <textarea id="message" name="message" required rows="5"
                              class="w-full px-4 py-3 rounded-lg border border-deep-brown/20 bg-warm-cream/50 text-deep-brown placeholder-deep-brown/50 focus:outline-none focus:border-rich-brown transition-all"
                              placeholder="Your Message"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2">
                        <span>Send Message</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="bg-rich-brown rounded-xl p-8 shadow-md hover-lift text-warm-cream">
            <h3 class="font-playfair text-2xl font-bold mb-6">Contact Information</h3>
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-map-marker-alt text-warm-cream/70 w-6"></i>
                    <p class="font-baskerville">123 Restaurant St., Food District, City</p>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fas fa-phone text-warm-cream/70 w-6"></i>
                    <p class="font-baskerville">+63 912 345 6789</p>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fas fa-envelope text-warm-cream/70 w-6"></i>
                    <p class="font-baskerville">info@caffelilio.com</p>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fas fa-clock text-warm-cream/70 w-6"></i>
                    <div>
                        <p class="font-baskerville">Mon - Fri: 11AM - 10PM</p>
                        <p class="font-baskerville">Sat - Sun: 10AM - 11PM</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h4 class="font-playfair text-lg font-semibold mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="https://web.facebook.com/caffelilio.ph" target="_blank"
                       class="w-10 h-10 bg-warm-cream/20 rounded-full flex items-center justify-center border border-warm-cream/30 hover:bg-warm-cream/30 transition-all duration-300"
                       data-tippy-content="Follow us on Facebook">
                        <i class="fab fa-facebook-f text-warm-cream"></i>
                    </a>
                    <a href="https://www.instagram.com/caffelilio.ph/" target="_blank"
                       class="w-10 h-10 bg-warm-cream/20 rounded-full flex items-center justify-center border border-warm-cream/30 hover:bg-warm-cream/30 transition-all duration-300"
                       data-tippy-content="Follow us on Instagram">
                        <i class="fab fa-instagram text-warm-cream"></i>
                    </a>
                    <a href="https://twitter.com/caffelilio" target="_blank"
                       class="w-10 h-10 bg-warm-cream/20 rounded-full flex items-center justify-center border border-warm-cream/30 hover:bg-warm-cream/30 transition-all duration-300"
                       data-tippy-content="Follow us on Twitter">
                        <i class="fab fa-twitter text-warm-cream"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Page-specific JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form');
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            NProgress.start();

            // Simulate form submission (replace with actual fetch to contact_handler.php)
            setTimeout(() => {
                NProgress.done();
                showToast('Your message has been sent successfully!', 'success');
                contactForm.reset();
            }, 1000);
        });
    });
</script>

<?php
// Capture content and include layout
$content = ob_get_clean();
include 'layout_customer.php';
?>