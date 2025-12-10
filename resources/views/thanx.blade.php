<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Спасибо за заказ</title>

    <x-layout.favicon/>

    {{-- Сторонние CSS --}}
    <link rel="stylesheet" href="/layout/css/libs.min.css">
    <link rel="stylesheet" href="/layout/css/app.min.css">
</head>
<body class="gray-bg">
<!-- Header Vue Component -->
<x-layout.header/>

<main class="thanx-page inner-page">
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
                <li>Спасибо</li>
            </ul>
        </div>
    </section>
    <section class="thanx-block">
        <div class="container">
            <div class="thanx-container">
                <div class="img">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18.0007L15.364 24.3647L28.0905 11.6367" stroke="#2C619D" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="title">
                    <h1>Спасибо!</h1>
                </div>
                <div class="text">
                    @if($calc_id)
                        Заказ № {{ $calc_id }} создан, в ближайшее время менеджер свяжется с вами для уточнения
                        подробностей
                    @else
                        Заказ успешно создан, в ближайшее время менеджер свяжется с вами для уточнения подробностей
                    @endif
                </div>

                <a href="/" class="btn ">На главную</a>

            </div>
        </div>
    </section>
</main>

<!-- Footer Blade Component -->
<x-product.footer/>

{{-- Библиотеки (Swiper и др.) --}}
<script src="/layout/js/libs.min.js"></script>
{{-- app.min.js: инициализация Swiper слайдеров и другие обработчики --}}
<script src="/layout/js/app.min.js"></script>
<style>
    .thanx-block .btn {
        margin-top: 32px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }
</style>
</body>
</html>
