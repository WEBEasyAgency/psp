<template>
    <div class="filter-buttons">
        <div class="filter-buttons__header">
            <div class="filter-buttons__label">
                {{ label }}
            </div>
            <HelpPopover
                v-if="hasHelp"
                :title="helpTitle"
                :description="helpDescription"
            />
        </div>
        <div class="filter-buttons__options">
            <button
                v-for="option in options"
                :key="option.value"
                @click="selectOption(option.value)"
                type="button"
                class="filter-buttons__button"
                :class="[
          modelValue === option.value ? 'filter-buttons__button--active' : '',
          fullWidthButtons ? 'filter-buttons__button--full-width' : ''
        ]"
            >
                <div class="filter-buttons__button-text">{{ option.label }}</div>
            </button>
        </div>
    </div>
</template>

<script setup>
import HelpPopover from './HelpPopover.vue';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    options: {
        type: Array,
        required: true
    },
    hasHelp: {
        type: Boolean,
        default: false
    },
    helpTitle: {
        type: String,
        default: 'Заголовок описания'
    },
    helpDescription: {
        type: String,
        default: 'Краткое описание в одну или две строки текста'
    },
    fullWidthButtons: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const selectOption = (value) => {
    emit('update:modelValue', value);
};
</script>

<style scoped>
@import "tailwindcss" reference;

.calculator__sub-options .filter-buttons {
    @apply gap-2;
}

.filter-buttons {
    @apply flex flex-col gap-4;
}

.filter-buttons__header {
    @apply h-6 inline-flex items-center gap-2;
}

.filter-buttons__label {
    @apply text-[#2d2b2c] text-base font-medium leading-[22.40px];
}

@media (min-width: 1280px) {
    .filter-buttons__label {
        @apply text-lg leading-[25.20px];
    }

    .calculator__sub-options .filter-buttons__label {
        @apply  text-[#2d2b2c] text-base font-normal font-['Inter'] leading-[17.60px]
    }
}

.filter-buttons__options {
    @apply inline-flex items-center gap-3 flex-wrap;
}

.filter-buttons__button {
    @apply h-7 px-2 py-1 rounded-lg flex items-center justify-center gap-1;
    /* Цвета управляются глобально из calculator.css */
}

@media (min-width: 1280px) {
    .filter-buttons__button {
        @apply h-11 px-4 py-2;
    }
}

/* Состояния кнопок управляются глобально из calculator.css */

.filter-buttons__button-text {
    @apply text-[13px] leading-[18.20px];
}
.filter-buttons__button:hover .filter-buttons__button-text {
    color: #244D80 !important;
}
.filter-buttons__button--full-width {
    @apply flex-grow;
}
</style>
