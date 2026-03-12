<footer>
    <div class="container">
        <div class="footer-top">
            <div class="flex">
                <div class="logo-block">
                    <div class="logo"><a href="/"><img src="/img/dest/footer-logo.svg" alt=""></a></div>
                    <div class="contacts">
                        <div class="item"><a href="tel:+78462129754">+7 (846) 212-97-54</a></div>
                        <div class="item"><a href="mailto:info@ra-psp.ru">info@ra-psp.ru</a></div>
                        <div class="item">г. Самара, ул. Революционная, 70г</div>
                    </div>
                </div>
                <div class="menu-block">
                    <div class="menu categories-menu">
                        <div class="caption">Категории</div>
                        <ul>
                            <li><a href="/product/156">Вывески</a></li>
                            <li><a href="/product/146">Объемные буквы</a></li>
                            <li><a href="/product/151">Стенды</a></li>
                            <li><a href="/product/159">Таблички</a></li>
                            <li><a href="/product/162">Баннеры</a></li>
                            <li><a href="/product/164">Плакаты</a></li>
                            <li><a href="/product/169">Панели</a></li>
                            <li><a href="/product/172">Режим работы</a></li>
                            <li><a href="/product/154">Наклейки</a></li>
                            <li><a href="/product/167">Аппликация на стекло</a></li>
                            <li><a href="/product/168">Световой короб</a></li>
                        </ul>
                    </div>
                    <div class="menu contacts-menu">
                        <div class="caption">Контакты</div>
                        <ul>
                            <li><a href="tel:+78462129754">+7 (846) 212-97-54</a></li>
                            <li><a href="mailto:info@ra-psp.ru">info@ra-psp.ru</a></li>
                            <li>г. Самара, ул. Революционная, 70г</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bot">
            <div class="flex">
                <div class="descriptor">Платформа онлайн расчета рекламных конструкций</div>
                <div class="footer-bot-right">
                    <div class="copy">© 2025–2026 — Copyright PSP online</div>
                    <div class="policy">
                        <a href="https://ra-psp.ru/policy/" target="_blank">Политика конфиденциальности</a>
                        <a href="https://ra-psp.ru/politika-v-otnoshenii-obrabotki-personalnyh-dannyh/" target="_blank">Политика
                            обработки персональных данных</a>
                    </div>

                </div>
                <div class="to-top" id="toTopBtn" style="display: none;">
                    <a href="#">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17V7M12 7L8 11M12 7L16 11" stroke="#94A3B8" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</footer>

<style>
    .to-top {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        cursor: pointer;
    }

    .to-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .to-top a {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .to-top svg {
        transition: transform 0.2s ease;
    }

    .to-top:hover svg {
        transform: translateY(-2px);
    }

    @media only screen and (max-width: 600px) {
        footer .footer-bot .footer-bot-right {
            align-items: flex-start;
        }
    }

    .policy {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toTopBtn = document.getElementById('toTopBtn');
        const toTopLink = document.getElementById('toTopLink');

        // Show/hide button on scroll
        function handleScroll() {
            if (window.scrollY > 300) {
                toTopBtn.classList.add('visible');
            } else {
                toTopBtn.classList.remove('visible');
            }
        }

        // Scroll to top on click
        toTopLink.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Listen to scroll events
        window.addEventListener('scroll', handleScroll);

        // Initial check
        handleScroll();
    });
</script>
