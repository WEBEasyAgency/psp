import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc172 from '../widgets/product/calculators/Calc172.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc172
    }
});

app.mount('#app');
