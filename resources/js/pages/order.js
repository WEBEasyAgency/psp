import { createApp } from 'vue';
import '../main.css';

// Импорт компонентов
import Header from '../entities/product/ui/Header.vue';
import Footer from '../entities/product/ui/Footer.vue';

// Монтирование Header
if (document.getElementById('header-app')) {
    const headerApp = createApp(Header);
    headerApp.mount('#header-app');
}

// Монтирование Footer
if (document.getElementById('footer-app')) {
    const footerApp = createApp(Footer);
    footerApp.mount('#footer-app');
}
