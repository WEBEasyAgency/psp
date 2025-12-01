<template>
  <div class="calculator">
    <!-- Заголовок (только для десктопа) -->
    <h1 class="calculator__title">Объемные буквы с бортом из алюминия</h1>
    
    <div class="calculator__content">
      <!-- Левая колонка: галерея и основные параметры -->
      <div class="calculator__left">
        <!-- Галерея изображений -->
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>


      </div>

      <!-- Правая колонка: опции материалов -->
      <div class="calculator__right">
        <!-- Основные параметры -->
        <div class="calculator__params">
          <!-- Текст или количество букв -->
          <div class="calculator__text-section inline-flex">
            <div class="calculator__text-input">
              <div class="field">
                <label class="field__label">Введите надпись</label>
                <div class="field__input">
                  <input
                      v-model="calculatorData.text"
                      type="text"
                      placeholder="Кафе"
                      class="input"
                  />
                </div>
              </div>

            </div>
            <div class="calculator__or">или</div>
            <NumberInput
                v-model="calculatorData.letterCount"
                label="Количество букв"
                :min="1"
                :max="50"
            />
          </div>

          <!-- Высота букв -->
          <NumberInput
              v-model="calculatorData.letterHeight"
              label="Высота букв, см"
              :min="5"
              :max="200"
              :step="5"
          />
        </div>
        <!-- Опции материалов -->
        <div class="calculator__options">
          <!-- Двухколоночная структура для поверхностей (только десктоп) -->
          <div class="calculator__surfaces-row">
            <!-- Лицевая поверхность -->
            <div class="calculator__surface-col">
              <FilterButtons
                v-model="calculatorData.frontSurface"
                label="Лицевая поверхность"
                :options="frontSurfaceOptions"
                :has-help="true"
              />
            </div>

            <!-- Боковая поверхность -->
            <div class="calculator__surface-col">
              <FilterButtons
                v-model="calculatorData.sideSurface"
                label="Боковая поверхность"
                :options="sideSurfaceOptions"
                :has-help="true"
              />
            </div>
          </div>
          <!-- Основа для букв -->
          <RadioGroup
              v-model="calculatorData.baseType"
              label="Основа для букв"
              :options="baseTypeOptions"
              :has-help="true"
          />
      <!-- Подсветка -->
      <FilterButtons
        v-model="calculatorData.lighting"
        label="Подсветка"
        :options="lightingOptions"
        :has-help="true"
      />



      <!-- Дополнительные услуги -->
      <div class="calculator__services">
        <div class="calculator__services-header">
          <span class="calculator__services-label">Дополнительные услуги</span>
          <div class="calculator__help-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.14648 9.07361C9.31728 8.54732 9.63015 8.07896 10.0508 7.71948C10.4714 7.36001 10.9838 7.12378 11.5303 7.03708C12.0768 6.95038 12.6362 7.0164 13.1475 7.22803C13.6587 7.43966 14.1014 7.78875 14.4268 8.23633C14.7521 8.68391 14.9469 9.21256 14.9904 9.76416C15.0339 10.3158 14.9238 10.8688 14.6727 11.3618C14.4215 11.8548 14.0394 12.2685 13.5676 12.5576C13.0958 12.8467 12.5533 12.9998 12 12.9998V14.0002M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 17V17.1L11.9502 17.1002V17H12.0498Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>

        <div class="calculator__services-list">
          <ToggleSwitch
            v-model="calculatorData.services.design"
            label="Дизайн"
          />
          <ToggleSwitch
            v-model="calculatorData.services.installation"
            label="Монтаж"
          />
          <ToggleSwitch
            v-model="calculatorData.services.delivery"
            label="Доставка по Самаре"
          />
        </div>
      </div>
    </div>
        <!-- Кнопка расчёта и результат -->
        <div class="calculator__action" :class="{ 'calculator__action--with-result': calculationResult }">
          <div class="calculator__info">
            Убедитесь что все фильтры выбраны верно и нажмите «Рассчитать стоимость». Для правильной работы калькулятор при каждом изменении фильров, требуется делать перерасчет.
          </div>
          <div class="calculator__action-buttons">


            <!-- Кнопка обновления расчёта (показывается после первого расчёта) -->
            <button
                v-if="calculationResult"
                @click="calculatePrice"
                :disabled="isCalculating"
                class="calculator__update-btn"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="17" height="20" viewBox="0 0 17 20" fill="none">
                <path d="M6.16724 13.75H1.16724V18.75M10.1672 5.75H15.1672V0.75M0.750244 6.7534C1.31093 5.36566 2.24968 4.16304 3.45979 3.28223C4.6699 2.40141 6.10409 1.87752 7.59699 1.77051C9.08989 1.6635 10.582 1.9774 11.9053 2.67661C13.2287 3.37582 14.3285 4.43254 15.0813 5.72612M15.5848 12.7471C15.0241 14.1348 14.0854 15.3374 12.8752 16.2182C11.6651 17.0991 10.2324 17.6223 8.7395 17.7293C7.2466 17.8363 5.7533 17.5225 4.42993 16.8232C3.10656 16.124 2.00606 15.0675 1.25317 13.7739" stroke="#2C619D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span v-if="isCalculating">Обновление...</span>
              <span v-else>Обновить расчет</span>
            </button>
            <div
                v-if="calculationResult"
                class="self-stretch h-14 px-6 bg-[#e6eef8] rounded-xl outline outline-offset-[-1px] outline-[#98bce1] inline-flex flex-col justify-center items-center gap-1">
              <div class="self-stretch inline-flex justify-between items-center">
                <div class="justify-start text-slate-500 text-xs font-medium font-['Inter'] uppercase leading-4 tracking-wide line-clamp-4">Итого:</div>
                <div class="justify-start text-[#282828] text-lg font-medium font-['Inter'] leading-[25.20px] line-clamp-4">{{ calculationResult.price_good }} ₽</div>
              </div>
            </div>

            <button
                v-if="!calculationResult"
                @click="calculatePrice"
                :disabled="isCalculating"
                class="calculator__calculate-btn"
            >
              <span v-if="isCalculating">Расчёт...</span>
              <span v-else>Рассчитать стоимость</span>
            </button>
            <!-- Кнопка "Оформить заказ" -->
            <a
                v-if="calculationResult"
                :href="orderLink"
                class="self-stretch px-6 py-4 bg-[#3c7bbb] rounded-xl inline-flex justify-center items-center gap-2">
              <div class=" flex justify-center items-center gap-1">
                <div class="justify-start text-white text-base font-normal font-['Inter'] leading-[22.40px]">Оформить сейчас</div>
              </div>
              <div data-svg-wrapper class="relative">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7 12H17M17 12L13 8M17 12L13 16" stroke="#F6F6F6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </a>

          </div>

          <!-- Результат расчёта -->

        </div>

        <!-- Ошибка -->
        <div v-if="error" class="calculator__error">
          {{ error }}
        </div>
      </div>
      </div>
    </div>


