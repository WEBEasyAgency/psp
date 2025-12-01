import { createApp } from 'vue';
import './main.css';

// Карта компонентов для импорта
const componentPaths = {
  'ProductCalculator': () => import('./widgets/product/ProductCalculator.vue'),
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
