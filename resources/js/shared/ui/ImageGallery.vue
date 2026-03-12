<template>
  <div class="image-gallery">
    <div
        class="image-gallery__main"
        @touchstart="handleTouchStart"
        @touchmove="handleTouchMove"
        @touchend="handleTouchEnd"
    >
      <picture>
        <source
            type="image/webp"
            :srcset="mainSrcset"
            sizes="(max-width: 768px) 100vw, 50vw"
        />
        <img :src="optimizedSrc(currentImage, 768)" alt="" class="image-gallery__main-image" />
      </picture>
    </div>
    <div class="image-gallery__thumbnails">
      <button
        type="button"
        class="image-gallery__arrow image-gallery__arrow--prev"
        @click="scrollPrev"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M15 19L8 12L15 5" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div
        v-for="(image, localIdx) in visibleImages"
        :key="thumbOffset + localIdx"
        class="image-gallery__thumbnail"
        :class="currentIndex === (thumbOffset + localIdx) ? 'image-gallery__thumbnail--active' : ''"
        @click="selectImage(thumbOffset + localIdx)"
      >
        <img :src="optimizedSrc(image, 100)" alt="" class="image-gallery__thumbnail-image" />
      </div>

      <button
        type="button"
        class="image-gallery__arrow image-gallery__arrow--next"
        @click="scrollNext"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9 5L16 12L9 19" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { optimizedSrc, optimizedSrcset } from '@/shared/composables/useOptimizedImage';

const props = defineProps({
  images: {
    type: Array,
    required: true,
    default: () => []
  },
  maxVisible: {
    type: Number,
    default: 0 // 0 = show all (backward compat)
  }
});

const currentIndex = ref(0);
const thumbOffset = ref(0);
const touchStartX = ref(0);
const touchEndX = ref(0);

const effectiveMax = computed(() => {
  if (props.maxVisible > 0) return props.maxVisible;
  return props.images.length; // show all
});

const currentImage = computed(() => props.images[currentIndex.value] || '');
const mainSrcset = computed(() => optimizedSrcset(currentImage.value, [480, 768, 1024]));

const visibleImages = computed(() => {
  return props.images.slice(thumbOffset.value, thumbOffset.value + effectiveMax.value);
});

const selectImage = (index) => {
  currentIndex.value = index;
};

// Auto-scroll thumbnails to keep current image visible
watch(currentIndex, (idx) => {
  if (idx < thumbOffset.value) {
    thumbOffset.value = idx;
  } else if (idx >= thumbOffset.value + effectiveMax.value) {
    thumbOffset.value = idx - effectiveMax.value + 1;
  }
});

const scrollPrev = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  }
};

const scrollNext = () => {
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
        scrollNext();
    }
    if (touchStartX.value - touchEndX.value < -50) {
        scrollPrev();
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
  @apply w-full inline-flex items-center gap-2 lg:gap-3;
}

.image-gallery__arrow {
  @apply flex items-center justify-center flex-shrink-0;
  background: none;
}

.image-gallery__thumbnail {
  @apply rounded-lg overflow-hidden flex-1 min-w-0;
  @apply opacity-50 cursor-pointer transition-opacity;
  @apply h-[33px] xl:h-[52px];
}

.image-gallery__thumbnail--active {
  @apply !opacity-100;
  @apply outline outline-[3px] outline-offset-[-3px] outline-[#f6f6f6];
}

.image-gallery__thumbnail-image {
  @apply w-full h-full object-cover;
}
</style>
