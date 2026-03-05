import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc163 from '../widgets/product/calculators/Calc163.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc163
    }
});

app.mount('#app');
