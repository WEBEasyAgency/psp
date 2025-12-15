<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-layout.favicon/>

    {{-- Сторонние CSS --}}
    <link rel="stylesheet" href="/layout/css/libs.min.css">
    <link rel="stylesheet" href="/layout/css/app.min.css">
    @vite(['resources/js/pages/cart.js'])
</head>
<body class="gray-bg">
<!-- Header Blade Component -->
<x-layout.header/>
<main class="cart-page inner-page">
    <section class="breadcrumbs">
        <div class="container">
            <ul>
                <li class="main">
                    <a href="/">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.33301 9.54425V14.0009C3.33301 14.9343 3.33301 15.4013 3.51466 15.7579C3.67445 16.0715 3.92924 16.3262 4.24284 16.486C4.59901 16.6675 5.0655 16.6675 5.9971 16.6675H14.0022C14.9338 16.6675 15.3997 16.6675 15.7558 16.486C16.0694 16.3262 16.3251 16.0715 16.4849 15.7579C16.6663 15.4017 16.6663 14.9354 16.6663 14.0038V9.54425C16.6663 9.09897 16.666 8.87621 16.6118 8.66901C16.5638 8.48539 16.4851 8.31162 16.3785 8.1546C16.2582 7.97741 16.091 7.83047 15.7559 7.53725L11.7559 4.03725C11.1337 3.49285 10.8226 3.22079 10.4725 3.11725C10.164 3.02602 9.83518 3.02602 9.52669 3.11725C9.17685 3.22071 8.86621 3.49251 8.24498 4.0361L4.24365 7.53726C3.90854 7.83048 3.74138 7.97741 3.62109 8.1546C3.5145 8.31162 3.43513 8.48539 3.38715 8.66901C3.33301 8.87621 3.33301 9.09897 3.33301 9.54425Z"
                                stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </li>
                <li class="separator">/</li>
                <li>Корзина</li>
            </ul>
        </div>
    </section>
    <section class="cart-block">
        <div class="container">
            <div class="title">
                <h1>Корзина</h1>
            </div>
            {{-- Vue приложение корзины --}}
            <div id="cart-app"></div>

            {{-- Статичная вёрстка для референса (закомментирована) --}}
            {{-- <div class="cart-inner">
                <div class="grid">
                    <div class="product-list-block">
                        <div class="title-block">
                            <div class="cart-quantity">Товаров в корзине: 2 шт</div>
                            <div class="clear-cart">
                                <a href="#" class="clear">
                                    Очистить корзину
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 12L8.00001 8.00001M8.00001 8.00001L4 4M8.00001 8.00001L12 4M8.00001 8.00001L4 12"
                                            stroke="#334155" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="product-list">
                            <div class="product-item">
                                <div class="name-block flex">
                                    <div class="name">
                                        <a href="#" class="flex">
                                            <div class="img"><img src="img/dest/cart-img.jpg" alt=""></div>
                                            <div class="text">Объемные буквы</div>
                                        </a>
                                    </div>
                                    <a href="#" class="btn btn-change">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.33333 9.9987H14M2 9.9987H3.33333M3.33333 9.9987C3.33333 10.9192 4.07953 11.6654 5 11.6654C5.92047 11.6654 6.66667 10.9192 6.66667 9.9987C6.66667 9.07822 5.92047 8.33203 5 8.33203C4.07953 8.33203 3.33333 9.07822 3.33333 9.9987ZM13.3333 5.9987H14M2 5.9987H6.66667M11 7.66536C10.0795 7.66536 9.33333 6.91917 9.33333 5.9987C9.33333 5.07822 10.0795 4.33203 11 4.33203C11.9205 4.33203 12.6667 5.07822 12.6667 5.9987C12.6667 6.91917 11.9205 7.66536 11 7.66536Z"
                                                stroke="#F6F6F6" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                        Изменить
                                    </a>
                                    <a href="#" class="btn btn-delete">
                                        Удалить
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 12L8.00001 8.00001M8.00001 8.00001L4 4M8.00001 8.00001L12 4M8.00001 8.00001L4 12"
                                                stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="parameters-block grid">
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Кол-во букв</span><span
                                                class="val">4</span></div>
                                        <div class="item"><span class="caption">Высота, см</span><span
                                                class="val">30</span></div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Подсветка</span><span class="val">ECO (гарантия 1 год)</span>
                                        </div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Лицевая поверхность</span><span
                                                class="val">Белая</span></div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Основа для букв</span><span class="val">Не нужна</span>
                                        </div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Боковая поверхность</span><span
                                                class="val">Цветная</span></div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Дизайн</span><span class="val">Да</span>
                                        </div>
                                        <div class="item"><span class="caption">Монтаж</span><span
                                                class="val">Нет</span></div>
                                        <div class="item"><span class="caption">Доставка</span><span
                                                class="val">Нет</span></div>
                                    </div>
                                </div>
                                <div class="quantity-price flex">
                                    <div class="number-input__wrapper">
                                        <button type="button" class="number-input__button">
                                            <svg data-v-26cf65c2="" width="24" height="24" viewBox="0 0 24 24"
                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path data-v-26cf65c2="" d="M6 12H18" stroke="#334155"
                                                      stroke-width="1.5" stroke-linecap="round"
                                                      stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                        <div class="number-input__field-wrapper">
                                            <input type="number" class="number-input__field" value="1">
                                        </div>
                                        <button type="button" class="number-input__button">
                                            <svg data-v-26cf65c2="" width="24" height="24" viewBox="0 0 24 24"
                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path data-v-26cf65c2="" d="M6 12H12M12 12H18M12 12V18M12 12V6"
                                                      stroke="#334155" stroke-width="1.5" stroke-linecap="round"
                                                      stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="price"><span class="val">25 000</span><span class="currency">₽</span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-item">
                                <div class="name-block flex">
                                    <div class="name">
                                        <a href="#" class="flex">
                                            <div class="img"><img src="img/dest/cart-img.jpg" alt=""></div>
                                            <div class="text">Объемные буквы</div>
                                        </a>
                                    </div>
                                    <a href="#" class="btn btn-change">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.33333 9.9987H14M2 9.9987H3.33333M3.33333 9.9987C3.33333 10.9192 4.07953 11.6654 5 11.6654C5.92047 11.6654 6.66667 10.9192 6.66667 9.9987C6.66667 9.07822 5.92047 8.33203 5 8.33203C4.07953 8.33203 3.33333 9.07822 3.33333 9.9987ZM13.3333 5.9987H14M2 5.9987H6.66667M11 7.66536C10.0795 7.66536 9.33333 6.91917 9.33333 5.9987C9.33333 5.07822 10.0795 4.33203 11 4.33203C11.9205 4.33203 12.6667 5.07822 12.6667 5.9987C12.6667 6.91917 11.9205 7.66536 11 7.66536Z"
                                                stroke="#F6F6F6" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                        Изменить
                                    </a>
                                    <a href="#" class="btn btn-delete">
                                        Удалить
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 12L8.00001 8.00001M8.00001 8.00001L4 4M8.00001 8.00001L12 4M8.00001 8.00001L4 12"
                                                stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="parameters-block grid">
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Кол-во букв</span><span
                                                class="val">4</span></div>
                                        <div class="item"><span class="caption">Высота, см</span><span
                                                class="val">30</span></div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Подсветка</span><span class="val">ECO (гарантия 1 год)</span>
                                        </div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Лицевая поверхность</span><span
                                                class="val">Белая</span></div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Основа для букв</span><span class="val">Не нужна</span>
                                        </div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Боковая поверхность</span><span
                                                class="val">Цветная</span></div>
                                    </div>
                                    <div class="parameter">
                                        <div class="item"><span class="caption">Дизайн</span><span class="val">Да</span>
                                        </div>
                                        <div class="item"><span class="caption">Монтаж</span><span
                                                class="val">Нет</span></div>
                                        <div class="item"><span class="caption">Доставка</span><span
                                                class="val">Нет</span></div>
                                    </div>
                                </div>
                                <div class="quantity-price flex">
                                    <div class="number-input__wrapper">
                                        <button type="button" class="number-input__button">
                                            <svg data-v-26cf65c2="" width="24" height="24" viewBox="0 0 24 24"
                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path data-v-26cf65c2="" d="M6 12H18" stroke="#334155"
                                                      stroke-width="1.5" stroke-linecap="round"
                                                      stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                        <div class="number-input__field-wrapper">
                                            <input type="number" class="number-input__field" value="1">
                                        </div>
                                        <button type="button" class="number-input__button">
                                            <svg data-v-26cf65c2="" width="24" height="24" viewBox="0 0 24 24"
                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path data-v-26cf65c2="" d="M6 12H12M12 12H18M12 12V18M12 12V6"
                                                      stroke="#334155" stroke-width="1.5" stroke-linecap="round"
                                                      stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="price"><span class="val">25 000</span><span class="currency">₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-area">
                        <div class="inner">
                            <div class="full-price">
                                <div class="caption">Итого</div>
                                <div class="val">75 000 ₽</div>
                            </div>
                            <div class="promo">
                                <div class="caption">Промокод на скидку</div>
                                <input type="text" name="promo" placeholder="Введите промокод"/>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="name">Кол-во товаров</div>
                                    <div class="val">2</div>
                                </div>
                                <div class="item">
                                    <div class="name">Общая скидка</div>
                                    <div class="val">5 000 ₽</div>
                                </div>
                            </div>
                            <div class="btn-block"><a href="#" class="btn">Оформить заказ</a><a href="#"
                                                                                                class="btn btn-white">Продолжить
                                    покупки</a></div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
</main>
<x-product.footer/>
{{-- Библиотеки (Swiper и др.) --}}
<script src="/layout/js/libs.min.js"></script>
{{-- app.min.js: инициализация Swiper слайдеров и другие обработчики --}}
<script src="/layout/js/app.min.js"></script>
</body>
</html>
