# План миграции проекта PSP на Vue.js + Laravel

## Текущее состояние проекта

### Структура проекта
```
psp/
├── backend/              # PHP API (Guzzle HTTP client)
│   ├── api/             # REST API endpoints
│   └── composer.json    # PHP dependencies
├── layout/              # Классический стек (HTML/CSS/JS)
│   ├── index.html       # Главная страница
│   ├── order.html       # Страница оформления заказа
│   ├── thanx-page.html  # Страница благодарности
│   ├── css/
│   │   ├── libs.min.css
│   │   └── app.min.css
│   └── js/
│       ├── libs.min.js
│       └── app.min.js
└── dev/                 # Vue.js приложение (калькуляторы)
    ├── vite.config.js   # Vite bundler
    ├── package.json     # Vue 3, Vite, Tailwind CSS 4
    └── src/
        ├── main.js      # Vue entry point
        ├── main.css     # CSS layers (layout CSS + Tailwind)
        ├── entities/    # Feature-Sliced Design
        ├── widgets/     # Калькуляторы (Calc146, 155, 156, 157)
        └── shared/      # UI компоненты
```

### Технический стек
- **Backend**: PHP 8.3, Guzzle HTTP, PHPUnit
- **Frontend (layout)**: Vanilla JS, minified CSS/JS
- **Frontend (dev)**: Vue 3, Vite, Tailwind CSS 4
- **Архитектура**: Feature-Sliced Design

### Существующие страницы для миграции
1. `layout/index.html` - Главная страница (~800 строк)
2. `layout/order.html` - Страница оформления заказа
3. `layout/thanx-page.html` - Страница "Спасибо"

### Проблемы текущей архитектуры
1. **Дублирование кода**: Header/Footer повторяются в каждом HTML-файле
2. **Сложность поддержки**: Изменения нужно вносить в несколько мест
3. **Технический долг**: Два стека технологий (классический + Vue)
4. **Отсутствие роутинга**: Нет SPA-навигации
5. **Нет Laravel**: API на чистом PHP без фреймворка

---

## Цели миграции

### Обязательные требования
1. ✅ Мигрировать все страницы из `/layout` на Vue.js
2. ✅ Настроить Laravel для API и роутинга
3. ✅ Настроить Vite для сборки с корректными URL
4. ✅ Сохранить работоспособность калькуляторов
5. ✅ Обеспечить совместимость с текущим CSS/JS

### Желаемые улучшения
1. Использовать Vue Router для SPA
2. Переиспользовать Header/Footer как Vue-компоненты
3. Настроить Laravel Mix или Vite Laravel plugin
4. Создать единую точку входа для приложения

---

## Архитектурные решения

### 1. Выбор роутинга
**Решение**: Hybrid routing (Laravel + Vue Router)

**Почему**:
- Laravel роуты для SSR и SEO (`/`, `/order`, `/thanks`)
- Vue Router для SPA-навигации внутри приложения
- Возможность постепенной миграции

**Альтернативы**:
- ❌ Только Vue Router (плохо для SEO, нет SSR)
- ❌ Только Laravel (потеряем SPA-функциональность)

### 2. Структура Vue приложения
**Решение**: Single Page Application с Feature-Sliced Design

**Структура**:
```
dev/src/
├── app/                 # App-level конфигурация
│   ├── router/         # Vue Router
│   └── store/          # State management (опционально)
├── pages/              # Page components
│   ├── HomePage.vue
│   ├── OrderPage.vue
│   └── ThanksPage.vue
├── widgets/            # Сложные блоки (калькуляторы)
├── features/           # Бизнес-логика
├── entities/           # Переиспользуемые блоки (Header, Footer)
└── shared/             # UI-kit
```

### 3. Laravel интеграция
**Решение**: Laravel + Vite официальный плагин

**Конфигурация**:
- Laravel обслуживает HTML shell
- Vite собирает Vue приложение
- API endpoints в `routes/api.php`

---

## Пошаговый план миграции

### Этап 1: Подготовка окружения 

#### 1.1 Установка Laravel
```bash
# Создать Laravel проект в корне
composer create-project laravel/laravel temp
# Перенести файлы Laravel в корень psp/
mv temp/* ./
mv temp/.* ./
rm -rf temp

# Установить Vite плагин для Laravel
npm install --save-dev vite laravel-vite-plugin
```

