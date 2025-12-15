<template>
    <div class="calculator">
        <h1 class="calculator__title">Плоские вывески из алюминиевого композита</h1>

        <div class="calculator__content">
            <!-- Левая колонка: Галерея -->
            <div class="calculator__left">
                <div class="calculator__gallery">
                    <ImageGallery :images="galleryImages"/>
                </div>

                <!-- Текстовый контент под галереей -->
                <div class="calculator__gallery-text text-slate-500 text-sm font-normal font-['Inter'] leading-5"
                     v-html="props.description">
                </div>
            </div>

            <!-- Правая колонка: Параметры -->
            <div class="calculator__right">

                <!-- Секция 1: Размеры и количество -->
                <div class="calculator__params">
                    <div class="calculator__dims-row">
                        <NumberInput v-model="calculatorData.w" label="Ширина, м" :min="0.1" :max="10" :step="0.1"/>
                        <NumberInput v-model="calculatorData.h" label="Высота, м" :min="0.1" :max="10" :step="0.1"/>
                    </div>

                </div>

                <!-- Секция 2: Материал (Толщина) - ТУТ МЕНЯТЬ ПОРЯДОК ЕСЛИ НУЖНО -->
                <div class="calculator__options">
                    <FilterButtons
                        v-if="options.komp_color"
                        v-model="calculatorData.komp_color"
                        label="Цвет композита"
                        :options="options.komp_color"
                        :has-help="true"
                    />
                    <FilterButtons
                        v-if="options.dist_derzh"
                        v-model="calculatorData.dist_derzh"
                        label="Дистанционные держатели"
                        :options="options.dist_derzh"
                        :has-help="true"
                    />

                    <!-- Сюда можно вставить любой текст -->
                    <!-- <p class="text-sm text-gray-500">Примечание по материалам...</p> -->
                </div>

                <!-- Секция 3: Опции (Чекбоксы) -->
                <div class="calculator__services">
                    <div class="calculator__services-header">
                        <span class="calculator__services-label">Параметры изготовления</span>
                    </div>
                    <div class="calculator__services-list">
                        <ToggleSwitch v-model="calculatorData.doubleside" label="Изображение с двух сторон"/>
                        <ToggleSwitch v-model="calculatorData.drills" label="Отверстия по углам"/>
                        <ToggleSwitch v-model="calculatorData.tape" label="Двусторонний скотч"/>
                        <ToggleSwitch v-model="calculatorData.round" label="Скругление углов"/>
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
                <NumberInput v-model="calculatorData.num" label="Количество" :min="1" :max="100"/>
                <InfoTooltip />
                <!-- Итого и Кнопки -->
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
import {ref, reactive, computed, onMounted} from 'vue'
import ImageGallery from '@/shared/ui/ImageGallery.vue'
import NumberInput from '@/shared/ui/NumberInput.vue'
import FilterButtons from '@/shared/ui/FilterButtons.vue'
import ToggleSwitch from '@/shared/ui/ToggleSwitch.vue'
import CalculatorAction from './components/CalculatorAction.vue'
import InfoTooltip from '@/shared/ui/InfoTooltip.vue'

const props = defineProps({
    initialImages: {type: Array, default: () => []},
    description: {type: String, default: ''}
})

const calcId = 158
const isCalculating = ref(false)
const error = ref('')
const calculationResult = ref(null)

const options = reactive({
    komp_color: [],
    dist_derzh: []
})

// Полные данные mat_select_params из API (для отправки обратно)
const rawMatSelectParams = ref([])

const calculatorData = reactive({
    w: 1,
    h: 1,
    num: 1,
    komp_color: null,
    doubleside: false,
    drills: false,
    tape: false,
    round: false,
    dist_derzh: null,

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
        desc: `Вывеска из композита ${calculatorData.w}x${calculatorData.h}м`
    })
    return `/order?${params.toString()}`
})

// Данные для корзины
const cartItemDescription = computed(() => {
    return `Вывеска из композита ${calculatorData.w}x${calculatorData.h}м (${calculatorData.num} шт)`
})

const cartItemImage = computed(() => {
    return props.initialImages && props.initialImages.length > 0
        ? props.initialImages[0]
        : '/img/dest/cart-img.jpg'
})

const calculatorDataForCart = computed(() => {
    return {
        params: [
            {variable: 'w', type: 1, value: calculatorData.w},
            {variable: 'h', type: 1, value: calculatorData.h},
            {variable: 'num', type: 1, value: calculatorData.num},
            {variable: 'komp_color', type: 5, value: calculatorData.komp_color || 0},
            {variable: 'doubleside', type: 2, value: calculatorData.doubleside ? 1 : 0},
            {variable: 'drills', type: 2, value: calculatorData.drills ? 1 : 0},
            {variable: 'tape', type: 2, value: calculatorData.tape ? 1 : 0},
            {variable: 'round', type: 2, value: calculatorData.round ? 1 : 0},
            {variable: 'dist_derzh', type: 5, value: calculatorData.dist_derzh || 0}
        ],
        mat_select_params: []
    }
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
                    calculatorData[p.variable] = parseFloat(p.default) || calculatorData[p.variable]
                }
                if (p.type === 2 && calculatorData[p.variable] !== undefined) {
                    calculatorData[p.variable] = !!parseInt(p.default)
                }
                if (p.type === 5 && p.options && options[p.variable] !== undefined) {
                    const opts = p.options.map((o, idx) => ({value: idx, label: o}))
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
            {variable: 'w', type: 1, value: calculatorData.w},
            {variable: 'h', type: 1, value: calculatorData.h},
            {variable: 'num', type: 1, value: calculatorData.num},
            {variable: 'komp_color', type: 5, value: calculatorData.komp_color || 0},
            {variable: 'doubleside', type: 2, value: calculatorData.doubleside ? 1 : 0},
            {variable: 'drills', type: 2, value: calculatorData.drills ? 1 : 0},
            {variable: 'tape', type: 2, value: calculatorData.tape ? 1 : 0},
            {variable: 'round', type: 2, value: calculatorData.round ? 1 : 0},
            {variable: 'dist_derzh', type: 5, value: calculatorData.dist_derzh || 0},
        ]

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
            headers: {'Content-Type': 'application/json'},
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

