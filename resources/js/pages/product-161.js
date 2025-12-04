import { createApp } from 'vue';
import '../main.css';

// Импорт только Vue компонентов (Faq и Calculator)
// Header и PopupMenu теперь Blade компоненты
import Faq from '../entities/product/ui/Faq.vue';
import Calc161 from '../widgets/product/calculators/Calc161.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc161
    }
});

app.mount('#app');
