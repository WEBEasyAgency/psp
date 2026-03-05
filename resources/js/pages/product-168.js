import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc168 from '../widgets/product/calculators/Calc168.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc168
    }
});

app.mount('#app');
