<template>
  <div class="calculator">
    <h1 class="calculator__title">Стенд из пластика с карманами</h1>
    
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
            <NumberInput v-model="calculatorData.w" label="Ширина, см" :min="10" :max="1000" />
            <NumberInput v-model="calculatorData.h" label="Высота, см" :min="10" :max="1000" />
          </div>
          <NumberInput v-model="calculatorData.num" label="Количество" :min="1" :max="100" />
        </div>

        <!-- Секция 2: Материал (Толщина) - ТУТ МЕНЯТЬ ПОРЯДОК ЕСЛИ НУЖНО -->
        <div class="calculator__options">
            <!-- Используем FilterButtons вместо RadioGroup по запросу -->
            <FilterButtons
                v-if="options.mat_select_in3"
                v-model="calculatorData.mat_select_in3"
                label="Толщина пластика"
                :options="options.mat_select_in3"
                :has-help="true"
            />

            <!-- Сюда можно вставить любой текст -->
            <!-- <p class="text-sm text-gray-500">Примечание по материалам...</p> -->
        </div>

        <!-- Секция 3: Карманы -->
        <div class="calculator__params">
          <div class="calculator__services-header">
            <span class="calculator__services-label">Плоские карманы</span>
            <HelpPopover
                title="Плоские карманы"
                description="Это прозрачные карманы из ПВХ для вставки стандартных листов бумаги (А3, А4, А5, А6). Крепятся к лицевой стороне стенда."
            />
          </div>
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.a4" label="Карман А4" :min="0" :max="50" />
            <NumberInput v-model="calculatorData.a3" label="Карман А3" :min="0" :max="50" />
          </div>
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.a5" label="Карман А5" :min="0" :max="50" />
            <NumberInput v-model="calculatorData.a6" label="Карман А6" :min="0" :max="50" />
          </div>

          <div class="calculator__services-header">
            <span class="calculator__services-label">Объемные карманы</span>
            <HelpPopover
                title="Объемные карманы"
                description="Это карманы из прозрачного оргстекла или ПЭТ для размещения пачек листовок, визиток или буклетов. Имеют глубину."
            />
          </div>
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.a4o" label="Карман А4" :min="0" :max="50" />
            <NumberInput v-model="calculatorData.a5o" label="Карман А5" :min="0" :max="50" />
          </div>
          <div class="calculator__dims-row">
            <NumberInput v-model="calculatorData.aEo" label="Карман Евро" :min="0" :max="50" />
            <NumberInput v-model="calculatorData.aVo" label="Карман для визиток" :min="0" :max="50" />
          </div>
        </div>

        <!-- Секция 4: Опции (Чекбоксы) -->
        <div class="calculator__services">
            <div class="calculator__services-header">
              <span class="calculator__services-label">Параметры изготовления</span>
            </div>
            <div class="calculator__services-list">
                <ToggleSwitch v-model="calculatorData.need_Nielsen" label="Профиль Nielsen по периметру" />
                <ToggleSwitch v-model="calculatorData.perekid" label="Перекидная система" />
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
import HelpPopover from '@/shared/ui/HelpPopover.vue'

const props = defineProps({
  initialImages: { type: Array, default: () => [] },
  description: {type: String, default: ''}
})

const calcId = 151 // Жестко задаем ID
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

// Опции для селектов/кнопок, загружаемые с бека
const options = reactive({
    mat_select_in3: [] // Материалы
})

// Полные данные mat_select_params из API (для отправки обратно)
const rawMatSelectParams = ref([])

// Основные данные калькулятора
const calculatorData = reactive({
  w: 100,
  h: 50,
  num: 1,
  // Карманы плоские
  a4: 0,
  a3: 0,
  a5: 0,
  a6: 0,
  // Карманы объемные
  a4o: 0,
  a5o: 0,
  aEo: 0,
  aVo: 0,
  // Опции
  mat_select_in3: null, // ID выбранного материала
  need_Nielsen: false,
  perekid: false,

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
    desc: `Стенд с карманами ${calculatorData.w}x${calculatorData.h}см`
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

    // Маппинг полученных данных на наши поля
    // 1. Обычные параметры (w, h, num, tape...) - тут мы просто берем дефолты если надо
    if (data.params) {
        data.params.forEach(p => {
            if (p.type === 1 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = parseInt(p.default) || calculatorData[p.variable]
            }
            if (p.type === 2 && calculatorData[p.variable] !== undefined) {
                calculatorData[p.variable] = !!parseInt(p.default)
            }
        })
    }

    // 2. Материалы (mat_select_params)
    // В 156 есть mat_select_in3 (ПВХ пластик)
    if (data.mat_select_params) {
        // Сохраняем полные данные для отправки обратно в API
        rawMatSelectParams.value = data.mat_select_params

        data.mat_select_params.forEach(p => {
            // Собираем опции для кнопок: { label: '3мм', value: 101 }
            // Имя материала часто длинное "ПВХ пластик 3мм", нам может быть нужно сократить или оставить как есть
            const opts = p.materials.map(m => ({ value: m.id, label: m.name }))
            options[p.variable] = opts
            
            // Дефолт
            if (opts.length > 0) {
                calculatorData[p.variable] = opts[0].value
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
    
    // Собираем params
    const params = [
        { variable: 'w', type: 1, value: calculatorData.w },
        { variable: 'h', type: 1, value: calculatorData.h },
        { variable: 'num', type: 1, value: calculatorData.num },
        // Карманы плоские
        { variable: 'a4', type: 1, value: calculatorData.a4 },
        { variable: 'a3', type: 1, value: calculatorData.a3 },
        { variable: 'a5', type: 1, value: calculatorData.a5 },
        { variable: 'a6', type: 1, value: calculatorData.a6 },
        // Карманы объемные
        { variable: 'a4o', type: 1, value: calculatorData.a4o },
        { variable: 'a5o', type: 1, value: calculatorData.a5o },
        { variable: 'aEo', type: 1, value: calculatorData.aEo },
        { variable: 'aVo', type: 1, value: calculatorData.aVo },
        // Опции
        { variable: 'need_Nielsen', type: 2, value: calculatorData.need_Nielsen ? 1 : 0 },
        { variable: 'perekid', type: 2, value: calculatorData.perekid ? 1 : 0 },
    ]

    // Добавляем материал в params с type=3
    if (calculatorData.mat_select_in3) {
        params.push({ variable: 'mat_select_in3', type: 3, value: calculatorData.mat_select_in3 })
    }

    // mat_select_params с обновлённым id на ID выбранного материала
    const matParams = rawMatSelectParams.value.map(param => {
        const selectedMaterialId = calculatorData[param.variable]
        const selectedMaterial = param.materials.find(m => m.id === selectedMaterialId)

        return {
            ...param, // Весь объект как пришёл из API
            id: selectedMaterial ? selectedMaterial.id : param.id, // ID выбранного материала
            name: selectedMaterial ? selectedMaterial.name : param.name, // Название выбранного материала
            materials: selectedMaterial ? [selectedMaterial] : param.materials // Только выбранный материал
        }
    })

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        params: params,
        mat_select_params: matParams
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

