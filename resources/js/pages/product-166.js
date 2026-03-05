import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc166 from '../widgets/product/calculators/Calc166.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc166
    }
});

app.mount('#app');
