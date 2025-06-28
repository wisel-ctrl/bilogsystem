<?php
// Start output buffering to capture content
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Lilio - <?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Admin Panel'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap">

    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <!-- Add these to your head section -->
    
   
    <script src="../tailwind.js"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-cream': '#E8E0D5',
                        'rich-brown': '#8B4513',
                        'deep-brown': '#5D2F0F',
                        'accent-brown': '#A0522D'
                    },
                    fontFamily: {
                        'serif': ['Georgia', 'serif'],
                        'script': ['Brush Script MT', 'cursive']
                    }
                }
            }
        }
    </script>

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


        /* ... (Include all styles from the original admin_employee_creation.php) ... */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
        
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .modal-hidden {
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
        }
        
        .field-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        
        .field-success {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .floating-label {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        .input-group.has-content input,
        .input-group.has-content select {
            background: rgba(255, 255, 255, 0.9);
            border-color: #8B4513;
        }
        
        .input-group input:focus,
        .input-group select:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.2);
        }
        
        .input-group input::placeholder {
            color: rgba(93, 47, 15, 0.6);
            opacity: 1;
        }
        
        .input-group input:focus::placeholder {
            color: transparent;
        }
        
        .password-strength {
            display: flex;
            gap: 1px;
            margin-bottom: 0.25rem;
        }
        
        .password-strength div {
            height: 0.25rem;
            flex: 1;
            border-radius: 0.125rem;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background-color: #f9fafb;
        }

        thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f3f4f6;
        }

        tbody td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Sidebar improvements */
        .sidebar-link {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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

        #sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            transition: width 0.3s ease-in-out;
            position: relative;
            z-index: 40;
        }

        #sidebar.collapsed {
            width: 4rem !important;
        }

        #sidebar .sidebar-header {
            flex-shrink: 0;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(232, 224, 213, 0.2);
        }

        #sidebar.collapsed .sidebar-header {
            padding: 1.5rem 0.75rem;
        }

        #sidebar nav {
            flex: 1;
            overflow-y: auto;
            padding-right: 4px;
        }

        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
            white-space: nowrap;
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
        #sidebar.collapsed .nav-subtitle {
            display: none;
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
        }

        #sidebar.collapsed .sidebar-link:hover {
            transform: scale(1.1);
        }

        /* Improved scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #E8E0D5;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #5D2F0F;
        }
                /* Add this to your existing style section */
                #profileMenu {
            z-index: 9999 !important;
            transform: translateY(0) !important;
        }

    </style>
</head>
<body class="bg-warm-cream font-baskerville">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <?php include 'header.php'; ?>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 relative">
                <?php echo $page_content; ?>
            </main>
        </div>
    </div>

    <?php
    // Include page-specific scripts if defined
    if (isset($page_scripts)) {
        echo $page_scripts;
    }
    ?>
</body>
</html>
<script>
    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const titleFull = document.querySelector('.nav-title');
    const titleShort = document.querySelector('.nav-title-short');
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

        
    // Load sidebar state from localStorage
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        titleFull.classList.add('hidden');
        titleShort.classList.remove('hidden');
    }

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        if (sidebar.classList.contains('collapsed')) {
            titleFull.classList.add('hidden');
            titleShort.classList.remove('hidden');
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            titleFull.classList.remove('hidden');
            titleShort.classList.add('hidden');
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    }

    sidebarToggle.addEventListener('click', toggleSidebar);

    // Sidebar link click handler
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });

    // Initialize sidebar state
    document.addEventListener('DOMContentLoaded', () => {
        // PHP sets the initial active state, so we only need to ensure JavaScript doesn't override it unnecessarily
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.sidebar-link').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    });




</script>
<?php
// End output buffering and flush
ob_end_flush();
?>