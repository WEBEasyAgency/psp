<template>
  <div class="calculator">
    <h1 class="calculator__title">Пластиковые буквы</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5" v-html="props.description"></div>
      </div>

      <div class="calculator__right">
        <div class="calculator__params">
          <TextInput
              v-model="textValue"
              label="Текст надписи"
              placeholder="Введите текст"
          />
          <NumberInput v-model="calculatorData.h" label="Высота букв, мм" :min="30" :max="2000" />
        </div>

        <div class="calculator__options">
          <div class="calculator__row">
            <FilterButtons
                v-if="options.thick"
                v-model="calculatorData.thick"
                label="Толщина пластика"
                :options="options.thick"
            />
            <FilterButtons
                v-if="options.color"
                v-model="calculatorData.color"
                label="Цвет лицевой поверхности букв"
                :options="options.color"
            />
          </div>
        </div>

        <div class="calculator__toggles">
          <ToggleSwitch
              v-model="scotchToggle"
              label="Двусторонний скотч"
          />
          <ToggleSwitch
              v-model="shablonToggle"
              label="Шаблон для монтажа"
          />
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
import { ref, reactive, computed, watch, onMounted } from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'
import TextInput from '@/shared/ui/TextInput.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import InfoTooltip from '@/shared/ui/InfoTooltip.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: { type: String, default: '' }
})

const calcId = 171
const { isEditMode, restoreParams } = useEditMode(calcId)
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)
const rawMatSelectParams = ref([])

const textValue = ref('')
const scotchToggle = ref(false)
const shablonToggle = ref(false)

const options = reactive({
  thick: [],
  color: [],
  scotch: [],
  shablon: []
})

const rawApiOptions = reactive({})

const calculatorData = reactive({
  num: 5,
  h: 50,
  thick: null,
  color: null,
  scotch: null,
  shablon: null
})

// When text changes, update num (character count)
watch(textValue, (val) => {
  const chars = val.replace(/\s/g, '').length
  if (chars > 0) {
    calculatorData.num = chars
  }
})

// Map toggle on/off to first/second option from API
watch(scotchToggle, (val) => {
  if (options.scotch.length >= 2) {
    calculatorData.scotch = val ? options.scotch[1].value : options.scotch[0].value
  }
})

watch(shablonToggle, (val) => {
  if (options.shablon.length >= 2) {
    calculatorData.shablon = val ? options.shablon[1].value : options.shablon[0].value
  }
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : ['https://placehold.co/343x257'])

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: 'Пластиковые буквы'
  })
  return `/order?${params.toString()}`
})

const cartItemDescription = computed(() => 'Пластиковые буквы')

const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0 ? props.initialImages[0] : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => ({
  params: [
    { variable: 'num', type: 1, value: calculatorData.num },
    { variable: 'h', type: 1, value: calculatorData.h },
    { variable: 'thick', type: 5, value: calculatorData.thick },
    { variable: 'color', type: 5, value: calculatorData.color },
    { variable: 'scotch', type: 5, value: calculatorData.scotch },
    { variable: 'shablon', type: 5, value: calculatorData.shablon }
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
      { variable: 'num', type: 1, value: calculatorData.num },
      { variable: 'h', type: 1, value: calculatorData.h },
      { variable: 'thick', type: 5, value: getIndex('thick', calculatorData.thick) },
      { variable: 'color', type: 5, value: getIndex('color', calculatorData.color) },
      { variable: 'scotch', type: 5, value: getIndex('scotch', calculatorData.scotch) },
      { variable: 'shablon', type: 5, value: getIndex('shablon', calculatorData.shablon) }
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

.calculator__row {
  @apply flex flex-col gap-6;
}

@media (min-width: 1280px) {
  .calculator__row {
    @apply flex-row gap-6 items-end;
  }
}

.calculator__toggles {
  @apply flex flex-col gap-4;
}
</style>
