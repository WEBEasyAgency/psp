<template>
  <section class="construction-type">
    <div class="container">
      <div class="title-block">
        <h2>Выберите тип конструкции для расчета стоимости</h2>
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
        class="construction-slider"
        @swiper="onSwiper"
      >
        <SwiperSlide v-for="(item, index) in constructionTypes" :key="index">
          <div class="inner">
            <div class="name-block">
              <div class="name">{{ item.name }}</div>
              <div class="img"><img :src="item.image" alt=""></div>
            </div>
            <div class="text">
              <ul>
                <li v-for="(link, i) in item.links" :key="i">
                  <a :href="link.url">{{ link.text }}</a>
                </li>
              </ul>
            </div>
            <div class="num">{{ String(index + 1).padStart(2, '0') }}</div>
          </div>
        </SwiperSlide>
      </Swiper>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'
import 'swiper/css'

const modules = [Navigation]
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

const constructionTypes = [
  {
    name: 'Несветовые вывески',
    image: '/img/dest/type1.jpg',
    links: [
      { text: 'Вывеска из ПВХ-пластика', url: '#' },
      { text: 'Вывеска из алюминиевого композита', url: '#' },
      { text: 'Вывеска из оргстекла', url: '#' }
    ]
  },
  {
    name: 'Режим работы',
    image: '/img/dest/type2.jpg',
    links: [
      { text: 'Вывеска из ПВХ-пластика', url: '#' },
      { text: 'Вывеска из алюминиевого композита', url: '#' }
    ]
  },
  {
    name: 'Таблички',
    image: '/img/dest/type3.jpg',
    links: [
      { text: 'Вывеска из ПВХ-пластика', url: '#' },
      { text: 'Вывеска из алюминиевого композита', url: '#' },
      { text: 'Вывеска из оргстекла', url: '#' }
    ]
  },
  {
    name: 'Баннеры',
    image: '/img/dest/type4.jpg',
    links: [
      { text: 'Вывеска из ПВХ-пластика', url: '#' },
      { text: 'Вывеска из алюминиевого композита', url: '#' }
    ]
  },
  {
    name: 'Наклейки',
    image: '/img/dest/type5.jpg',
    links: [
      { text: 'Все наклейки', url: '#' }
    ]
  },
  {
    name: 'Постеры',
    image: '/img/dest/type6.jpg',
    links: [
      { text: 'Все постеры', url: '#' }
    ]
  },
  {
    name: 'Объемные буквы',
    image: '/img/dest/type7.jpg',
    links: [
      { text: 'Все объемные буквы', url: '#' }
    ]
  },
  {
    name: 'Флаги',
    image: '/img/dest/type8.jpg',
    links: [
      { text: 'Все флаги', url: '#' }
    ]
  },
  {
    name: 'Роллапы',
    image: '/img/dest/type9.jpg',
    links: [
      { text: 'Все роллапы', url: '#' }
    ]
  },
  {
    name: 'Виндеры',
    image: '/img/dest/type10.jpg',
    links: [
      { text: 'Все виндеры', url: '#' }
    ]
  }
]
</script>
