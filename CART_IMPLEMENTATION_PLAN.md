# 🛒 ПЛАН РЕАЛИЗАЦИИ КОРЗИНЫ (MVP)

## Архитектурные решения для MVP

### Количество товара
- Умножение на frontend: `price × quantity`
- Без пересчёта через API

### Кнопка "Изменить"
- Возврат на страницу калькулятора с предзаполненными параметрами
- Сохраняем: `params`, `mat_select_params`, `calculator_id`

### Оформление заказа
- Один общий заказ (`calc_id`) со всеми товарами
- При клике "Оформить заказ": все товары через `/addCalc` → один `calc_id` → передать в `/order`

### Хранение данных
- Корзина полностью на frontend (localStorage)
- Жизненный цикл: до оформления заказа + до очистки браузера

---

## ЭТАПЫ РЕАЛИЗАЦИИ

### ✅ ЭТАП 1: Роутинг для статичной корзины
**Задачи:**
- [ ] Добавить роут `GET /cart` в `routes/web.php`
- [ ] Протестировать в браузере

**Файлы:** `routes/web.php`

---

### ✅ ЭТАП 2: Backend API для работы с корзиной (TDD)

#### 2.1 Написание тестов (RED phase)
- [ ] Создать `backend/tests/CartApiTest.php`
- [ ] Тесты для `addCalc`, `delCalc`
- [ ] HTML test runner для просмотра в браузере
- [ ] Запустить → убедиться что падают (RED)

#### 2.2 Реализация (GREEN phase)
- [ ] Проверить работу существующих endpoints
- [ ] Тесты проходят (GREEN)

#### 2.3 Рефакторинг (REFACTOR phase)
- [ ] Оптимизация кода
- [ ] PHPDoc комментарии

**Файлы:**
- `backend/tests/CartApiTest.php` (новый)
- `backend/tests/test-runner.html` (новый)

---

### ✅ ЭТАП 3: Composable для управления корзиной

**Задачи:**
- [ ] Создать `useCart.js`
- [ ] Методы: `addItem()`, `removeItem()`, `updateQuantity()`, `getItems()`, `clearCart()`, `getCartId()`
- [ ] Реактивный счётчик количества товаров

**Структура данных в localStorage:**
```javascript
{
  "psp_calc_id": null,  // Будет создан при первом addCalc
  "psp_cart_items": [
    {
      "id": "uuid",  // Уникальный ID для frontend
      "calc_position_id": 48490,
      "calculator_id": 146,
      "price_good": 25000,
      "quantity": 1,
      "description": "Объемные буквы (4 буквы, высота 30см)",
      "params": [...],  // Для кнопки "Изменить"
      "mat_select_params": [...],
      "image": "/img/placeholder.jpg"
    }
  ]
}
```

**Файлы:** `resources/js/shared/composables/useCart.js` (новый)

---

### ✅ ЭТАП 4: Интеграция кнопки "Добавить в корзину"

**Задачи:**
- [ ] Раскомментировать кнопку в `CalculatorAction.vue`
- [ ] Реализовать `addToCart()` - НЕ вызываем API сразу
- [ ] Сохранить товар в localStorage
- [ ] Показать toast уведомление
- [ ] Переключить на кнопку "Перейти в корзину"
- [ ] Обновить счётчик в header

**Логика:** Добавляем в localStorage, синхронизация с API будет при оформлении

**Файлы:** `resources/js/widgets/product/calculators/components/CalculatorAction.vue`

---

### ✅ ЭТАП 5: Счётчик корзины в Header

**Задачи:**
- [ ] Добавить использование `useCart()`
- [ ] Показать бейдж с количеством товаров
- [ ] Ссылка на `/cart`
- [ ] Скрыть бейдж если корзина пуста

**Файлы:** `resources/js/entities/product/ui/Header.vue`

---

### ✅ ЭТАП 6: Динамическая страница корзины

**Задачи:**
- [ ] Создать `resources/js/pages/cart.js` (entry point)
- [ ] Создать `resources/js/widgets/cart/Cart.vue`
- [ ] Загрузка из localStorage
- [ ] Отображение списка товаров
- [ ] Изменение количества (+ / -)
- [ ] Удаление товаров
- [ ] Кнопка "Изменить" → переход на калькулятор с params
- [ ] Подсчёт итоговой суммы (price × quantity)
- [ ] Кнопка "Оформить заказ"
- [ ] Кнопка "Очистить корзину"
- [ ] Обновить `cart.blade.php` для Vue
- [ ] Добавить в `vite.config.js`

**Файлы:**
- `resources/js/pages/cart.js` (новый)
- `resources/js/widgets/cart/Cart.vue` (новый)
- `resources/views/cart.blade.php`
- `vite.config.js`

---

### ✅ ЭТАП 7: Интеграция с Order

**Задачи:**
- [ ] Модифицировать `order.blade.php` для поддержки корзины
- [ ] При переходе из корзины: для каждого товара вызвать `POST /addCalc`
- [ ] Получить `calc_id` (создаётся при первом addCalc)
- [ ] Далее стандартный flow: `POST /addContact` → `POST /saveCalc`
- [ ] После успешного оформления - очистить localStorage
- [ ] Сохранить поддержку прямого заказа (без корзины)

**Логика:**
```javascript
// Сценарий 1: Из корзины (?from_cart=1)
if (urlParams.get('from_cart')) {
  const cartItems = getItemsFromLocalStorage()
  let calc_id = null

  for (const item of cartItems) {
    const res = await POST('/addCalc', {
      calc_id: calc_id || 0,
      calc_position_id: item.calc_position_id,
      price_good: item.price_good * item.quantity
    })
    calc_id = res.calc_id
  }

  // Далее: addContact → saveCalc
  // После успеха: clearCart()
}

// Сценарий 2: Прямой заказ (как сейчас)
else if (urlParams.get('calc_position_id')) {
  // Существующая логика
}
```

**Файлы:**
- `resources/views/order.blade.php`
- `routes/web.php` (если нужно)

---

## WORKFLOW MVP

```
1. Калькулятор → Расчёт
   ↓
2. "Добавить в корзину" → Сохранено в localStorage
   ↓
3. Продолжить покупки ИЛИ "Перейти в корзину"
   ↓
4. Страница /cart → Просмотр, изменение количества, удаление
   ↓
5. "Оформить заказ" → /order?from_cart=1
   ↓
6. Для каждого товара: POST /addCalc (создаётся calc_id)
   ↓
7. POST /addContact → POST /saveCalc
   ↓
8. Успех → /thanx → localStorage очищен
```

**Альтернативный путь:** "Оформить сейчас" → bypass корзины → прямо на `/order` (как сейчас)

---

## TDD Подход

1. **RED:** Пишем тест → он падает
2. **GREEN:** Пишем код → тест проходит
3. **REFACTOR:** Улучшаем → тест проходит

**ВАЖНО:** Результаты тестов в браузере через HTML runner!

---

## Новые файлы

```
backend/tests/
  CartApiTest.php
  test-runner.html

resources/js/
  pages/
    cart.js
  widgets/
    cart/
      Cart.vue
  shared/
    composables/
      useCart.js
```
