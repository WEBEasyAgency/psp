<template>
  <section class="cases-block" v-if="images && images.length > 0">
    <div class="container">
      <div class="title-block">
        <h2>Как это выглядит после установки</h2>
        <div class="arrows">
          <div class="prev" @click="slidePrev">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M14 16L10 12L14 8" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="next" @click="slideNext">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 8L14 12L10 16" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="tabs-body">
        <div class="tab active" style="display: block;">
          <div class="cases-slider-vue">
            <Swiper
              :modules="modules"
              :slides-per-view="'auto'"
              :space-between="12"
              :mousewheel="{ forceToAxis: true, releaseOnEdges: true }"
              :free-mode="{ enabled: true, sticky: false }"
              @swiper="onSwiper"
            >
              <SwiperSlide v-for="(imgSrc, index) in images" :key="index">
                <div class="img">
                  <img
                      :src="optimizedSrc(imgSrc, 320)"
                      :srcset="optimizedSrcset(imgSrc, [320, 480, 768])"
                      sizes="(max-width: 768px) 241px, 692px"
                      alt="Пример установки"
                      loading="lazy"
                  >
                </div>
              </SwiperSlide>
            </Swiper>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { optimizedSrc, optimizedSrcset } from '@/shared/composables/useOptimizedImage'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Mousewheel, FreeMode } from 'swiper/modules'
import 'swiper/css'
import { installationCasesImages } from '@/shared/data/installationCasesImages.js'

const props = defineProps({
  calcId: {
    type: [Number, String],
    required: true
  }
})

const modules = [Navigation, Mousewheel, FreeMode]
const swiperInstance = ref(null)

const onSwiper = (swiper) => {
  swiperInstance.value = swiper
}

const slidePrev = () => {
  swiperInstance.value?.slidePrev()
}

const slideNext = () => {
  swiperInstance.value?.slideNext()
}

const images = computed(() => {
  return installationCasesImages[props.calcId] || []
})
</script>

<style scoped>
/* Стили скопированы и адаптированы из оригинального CSS/LESS */
@media only screen and (min-width:1280px){
    .cases-block {
        padding: 64px 0 0 !important;
        /* Ensure visibility if global styles rely on tabs */
    }

}

.tab.active {
    display: block;
}

.cases-slider-vue {
    margin-top: 32px;
}
.cases-slider-vue :deep(.swiper-slide) {
    max-width: 692px;
}
@media only screen and (max-width: 768px) {
    .cases-slider-vue :deep(.swiper-slide) {
        max-width: 241px;
    }
}
.cases-slider-vue :deep(.swiper-slide .img) {
    height: 460px;
}
@media only screen and (max-width: 768px) {
    .cases-slider-vue :deep(.swiper-slide .img) {
        height: 160px;
    }
}
.cases-slider-vue :deep(.swiper-slide img) {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 24px;
}
.swiper-wrapper {
    overflow: visible;
}
.swiper.swiper-initialized.swiper-horizontal {
    overflow: visible;
}
</style>
