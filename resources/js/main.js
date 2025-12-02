import { createApp } from 'vue';
import './main.css';

// Карта компонентов для импорта
const componentPaths = {
  'Calc146': () => import('./widgets/product/calculators/Calc146.vue'),
  'Calc155': () => import('./widgets/product/calculators/Calc155.vue'),
  'Calc156': () => import('./widgets/product/calculators/Calc156.vue'),
  'Calc157': () => import('./widgets/product/calculators/Calc157.vue'),
};

// Автоматически монтируем все компоненты с атрибутом data-vue-component
document.addEventListener('DOMContentLoaded', () => {
  const vueComponents = document.querySelectorAll('[data-vue-component]');

  vueComponents.forEach((el) => {
    const componentName = el.getAttribute('data-vue-component');
    const propsData = el.getAttribute('data-vue-props');
    const props = propsData ? JSON.parse(propsData) : {};

    // Динамический импорт компонента из карты
    const importComponent = componentPaths[componentName];

    if (!importComponent) {
      console.error(`Component not found in component map: ${componentName}`);
      return;
    }

    importComponent()
      .then((module) => {
        const app = createApp(module.default, props);
        app.mount(el);
      })
      .catch((err) => {
        console.error(`Failed to load component: ${componentName}`, err);
      });
  });
});
