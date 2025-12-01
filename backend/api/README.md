# PSP Calc API - Mock Endpoints

Mock API для работы с калькуляторами PSP. Реализует все эндпоинты из Swagger спецификации.

## Удаленный сервер

API развернуто на удаленном сервере:

**Base URL:** `https://psp.realeasystudio.site/backend/api`

Проект автоматически синхронизируется с удаленным сервером, все изменения применяются автоматически.

### Быстрый тест

```bash
curl https://psp.realeasystudio.site/backend/api/calcs
```

## Установка (для локальной разработки)

1. Установите Composer зависимости:
```bash
cd backend
composer install
```

2. Веб-сервер уже настроен на удаленном сервере. Для локальной разработки можно использовать встроенный PHP сервер:
```bash
cd backend/api
php -S localhost:8000
```

## Структура проекта

```
backend/
├── api/
│   ├── .htaccess           # Настройки Apache
│   ├── index.php           # Главный роутер
│   ├── src/
│   │   ├── Router.php      # Класс роутера
│   │   ├── Response.php    # Класс для ответов
│   │   └── MockData.php    # Моковые данные
│   └── README.md
├── composer.json
└── RestApi.yaml           # Swagger спецификация
```

## Доступные эндпоинты

### 1. GET /calcs
Получение списка доступных калькуляторов.

**Ответ:**
```json
{
  "iCalcs": [
    {
      "id": 1,
      "name": "Вывеска из ПВХ"
    },
    {
      "id": 15,
      "name": "Led-модули"
    }
  ]
}
```

### 2. POST /:id/params
Получение параметров конкретного калькулятора.

**Запрос:**
```json
{
  "db_id": 1,
  "user": "user",
  "pass": "password"
}
```

**Ответ:**
```json
{
  "params": [
    {
      "id": 53,
      "variable": "l_fig_frez",
      "caption": "Длина фигурной фрезеровки",
      "type": 1,
      "default": "0",
      "value": 0
    }
  ],
  "mat_select_params": [
    {
      "id": 1,
      "name": "Материал основы",
      "variable": "mat_select",
      "materials": [
        {
          "id": 101,
          "name": "ПВХ пластик 2мм"
        }
      ]
    }
  ]
}
```

### 3. POST /:id/run
Выполнение расчёта.

**Запрос:**
```json
{
  "db_id": 1,
  "user": "user",
  "pass": "password",
  "params": [
    {
      "id": 54,
      "variable": "width",
      "value": 1000
    }
  ],
  "mat_select_params": []
}
```

**Ответ:**
```json
{
  "calc_position_id": 48490,
  "price_good": 1454
}
```

### 4. POST /:id/add
Добавление расчёта в калькуляцию.

**Запрос:**
```json
{
  "db_id": 1,
  "user": "user",
  "pass": "password",
  "calc_id": 0,
  "calc_position_id": 48490,
  "price_good": 1260.00
}
```

**Ответ:**
```json
{
  "calc_id": 41008167
}
```

### 5. POST /:id/save
Сохранение калькуляции.

**Запрос:**
```json
{
  "db_id": 1,
  "user": "user",
  "pass": "password",
  "calc__id": 41008167,
  "clientId": 988
}
```

**Ответ:**
```json
{
  "result": "OK"
}
```

## Тестирование

Используйте Postman коллекцию `PSP_Calc_API.postman_collection.json` для тестирования всех эндпоинтов.

## Переход на реальное API

В будущем планируется:
1. Интеграция с реальным API через Guzzle
2. Добавление аутентификации
3. Миграция на Laravel

Текущая структура позволит легко перенести код на Laravel, так как используется PSR-4 автозагрузка и разделение на классы.
