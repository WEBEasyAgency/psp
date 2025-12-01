# FAQ Блок - Документация по внедрению

## Статус реализации

✅ **Завершено**: FAQ блок переведен с Vue компонента на статичный HTML с нативным `<details>/<summary>` аккордеоном.

## Что было реализовано

### 1. Статичный HTML блок в `product.html`

- **Размещение**: Линии 269-456 в `F:\CODE\easy_web\psp\dev\product.html`
- **Мобильная версия**: 375px, скрыта на десктопе (`xl:hidden`)
- **Десктопная версия**: Full-width, видна только на больших экранах (`hidden xl:block`)
- **4 FAQ элемента** с вопросами и ответами

### 2. Структура HTML

Используется нативный HTML5 элемент `<details>` с `<summary>`:

```html
<details class="faq-item ...">
  <summary class="...">
    <div class="faq-icon">...</div>
    <div>Вопрос</div>
  </summary>
  <div class="faq-answer">
    Ответ на вопрос
  </div>
</details>
```

**Преимущества**:
- ✅ Не требует JavaScript
- ✅ Нативная поддержка во всех браузерах
- ✅ Встроенная доступность (accessibility)
- ✅ SEO friendly
- ✅ Семантичный HTML

### 3. CSS стили (встроены в `<head>`)

Стили управляют:

- **Скрытие дефолтного маркера**: `details summary { list-style: none; }`
- **Открытое состояние**: `details[open]` селектор изменяет:
  - Фон на градиент: `linear-gradient(141deg, rgba(193, 205, 217, 0.4) 0%, rgba(235, 240, 243, 0.4) 100%)`
  - Цвет иконки на синий: `#3c7bbb`
  - Цвет текста на синий: `#2c619d`
  - Штрих иконки на белый
- **Анимация иконки**: Вращение на 180 градусов при открытии
- **Анимация ответа**: Slide-down эффект при появлении

### 4. Классы для стилизации

- `.product-faq` - контейнер всего блока
- `.faq-item` - каждый FAQ элемент
- `.faq-icon` - иконка с стрелкой
- `.faq-answer` - контейнер ответа (скрывается по умолчанию, видна в `details[open]`)

## Как использовать

### Добавить новый FAQ элемент

```html
<details class="faq-item self-stretch p-4 rounded-3xl flex flex-col justify-start items-end gap-3 bg-white outline outline-1 outline-offset-[-1px] outline-slate-200">
  <summary class="self-stretch inline-flex justify-start items-center gap-4 cursor-pointer list-none">
    <div class="faq-icon size-9 p-3 rounded-xl flex justify-center items-center gap-2.5 transition-colors bg-[#f6f6f6]">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 14L12 10L16 14" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <div class="flex-1 max-w-[650px] justify-start text-[#170f49] text-base font-medium font-['Inter'] leading-[22.40px]">Ваш вопрос</div>
  </summary>
  <div class="faq-answer self-stretch pl-[52px] inline-flex justify-start items-start gap-2.5">
    <div class="flex-1 justify-start text-slate-900 text-sm font-normal font-['Inter'] leading-5">Ваш ответ</div>
  </div>
</details>
```

### Изменить цвета

Отредактируйте CSS в `<style>` теге в `<head>`:
- Цвет открытого состояния: `.product-faq details[open] .faq-icon { background-color: #3c7bbb; }`
- Цвет текста открытого: `.product-faq details[open] summary div:last-child { color: #2c619d; }`
- Градиент фона: `.product-faq details[open] { background: linear-gradient(...) }`

## Вариант с минимальным JS (опционально)

Если нужна функциональность "только один открытый элемент", добавьте этот скрипт перед `</body>`:

```javascript
<script>
document.querySelectorAll('.product-faq details').forEach(details => {
  details.addEventListener('click', (e) => {
    if (e.target.closest('summary')) {
      // Закрыть все остальные элементы в этой группе
      const parent = details.closest('.product-faq');
      parent?.querySelectorAll('details').forEach(d => {
        if (d !== details) d.open = false;
      });
    }
  });
});
</script>
```

## Браузеры и совместимость

- ✅ Chrome 12+
- ✅ Firefox 49+
- ✅ Safari 15.4+
- ✅ Edge 79+
- ⚠️ IE 11 - не поддерживается (но можно использовать полифилл)

## Файлы для внесения изменений

- **HTML**: `F:\CODE\easy_web\psp\dev\product.html` (строки 269-456)
- **Стили**: Встроены в `<style>` тег в `<head>` (строки 11-74)
- **Build**: `F:\CODE\easy_web\psp\dev\dist\product.html` (автоматически собирается)

## Следующие шаги

1. ✅ Протестировать на разных разрешениях экрана
2. ✅ Проверить отображение на мобильных браузерах
3. Опционально: Добавить поддержку только одного открытого элемента (требует JS)
4. Опционально: Добавить аналитику для отслеживания кликов на FAQ

## Примечания

- FAQ блок полностью встроен в HTML без внешних зависимостей
- Все стили используют Tailwind CSS классы
- CSS для `<details>/<summary>` встроен локально и не конфликтует с другими компонентами
- При изменении файла `product.html` нужно запустить `npm run build` для обновления `dist/product.html`
