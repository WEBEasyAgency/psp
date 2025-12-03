<template>
  <section class="top-block">
    <div class="container">
      <div class="top-img"><img src="/img/dest/top-text.svg" alt=""></div>
      <div class="text-block">
        <div class="title">
          Онлайн расчет <span>наружной рекламы</span>
        </div>
        <div class="line">
          <picture>
            <source media="(max-width: 1000px)" srcset="/img/dest/top-line-375.svg">
            <img src="/img/dest/top-line.svg" alt="">
          </picture>
        </div>
        <div class="feed-btn-block">
          <div class="feed-block">
            <div class="faces">
              <div class="img"><img src="/img/dest/top-faces.png" alt=""></div>
              <div class="feed-text">
                <div class="num">2500+</div>
                <div class="caption">довольных пользователей</div>
              </div>
            </div>
            <div class="stars-block">
              <div class="rating">
                <div class="star" v-for="n in 5" :key="n">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.33496 10.3363C2.02171 10.0466 2.19187 9.5229 2.61557 9.47267L8.61914 8.76058C8.79182 8.7401 8.94181 8.63166 9.01465 8.47376L11.5469 2.98397C11.7256 2.59654 12.2764 2.59646 12.4551 2.9839L14.9873 8.47365C15.0601 8.63155 15.2092 8.74028 15.3818 8.76075L21.3857 9.47267C21.8094 9.5229 21.9791 10.0468 21.6659 10.3364L17.2278 14.4414C17.1001 14.5595 17.0433 14.7352 17.0771 14.9058L18.255 20.8355C18.3382 21.2539 17.8928 21.5782 17.5205 21.3698L12.2451 18.4161C12.0934 18.3312 11.9091 18.3316 11.7573 18.4165L6.48144 21.369C6.10913 21.5774 5.66294 21.2539 5.74609 20.8354L6.92414 14.9061C6.95803 14.7356 6.90134 14.5594 6.77367 14.4414L2.33496 10.3363Z" fill="#F88B2C"/>
                  </svg>
                </div>
              </div>
              <div class="stars-num"><span>4,78</span> средний балл</div>
            </div>
          </div>
          <div class="btn-block">
            <div class="btn-text">Узнайте стоимость онлайн за 5 минут и без регистрации</div>
            <a href="#online-order" class="btn btn-white link">Как это работает</a>
            <a href="#" class="btn">Выбрать продукт</a>
          </div>
        </div>
      </div>
      <div class="img-block">
        <picture>
          <source media="(max-width: 1000px)" srcset="/img/dest/top-img-375.png">
          <img src="/img/dest/top-img.png" alt="">
        </picture>
      </div>
    </div>
  </section>
</template>

<script setup>
// Smooth scroll logic will be added when porting jQuery
</script>
