<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Calc - Онлайн калькулятор рекламных конструкций</title>

    <x-layout.favicon />

    {{-- Load JS from manifest --}}
    @vite('resources/js/pages/home.js')
    <link rel="stylesheet" href="/layout/css/libs.min.css">
    <link rel="stylesheet" href="/layout/css/app.min.css">

</head>
<body>
    <div class="main-page">
        <!-- Header Blade Component -->
        <x-layout.header />

        <!-- PopupMenu Blade Component -->
        <x-layout.popup-menu />

        <main>
            <!-- Static Blade Components -->
            <x-home.hero-section />

            <!-- Vue Slider Component -->
            <div id="construction-slider-app"></div>

            <!-- Static Blade Components -->
            <x-home.services-advantages />
            <x-home.order-instruction />

            <!-- Blade Slider Components -->
            <x-home.cases-block />
            <x-home.feedback-slider />
            <x-home.clients-slider />
        </main>

        <!-- Footer Blade Component -->
        <x-product.footer />
    </div>

    {{-- Библиотеки (Swiper и др.) --}}
    <script src="/layout/js/libs.min.js"></script>
    {{-- app.min.js: инициализация Swiper слайдеров и другие обработчики --}}
    <script src="/layout/js/app.min.js"></script>
</body>
</html>
