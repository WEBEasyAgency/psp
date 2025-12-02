<template>
  <div class="calculator">
    <h1 class="calculator__title">Объемные буквы со световым бортом</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages"/>
        </div>

        <!-- Текстовый контент под галереей -->
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5">
          <p class="mb-2"><strong>Объемные буквы со световым бортом</strong> создают эффектный ореол свечения, делая вывеску визуально легкой и привлекательной. Светится не только лицевая часть, но и боковые грани.</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Максимальная яркость и заметность</li>
            <li>Полностью акриловый корпус</li>
            <li>Премиальный внешний вид</li>
          </ul>
        </div>
      </div>

      <div class="calculator__right">

        <!-- 1. Текст и Размер -->
        <div class="calculator__params">
          <div class="calculator__text-section inline-flex">
            <div class="calculator__text-input">
              <div class="field">
                <label class="field__label">Введите надпись</label>
                <div class="field__input">
                  <input v-model="calculatorData.text" type="text" placeholder="Кафе" class="input"/>
                </div>
              </div>
            </div>
            <div class="calculator__or">или</div>
            <NumberInput v-model="calculatorData.num" label="Количество букв" :min="1" :max="50"/>
          </div>
          <NumberInput v-model="calculatorData.sr_h" label="Высота букв, см" :min="5" :max="200" :step="5"/>
        </div>

        <!-- 2. Поверхности -->
        <div class="calculator__options">
          <div class="calculator__surfaces-row">
            <div class="calculator__surface-col">
              <FilterButtons
                  v-if="options.face"
                  v-model="calculatorData.face"
                  label="Лицевая поверхность"
                  :options="options.face"
                  :has-help="true"
              />
            </div>
            <div class="calculator__surface-col">
              <FilterButtons
                  v-if="options.bort"
                  v-model="calculatorData.bort"
                  label="Боковая поверхность"
                  :options="options.bort"
                  :has-help="true"
              />
            </div>
          </div>
          <!-- 4. Подсветка -->
          <div class="xl:pr-4">
            <FilterButtons
                v-if="options.brand"
                v-model="calculatorData.brand"
                label="Подсветка"
                :options="options.brand"
                :has-help="true"
                :full-width-buttons="true"
            />
          </div>
          <!-- 3. Основа -->
          <RadioGroup
              v-if="options.ustanovka"
              v-model="calculatorData.ustanovka"
              label="Основа для букв"
              :options="options.ustanovka"
              :has-help="true"
          >
            <template #details="{ option, isActive }">
              <div v-if="isActive && option.value === 'Рама'" class="calculator__sub-options">
                <NumberInput v-model="calculatorData.rama_w" label="Ширина рамы, см" :min="0" :max="500"/>
                <FilterButtons
                    v-if="options.color_rama"
                    v-model="calculatorData.color_rama"
                    label="Цвет рамы"
                    :options="options.color_rama"
                    :has-help="true"
                />
              </div>
              <div v-if="isActive && option.value === 'Подложка из композита'" class="calculator__sub-options">
                <NumberInput v-model="calculatorData.wp" label="Ширина подложки, см" :min="0" :max="500"/>
                <NumberInput v-model="calculatorData.hp" label="Высота подложки, см" :min="0" :max="500"/>
                <FilterButtons
                    v-if="options.colorp"
                    v-model="calculatorData.colorp"
                    label="Цвет подложки"
                    :options="options.colorp"
                    :has-help="true"
                />
              </div>
            </template>
          </RadioGroup>


        </div>


        <!-- Итого -->
        <div class="calculator__action" :class="{ 'calculator__action--with-result': calculationResult }">
          <div class="calculator__info">Проверьте параметры и нажмите «Рассчитать стоимость».</div>
          <div class="calculator__action-buttons">
            <button v-if="calculationResult" @click="calculatePrice" :disabled="isCalculating"
                    class="calculator__update-btn">
              <span v-if="isCalculating">Обновление...</span>
              <span v-else>Обновить расчет</span>
            </button>
            <div v-if="calculationResult" class="result-box">
              <div class="result-row">
                <div class="result-label">Итого:</div>
                <div class="result-value">{{ calculationResult.price_good }} ₽</div>
              </div>
            </div>
            <button v-if="!calculationResult" @click="calculatePrice" :disabled="isCalculating"
                    class="calculator__calculate-btn">
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
import {ref, reactive, computed, onMounted} from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import RadioGroup from '@/shared/ui/RadioGroup.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'

