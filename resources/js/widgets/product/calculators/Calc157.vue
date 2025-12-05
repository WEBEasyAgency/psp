<template>
  <div class="calculator">
    <h1 class="calculator__title">Акриловые вывески</h1>
    
    <div class="calculator__content">
      <!-- Левая колонка: Галерея -->
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>

        <!-- Текстовый контент под галереей -->
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5" v-html="props.description">
        </div>
      </div>

      <!-- Правая колонка: Параметры -->
      <div class="calculator__right">
        
        <!-- Секция 1: Размеры и количество -->
        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, м" :min="0.1" :max="10" :step="0.1" />
            <NumberInput v-model="calculatorData.h" label="Высота, м" :min="0.1" :max="10" :step="0.1" />
          </div>
          <NumberInput v-model="calculatorData.num" label="Количество" :min="1" :max="100" />
        </div>

        <!-- Секция 2: Опции (Селекты/Кнопки) -->
        <div class="calculator__options">
            <!-- Толщина акрила -->
            <FilterButtons
                v-if="options.th_acryl"
                v-model="calculatorData.th_acryl"
                label="Толщина акрила"
                :options="options.th_acryl"
                :has-help="true"
            />

            <!-- Цвет акрила -->
            <FilterButtons
                v-if="options.acryl_color"
                v-model="calculatorData.acryl_color"
                label="Цвет акрила"
                :options="options.acryl_color"
                :has-help="true"
            />

            <!-- Дистанционные держатели -->
            <FilterButtons
                v-if="options.dist_derzh"
                v-model="calculatorData.dist_derzh"
                label="Дистанционные держатели"
                :options="options.dist_derzh"
                :has-help="true"
            />
        </div>

        <!-- Секция 3: Опции (Чекбоксы) -->
        <div class="calculator__services">
            <div class="calculator__services-header">
              <span class="calculator__services-label">Параметры изготовления</span>
            </div>
            <div class="calculator__services-list">
                <ToggleSwitch v-model="calculatorData.tape" label="Двусторонний скотч" />
                <ToggleSwitch v-model="calculatorData.drills" label="Отверстия по углам" />
                <ToggleSwitch v-model="calculatorData.round" label="Скругление углов" />
            </div>
        </div>

        <!--
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
        -->

        <!-- Итого и Кнопки -->
        <CalculatorAction 
            :result="calculationResult" 
            :loading="isCalculating" 
            :order-link="orderLink" 
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

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: {type: String, default: ''}
})

const calcId = 157
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

// Опции для селектов, загружаемые с бека
const options = reactive({
    th_acryl: [],
    acryl_color: [],
    dist_derzh: []
})

// Raw API options to map indices back (because type 5 needs index)
const rawApiOptions = reactive({})

const calculatorData = reactive({
  w: 100,
  h: 50,
  num: 1,
  // Специфичные поля
  th_acryl: null,
  acryl_color: null,
  dist_derzh: null,
  tape: false,
  drills: false,
  round: false,
  
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
    desc: `Акриловая вывеска ${calculatorData.w}x${calculatorData.h}см`
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

    if (data.params) {
        data.params.forEach(p => {
            // Defaults
            if (p.type === 1 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = parseInt(p.default) || calculatorData[p.variable]
            }
            if (p.type === 2 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = !!parseInt(p.default)
            }
            
            // Options for Type 5
            if (p.type === 5 && p.options && options[p.variable] !== undefined) {
                rawApiOptions[p.variable] = p.options // Save for index lookup
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
    
    // Helper to get index for type 5
    const getIndex = (variable, value) => {
        const list = rawApiOptions[variable] || []
        const idx = list.indexOf(value)
        return idx === -1 ? 0 : idx
    }

    const params = [
        { variable: 'w', type: 1, value: calculatorData.w },
        { variable: 'h', type: 1, value: calculatorData.h },
        { variable: 'num', type: 1, value: calculatorData.num },
        
        // Type 5 sends Index
        { variable: 'th_acryl', type: 5, value: getIndex('th_acryl', calculatorData.th_acryl) },
        { variable: 'acryl_color', type: 5, value: getIndex('acryl_color', calculatorData.acryl_color) },
        { variable: 'dist_derzh', type: 5, value: getIndex('dist_derzh', calculatorData.dist_derzh) },

        // Type 2 sends 0/1
        { variable: 'tape', type: 2, value: calculatorData.tape ? 1 : 0 },
        { variable: 'drills', type: 2, value: calculatorData.drills ? 1 : 0 },
        { variable: 'round', type: 2, value: calculatorData.round ? 1 : 0 },
    ]

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        params: params,
        mat_select_params: [] // Empty for 157
      })
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

onMounted(fetchCalculatorParams)
</script>

