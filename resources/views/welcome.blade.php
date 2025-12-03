<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Calc - Онлайн калькулятор рекламных конструкций</title>

    <link rel="stylesheet" href="/layout/css/libs.min.css">
    <link rel="stylesheet" href="/layout/css/app.min.css">

    {{-- Vite для Vue компонентов и CSS --}}
    @vite(['resources/js/pages/home.js'])
</head>
<body>
    <!-- Vue App Root -->
    <div id="app"></div>

    {{-- Библиотеки (Swiper и др.) --}}
    <script src="{{ asset('js/libs.min.js') }}"></script>
    {{-- app.min.js: обработчики отвязываются в Vue onMounted --}}
    <script src="{{ asset('js/app.min.js') }}"></script>
</body>
</html>
