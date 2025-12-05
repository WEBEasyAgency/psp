# Calc159 - История форматов mat_select_params

## Контекст
Калькулятор 159 (Пластиковые таблички) позволяет выбрать толщину пластика: 3мм (ID=2), 5мм (ID=3), 8мм (ID=257).
Проблема: клиент сообщает что "значения некорректны" при выборе толщины.

---

## ВАРИАНТ 1: Упрощённый формат (изначальный)

**Код:** Самая первая версия Calc159.vue

**Формат отправки:**
```json
{
  "params": [
    {"variable": "w", "type": 1, "value": 30},
    {"variable": "h", "type": 1, "value": 12},
    {"variable": "num", "type": 1, "value": 1},
    {"variable": "doubleside", "type": 2, "value": 0},
    {"variable": "round", "type": 2, "value": 0},
    {"variable": "tape", "type": 2, "value": 0},
    {"variable": "need_Nielsen", "type": 2, "value": 0}
  ],
  "mat_select_params": [
    {"variable": "mat_select_in3", "value": 3}
  ]
}
```

**Пример при выборе 5мм:**
```json
"mat_select_params": [
  {"variable": "mat_select_in3", "value": 3}
]
```

**Результат:** Клиент сообщил что "значения некорректны"

---

## ВАРИАНТ 2: Полный объект со статичными id/name

**Код:** После первого исправления (commit 8551ce0)

**Формат отправки:**
```json
{
  "params": [...],
  "mat_select_params": [
    {
      "id": 2,
      "name": "ПВХ пластик 3мм",
      "prop_type": 3,
      "variable": "mat_select_in3",
      "materials": [
        {"id": 3, "name": "ПВХ пластик 5мм"}
      ]
    }
  ]
}
```

**Пример при выборе 5мм:**
```json
"mat_select_params": [
  {
    "id": 2,  // ❌ СТАТИЧНЫЙ - всегда 2, не меняется
    "name": "ПВХ пластик 3мм",  // ❌ СТАТИЧНЫЙ - всегда "3мм"
    "prop_type": 3,
    "variable": "mat_select_in3",
    "materials": [
      {"id": 3, "name": "ПВХ пластик 5мм"}  // ✅ Правильный выбранный материал
    ]
  }
]
```

**Проблема:** `id` и `name` брались из объекта `param` (который один и тот же для всех материалов), а не из выбранного материала.

**Результат:** Клиент сообщил "Всё ещё ничего не меняется"

---

## ВАРИАНТ 3: Полный объект с динамическими id/name

**Код:** После второго исправления (commit f888e1f)

**Формат отправки:**
```json
{
  "params": [...],
  "mat_select_params": [
    {
      "id": 3,
      "name": "ПВХ пластик 5мм",
      "prop_type": 3,
      "variable": "mat_select_in3",
      "materials": [
        {"id": 3, "name": "ПВХ пластик 5мм"}
      ]
    }
  ]
}
```

**Пример при выборе 5мм:**
```json
"mat_select_params": [
  {
    "id": 3,  // ✅ ДИНАМИЧЕСКИЙ - берётся из выбранного материала
    "name": "ПВХ пластик 5мм",  // ✅ ДИНАМИЧЕСКИЙ - берётся из выбранного материала
    "prop_type": 3,
    "variable": "mat_select_in3",
    "materials": [
      {"id": 3, "name": "ПВХ пластик 5мм"}
    ]
  }
]
```

**Пример при выборе 3мм:**
```json
"mat_select_params": [
  {
    "id": 2,
    "name": "ПВХ пластик 3мм",
    "prop_type": 3,
    "variable": "mat_select_in3",
    "materials": [
      {"id": 2, "name": "ПВХ пластик 3мм"}
    ]
  }
]
```

**Пример при выборе 8мм:**
```json
"mat_select_params": [
  {
    "id": 257,
    "name": "ПВХ пластик 8мм",
    "prop_type": 3,
    "variable": "mat_select_in3",
    "materials": [
      {"id": 257, "name": "ПВХ пластик 8мм"}
    ]
  }
]
```

**Результат:** Клиент сообщил "Всё ещё ничего не меняется"

---

## Данные из API /params

Когда мы запрашиваем `/backend/api/calc/159/params`, API возвращает:

```json
{
  "mat_select_params": [
    {
      "id": 2,  // ← Это ID ПАРАМЕТРА, а не материала!
      "name": "ПВХ пластик",
      "in_prop_id": 3,
      "prop_id": 3,
      "mat_sel_link": 3,
      "mat_sel_link2": 0,
      "prop_type": 3,
      "variable": "mat_select_in3",
      "materials": [
        {"id": 2, "name": "ПВХ пластик 3мм"},
        {"id": 3, "name": "ПВХ пластик 5мм"},
        {"id": 257, "name": "ПВХ пластик 8мм"}
      ]
    }
  ]
}
```

**Важно:**
- `id: 2` в корневом объекте - это ID параметра (mat_select_in3), а не материала
- `materials` содержит список доступных материалов с их ID

---

## ВОПРОС К КЛИЕНТУ

