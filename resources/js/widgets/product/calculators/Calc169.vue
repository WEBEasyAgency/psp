<template>
  <div class="calculator calculator--v2">
    <h1 class="calculator__title">Панель-кронштейн световой круглый</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" :max-visible="6" />
        </div>
        <div class="calculator__gallery-text" v-html="props.description"></div>
      </div>

      <div class="calculator__right">
        <div class="calculator__options">
          <FilterButtons
              v-if="options.d"
              v-model="calculatorData.d"
              label="Диаметр"
              :options="options.d"
          />
          <FilterButtons
              v-if="options.bok"
              v-model="calculatorData.bok"
              label="Боковая поверхность"
              :options="options.bok"
          />
          <FilterButtons
              v-if="options.color"
              v-model="calculatorData.color"
              label="Цвет кронштейна"
              :options="options.color"
          />
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
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: { type: String, default: '' }
})

const calcId = 169
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])

const options = reactive({
  d: [],
  bok: [],
  color: []
})

const rawApiOptions = reactive({})

const calculatorData = reactive({
  d: null,
  bok: null,
  color: null
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : ['https://placehold.co/343x257'])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: 'Панель-кронштейн световой круглый'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Панель-кронштейн световой круглый')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: 'd', type: 5, value: calculatorData.d },
    { variable: 'bok', type: 5, value: calculatorData.bok },
    { variable: 'color', type: 5, value: calculatorData.color }
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
      { variable: 'd', type: 5, value: getIndex('d', calculatorData.d) },
      { variable: 'bok', type: 5, value: getIndex('bok', calculatorData.bok) },
      { variable: 'color', type: 5, value: getIndex('color', calculatorData.color) }
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
