<template>
  <div class="image-gallery">
    <div
        class="image-gallery__main"
        @touchstart="handleTouchStart"
        @touchmove="handleTouchMove"
        @touchend="handleTouchEnd"
    >
      <img :src="currentImage" alt="" class="image-gallery__main-image" />
    </div>
    <div class="image-gallery__thumbnails">
      <button
        type="button"
        class="image-gallery__arrow image-gallery__arrow--prev"
        @click="prevImage"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M15 19L8 12L15 5" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div
        v-for="(image, index) in images"
        :key="index"
        class="image-gallery__thumbnail"
        :class="currentIndex === index ? 'image-gallery__thumbnail--active' : ''"
        @click="selectImage(index)"
      >
        <img :src="image" alt="" class="image-gallery__thumbnail-image" />
      </div>

      <button
        type="button"
        class="image-gallery__arrow image-gallery__arrow--next"
        @click="nextImage"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9 5L16 12L9 19" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  images: {
    type: Array,
    required: true,
    default: () => []
  }
});

const currentIndex = ref(0);
const touchStartX = ref(0);
const touchEndX = ref(0);

const currentImage = computed(() => props.images[currentIndex.value] || '');

const selectImage = (index) => {
  currentIndex.value = index;
};

const prevImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  }
};

const nextImage = () => {
  if (currentIndex.value < props.images.length - 1) {
    currentIndex.value++;
  }
};

const handleTouchStart = (e) => {
    touchStartX.value = e.touches[0].clientX;
};

const handleTouchMove = (e) => {
    touchEndX.value = e.touches[0].clientX;
};

const handleTouchEnd = () => {
    if (touchStartX.value - touchEndX.value > 50) {
        nextImage();
    }
    if (touchStartX.value - touchEndX.value < -50) {
        prevImage();
    }
    touchStartX.value = 0;
    touchEndX.value = 0;
};
</script>

<style scoped>
@import "tailwindcss" reference;

.image-gallery {
  @apply flex flex-col gap-3;
}

.image-gallery__main {
  @apply h-[200px] lg:h-[332px] relative bg-white rounded-2xl overflow-hidden;
}

@media (min-width: 1280px) {
  .image-gallery__main {
    @apply h-[332px];
  }
}

.image-gallery__main-image {
  @apply w-full h-full object-cover;
}

.image-gallery__thumbnails {
  @apply w-full inline-flex items-center justify-between gap-0 lg:gap-3 overflow-hidden;
}

.image-gallery__arrow {
  @apply flex items-center justify-center flex-shrink-0; /* Prevent shrinking/growing */
  background: none;
}

.image-gallery__thumbnail {
  @apply rounded-lg overflow-hidden;
  @apply opacity-50 cursor-pointer transition-opacity;
  @apply w-12 h-[33px] flex-shrink-0; /* Фиксированные размеры превью */
  @apply inline-flex flex-col justify-start items-start;
}

.image-gallery__thumbnail--active {
  @apply !opacity-100;
  @apply outline outline-[3px] outline-offset-[-3px] outline-[#f6f6f6];
}

.image-gallery__thumbnail-image {
  @apply w-full h-full object-cover;
}
</style>