У нас есть 3 варианта отправки `mat_select_params`. Все три НЕ РАБОТАЮТ.

**Вариант 1** - упрощённый (только variable + value):
```json
[{"variable": "mat_select_in3", "value": 3}]
```

**Вариант 2** - полный объект со статичными полями:
```json
[{
  "id": 2,
  "name": "ПВХ пластик 3мм",
  "prop_type": 3,
  "variable": "mat_select_in3",
  "materials": [{"id": 3, "name": "ПВХ пластик 5мм"}]
}]
```

**Вариант 3** - полный объект с динамическими полями (текущий):
```json
[{
  "id": 3,
  "name": "ПВХ пластик 5мм",
  "prop_type": 3,
  "variable": "mat_select_in3",
  "materials": [{"id": 3, "name": "ПВХ пластик 5мм"}]
}]
```

### ВОПРОСЫ:

1. **Какой формат ожидает API?** Можете ли вы показать ТОЧНЫЙ пример успешного запроса `/calc/159/run` с вашей стороны?

2. **Что значит "ничего не меняется"?**
   - Цена не меняется при выборе разной толщины?
   - Возвращается ошибка?
   - Калькулятор игнорирует выбранную толщину?

3. **Можете протестировать прямой запрос к API?** Используйте Postman коллекцию `Calc159_Test.postman_collection.json`:
   - Запросы "DIRECT - Run Calc 159 - 3mm/5mm/8mm" идут напрямую к вашему API
   - Какой из них работает правильно?

4. **Есть ли рабочий пример** из другого калькулятора (146, 151, 156 и т.д.), где материалы работают корректно?

---

## Структура по спецификации OpenAPI

Согласно `backend/RestApi New.yaml` (строки 113-117):

```yaml
mat_select_params:
    type: array
    items:
      $ref: '#/components/schemas/mat_select_param'
```

И schema `mat_select_param` (строки 497-520):

```yaml
mat_select_param:
  type: object
  properties:
    id:
      type: integer
      examples: [53]
      description: Идентификатор параметра
    name:
      type: string
      examples: ["ПВХ пластик 2мм"]
    prop_type:
      type: integer
      examples: [3]
    variable:
      type: string
      examples: ["mat_select"]
    materials:
      type: array
      items:
        $ref: '#/components/schemas/material'
```

**НО:** Спецификация показывает структуру ОТВЕТА от `/params`, а не формат ЗАПРОСА для `/run`!

---

## Что можно ещё попробовать?

### ВАРИАНТ 4: Только массив materials без обёртки
```json
"mat_select_params": [
  {"id": 3, "name": "ПВХ пластик 5мм"}
]
```

### ВАРИАНТ 5: Весь объект param как пришёл из API, но с отфильтрованным materials
```json
"mat_select_params": [
  {
    "id": 2,
    "name": "ПВХ пластик",
    "in_prop_id": 3,
    "prop_id": 3,
    "mat_sel_link": 3,
    "mat_sel_link2": 0,
    "prop_type": 3,
    "variable": "mat_select_in3",
    "materials": [{"id": 3, "name": "ПВХ пластик 5мм"}]
  }
]
```

### ВАРИАНТ 6: ID параметра + ID материала отдельно
```json
{
  "params": [...],
  "mat_select_params": [
    {
      "param_id": 2,
      "material_id": 3
    }
  ]
}
```

---

**Пожалуйста, уточните какой формат ожидает ваш API, или предоставьте рабочий пример запроса!**

---

## ✅ РЕШЕНИЕ: ВАРИАНТ 6 (РАБОТАЕТ!)

**Код:** commit ed2c329

**Формат отправки:**
Материал передаётся **И в `params` И в `mat_select_params`** с обновлёнными полями:

```json
{
  "params": [
    {"variable": "w", "type": 1, "value": 30},
    {"variable": "h", "type": 1, "value": 12},
    {"variable": "num", "type": 1, "value": 1},
    {"variable": "doubleside", "type": 2, "value": 0},
    {"variable": "round", "type": 2, "value": 0},
    {"variable": "tape", "type": 2, "value": 0},
    {"variable": "need_Nielsen", "type": 2, "value": 0},
    {"variable": "mat_select_in3", "type": 3, "value": 3}
  ],
  "mat_select_params": [
    {
      "id": 3,
      "name": "ПВХ пластик 5мм",
      "in_prop_id": 3,
      "prop_id": 3,
      "mat_sel_link": 3,
      "mat_sel_link2": 0,
      "prop_type": 3,
      "variable": "mat_select_in3",
      "materials": [{"id": 3, "name": "ПВХ пластик 5мм"}]
    }
  ]
}
```

**Ключевые моменты:**
1. ✅ Материал добавлен в `params` как обычный параметр с `type: 3`
2. ✅ В `mat_select_params` объект передаётся полностью (все поля из API)
3. ✅ `id` и `name` в объекте `mat_select_params` **обновляются** на ID и название выбранного материала
4. ✅ Массив `materials` содержит только выбранный материал

**Результат:** Цена меняется корректно при выборе разных толщин пластика! ✅
