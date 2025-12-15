<template>
  <div class="toggle-switch-wrapper">
    <button
      @click="toggle"
      type="button"
      class="toggle-switch"
    >
      <div
        class="toggle-switch__track"
        :class="modelValue ? 'toggle-switch__track--active' : ''"
      >
        <div
          class="toggle-switch__thumb"
          :class="modelValue ? 'toggle-switch__thumb--active' : ''"
        >
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="20" height="20" rx="10" fill="white"/>
          </svg>
        </div>
      </div>
      <div class="toggle-switch__label">{{ label }}</div>
    </button>
    <HelpPopover
      v-if="helpTitle && helpDescription"
      :title="helpTitle"
      :description="helpDescription"
      placement="right"
    />
  </div>
</template>

<script setup>
import HelpPopover from './HelpPopover.vue';

const props = defineProps({
  modelValue: Boolean,
  label: {
    type: String,
    required: true
  },
  helpTitle: {
    type: String,
    default: ''
  },
  helpDescription: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const toggle = () => {
  emit('update:modelValue', !props.modelValue);
};
</script>

<style scoped>
@import "tailwindcss" reference;

.toggle-switch-wrapper {
  @apply flex items-center gap-2;
}

.toggle-switch {
  @apply flex items-center gap-2;
  background: none;
}

.toggle-switch__track {
  @apply w-10 h-6 p-2.5 relative rounded-xl flex items-start justify-start transition-colors;
  @apply bg-[#c8dbef];
}

.toggle-switch__track--active {
  @apply bg-[#3c7bbb];
}

.toggle-switch__thumb {
  @apply absolute top-[2px] transition-all;
  @apply left-[2px];
}

.toggle-switch__thumb--active {
  @apply left-[18px];
}

.toggle-switch__label {
  @apply text-[#2d2b2c] text-sm leading-5;
}

@media (min-width: 1280px) {
  .toggle-switch__label {
    @apply text-base leading-[22.40px];
  }
}
</style>