#### 1.2 Настройка структуры каталогов
```bash
# Переместить Vue приложение
mv dev/src resources/js/
mv dev/package.json ./
mv dev/vite.config.js ./

# Переместить статические ассеты
mv layout/css public/css/
mv layout/js public/js/
mv layout/img public/img/
mv layout/fonts public/fonts/

# НЕ удалять старые каталоги для обратной совместимости на случай если что-то пойдёт не так

```

#### 1.3 Обновить `vite.config.js`
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue(),
  ],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
});
```

#### 1.4 Настроить `package.json`
```json
{
  "private": true,
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.2.1",
    "laravel-vite-plugin": "^1.1.1",
    "vite": "^6.0.3",
    "tailwindcss": "^4.0.0",
    "@tailwindcss/vite": "^4.0.0"
  },
  "dependencies": {
    "vue": "^3.5.13",
    "vue-router": "^4.5.0"
  }
}
```

---

### Этап 2: Миграция компонентов 

#### 2.1 Создать layout-компоненты

**`resources/js/entities/layout/ui/Header.vue`**
```vue
<template>
  <header>
    <div class="container">
      <div class="header-inner">
        <!-- Скопировать HTML из layout/index.html -->
        <!-- Заменить <a href="#"> на <router-link to="/"> -->
      </div>
    </div>
  </header>
</template>

<script setup>
// Логика header (если нужна)
</script>
```

**`resources/js/entities/layout/ui/Footer.vue`**
```vue
<template>
  <footer>
    <!-- Аналогично Header -->
  </footer>
</template>
```

**`resources/js/entities/layout/ui/MobileMenu.vue`**
```vue
<template>
  <div class="popupmenu">
    <!-- Мобильное меню -->
  </div>
</template>
```

#### 2.2 Создать page-компоненты

**`resources/js/pages/HomePage.vue`**
```vue
<template>
  <main class="main-page">
    <!-- Контент главной страницы из layout/index.html -->
  </main>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted(() => {
  // Инициализация jQuery/vanilla JS если нужно
});
</script>
```

**`resources/js/pages/OrderPage.vue`**
```vue
<template>
  <main class="order-page inner-page">
    <!-- Контент из layout/order.html -->
  </main>
</template>
```

**`resources/js/pages/ThanksPage.vue`**
```vue
<template>
  <main class="thanx-page inner-page">
    <!-- Контент из layout/thanx-page.html -->
  </main>
</template>
```

#### 2.3 Создать App Layout

**`resources/js/layouts/DefaultLayout.vue`**
```vue
<template>
  <div id="app">
    <Header />
    <router-view />
    <Footer />
    <MobileMenu />
  </div>
</template>

<script setup>
import Header from '@/entities/layout/ui/Header.vue';
import Footer from '@/entities/layout/ui/Footer.vue';
import MobileMenu from '@/entities/layout/ui/MobileMenu.vue';
</script>
```

---

### Этап 3: Настройка роутинга 

#### 3.1 Vue Router

**`resources/js/app/router/index.js`**
```javascript
import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '@/pages/HomePage.vue';
import OrderPage from '@/pages/OrderPage.vue';
import ThanksPage from '@/pages/ThanksPage.vue';
import ProductPage from '@/pages/ProductPage.vue';

const routes = [
  { path: '/', name: 'home', component: HomePage },
  { path: '/order', name: 'order', component: OrderPage },
  { path: '/thanks', name: 'thanks', component: ThanksPage },
  {
    path: '/product/:id',
    name: 'product',
    component: ProductPage,
    props: true
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});
```

#### 3.2 Главный entry point

**`resources/js/app.js`**
```javascript
import { createApp } from 'vue';
import { router } from './app/router';
import App from './layouts/DefaultLayout.vue';
import './main.css';

const app = createApp(App);
app.use(router);
app.mount('#app');
```

#### 3.3 Laravel роуты

**`routes/web.php`**
```php
<?php

use Illuminate\Support\Facades\Route;

