<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Calc - Онлайн калькулятор рекламных конструкций</title>

    {{-- Vite для Vue компонентов (libs и app в @layer components через main.css) --}}
    @vite(['resources/js/pages/home.js'])
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

    {{-- Переинициализация cases-slider с lazy loading --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Уничтожаем старый экземпляр Swiper для cases-slider
            if (window.swiper2 && typeof window.swiper2.destroy === 'function') {
                window.swiper2.destroy(true, true);
            }

            // Создаём новый с lazy loading
            window.swiper2 = new Swiper(".cases-slider", {
                slidesPerView: "auto",
                spaceBetween: 12,
                navigation: {
                    nextEl: ".cases-block .next",
                    prevEl: ".cases-block .prev",
                },
                lazy: {
                    loadPrevNext: true,
                    loadPrevNextAmount: 2,
                    loadOnTransitionStart: true,
                },
                preloadImages: false,
                watchSlidesProgress: true,
            });
        });
    </script>
</body>
</html>
