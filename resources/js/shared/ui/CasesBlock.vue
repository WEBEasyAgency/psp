<template>
  <section class="cases-block">
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
      <div class="cases-type-list-outer">
        <div class="cases-type-list">
          <a
            v-for="(category, index) in categories"
            :key="index"
            href="#"
            class="item"
            :class="{ active: activeTab === index }"
            @click.prevent="setActiveTab(index)"
          >
            <div class="name">{{ category.name }}</div>
            <div class="val">{{ category.count }}</div>
          </a>
        </div>
      </div>
      <div class="tabs-body">
        <div
          v-for="(category, index) in categories"
          :key="index"
          class="tab"
          :class="{ active: activeTab === index }"
        >
          <Swiper
            :modules="modules"
            :slides-per-view="'auto'"
            :space-between="12"
            :mousewheel="{ forceToAxis: true, releaseOnEdges: true }"
            :free-mode="{ enabled: true, sticky: false }"
            class="cases-slider"
            @swiper="(swiper) => onSwiper(swiper, index)"
          >
            <SwiperSlide v-for="(image, imgIndex) in category.images" :key="imgIndex">
              <div class="img"><img :src="image" alt=""></div>
            </SwiperSlide>
          </Swiper>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Mousewheel, FreeMode } from 'swiper/modules'
import 'swiper/css'

const modules = [Navigation, Mousewheel, FreeMode]
const activeTab = ref(0)
const swiperInstances = ref([])

const onSwiper = (swiper, index) => {
  swiperInstances.value[index] = swiper
}

const setActiveTab = (index) => {
  activeTab.value = index
  const swiper = swiperInstances.value[index]
  if (swiper) swiper.slideTo(0, 0)
}

const slidePrev = () => {
  const currentSwiper = swiperInstances.value[activeTab.value]
  currentSwiper?.slidePrev()
}

const slideNext = () => {
  const currentSwiper = swiperInstances.value[activeTab.value]
  currentSwiper?.slideNext()
}

const categories = [
  {
    name: 'Режим работы',
    count: 200,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Несветовые вывески',
    count: 50,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Таблички',
    count: 14,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Баннеры',
    count: 99,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Наклейки',
    count: 22,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Постеры',
    count: 45,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Объемные буквы',
    count: 12,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Флаги',
    count: 57,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Роллапы',
    count: 100,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  },
  {
    name: 'Виндеры',
    count: 24,
    images: [
      '/img/dest/case1.jpg',
      '/img/dest/case2.jpg',
      '/img/dest/case3.jpg',
      '/img/dest/case1.jpg'
    ]
  }
]
</script>
