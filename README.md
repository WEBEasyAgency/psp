# PSP Calc - Online Calculator Services

Laravel 12 + Vue 3 приложение для онлайн-калькуляторов печатной продукции.

🌐 **Production:** https://psp.realeasystudio.site/

## Требования

- **PHP 8.1+** (рекомендуется 8.4)
- **Composer 2.0+**
- **Node.js 20+** и npm
- **Git**

## Быстрый старт

### 1. Клонирование репозитория

```bash
git clone https://github.com/WEBEasyAgency/psp.git
cd psp
```

### 2. Установка зависимостей

```bash
# PHP зависимости
composer install

# Node.js зависимости
npm install
```

### 3. Настройка окружения

```bash
# Копировать .env файл
cp .env.example .env

# Сгенерировать ключ приложения
php artisan key:generate
```

### 4. Запуск development серверов

**Важно:** Нужно запустить **два сервера одновременно** в разных терминалах.

**Терминал 1 - Laravel сервер:**
```bash
php artisan serve
```
Доступен на: http://localhost:8000

**Терминал 2 - Vite dev server (Hot Module Replacement):**
```bash
npm run dev
```
Работает на: http://localhost:5173 (автоматически подключается к Laravel)

### 5. Открыть приложение

Открой в браузере: **http://localhost:8000**

## Доступные страницы

### Калькуляторы
- http://localhost:8000/product/146 - Объемные буквы с бортом из алюминия
- http://localhost:8000/product/155 - Объемные буквы со световым бортом
- http://localhost:8000/product/156 - Пластиковые вывески
- http://localhost:8000/product/157 - Акриловые вывески
- http://localhost:8000/product/158 - Вывески из композита
- http://localhost:8000/product/151 - Стенд из пластика с карманами
- http://localhost:8000/product/154 - Маленькие наклейки
- http://localhost:8000/product/159 - Пластиковые таблички
- http://localhost:8000/product/160 - Акриловые таблички
- http://localhost:8000/product/161 - Таблички из композита

### Другие страницы
- http://localhost:8000/order - Страница заказа
- http://localhost:8000/thanx - Страница благодарности
- http://localhost:8000/ - Главная (временный редирект на /layout/index-new.html)

## Структура проекта

```
psp/
├── resources/
│   ├── js/
│   │   ├── pages/              # Entry points для каждой страницы
│   │   ├── widgets/product/calculators/  # Vue компоненты калькуляторов
│   │   ├── entities/product/ui/  # Общие компоненты страниц
│   │   └── shared/ui/          # Переиспользуемые UI компоненты
│   └── views/                  # Blade шаблоны
├── routes/
│   └── web.php                 # Определения роутов
├── public/
│   ├── build/                  # Скомпилированные Vite assets
│   └── img/                    # Изображения продуктов
└── backend/
    └── api/                    # Legacy PHP API (прокси к внешнему API)
```

## Разработка

### Hot Module Replacement (HMR)

При работающем Vite dev server (`npm run dev`) любые изменения в Vue компонентах автоматически отображаются в браузере без перезагрузки страницы.

**Пример workflow:**
1. Открой http://localhost:8000/product/146
2. Измени `resources/js/widgets/product/calculators/Calc146.vue`
3. Сохрани файл
4. Изменения мгновенно применятся в браузере

### Сборка для продакшена

```bash
npm run build
```

Собранные assets будут в `public/build/` и готовы к деплою.

## API

Калькуляторы взаимодействуют с backend API:

- **GET** `/backend/api/calcs` - Список калькуляторов
- **POST** `/backend/api/calc/{id}/params` - Параметры калькулятора
- **POST** `/backend/api/calc/{id}/run` - Расчет стоимости

**Пример запроса:**
```bash
curl -X POST "http://localhost:8000/backend/api/calc/146/params" \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password"}'
```

## Деплой

Проект автоматически деплоится через **GitHub Actions** при push в ветку `main`.

Деплой включает:
1. Сборку frontend assets (`npm run build`)
2. Коммит собранных файлов
3. SSH подключение к серверу
4. Выполнение `deploy.sh` на production сервере

## Troubleshooting

### Ошибка "could not find driver (Connection: sqlite)"

Это означает, что в системных переменных окружения установлены старые значения.

**Решение:**
```bash
unset SESSION_DRIVER CACHE_STORE DB_CONNECTION
php artisan serve
```

Или перезапусти терминал.

### Vite dev server не подключается

Убедись что:
1. `npm run dev` запущен и показывает "ready"
2. В консоли браузера нет ошибок подключения к localhost:5173
3. Оба сервера (Laravel и Vite) работают одновременно

### Изменения не применяются

Попробуй:
```bash
# Очистить Laravel кеши
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Пересобрать Vite
npm run build
```

## Технологии

- **Backend:** Laravel 12, PHP 8.4
- **Frontend:** Vue 3 (Composition API), Vite 7
- **Styling:** Tailwind CSS, Custom CSS
- **API Client:** Fetch API
- **Deployment:** GitHub Actions, BeGet Hosting

## Документация

Подробная документация доступна в [CLAUDE.md](CLAUDE.md) - включает:
- Архитектуру приложения
- Структуру калькуляторов
- Конфигурацию деплоя
- API спецификацию
- Частые проблемы и решения

## Лицензия

Proprietary - все права защищены.
