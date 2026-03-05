<template>
  <div class="calculator">
    <h1 class="calculator__title">Баннер на раме</h1>

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
              v-if="options.profil_color"
              v-model="calculatorData.profil_color"
              label="Цвет профиля"
              :options="options.profil_color"
              :has-help="true"
          />
          <FilterButtons
              v-if="options.metall_color"
              v-model="calculatorData.metall_color"
              label="Цвет металлокаркаса"
              :options="options.metall_color"
              :has-help="true"
          />
        </div>

        <div class="calculator__services">
          <div class="calculator__services-header">
            <span class="calculator__services-label">Параметры изготовления</span>
          </div>
          <div class="calculator__services-list">
            <ToggleSwitch
                v-model="calculatorData.need_profil"
                label="Обрамление профилем"
                help-title="Обрамление профилем"
                help-description="Алюминиевый профиль по периметру баннера для жёсткости конструкции."
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

const calcId = 163
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])

const options = reactive({
  banner: [],
  print: [],
  profil_color: [],
  metall_color: []
})

const rawApiOptions = reactive({})
const apiParamTypes = reactive({})
// Map cleaned variable names to original API variable names (for {} issue)
const apiVariableNames = reactive({})

const calculatorData = reactive({
  w: 1,
  h: 1,
  banner: null,
  print: null,
  need_profil: false,
  profil_color: null,
  metall_color: null
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : ['https://placehold.co/343x257'])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: 'Баннер на раме'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Баннер на раме')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: apiVariableNames.w || 'w', type: 1, value: calculatorData.w },
    { variable: apiVariableNames.h || 'h', type: 1, value: calculatorData.h },
    { variable: apiVariableNames.banner || 'banner', type: apiParamTypes.banner || 4, value: calculatorData.banner },
    { variable: apiVariableNames.print || 'print', type: 5, value: calculatorData.print },
    { variable: apiVariableNames.need_profil || '{need_profil}', type: 2, value: calculatorData.need_profil ? 1 : 0 },
    { variable: apiVariableNames.profil_color || '{profil_color}', type: 5, value: calculatorData.profil_color },
    { variable: apiVariableNames.metall_color || 'metall_color', type: 5, value: calculatorData.metall_color }
  ],
  mat_select_params: []
}))

// Strip curly braces from API variable names
const cleanVariable = (name) => name.replace(/[{}]/g, '')

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
        const cleanVar = cleanVariable(p.variable)
        // Store mapping: clean name → original API name
        apiVariableNames[cleanVar] = p.variable
        apiParamTypes[cleanVar] = p.type

        if (p.type === 1 && calculatorData[cleanVar] !== undefined) {
          calculatorData[cleanVar] = parseFloat(p.default) || calculatorData[cleanVar]
        }
        if (p.type === 2 && calculatorData[cleanVar] !== undefined) {
          calculatorData[cleanVar] = !!parseInt(p.default)
        }
        if ((p.type === 4 || p.type === 5) && p.options && options[cleanVar] !== undefined) {
          rawApiOptions[cleanVar] = p.options
          const opts = p.options.map(o => ({ value: o, label: o }))
          options[cleanVar] = opts
          if (opts.length > 0) calculatorData[cleanVar] = opts[0].value
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

    // Use original API variable names (with {} if present)
    const params = [
      { variable: apiVariableNames.w || 'w', type: 1, value: calculatorData.w },
      { variable: apiVariableNames.h || 'h', type: 1, value: calculatorData.h },
      { variable: apiVariableNames.banner || 'banner', type: apiParamTypes.banner || 4, value: getIndex('banner', calculatorData.banner) },
      { variable: apiVariableNames.print || 'print', type: 5, value: getIndex('print', calculatorData.print) },
      { variable: apiVariableNames.need_profil || '{need_profil}', type: 2, value: calculatorData.need_profil ? 1 : 0 },
      { variable: apiVariableNames.profil_color || '{profil_color}', type: 5, value: getIndex('profil_color', calculatorData.profil_color) },
      { variable: apiVariableNames.metall_color || 'metall_color', type: 5, value: getIndex('metall_color', calculatorData.metall_color) }
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
