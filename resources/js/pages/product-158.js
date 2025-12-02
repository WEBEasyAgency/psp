import { createApp } from 'vue';
import '../main.css';

// Импорт компонентов
import Header from '../entities/product/ui/Header.vue';
import TechnologyAdvantages from '../entities/product/ui/TechnologyAdvantages.vue';
import Faq from '../entities/product/ui/Faq.vue';
import SeoBlock from '../entities/product/ui/SeoBlock.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Feedback from '../entities/product/ui/Feedback.vue';
import Footer from '../entities/product/ui/Footer.vue';
import PopupMenu from '../entities/product/ui/PopupMenu.vue';
import Calc158 from '../widgets/product/calculators/Calc158.vue';

const app = createApp({
    components: {
        AppHeader: Header,
        TechnologyAdvantages,
        Faq,
        SeoBlock,
        InstallationCases,
        Feedback,
        AppFooter: Footer,
        PopupMenu,
        Calc158
    }
});

app.mount('#app');
