<template>
  <div class="calculator">
    <h1 class="calculator__title">Объемные буквы со световым бортом</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages"/>
        </div>

        <!-- Текстовый контент под галереей -->
        <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5">
          <p class="mb-2"><strong>Объемные буквы со световым бортом</strong> создают эффектный ореол свечения, делая вывеску визуально легкой и привлекательной. Светится не только лицевая часть, но и боковые грани.</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Максимальная яркость и заметность</li>
            <li>Полностью акриловый корпус</li>
            <li>Премиальный внешний вид</li>
          </ul>
        </div>
      </div>

      <div class="calculator__right">

        <!-- 1. Текст и Размер -->
        <div class="calculator__params">
          <div class="calculator__text-section inline-flex">
            <div class="calculator__text-input">
              <div class="field">
                <label class="field__label">Введите надпись</label>
                <div class="field__input">
                  <input v-model="calculatorData.text" type="text" placeholder="Кафе" class="input"/>
                </div>
              </div>
            </div>
            <div class="calculator__or">или</div>
            <NumberInput v-model="calculatorData.num" label="Количество букв" :min="1" :max="50"/>
          </div>
          <NumberInput v-model="calculatorData.sr_h" label="Высота букв, см" :min="5" :max="200" :step="5"/>
        </div>

        <!-- 2. Поверхности -->
        <div class="calculator__options">
          <div class="calculator__surfaces-row">
            <div class="calculator__surface-col">
              <FilterButtons
                  v-if="options.face"
                  v-model="calculatorData.face"
                  label="Лицевая поверхность"
                  :options="options.face"
                  :has-help="true"
              />
            </div>
            <div class="calculator__surface-col">
              <FilterButtons
                  v-if="options.bort"
                  v-model="calculatorData.bort"
                  label="Боковая поверхность"
                  :options="options.bort"
                  :has-help="true"
              />
            </div>
          </div>
          <!-- 4. Подсветка -->
          <div class="xl:pr-4">
            <FilterButtons
                v-if="options.brand"
                v-model="calculatorData.brand"
                label="Подсветка"
                :options="options.brand"
                :has-help="true"
                :full-width-buttons="true"
            />
          </div>
          <!-- 3. Основа -->
          <RadioGroup
              v-if="options.ustanovka"
              v-model="calculatorData.ustanovka"
              label="Основа для букв"
              :options="options.ustanovka"
              :has-help="true"
          >
            <template #details="{ option, isActive }">
              <div v-if="isActive && option.value === 'Рама'" class="calculator__sub-options">
                <NumberInput v-model="calculatorData.rama_w" label="Ширина рамы, м" :min="0" :max="5" :step="0.1"/>
                <FilterButtons
                    v-if="options.color_rama"
                    v-model="calculatorData.color_rama"
                    label="Цвет рамы"
                    :options="options.color_rama"
                    :has-help="true"
                />
              </div>
              <div v-if="isActive && option.value === 'Подложка из композита'" class="calculator__sub-options">
                <NumberInput v-model="calculatorData.wp" label="Ширина подложки, м" :min="0" :max="5" :step="0.1"/>
                <NumberInput v-model="calculatorData.hp" label="Высота подложки, м" :min="0" :max="5" :step="0.1"/>
                <FilterButtons
                    v-if="options.colorp"
                    v-model="calculatorData.colorp"
                    label="Цвет подложки"
                    :options="options.colorp"
                    :has-help="true"
                />
              </div>
            </template>
          </RadioGroup>


        </div>


        <!-- Итого -->
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
import {ref, reactive, computed, onMounted} from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import RadioGroup from '@/shared/ui/RadioGroup.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'
import CalculatorAction from './components/CalculatorAction.vue'

const props = defineProps({
  initialImages: {type: Array, default: () => []}
})

const calcId = 155
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

const options = reactive({
  face: [],
  bort: [],
  brand: [],
  ustanovka: [],
  color_rama: [],
  colorp: []
})
const rawApiOptions = reactive({})

const calculatorData = reactive({
  text: '',
  sr_h: 30,
  num: null,
  face: null,
  bort: null,
  brand: null,
  ustanovka: null,
  rama_w: null,
  color_rama: null,
  wp: null,
  hp: null,
  colorp: null,
  services: {
    design: false,
    installation: false,
    delivery: false
  }
})

const galleryImages = ref(props.initialImages.length > 0 ? props.initialImages : [
  'https://placehold.co/343x257'
])

const actualLetterCount = computed(() => {
  if (calculatorData.text && calculatorData.text.trim().length > 0) {
    return calculatorData.text.trim().length
  }
  return calculatorData.num || 1
})

const getLetterWord = (count) => {
  const cases = [2, 0, 1, 1, 1, 2]
  const titles = ['буква', 'буквы', 'букв']
  return titles[(count % 100 > 4 && count % 100 < 20) ? 2 : cases[Math.min(count % 10, 5)]]
}

const orderLink = computed(() => {
  if (!calculationResult.value) return '#'
  const params = new URLSearchParams({
    calc_position_id: calculationResult.value.calc_position_id,
    price: calculationResult.value.price_good,
    desc: `Объемные буквы (${actualLetterCount.value} ${getLetterWord(actualLetterCount.value)}, высота ${calculatorData.sr_h}см)`
  })
  return `/order?${params.toString()}`
})

const fetchCalculatorParams = async () => {
  try {
    error.value = ''
    const response = await fetch(`/backend/api/calc/${calcId}/params`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}
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
                        let opts;
                        if (p.variable === 'brand') {
                            opts = p.options.map(o => {
                                let label = o;
                                if (o === 'ECO') label = 'ECO (гарантия 1 год)';
                                else if (o === 'NORM') label = 'NORM (гарантия 3 года)';
                                else if (o === 'PREMIUM') label = 'PREMIUM (гарантия 5 лет)';
                                return { value: o, label: label };
                            });
                        } else {
                            opts = p.options.map(o => ({ value: o, label: o }));
                        }
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
      {variable: 'sr_h', type: 1, value: calculatorData.sr_h},
      {variable: 'num', type: 1, value: actualLetterCount.value},
      {variable: 'rama_w', type: 1, value: calculatorData.rama_w},
      {variable: 'wp', type: 1, value: calculatorData.wp},
      {variable: 'hp', type: 1, value: calculatorData.hp},

      {variable: 'face', type: 5, value: getIndex('face', calculatorData.face)},
      {variable: 'bort', type: 5, value: getIndex('bort', calculatorData.bort)},
      {variable: 'brand', type: 5, value: getIndex('brand', calculatorData.brand)},
      {variable: 'ustanovka', type: 5, value: getIndex('ustanovka', calculatorData.ustanovka)},
      {variable: 'color_rama', type: 5, value: getIndex('color_rama', calculatorData.color_rama)},
      {variable: 'colorp', type: 5, value: getIndex('colorp', calculatorData.colorp)},
    ]

    const response = await fetch(`/backend/api/calc/${calcId}/run`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
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

