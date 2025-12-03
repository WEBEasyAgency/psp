<template>
<header>
    <div class="container">
        <div class="header-inner">
            <div class="flex">
                <div class="logo-block">
                    <div class="logo"><a href="/"><img src="/img/dest/logo.svg" alt=""></a></div>
                    <div class="city">
                        <a href="#">
                            <span>Самара</span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 10L12 14L8 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="catalog-btn">
                    <a href="#" class="btn btn-catalog" :class="{ active: isCatalogOpen }" @click.prevent="toggleCatalog">
							<span class="b-catalog">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M5 17H13M5 12H19M5 7H13" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
                        Каталог
                        <span class="close">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M16 16L12 12M12 12L8 8M12 12L16 8M12 12L8 16" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
                    </a>
                    <div class="catalog-menu" :class="{ active: isCatalogOpen }">
                        <ul>
                            <li class="parent">
                                <a href="#">Вывески</a>
                                <div class="child">
                                    <ul>
                                        <li><a href="/product/156">Вывеска из ПВХ-пластика</a></li>
                                        <li><a href="/product/158">Вывеска из алюминиевого композита</a></li>
                                        <li><a href="/product/157">Вывеска из оргстекла</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="parent">
                                <a href="#">Объемные буквы</a>
                                <div class="child">
                                    <ul>
                                        <li class="li"><a href="/product/146">С бортом из алюминия</a></li>
                                        <li class="li"><a href="/product/155">Со световым бортом</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="parent">
                                <a href="#">Стенды</a>
                                <div class="child">
                                    <ul>
                                        <li><a href="/product/151">Стенд из пластика с карманами</a></li>
                                        <li><a href="#">Стенд из алюминиевого композита</a></li>
                                        <li><a href="#">Стенд из оргстекла</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="parent">
                                <a href="#">Таблички</a>
                                <div class="child">
                                    <ul>
                                        <li><a href="/product/159">Пластиковые таблички</a></li>
                                        <li><a href="/product/160">Акриловые таблички</a></li>
                                        <li><a href="/product/161">Таблички из композита</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="parent">
                                <a href="#">Баннеры</a>
                                <div class="child">
                                    <ul>
                                        <li class="li"><a href="#">Баннер с люверсами</a></li>
                                        <li class="li"><a href="#">Баннер на раме</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="#">Найлейки</a></li>
                            <li><a href="#">Флаги</a></li>
                            <li><a href="#">Роллапы</a></li>
                            <li><a href="#">Виндеры</a></li>
                            <li><a href="#">Режим работы</a></li>
                        </ul>
                    </div>
                    <div class="popup-bg" :class="{ active: isCatalogOpen }" @click="closeCatalog"></div>
                </div>
                <div class="menu">
                    <nav>
                        <ul>
                            <li><a href="/product/146">Объемные буквы</a></li>
                            <li><a href="/product/156">Вывески</a></li>
                            <li><a href="/product/151">Стенды</a></li>
                            <li><a href="/product/154">Наклейки</a></li>
                            <li><a href="/product/159">Таблички</a></li>
                            <li><a href="#">Баннеры</a></li>
                            <li class="more-btn">
                                <a href="#" :class="{ active: isMoreMenuOpen }" @click.prevent="toggleMoreMenu">
                                    Еще
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 12C17 12.5523 17.4477 13 18 13C18.5523 13 19 12.5523 19 12C19 11.4477 18.5523 11 18 11C17.4477 11 17 11.4477 17 12Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5 12C5 12.5523 5.44772 13 6 13C6.55228 13 7 12.5523 7 12C7 11.4477 6.55228 11 6 11C5.44772 11 5 11.4477 5 12Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <ul class="more" :class="{ active: isMoreMenuOpen }">
                            <li><a href="#">Режим работы</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="phone">
                    <a href="tel:+78462113710">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.91872 3.54768C7.66561 2.91492 7.05276 2.5 6.37126 2.5H4.07895C3.20692 2.5 2.5 3.20675 2.5 4.07878C2.5 11.491 8.50898 17.5 15.9212 17.5C16.7933 17.5 17.5 16.793 17.5 15.921L17.5004 13.6283C17.5004 12.9468 17.0856 12.334 16.4528 12.0809L14.2558 11.2024C13.6874 10.9751 13.0402 11.0774 12.57 11.4693L12.0029 11.9422C11.3407 12.4941 10.3664 12.4502 9.75683 11.8407L8.16018 10.2425C7.55065 9.63302 7.50561 8.65945 8.05745 7.99724L8.53027 7.43025C8.92218 6.95996 9.02541 6.31263 8.79805 5.74424L7.91872 3.54768Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +7 (846) 211-3710
                    </a>
                </div>
                <div class="icons">
                    <div class="item account">
                        <a href="#" @click.prevent="handleAccountClick">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.8337 21.5C19.8337 18.2783 17.222 15.6667 14.0003 15.6667C10.7787 15.6667 8.16699 18.2783 8.16699 21.5M14.0003 13.1667C12.1594 13.1667 10.667 11.6743 10.667 9.83333C10.667 7.99238 12.1594 6.5 14.0003 6.5C15.8413 6.5 17.3337 7.99238 17.3337 9.83333C17.3337 11.6743 15.8413 13.1667 14.0003 13.1667Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                    <div class="item search">
                        <a href="#" @click.prevent="handleSearchClick">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 16.5L21.5 21.5M12.3333 18.1667C9.11167 18.1667 6.5 15.555 6.5 12.3333C6.5 9.11167 9.11167 6.5 12.3333 6.5C15.555 6.5 18.1667 9.11167 18.1667 12.3333C18.1667 15.555 15.555 18.1667 12.3333 18.1667Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                    <div class="item mini-cart">
                        <a href="#" @click.prevent="handleCartClick">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.5 6.5H6.72362C7.11844 6.5 7.31619 6.5 7.47722 6.57123C7.6192 6.63403 7.74059 6.73515 7.82812 6.86336C7.92724 7.00855 7.96304 7.20241 8.03448 7.58939L9.83337 17.3334L18.5182 17.3333C18.8958 17.3333 19.0853 17.3333 19.2417 17.2666C19.3799 17.2077 19.4991 17.1122 19.5876 16.9908C19.6877 16.8535 19.7301 16.6697 19.8148 16.3026L19.8154 16.3L21.1231 10.6333L21.1234 10.6321C21.2519 10.0751 21.3164 9.79596 21.2456 9.57699C21.1835 9.38485 21.0529 9.22195 20.8797 9.11827C20.682 9 20.3965 9 19.8239 9H8.58333M19 21.5C18.5398 21.5 18.1667 21.1269 18.1667 20.6667C18.1667 20.2064 18.5398 19.8333 19 19.8333C19.4602 19.8333 19.8333 20.2064 19.8333 20.6667C19.8333 21.1269 19.4602 21.5 19 21.5ZM10.6667 21.5C10.2064 21.5 9.83333 21.1269 9.83333 20.6667C9.83333 20.2064 10.2064 19.8333 10.6667 19.8333C11.1269 19.8333 11.5 20.2064 11.5 20.6667C11.5 21.1269 11.1269 21.5 10.6667 21.5Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="burger" :class="{ active: isBurgerOpen }" @click="toggleBurgerMenu">
                    <span class="normal" v-show="!isBurgerOpen">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 5.67578C3 5.26157 3.33579 4.92578 3.75 4.92578H20.25C20.6642 4.92578 21 5.26157 21 5.67578C21 6.08999 20.6642 6.42578 20.25 6.42578H3.75C3.33579 6.42578 3 6.08999 3 5.67578Z" fill="#2C619D"/>
                            <path d="M3 11.6758C3 11.2616 3.33579 10.9258 3.75 10.9258H20.25C20.6642 10.9258 21 11.2616 21 11.6758C21 12.09 20.6642 12.4258 20.25 12.4258H3.75C3.33579 12.4258 3 12.09 3 11.6758Z" fill="#2C619D"/>
                            <path d="M3 18.6758C3 18.2616 3.33579 17.9258 3.75 17.9258H20.25C20.6642 17.9258 21 18.2616 21 18.6758C21 19.09 20.6642 19.4258 20.25 19.4258H3.75C3.33579 19.4258 3 19.09 3 18.6758Z" fill="#2C619D"/>
                        </svg>
                    </span>
                    <span class="close" v-show="isBurgerOpen">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.46967 5.45611C5.76256 5.16322 6.23744 5.16322 6.53033 5.45611L18.1976 17.1234C18.4905 17.4163 18.4905 17.8911 18.1976 18.184C17.9047 18.4769 17.4298 18.4769 17.1369 18.184L5.46967 6.51677C5.17678 6.22388 5.17678 5.749 5.46967 5.45611Z" fill="white"/>
                            <path d="M5.53033 18.4561C5.23744 18.1632 5.23744 17.6883 5.53033 17.3955L17.1976 5.72819C17.4905 5.4353 17.9654 5.4353 18.2583 5.72819C18.5511 6.02108 18.5511 6.49596 18.2583 6.78885L6.59099 18.4561C6.2981 18.749 5.82322 18.749 5.53033 18.4561Z" fill="white"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Reactive state
