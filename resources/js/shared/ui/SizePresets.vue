<template>
  <div class="size-presets">
    <div class="size-presets__label">Стандартные размеры</div>
    <div class="size-presets__options">
      <button
          v-for="preset in presets"
          :key="preset.label"
          @click="selectPreset(preset)"
          type="button"
          class="size-presets__button"
          :class="modelValue === preset.label ? 'size-presets__button--active' : ''"
      >
        <div class="size-presets__button-text">{{ preset.label }}</div>
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: String,
    default: null
  },
  presets: {
    type: Array,
    required: true
    // [{label: 'A4', w: 297, h: 420}, ...]
  }
})

const emit = defineEmits(['update:modelValue', 'select'])

const selectPreset = (preset) => {
  emit('update:modelValue', preset.label)
  emit('select', { w: preset.w, h: preset.h })
}
</script>

<style scoped>
@import "tailwindcss" reference;

.size-presets {
  @apply flex flex-col gap-4;
}

.size-presets__label {
  @apply text-[#2d2b2c] text-base font-medium leading-[22.40px];
}

@media (min-width: 1280px) {
  .size-presets__label {
    @apply text-lg leading-[25.20px];
  }
}

.size-presets__options {
  @apply inline-flex items-center gap-3 flex-wrap;
}

.size-presets__button {
  @apply h-7 px-2 py-1 rounded-lg flex items-center justify-center gap-1;
}

@media (min-width: 1280px) {
  .size-presets__button {
    @apply h-11 px-4 py-2;
  }
}
.size-presets__button:hover:not(:disabled) {
    background-color: #c8dbef !important;
}
.size-presets__button-text {
  @apply text-[13px] leading-[18.20px];
}
.size-presets__button--active:hover:not(:disabled)  {
    background-color: #f0f4fa !important;
    color: #2c619d !important;
    border: 1px solid #3c7bbb !important;
    outline: none !important;
    opacity: 1 !important;
}
</style>