</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import RadioGroup from '@/shared/ui/RadioGroup.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'

// ID калькулятора для объёмных букв (S Буквы вклейка)
const CALC_ID = 146

// Реактивные данные
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const apiParams = ref(null)

// Галерея изображений
const galleryImages = ref([
  'https://placehold.co/343x257',
  'https://placehold.co/73x33',
  'https://placehold.co/47x33',
  'https://placehold.co/49x33',
  'https://placehold.co/47x33',
  'https://placehold.co/48x33'
])

// Опции для фильтров
const frontSurfaceOptions = ref([
  { value: 'white', label: 'Белая' },
  { value: 'colored', label: 'Цветная' }
])

const sideSurfaceOptions = ref([
  { value: 'white', label: 'Белая' },
  { value: 'black', label: 'Черная' },
  { value: 'colored', label: 'Цветная' }
])

const lightingOptions = ref([
  { value: 'eco', label: 'ECO (гарантия 1 год)' },
  { value: 'norm', label: 'NORM (гарантия 3 года)' },
  { value: 'premium', label: 'PREMIUM (гарантия 5 лет)' }
])

const baseTypeOptions = ref([
  { value: 'none', label: 'Не нужна' },
  { value: 'frame', label: 'На раме' },
  { value: 'composite', label: 'На подложке из композитного материала' }
])

// Данные калькулятора
const calculatorData = reactive({
  text: '',
  letterCount: null,
  letterHeight: 30,
  frontSurface: 'white',
  sideSurface: 'white',
  lighting: 'eco',
  baseType: 'none',
  services: {
    design: false,
    installation: false,
    delivery: false
  }
})

