<template>
  <div class="filter-buttons">
    <div class="filter-buttons__header">
      <div class="filter-buttons__label">
        {{ label }}
      </div>
      <div v-if="hasHelp" class="filter-buttons__help">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9.14648 9.07361C9.31728 8.54732 9.63015 8.07896 10.0508 7.71948C10.4714 7.36001 10.9838 7.12378 11.5303 7.03708C12.0768 6.95038 12.6362 7.0164 13.1475 7.22803C13.6587 7.43966 14.1014 7.78875 14.4268 8.23633C14.7521 8.68391 14.9469 9.21256 14.9904 9.76416C15.0339 10.3158 14.9238 10.8688 14.6727 11.3618C14.4215 11.8548 14.0394 12.2685 13.5676 12.5576C13.0958 12.8467 12.5533 12.9998 12 12.9998V14.0002M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 17V17.1L11.9502 17.1002V17H12.0498Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
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
}

.filter-buttons__help {
  @apply flex items-center;
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

.filter-buttons__button--full-width {
  @apply flex-grow;
}
</style>
