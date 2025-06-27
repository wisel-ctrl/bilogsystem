<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');

    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-baskerville { font-family: 'Libre Baskerville', serif; }

    #sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
        transition: width 0.3s ease-in-out;
        position: fixed;
        left: 0;
        top: 0;
        width: 16rem;
        background: #5D2F0F;
        color: #E8E0D5;
        z-index: 40;
    }

    #sidebar.collapsed {
        width: 4rem !important;
    }

    #sidebar .sidebar-header {
        flex-shrink: 0;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(232, 224, 213, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #sidebar.collapsed .sidebar-header {
        padding: 1.5rem 0.75rem;
        justify-content: center;
    }

    #sidebar nav {
        flex: 1;
        overflow-y: auto;
        padding-right: 4px;
        padding-bottom: 1rem;
    }

    .sidebar-link {
        position: relative;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: #E8E0D5;
        font-family: 'Libre Baskerville', serif;
        font-size: 0.9rem;
    }

    #sidebar.collapsed .sidebar-link {
        padding: 0.75rem !important;
        justify-content: center;
    }

    #sidebar.collapsed .sidebar-link i {
        margin: 0 !important;
    }

    #sidebar.collapsed .sidebar-text,
    #sidebar.collapsed .nav-title,
    #sidebar.collapsed .nav-title-short,
    #sidebar.collapsed .nav-subtitle {
        display: none;
    }

    #sidebar:not(.collapsed) .nav-title-short {
        display: none;
    }

    #sidebar.collapsed .nav-title-short {
        display: block;
    }

    .sidebar-link .tooltip {
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #5D2F0F;
        color: #E8E0D5;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        z-index: 50;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        pointer-events: none;
    }

    .sidebar-link .tooltip::before {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: #5D2F0F;
    }

    #sidebar.collapsed .sidebar-link:hover .tooltip {
        opacity: 1;
        visibility: visible;
        left: calc(100% + 0.5rem);
    }

    .sidebar-link.active {
        background: rgba(232, 224, 213, 0.2) !important;
        color: #E8E0D5 !important;
    }

    .sidebar-link:hover {
        background: rgba(232, 224, 213, 0.15) !important;
        transform: translateX(5px);
    }

    #sidebar.collapsed .sidebar-link:hover {
        transform: scale(1.1);
    }

    .sidebar-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: #E8E0D5;
        transition: width 0.3s ease;
    }

    .sidebar-link:hover::after {
        width: 100%;
    }

    /* Improved scrollbar */
    #sidebar nav::-webkit-scrollbar {
        width: 6px;
    }

    #sidebar nav::-webkit-scrollbar-track {
        background: #E8E0D5;
        border-radius: 4px;
    }

    #sidebar nav::-webkit-scrollbar-thumb {
        background: #8B4513;
        border-radius: 4px;
    }

    #sidebar nav::-webkit-scrollbar-thumb:hover {
        background: #5D2F0F;
    }
</style>

<div id="sidebar" class="bg-gradient-to-b from-deep-brown via-rich-brown to-accent-brown text-warm-cream transition-all duration-300 ease-in-out w-64 flex-shrink-0 shadow-2xl">
    <div class="sidebar-header p-6 border-b border-warm-cream/20">
        <div>
            <h1 class="nav-title font-playfair font-bold text-xl text-warm-cream">Caffè Lilio</h1>
            <h1 class="nav-title-short font-playfair font-bold text-xl text-warm-cream hidden">CL</h1>

            <p class="nav-subtitle text-xs text-warm-cream tracking-widest">RISTORANTE</p>
        </div>
    </div>
    
    <nav class="px-4">
        <ul class="space-y-2">
            <li>
                <a href="admin_dashboard.php" class="sidebar-link flex items-center p-3 rounded-lg text-warm-cream hover:bg-warm-cream/20 transition-all duration-200 mt-8 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="sidebar-text font-baskerville">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="admin_bookings.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span class="sidebar-text font-baskerville">Booking Requests</span>
                    <span class="tooltip">Booking Requests</span>
                </a>
            </li>
            <li>
                <a href="admin_menu.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_menu.php' ? 'active' : ''; ?>">
                    <i class="fas fa-utensils w-5"></i>
                    <span class="sidebar-text font-baskerville">Menu Management</span>
                    <span class="tooltip">Menu Management</span>
                </a>
            </li>
            <li>
                <a href="admin_inventory.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_inventory.php' ? 'active' : ''; ?>">
                    <i class="fas fa-boxes w-5"></i>
                    <span class="sidebar-text font-baskerville">Inventory</span>
                    <span class="tooltip">Inventory</span>
                </a>
            </li>
            <li>
                <a href="admin_expenses.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_expenses.php' ? 'active' : ''; ?>">
                    <i class="fas fa-receipt w-5"></i>
                    <span class="sidebar-text font-baskerville">Expenses</span>
                    <span class="tooltip">Expenses</span>
                </a>
            </li>
            <li>
                <a href="admin_employee_creation.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_employee_creation.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus w-5"></i>
                    <span class="sidebar-text font-baskerville">Our Employee</span>
                    <span class="tooltip">Our Employee</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

