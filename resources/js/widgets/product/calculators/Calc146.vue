<template>
  <div class="calculator">
    <h1 class="calculator__title">Объемные буквы с бортом из алюминия</h1>

    <div class="calculator__content">
      <div class="calculator__left">
        <div class="calculator__gallery">
          <ImageGallery :images="galleryImages"/>
        </div>

        <!-- Текстовый контент под галереей -->
        <div class="calculator__gallery-text" v-html="props.description">
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
            :calculator-id="calcId"
            :calculator-data="calculatorDataForCart"
            :description="cartItemDescription"
            :image="cartItemImage"
            @calculate="calculatePrice"
            @loadEditData="loadEditData"
        />
        <div v-if="error" class="calculator__error">{{ error }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, reactive, computed, onMounted, watch} from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import RadioGroup from '@/shared/ui/RadioGroup.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
  initialImages: {type: Array, default: () => []},
  description: {type: String, default: ''}
})

const calcId = 146
const { isEditMode, restoreParams } = useEditMode(calcId)
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
const rawMatSelectParams = ref([])

const calculatorData = reactive({
  text: '',
  sr_h: 30,
  num: 4,
  face: null,
  bort: null,
  brand: null,
  ustanovka: null,
  rama_w: 1.0,
  color_rama: null,
  wp: 1.0,
  hp: 1.0,
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
    // Убираем все пробелы перед подсчетом
    return calculatorData.text.replace(/\s/g, '').length
  }
  return calculatorData.num || 1
})

// Watcher to automatically update num when text changes
watch(() => calculatorData.text, (newText) => {
  if (newText && newText.trim().length > 0) {
    calculatorData.num = newText.replace(/\s/g, '').length
  }
});

// Watcher to reset sub-parameters when 'ustanovka' changes
watch(() => calculatorData.ustanovka, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    // Reset all sub-parameters to their default states
    calculatorData.rama_w = 1.0;
    calculatorData.color_rama = null;
    calculatorData.wp = 1.0;
    calculatorData.hp = 1.0;
    calculatorData.colorp = null;
    // Note: The actual display of these sub-parameters is handled by v-if in the template
    // and their values will be re-initialized by the components if they become active again.
  }
});

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
    desc: `Объемные буквы с бортом из алюминия`
  })
  return `/order?${params.toString()}`
})

// Описание товара для корзины
const cartItemDescription = computed(() => {
  return `Объемные буквы с бортом из алюминия`
})

// Изображение товара для корзины (первое из массива)
const cartItemImage = computed(() => {
  return props.initialImages && props.initialImages.length > 0
    ? props.initialImages[0]
    : '/img/dest/cart-img.jpg'
})

// Данные калькулятора для сохранения в корзину
const calculatorDataForCart = computed(() => {
  const params = [
    {variable: 'sr_h', type: 1, value: calculatorData.sr_h},
    {variable: 'num', type: 1, value: actualLetterCount.value},
    {variable: 'face', type: 5, value: calculatorData.face},
    {variable: 'bort', type: 5, value: calculatorData.bort},
    {variable: 'brand', type: 5, value: calculatorData.brand},
    {variable: 'ustanovka', type: 5, value: calculatorData.ustanovka},
  ];

  if (calculatorData.ustanovka === 'Рама') {
    params.push(
      {variable: 'rama_w', type: 1, value: calculatorData.rama_w},
      {variable: 'color_rama', type: 5, value: calculatorData.color_rama}
    );
  } else if (calculatorData.ustanovka === 'Подложка из композита') {
    params.push(
      {variable: 'wp', type: 1, value: calculatorData.wp},
      {variable: 'hp', type: 1, value: calculatorData.hp},
      {variable: 'colorp', type: 5, value: calculatorData.colorp}
    );
  }

  return {
    params: params,
    mat_select_params: [],
    text: calculatorData.text
  };
});

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
          // Не перезаписываем num из API, используем начальное значение 4
          if (p.variable === 'num') return
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

// Загрузка данных для редактирования из корзины
const loadEditData = async (editData) => {
  console.log('Loading edit data:', editData)

  // Ждем загрузки параметров из API
  await fetchCalculatorParams()

  // Применяем параметры из корзины
  if (editData.params) {
    editData.params.forEach(param => {
      if (param.variable === 'text' && param.value) {
        calculatorData.text = param.value
      } else if (param.type === 1 && calculatorData[param.variable] !== undefined) {
        // Числовые параметры
        calculatorData[param.variable] = param.value
      } else if (param.type === 5 && calculatorData[param.variable] !== undefined) {
        // Select параметры
        calculatorData[param.variable] = param.value
      }
    })
  }

  // Автоматически пересчитываем цену
  await calculatePrice()
}

onMounted(async () => {
    await fetchCalculatorParams()
    if (isEditMode.value) {
        restoreParams(calculatorData, rawMatSelectParams)
    }
})
</script>

