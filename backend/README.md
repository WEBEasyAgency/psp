# PSP Calc API Backend

Mock API для работы с калькуляторами PSP с поддержкой интеграции реального API.

## Возможности

- ✅ Mock режим с тестовыми данными (по умолчанию)
- ✅ Интеграция с реальным API через Guzzle HTTP Client
- ✅ Автоматическое переключение между режимами
- ✅ Полное покрытие тестами (PHPUnit)
- ✅ PSR-4 автозагрузка классов

## Быстрый старт

### Установка зависимостей

```bash
cd backend
composer install
```

### Тестирование API

#### Удаленный сервер

API развернуто по адресу:
```
https://psp.realeasystudio.site/backend/api
```

#### Локальная разработка

```bash
cd backend/api
php -S localhost:8000
```

## Конфигурация

Настройки находятся в `backend/api/config.php`:

```php
[
    'mode' => 'mock',  // 'mock' или 'real'
    'real_api_url' => 'http://5.188.117.42:9000/api',
    'logging' => [
        'enabled' => true,
        'log_file' => __DIR__ . '/logs/api.log'
    ]
]
```

### Переключение режимов

#### Через конфигурацию

Отредактируйте `backend/api/config.php`:

```php
'mode' => 'real',  // Включить реальное API
```

#### Через переменные окружения

```bash
export API_MODE=real
export REAL_API_URL=http://custom.api.com/endpoint
```

#### Программно

```php
use PSP\MockData;

// Включить реальное API
MockData::enableRealApi('http://5.188.117.42:9000/api');

// Вернуться к mock данным
MockData::disableRealApi();

// Проверить текущий режим
if (MockData::isUsingRealApi()) {
    echo "Используется реальное API";
}
```

## Тестирование

### Запуск всех тестов

```bash
cd backend
vendor/bin/phpunit
```

### Запуск конкретного теста

```bash
vendor/bin/phpunit tests/MockDataTest.php
```

### Запуск с покрытием кода

```bash
vendor/bin/phpunit --coverage-text
```

### Интеграционные тесты

Интеграционные тесты (помечены `@group integration`) пропускаются по умолчанию:

```bash
# Запустить только интеграционные тесты
vendor/bin/phpunit --group integration

# Исключить интеграционные тесты
vendor/bin/phpunit --exclude-group integration
```

## Доступные эндпоинты

### GET /calcs
Получение списка калькуляторов.

```bash
curl https://psp.realeasystudio.site/backend/api/calcs
```

### POST /:id/params
Получение параметров калькулятора.

```bash
curl -X POST https://psp.realeasystudio.site/backend/api/1/params \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password"}'
```

### POST /:id/run
Выполнение расчёта.

```bash
curl -X POST https://psp.realeasystudio.site/backend/api/1/run \
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
```

### POST /:id/add
Добавление расчёта в калькуляцию.

```bash
curl -X POST https://psp.realeasystudio.site/backend/api/1/add \
  -H "Content-Type: application/json" \
  -d '{
    "db_id": 1,
    "user": "user",
    "pass": "password",
    "calc_id": 0,
    "calc_position_id": 48490,
    "price_good": 1260.00
  }'
```

### POST /:id/save
Сохранение калькуляции.

```bash
curl -X POST https://psp.realeaststudio.site/backend/api/1/save \
  -H "Content-Type: application/json" \
  -d '{
    "db_id": 1,
    "user": "user",
    "pass": "password",
    "calc__id": 41008167,
    "clientId": 988
  }'
```

## Архитектура

### Структура классов

- **Router** (`api/src/Router.php`) - Маршрутизация HTTP запросов
- **Response** (`api/src/Response.php`) - Форматирование JSON ответов
- **MockData** (`api/src/MockData.php`) - Провайдер данных (mock/real)
- **ApiClient** (`api/src/ApiClient.php`) - HTTP клиент для реального API

### Поток данных

```
HTTP Request
    ↓
Apache (.htaccess)
    ↓
index.php (загрузка конфигурации)
    ↓
Router (pattern matching)
    ↓
MockData (выбор источника: mock/real)
    ↓
ApiClient (если mode=real)
    ↓
Response (JSON output)
```

### Обработка ошибок

При работе с реальным API, если запрос не удался, автоматически используются mock данные:

```php
try {
    return self::$apiClient->getCalcs();
} catch (\Exception $e) {
    error_log('API Error: ' . $e->getMessage());
    return self::getMockCalcs();  // Fallback to mock data
}
```

## Разработка

### Добавление нового эндпоинта

1. Обновите `RestApi.yaml` (OpenAPI спецификация)
2. Добавьте роут в `api/index.php`
3. Добавьте метод в `ApiClient.php`
4. Добавьте mock метод в `MockData.php`
5. Напишите тест в `tests/`

### Соглашения о коде

- PSR-4 автозагрузка
- Namespace: `PSP\`
- Все публичные методы должны иметь PHPDoc комментарии
- Тесты покрывают все публичные методы

## Postman

Для тестирования используйте коллекцию: `backend/api/PSP_Calc_API.postman_collection.json`

Установите переменную окружения:
- `base_url`: `https://psp.realeasystudio.site/backend/api`

## Документация API

Полная спецификация API находится в `backend/RestApi.yaml` (OpenAPI 3.1.0)

## Требования

- PHP >= 7.4
- Composer
- Расширения: curl, json

## Автор

PSP Development Team

## Лицензия

Proprietary
