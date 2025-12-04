<template>
    <component
        :is="tag"
        :href="href"
        :type="buttonType"
        :disabled="isDisabled"
        class="btn"
        :class="[
            `btn--${variant}`,
            `btn--${size}`,
            { 'btn--loading': loading }
        ]"
        @click="handleClick"
    >
        <!-- Loading Spinner -->
        <svg v-if="loading" class="btn__spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <span class="btn__content" :class="{ 'opacity-0': loading && !showContentWhenLoading }">
            <slot />
        </span>
    </component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    /**
     * Если передана ссылка, кнопка рендерится как тег <a>
     */
    href: { type: String, default: null },
    /**
     * Тип кнопки (button, submit, reset) - только для тега <button>
     */
    type: { type: String, default: 'button' },
    /**
     * Блокирует кнопку
     */
    disabled: { type: Boolean, default: false },
    /**
     * Показывает спиннер загрузки и блокирует кнопку
     */
    loading: { type: Boolean, default: false },
    /**
     * Визуальный стиль кнопки: 'primary', 'secondary', 'outline'
     */
    variant: { type: String, default: 'primary' },
    /**
     * Размер кнопки: 'sm', 'md', 'lg'
     */
    size: { type: String, default: 'md' },
    /**
     * Если true, контент кнопки остается видимым при загрузке (по умолчанию скрывается)
     */
    showContentWhenLoading: { type: Boolean, default: false }
})

const emit = defineEmits(['click'])

const tag = computed(() => props.href ? 'a' : 'button')
const buttonType = computed(() => tag.value === 'a' ? null : props.type)
const isDisabled = computed(() => props.disabled || props.loading)

const handleClick = (event) => {
    if (isDisabled.value) {
        event.preventDefault()
        event.stopPropagation()
        return
    }
    emit('click', event)
}
</script>

<style scoped>
/*
  Мы используем @reference, чтобы получить доступ к переменным и утилитам Tailwind
  из основного файла стилей.
  ПУТЬ ДОЛЖЕН БЫТЬ ОТНОСИТЕЛЬНЫМ!
*/
@reference "../../../../css/app.css";

/* ==========================================================================
   Base Button Styles
   ========================================================================== */
.btn {
    @apply inline-flex justify-center items-center gap-2 font-medium transition-all duration-200 whitespace-nowrap relative;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn:disabled,
.btn--loading {
    @apply cursor-not-allowed opacity-70;
}

/* ==========================================================================
   Sizes
   Добавляйте новые размеры здесь. Именование: .btn--{size}
   ========================================================================== */
.btn--sm {
    @apply h-10 px-4 py-2 text-sm rounded-lg;
}

.btn--md {
    /* Стандартный размер для калькуляторов */
    @apply h-14 px-6 py-4 text-base rounded-xl;
}

.btn--lg {
    @apply h-16 px-8 py-5 text-lg rounded-2xl;
}

/* ==========================================================================
   Variants (Colors)
   Добавляйте новые цвета здесь. Именование: .btn--{variant}
   ========================================================================== */

/* Primary: Синяя заливка */
.btn--primary {
    @apply bg-[#3c7bbb] text-white;
}
.btn--primary:hover:not(:disabled) {
    @apply bg-[#244d80];
}
.btn--primary:active:not(:disabled) {
    @apply bg-[#1e406b];
}

/* Secondary: Белая с серой рамкой */
.btn--secondary {
    @apply bg-white border border-[#cbd5e1] text-[#1e3552];
}
.btn--secondary:hover:not(:disabled) {
    @apply border-[#3c7bbb] text-[#3c7bbb];
}

/* Outline: Прозрачная с синей рамкой */
.btn--outline {
    @apply bg-transparent border-2 border-[#3c7bbb] text-[#3c7bbb] py-1;
}
.btn--outline:hover:not(:disabled) {
    @apply bg-[#e6eef8] text-[#1e3552];
}

/* Filled: Белая с серой рамкой (как secondary), но с конкретными стилями из макета */
.btn--filled {
    @apply bg-white border border-slate-200 text-[#1e3552] outline-1 outline-offset-[-1px] outline-slate-200;
}
.btn--filled:hover:not(:disabled) {
    @apply border-[#3c7bbb] text-[#3c7bbb] outline-[#3c7bbb];
}

/* Success: Зеленая заливка (для "Перейти в корзину") */
.btn--success {
    @apply bg-green-500 text-white;
}
.btn--success:hover:not(:disabled) {
    @apply bg-green-600;
}
.btn--success:active:not(:disabled) {
    @apply bg-green-700;
}

/* Loading Spinner */
.btn__spinner {
    @apply animate-spin -ml-1 mr-2 h-5 w-5 text-current absolute;
}

.btn__content {
    @apply flex items-center gap-2;
}
</style>
