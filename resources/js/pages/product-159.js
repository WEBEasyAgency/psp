import { createApp } from 'vue';
import '../main.css';

// Импорт только Vue компонентов (Faq и Calculator)
// Header и PopupMenu теперь Blade компоненты
import Faq from '../entities/product/ui/Faq.vue';
import Calc159 from '../widgets/product/calculators/Calc159.vue';

const app = createApp({
    components: {
        Faq,
        Calc159
    }
});

app.mount('#app');
