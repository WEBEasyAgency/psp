<template>
  <section id="construction-slider" class="construction-type ">
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
        :mousewheel="{ forceToAxis: true, releaseOnEdges: true }"
        :free-mode="{ enabled: true, sticky: false }"
        class="construction-slider"
        @swiper="onSwiper"
      >
        <SwiperSlide v-for="(item, index) in constructionTypes" :key="index">
          <div class="inner">
            <div class="name-block">
              <div class="name">{{ item.name }}</div>
              <div class="img">
                <img :src="item.image" alt="">
              </div>
            </div>
            <div class="text">
              <ul>
                <li v-for="(link, i) in item.links" :key="i">
                  <a :href="link.url">{{ link.text }}</a>
                </li>
              </ul>
            </div>
          </div>
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

const constructionTypes = [
  {
    name: 'Несветовые вывески',
    image: '/img/dest/type1.jpg',
    links: [
      { text: 'Вывеска из ПВХ-пластика', url: '/product/156' },
      { text: 'Вывеска из алюминиевого композита', url: '/product/158' },
      { text: 'Вывеска из оргстекла', url: '/product/157' }
    ]
  },
  {
    name: 'Режим работы',
    image: '/img/dest/type2.jpg',
    links: [
      { text: 'Режим работы (скоро)', url: '#' }
    ]
  },
  {
    name: 'Таблички',
    image: '/img/dest/type3.jpg',
    links: [
      { text: 'Пластиковые таблички', url: '/product/159' },
      { text: 'Акриловые таблички', url: '/product/160' },
      { text: 'Таблички из композита', url: '/product/161' }
    ]
  },
  {
    name: 'Баннеры',
    image: '/img/dest/type4.jpg',
    links: [
      { text: 'Баннеры (скоро)', url: '#' }
    ]
  },
  {
    name: 'Наклейки',
    image: '/img/dest/type5.jpg',
    links: [
      { text: 'Маленькие наклейки', url: '/product/154' }
    ]
  },
  {
    name: 'Постеры',
    image: '/img/dest/type6.jpg',
    links: [
      { text: 'Постеры (скоро)', url: '#' }
    ]
  },
  {
    name: 'Объемные буквы',
    image: '/img/dest/type7.jpg',
    links: [
      { text: 'С бортом из алюминия', url: '/product/146' },
      { text: 'Со световым бортом', url: '/product/155' }
    ]
  },
  {
    name: 'Флаги',
    image: '/img/dest/type8.jpg',
    links: [
      { text: 'Флаги (скоро)', url: '#' }
    ]
  },
  {
    name: 'Роллапы',
    image: '/img/dest/type9.jpg',
    links: [
      { text: 'Роллапы (скоро)', url: '#' }
    ]
  },
  {
    name: 'Виндеры',
    image: '/img/dest/type10.jpg',
    links: [
      { text: 'Виндеры (скоро)', url: '#' }
    ]
  }
]
</script>

<style scoped>
.swiper {
    overflow: visible !important;
}

.name-block .img {
    flex-shrink: 0;
    width: 96px;
    height: 96px;
}

.name-block .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<style>
.construction-type .construction-slider .swiper-slide {
    height: 298px;
}

.construction-type .construction-slider .swiper-slide .inner,
.construction-type .construction-slider .swiper-slide.swiper-slide-active .inner,
.construction-type .construction-slider:has(.inner:hover) .swiper-slide-active .inner {
    height: 298px;
    transition: none;
}

.construction-type .construction-slider .swiper-slide .inner:hover {
    height: 298px;
    background-color: var(--light-gray);
}

@media only screen and (max-width: 768px) {
    .construction-type .construction-slider .swiper-slide {
        height: 239px;
    }
    .construction-type .construction-slider .swiper-slide .inner,
    .construction-type .construction-slider .swiper-slide.swiper-slide-active .inner,
    .construction-type .construction-slider:has(.inner:hover) .swiper-slide-active .inner,
    .construction-type .construction-slider .swiper-slide .inner:hover {
        height: 100%;
    }
}

.construction-type .construction-slider .inner .text {
    margin-top: auto;
}

.construction-type .construction-slider .inner .text ul {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0;
    margin: 0;
    list-style: none;
}

.construction-type .construction-slider .inner .text li {
    margin: 0;
}

.construction-type .construction-slider .inner .text li a {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border: 1px solid #94a3b8;
    border-radius: 8px;
    font-size: 16px;
    line-height: 1.4;
    color: #282828;
    text-decoration: none;
    transition: border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease;
}

.construction-type .construction-slider .inner .text li a:hover {
    border-color: #1e3552;
    background-color: #1e3552;
    color: #ffffff;
}

@media only screen and (max-width: 768px) {
    .construction-type .construction-slider .inner .text li a {
        font-size: 14px;
    }
}
</style>
