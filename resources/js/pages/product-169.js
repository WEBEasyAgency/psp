import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc169 from '../widgets/product/calculators/Calc169.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc169
    }
});

app.mount('#app');
