


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
            <li>
                <a href="admin_reports.php" class="sidebar-link flex items-center p-3 rounded-lg hover:bg-warm-cream/20 text-warm-cream/80 hover:text-warm-cream transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admin_reports.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt w-5"></i>
                <span class="sidebar-text font-baskerville">Reports</span>
                    <span class="tooltip">Reports</span>
                </a>
            </li>
        </ul>
    </nav>
</div>



