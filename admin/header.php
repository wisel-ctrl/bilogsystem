<header class="bg-white/80 backdrop-blur-md shadow-md border-b border-warm-cream/20 px-6 py-4 relative z-[100]">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button id="sidebar-toggle" class="text-deep-brown hover:text-rich-brown transition-colors duration-200">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
        <div class="text-sm text-rich-brown font-baskerville flex-1 text-center mx-4 hidden md:block">
            <i class="fas fa-calendar-alt mr-2"></i>
            <span id="current-date"></span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button id="notifications" class="text-deep-brown hover:text-rich-brown transition-colors duration-200 p-2 rounded-lg hover:bg-warm-cream/10">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <div id="notificationMenu" class="absolute right-0 top-full mt-2 w-64 bg-white rounded-lg shadow-lg py-2 hidden transform opacity-0 transition-all duration-200">
                    <div class="px-4 py-2 text-sm text-deep-brown">
                        <p class="font-medium">Notifications</p>
                        <ul class="mt-2 space-y-2">
                            <li class="text-sm">No new notifications</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="relative">
                <button id="profileDropdown" class="flex items-center space-x-2 hover:bg-warm-cream/10 p-2 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 rounded-full border-2 border-accent-brown overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-medium text-deep-brown font-baskerville">Admin</span>
                    <i class="fas fa-chevron-down text-deep-brown text-sm transition-transform duration-200"></i>
                </button>
                <div id="profileMenu" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden transform opacity-0 transition-all duration-200">
                    <a href="admin_setting.php" class="flex items-center space-x-2 px-4 py-2 text-sm text-deep-brown hover:bg-warm-cream/10 transition-colors duration-200">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="../logout.php?usertype=admin" class="flex items-center space-x-2 px-4 py-2 text-sm text-deep-brown hover:bg-warm-cream/10 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationButton = document.getElementById('notifications');
    const notificationMenu = document.getElementById('notificationMenu');
    // const profileButton = document.getElementById('profileDropdown');
    // const profileMenu = document.getElementById('profileMenu');

    // Toggle notification menu
    notificationButton.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = notificationMenu.classList.contains('hidden');
        notificationMenu.classList.toggle('hidden', !isHidden);
        notificationMenu.classList.toggle('opacity-0', !isHidden);
        // Close profile menu if open
        profileMenu.classList.add('hidden', 'opacity-0');
    });

    // Toggle profile menu
    // profileButton.addEventListener('click', function(e) {
    //     e.stopPropagation();
    //     const isHidden = profileMenu.classList.contains('hidden');
    //     profileMenu.classList.toggle('hidden', !isHidden);
    //     profileMenu.classList.toggle('opacity-0', !isHidden);
    //     // Close notification menu if open
    //     notificationMenu.classList.add('hidden', 'opacity-0');
    // });

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationButton.contains(e.target) && !notificationMenu.contains(e.target)) {
            notificationMenu.classList.add('hidden', 'opacity-0');
        }
        // if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
        //     profileMenu.classList.add('hidden', 'opacity-0');
        // }
    });
});
</script>