<template>
  <div class="calculator">
    <h1 class="calculator__title">Баннер с люверсами</h1>

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
            <NumberInput v-model="calculatorData.w" label="Ширина, м" :min="0.3" :max="50" :step="0.1" />
            <NumberInput v-model="calculatorData.h" label="Высота, м" :min="0.3" :max="50" :step="0.1" />
          </div>
        </div>

        <div class="calculator__options">
          <FilterButtons
              v-if="options.banner"
              v-model="calculatorData.banner"
              label="Баннерная ткань"
              :options="options.banner"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.print"
              v-model="calculatorData.print"
              label="Печать"
              :options="options.print"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.luvers_space"
              v-model="calculatorData.luvers_space"
              label="Шаг люверсов"
              :options="options.luvers_space"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.zagib"
              v-model="calculatorData.zagib"
              label="Загиб"
              :options="options.zagib"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.added"
              v-model="calculatorData.added"
              label="Дополнительно"
              :options="options.added"
              :has-help="true"
          />
        </div>

        <div class="calculator__services">
          <div class="calculator__services-header">
            <span class="calculator__services-label">Параметры изготовления</span>
          </div>
          <div class="calculator__services-list">
            <ToggleSwitch
                v-model="calculatorData.need_prokleyka"
                label="Усиление края"
                help-title="Усиление края"
                help-description="Дополнительная проклейка края баннера для увеличения прочности."
            />
            <ToggleSwitch
                v-model="calculatorData.need_luvers"
                label="Люверсы"
                help-title="Люверсы"
                help-description="Металлические кольца по периметру баннера для крепления."
            />
          </div>
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
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'
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

const options = reactive({
  banner: [],
  print: [],
  luvers_space: [],
  zagib: [],
  added: []
})

const rawApiOptions = reactive({})
// Store the original API type for each variable (needed for type=4)
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
  added: null
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
    { variable: 'need_luvers', type: 2, value: calculatorData.need_luvers ? 1 : 0 },
    { variable: 'luvers_space', type: 5, value: calculatorData.luvers_space },
    { variable: 'zagib', type: 5, value: calculatorData.zagib },
    { variable: 'added', type: 5, value: calculatorData.added }
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
        // Store original API type
        apiParamTypes[p.variable] = p.type

        if (p.type === 1 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = parseFloat(p.default) || calculatorData[p.variable]
        }
        if (p.type === 2 && calculatorData[p.variable] !== undefined) {
          calculatorData[p.variable] = !!parseInt(p.default)
        }
        // type=4 (material) and type=5 (select) — both render as FilterButtons
        if ((p.type === 4 || p.type === 5) && p.options && options[p.variable] !== undefined) {
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
      { variable: 'banner', type: apiParamTypes.banner || 4, value: getIndex('banner', calculatorData.banner) },
      { variable: 'print', type: 5, value: getIndex('print', calculatorData.print) },
      { variable: 'need_prokleyka', type: 2, value: calculatorData.need_prokleyka ? 1 : 0 },
      { variable: 'need_luvers', type: 2, value: calculatorData.need_luvers ? 1 : 0 },
      { variable: 'luvers_space', type: 5, value: getIndex('luvers_space', calculatorData.luvers_space) },
      { variable: 'zagib', type: 5, value: getIndex('zagib', calculatorData.zagib) },
      { variable: 'added', type: 5, value: getIndex('added', calculatorData.added) }
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
