<template>
  <div class="number-input">
    <label v-if="label" class="number-input__label">
      {{ label }}
    </label>
    <div class="number-input__wrapper">
      <button
        @click="decrement"
        type="button"
        class="number-input__button"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 12H18" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <div class="number-input__field-wrapper">
        <input
          type="number"
          :value="modelValue"
          @input="updateValue"
          :step="step"
          :min="min"
          :max="max"
          class="number-input__field"
        />
      </div>
      <button
        @click="increment"
        type="button"
        class="number-input__button"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  label: String,
  min: {
    type: Number,
    default: 0
  },
  max: Number,
  step: {
    type: Number,
    default: 1
  }
});

const emit = defineEmits(['update:modelValue']);

const roundToStep = (value) => {
  // Определяем количество знаков после запятой на основе step
  const decimals = props.step < 1 ? 1 : 0;
  return Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals);
};

const increment = () => {
  const newValue = roundToStep(props.modelValue + props.step);
  if (!props.max || newValue <= props.max) {
    emit('update:modelValue', newValue);
  }
};

const decrement = () => {
  const newValue = roundToStep(props.modelValue - props.step);
  if (newValue >= props.min) {
    emit('update:modelValue', newValue);
  }
};

const updateValue = (e) => {
  const value = parseFloat(e.target.value);
  if (isNaN(value)) {
    emit('update:modelValue', props.min);
    return;
  }
  const roundedValue = roundToStep(value);
  if (roundedValue >= props.min && (!props.max || roundedValue <= props.max)) {
    emit('update:modelValue', roundedValue);
  }
};
</script>

<style scoped>
@import "tailwindcss" reference;

.number-input {
  @apply flex flex-col gap-2 xl:w-48;
}

.number-input__label {
  @apply text-[#1e3552] text-base leading-[22.40px];
}

.number-input__wrapper {
  @apply bg-[#f6f6f6] rounded-2xl min-w-[165px] max-w-[194px] xl:max-w-full outline outline-offset-[-1px] outline-slate-200 inline-flex items-center justify-between overflow-hidden;
}

.number-input__button {
  @apply p-3 bg-white outline outline-slate-200 flex items-center justify-center;
}

.number-input__field-wrapper {
  @apply xl:w-24 inline-flex flex-col items-center justify-center gap-2;
}

.number-input__field {
  @apply w-full h-6 text-center text-[#9494a7] text-base bg-transparent outline-none py-0 appearance-none;
  -moz-appearance: textfield;
}

.number-input__field::-webkit-outer-spin-button,
.number-input__field::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
