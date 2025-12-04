import { createApp } from 'vue';
import '../main.css';

// Импорт только Vue компонентов (Faq и Calculator)
// Header и PopupMenu теперь Blade компоненты
import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc146 from '../widgets/product/calculators/Calc146.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc146
    }
});

app.mount('#app');
