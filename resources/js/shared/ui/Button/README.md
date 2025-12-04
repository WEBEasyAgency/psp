# BaseButton Component

Универсальный компонент кнопки для использования во всем проекте. Поддерживает различные размеры, цвета (варианты), состояния загрузки и может работать как ссылка.

## 📍 Импорт

```javascript
import BaseButton from '@/shared/ui/Button/BaseButton.vue'
```

## 🚀 Быстрый старт

Самый простой вариант (синяя кнопка, средний размер):

```html
<BaseButton @click="doSomething">
  Нажми меня
</BaseButton>
```

---

## 🎨 Как менять внешний вид (Variants)

Используйте проп `variant`, чтобы изменить цвет и стиль кнопки.
**Не пишите CSS классы вручную!** Компонент сам подставит нужный класс `.btn--{variant}`.

| Variant | Описание | Пример кода |
| :--- | :--- | :--- |
| `primary` | **(По умолчанию)** Синяя заливка, белый текст. Основное действие. | `<BaseButton variant="primary">Action</BaseButton>` |
| `outline` | Прозрачная с синей рамкой. Второстепенное действие. | `<BaseButton variant="outline">Cancel</BaseButton>` |
| `secondary` | Белая с серой рамкой. | `<BaseButton variant="secondary">More</BaseButton>` |

## 📏 Как менять размер (Sizes)

Используйте проп `size`. Компонент подставит класс `.btn--{size}`.

| Size | Высота | Описание | Пример кода |
| :--- | :--- | :--- | :--- |
| `sm` | 40px | Маленькая кнопка | `<BaseButton size="sm">Small</BaseButton>` |
| `md` | 56px | **(По умолчанию)** Стандарт для калькуляторов | `<BaseButton size="md">Medium</BaseButton>` |
| `lg` | 64px | Большая кнопка | `<BaseButton size="lg">Large</BaseButton>` |

---

## ⚙️ Другие возможности

### Ссылка вместо кнопки
Если передать проп `href`, компонент автоматически превратится в тег `<a>`.

```html
<BaseButton href="/order" variant="primary">
  Перейти к заказу
</BaseButton>
```

### Состояние загрузки (Loading)
Передайте `loading`, чтобы показать спиннер. Кнопка автоматически заблокируется.

```html
<BaseButton :loading="isLoading" @click="saveData">
  <span v-if="!isLoading">Сохранить</span>
  <span v-else>Сохранение...</span>
</BaseButton>
```

### Блокировка (Disabled)

```html
<BaseButton disabled>
  Недоступно
</BaseButton>
```

---

## 🛠 Для разработчиков (Как это работает под капотом)

1. **HTML:** Компонент рендерит `<button>` или `<a>` с базовым классом `.btn`.
2. **JS:** Пропсы `variant` и `size` динамически формируют классы.
   * `variant="outline"` -> добавляет класс `.btn--outline`
   * `size="lg"` -> добавляет класс `.btn--lg`
3. **CSS:** Все стили лежат в блоке `<style scoped>` внутри `BaseButton.vue`.
   * Мы используем Tailwind `@apply` для применения стилей.
   * Чтобы добавить новый цвет, создайте класс `.btn--newcolor` в секции Variants.

**Пример добавления нового цвета (в BaseButton.vue):**

```css
/* В секции Variants */
.btn--danger {
    @apply bg-red-500 text-white;
}
.btn--danger:hover {
    @apply bg-red-600;
}
```

Теперь можно использовать: `<BaseButton variant="danger">Delete</BaseButton>`
