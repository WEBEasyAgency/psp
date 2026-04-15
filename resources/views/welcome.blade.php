<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Calc - Онлайн калькулятор рекламных конструкций</title>

    <x-layout.favicon />
    <x-layout.metrika/>

    {{-- Load JS from manifest --}}
    @vite('resources/js/pages/home.js')
    <link rel="stylesheet" href="/layout/css/libs.min.css">
    <link rel="stylesheet" href="/layout/css/app.min.css">

</head>
<body>
    <div class="main-page  123">
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
    {{-- Пересоздаём Blade-слайдеры с поддержкой mousewheel (тачпад/трекпад) --}}
    <script>
    $(document).ready(function() {
        setTimeout(function() {
            var mw = { forceToAxis: true, releaseOnEdges: true };
            var fm = { enabled: true, sticky: false };
            var reinit = function(selector, nav) {
                var el = document.querySelector(selector);
                if (!el || !el.swiper) return;
                var sw = el.swiper;
                var opts = {
                    slidesPerView: sw.params.slidesPerView,
                    spaceBetween: sw.params.spaceBetween,
                    mousewheel: mw,
                    freeMode: fm,
                    navigation: nav
                };
                sw.destroy(true, true);
                new Swiper(selector, opts);
            };
            reinit('.cases-slider', { nextEl: '.cases-block .next', prevEl: '.cases-block .prev' });
            reinit('.feedback-slider', { nextEl: '.feedback-block .next', prevEl: '.feedback-block .prev' });
            reinit('.clients-slider', { nextEl: '.clients-block .next', prevEl: '.clients-block .prev' });
        }, 0);

        $('.cases-type-list .item').on('click', function() {
            var tab = $(this).attr('href');
            var $tab = $('.cases-block').find('.tab' + tab);
            $tab.promise().done(function() {
                var el = $tab.find('.cases-slider')[0];
                if (el && el.swiper) {
                    el.swiper.update();
                    el.swiper.slideTo(0, 0);
                }
            });
        });
    });
    </script>
</body>
</html>
