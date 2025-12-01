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

const increment = () => {
  const newValue = props.modelValue + props.step;
  if (!props.max || newValue <= props.max) {
    emit('update:modelValue', newValue);
  }
};

const decrement = () => {
  const newValue = props.modelValue - props.step;
  if (newValue >= props.min) {
    emit('update:modelValue', newValue);
  }
};

const updateValue = (e) => {
  const value = parseInt(e.target.value) || props.min;
  if (value >= props.min && (!props.max || value <= props.max)) {
    emit('update:modelValue', value);
  }
};
</script>

<style scoped>
@import "tailwindcss" reference;

.number-input {
  @apply flex flex-col gap-2;
}

.number-input__label {
  @apply text-[#1e3552] text-base leading-[22.40px];
}

.number-input__wrapper {
  @apply bg-[#f6f6f6] rounded-2xl outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex items-center overflow-hidden;
}

.number-input__button {
  @apply p-3 bg-white outline outline-1 outline-slate-200 flex items-center justify-center;
}

.number-input__field-wrapper {
  @apply w-full inline-flex flex-col items-center justify-start gap-2;
}

.number-input__field {
  @apply w-full xl:w-24 h-6 text-center text-[#9494a7] text-base bg-transparent outline-none;
}
</style>
