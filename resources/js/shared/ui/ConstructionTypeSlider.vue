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
    name: 'Вывески',
    image: '/img/dest/construction/1.png',
    links: [
      { text: 'из оргстекла', url: '/product/157' },
      { text: 'из ПВХ-пластика', url: '/product/156' },
      { text: 'из алюминиевого композита', url: '/product/158' }
    ]
  },
  {
    name: 'Объемные буквы',
    image: '/img/dest/construction/2.png',
    links: [
      { text: 'с бортом из алюминия', url: '/product/146' },
      { text: 'со световым бортом', url: '/product/155' }
    ]
  },
  {
    name: 'Наклейки',
    image: '/img/dest/construction/3.png',
    links: [
      { text: 'маленькие наклейки', url: '/product/154' },
      { text: 'аппликация на стекло', url: '/product/167' }
    ]
  },
  {
    name: 'Баннеры',
    image: '/img/dest/construction/4.png',
    links: [
      { text: 'баннер с люверсами', url: '/product/162' },
      { text: 'баннер на раме', url: '/product/163' }
    ]
  },
  {
    name: 'Режим работы',
    image: '/img/dest/construction/5.png',
    links: [
      { text: 'из акрила Премиум', url: '/product/172' },
      { text: 'пластиковый', url: '/product/173' },
      { text: 'наклейка на стекло', url: '/product/175' }
    ]
  },
  {
    name: 'Плакаты и постеры',
    image: '/img/dest/construction/6.png',
    links: [
      { text: 'плакат на постерной бумаге', url: '/product/164' },
      { text: 'плакат для лайтбоксов (бэклит)', url: '/product/166' }
    ]
  },
  {
    name: 'Таблички',
    image: '/img/dest/construction/7.png',
    links: [
      { text: 'пластиковые', url: '/product/159' },
      { text: 'акриловые', url: '/product/160' },
      { text: 'из композита', url: '/product/161' }
    ]
  },
  {
    name: 'Стенды',
    image: '/img/dest/construction/8.png',
    links: [
      { text: 'стенд из пластика с карманами', url: '/product/151' }
    ]
  },
  {
    name: 'Световые короба',
    image: '/img/dest/construction/9.png',
    links: [
      { text: 'световой короб (лайтбокс)', url: '/product/168' }
    ]
  },
  {
    name: 'Панель-кронштейны',
    image: '/img/dest/construction/10.png',
    links: [
      { text: 'круглый', url: '/product/169' },
      { text: 'прямоугольный', url: '/product/170' }
    ]
  },
  {
    name: 'Пластиковые буквы',
    image: '/img/dest/construction/11.png',
    links: [
      { text: 'пластиковые буквы', url: '/product/171' }
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
    height: 298px !important;
}

.construction-type .construction-slider .swiper-slide .inner,
.construction-type .construction-slider .swiper-slide.swiper-slide-active .inner {
    height: 298px !important;
    background-color: var(--light-gray) !important;
    transition: background-color 0.2s ease !important;
}

.construction-type .construction-slider .swiper-slide .inner:hover,
.construction-type .construction-slider .swiper-slide.swiper-slide-active .inner:hover {
    height: 298px !important;
    background-color: #E6EEF8 !important;
}

@media only screen and (max-width: 768px) {
    .construction-type .construction-slider .swiper-slide {
        height: 239px !important;
    }
    .construction-type .construction-slider .swiper-slide .inner,
    .construction-type .construction-slider .swiper-slide.swiper-slide-active .inner,
    .construction-type .construction-slider .swiper-slide .inner:hover,
    .construction-type .construction-slider .swiper-slide.swiper-slide-active .inner:hover {
        height: 100% !important;
    }
}

.construction-type .construction-slider .swiper-slide .inner .text {
    margin-top: auto;
}

.construction-type .construction-slider .swiper-slide .inner .text ul {
    display: flex !important;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 8px;
    padding: 0;
    margin: 0;
    list-style: none;
    width: 100%;
}

.construction-type .construction-slider .swiper-slide .inner .text li {
    margin: 0 !important;
    list-style: none;
}

.construction-type .construction-slider .swiper-slide .inner .text li a {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border: 1px solid #94a3b8;
    border-radius: 8px;
    font-size: 16px;
    line-height: 1.4;
    color: #282828;
    text-decoration: none;
    background-color: transparent;
    transition: border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease;
}

.construction-type .construction-slider .swiper-slide .inner .text li a:hover {
    border-color: transparent;
    background-color: #ffffff;
    color: #2C619D;
}

@media only screen and (max-width: 768px) {
    .construction-type .construction-slider .swiper-slide .inner .text li a {
        font-size: 14px;
    }
}
</style>
