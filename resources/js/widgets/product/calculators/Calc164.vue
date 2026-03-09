<template>
  <div class="calculator">
    <h1 class="calculator__title">Плакат на постерной бумаге</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5" v-html="props.description"></div>
      </div>

      <div class="calculator__right">
        <SizePresets
            v-model="activePreset"
            :presets="sizePresets"
            @select="applyPreset"
        />

        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, мм" :min="100" :max="5000" @update:modelValue="activePreset = null" />
            <NumberInput v-model="calculatorData.h" label="Высота, мм" :min="100" :max="5000" @update:modelValue="activePreset = null" />
          </div>
        </div>

        <div class="calculator__options">
          <FilterButtons
              v-if="options.finish"
              v-model="calculatorData.finish"
              label="Подрезка"
              :options="options.finish"
          />
        </div>

        <div class="calculator__quantity">
          <NumberInput v-model="calculatorData.num" label="Количество, шт." :min="1" :max="1000" />
          <span class="calculator__hint">Чем больше, тем дешевле</span>
        </div>

        <InfoTooltip />
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
import SizePresets from '@/shared/ui/SizePresets.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import InfoTooltip from '@/shared/ui/InfoTooltip.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: { type: String, default: '' }
})

const calcId = 164
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])
const activePreset = ref(null)

const sizePresets = [
  { label: 'A4 (297x420)', w: 297, h: 420 },
  { label: 'A3 (420x297)', w: 420, h: 297 },
  { label: '500x700', w: 500, h: 700 },
  { label: '700x1000', w: 700, h: 1000 }
]

const options = reactive({
  finish: []
})

const rawApiOptions = reactive({})

const calculatorData = reactive({
  w: 297,
  h: 420,
  num: 1,
  finish: null
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
    desc: 'Плакат на постерной бумаге'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Плакат на постерной бумаге')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: 'w', type: 1, value: calculatorData.w },
    { variable: 'h', type: 1, value: calculatorData.h },
    { variable: 'finish', type: 5, value: calculatorData.finish },
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
      { variable: 'finish', type: 5, value: getIndex('finish', calculatorData.finish) },
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

.calculator__hint {
  @apply text-slate-400 text-sm mt-1;
}
</style>