const isCatalogOpen = ref(false)
const isMoreMenuOpen = ref(false)
const isBurgerOpen = ref(false)

// Catalog menu toggle
const toggleCatalog = () => {
  isCatalogOpen.value = !isCatalogOpen.value
}

const closeCatalog = () => {
  isCatalogOpen.value = false
}

// More menu toggle
const toggleMoreMenu = () => {
  isMoreMenuOpen.value = !isMoreMenuOpen.value
}

// Burger menu toggle
const toggleBurgerMenu = () => {
  isBurgerOpen.value = !isBurgerOpen.value
}

// Close menu on click outside
const handleClickOutside = (e) => {
  // Close "More" menu
  const moreMenu = document.querySelector('.menu .more')
  const moreBtn = document.querySelector('.menu .more-btn a')
  if (moreMenu && moreBtn && !moreMenu.contains(e.target) && !moreBtn.contains(e.target)) {
    isMoreMenuOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('mouseup', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('mouseup', handleClickOutside)
})

// Icon handlers (placeholder for future implementation)
const handleAccountClick = () => {
  console.log('Account clicked')
  // TODO: Open account modal or navigate to account page
}

const handleSearchClick = () => {
  console.log('Search clicked')
  // TODO: Open search modal or navigate to search page
}

const handleCartClick = () => {
  console.log('Cart clicked')
  // TODO: Open cart modal or navigate to cart page
}
</script>

<style scoped>
/* Стили можно добавить здесь, если они специфичны для этого компонента */
</style>
