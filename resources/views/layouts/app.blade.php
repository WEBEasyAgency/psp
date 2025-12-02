<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PSP - Производство рекламы')</title>

    {{-- Существующие стили из layout --}}
    <link rel="stylesheet" href="{{ asset('css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">

    {{-- Vite стили и скрипты --}}
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>
<body>
    <header>
        <div class="container">
            <div class="header-inner">
                <div class="flex">
                    <div class="logo-block">
                        <div class="logo"><a href="/"><img src="{{ asset('img/dest/logo.svg') }}" alt=""></a></div>
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
                        <a href="#" class="btn btn-catalog">
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
                        <div class="catalog-menu">
                            <ul>
                                <li class="parent">
                                    <a href="#">Объемные буквы</a>
                                    <ul class="child">
                                        <li><a href="/product/146">С бортом из алюминия</a></li>
                                        <li><a href="/product/155">Со световым бортом</a></li>
                                    </ul>
                                </li>
                                <li class="parent">
                                    <a href="#">Несветовые вывески</a>
                                    <ul class="child">
                                        <li class="li"><a href="/product/156">Пластиковые вывески</a></li>
                                        <li class="li"><a href="/product/157">Акриловые вывески</a></li>
                                        <li class="li"><a href="#">Вывески из алюминиевого композита (скоро)</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="popup-bg"></div>
                    </div>
                    <div class="menu">
                        <nav>
                            <ul>
                                <li><a href="#">Объемные буквы</a></li>
                                <li><a href="#">Стенды</a></li>
                                <li><a href="#">Таблички</a></li>
                                <li><a href="#">Наклейки</a></li>
                                <li><a href="#">Баннеры</a></li>
                                <li><a href="#">Флаги</a></li>
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
                            <a href="#">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.8337 21.5C19.8337 18.2783 17.222 15.6667 14.0003 15.6667C10.7787 15.6667 8.16699 18.2783 8.16699 21.5M14.0003 13.1667C12.1594 13.1667 10.667 11.6743 10.667 9.83333C10.667 7.99238 12.1594 6.5 14.0003 6.5C15.8413 6.5 17.3337 7.99238 17.3337 9.83333C17.3337 11.6743 15.8413 13.1667 14.0003 13.1667Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                        <div class="item search">
                            <a href="#">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.5 16.5L21.5 21.5M12.3333 18.1667C9.11167 18.1667 6.5 15.555 6.5 12.3333C6.5 9.11167 9.11167 6.5 12.3333 6.5C15.555 6.5 18.1667 9.11167 18.1667 12.3333C18.1667 15.555 15.555 18.1667 12.3333 18.1667Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                        <div class="item mini-cart">
                            <a href="#">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.5 6.5H6.72362C7.11844 6.5 7.31619 6.5 7.47722 6.57123C7.6192 6.63403 7.74059 6.73515 7.82812 6.86336C7.92724 7.00855 7.96304 7.20241 8.03448 7.58939L9.83337 17.3334L18.5182 17.3333C18.8958 17.3333 19.0853 17.3333 19.2417 17.2666C19.3799 17.2077 19.4991 17.1122 19.5876 16.9908C19.6877 16.8535 19.7301 16.6697 19.8148 16.3026L19.8154 16.3L21.1231 10.6333L21.1234 10.6321C21.2519 10.0751 21.3164 9.79596 21.2456 9.57699C21.1835 9.38485 21.0529 9.22195 20.8797 9.11827C20.682 9 20.3965 9 19.8239 9H8.58333M19 21.5C18.5398 21.5 18.1667 21.1269 18.1667 20.6667C18.1667 20.2064 18.5398 19.8333 19 19.8333C19.4602 19.8333 19.8333 20.2064 19.8333 20.6667C19.8333 21.1269 19.4602 21.5 19 21.5ZM10.6667 21.5C10.2064 21.5 9.83333 21.1269 9.83333 20.6667C9.83333 20.2064 10.2064 19.8333 10.6667 19.8333C11.1269 19.8333 11.5 20.2064 11.5 20.6667C11.5 21.1269 11.1269 21.5 10.6667 21.5Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-top">
                <div class="flex">
                    <div class="logo-block">
                        <div class="logo"><a href="/"><img src="{{ asset('img/dest/footer-logo.svg') }}" alt=""></a></div>
                        <div class="contacts">
                            <div class="item"><a href="tel:+74951288876">+7 (495) 128-88-76</a></div>
                            <div class="item"><a href="mailto:order@ra-psp.ru">order@ra-psp.ru</a></div>
                            <div class="item">г. Самара, ул. Революционная, 70г</div>
                        </div>
                    </div>
                    <div class="menu-block">
                        <div class="menu categories-menu">
                            <div class="caption">Категории</div>
                            <ul>
                                <li><a href="#">Несветовые вывески</a></li>
                                <li><a href="#">Объемные буквы</a></li>
                                <li><a href="#">Таблички</a></li>
                                <li><a href="#">Баннер</a></li>
                                <li><a href="#">Наклейки</a></li>
                            </ul>
                        </div>
                        <div class="menu">
                            <div class="caption">Покупателю</div>
                            <ul>
                                <li><a href="#">Личный кабинет</a></li>
                                <li><a href="#">Как заказывать</a></li>
                                <li><a href="#">Гарантия</a></li>
                                <li><a href="#">Доставка</a></li>
                                <li><a href="#">Контакты</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bot">
                <div class="flex">
                    <div class="descriptor">Платформа онлайн расчета рекламных конструкций</div>
                    <div class="footer-bot-right">
                        <div class="copy">© 2025 — Copyright PSP online</div>
                        <div class="policy"><a href="#" target="_blank">Политика конфиденциальности</a></div>
                    </div>
                    <div class="to-top">
                        <a href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 17V7M12 7L8 11M12 7L16 11" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Существующие скрипты из layout --}}
    <script src="{{ asset('js/libs.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
