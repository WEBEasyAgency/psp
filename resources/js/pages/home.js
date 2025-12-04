import { createApp } from 'vue'

// Импорт только Vue компонента ConstructionTypeSlider
// Header, PopupMenu теперь Blade компоненты
// CasesBlock, FeedbackSlider, ClientsSlider перенесены на Blade + app.min.js
import ConstructionTypeSlider from '../shared/ui/ConstructionTypeSlider.vue'

// Монтирование Construction Type Slider
if (document.getElementById('construction-slider-app')) {
    const constructionApp = createApp(ConstructionTypeSlider)
    constructionApp.mount('#construction-slider-app')
}
