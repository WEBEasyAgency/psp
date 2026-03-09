<template>
  <div class="calculator calculator--v2">
    <h1 class="calculator__title">Режим работы (наклейка)</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" :max-visible="6" />
        </div>
        <div class="calculator__gallery-text" v-html="props.description"></div>
      </div>

      <div class="calculator__right">
        <SizePresets
            v-model="activePreset"
            :presets="sizePresets"
            @select="applyPreset"
        />

        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, см" :min="5" :max="200" @update:modelValue="activePreset = null" />
            <NumberInput v-model="calculatorData.h" label="Высота, см" :min="5" :max="200" @update:modelValue="activePreset = null" />
          </div>
        </div>

        <div class="calculator__options">
          <FilterButtons
              v-if="options.side && options.side.length"
              v-model="calculatorData.side"
              label="Будет клеится на стекло"
              :options="options.side"
          />
        </div>

        <div class="toggle-section">
          <div class="toggle-section__label">Инструмент для монтажа</div>
          <ToggleSwitch
              v-model="calculatorData.rakel"
              label="Приложить инструмент для монтажа (ракель)"
          />
        </div>

        <div class="quantity-block">
          <NumberInput v-model="calculatorData.num" label="Количество, шт" :min="1" :max="100" />
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
import SizePresets from '@/shared/ui/SizePresets.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import InfoTooltip from '@/shared/ui/InfoTooltip.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: { type: String, default: '' }
})

const calcId = 175
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])
const activePreset = ref(null)

const sizePresets = [
  { label: '20x30см', w: 20, h: 30 },
  { label: '30x40см', w: 30, h: 40 },
  { label: '40x60см', w: 40, h: 60 }
]

const options = reactive({
  num_colors: [],
  side: [],
  dist_derzh: []
})

const rawApiOptions = reactive({})

const calculatorData = reactive({
  w: 30,
  h: 40,
  num: 1,
  num_colors: null,
  side: null,
  dist_derzh: null,
  rakel: false
})

const applyPreset = ({ w, h }) => {
  calculatorData.w = w
  calculatorData.h = h
}

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : ['https://placehold.co/343x257'])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: 'Режим работы (наклейка)'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Режим работы (наклейка)')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: 'w', type: 1, value: calculatorData.w },
    { variable: 'h', type: 1, value: calculatorData.h },
    { variable: 'num_colors', type: 5, value: calculatorData.num_colors },
    { variable: 'side', type: 5, value: calculatorData.side },
    { variable: 'dist_derzh', type: 5, value: calculatorData.dist_derzh },
    { variable: 'rakel', type: 2, value: calculatorData.rakel ? 1 : 0 },
    { variable: 'num', type: 1, value: calculatorData.num }
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
        if (p.type === 1 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = parseInt(p.default) || calculatorData[p.variable]
        }
        if (p.type === 2 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = !!parseInt(p.default)
        }
        if (p.type === 5 && p.options && options[p.variable] !== undefined) {
          rawApiOptions[p.variable] = p.options
          const opts = p.options.map(o => ({ value: o, label: o }))
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
      { variable: 'w', type: 1, value: calculatorData.w },
      { variable: 'h', type: 1, value: calculatorData.h },
      { variable: 'num_colors', type: 5, value: getIndex('num_colors', calculatorData.num_colors) },
      { variable: 'side', type: 5, value: getIndex('side', calculatorData.side) },
      { variable: 'dist_derzh', type: 5, value: getIndex('dist_derzh', calculatorData.dist_derzh) },
      { variable: 'rakel', type: 2, value: calculatorData.rakel ? 1 : 0 },
      { variable: 'num', type: 1, value: calculatorData.num }
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
