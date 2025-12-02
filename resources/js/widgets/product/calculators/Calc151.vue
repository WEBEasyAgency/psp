<template>
  <div class="calculator">
    <h1 class="calculator__title">Стенд из пластика с карманами</h1>
    
    <div class="calculator__content">
      <!-- Левая колонка: Галерея -->
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>

        <!-- Текстовый контент под галереей -->
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5">
          <p class="mb-2"><strong>Стенд из пластика с карманами из ПВХ</strong> — это бюджетный и практичный вариант для оформления интерьера и экстерьера. Материал легок в обработке и позволяет создавать изделия любых форм.</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Доступная стоимость</li>
            <li>Легкий вес конструкции</li>
            <li>Универсальность применения</li>
          </ul>
        </div>
      </div>

      <!-- Правая колонка: Параметры -->
      <div class="calculator__right">
        
        <!-- Секция 1: Размеры и количество -->
        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, см" :min="10" :max="1000" />
            <NumberInput v-model="calculatorData.h" label="Высота, см" :min="10" :max="1000" />
          </div>
          <NumberInput v-model="calculatorData.num" label="Количество" :min="1" :max="100" />
        </div>

        <!-- Секция 2: Материал (Толщина) - ТУТ МЕНЯТЬ ПОРЯДОК ЕСЛИ НУЖНО -->
        <div class="calculator__options">
            <!-- Используем FilterButtons вместо RadioGroup по запросу -->
            <FilterButtons
                v-if="options.mat_select_in3"
                v-model="calculatorData.mat_select_in3"
                label="Толщина пластика"
                :options="options.mat_select_in3"
                :has-help="true"
            />

            <!-- Сюда можно вставить любой текст -->
            <!-- <p class="text-sm text-gray-500">Примечание по материалам...</p> -->
        </div>

        <!-- Секция 3: Опции (Чекбоксы) -->
        <div class="calculator__services">
            <div class="calculator__services-header">
              <span class="calculator__services-label">Параметры изготовления</span>
            </div>
            <div class="calculator__services-list">
                <!-- Жестко прописанные тогглы, привязанные к данным -->
                <ToggleSwitch v-model="calculatorData.tape" label="Двусторонний скотч" />
                <ToggleSwitch v-model="calculatorData.drills" label="Отверстия по углам" />
                <ToggleSwitch v-model="calculatorData.doubleside" label="Двусторонняя печать" />
                <ToggleSwitch v-model="calculatorData.need_Nielsen" label="Профиль Nielsen" />
            </div>
        </div>

        <!-- Секция 4: Услуги (Статичные) -->
        <div class="calculator__services">
          <div class="calculator__services-header">
            <span class="calculator__services-label">Дополнительные услуги</span>
          </div>
          <div class="calculator__services-list">
            <ToggleSwitch v-model="calculatorData.services.design" label="Дизайн" />
            <ToggleSwitch v-model="calculatorData.services.installation" label="Монтаж" />
            <ToggleSwitch v-model="calculatorData.services.delivery" label="Доставка" />
          </div>
        </div>

        <!-- Итого и Кнопки -->
        <div class="calculator__action" :class="{ 'calculator__action--with-result': calculationResult }">
           <div class="calculator__info">Проверьте параметры и нажмите «Рассчитать стоимость».</div>
           
           <div class="calculator__action-buttons">
            <button v-if="calculationResult" @click="calculatePrice" :disabled="isCalculating" class="calculator__update-btn">
              <span v-if="isCalculating">Обновление...</span>
              <span v-else>Обновить расчет</span>
            </button>
            
             <div v-if="calculationResult" class="result-box">
              <div class="result-row">
                <div class="result-label">Итого:</div>
                <div class="result-value">{{ calculationResult.price_good }} ₽</div>
              </div>
            </div>

            <button v-if="!calculationResult" @click="calculatePrice" :disabled="isCalculating" class="calculator__calculate-btn">
              <span v-if="isCalculating">Расчёт...</span>
              <span v-else>Рассчитать стоимость</span>
            </button>

             <a v-if="calculationResult" :href="orderLink" class="calculator__order-btn">
              <div class="btn-content">Заказать</div>
            </a>
          </div>
        </div>
         <div v-if="error" class="calculator__error">{{ error }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'

const props = defineProps({
  initialImages: { type: Array, default: () => [] }
})

const calcId = 151 // Жестко задаем ID
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

// Опции для селектов/кнопок, загружаемые с бека
const options = reactive({
    mat_select_in3: [] // Материалы
})

// Основные данные калькулятора
const calculatorData = reactive({
  w: 100,
  h: 50,
  num: 1,
  // Специфичные поля для 156
  mat_select_in3: null, // ID выбранного материала
  tape: false,
  drills: false,
  doubleside: false,
  need_Nielsen: false,
  
  services: {
    design: false,
    installation: false,
    delivery: false
  }
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : [
  'https://placehold.co/343x257'
])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: `Стенд с карманами ${calculatorData.w}x${calculatorData.h}см`
  })
  return `/order?${params.toString()}`
})

