import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc170 from '../widgets/product/calculators/Calc170.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc170
    }
});

app.mount('#app');
