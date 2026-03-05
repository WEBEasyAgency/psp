import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc164 from '../widgets/product/calculators/Calc164.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc164
    }
});

app.mount('#app');
