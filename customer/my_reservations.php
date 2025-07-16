<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Start output buffering to catch potential output issues
ob_start();

try {
    // Include required files
    if (!file_exists('customer_auth.php') || !file_exists('../db_connect.php')) {
        throw new Exception('Required file(s) not found: customer_auth.php or db_connect.php');
    }
    require_once 'customer_auth.php';
    require_once '../db_connect.php';

    // Check if session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verify user session
    if (!isset($_SESSION['user_id'])) {
        error_log('Session user_id not set. Redirecting to login.');
        header('Location: login.php');
        exit;
    }

    // Fetch bookings for the logged-in user using PDO
    $user_id = $_SESSION['user_id'];
    if (!isset($conn) || !$conn instanceof PDO) {
        throw new Exception('Invalid PDO database connection');
    }

    // Get selected status filter (default to 'pending')
    $status_filter = isset($_GET['status']) ? $_GET['status'] : 'pending';
    $valid_statuses = ['pending', 'accepted', 'declined', 'done', 'cancel', 'all'];
    if (!in_array($status_filter, $valid_statuses)) {
        $status_filter = 'pending';
    }

    // Pagination settings
    $items_per_page = 5;
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Get total number of bookings for pagination
    $count_query = "SELECT COUNT(*) as total 
                    FROM booking_tb 
                    WHERE customer_id = :user_id";
    if ($status_filter !== 'all') {
        $count_query .= " AND booking_status = :status";
    }
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    if ($status_filter !== 'all') {
        $count_stmt->bindValue(':status', $status_filter, PDO::PARAM_STR);
    }
    $count_stmt->execute();
    $total_bookings = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_bookings / $items_per_page);

    // Fetch bookings for current page
    $query = "SELECT booking_id, reservation_datetime, event, pax, booking_status, decline_reason 
              FROM booking_tb 
              WHERE customer_id = :user_id";
    if ($status_filter !== 'all') {
        $query .= " AND booking_status = :status";
    }
    $query .= " ORDER BY reservation_datetime ASC 
                LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Query preparation failed: ' . implode(' | ', $conn->errorInfo()));
    }
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    if ($status_filter !== 'all') {
        $stmt->bindValue(':status', $status_filter, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    if (!$stmt->execute()) {
        throw new Exception('Query execution failed: ' . implode(' | ', $stmt->errorInfo()));
    }
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} catch (Exception $e) {
    error_log('Error in my_reservations.php: ' . $e->getMessage());
    http_response_code(500);
    echo '<!DOCTYPE html><html><body><h1>Error</h1><p>An error occurred: ' . htmlspecialchars($e->getMessage()) . '</p><p>Please try again later or contact support.</p></body></html>';
    ob_end_flush();
    exit;
}

ob_end_flush();

$page_title = "My Reservations - Caffè Lilio";

ob_start();
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
    

    
    .hover-lift {
        transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .hover-lift:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(93, 47, 15, 0.15);
    }

    .bg-warm-gradient {
        background: linear-gradient(135deg, #E8E0D5, #d4c8b9);
    }

    .bg-card {
        background: linear-gradient(145deg, #E8E0D5, #d6c7b6);
        backdrop-filter: blur(8px);
    }

    .bg-nav {
        background: linear-gradient(to bottom, #5D2F0F, #4a260d);
    }

    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    .skeleton {
        background: linear-gradient(90deg, #E8E0D5 25%, #d4c8b9 50%, #E8E0D5 75%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }

    :focus {
        outline: 2px solid #8B4513;
        outline-offset: 2px;
    }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #E8E0D5;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: 8 Black 4513;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #5D2F0F;
    }

    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 1rem;
        border-radius: 8px;
        background: #E8E0D5;
        box-shadow: 0 4px 12px rgba(93, 47, 15, 0.15);
        transform: translateY(100%);
        opacity: 0;
        transition: all 0.3s ease-in-out;
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .btn-primary {
        position: relative;
        overflow: hidden;
        background: #8B4513;
        color: #E8E0D5;
    }

    .btn-primary::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(232, 224, 213, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-primary:active::after {
        width: 200%;
        height: 200%;
    }

    .mobile-menu {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .mobile-menu.open {
        transform: translateX(0);
    }

    .skeleton-text {
        height: 1em;
        background: #e0e0e0;
        margin: 0.5em 0;
        border-radius: 4px;
    }

    input, select, textarea {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #8B4513;
        box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
    }

    .otp-input {
        width: 3rem;
        height: 3rem;
        text-align: center;
        font-size: 1.25rem;
        border: 1px solid #8B4513;
        border-radius: 0.375rem;
    }

    .otp-input:focus {
        outline: none;
        border-color: #5D2F0F;
        box-shadow: 0 0 0 2px rgba(93, 47, 15, 0.2);
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }

    .pagination button {
        padding: 0.5rem 1rem;
        border: 1px solid #8B4513;
        border-radius: 0.375rem;
        background: #E8E0D5;
        color: #5D2F0F;
        font-family: 'Libre Baskerville', serif;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .pagination button:hover {
        background: #8B4513;
        color: #E8E0D5;
    }

    .pagination button:disabled {
        background: #d4c8b9;
        color: #a89b8c;
        cursor: not-allowed;
        border-color: #a89b8c;
    }

    .pagination .active {
        background: #8B4513;
        color: #E8E0D5;
        font-weight: bold;
    }

    .status-filter {
        border: 1px solid #8B4513;
        border-radius: 0.375rem;
        padding: 0.5rem;
        background: #E8E0D5;
        color: #5D2F0F;
        font-family: 'Libre Baskerville', serif;
    }

    .status-filter:focus {
        border-color: #5D2F0F;
        box-shadow: 0 0 0 2px rgba(93, 47, 15, 0.2);
    }
</style>

<!-- Loading Progress Bar -->
<div id="nprogress-container"></div>

<!-- Toast Notifications Container -->
<div id="toast-container"></div>

<!-- Main Content -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-playfair text-2xl font-bold text-deep-brown">My Reservations</h3>
        <div class="flex items-center space-x-4">
            <select id="status-filter" class="status-filter">
                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="accepted" <?php echo $status_filter === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                <option value="declined" <?php echo $status_filter === 'declined' ? 'selected' : ''; ?>>Declined</option>
                <option value="done" <?php echo $status_filter === 'done' ? 'selected' : ''; ?>>Done</option>
                <option value="cancel" <?php echo $status_filter === 'cancel' ? 'selected' : ''; ?>>Cancelled</option>
                <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All</option>
            </select>
            <a href="bookingpage.php" class="btn-primary bg-rich-brown text-warm-cream px-6 py-3 rounded-lg font-baskerville hover:bg-deep-brown transition-all duration-300 flex items-center space-x-2">
                <span>Make New Reservation</span>
                <i class="fas fa-calendar-plus"></i>
            </a>
        </div>
    </div>
    <div class="bg-white/50 rounded-xl p-6 shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full" role="table">
                <thead>
                    <tr class="border-b border-deep-brown/20">
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Date & Time</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Event Type</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Guests</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Status</th>
                        <?php if ($status_filter === 'pending' || $status_filter === 'all'): ?>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="bookings-table-body">
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="<?php echo ($status_filter === 'pending' || $status_filter === 'all') ? 5 : 4; ?>" class="py-4 px-4 text-center font-baskerville text-deep-brown">
                                No reservations found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="border-b border-deep-brown/10 hover:bg-deep-brown/5 transition-colors duration-300">
                                <td class="py-4 px-4">
                                    <div class="font-baskerville text-deep-brown">
                                        <?php echo date('F d, Y', strtotime($booking['reservation_datetime'])); ?>
                                    </div>
                                    <div class="text-sm text-deep-brown/60">
                                        <?php echo date('h:i A', strtotime($booking['reservation_datetime'])); ?>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-baskerville text-deep-brown">
                                    <?php echo htmlspecialchars($booking['event'] ?? 'Not Specified'); ?>
                                </td>
                                <td class="py-4 px-4 font-baskerville text-deep-brown">
                                    <?php echo $booking['pax']; ?>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center border border-deep-brown/10
                                        <?php 
                                        if ($booking['booking_status'] === 'accepted') {
                                            echo 'bg-warm-cream/50 text-deep-brown';
                                        } elseif ($booking['booking_status'] === 'declined') {
                                            echo 'bg-red-100/50 text-red-800';
                                        } elseif ($booking['booking_status'] === 'pending') {
                                            echo 'bg-yellow-100/50 text-yellow-800';
                                        } elseif ($booking['booking_status'] === 'done') {
                                            echo 'bg-green-100/50 text-green-800';
                                        } else {
                                            echo 'bg-gray-100/50 text-gray-800';
                                        }
                                        ?>">
                                        <span class="w-2 h-2 rounded-full mr-2 
                                            <?php 
                                            if ($booking['booking_status'] === 'accepted') {
                                                echo 'bg-green-500';
                                            } elseif ($booking['booking_status'] === 'declined') {
                                                echo 'bg-red-500';
                                            } elseif ($booking['booking_status'] === 'pending') {
                                                echo 'bg-yellow-500';
                                            } elseif ($booking['booking_status'] === 'done') {
                                                echo 'bg-green-500';
                                            } else {
                                                echo 'bg-gray-500';
                                            }
                                            ?>">
                                        </span>
                                        <?php echo ucfirst($booking['booking_status']); ?>
                                    </span>
                                    <?php if ($booking['booking_status'] === 'declined' && !empty($booking['decline_reason'])): ?>
                                        <button class="ml-2 text-red-600 hover:text-red-700 transition-colors duration-300 p-1 rounded-full hover:bg-red-50 view-reason"
                                                data-reason="<?php echo htmlspecialchars($booking['decline_reason']); ?>"
                                                data-tippy-content="View decline reason">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <?php if ($status_filter === 'pending' || $status_filter === 'all'): ?>
                                    <td class="py-4 px-4">
                                        <?php if ($booking['booking_status'] === 'pending'): ?>
                                            <div class="flex space-x-2">
                                                <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-red-50 cancel-reservation"
                                                        data-booking-id="<?php echo $booking['booking_id']; ?>"
                                                        data-tippy-content="Cancel reservation">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination" id="pagination">
            <?php if ($total_pages > 1): ?>
                <button data-page="1" <?php echo $current_page == 1 ? 'disabled' : ''; ?>>First</button>
                <button data-page="<?php echo $current_page - 1; ?>" <?php echo $current_page == 1 ? 'disabled' : ''; ?>>Previous</button>
                <?php
                $start_page = max(1, $current_page - 1);
                $end_page = min($total_pages, $current_page + 1);
                
                if ($end_page - $start_page < 2) {
                    if ($start_page == 1) {
                        $end_page = min($total_pages, $start_page + 2);
                    } elseif ($end_page == $total_pages) {
                        $start_page = max(1, $end_page - 2);
                    }
                }
        
                for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <button data-page="<?php echo $i; ?>" class="<?php echo $i == $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </button>
                <?php endfor; ?>
                <button data-page="<?php echo $current_page + 1; ?>" <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>>Next</button>
                <button data-page="<?php echo $total_pages; ?>" <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>>Last</button>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TikTok pixel
    !function (w, d, t) {
        w.TiktokAnalyticsObject = t;
        var ttq = w[t] = w[t] || [];
        ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"];
        ttq.setAndDefer = function(t, e) {
            t[e] = function() {
                t.push([e].concat(Array.prototype.slice.call(arguments, 0)));
            };
        };
        for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
        ttq.instance = function(t) {
            for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
            return e;
        };
        ttq.load = function(e, n) {
            var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
            ttq._i = ttq._i || {};
            ttq._i[e] = [];
            ttq._i[e]._u = i;
            ttq._t = ttq._t || {};
            ttq._t[e] = +new Date;
            ttq._o = ttq._o || {};
            ttq._o[e] = n || {};
            var o = document.createElement("script");
            o.type = "text/javascript";
            o.async = !0;
            o.src = i + "?sdkid=" + e + "&lib=" + t;
            var a = document.getElementsByTagName("script")[0];
            a.parentNode.insertBefore(o, a);
        };

        ttq.load('CNFM3GRC77U8P7G7AATG');
        ttq.page();
    }(window, document, 'ttq');

    // Initialize tooltips
    tippy('[data-tippy-content]', {
        theme: 'custom',
        animation: 'scale',
        duration: [200, 150],
        placement: 'bottom'
    });

    // Initialize loading bar
    NProgress.configure({ 
        showSpinner: false,
        minimum: 0.3,
        easing: 'ease',
        speed: 500
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-${type === 'success' ? 'check-circle text-green-500' : 'exclamation-circle text-red-500'}"></i>
                <span>${message}</span>
            </div>
        `;
        document.getElementById('toast-container').appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 100);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

// AJAX Pagination with proper column handling
function loadBookings(page, status = 'pending') {
    NProgress.start();
    fetch(`reservationsAPI/fetch_reservations.php?page=${page}&status=${status}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        NProgress.done();
        const tbody = document.getElementById('bookings-table-body');
        const pagination = document.getElementById('pagination');
        
        if (!tbody) {
            showToast('Error: Table body not found', 'error');
            console.error('Table body element not found');
            return;
        }

        // Determine if actions column should be shown
        const showActionsColumn = (status === 'pending' || status === 'all');
        const colspan = showActionsColumn ? 5 : 4;

        // Update table header to show/hide Actions column
        const tableHeader = document.querySelector('thead tr');
        if (tableHeader) {
            const actionsHeader = tableHeader.querySelector('th:last-child');
            if (showActionsColumn) {
                if (!actionsHeader || !actionsHeader.textContent.includes('Action')) {
                    tableHeader.innerHTML = `
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Date & Time</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Event Type</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Guests</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Status</th>
                        <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Action</th>
                    `;
                }
            } else {
                tableHeader.innerHTML = `
                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Date & Time</th>
                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Event Type</th>
                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Guests</th>
                    <th class="text-left py-3 px-4 font-baskerville text-deep-brown" scope="col">Status</th>
                `;
            }
        }

        // Update table body
        tbody.innerHTML = '';
        if (!data.success || data.bookings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="${colspan}" class="py-4 px-4 text-center font-baskerville text-deep-brown">
                        No reservations found
                    </td>
                </tr>
            `;
        } else {
            data.bookings.forEach(booking => {
                const statusClass = booking.booking_status === 'accepted' ? 'bg-warm-cream/50 text-deep-brown' :
                                 booking.booking_status === 'declined' ? 'bg-red-100/50 text-red-800' :
                                 booking.booking_status === 'pending' ? 'bg-yellow-100/50 text-yellow-800' :
                                 booking.booking_status === 'done' ? 'bg-green-100/50 text-green-800' :
                                 'bg-gray-100/50 text-gray-800';
                const statusDotClass = booking.booking_status === 'accepted' ? 'bg-green-500' :
                                     booking.booking_status === 'declined' ? 'bg-red-500' :
                                     booking.booking_status === 'pending' ? 'bg-yellow-500' :
                                     booking.booking_status === 'done' ? 'bg-green-500' :
                                     'bg-gray-500';
                const declineReason = booking.booking_status === 'declined' && booking.decline_reason ? `
                    <button class="ml-2 text-red-600 hover:text-red-700 transition-colors duration-300 p-1 rounded-full hover:bg-red-50 view-reason"
                            data-reason="${booking.decline_reason}"
                            data-tippy-content="View decline reason">
                        <i class="fas fa-info-circle"></i>
                    </button>` : '';
                
                // Only show actions column if status is 'pending' or 'all'
                const actionColumn = showActionsColumn && booking.booking_status === 'pending' ? `
                    <td class="py-4 px-4">
                        <div class="flex space-x-2">
                            <button class="text-red-600 hover:text-red-700 transition-colors duration-300 p-2 rounded-full hover:bg-red-50 cancel-reservation"
                                    data-booking-id="${booking.booking_id}"
                                    data-tippy-content="Cancel reservation">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>` : (showActionsColumn ? '<td class="py-4 px-4"></td>' : '');
                
                tbody.innerHTML += `
                    <tr class="border-b border-deep-brown/10 hover:bg-deep-brown/5 transition-colors duration-300">
                        <td class="py-4 px-4">
                            <div class="font-baskerville text-deep-brown">
                                ${new Date(booking.reservation_datetime).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}
                            </div>
                            <div class="text-sm text-deep-brown/60">
                                ${new Date(booking.reservation_datetime).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}
                            </div>
                        </td>
                        <td class="py-4 px-4 font-baskerville text-deep-brown">
                            ${booking.event || 'Not Specified'}
                        </td>
                        <td class="py-4 px-4 font-baskerville text-deep-brown">
                            ${booking.pax}
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-sm font-baskerville inline-flex items-center border border-deep-brown/10 ${statusClass}">
                                <span class="w-2 h-2 rounded-full mr-2 ${statusDotClass}"></span>
                                ${booking.booking_status.charAt(0).toUpperCase() + booking.booking_status.slice(1)}
                            </span>
                            ${declineReason}
                        </td>
                        ${actionColumn}
                    </tr>
                `;
            });
            
            // Reinitialize tooltips for new elements
            tippy('[data-tippy-content]', {
                theme: 'custom',
                animation: 'scale',
                duration: [200, 150],
                placement: 'bottom'
            });

            // Reinitialize view reason buttons
            document.querySelectorAll('.view-reason').forEach(button => {
                button.addEventListener('click', function() {
                    const reason = this.getAttribute('data-reason');
                    const reasonDialog = document.createElement('div');
                    reasonDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                    reasonDialog.innerHTML = `
                        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                            <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Decline Reason</h3>
                            <div class="bg-red-50/50 border border-red-100 rounded-lg p-4 mb-4">
                                <p class="text-red-800 font-baskerville">${reason}</p>
                            </div>
                            <div class="flex justify-end">
                                <button class="px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition-colors duration-300" id="close-reason">
                                    Close
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(reasonDialog);
                    document.body.classList.add('overflow-hidden');

                    document.getElementById('close-reason').addEventListener('click', () => {
                        reasonDialog.remove();
                        document.body.classList.remove('overflow-hidden');
                    });
                });
            });
        }

        // Update pagination
        if (pagination) {
            pagination.innerHTML = '';
            if (data.total_pages > 1) {
                const start_page = Math.max(1, data.current_page - 1);
                const end_page = Math.min(data.total_pages, data.current_page + 1);
                
                pagination.innerHTML += `
                    <button data-page="1" ${data.current_page == 1 ? 'disabled' : ''}>First</button>
                    <button data-page="${data.current_page - 1}" ${data.current_page == 1 ? 'disabled' : ''}>Previous</button>
                `;
                
                for (let i = start_page; i <= end_page; i++) {
                    pagination.innerHTML += `
                        <button data-page="${i}" ${i == data.current_page ? 'class="active"' : ''}>
                            ${i}
                        </button>
                    `;
                }
                
                pagination.innerHTML += `
                    <button data-page="${data.current_page + 1}" ${data.current_page == data.total_pages ? 'disabled' : ''}>Next</button>
                    <button data-page="${data.total_pages}" ${data.current_page == data.total_pages ? 'disabled' : ''}>Last</button>
                `;
                
                // Add event listeners to new pagination buttons
                document.querySelectorAll('#pagination button').forEach(button => {
                    button.addEventListener('click', () => {
                        const page = button.getAttribute('data-page');
                        if (page && !button.disabled) {
                            loadBookings(page, document.getElementById('status-filter').value);
                        }
                    });
                });
            }
        } else {
            console.error('Pagination element not found');
            showToast('Error: Pagination container not found', 'error');
        }
    })
    .catch(error => {
        NProgress.done();
        console.error('Error loading bookings:', error);
        showToast(`Error loading bookings: ${error.message}`, 'error');
    });
}

    // Initialize status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        loadBookings(1, this.value);
    });

    // Initialize pagination buttons
    document.querySelectorAll('#pagination button').forEach(button => {
        button.addEventListener('click', () => {
            const page = button.getAttribute('data-page');
            if (page && !button.disabled) {
                loadBookings(page, document.getElementById('status-filter').value);
            }
        });
    });

    // Handle reservation cancellation using event delegation
    document.getElementById('bookings-table-body').addEventListener('click', function(event) {
        const button = event.target.closest('.cancel-reservation');
        if (button) {
            event.preventDefault();
            const bookingId = button.getAttribute('data-booking-id');
            
            const confirmDialog = document.createElement('div');
            confirmDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
            confirmDialog.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                    <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Cancel Reservation?</h3>
                    <p class="text-deep-brown/80 mb-6">Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                    <div class="flex justify-end space-x-4">
                        <button class="px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300" id="cancel-dialog">
                            Keep Reservation
                        </button>
                        <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors duration-300" id="confirm-cancel">
                            Yes, Cancel
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(confirmDialog);
            document.body.classList.add('overflow-hidden');

            document.getElementById('cancel-dialog').addEventListener('click', () => {
                confirmDialog.remove();
                document.body.classList.remove('overflow-hidden');
            });

            document.getElementById('confirm-cancel').addEventListener('click', () => {
                confirmDialog.remove();
                NProgress.start();

                fetch('reservationsAPI/otp_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=generate_otp&booking_id=${bookingId}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    NProgress.done();
                    if (!data.success) {
                        showToast(data.message || 'Failed to send OTP', 'error');
                        document.body.classList.remove('overflow-hidden');
                        return;
                    }

                    const otpDialog = document.createElement('div');
                    otpDialog.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                    otpDialog.innerHTML = `
                        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                            <h3 class="font-playfair text-xl font-bold mb-4 text-deep-brown">Enter OTP</h3>
                            <p class="text-deep-brown/80 mb-4">An OTP has been sent to your registered phone number.</p>
                            <form id="otp-form">
                                <div class="flex justify-center space-x-2 mb-6">
                                    <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                    <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                    <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                    <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                    <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                    <input type="text" maxlength="1" class="otp-input" placeholder="0" required>
                                </div>
                                <p class="text-deep-brown/80 mb-4 text-sm">Didn't receive OTP? <a href="#" id="resend-otp" class="text-rich-brown hover:underline">Resend OTP</a></p>
                                <div class="flex justify-end space-x-4">
                                    <button type="button" class="px-4 py-2 rounded-lg text-deep-brown hover:bg-deep-brown/10 transition-colors duration-300" id="cancel-otp">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-rich-brown text-white hover:bg-deep-brown transition-colors duration-300" id="verify-otp">
                                        Verify OTP
                                    </button>
                                </div>
                            </form>
                        </div>
                    `;
                    
                    document.body.appendChild(otpDialog);

                    const otpInputs = otpDialog.querySelectorAll('.otp-input');
                    otpInputs.forEach((input, index) => {
                        input.addEventListener('input', (e) => {
                            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                                otpInputs[index + 1].focus();
                            }
                        });
                        input.addEventListener('keydown', (e) => {
                            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                                otpInputs[index - 1].focus();
                            }
                        });
                    });

                    document.getElementById('cancel-otp').addEventListener('click', () => {
                        otpDialog.remove();
                        document.body.classList.remove('overflow-hidden');
                    });

                    document.getElementById('resend-otp').addEventListener('click', (e) => {
                        e.preventDefault();
                        NProgress.start();
                        fetch('reservationsAPI/otp_handler.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=generate_otp&booking_id=${bookingId}`
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            NProgress.done();
                            if (data.success) {
                                showToast('New OTP has been sent to your phone', 'success');
                                otpInputs.forEach(input => input.value = '');
                                otpInputs[0].focus();
                            } else {
                                showToast(data.message || 'Failed to resend OTP', 'error');
                            }
                        })
                        .catch(error => {
                            NProgress.done();
                            showToast('Error resending OTP: ' + error.message, 'error');
                        });
                    });
                    
                    document.getElementById('otp-form').addEventListener('submit', (e) => {
                        e.preventDefault();
                        const otp = Array.from(otpInputs).map(input => input.value).join('');
                        if (otp.length !== 6) {
                            showToast('Please enter a complete 6-digit OTP', 'error');
                            return;
                        }
                    
                        NProgress.start();
                        fetch('reservationsAPI/otp_handler.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=verify_otp&otp=${otp}&booking_id=${bookingId}`
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            NProgress.done();
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Reservation cancelled successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    willClose: () => {
                                        otpDialog.remove();
                                        document.body.classList.remove('overflow-hidden');
                                        loadBookings(1, document.getElementById('status-filter').value);
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'Invalid OTP. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'Try Again'
                                }).then(() => {
                                    otpInputs.forEach(input => input.value = '');
                                    otpInputs[0].focus();
                                });
                            }
                        })
                        .catch(error => {
                            NProgress.done();
                            showToast('Error verifying OTP: ' + error.message, 'error');
                        });
                    });
                })
                .catch(error => {
                    document.body.classList.remove('overflow-hidden');
                    NProgress.done();
                    showToast('Error sending OTP: ' + error.message, 'error');
                });
            });
        }
    });

    // Load notifications
    function loadNotifications() {
        const notificationsContainer = document.querySelector('#notifications-button + div .animate-pulse');
        setTimeout(() => {
            if (notificationsContainer) {
                notificationsContainer.innerHTML = `
                    <div class="p-4 border-b border-deep-brown/10">
                        <p class="font-baskerville text-deep-brown">New special offer available!</p>
                        <p class="text-sm text-deep-brown/60">Check out our weekend buffet special.</p>
                    </div>
                `;
            }
        }, 1000);
    }

    loadNotifications();
});
</script>
<?php
$content = ob_get_clean();
include 'layout_customer.php';
?>