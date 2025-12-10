<template>
    <div class="info-tooltip" ref="tooltipRef" @mouseenter="showTooltip" @mouseleave="hideTooltip">
        <!-- Текст-триггер -->
        <div class="info-tooltip__text">
            <slot>Чем больше, тем дешевле</slot>
        </div>

        <!-- Поповер -->
        <Transition name="tooltip">
            <div
                v-if="isOpen"
                class="info-tooltip__content"
                :class="placementClasses"
            >
                <!-- Иконка информации -->
                <div class="info-tooltip__info-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 11V16M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 8V8.1L11.9502 8.1002V8H12.0498Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <!-- Текстовый контент -->
                <div class="info-tooltip__text-content">
                    <div class="info-tooltip__title">{{ title }}</div>
                    <div class="info-tooltip__description">{{ description }}</div>
                </div>

                <!-- Треугольная стрелка -->
                <div class="info-tooltip__arrow" :class="arrowClasses">
                    <!-- Стрелка для Right (указывает влево) -->
                    <svg v-if="placement === 'right'" width="12" height="23" viewBox="0 0 12 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.3135 0V11.3137V22.6274L-0.000231743 11.3137L11.3135 0Z" fill="#244D80"/>
                    </svg>

                    <!-- Стрелка для Top (указывает вниз) -->
                    <svg v-if="placement === 'top'" width="23" height="12" viewBox="0 0 23 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M0 0H11.3137H22.6274L11.3137 11.3137L0 0Z" fill="#244D80"/>
                    </svg>

                    <!-- Стрелка для Bottom (указывает вверх) -->
                    <svg v-if="placement === 'bottom'" width="23" height="12" viewBox="0 0 23 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.6274 11.3135L11.3137 11.3135L2.47955e-05 11.3135L11.3137 -0.000232697L22.6274 11.3135Z" fill="#244D80"/>
                    </svg>

                    <!-- Стрелка для Left (указывает вправо) -->
                    <svg v-if="placement === 'left'" width="12" height="23" viewBox="0 0 12 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 22.6274V11.3137V2.47955e-05L11.3137 11.3137L0 22.6274Z" fill="#244D80"/>
                    </svg>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    title: { type: String, default: 'Скидка при заказе' },
    description: { type: String, default: 'При увеличении количества товара стоимость за единицу снижается' },
    placement: {
        type: String,
        default: 'top', // 'top', 'bottom', 'left', 'right'
        validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value)
    }
});

const isOpen = ref(false);
const tooltipRef = ref(null);

const showTooltip = () => {
    isOpen.value = true;
};

const hideTooltip = () => {
    isOpen.value = false;
};

// Классы позиционирования самого поповера
const placementClasses = computed(() => {
    const positions = {
        top: 'bottom-full mb-3 left-1/2 -translate-x-1/2', // Центрирование по горизонтали
        bottom: 'top-full mt-3 left-1/2 -translate-x-1/2', // Центрирование по горизонтали
        left: 'right-full mr-3 top-1/2 -translate-y-1/2',  // Центрирование по вертикали
        right: 'left-full ml-3 top-1/2 -translate-y-1/2'   // Центрирование по вертикали
    };
    return positions[props.placement];
});

// Классы позиционирования стрелки внутри поповера
const arrowClasses = computed(() => {
    const arrows = {
        top: 'left-1/2 -bottom-[11px] -translate-x-1/2', // По центру снизу
        bottom: 'left-1/2 -top-[11px] -translate-x-1/2', // По центру сверху
        left: '-right-[11px] top-1/2 -translate-y-1/2',  // По центру справа
        right: '-left-[11px] top-1/2 -translate-y-1/2'   // По центру слева
    };
    return arrows[props.placement];
});
</script>

<style scoped>
@import "tailwindcss" reference;

.info-tooltip {
    @apply relative inline-flex items-center;
}

.info-tooltip__text {
    @apply justify-start text-[#2c619d] text-sm font-normal font-['Inter'] leading-5 underline underline-offset-4 decoration-dashed cursor-help;
}

.info-tooltip__content {
    @apply w-[280px] p-3 absolute bg-[#244d80] rounded-lg;
    @apply inline-flex justify-center items-start gap-2;
    @apply z-50;
    box-shadow: 0px 2px 6px -2px rgba(0, 0, 0, 0.06), 0px 8px 12px -2px rgba(0, 0, 0, 0.08);
}

.info-tooltip__info-icon {
    @apply flex-shrink-0;
}

.info-tooltip__text-content {
    @apply flex-1 inline-flex flex-col justify-center items-start gap-1;
}

.info-tooltip__title {
    @apply text-white text-lg font-medium font-['Inter'] leading-[25.20px];
}

.info-tooltip__description {
    @apply text-slate-200 text-sm font-normal font-['Inter'] leading-5;
}

.info-tooltip__arrow {
    @apply absolute pointer-events-none;
}

/* Transition */
.tooltip-enter-active,
.tooltip-leave-active {
    transition: opacity 0.2s, transform 0.2s;
}

.tooltip-enter-from,
.tooltip-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
