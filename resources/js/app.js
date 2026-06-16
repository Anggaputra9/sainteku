import './bootstrap';
import "@fortawesome/fontawesome-free/css/all.min.css";

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Inline script blade (modal) jalan saat parse; modul vite jalan setelahnya
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => Alpine.start());
} else {
    Alpine.start();
}
