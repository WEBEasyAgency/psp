<template>
  <div class="text-input">
    <label v-if="label" class="text-input__label">
      {{ label }}
    </label>
    <div class="text-input__wrapper">
      <input
          type="text"
          :value="modelValue"
          @input="updateValue"
          :placeholder="placeholder"
          class="text-input__field"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  label: String,
  placeholder: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const updateValue = (e) => {
  emit('update:modelValue', e.target.value)
}
</script>

<style scoped>
@import "tailwindcss" reference;

.text-input {
  @apply flex flex-col gap-2;
}

.text-input__label {
  @apply text-[#1e3552] text-base leading-[22.40px];
}

.text-input__wrapper {
  @apply bg-[#f6f6f6] rounded-2xl outline outline-offset-[-1px] outline-slate-200 inline-flex items-center overflow-hidden;
}

.text-input__field {
  @apply w-full h-12 px-4 text-[#2d2b2c] text-base bg-transparent outline-none;
}

.text-input__field::placeholder {
  @apply text-[#9494a7];
}
</style>
