<template>
  <div class="radio-group">
    <div class="radio-group__header">
      <div class="radio-group__label">
        {{ label }}
      </div>
      <div v-if="hasHelp" class="radio-group__help">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9.14648 9.07361C9.31728 8.54732 9.63015 8.07896 10.0508 7.71948C10.4714 7.36001 10.9838 7.12378 11.5303 7.03708C12.0768 6.95038 12.6362 7.0164 13.1475 7.22803C13.6587 7.43966 14.1014 7.78875 14.4268 8.23633C14.7521 8.68391 14.9469 9.21256 14.9904 9.76416C15.0339 10.3158 14.9238 10.8688 14.6727 11.3618C14.4215 11.8548 14.0394 12.2685 13.5676 12.5576C13.0958 12.8467 12.5533 12.9998 12 12.9998V14.0002M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 17V17.1L11.9502 17.1002V17H12.0498Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
    </div>
    <div class="radio-group__options">
      <button
        v-for="option in options"
        :key="option.value"
        @click="selectOption(option.value)"
        type="button"
        class="radio-group__option"
      >
        <svg v-if="modelValue === option.value" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="2.5" y="2.5" width="11" height="11" rx="5.5" fill="white" />
          <rect x="2.5" y="2.5" width="11" height="11" rx="5.5" stroke="#3C7BBB" stroke-width="5" />
        </svg>
        <svg v-else width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="0.5" y="0.5" width="15" height="15" rx="7.5" fill="white" />
          <rect x="0.5" y="0.5" width="15" height="15" rx="7.5" stroke="#94A3B8" />
        </svg>
        <div
          class="radio-group__option-text"
          :class="modelValue === option.value ? 'radio-group__option-text--active' : ''"
        >
          {{ option.label }}
        </div>
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
  }
});

const emit = defineEmits(['update:modelValue']);

const selectOption = (value) => {
  emit('update:modelValue', value);
};
</script>

<style scoped>
@import "tailwindcss" reference;

.radio-group {
  @apply flex flex-col gap-4;
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
  @apply flex flex-col gap-3;
}

.radio-group__option {
  @apply inline-flex  bg-none gap-2 text-left;
  background: none;
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
