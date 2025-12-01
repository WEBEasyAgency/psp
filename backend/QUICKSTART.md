# PSP Calc API - Быстрый старт

## Что было реализовано

Создана структура mock-эндпоинтов для работы с калькуляторами на основе Swagger спецификации `RestApi.yaml`.

### Структура проекта

```
backend/
├── api/
│   ├── .htaccess                              # Apache rewrite rules
│   ├── index.php                              # Главный роутер
│   ├── src/
│   │   ├── Router.php                         # Класс роутера
│   │   ├── Response.php                       # Класс для JSON ответов
│   │   └── MockData.php                       # Моковые данные
│   ├── README.md                              # Документация API
│   └── PSP_Calc_API.postman_collection.json   # Postman коллекция
├── composer.json                              # Composer конфигурация
└── RestApi.yaml                               # Swagger спецификация
```

### Реализованные эндпоинты

1. **GET /calcs** - Получение списка калькуляторов
2. **POST /:id/params** - Получение параметров калькулятора
3. **POST /:id/run** - Выполнение расчёта
4. **POST /:id/add** - Добавление расчёта в калькуляцию
5. **POST /:id/save** - Сохранение калькуляции

## Быстрый запуск

### Установка зависимостей

```bash
cd backend
composer install
```

### Удаленный сервер

API автоматически развернуто на удаленном сервере:

**Base URL:** `https://psp.realeasystudio.site/backend/api`

Проект синхронизирован с удаленным сервером, поэтому все изменения автоматически доступны.

### Быстрый тест

```bash
curl https://psp.realeasystudio.site/backend/api/calcs
```

Должен вернуть список калькуляторов в формате JSON.

## Тестирование

### С помощью Postman

1. Откройте Postman
2. Импортируйте файл `backend/api/PSP_Calc_API.postman_collection.json`
3. Установите переменную окружения `base_url`:
   - `https://psp.realeasystudio.site/backend/api`
4. Запустите запросы

### С помощью cURL

```bash
# Base URL для всех запросов
BASE_URL="https://psp.realeasystudio.site/backend/api"

# 1. Получить список калькуляторов
curl ${BASE_URL}/calcs

# 2. Получить параметры калькулятора
curl -X POST ${BASE_URL}/1/params \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password"}'

# 3. Выполнить расчёт
curl -X POST ${BASE_URL}/1/run \
  -H "Content-Type: application/json" \
  -d '{
    "db_id": 1,
    "user": "user",
    "pass": "password",
    "params": [
      {"id": 54, "variable": "width", "value": 1000},
      {"id": 55, "variable": "height", "value": 500}
    ],
    "mat_select_params": []
  }'

# 4. Добавить в калькуляцию
curl -X POST ${BASE_URL}/1/add \
  -H "Content-Type: application/json" \
  -d '{
    "db_id": 1,
    "user": "user",
    "pass": "password",
    "calc_id": 0,
    "calc_position_id": 48490,
    "price_good": 1260.00
  }'

# 5. Сохранить калькуляцию
curl -X POST ${BASE_URL}/1/save \
  -H "Content-Type: application/json" \
  -d '{
    "db_id": 1,
    "user": "user",
    "pass": "password",
    "calc__id": 41008167,
    "clientId": 988
  }'
```

## Следующие шаги

1. **Интеграция с реальным API**
   - Установить Guzzle: `composer require guzzlehttp/guzzle`
   - Создать класс `ApiClient` для работы с удалённым API
   - Переключить `MockData.php` на реальные запросы

2. **Миграция на Laravel**
   - Установить Laravel: `composer create-project laravel/laravel`
   - Перенести роуты в `routes/api.php`
   - Создать контроллеры: `CalcController`, `CalculationController`
   - Создать модели и миграции

3. **Добавление аутентификации**
   - Laravel Sanctum для API токенов
   - Middleware для проверки прав доступа

## Дополнительная информация

Подробная документация находится в `backend/api/README.md`
