<?php
// nav.php
?>
<!-- Navigation -->
<nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-3">
                <div>
                    <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
                    <p class="nav-subtitle text-xs text-warm-cream tracking-widest">RISTORANTE</p>
                </div>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#about" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                    About Us
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#menu" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                    Menu & Packages
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#services" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                    What We Offer
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>
            
            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="login.php" class="nav-link font-baskerville text-warm-cream hover:text-warm-cream/80 transition-colors duration-300 relative group">
                    Login
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-warm-cream transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="register.php" class="nav-button font-baskerville bg-warm-cream text-deep-brown px-4 py-2 rounded-full transition-all duration-300">
                    Register
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden focus:outline-none" id="mobile-menu-btn">
                <div class="w-6 h-6 flex flex-col justify-center space-y-1">
                    <span class="block w-full h-0.5 bg-deep-brown transition-all duration-300"></span>
                    <span class="block w-full h-0.5 bg-deep-brown transition-all duration-300"></span>
                    <span class="block w-full h-0.5 bg-deep-brown transition-all duration-300"></span>
                </div>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="md:hidden hidden glass-effect" id="mobile-menu">
        <div class="px-4 py-4 space-y-4">
            <a href="#home" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">Home</a>
            <a href="#about" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">About Us</a>
            <a href="#menu" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">Menu & Packages</a>
            <a href="#services" class="block font-baskerville hover:text-rich-brown transition-colors duration-300">What We Offer</a>
            
            <div class="pt-4 border-t border-deep-brown/10">
                <a href="login.php" class="block w-full text-left font-baskerville hover:text-rich-brown transition-colors duration-300 mb-3">
                    Login
                </a>
                <a href="register.php" class="block w-full font-baskerville bg-deep-brown text-warm-cream px-4 py-2 rounded-full hover:bg-rich-brown transition-all duration-300">
                    Register
                </a>
            </div>
        </div>
    </div>
</nav>