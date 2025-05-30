document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle script
    const toggleSidebarBtn = document.getElementById("toggleSidebar");
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener("click", function() {
            document.querySelector("sidebar").classList.toggle("collapsed");
        });
    }

    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // Toggle sidebar when hamburger is clicked
    hamburgerBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        hamburgerBtn.classList.toggle('active');

        if (sidebar.classList.contains('active')) {
            sidebarOverlay.style.display = 'block';
        } else {
            sidebarOverlay.style.display = 'none';
        }
    });

    // Close sidebar when clicking on overlay
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        hamburgerBtn.classList.remove('active');
        sidebarOverlay.style.display = 'none';
    });

    // Close sidebar when clicking a menu item (for mobile)
    const menuItems = document.querySelectorAll('.sidebar ul li a');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                hamburgerBtn.classList.remove('active');
                sidebarOverlay.style.display = 'none';
            }
        });
    });
});