<template>
  <div class="checkbox-group">
    <div class="checkbox-group__label">{{ label }}</div>
    <div class="checkbox-group__options">
      <label
          v-for="option in options"
          :key="option.value"
          class="checkbox-group__option"
      >
        <input
            type="checkbox"
            :checked="modelValue.includes(option.value)"
            @change="toggleOption(option.value)"
            class="checkbox-group__input"
        />
        <span class="checkbox-group__checkmark"></span>
        <span class="checkbox-group__text">{{ option.label }}</span>
      </label>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  label: {
    type: String,
    default: ''
  },
  options: {
    type: Array,
    required: true
    // [{value: 'ties', label: 'Комплект стяжек'}, ...]
  },
  modelValue: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue'])

const toggleOption = (value) => {
  const current = [...props.modelValue]
  const idx = current.indexOf(value)
  if (idx === -1) {
    current.push(value)
  } else {
    current.splice(idx, 1)
  }
  emit('update:modelValue', current)
}
</script>

<style scoped>
@import "tailwindcss" reference;

.checkbox-group {
  @apply flex flex-col gap-4;
}

.checkbox-group__label {
  @apply text-[#2d2b2c] text-base font-medium leading-[22.40px];
}

@media (min-width: 1280px) {
  .checkbox-group__label {
    @apply text-lg leading-[25.20px];
  }
}

.checkbox-group__options {
  @apply flex flex-col gap-3;
}

.checkbox-group__option {
  @apply flex items-center gap-3 cursor-pointer;
}

.checkbox-group__input {
  @apply sr-only;
}

.checkbox-group__checkmark {
  @apply w-6 h-6 rounded border-2 border-[#c8dbef] flex items-center justify-center flex-shrink-0 transition-colors;
}

.checkbox-group__input:checked ~ .checkbox-group__checkmark {
  @apply bg-[#3c7bbb] border-[#3c7bbb];
}

.checkbox-group__input:checked ~ .checkbox-group__checkmark::after {
  content: '';
  @apply block w-3 h-1.5 border-l-2 border-b-2 border-white transform -rotate-45 -translate-y-0.5;
}

.checkbox-group__text {
  @apply text-[#2d2b2c] text-sm leading-5;
}

@media (min-width: 1280px) {
  .checkbox-group__text {
    @apply text-base leading-[22.40px];
  }
}
</style>
