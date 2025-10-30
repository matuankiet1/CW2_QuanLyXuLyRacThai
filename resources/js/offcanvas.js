// Offcanvas functionality for dropdown menus
document.addEventListener('DOMContentLoaded', function () {
    // Handle user dropdown menu
    const userDropdownBtn = document.querySelector('.btn-user-dropdown');
    const userDropdownMenu = document.querySelector('.menu-user-dropdown');
    
    if (userDropdownBtn && userDropdownMenu) {
        userDropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = userDropdownMenu.classList.contains('opacity-100');
            
            // Close all dropdowns first
            document.querySelectorAll('.menu-user-dropdown').forEach(menu => {
                menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            });
            
            // Toggle current dropdown
            if (!isOpen) {
                userDropdownMenu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                userDropdownMenu.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!userDropdownMenu.contains(e.target) && !userDropdownBtn.contains(e.target)) {
                userDropdownMenu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                userDropdownMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            }
        });
        
        // Handle menu items
        const menuItems = userDropdownMenu.querySelectorAll('[role="menuItemUserDropdown"]');
        menuItems.forEach(item => {
            item.addEventListener('click', function (e) {
                if (this.textContent.trim() === 'Đăng xuất') {
                    e.preventDefault();
                    if (confirm('Bạn có chắc muốn đăng xuất?')) {
                        document.querySelector('form[action*="logout"]').submit();
                    }
                }
            });
        });
    }
});
