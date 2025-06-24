<style>
    #profileMenu {
        z-index: 9999 !important;
        transform: translateY(0) !important;
    }

    header {
        z-index: 50;
    }

    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }

    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-baskerville { font-family: 'Libre Baskerville', serif; }
</style>

<header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[100]">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button id="sidebar-toggle" class="text-deep-brown hover:text-rich-brown transition-colors duration-200">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 class="text-2xl font-bold text-deep-brown font-playfair"><?php echo $page_title ?? 'Dashboard'; ?></h2>
        </div>
        <div class="text-sm text-rich-brown font-baskerville flex-1 text-center mx-4">
            <i class="fas fa-calendar-alt mr-2"></i>
            <span id="current-date"></span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button id="profileDropdown" class="flex items-center space-x-2 hover:bg-warm-cream/10 p-2 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 rounded-full border-2 border-accent-brown overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-medium text-deep-brown font-baskerville">Admin</span>
                    <i class="fas fa-chevron-down text-deep-brown text-sm transition-transform duration-200"></i>
                </button>
                <div id="profileMenu" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden transform opacity-0 transition-all duration-200">
                    <a href="../logout.php" class="flex items-center space-x-2 px-4 py-2 text-sm text-deep-brown hover:bg-warm-cream/10 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Profile Dropdown functionality
    const profileDropdown = document.getElementById('profileDropdown');
    const profileMenu = document.getElementById('profileMenu');
    const dropdownIcon = profileDropdown.querySelector('.fa-chevron-down');

    profileDropdown.addEventListener('click', () => {
        profileMenu.classList.toggle('hidden');
        setTimeout(() => {
            profileMenu.classList.toggle('opacity-0');
            dropdownIcon.classList.toggle('rotate-180');
        }, 50);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target)) {
            profileMenu.classList.add('hidden', 'opacity-0');
            dropdownIcon.classList.remove('rotate-180');
        }
    });

    // Set current date
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', options);
</script>