<template>
  <div class="calculator">
    <h1 class="calculator__title">Режим работы из акрила Премиум</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5" v-html="props.description"></div>
      </div>

      <div class="calculator__right">
        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, см" :min="5" :max="200" />
            <NumberInput v-model="calculatorData.h" label="Высота, см" :min="5" :max="200" />
          </div>
        </div>

        <div class="calculator__options">
          <FilterButtons
              v-if="options.thick"
              v-model="calculatorData.thick"
              label="Толщина акрила"
              :options="options.thick"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.acryl_color"
              v-model="calculatorData.acryl_color"
              label="Цвет акрила"
              :options="options.acryl_color"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.num_colors"
              v-model="calculatorData.num_colors"
              label="Кол-во цветов пленки"
              :options="options.num_colors"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.dist_derzh"
              v-model="calculatorData.dist_derzh"
              label="Дистанционные держатели"
              :options="options.dist_derzh"
              :has-help="true"
          />
        </div>

        <div class="calculator__services">
          <div class="calculator__services-header">
            <span class="calculator__services-label">Параметры изготовления</span>
          </div>
          <div class="calculator__services-list">
            <ToggleSwitch
                v-model="calculatorData.round"
                label="Скругление углов"
                help-title="Скругление углов"
                help-description="Скругление острых углов акрила радиусом 10мм."
            />
          </div>
        </div>

        <NumberInput v-model="calculatorData.num" label="Количество" :min="1" :max="100" />
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
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import InfoTooltip from '@/shared/ui/InfoTooltip.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: { type: String, default: '' }
})

const calcId = 172
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])

const options = reactive({
  thick: [],
  acryl_color: [],
  num_colors: [],
  dist_derzh: []
})

const rawApiOptions = reactive({})

const calculatorData = reactive({
  w: 20,
  h: 30,
  num: 1,
  thick: null,
  acryl_color: null,
  num_colors: null,
  round: true,
  dist_derzh: null
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : ['https://placehold.co/343x257'])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: 'Режим работы из акрила Премиум'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Режим работы из акрила Премиум')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: 'w', type: 1, value: calculatorData.w },
    { variable: 'h', type: 1, value: calculatorData.h },
    { variable: 'thick', type: 5, value: calculatorData.thick },
    { variable: 'acryl_color', type: 5, value: calculatorData.acryl_color },
    { variable: 'num_colors', type: 5, value: calculatorData.num_colors },
    { variable: 'round', type: 2, value: calculatorData.round ? 1 : 0 },
    { variable: 'dist_derzh', type: 5, value: calculatorData.dist_derzh },
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
      { variable: 'thick', type: 5, value: getIndex('thick', calculatorData.thick) },
      { variable: 'acryl_color', type: 5, value: getIndex('acryl_color', calculatorData.acryl_color) },
      { variable: 'num_colors', type: 5, value: getIndex('num_colors', calculatorData.num_colors) },
      { variable: 'round', type: 2, value: calculatorData.round ? 1 : 0 },
      { variable: 'dist_derzh', type: 5, value: getIndex('dist_derzh', calculatorData.dist_derzh) },
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