// Вычисляемое количество букв: берём из текста, если он есть, иначе из поля количества
const actualLetterCount = computed(() => {
  if (calculatorData.text && calculatorData.text.trim().length > 0) {
    return calculatorData.text.trim().length
  }
  return calculatorData.letterCount || 1
})

// Ссылка на страницу оформления заказа с GET параметрами
const orderLink = computed(() => {
  if (!calculationResult.value) return '#'

  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: `Объемные буквы (${actualLetterCount.value} ${getLetterWord(actualLetterCount.value)}, высота ${calculatorData.letterHeight}см)`
  })

  return `/layout/order.php?${params.toString()}`
})

// Склонение слова "буква"
const getLetterWord = (count) => {
  const cases = [2, 0, 1, 1, 1, 2]
  const titles = ['буква', 'буквы', 'букв']
  return titles[(count % 100 > 4 && count % 100 < 20) ? 2 : cases[Math.min(count % 10, 5)]]
}

// API функции
const fetchCalculatorParams = async () => {
  try {
    error.value = ''
    // Credentials передаются на стороне бэкенда, клиент ничего не отправляет
    const response = await fetch(`/backend/api/calc/${CALC_ID}/params`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    })
    
    if (!response.ok) {
      throw new Error('Ошибка при загрузке параметров калькулятора')
    }
    
    const data = await response.json()
    apiParams.value = data
  } catch (err) {
    error.value = err.message
    console.error('Error fetching calculator params:', err)
  }
}

const calculatePrice = async () => {
  try {
    isCalculating.value = true
    error.value = ''
    
    // Подготовка параметров для API калькулятора 146
    // ВАЖНО: type 1 (числа) - передаём как число
    //        type 5 (выбор) - передаём ИНДЕКС опции (0, 1, 2...)
    const params = []

    // sr_h - Высота буквы, см (type 1 - число)
    params.push({
      variable: 'sr_h',
      type: 1,
      value: calculatorData.letterHeight
    })

    // num - Кол-во букв (type 1 - число)
    // Используем actualLetterCount - берём из текста или из поля количества
    params.push({
      variable: 'num',
      type: 1,
      value: actualLetterCount.value
    })

    // face - Лицевая поверхность (type 5 - индекс: 0="Белая", 1="Цветная")
    params.push({
      variable: 'face',
      type: 5,
      value: calculatorData.frontSurface === 'white' ? 0 : 1
    })

    // bort - Боковая поверхность (type 5 - индекс: 0="Белая", 1="Черная", 2="Цветная")
    let bortIndex = 0
    if (calculatorData.sideSurface === 'black') bortIndex = 1
    else if (calculatorData.sideSurface === 'colored') bortIndex = 2
    params.push({
      variable: 'bort',
      type: 5,
      value: bortIndex
    })

    // brand - Подсветка Букв (type 5 - индекс: 0="ECO", 1="NORM", 2="PREMIUM")
    const brandIndex = calculatorData.lighting === 'eco' ? 0 : (calculatorData.lighting === 'norm' ? 1 : 2)
    params.push({
      variable: 'brand',
      type: 5,
      value: brandIndex
    })

    // ustanovka - Основа для букв (type 5 - индекс: 0="Не нужна", 1="Рама", 2="Подложка из композита")
    let ustanovkaIndex = 0
    if (calculatorData.baseType === 'frame') ustanovkaIndex = 1
    else if (calculatorData.baseType === 'composite') ustanovkaIndex = 2
    params.push({
      variable: 'ustanovka',
      type: 5,
      value: ustanovkaIndex
    })

    // rama_w - Ширина рамы (type 1 - число, 0 если рама не нужна)
    params.push({
      variable: 'rama_w',
      type: 1,
      value: 0
    })

    // color_rama - Цвет рамы (type 5 - индекс: 0="Белая", 1="Черная", 2="Цветная")
    params.push({
      variable: 'color_rama',
      type: 5,
      value: 0
    })

    // wp - Ширина подложки (type 1 - число, 0 если подложка не нужна)
    params.push({
      variable: 'wp',
      type: 1,
      value: 0
    })

    // hp - Высота подложки (type 1 - число, 0 если подложка не нужна)
    params.push({
      variable: 'hp',
      type: 1,
      value: 0
    })

    // colorp - Цвет подложки (type 5 - индекс: 0="Белый", 1="Черный", 2="Цветной")
    params.push({
      variable: 'colorp',
      type: 5,
      value: 0
    })
    
    // Credentials добавляются на стороне бэкенда
    const response = await fetch(`/backend/api/calc/${CALC_ID}/run`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        params: params,
        mat_select_params: []
      })
    })
    
    if (!response.ok) {
      throw new Error('Ошибка при расчёте стоимости')
    }
    
    const data = await response.json()
    calculationResult.value = data
  } catch (err) {
    error.value = err.message
    console.error('Error calculating price:', err)
  } finally {
    isCalculating.value = false
  }
}

