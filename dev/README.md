# PSP Frontend Development Environment

Отдельное окружение для разработки новых страниц с использованием Vue 3, Tailwind CSS 4 и существующих минифицированных файлов из `layout/`.

## 🚀 Быстрый старт

### Установка зависимостей

```bash
cd dev
npm install
```

### Запуск dev-сервера

```bash
npm run dev
```

Откроется браузер на `http://127.0.0.1:3000` с hot-reload.

**Важно:** Сервер использует `127.0.0.1` вместо `localhost` для совместимости с VPN.

### Сборка для продакшена

```bash
npm run build
```

Собранные файлы будут в папке `dev/dist/`.

## 📁 Структура проекта

```
dev/
├── src/
│   ├── main.js              # Главный JS файл (инициализация Vue)
│   ├── main.css             # Главный CSS (Tailwind CSS 4 + кастомные стили)
│   └── components/          # Vue компоненты
│       └── HelloWorld.vue   # Пример компонента
├── public/                  # Статические файлы (копируются как есть)
│   └── layout/              # Копии файлов из ../layout/
│       ├── css/             # libs.min.css, app.min.css
│       ├── js/              # libs.min.js, app.min.js
│       ├── img/dest/        # Изображения
│       └── fonts/           # Шрифты
├── index.html               # Главная страница (пример)
├── sync-layout.sh           # Скрипт синхронизации из ../layout/
├── package.json
├── vite.config.js
└── README.md
```

## 🎨 Использование

### 1. Подключение существующих стилей и скриптов

В HTML уже подключены:
- `/layout/css/libs.min.css` - минифицированные библиотеки
- `/layout/css/app.min.css` - минифицированные стили приложения
- `/layout/js/libs.min.js` - минифицированные JS библиотеки
- `/layout/js/app.min.js` - минифицированный JS приложения

Эти файлы копируются из `../layout/` в `public/layout/` для корректной работы сборки.

**Синхронизация с layout:**
Если файлы в `../layout/` обновились, запустите:
```bash
bash sync-layout.sh
```

### 2. Добавление своих стилей

Редактируйте `src/main.css`:

```css
/* Tailwind загружается в слое utilities для высокого приоритета */
@layer utilities {
  @import "tailwindcss";
}

/* Ваши кастомные стили в слое components */
@layer components {
  .my-custom-class {
    /* ... */
  }
}
```

**Важно:** Используются [CSS Cascade Layers](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer) для управления приоритетом:
- Старые стили из `layout/` (без слоя) = базовый приоритет
- Tailwind utilities (в слое `utilities`) = высокий приоритет
- Ваши кастомные стили (в слое `components`) = средний приоритет

Это позволяет Tailwind-классам переопределять старые стили из layout.

### 3. Создание Vue компонентов

Создайте файл в `src/components/MyComponent.vue`:

```vue
<template>
  <div class="p-4 bg-blue-500 text-white rounded">
    <h2>{{ title }}</h2>
    <p>{{ message }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  message: String
});
</script>
```

### 4. Использование Vue компонентов в HTML

Просто добавьте div с атрибутами `data-vue-component` и `data-vue-props`:

```html
<!-- Без props (будут использованы дефолтные) -->
<div data-vue-component="MyComponent"></div>

<!-- С props (JSON формат) -->
<div
  data-vue-component="MyComponent"
  data-vue-props='{"title": "Привет", "message": "Мир!"}'
></div>
```

Компоненты автоматически монтируются при загрузке страницы.

### 5. Использование Tailwind CSS 4

Используйте классы Tailwind прямо в HTML:

```html
<div class="flex items-center gap-4 bg-gradient-to-r from-blue-500 to-purple-600 p-8 rounded-lg">
  <h1 class="text-3xl font-bold text-white">Hello Tailwind 4!</h1>
</div>
```

## 📄 Создание новых страниц

1. Создайте новый HTML файл (например, `catalog.html`) в папке `dev/`
2. Скопируйте структуру из `index.html`
3. Добавьте страницу в `vite.config.js`:

```js
build: {
  rollupOptions: {
    input: {
      main: resolve(__dirname, 'index.html'),
      catalog: resolve(__dirname, 'catalog.html'), // <-- добавьте здесь
    },
  },
}
```

## 🔧 Настройка

### Изменение порта dev-сервера

В `vite.config.js`:

```js
server: {
  port: 3000, // измените на нужный
  open: true,
}
```

### Алиасы путей

Уже настроены:
- `@` → `dev/src/`
- `@layout` → `layout/`

Использование:

```js
import MyComponent from '@/components/MyComponent.vue';
```

## 💡 Советы

1. **Hot Module Replacement (HMR)** работает автоматически — изменения применяются без перезагрузки страницы
2. **Tailwind CSS 4** использует новый движок (Lightning CSS) — быстрее и мощнее
3. **Vue компоненты** монтируются автоматически через `data-vue-component` атрибут
4. Существующие стили из `layout/` не конфликтуют с Tailwind благодаря prefix/scoping

## 🐛 Troubleshooting

### Компонент не монтируется
- Проверьте, что имя в `data-vue-component` совпадает с именем файла (без .vue)
- Проверьте консоль браузера на ошибки

### Стили не применяются
- Убедитесь, что `src/main.css` подключен в HTML
- Проверьте, что Tailwind классы написаны правильно

### Ошибки сборки
- Очистите `node_modules` и переустановите: `rm -rf node_modules && npm install`
