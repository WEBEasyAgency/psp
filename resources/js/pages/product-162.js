import { createApp } from 'vue';
import '../main.css';

import Faq from '../entities/product/ui/Faq.vue';
import InstallationCases from '../entities/product/ui/InstallationCases.vue';
import Calc162 from '../widgets/product/calculators/Calc162.vue';

const app = createApp({
    components: {
        Faq,
        InstallationCases,
        Calc162
    }
});

app.mount('#app');
