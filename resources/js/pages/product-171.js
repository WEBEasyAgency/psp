import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc171 from '../widgets/product/calculators/Calc171.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc171
    }
});

app.mount('#app');