const fetchCalculatorParams = async () => {
  try {
    error.value = ''
    const response = await fetch(`/backend/api/calc/${calcId}/params`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
    })
    if (!response.ok) throw new Error('Ошибка загрузки')
    const data = await response.json()

    // Маппинг полученных данных на наши поля
    // 1. Обычные параметры (w, h, num, tape...) - тут мы просто берем дефолты если надо
    if (data.params) {
        data.params.forEach(p => {
            if (p.type === 1 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = parseInt(p.default) || calculatorData[p.variable]
            }
            if (p.type === 2 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = !!parseInt(p.default)
            }
        })
    }

    // 2. Материалы (mat_select_params)
    // В 156 есть mat_select_in3 (ПВХ пластик)
    if (data.mat_select_params) {
        data.mat_select_params.forEach(p => {
            // Собираем опции для кнопок: { label: '3мм', value: 101 }
            // Имя материала часто длинное "ПВХ пластик 3мм", нам может быть нужно сократить или оставить как есть
            const opts = p.materials.map(m => ({ value: m.id, label: m.name }))
            options[p.variable] = opts
            
            // Дефолт
            if (opts.length > 0) {
                calculatorData[p.variable] = opts[0].value
            }
        })
    }
  } catch (err) {
    error.value = err.message
  }
}

const calculatePrice = async () => {
  try {
    isCalculating.value = true
    
    // Собираем params
    const params = [
        { variable: 'w', type: 1, value: calculatorData.w },
        { variable: 'h', type: 1, value: calculatorData.h },
        { variable: 'num', type: 1, value: calculatorData.num },
        { variable: 'tape', type: 2, value: calculatorData.tape ? 1 : 0 },
        { variable: 'drills', type: 2, value: calculatorData.drills ? 1 : 0 },
        { variable: 'doubleside', type: 2, value: calculatorData.doubleside ? 1 : 0 },
        { variable: 'need_Nielsen', type: 2, value: calculatorData.need_Nielsen ? 1 : 0 },
    ]

    // Собираем mat_select_params
    const matParams = []
    if (calculatorData.mat_select_in3) {
        matParams.push({
            variable: 'mat_select_in3',
            value: calculatorData.mat_select_in3
        })
    }

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        params: params,
        mat_select_params: matParams
      })
    })

    if (!response.ok) throw new Error('Ошибка расчета')
    calculationResult.value = await response.json()

  } catch (err) {
    error.value = err.message
  } finally {
    isCalculating.value = false
  }
}

onMounted(fetchCalculatorParams)
</script>

<style scoped>
@import "tailwindcss" reference;

.calculator { @apply self-stretch inline-flex flex-col justify-start items-start gap-6; }
@media (min-width: 1280px) { .calculator { @apply w-[1400px] p-16 bg-white rounded-[32px] inline-flex flex-col justify-start items-start gap-16; } }
.calculator__title { @apply text-[#1e3552] text-[64px] font-medium font-['Inter'] leading-[64px] xl:whitespace-nowrap; letter-spacing: -1.2px; }
.calculator__content { @apply flex-col inline-flex justify-between items-start xl:flex-row gap-4 xl:gap-[74px] w-full; }
.calculator__left { @apply flex flex-col w-full xl:w-[568px]; }
.calculator__right { @apply flex flex-col w-full xl:w-[624px] gap-8; }
.calculator__params { @apply flex flex-col gap-6; }
.calculator__dims-row { @apply flex flex-col xl:flex-row gap-4; }
.calculator__options { @apply flex flex-col gap-8; }
.calculator__services { @apply flex flex-col gap-4; }
.calculator__services-list { @apply flex flex-wrap gap-4; }
.calculator__services-label { @apply text-[#2d2b2c] text-base font-medium; }
.calculator__action { @apply p-4 bg-[#f6f6f6] rounded-2xl flex flex-col gap-4; }
@media (min-width: 1280px) { 
    .calculator__action { @apply p-8 flex-row items-center justify-between; }
    .calculator__action--with-result { @apply flex-col items-start; }
    .calculator__action--with-result .calculator__action-buttons { @apply grid grid-cols-2 gap-4 w-full; }
}
.calculator__action-buttons { @apply flex flex-col gap-2 w-full xl:w-auto; }
@media (min-width: 1280px) { .calculator__action-buttons { @apply flex-row; } }
.calculator__info { @apply text-slate-500 text-sm xl:text-base; }
.calculator__calculate-btn, .calculator__update-btn, .calculator__order-btn { @apply h-12 xl:h-14 px-6 bg-[#3c7bbb] rounded-xl flex justify-center items-center text-white text-base font-normal w-full cursor-pointer; }
.calculator__update-btn { @apply bg-white border-2 border-[#3c7bbb] text-[#3c7bbb]; }
.calculator__error { @apply p-4 bg-red-50 text-red-800 rounded-xl; }
.result-box { @apply self-stretch h-14 px-6 bg-[#e6eef8] rounded-xl outline outline-offset-[-1px] outline-[#98bce1] inline-flex flex-col justify-center items-center gap-1; }
.result-row { @apply self-stretch inline-flex justify-between items-center; }
.result-label { @apply justify-start text-slate-500 text-xs font-medium font-['Inter'] uppercase leading-4 tracking-wide; }
.result-value { @apply justify-start text-[#282828] text-lg font-medium font-['Inter'] leading-[25.20px]; }
</style>
