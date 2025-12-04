<template>
  <div class="calculator">
    <h1 class="calculator__title">Маленькие наклейки</h1>
    
    <div class="calculator__content">
      <!-- Левая колонка: Галерея -->
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages" />
        </div>

        <!-- Текстовый контент под галереей -->
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5">
          <p class="mb-2"><strong>Маленькие наклейки</strong> — это идеальное решение для брендирования, стикерпаков и рекламных материалов. Печать на самоклеящейся пленке с плоттерной резкой.</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Яркая цветная печать</li>
            <li>Плоттерная резка по контуру</li>
            <li>Различные виды пленок</li>
          </ul>
        </div>
      </div>

      <!-- Правая колонка: Параметры -->
      <div class="calculator__right">
        
        <!-- Секция 1: Размеры и количество -->
        <div class="calculator__params">
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.w" label="Ширина, мм" :min="10" :max="500" />
            <NumberInput v-model="calculatorData.h" label="Высота, мм" :min="10" :max="500" />
          </div>
          <NumberInput v-model="calculatorData.num" label="Количество, шт." :min="1" :max="10000" />
        </div>

        <!-- Секция 2: Параметры печати -->
        <div class="calculator__options">
            <FilterButtons
                v-if="options.plenka_color"
                v-model="calculatorData.plenka_color"
                label="Пленка для печати"
                :options="options.plenka_color"
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
                v-if="options.upakovka"
                v-model="calculatorData.upakovka"
                label="Итоговый вид"
                :options="options.upakovka"
                :has-help="true"
            />
        </div>

        <!-- Секция 3: Опции -->
        <div class="calculator__services">
            <div class="calculator__services-header">
              <span class="calculator__services-label">Параметры изготовления</span>
            </div>
            <div class="calculator__services-list">
                <ToggleSwitch v-model="calculatorData.need_plotter" label="Плоттерная резка" />
                <ToggleSwitch v-model="calculatorData.vivorka" label="Выборка" />
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
  initialImages: { type: Array, default: () => [] }
})

const calcId = 154
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

const options = reactive({
    plenka_color: [],
    print: [],
    upakovka: []
})

const calculatorData = reactive({
  w: 100,
  h: 100,
  num: 100,
  plenka_color: null,
  print: null,
  need_plotter: true,
  vivorka: false,
  upakovka: null,
  
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
    desc: `Наклейки ${calculatorData.w}x${calculatorData.h}мм, ${calculatorData.num}шт`
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
            if (p.type === 1 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = parseInt(p.default) || calculatorData[p.variable]
            }
            if (p.type === 2 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = !!parseInt(p.default)
            }
            if (p.type === 5 && p.options && options[p.variable] !== undefined) {
                const opts = p.options.map((o, idx) => ({ value: idx, label: o }))
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
    
    const params = [
        { variable: 'w', type: 1, value: calculatorData.w },
        { variable: 'h', type: 1, value: calculatorData.h },
        { variable: 'num', type: 1, value: calculatorData.num },
        { variable: 'plenka_color', type: 5, value: calculatorData.plenka_color || 0 },
        { variable: 'print', type: 5, value: calculatorData.print || 0 },
        { variable: 'need_plotter', type: 2, value: calculatorData.need_plotter ? 1 : 0 },
        { variable: 'vivorka', type: 2, value: calculatorData.vivorka ? 1 : 0 },
        { variable: 'upakovka', type: 5, value: calculatorData.upakovka || 0 },
    ]

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        params: params,
        mat_select_params: []
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

