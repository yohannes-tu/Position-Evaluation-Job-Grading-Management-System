import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');

    if (sidebar) {
        sidebar.classList.toggle('open');
    }
};