const props = defineProps({
  initialImages: {type: Array, default: () => []}
})

const calcId = 155
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

const options = reactive({
  face: [],
  bort: [],
  brand: [],
  ustanovka: [],
  color_rama: [],
  colorp: []
})
const rawApiOptions = reactive({})

const calculatorData = reactive({
  text: '',
  sr_h: 30,
  num: null,
  face: null,
  bort: null,
  brand: null,
  ustanovka: null,
  rama_w: null,
  color_rama: null,
  wp: null,
  hp: null,
  colorp: null,
  services: {
    design: false,
    installation: false,
    delivery: false
  }
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : [
  'https://placehold.co/343x257'
])

const actualLetterCount = computed(() => {
  if (calculatorData.text && calculatorData.text.trim().length > 0) {
    return calculatorData.text.trim().length
  }
  return calculatorData.num || 1
})

const getLetterWord = (count) => {
  const cases = [2, 0, 1, 1, 1, 2]
  const titles = ['буква', 'буквы', 'букв']
  return titles[(count % 100 > 4 && count % 100 < 20) ? 2 : cases[Math.min(count % 10, 5)]]
}

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: `Объемные буквы (${actualLetterCount.value} ${getLetterWord(actualLetterCount.value)}, высота ${calculatorData.sr_h}см)`
  })
  return `/order?${params.toString()}`
})