// Автоматический пересчёт отключён - только по кнопке
// watch(
//   () => [calculatorData.text, calculatorData.letterCount, calculatorData.letterHeight, calculatorData.frontSurface, calculatorData.sideSurface, calculatorData.lighting, calculatorData.baseType],
//   () => {
//     if (calculationResult.value) {
//       calculatePrice()
//     }
//   },
//   { deep: true }
// )

// Загрузка параметров при монтировании
onMounted(() => {
  fetchCalculatorParams()
})
</script>

<style scoped>
@import "tailwindcss" reference;

.calculator {
  @apply self-stretch inline-flex flex-col justify-start items-start gap-6;
}

@media (min-width: 1280px) {
  .calculator {
    @apply w-[1400px] p-16 bg-white rounded-[32px] inline-flex flex-col justify-start items-start gap-16;
  }
}


  .calculator__title {
    @apply text-[#1e3552] text-[64px] font-medium font-['Inter'] leading-[64px] xl:whitespace-nowrap;
    letter-spacing: -1.2px;
  }

.calculator__content {
  @apply flex-col inline-flex justify-between items-start xl:flex-row gap-4 xl:gap-[74px];
}



.calculator__left {
  @apply flex flex-col;
}

@media (min-width: 1280px) {
  .calculator__left {
    @apply w-[568px] inline-flex flex-col justify-start items-start gap-8;
  }
}

.calculator__right {
  @apply flex flex-col;
}

@media (min-width: 1280px) {
  .calculator__right {
    @apply w-[624px] inline-flex flex-col justify-start items-start gap-8;
  }
}

.calculator__gallery {
  @apply w-[343px] inline-flex flex-col justify-start items-start gap-3;
}

@media (min-width: 1280px) {
  .calculator__gallery {
    @apply w-[568px];
  }
}

.calculator__params {
  @apply self-stretch flex flex-col justify-start items-start gap-8;
}

.calculator__text-section {
  @apply self-stretch flex flex-col xl:flex-row justify-start xl:justify-between items-start xl:items-end gap-2.5;
}

@media (min-width: 1280px) {
  .calculator__text-input {
    @apply self-stretch inline-flex justify-between items-end;
  }
}

.calculator__text-input {
  @apply self-stretch flex flex-col justify-start items-start gap-2.5;
}

.calculator__or {
  @apply justify-start text-slate-500 text-[13px] font-normal font-['Inter'] leading-[18.20px];
}

.field {
  @apply w-[298px] flex flex-col justify-start items-start gap-2;
}

.field__label {
  @apply self-stretch justify-end text-[#1e3552] text-base font-normal font-['Inter'] leading-[22.40px];
}

.field__input {
  @apply self-stretch h-12 p-3 bg-[#f6f6f6] rounded-2xl outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex justify-center items-center gap-2.5;
}

.input {
  @apply flex-1 justify-start text-[#9494a7] text-base font-normal font-['Inter'] leading-[22.40px] bg-transparent outline-none;
}

.calculator__options {
  @apply self-stretch flex flex-col justify-start items-start gap-8;
}

@media (min-width: 1280px) {
  .calculator__options {
    @apply self-stretch flex flex-col justify-start items-start gap-8;
  }
  
  /* Двухколоночная структура для лицевой и боковой поверхности */
  .calculator__surfaces-row {
    @apply self-stretch inline-flex justify-start items-start gap-8;
  }
  
  .calculator__surface-col {
    @apply flex-1 inline-flex flex-col justify-center items-start gap-4;
  }
}

.calculator__services {
  @apply self-stretch flex flex-col justify-center items-start gap-4;
}

.calculator__services-header {
  @apply h-6 inline-flex justify-start items-center gap-2;
}

.calculator__services-label {
  @apply justify-start text-[#2d2b2c] text-base font-medium font-['Inter'] leading-[22.40px];
}

.calculator__help-icon {
  @apply flex justify-start items-center gap-2.5;
}

.calculator__services-list {
  @apply self-stretch inline-flex justify-start items-start gap-6 flex-wrap content-start;
}

.calculator__action {
  @apply w-[343px] p-4 bg-[#f6f6f6] rounded-2xl flex flex-col justify-start items-start gap-2.5 overflow-hidden;
}

@media (min-width: 1280px) {
  .calculator__action {
    @apply  p-8 bg-[#f6f6f6] rounded-2xl inline-flex justify-start items-center gap-2.5 overflow-hidden w-full flex-row;
  }

  .calculator__action--with-result {
    @apply flex-col items-start;
  }
}

.calculator__info {
  @apply self-stretch justify-start text-slate-500 text-sm font-normal font-['Inter'] leading-5;
}

@media (min-width: 1280px) {
  .calculator__info {
    @apply w-[330px] justify-start text-slate-500 text-base font-normal font-['Inter'] leading-[22.40px];
  }
  .calculator__action--with-result .calculator__info {
    @apply w-full;
  }
  .calculator__action--with-result .calculator__action-buttons {
    @apply  grid grid-cols-2 gap-x-4 gap-y-5;
  }
}

.calculator__action-buttons {
  @apply self-stretch flex flex-col gap-2;
}

@media (min-width: 1280px) {
  .calculator__action-buttons {
    @apply self-stretch inline-flex justify-center items-center gap-4;
  }
}

.calculator__calculate-btn {
  @apply w-[226px] h-12 px-4 py-3 bg-[#3c7bbb] rounded-xl inline-flex justify-center items-center gap-2 text-white text-sm font-normal font-['Inter'] leading-5 transition-opacity disabled:opacity-50;
}

@media (min-width: 1280px) {
  .calculator__calculate-btn {
    @apply h-14 px-6 py-4 bg-[#3c7bbb] rounded-xl flex justify-center items-center gap-2 text-white text-base font-normal font-['Inter'] leading-[22.40px];
  }
}

.calculator__update-btn {
  @apply h-12 px-4 py-3 bg-white border-2 border-[#3c7bbb] rounded-xl inline-flex justify-center items-center gap-2 text-[#3c7bbb] text-sm font-normal font-['Inter'] leading-5 transition-opacity disabled:opacity-50;
}

@media (min-width: 1280px) {
  .calculator__update-btn {
    @apply h-14 px-6 py-4 bg-white border-2 border-[#3c7bbb] rounded-xl flex justify-center items-center gap-2 text-[#3c7bbb] text-base font-normal font-['Inter'] leading-[22.40px];
  }
}

.calculator__update-icon {
  @apply flex-shrink-0;
}

.calculator__result {
  @apply self-stretch bg-white rounded-2xl outline outline-1 outline-offset-[-1px] outline-slate-200 mt-4;
}

@media (min-width: 1280px) {
  .calculator__result {
    @apply self-stretch bg-white rounded-2xl outline outline-1 outline-offset-[-1px] outline-slate-200 ;
  }
}

.calculator__result-content {
  @apply self-stretch p-4 flex flex-col items-start gap-2;
}

@media (min-width: 1280px) {
  .calculator__result-content {
    @apply self-stretch p-4 flex flex-row justify-between items-center;
  }
}

.calculator__result-price {
  @apply text-lg font-semibold text-[#1e3552];
}

@media (min-width: 1280px) {
  .calculator__result-price {
    @apply text-xl font-semibold text-[#1e3552];
  }
}

.calculator__result-id {
  @apply text-sm text-slate-500;
}

@media (min-width: 1280px) {
  .calculator__result-id {
    @apply text-sm text-slate-500;
  }
}

.calculator__error {
  @apply self-stretch p-4 bg-red-50 rounded-2xl mt-4 text-red-800;
}
</style>