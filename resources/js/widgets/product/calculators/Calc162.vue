<template>
  <div class="calculator calculator--v2">
    <h1 class="calculator__title">Баннер с люверсами</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" :max-visible="6" />
        </div>
        <div class="calculator__gallery-text" v-html="props.description"></div>
      </div>

      <div class="calculator__right">
        <!-- Ширина + Высота -->
        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, м" :min="0.3" :max="50" :step="0.1" />
            <NumberInput v-model="calculatorData.h" label="Высота, м" :min="0.3" :max="50" :step="0.1" />
          </div>
        </div>

        <!-- Баннерная ткань + Печать -->
        <div class="calculator__options">
          <FilterButtons
              v-if="options.banner.length"
              v-model="calculatorData.banner"
              label="Баннерная ткань"
              :options="options.banner"
          />
          <FilterButtons
              v-if="options.print.length"
              v-model="calculatorData.print"
              label="Печать"
              :options="options.print"
          />
        </div>

        <!-- Усиление края по периметру -->
        <div class="toggle-section">
          <div class="toggle-section__label">Усиление края по периметру</div>
          <ToggleSwitch
              v-model="calculatorData.need_prokleyka"
              label="Усилить"
          />
        </div>

        <!-- Шаг установки люверсов + Загиб -->
        <div class="calculator__options">
          <FilterButtons
              v-if="luversOptions.length"
              v-model="selectedLuvers"
              label="Шаг установки люверсов"
              :options="luversOptions"
          />
          <FilterButtons
              v-if="options.zagib.length"
              v-model="calculatorData.zagib"
              label="Загиб баннера в карман:"
              :options="options.zagib"
          />
        </div>

        <!-- Дополнительно (чекбоксы без "Нет") -->
        <CheckboxGroup
            v-if="addedCheckboxOptions.length > 0"
            v-model="selectedAdded"
            label="Дополнительно"
            :options="addedCheckboxOptions"
        />

        <!-- Количество -->
        <div class="quantity-block">
          <NumberInput v-model="calculatorData.added_num" label="Количество, шт" :min="1" :max="1000" />
          <InfoTooltip />
        </div>
        <CalculatorAction
            :result="calculationResult"
            :loading="isCalculating"
            :order-link="orderLink"
            :calculator-id="calcId"
            :calculator-data="calculatorDataForCart"
            :description="cartItemDescription"
            :image="cartItemImage"
            @calculate="calculatePrice"
        />
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
import CheckboxGroup from '@/shared/ui/CheckboxGroup.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import InfoTooltip from '@/shared/ui/InfoTooltip.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: { type: String, default: '' }
})

const calcId = 162
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])

// Виртуальная опция "Нет" для люверсов (не из API)
const LUVERS_NONE = '__none__'
const selectedLuvers = ref(LUVERS_NONE)
const selectedAdded = ref([])

const options = reactive({
  banner: [],
  print: [],
  luvers_space: [],
  zagib: [],
  added: []
})

const rawApiOptions = reactive({})
const apiParamTypes = reactive({})

const calculatorData = reactive({
  w: 1,
  h: 1,
  banner: null,
  print: null,
  need_prokleyka: false,
  need_luvers: false,
  luvers_space: null,
  zagib: null,
  added: null,
  added_num: 1
})

// Люверсы: "Нет" + опции из API
const luversOptions = computed(() => {
  return [{ value: LUVERS_NONE, label: 'Нет' }, ...options.luvers_space]
})

// Чекбоксы "Дополнительно": фильтруем "Нет" из API опций
const addedCheckboxOptions = computed(() => {
  return options.added
    .filter(opt => opt.value !== 'Нет')
    .map(opt => ({ value: opt.value, label: opt.label }))
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : ['https://placehold.co/343x257'])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: 'Баннер с люверсами'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Баннер с люверсами')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: 'w', type: 1, value: calculatorData.w },
    { variable: 'h', type: 1, value: calculatorData.h },
    { variable: 'banner', type: apiParamTypes.banner || 4, value: calculatorData.banner },
    { variable: 'print', type: 5, value: calculatorData.print },
    { variable: 'need_prokleyka', type: 2, value: calculatorData.need_prokleyka ? 1 : 0 },
    { variable: 'need_luvers', type: 2, value: selectedLuvers.value !== LUVERS_NONE ? 1 : 0 },
    { variable: 'luvers_space', type: 5, value: selectedLuvers.value !== LUVERS_NONE ? selectedLuvers.value : null },
    { variable: 'zagib', type: 5, value: calculatorData.zagib },
    { variable: 'added', type: 5, value: selectedAdded.value.length > 0 ? selectedAdded.value[selectedAdded.value.length - 1] : 'Нет' }
  ],
  mat_select_params: []
}))

const fetchCalculatorParams = async () => {
  try {
    error.value = ''
    const response = await fetch(`/backend/api/calc/${calcId}/params`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
    })
    if (!response.ok) throw new Error('Ошибка загрузки')
    const data = await response.json()

    if (data.params) {
      data.params.forEach(p => {
        apiParamTypes[p.variable] = p.type

        if (p.type === 1 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = parseFloat(p.default) || calculatorData[p.variable]
        }
        if (p.type === 2 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = !!parseInt(p.default)
        }
        if ((p.type === 4 || p.type === 5) && p.options && options[p.variable] !== undefined) {
          rawApiOptions[p.variable] = p.options
          const opts = p.options.map(o => ({ value: o, label: o }))
          options[p.variable] = opts
          // luvers_space: не устанавливаем default, "Нет" выбран по умолчанию
          if (p.variable !== 'luvers_space') {
            if (opts.length > 0) calculatorData[p.variable] = opts[0].value
          }
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

    // need_luvers и luvers_space из selectedLuvers
    const needLuvers = selectedLuvers.value !== LUVERS_NONE ? 1 : 0
    const luversIndex = needLuvers ? getIndex('luvers_space', selectedLuvers.value) : 0

    // added: из selectedAdded чекбоксов
    const addedValue = selectedAdded.value.length > 0
      ? selectedAdded.value[selectedAdded.value.length - 1]
      : 'Нет'

    const params = [
      { variable: 'w', type: 1, value: calculatorData.w },
      { variable: 'h', type: 1, value: calculatorData.h },
      { variable: 'banner', type: apiParamTypes.banner || 4, value: getIndex('banner', calculatorData.banner) },
      { variable: 'print', type: 5, value: getIndex('print', calculatorData.print) },
      { variable: 'need_prokleyka', type: 2, value: calculatorData.need_prokleyka ? 1 : 0 },
      { variable: 'need_luvers', type: 2, value: needLuvers },
      { variable: 'luvers_space', type: 5, value: luversIndex },
      { variable: 'zagib', type: 5, value: getIndex('zagib', calculatorData.zagib) },
      { variable: 'added', type: 5, value: getIndex('added', addedValue) }
    ]

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ params, mat_select_params: [] })
    })

    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.error || 'Ошибка расчета')
    }
    calculationResult.value = await response.json()
  } catch (err) {
    error.value = err.message
  } finally {
    isCalculating.value = false
  }
}

onMounted(async () => {
  await fetchCalculatorParams()
  if (isEditMode.value) {
    restoreParams(calculatorData, rawMatSelectParams)
  }
})
</script>

<style scoped>
@import "tailwindcss" reference;

.toggle-section {
  @apply flex flex-col;
  gap: 16px;
}

.toggle-section__label {
  font-size: 18px;
  font-weight: 500;
  color: #282828;
  line-height: 1.4;
}

.quantity-block {
  @apply flex flex-col;
  gap: 12px;
}
</style>
