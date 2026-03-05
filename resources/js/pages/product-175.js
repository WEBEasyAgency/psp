import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc175 from '../widgets/product/calculators/Calc175.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc175
    }
});

app.mount('#app');
