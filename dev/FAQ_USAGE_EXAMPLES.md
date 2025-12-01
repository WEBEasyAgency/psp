# Примеры использования FAQ блока

## 1. Базовое использование (нативный HTML5, БЕЗ JS)

Это рекомендуемый вариант для большинства случаев.

### Преимущества:
- Работает без JavaScript
- Минимальный размер файла
- Отличная производительность
- Встроенная доступность

### Как использовать:
Просто используйте HTML с `<details>` и `<summary>` элементами, как реализовано в `product.html`.

**Файл**: `F:\CODE\easy_web\psp\dev\product.html` (строки 269-456)

---

## 2. Использование с усовершенствованным JS

Для более сложной функциональности добавьте скрипт `faq-enhanced.js`.

### Функции:
- ✅ Только один открытый элемент одновременно
- ✅ Отслеживание аналитики (Google Analytics, Yandex.Metrica)
- ✅ Программное управление состоянием
- ✅ Callbacks для интеграции

### Установка:

#### Вариант 1: Прямое включение в HTML

```html
<!-- Перед </body> -->
<script src="/faq-enhanced.js"></script>
<script>
  // Инициализация с параметрами
  const faq = new FaqAccordion('.product-faq', {
    singleOpen: true,              // Только один открытый элемент
    animationDuration: 300,        // Длительность анимации в ms
    trackAnalytics: true,          // Отслеживать клики
    onToggle: (state) => {
      console.log('FAQ toggled:', state);
    }
  });
</script>
```

#### Вариант 2: Как ES модуль

```javascript
import FaqAccordion from './faq-enhanced.js';

const faq = new FaqAccordion('.product-faq', {
  singleOpen: true,
  onToggle: (state) => {
    // Ваш код при переключении
    console.log(`Вопрос ${state.index} открыт: ${state.isOpen}`);
  }
});
```

---

## 3. API методы FaqAccordion

### Инициализация

```javascript
const faq = new FaqAccordion('.product-faq', options);
```

### Методы

#### `openAll()`
Открыть все элементы
```javascript
faq.openAll();
```

#### `closeAll()`
Закрыть все элементы
```javascript
faq.closeAll();
```

#### `toggle(index)`
Переключить состояние элемента
```javascript
faq.toggle(0); // Переключить первый элемент
```

#### `open(index)`
Открыть элемент по индексу
```javascript
faq.open(1); // Открыть второй элемент
```

#### `close(index)`
Закрыть элемент по индексу
```javascript
faq.close(1);
```

#### `getState()`
Получить состояние всех элементов
```javascript
const state = faq.getState();
console.log(state);
// [
//   { index: 0, isOpen: true, question: "Как долго..." },
//   { index: 1, isOpen: false, question: "Можно ли..." },
//   ...
// ]
```

---

## 4. Примеры интеграции

### Пример 1: Аналитика Google Analytics

```html
<script>
  const faq = new FaqAccordion('.product-faq', {
    onToggle: (state) => {
      gtag('event', 'faq_toggle', {
        event_category: 'engagement',
        event_label: state.question,
        value: state.isOpen ? 1 : 0
      });
    }
  });
</script>
```

### Пример 2: Yandex.Metrica

```html
<script>
  const faq = new FaqAccordion('.product-faq', {
    onToggle: (state) => {
      if (window.ym) {
        ym(METRICA_ID, 'reachGoal', 'faq_' + state.index);
      }
    }
  });
</script>
```

### Пример 3: Синхронизация с формой

```html
<script>
  const faq = new FaqAccordion('.product-faq', {
    onToggle: (state) => {
      // Заполнить скрытое поле формы при открытии FAQ
      if (state.isOpen) {
        document.getElementById('faq_topic').value = state.question;
      }
    }
  });
</script>
```

### Пример 4: Открытие определенного FAQ при загрузке страницы

