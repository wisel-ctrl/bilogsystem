<?php 
// Get current user data
try {
    $stmt = $conn->prepare("SELECT * FROM users_tb WHERE id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception("User not found");
    }
    
    // Set profile picture path for JavaScript
    $profilePhotoPath = !empty($user['profile_picture']) 
        ? '../images/profile_pictures/' . htmlspecialchars($user['profile_picture'])
        : 'https://via.placeholder.com/120x120/D2B48C/FFFFFF?text=Admin';
        
    // Fetch notifications for the current user
    $notificationStmt = $conn->prepare("
        SELECT * FROM notifications_tb 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $notificationStmt->execute([':user_id' => $_SESSION['user_id']]);
    $notifications = $notificationStmt->fetchAll();
    
    $unreadCount = 0;
    foreach ($notifications as $notification) {
        if (!$notification['is_read']) {
            $unreadCount++;
        }
    }
    
} catch (Exception $e) {
    die("Error fetching user data: " . $e->getMessage());
}
?>

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
            <button id="notifications" class="text-deep-brown hover:text-rich-brown transition-colors duration-200 p-2 rounded-lg hover:bg-warm-cream/10 relative">
                <i class="fas fa-bell text-xl"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?php echo $unreadCount; ?>
                    </span>
                <?php endif; ?>
            </button>
            <div id="notificationMenu" class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-lg py-2 hidden transform opacity-0 transition-all duration-200 max-h-96 overflow-y-auto">
                <div class="px-4 py-2 text-sm text-deep-brown sticky top-0 bg-white border-b">
                    <div class="flex justify-between items-center">
                        <p class="font-medium">Notifications</p>
                        <?php if (count($notifications) > 0): ?>
                            <button id="markAllRead" class="text-xs text-accent-brown hover:text-rich-brown">Mark all as read</button>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="divide-y divide-warm-cream/20">
                    <?php if (count($notifications) > 0): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <li class="px-4 py-3 hover:bg-warm-cream/10 transition-colors duration-150 <?php echo !$notification['is_read'] ? 'bg-warm-cream/5' : ''; ?>">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 pt-1">
                                        <i class="fas fa-<?php 
                                            echo strpos($notification['message'], 'booking') !== false ? 'calendar-check' : 
                                                 (strpos($notification['message'], 'payment') !== false ? 'money-bill-wave' : 'bell');
                                        ?> text-accent-brown"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-deep-brown"><?php echo htmlspecialchars($notification['message']); ?></p>
                                        <p class="text-xs text-gray-500 mt-1"><?php 
                                            $date = new DateTime($notification['created_at']);
                                            echo $date->format('M j, Y g:i A');
                                        ?></p>
                                    </div>
                                    <?php if (!$notification['is_read']): ?>
                                        <div class="flex-shrink-0">
                                            <span class="inline-block h-2 w-2 rounded-full bg-accent-brown"></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="px-4 py-3 text-sm text-center text-gray-500">No notifications yet</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
            <div class="relative">
                <button id="profileDropdown" class="flex items-center space-x-2 hover:bg-warm-cream/10 p-2 rounded-lg transition-all duration-200">
                    <div class="w-10 h-10 rounded-full border-2 border-accent-brown overflow-hidden">
                        <img id="header-profile-pic" src="<?php echo $profilePhotoPath; ?>" alt="Profile" class="w-full h-full object-cover">
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
    const markAllReadButton = document.getElementById('markAllRead');

    // Toggle notification menu
    notificationButton.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = notificationMenu.classList.contains('hidden');
        notificationMenu.classList.toggle('hidden', !isHidden);
        notificationMenu.classList.toggle('opacity-0', !isHidden);
        
        // Close profile menu if open
        profileMenu.classList.add('hidden', 'opacity-0');
        
        // Mark notifications as read when opened
        if (!isHidden) {
            markNotificationsAsRead();
        }
    });

    // Mark all as read button
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function(e) {
            e.stopPropagation();
            markNotificationsAsRead();
        });
    }

    // Function to mark notifications as read
    function markNotificationsAsRead() {
        fetch('adminNotif/mark_notifications_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: <?php echo $_SESSION['user_id']; ?> })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('#notificationMenu li').forEach(li => {
                    li.classList.remove('bg-warm-cream/5');
                    const unreadDot = li.querySelector('.bg-accent-brown');
                    if (unreadDot) {
                        unreadDot.remove();
                    }
                });
                
                // Update badge count
                const badge = notificationButton.querySelector('.bg-red-500');
                if (badge) {
                    badge.remove();
                }
            }
        });
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationButton.contains(e.target) && !notificationMenu.contains(e.target)) {
            notificationMenu.classList.add('hidden', 'opacity-0');
        }
    });
});
</script>