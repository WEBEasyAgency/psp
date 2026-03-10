<template>
  <section class="clients-block">
    <div class="container">
      <div class="title-block">
        <h2>Клиенты</h2>
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
      <Swiper
        :modules="modules"
        :slides-per-view="'auto'"
        :space-between="12"
        :mousewheel="{ forceToAxis: true, releaseOnEdges: true }"
        :free-mode="{ enabled: true, sticky: false }"
        class="clients-slider"
        @swiper="onSwiper"
      >
        <SwiperSlide v-for="(client, index) in clients" :key="index">
          <div class="img"><img :src="client" alt=""></div>
        </SwiperSlide>
      </Swiper>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Mousewheel, FreeMode } from 'swiper/modules'
import 'swiper/css'

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

const clients = [
  '/img/dest/client1.png',
  '/img/dest/client2.png',
  '/img/dest/client3.png',
  '/img/dest/client4.png',
  '/img/dest/client5.png',
  '/img/dest/client6.png',
  '/img/dest/client7.png',
  '/img/dest/client8.png'
]
</script>