// Все роуты отдают один и тот же view
// Vue Router обрабатывает навигацию на клиенте
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
```

#### 3.4 Blade шаблон

**`resources/views/app.blade.php`**
```html
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Online</title>

    <!-- Старые минифицированные стили -->
    <link rel="stylesheet" href="/css/libs.min.css">
    <link rel="stylesheet" href="/css/app.min.css">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>

    <!-- Старые скрипты (jQuery и т.д.) -->
    <script src="/js/libs.min.js"></script>
    <script src="/js/app.min.js"></script>
</body>
</html>
```

---

### Этап 4: Миграция CSS 

#### 4.1 Обновить `resources/css/app.css`
```css
/* CSS Layers для приоритета */
@layer reset, utilities, components;

/* Старые стили из layout */
@import "/css/libs.min.css" layer(reset);
@import "/css/app.min.css" layer(reset);

/* Tailwind CSS */
@import "tailwindcss/theme" layer(utilities);
@import "tailwindcss/utilities" layer(utilities);

/* Кастомные компоненты */
@layer components {
  /* Vue-специфичные стили */
}
```

#### 4.2 Настроить Tailwind

**`tailwind.config.js`**
```javascript
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
```

---

### Этап 5: Настройка Laravel API 

#### 5.1 Перенести API из backend/

**Создать контроллеры**:
```bash
php artisan make:controller Api/CalculatorController
php artisan make:controller Api/OrderController
```

**`app/Http/Controllers/Api/CalculatorController.php`**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CalculatorController extends Controller
{
    private $client;
    private $remoteApiUrl = 'http://5.188.117.42:9000/api';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->remoteApiUrl,
            'timeout' => 10,
        ]);
    }

    public function getCalculators()
    {
        try {
            $response = $this->client->get('/calcs');
            return response()->json(json_decode($response->getBody()));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getParams(Request $request, $id)
    {
        // Аналогично backend/api/src/MockData.php
    }

    public function runCalculation(Request $request, $id)
    {
        // Аналогично backend/api/src/MockData.php
    }
}
```

#### 5.2 Настроить API роуты

**`routes/api.php`**
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CalculatorController;
use App\Http\Controllers\Api\OrderController;

Route::prefix('calcs')->group(function () {
    Route::get('/', [CalculatorController::class, 'getCalculators']);
    Route::post('/{id}/params', [CalculatorController::class, 'getParams']);
    Route::post('/{id}/run', [CalculatorController::class, 'runCalculation']);
});

Route::prefix('order')->group(function () {
    Route::post('/add', [OrderController::class, 'addToCart']);
    Route::post('/save', [OrderController::class, 'save']);
});
```

#### 5.3 Обновить composer.json
```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0",
        "guzzlehttp/guzzle": "^7.10"
    }
}
```

---

### Этап 6: Обработка старого JS 

#### 6.1 Анализ зависимостей

Проверить `/layout/js/libs.min.js` и `app.min.js`:
- jQuery
- Slick carousel
- Custom plugins

**Варианты**:
1. ✅ Оставить как есть (для совместимости)
2. Постепенно мигрировать на Vue-аналоги
3. Использовать `@vueuse/core` для utilities

#### 6.2 Интеграция jQuery с Vue

**В компонентах, где нужен jQuery**:
```vue
<script setup>
import { onMounted, onUnmounted } from 'vue';
import $ from 'jquery'; // если нужно

onMounted(() => {
  // Инициализация jQuery-плагинов
  $('.slider').slick({ /* options */ });
});

onUnmounted(() => {
  // Cleanup
  $('.slider').slick('unslick');
});
</script>
```

---

### Этап 7: Тестирование и отладка 

#### 7.1 Проверить все страницы
- [ ] Главная страница (/)
- [ ] Страница товара (/product/:id)
- [ ] Оформление заказа (/order)
- [ ] Страница благодарности (/thanks)
- [ ] Калькуляторы (Calc146, 155, 156, 157)

#### 7.2 Проверить функциональность
- [ ] Навигация (Vue Router)
- [ ] API запросы
- [ ] Формы отправки
- [ ] Калькуляторы
- [ ] Мобильное меню
- [ ] CSS стили (layout + Tailwind)

#### 7.3 Проверить сборку
```bash
# Development
npm run dev

# Production
npm run build
php artisan serve
```

---

### Этап 8: Деплой 

#### 8.1 Настроить окружение на сервере
```bash
# Установить зависимости
composer install --no-dev
npm ci --production
npm run build

