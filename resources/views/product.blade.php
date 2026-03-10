<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <x-layout.favicon/>
    <x-layout.metrika/>

    {{-- Load CSS from manifest --}}
    @vite('resources/css/product.css')

    {{-- Skeleton Loader Styles (inline для мгновенной загрузки) --}}
    <style>
        /* Page Skeleton Overlay */
        #page-skeleton {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #F6F6F6;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.3s ease-out;
            overflow-y: auto;
        }

        #page-skeleton.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* App visible for FCP; skeleton covers visually */
        #app {
            transition: opacity 0.3s ease-in;
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
            border-radius: 8px;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Header Skeleton */
        .skeleton-header {
            background: #ffffff;
            padding: 16px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .skeleton-header-inner {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .skeleton-logo {
            width: 120px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 8px;
        }

        .skeleton-nav {
            display: none;
            gap: 20px;
        }

        @media (min-width: 768px) {
            .skeleton-nav { display: flex; }
        }

        .skeleton-nav-item {
            width: 100px;
            height: 24px;
            background: #f0f0f0;
            border-radius: 4px;
        }

        .skeleton-icons {
            display: flex;
            gap: 12px;
        }

        .skeleton-icon {
            width: 28px;
            height: 28px;
            background: #f0f0f0;
            border-radius: 50%;
        }

        /* Breadcrumbs Skeleton */
        .skeleton-breadcrumbs {
            padding: 20px 0;
            max-width: 1440px;
            margin: 0 auto;
            padding: 20px;
        }

        .skeleton-breadcrumb {
            width: 200px;
            height: 20px;
        }

        /* Calculator Skeleton */
        .skeleton-calculator {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px 48px;
        }

        .skeleton-title {
            height: 36px;
            margin-bottom: 24px;
            max-width: 500px;
        }

        .skeleton-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
        }

        @media (min-width: 1024px) {
            .skeleton-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .skeleton-left, .skeleton-right {
            display: flex;
            flex-direction: column;
        }

        .skeleton-gallery {
            width: 100%;
            padding-bottom: 75%;
            position: relative;
            margin-bottom: 20px;
        }

        .skeleton-text {
            height: 20px;
            margin-bottom: 12px;
        }

        .skeleton-input {
            height: 56px;
            margin-bottom: 16px;
        }

        .skeleton-button-group {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .skeleton-button {
            height: 48px;
            flex: 1;
        }

        .skeleton-toggle {
            height: 40px;
            margin-bottom: 12px;
        }

        .skeleton-result {
            height: 80px;
            margin-bottom: 16px;
        }

        .skeleton-action-btn {
            height: 56px;
        }

        /* Sections Skeleton */
        .skeleton-section {
            max-width: 1440px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .skeleton-section-title {
            height: 32px;
            max-width: 400px;
            margin-bottom: 24px;
        }

        .skeleton-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .skeleton-card {
            height: 200px;
        }

        /* Footer Skeleton */
        .skeleton-footer {
            background: #2C4A6B;
            margin-top: 60px;
            padding: 40px 20px;
        }

        .skeleton-footer-content {
            max-width: 1440px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .skeleton-footer-block {
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body>
{{-- Full Page Skeleton Overlay --}}
<div id="page-skeleton">
    <!-- Header Skeleton -->
    <div class="skeleton-header">
        <div class="skeleton-header-inner">
            <div class="skeleton-logo"></div>
            <div class="skeleton-nav">
                <div class="skeleton-nav-item"></div>
                <div class="skeleton-nav-item"></div>
                <div class="skeleton-nav-item"></div>
                <div class="skeleton-nav-item"></div>
            </div>
            <div class="skeleton-icons">
                <div class="skeleton-icon"></div>
                <div class="skeleton-icon"></div>
                <div class="skeleton-icon"></div>
            </div>
        </div>
    </div>

    <!-- Breadcrumbs Skeleton -->
    <div class="skeleton-breadcrumbs">
        <div class="skeleton skeleton-breadcrumb"></div>
    </div>

    <!-- Calculator Skeleton -->
    <div class="skeleton-calculator">
        <div class="skeleton skeleton-title"></div>

        <div class="skeleton-content">
            <!-- Left Column: Gallery -->
            <div class="skeleton-left">
                <div class="skeleton skeleton-gallery"></div>
                <div class="skeleton skeleton-text" style="width: 100%;"></div>
                <div class="skeleton skeleton-text" style="width: 90%;"></div>
                <div class="skeleton skeleton-text" style="width: 85%;"></div>
            </div>

            <!-- Right Column: Parameters -->
            <div class="skeleton-right">
                <div class="skeleton skeleton-input"></div>
                <div class="skeleton skeleton-input"></div>

                <div class="skeleton-button-group">
                    <div class="skeleton skeleton-button"></div>
                    <div class="skeleton skeleton-button"></div>
                    <div class="skeleton skeleton-button"></div>
                </div>

                <div class="skeleton skeleton-toggle"></div>
                <div class="skeleton skeleton-toggle"></div>
                <div class="skeleton skeleton-toggle"></div>
                <div class="skeleton skeleton-toggle"></div>

                <div class="skeleton skeleton-input"></div>
                <div class="skeleton skeleton-result"></div>
                <div class="skeleton skeleton-action-btn"></div>
            </div>
        </div>
    </div>

    <!-- Sections Skeleton -->
    <div class="skeleton-section">
        <div class="skeleton skeleton-section-title"></div>
        <div class="skeleton-cards">
            <div class="skeleton skeleton-card"></div>
            <div class="skeleton skeleton-card"></div>
            <div class="skeleton skeleton-card"></div>
            <div class="skeleton skeleton-card"></div>
        </div>
    </div>

    <div class="skeleton-section">
        <div class="skeleton skeleton-section-title"></div>
        <div style="height: 250px;" class="skeleton"></div>
    </div>

    <div class="skeleton-section">
        <div class="skeleton skeleton-section-title"></div>
        <div class="skeleton-cards">
            <div class="skeleton skeleton-card"></div>
            <div class="skeleton skeleton-card"></div>
            <div class="skeleton skeleton-card"></div>
        </div>
    </div>

    <!-- Footer Skeleton -->
    <div class="skeleton-footer">
        <div class="skeleton-footer-content">
            <div class="skeleton-footer-block"></div>
            <div class="skeleton-footer-block"></div>
            <div class="skeleton-footer-block"></div>
            <div class="skeleton-footer-block"></div>
        </div>
    </div>
</div>

<div id="app">

    <x-layout.header/>


    <main class="main-page">
        <div class="bg-[#F6F6F6]">
            <section class="breadcrumbs pt-[20px] xl:pt-[32px] pl-4">
                <div class="container">
                    <ul>
                        <li class="main">
                            <a href="/">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.33301 9.54425V14.0009C3.33301 14.9343 3.33301 15.4013 3.51466 15.7579C3.67445 16.0715 3.92924 16.3262 4.24284 16.486C4.59901 16.6675 5.0655 16.6675 5.9971 16.6675H14.0022C14.9338 16.6675 15.3997 16.6675 15.7558 16.486C16.0694 16.3262 16.3251 16.0715 16.4849 15.7579C16.6663 15.4017 16.6663 14.9354 16.6663 14.0038V9.54425C16.6663 9.09897 16.666 8.87621 16.6118 8.66901C16.5638 8.48539 16.4851 8.31162 16.3785 8.1546C16.2582 7.97741 16.091 7.83047 15.7559 7.53725L11.7559 4.03725C11.1337 3.49285 10.8226 3.22079 10.4725 3.11725C10.164 3.02602 9.83518 3.02602 9.52669 3.11725C9.17685 3.22071 8.86621 3.49251 8.24498 4.0361L4.24365 7.53726C3.90854 7.83048 3.74138 7.97741 3.62109 8.1546C3.5145 8.31162 3.43513 8.48539 3.38715 8.66901C3.33301 8.87621 3.33301 9.09897 3.33301 9.54425Z"
                                        stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </li>
                        <li class="separator">/</li>
                        <li>{{ $title }}</li>
                    </ul>
                </div>
            </section>
            <div class="mb-12">
                <div class="container">
                    <calc-{{ $calculatorId }}
                        :initial-images
                    ='@json($images)'
                    :description='@json($description)'
                    ></calc-{{ $calculatorId }}>
                </div>
            </div>
            <x-product.technology-advantages :advantages="$advantages"/>
            <faq :items='@json($faq)'></faq>
            <x-product.seo-block :seo="$seo"/>
            <installation-cases :calc-id="{{ $calculatorId }}"></installation-cases>
        </div>

        <div class="bg-white">
            <x-home.feedback-slider/>
        </div>
    </main>
    <x-product.footer/>
    <x-layout.popup-menu/>
</div>

{{-- Библиотеки (Swiper и др.) --}}
<script src="/layout/js/libs.min.js"></script>
{{-- app.min.js: инициализация Swiper слайдеров и другие обработчики --}}
<script src="/layout/js/app.min.js"></script>

{{-- Page Skeleton Loader Script --}}
<script>
    (function() {
        let skeletonHidden = false;

        function hidePageSkeleton() {
            if (skeletonHidden) return;

            const skeleton = document.getElementById('page-skeleton');
            const app = document.getElementById('app');

            if (!skeleton || !app) return;

            // Проверяем, что Vue приложение отрендерилось
            const header = app.querySelector('header');
            const calculator = app.querySelector('.calculator');

            if (!header || !calculator) return;

            skeletonHidden = true;

            // Скрываем skeleton
            skeleton.classList.add('hidden');

            // Полностью удаляем skeleton из DOM после анимации
            setTimeout(function() {
                if (skeleton.parentNode) {
                    skeleton.parentNode.removeChild(skeleton);
                }
            }, 300);
        }

        // Наблюдаем за появлением Vue-приложения в DOM
        function observeVueMount() {
            const app = document.getElementById('app');
            if (!app) return;

            const observer = new MutationObserver(function(mutations) {
                const header = app.querySelector('header');
                const calculator = app.querySelector('.calculator');

                if (header && calculator) {
                    // Небольшая задержка для завершения рендера всех компонентов
                    setTimeout(hidePageSkeleton, 150);
                    observer.disconnect();
                }
            });

            observer.observe(app, {
                childList: true,
                subtree: true
            });
        }

        // Запускаем наблюдатель когда DOM готов
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', observeVueMount);
        } else {
            observeVueMount();
        }

        // Fallback: скрываем skeleton через 3 секунды в любом случае
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (!skeletonHidden) {
                    console.warn('Page skeleton fallback triggered - hiding after timeout');
                    hidePageSkeleton();
                }
            }, 3000);
        });

        // Экспортируем функцию для ручного вызова
        window.hidePageSkeleton = hidePageSkeleton;
    })();
</script>

{{-- Vue приложение --}}
@vite('resources/js/pages/product-' . $calculatorId . '.js')
</body>
</html>
