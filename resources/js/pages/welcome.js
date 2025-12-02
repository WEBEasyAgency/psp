import { createApp } from 'vue';
import '../main.css';

// Импорт компонентов
import Header from '../entities/product/ui/Header.vue';
import Footer from '../entities/product/ui/Footer.vue';
import Feedback from '../entities/product/ui/Feedback.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';

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

// Монтирование галереи отзывов
if (document.getElementById('feedback-app')) {
    const feedbackApp = createApp(Feedback);
    feedbackApp.mount('#feedback-app');
}

// Монтирование галереи кейсов
if (document.getElementById('cases-app')) {
    const casesApp = createApp(InstallationCases);
    casesApp.mount('#cases-app');
}
