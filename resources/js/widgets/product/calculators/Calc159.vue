<template>
    <div class="calculator">
        <h1 class="calculator__title">Пластиковые таблички</h1>

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
                        <NumberInput v-model="calculatorData.w" label="Ширина, см" :min="5" :max="200"/>
                        <NumberInput v-model="calculatorData.h" label="Высота, см" :min="5" :max="200"/>
                    </div>

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

                <!-- Секция 3: Опции (Чекбоксы) -->
                <div class="calculator__services">
                    <div class="calculator__services-header">
                        <span class="calculator__services-label">Параметры изготовления</span>
                        <HelpPopover
                            title="Параметры изготовления"
                            description="Выберите дополнительные опции для вашей пластиковой таблички: двусторонняя печать, скругление углов, скотч для крепления или обрамление профилем Нильсен."
                        />
                    </div>
                    <div class="calculator__services-list">
                        <ToggleSwitch
                            v-model="calculatorData.doubleside"
                            label="Изображение с двух сторон"
                            help-title="Изображение с двух сторон"
                            help-description="Полноцветная УФ-печать с обеих сторон пластиковой таблички. Подходит для подвесных конструкций."
                        />
                        <ToggleSwitch
                            v-model="calculatorData.round"
                            label="Скругление углов"
                            help-title="Скругление углов"
                            help-description="Скругление острых углов пластика радиусом 10мм. Придает законченный вид и повышает безопасность."
                        />
                        <ToggleSwitch
                            v-model="calculatorData.tape"
                            label="Двусторонний скотч"
                            help-title="Двусторонний скотч"
                            help-description="Прочный двусторонний скотч 3М для крепления таблички к стене. Подходит для гладких поверхностей."
                        />
                        <ToggleSwitch
                            v-model="calculatorData.need_Nielsen"
                            label="Обрамление профилем Нильсен по периметру"
                            help-title="Обрамление профилем Нильсен"
                            help-description="Алюминиевая рамка Nielsen по периметру таблички. Придает презентабельный вид и защищает края."
                        />
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
import HelpPopover from '@/shared/ui/HelpPopover.vue'
import { useEditMode } from '@/shared/composables/useEditMode'

const props = defineProps({
    initialImages: {type: Array, default: () => []},
    description: {type: String, default: ''}
})

const calcId = 159 // Жестко задаем ID
const { isEditMode, restoreParams } = useEditMode(calcId)
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
    mat_select_in3: null, // ID выбранного материала
    doubleside: false,
    round: false,
    tape: false,
    need_Nielsen: false,

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
        desc: `Пластиковая табличка ${calculatorData.w}x${calculatorData.h}см`
    })
    return `/order?${params.toString()}`
})

// Данные для корзины
const cartItemDescription = computed(() => {
    return `Пластиковая табличка ${calculatorData.w}x${calculatorData.h}см (${calculatorData.num} шт)`
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
            {variable: 'mat_select_in3', type: 3, value: calculatorData.mat_select_in3},
            {variable: 'doubleside', type: 2, value: calculatorData.doubleside ? 1 : 0},
            {variable: 'round', type: 2, value: calculatorData.round ? 1 : 0},
            {variable: 'tape', type: 2, value: calculatorData.tape ? 1 : 0},
            {variable: 'need_Nielsen', type: 2, value: calculatorData.need_Nielsen ? 1 : 0}
        ],
        mat_select_params: rawMatSelectParams.value.map(param => {
            const selectedMaterialId = calculatorData[param.variable]
            const selectedMaterial = param.materials.find(m => m.id === selectedMaterialId)
            return {
                ...param,
                id: selectedMaterial ? selectedMaterial.id : param.id,
                name: selectedMaterial ? selectedMaterial.name : param.name,
                materials: selectedMaterial ? [selectedMaterial] : param.materials
            }
        })
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
        // В 159 есть mat_select_in3 (ПВХ пластик)
        if (data.mat_select_params) {
            // Сохраняем полные данные для отправки обратно в API
            rawMatSelectParams.value = data.mat_select_params

            data.mat_select_params.forEach(p => {
                // Собираем опции для кнопок: { label: '3мм', value: 101 }
                // Имя материала часто длинное "ПВХ пластик 3мм", нам может быть нужно сократить или оставить как есть
                const opts = p.materials.map(m => ({value: m.id, label: m.name}))
                options[p.variable] = opts

                // Дефолт - устанавливаем первое значение
                if (opts.length > 0 && calculatorData[p.variable] === null) {
                    calculatorData[p.variable] = opts[0].value
                    console.log(`Установлен дефолт для ${p.variable}:`, opts[0].value)
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
        error.value = ''

        // Проверяем, что толщина выбрана
        if (!calculatorData.mat_select_in3) {
            throw new Error('Пожалуйста, выберите толщину пластика')
        }

        // Собираем params
        const params = [
            {variable: 'w', type: 1, value: calculatorData.w},
            {variable: 'h', type: 1, value: calculatorData.h},
            {variable: 'num', type: 1, value: calculatorData.num},
            {variable: 'doubleside', type: 2, value: calculatorData.doubleside ? 1 : 0},
            {variable: 'round', type: 2, value: calculatorData.round ? 1 : 0},
            {variable: 'tape', type: 2, value: calculatorData.tape ? 1 : 0},
            {variable: 'need_Nielsen', type: 2, value: calculatorData.need_Nielsen ? 1 : 0},
            // ВАРИАНТ 5: Добавляем материал прямо в params с type=3
            {variable: 'mat_select_in3', type: 3, value: calculatorData.mat_select_in3},
        ]

        // ВАРИАНТ 6: mat_select_params с обновлённым id на ID выбранного материала
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

onMounted(async () => {
    await fetchCalculatorParams()
    if (isEditMode.value) {
        restoreParams(calculatorData, rawMatSelectParams)
    }
})
</script>