```javascript
// Если URL содержит #faq-1, открыть 1-й элемент
document.addEventListener('DOMContentLoaded', () => {
  const faq = new FaqAccordion('.product-faq');

  const hash = window.location.hash;
  if (hash.startsWith('#faq-')) {
    const index = parseInt(hash.replace('#faq-', ''));
    faq.open(index);
  }
});
```

---

## 5. Изменение стилей

### Через CSS (встроено в HTML)

Отредактируйте `<style>` в `<head>` файла `product.html`:

```css
/* Изменить цвет открытого состояния */
.product-faq details[open] .faq-icon {
  background-color: #YOUR_COLOR !important;
}

/* Изменить цвет текста */
.product-faq details[open] summary div:last-child {
  color: #YOUR_COLOR !important;
}

/* Изменить фон градиента */
.product-faq details[open] {
  background: linear-gradient(141deg, #color1, #color2) !important;
}

/* Изменить скорость анимации */
.product-faq .faq-icon svg {
  transition: transform 0.5s ease; /* Было 0.3s */
}
```

### Через JavaScript

```javascript
const style = document.createElement('style');
style.textContent = `
  .product-faq details[open] .faq-icon {
    background-color: #FF5733 !important;
  }
`;
document.head.appendChild(style);
```

---

## 6. Темизация (мобильная vs десктопная)

### Разные стили для мобильной версии

```css
/* Мобильная версия (375px) */
.product-faq .xl\:hidden details[open] {
  background: linear-gradient(...) !important;
  padding: 1rem;
}

/* Десктопная версия */
.product-faq .hidden.xl\:block details[open] {
  background: linear-gradient(...) !important;
  padding: 1.5rem;
}
```

---

## 7. Тестирование

### Проверка функциональности в консоли браузера

```javascript
// Инициализировать
const faq = new FaqAccordion('.product-faq');

// Получить состояние
console.log(faq.getState());

// Открыть первый элемент
faq.open(0);

// Закрыть все
faq.closeAll();

// Открыть все
faq.openAll();
```

---

## 8. Интеграция с Vue (если требуется)

Если нужно управлять FAQ из Vue компонента:

```vue
<template>
  <div class="product-faq">
    <!-- Ваш FAQ HTML -->
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import FaqAccordion from './faq-enhanced.js';

let faq = null;

onMounted(() => {
  faq = new FaqAccordion('.product-faq', {
    onToggle: (state) => {
      console.log('FAQ state changed:', state);
    }
  });
});

// Методы для управления из Vue
const openFaq = (index) => faq?.open(index);
const closeFaq = (index) => faq?.close(index);
const closeAll = () => faq?.closeAll();
</script>
```

---

## 9. Частые вопросы (FAQ о FAQ)

### Q: Как добавить больше FAQ элементов?
A: Скопируйте блок `<details>...</details>` и измените вопрос/ответ.

### Q: Как изменить иконку стрелки?
A: Отредактируйте SVG внутри `.faq-icon` в каждом элементе.

### Q: Может ли несколько элементов быть открытыми одновременно?
A: Да! Используйте опцию `singleOpen: false` при инициализации FaqAccordion или просто не подключайте JavaScript.

### Q: Работает ли на IE 11?
A: Нативный `<details>` не поддерживается в IE 11. Нужен полифилл:
```html
<script src="https://cdn.jsdelivr.net/npm/details-element-polyfill@3.0.0/dist/details-element-polyfill.js"></script>
```

### Q: Как сделать SEO-оптимизацию?
A: Контент в `<details>` уже индексируется поисковиками. Используйте правильные заголовки для вопросов.

---

## 10. Рекомендации

1. **Для простых случаев**: Используйте базовый вариант с `<details>` (без JS)
2. **Для аналитики**: Подключите `faq-enhanced.js` с trackAnalytics: true
3. **Для сложного взаимодействия**: Расширьте класс FaqAccordion
4. **Для производительности**: Минимизируйте CSS/JS (уже сделано в build)
5. **Для доступности**: Тестируйте с screen readers (встроенная поддержка `<details>`)
