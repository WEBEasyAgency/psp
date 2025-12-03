<template>
  <div class="help-popover" @mouseenter="showPopover" @mouseleave="hidePopover">
    <!-- Иконка вопроса -->
    <div class="help-popover__icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9.14648 9.07361C9.31728 8.54732 9.63015 8.07896 10.0508 7.71948C10.4714 7.36001 10.9838 7.12378 11.5303 7.03708C12.0768 6.95038 12.6362 7.0164 13.1475 7.22803C13.6587 7.43966 14.1014 7.78875 14.4268 8.23633C14.7521 8.68391 14.9469 9.21256 14.9904 9.76416C15.0339 10.3158 14.9238 10.8688 14.6727 11.3618C14.4215 11.8548 14.0394 12.2685 13.5676 12.5576C13.0958 12.8467 12.5533 12.9998 12 12.9998V14.0002M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 17V17.1L11.9502 17.1002V17H12.0498Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <!-- Поповер -->
    <Transition name="popover">
      <div v-if="isOpen" class="help-popover__content">
        <!-- Иконка информации -->
        <div class="help-popover__info-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 11V16M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 8V8.1L11.9502 8.1002V8H12.0498Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

        <!-- Текстовый контент -->
        <div class="help-popover__text">
          <div class="help-popover__title">{{ title }}</div>
          <div class="help-popover__description">{{ description }}</div>
        </div>

        <!-- Кнопка закрытия -->
        <button @click="hidePopover" class="help-popover__close" type="button">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 16L12 12M12 12L8 8M12 12L16 8M12 12L8 16" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <!-- Треугольная стрелка -->
        <div class="help-popover__arrow">
          <svg width="23" height="12" viewBox="0 0 23 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0H11.3137H22.6274L11.3137 11.3137L0 0Z" fill="#244D80"/>
          </svg>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Заголовок описания'
  },
  description: {
    type: String,
    default: 'Краткое описание в одну или две строки текста'
  }
});

const isOpen = ref(false);

const showPopover = () => {
  isOpen.value = true;
};

const hidePopover = () => {
  isOpen.value = false;
};
</script>

<style scoped>
@import "tailwindcss" reference;

.help-popover {
  @apply relative inline-flex items-center;
}

.help-popover__icon {
  @apply flex items-center justify-center cursor-pointer;
}

.help-popover__content {
  @apply w-[267px] p-3 absolute bg-[#244d80] rounded-lg;
  @apply inline-flex justify-center items-start gap-2;
  @apply z-50;
  /* Позиционирование: внизу справа от иконки */
  left: 0;
  top: calc(100% + 12px);
  box-shadow: 0px 2px 6px -2px rgba(0, 0, 0, 0.06), 0px 8px 12px -2px rgba(0, 0, 0, 0.08);
}

.help-popover__info-icon {
  @apply flex-shrink-0;
}

.help-popover__text {
  @apply flex-1 inline-flex flex-col justify-center items-start gap-1;
}

.help-popover__title {
  @apply text-white text-lg font-medium font-['Inter'] leading-[25.20px];
}

.help-popover__description {
  @apply text-slate-200 text-sm font-normal font-['Inter'] leading-5;
}

.help-popover__close {
  @apply flex-shrink-0 cursor-pointer;
  background: none;
  border: none;
  padding: 0;
}

.help-popover__arrow {
  @apply absolute;
  left: 12px;
  top: -11px;
  transform: rotate(180deg);
}

/* Transition */
.popover-enter-active,
.popover-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}

.popover-enter-from,
.popover-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