# Настроить .env
cp .env.example .env
php artisan key:generate

# Права доступа
chmod -R 755 storage bootstrap/cache
```

#### 8.2 Настроить веб-сервер

**Apache `.htaccess`** (в `public/`):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ index.php [L]
</IfModule>
```

**Nginx**:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## Итоговая структура проекта

```
psp/
├── app/                    # Laravel приложение
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/        # API контроллеры
│   └── Models/
├── resources/
│   ├── views/
│   │   └── app.blade.php   # SPA shell
│   ├── css/
│   │   └── app.css         # Главный CSS (layers)
│   └── js/                 # Vue приложение
│       ├── app.js          # Entry point
│       ├── app/
│       │   └── router/     # Vue Router
│       ├── pages/          # Страницы
│       ├── layouts/        # Layouts
│       ├── widgets/        # Калькуляторы
│       ├── entities/       # Header, Footer
│       └── shared/         # UI-kit
├── public/
│   ├── css/                # Старые минифицированные стили
│   ├── js/                 # Старые скрипты
│   ├── img/
│   └── fonts/
├── routes/
│   ├── web.php             # Catch-all для SPA
│   └── api.php             # API endpoints
├── vite.config.js          # Vite + Laravel plugin
├── package.json            # Vue, Vite, Tailwind
├── composer.json           # Laravel, Guzzle
└── .env                    # Конфигурация
```

---

## Риски и их митигация

### Риск 1: Конфликты CSS
**Проблема**: Старые стили могут конфликтовать с Tailwind
**Решение**: Использовать CSS Layers для управления приоритетом

### Риск 2: jQuery vs Vue
**Проблема**: jQuery-плагины могут не работать с виртуальным DOM
**Решение**:
- Инициализировать jQuery только в `onMounted`
- Использовать `ref` для прямого доступа к DOM
- Постепенно заменять на Vue-аналоги

### Риск 3: Поломка калькуляторов
**Проблема**: Калькуляторы могут сломаться при переносе
**Решение**:
- Перенести их в последнюю очередь
- Создать резервные копии
- Тестировать каждый калькулятор отдельно

### Риск 4: SEO проблемы
**Проблема**: SPA плохо индексируется
**Решение**:
- Использовать SSR (Inertia.js) в будущем
- Добавить meta-теги через Vue Router
- Настроить sitemap.xml

---

## Альтернативные подходы

### Вариант 1: Постепенная миграция
- Оставить `/layout` как есть
- Добавить новые страницы на Vue
- Постепенно переносить старые

**Плюсы**: Меньше рисков
**Минусы**: Дольше, технический долг остается

### Вариант 2: Полная переработка
- Переписать все с нуля
- Использовать Nuxt.js или Next.js
- Отказаться от старого CSS/JS

**Плюсы**: Современный стек, чистый код
**Минусы**: Очень долго, высокие риски

### Вариант 3: Inertia.js (рекомендуется для будущего)
- Laravel + Inertia.js + Vue
- SSR из коробки
- Меньше настроек

**Плюсы**: Проще, лучше SEO
**Минусы**: Нужно изучать Inertia

---

## Рекомендации

1. **Начать с Этапа 1-3** - это даст быструю базу
2. **Протестировать на dev-сервере** перед деплоем
3. **Сохранить бэкапы** старого кода
4. **Документировать изменения** в CHANGELOG.md
5. **Использовать Git branches** для каждого этапа
6. **Согласовать план** с командой перед началом

---

## Контрольные точки

- [ ] Этап 1 завершен: Laravel установлен, структура настроена
- [ ] Этап 2 завершен: Компоненты созданы
- [ ] Этап 3 завершен: Роутинг работает
- [ ] Этап 4 завершен: CSS корректный
- [ ] Этап 5 завершен: API работает
- [ ] Этап 6 завершен: JS интегрирован
- [ ] Этап 7 завершен: Все протестировано
- [ ] Этап 8 завершен: Деплой выполнен

---

## Следующие шаги после миграции

1. Оптимизация производительности
2. Добавление тестов (PHPUnit, Vitest)
3. Настройка CI/CD
4. Миграция на Inertia.js для SSR
5. Рефакторинг компонентов
6. Добавление state management (Pinia)