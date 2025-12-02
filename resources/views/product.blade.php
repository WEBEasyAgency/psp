<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- CSS из dev (импортирует layout CSS + Tailwind) --}}
    @vite('resources/css/product.css')
</head>
<body>
    <div id="app">
        <app-header></app-header>
        <main class="main-page bg-[#F6F6F6]">
            <div class="pt-32 mb-12">
                <div class="container">
                    <calc-{{ $calculatorId }}
                        :initial-images='@json($images)'
                    ></calc-{{ $calculatorId }}>
                </div>
            </div>
            <technology-advantages></technology-advantages>
            <faq></faq>
            <seo-block></seo-block>
            <installation-cases></installation-cases>
            <feedback></feedback>
        </main>
        <app-footer></app-footer>
        <popup-menu></popup-menu>
    </div>

    {{-- Скрипты из layout --}}
    <script src="{{ asset('js/libs.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>

    {{-- Vue приложение --}}
    @vite('resources/js/pages/product-' . $calculatorId . '.js')
</body>
</html>
