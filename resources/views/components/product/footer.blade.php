<footer>
    <div class="container">
        <div class="footer-top">
            <div class="flex">
                <div class="logo-block">
                    <div class="logo"><a href="#"><img src="/img/dest/footer-logo.svg" alt=""></a></div>
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
                            <li><a href="#">Постеры</a></li>
                            <li><a href="#">Режим работы</a></li>
                            <li><a href="#">Объемные буквы</a></li>
                            <li><a href="#">Таблички</a></li>
                            <li><a href="#">Флаги</a></li>
                            <li><a href="#">Баннер</a></li>
                            <li><a href="#">Роллапы</a></li>
                            <li><a href="#">Наклейки</a></li>
                            <li><a href="#">Виндеры</a></li>
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
                    <div class="menu contacts-menu">
                        <div class="caption">Контакты</div>
                        <ul>
                            <li><a href="tel:+74951288876">+7 (495) 128-88-76</a></li>
                            <li><a href="mailto:order@ra-psp.ru">order@ra-psp.ru</a></li>
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
                    <div class="copy">© 2025 — Copyright PSP online</div>
                    <div class="policy"><a href="#" target="_blank">Политика конфиденциальности</a></div>
                </div>
                <div class="to-top" id="toTopBtn">
                    <a href="#" id="toTopLink">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17V7M12 7L8 11M12 7L16 11" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    toTopLink.addEventListener('click', function(e) {
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
