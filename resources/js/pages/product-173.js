import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc173 from '../widgets/product/calculators/Calc173.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc173
    }
});

app.mount('#app');