const fetchCalculatorParams = async () => {
  try {
    error.value = ''
    const response = await fetch(`/backend/api/calc/${calcId}/params`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}
    })
    if (!response.ok) throw new Error('Ошибка загрузки')
    const data = await response.json()

    if (data.params) {
      data.params.forEach(p => {
        if (p.type === 1 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = parseInt(p.default) || calculatorData[p.variable]
        }
                    if (p.type === 5 && p.options && options[p.variable] !== undefined) {
                        rawApiOptions[p.variable] = p.options
                        let opts;
                        if (p.variable === 'brand') {
                            opts = p.options.map(o => {
                                let label = o;
                                if (o === 'ECO') label = 'ECO (гарантия 1 год)';
                                else if (o === 'NORM') label = 'NORM (гарантия 3 года)';
                                else if (o === 'PREMIUM') label = 'PREMIUM (гарантия 5 лет)';
                                return { value: o, label: label };
                            });
                        } else {
                            opts = p.options.map(o => ({ value: o, label: o }));
                        }
                        options[p.variable] = opts
                        if (opts.length > 0) calculatorData[p.variable] = opts[0].value
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

    const getIndex = (variable, value) => {
      const list = rawApiOptions[variable] || []
      const idx = list.indexOf(value)
      return idx === -1 ? 0 : idx
    }

    const params = [
      {variable: 'sr_h', type: 1, value: calculatorData.sr_h},
      {variable: 'num', type: 1, value: actualLetterCount.value},
      {variable: 'rama_w', type: 1, value: calculatorData.rama_w},
      {variable: 'wp', type: 1, value: calculatorData.wp},
      {variable: 'hp', type: 1, value: calculatorData.hp},

      {variable: 'face', type: 5, value: getIndex('face', calculatorData.face)},
      {variable: 'bort', type: 5, value: getIndex('bort', calculatorData.bort)},
      {variable: 'brand', type: 5, value: getIndex('brand', calculatorData.brand)},
      {variable: 'ustanovka', type: 5, value: getIndex('ustanovka', calculatorData.ustanovka)},
      {variable: 'color_rama', type: 5, value: getIndex('color_rama', calculatorData.color_rama)},
      {variable: 'colorp', type: 5, value: getIndex('colorp', calculatorData.colorp)},
    ]

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        params: params,
        mat_select_params: []
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
  @apply flex-col inline-flex justify-between items-start xl:flex-row gap-4 xl:gap-[74px] w-full;
}

.calculator__left {
  @apply flex flex-col w-full xl:w-[568px];
}

.calculator__right {
  @apply flex flex-col w-full xl:w-[630px] gap-8;
}

.calculator__params {
  @apply flex flex-col gap-8;
}

.calculator__text-section {
  @apply self-stretch flex flex-col xl:flex-row justify-start xl:justify-between items-start xl:items-end gap-2.5 xl:gap-[58px];
}

.calculator__text-input {
  @apply self-stretch flex flex-col justify-start items-start gap-2.5 w-full;
}

.calculator__or {
  @apply justify-start text-slate-500 text-[13px] font-normal font-['Inter'] leading-[18.20px];
}

.field {
  @apply self-stretch flex flex-col justify-start items-start gap-2;
}

.field__label {
  @apply self-stretch justify-end text-[#1e3552] text-base font-normal font-['Inter'] leading-[22.40px];
}

.field__input {
  @apply self-stretch w-full h-12 p-3 bg-[#f6f6f6] rounded-2xl outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex justify-center items-center gap-2.5;
}

.input {
  @apply flex-1 justify-start text-[#9494a7] text-base font-normal font-['Inter'] leading-[22.40px] bg-transparent outline-none;
}

.calculator__options {
  @apply flex flex-col gap-8;
}

.calculator__surfaces-row {
  @apply self-stretch inline-flex justify-start items-start gap-8;
}

.calculator__surface-col {
  @apply flex-1 inline-flex flex-col justify-center items-start gap-4;
}

.calculator__sub-options {
  @apply flex flex-col gap-4 pl-6 mt-2 border-l-2 border-[#e2e8f0];
}

.calculator__services {
  @apply flex flex-col gap-4;
}

.calculator__services-list {
  @apply flex flex-wrap gap-4;
}

.calculator__services-label {
  @apply text-[#2d2b2c] text-base font-medium;
}

.calculator__action {
  @apply p-4 bg-[#f6f6f6] rounded-2xl flex flex-col gap-4;
}

@media (min-width: 1280px) {
  .calculator__action {
    @apply p-8 flex-row items-center justify-between;
  }

  .calculator__action--with-result {
    @apply flex-col items-start;
  }

  .calculator__action--with-result .calculator__action-buttons {
    @apply grid grid-cols-2 gap-4 w-full;
  }
}

.calculator__action-buttons {
  @apply flex flex-col gap-2 w-full xl:w-auto;
}

@media (min-width: 1280px) {
  .calculator__action-buttons {
    @apply flex-row;
  }
}

.calculator__info {
  @apply text-slate-500 text-sm xl:text-base;
}

.calculator__calculate-btn, .calculator__update-btn, .calculator__order-btn {
  @apply h-12 xl:h-14 px-6 bg-[#3c7bbb] rounded-xl flex justify-center items-center text-white text-base font-normal w-full cursor-pointer;
}

.calculator__update-btn {
  @apply bg-white border-2 border-[#3c7bbb] text-[#3c7bbb];
}

.calculator__error {
  @apply p-4 bg-red-50 text-red-800 rounded-xl;
}

.result-box {
  @apply self-stretch h-14 px-6 bg-[#e6eef8] rounded-xl outline outline-offset-[-1px] outline-[#98bce1] inline-flex flex-col justify-center items-center gap-1;
}

.result-row {
  @apply self-stretch inline-flex justify-between items-center;
}

.result-label {
  @apply justify-start text-slate-500 text-xs font-medium font-['Inter'] uppercase leading-4 tracking-wide;
}

.result-value {
  @apply justify-start text-[#282828] text-lg font-medium font-['Inter'] leading-[25.20px];
}
</style>