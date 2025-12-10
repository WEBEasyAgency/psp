<template>
    <div class="radio-group">
        <div class="radio-group__header">
            <div class="radio-group__label">
                {{ label }}
            </div>
            <HelpPopover
                v-if="hasHelp"
                :title="helpTitle"
                :description="helpDescription"
            />
        </div>
        <div class="radio-group__options">
            <div
                v-for="option in options"
                :key="option.value"
                class="radio-group__item"
            >
                <button
                    @click="selectOption(option.value)"
                    type="button"
                    class="radio-group__option"
                >
                    <svg v-if="modelValue === option.value" width="16" height="16" viewBox="0 0 16 16" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <rect x="2.5" y="2.5" width="11" height="11" rx="5.5" fill="white"/>
                        <rect x="2.5" y="2.5" width="11" height="11" rx="5.5" stroke="#3C7BBB" stroke-width="5"/>
                    </svg>
                    <svg v-else width="16" height="16" viewBox="0 0 16 16" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="15" height="15" rx="7.5" fill="white"/>
                        <rect x="0.5" y="0.5" width="15" height="15" rx="7.5" stroke="#94A3B8"/>
                    </svg>
                    <div
                        class="radio-group__option-text"
                        :class="modelValue === option.value ? 'radio-group__option-text--active' : ''"
                    >
                        {{ option.label }}
                    </div>
                </button>

                <!-- Слот для дополнительного контента под опцией -->
                <slot
                    name="details"
                    :option="option"
                    :isActive="modelValue === option.value"
                ></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import HelpPopover from "@/shared/ui/HelpPopover.vue";

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
});

const emit = defineEmits(['update:modelValue']);

const selectOption = (value) => {
    emit('update:modelValue', value);
};
</script>

<style scoped>
@import "tailwindcss" reference;

.radio-group {
    @apply flex flex-col gap-2 lg:gap-4;
}

.radio-group__header {
    @apply h-6 inline-flex items-center gap-2;
}

.radio-group__label {
    @apply text-[#2d2b2c] text-base font-medium leading-[22.40px];
}

@media (min-width: 1280px) {
    .radio-group__label {
        @apply text-lg leading-[25.20px];
    }
}

.radio-group__help {
    @apply flex items-center;
}

.radio-group__options {
    @apply flex flex-col;
}

.radio-group__item {
    @apply flex flex-col;
}

.radio-group__option {
    @apply inline-flex gap-2 text-left items-center justify-start;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    max-height: 35px;
}

.radio-group__option-text {
    @apply text-sm text-[#282828] leading-5;
    @apply font-normal;
}

@media (min-width: 1280px) {
    .radio-group__option-text {
        @apply text-base leading-[22.40px];
    }
}

.radio-group__option-text--active {
    @apply font-medium;
}
</style>
