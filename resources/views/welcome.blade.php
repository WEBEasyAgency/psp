<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Calc - Онлайн калькулятор рекламных конструкций</title>
    @vite('resources/js/pages/welcome.js')
</head>
<body>
    <!-- Header Vue Component -->
    <div id="header-app"></div>

    <!-- Middle sections from layout -->
		<section class="top-block">
			<div class="container">
				<div class="top-img"><img src="img/dest/top-text.svg" alt=""></div>
				<div class="text-block">
					<div class="title">
						Онлайн расчет <span>наружной рекламы</span>
					</div>
					<div class="line">
						<picture>
							<source media="(max-width: 1000px)" srcset="img/dest/top-line-375.svg">
							<img src="img/dest/top-line.svg" alt="">
						</picture>
					</div>
					<div class="feed-btn-block">
						<div class="feed-block">
							<div class="faces">
								<div class="img"><img src="img/dest/top-faces.png" alt=""></div>
								<div class="feed-text">
									<div class="num">2500+</div>
									<div class="caption">довольных пользователей</div>
								</div>
							</div>
							<div class="stars-block">
								<div class="rating">
									<div class="star">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.33496 10.3363C2.02171 10.0466 2.19187 9.5229 2.61557 9.47267L8.61914 8.76058C8.79182 8.7401 8.94181 8.63166 9.01465 8.47376L11.5469 2.98397C11.7256 2.59654 12.2764 2.59646 12.4551 2.9839L14.9873 8.47365C15.0601 8.63155 15.2092 8.74028 15.3818 8.76075L21.3857 9.47267C21.8094 9.5229 21.9791 10.0468 21.6659 10.3364L17.2278 14.4414C17.1001 14.5595 17.0433 14.7352 17.0771 14.9058L18.255 20.8355C18.3382 21.2539 17.8928 21.5782 17.5205 21.3698L12.2451 18.4161C12.0934 18.3312 11.9091 18.3316 11.7573 18.4165L6.48144 21.369C6.10913 21.5774 5.66294 21.2539 5.74609 20.8354L6.92414 14.9061C6.95803 14.7356 6.90134 14.5594 6.77367 14.4414L2.33496 10.3363Z" fill="#F88B2C"/>
										</svg>
									</div>
									<div class="star">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.33496 10.3363C2.02171 10.0466 2.19187 9.5229 2.61557 9.47267L8.61914 8.76058C8.79182 8.7401 8.94181 8.63166 9.01465 8.47376L11.5469 2.98397C11.7256 2.59654 12.2764 2.59646 12.4551 2.9839L14.9873 8.47365C15.0601 8.63155 15.2092 8.74028 15.3818 8.76075L21.3857 9.47267C21.8094 9.5229 21.9791 10.0468 21.6659 10.3364L17.2278 14.4414C17.1001 14.5595 17.0433 14.7352 17.0771 14.9058L18.255 20.8355C18.3382 21.2539 17.8928 21.5782 17.5205 21.3698L12.2451 18.4161C12.0934 18.3312 11.9091 18.3316 11.7573 18.4165L6.48144 21.369C6.10913 21.5774 5.66294 21.2539 5.74609 20.8354L6.92414 14.9061C6.95803 14.7356 6.90134 14.5594 6.77367 14.4414L2.33496 10.3363Z" fill="#F88B2C"/>
										</svg>
									</div>
									<div class="star">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.33496 10.3363C2.02171 10.0466 2.19187 9.5229 2.61557 9.47267L8.61914 8.76058C8.79182 8.7401 8.94181 8.63166 9.01465 8.47376L11.5469 2.98397C11.7256 2.59654 12.2764 2.59646 12.4551 2.9839L14.9873 8.47365C15.0601 8.63155 15.2092 8.74028 15.3818 8.76075L21.3857 9.47267C21.8094 9.5229 21.9791 10.0468 21.6659 10.3364L17.2278 14.4414C17.1001 14.5595 17.0433 14.7352 17.0771 14.9058L18.255 20.8355C18.3382 21.2539 17.8928 21.5782 17.5205 21.3698L12.2451 18.4161C12.0934 18.3312 11.9091 18.3316 11.7573 18.4165L6.48144 21.369C6.10913 21.5774 5.66294 21.2539 5.74609 20.8354L6.92414 14.9061C6.95803 14.7356 6.90134 14.5594 6.77367 14.4414L2.33496 10.3363Z" fill="#F88B2C"/>
										</svg>
									</div>
									<div class="star">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.33496 10.3363C2.02171 10.0466 2.19187 9.5229 2.61557 9.47267L8.61914 8.76058C8.79182 8.7401 8.94181 8.63166 9.01465 8.47376L11.5469 2.98397C11.7256 2.59654 12.2764 2.59646 12.4551 2.9839L14.9873 8.47365C15.0601 8.63155 15.2092 8.74028 15.3818 8.76075L21.3857 9.47267C21.8094 9.5229 21.9791 10.0468 21.6659 10.3364L17.2278 14.4414C17.1001 14.5595 17.0433 14.7352 17.0771 14.9058L18.255 20.8355C18.3382 21.2539 17.8928 21.5782 17.5205 21.3698L12.2451 18.4161C12.0934 18.3312 11.9091 18.3316 11.7573 18.4165L6.48144 21.369C6.10913 21.5774 5.66294 21.2539 5.74609 20.8354L6.92414 14.9061C6.95803 14.7356 6.90134 14.5594 6.77367 14.4414L2.33496 10.3363Z" fill="#F88B2C"/>
										</svg>
									</div>
									<div class="star">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.33496 10.3363C2.02171 10.0466 2.19187 9.5229 2.61557 9.47267L8.61914 8.76058C8.79182 8.7401 8.94181 8.63166 9.01465 8.47376L11.5469 2.98397C11.7256 2.59654 12.2764 2.59646 12.4551 2.9839L14.9873 8.47365C15.0601 8.63155 15.2092 8.74028 15.3818 8.76075L21.3857 9.47267C21.8094 9.5229 21.9791 10.0468 21.6659 10.3364L17.2278 14.4414C17.1001 14.5595 17.0433 14.7352 17.0771 14.9058L18.255 20.8355C18.3382 21.2539 17.8928 21.5782 17.5205 21.3698L12.2451 18.4161C12.0934 18.3312 11.9091 18.3316 11.7573 18.4165L6.48144 21.369C6.10913 21.5774 5.66294 21.2539 5.74609 20.8354L6.92414 14.9061C6.95803 14.7356 6.90134 14.5594 6.77367 14.4414L2.33496 10.3363Z" fill="#F88B2C"/>
										</svg>
									</div>
								</div>
								<div class="stars-num"><span>4,78</span> средний балл</div>
							</div>
						</div>
						<div class="btn-block">
							<div class="btn-text">Узнайте стоимость онлайн за 5 минут и без регистрации</div>
							<a href="#" class="btn btn-white">Как это работает</a><a href="#" class="btn">Выбрать продукт</a></div>
					</div>
				</div>
				<div class="img-block">
					<picture>
						<source media="(max-width: 1000px)" srcset="img/dest/top-img-375.png">
						<img src="img/dest/top-img.png" alt="">
					</picture>
				</div>
			</div>
		</section>
		<section class="construction-type">
			<div class="container">
				<div class="title-block">
					<h2>Выберите тип конструкции для расчета стоимости</h2>
					<div class="arrows">
						<div class="prev">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 16L10 12L14 8" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
						<div class="next">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 8L14 12L10 16" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>
				</div>
				<div class="construction-slider">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Объемные буквы</div>
									<div class="img"><img src="img/dest/type7.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="/dev/dist/product.html">С бортом из алюминия</a></li>
										<li><a href="/dev/dist/product-155.html">Со световым бортом</a></li>
									</ul>
								</div>
								<div class="num">01</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Несветовые вывески</div>
									<div class="img"><img src="img/dest/type1.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="/dev/dist/sign-156.html">Пластиковые вывески</a></li>
										<li><a href="/dev/dist/sign-157.html">Акриловые вывески</a></li>
										<li><a href="#">Вывески из алюминиевого композита (скоро)</a></li>
									</ul>
								</div>
								<div class="num">02</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Стенды</div>
									<div class="img"><img src="img/dest/type3.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="#">Стенд из пластика с карманами (скоро)</a></li>
										<li><a href="#">Стенд из оргстекла с карманами (скоро)</a></li>
									</ul>
								</div>
								<div class="num">03</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Наклейки</div>
									<div class="img"><img src="img/dest/type5.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="#">Маленькие наклейки с резкой (скоро)</a></li>
										<li><a href="#">Аппликация на стекло (скоро)</a></li>
										<li><a href="#">Печать на пленке самоклеющейся (скоро)</a></li>
									</ul>
								</div>
								<div class="num">04</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Таблички</div>
									<div class="img"><img src="img/dest/type3.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="#">Пластиковые таблички (скоро)</a></li>
										<li><a href="#">Акриловые таблички (скоро)</a></li>
										<li><a href="#">Таблички из композита (скоро)</a></li>
									</ul>
								</div>
								<div class="num">05</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Баннеры</div>
									<div class="img"><img src="img/dest/type4.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="#">Баннер с люверсами (скоро)</a></li>
										<li><a href="#">Баннер на раме (скоро)</a></li>
									</ul>
								</div>
								<div class="num">06</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="inner">
								<div class="name-block">
									<div class="name">Режим работы</div>
									<div class="img"><img src="img/dest/type2.jpg" alt=""></div>
								</div>
								<div class="text">
									<ul>
										<li><a href="#">Режим работы акриловый Премиум (скоро)</a></li>
										<li><a href="#">Режим работы пластиковый (скоро)</a></li>
										<li><a href="#">Режим работы (наклейка) (скоро)</a></li>
									</ul>
								</div>
								<div class="num">07</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="services-advantages">
			<div class="container">
				<div class="row">
					<div class="col-12 col-sm-6 text-block">
						<div class="title">
							<h2>Преимущества сервиса</h2>
						</div>
						<div class="advantages-list">
							<div class="item">
								<div class="name">Быстрота и удобство расчёта</div>
								<div class="val">Вы можете самостоятельно получить точный расчет стоимости за несколько минут — без звонков и переписки.</div>
							</div>
							<div class="item">
								<div class="name">Персонализация предложения</div>
								<div class="val">Учитываем индивидуальные параметры от типа конструции до вариантов оформления</div>
							</div>
							<div class="item">
								<div class="name">Возможность заказать Online</div>
								<div class="val">Формируйте счета или оплачивайте, как физическое лицо по карте или СБП (Системе быстрых платежей)</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 img-block">
						<div class="img">
							<picture>
								<img src="img/dest/advantages-img.png" alt="">
							</picture>
						</div>
						<div class="advantages-img-text img-text1">
							<div class="icon">
								<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M28.5 16.5C31.8137 16.5 34.5 19.1863 34.5 22.5C34.5 25.8137 31.8137 28.5 28.5 28.5L9 28.5001C4.85786 28.5001 1.5 25.1419 1.5 20.9998C1.5 17.0247 4.5931 13.7722 8.50342 13.5162C10.1853 9.96006 13.805 7.5 18 7.5C23.29 7.5 27.6671 11.412 28.3945 16.5009C28.4299 16.5003 28.4645 16.5 28.5 16.5Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<div class="text">
								<div class="name">Online расчет</div>
								<div class="caption">без участия человека</div>
							</div>
						</div>
						<div class="advantages-img-text img-text2">
							<div class="icon">
								<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M18 10.5V18H25.5M18 31.5C10.5442 31.5 4.5 25.4558 4.5 18C4.5 10.5442 10.5442 4.5 18 4.5C25.4558 4.5 31.5 10.5442 31.5 18C31.5 25.4558 25.4558 31.5 18 31.5Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<div class="text">
								<div class="name">Быстро</div>
								<div class="caption">5 минут и готово </div>
							</div>
						</div>
						<div class="advantages-img-text img-text3">
							<div class="icon">
								<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9 11.2503V10.8003C9 9.12014 9 8.27943 9.32698 7.6377C9.6146 7.07321 10.0732 6.6146 10.6377 6.32698C11.2794 6 12.1201 6 13.8003 6H26.7003C28.3805 6 29.2194 6 29.8611 6.32698C30.4256 6.6146 30.8857 7.07321 31.1733 7.6377C31.5 8.2788 31.5 9.11849 31.5 10.7954V20.7046C31.5 22.3815 31.5 23.22 31.1733 23.8611C30.8857 24.4256 30.4259 24.8857 29.8614 25.1733C29.2203 25.5 28.3815 25.5 26.7046 25.5H15.75M4.5 25.2003V16.8003C4.5 15.1201 4.5 14.2794 4.82698 13.6377C5.1146 13.0732 5.57321 12.6146 6.1377 12.327C6.77943 12 7.62014 12 9.30029 12H10.2003C11.8805 12 12.7194 12 13.3611 12.327C13.9256 12.6146 14.3857 13.0732 14.6733 13.6377C15 14.2788 15 15.1185 15 16.7954V25.2046C15 26.8815 15 27.72 14.6733 28.3611C14.3857 28.9256 13.9256 29.3857 13.3611 29.6733C12.72 30 11.8815 30 10.2046 30H9.29537C7.61849 30 6.7788 30 6.1377 29.6733C5.57321 29.3857 5.1146 28.9256 4.82698 28.3611C4.5 27.7194 4.5 26.8805 4.5 25.2003Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<div class="text">
								<div class="name">Сохраняйте расчет</div>
								<div class="caption">и заказываете еще раз</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="order-instruction">
			<div class="container">
				<div class="instruction-inner">
					<div class="row">
						<div class="col-12 col-sm-6">
							<div class="title">
								<h3>Как оформить заказ Online?</h3>
							</div>
							<div class="instruction-list">
								<div class="item">
									<div class="img">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M18 14C17.4477 14 17 14.4477 17 15C17 15.5523 17.4477 16 18 16C18.5523 16 19 15.5523 19 15C19 14.4477 18.5523 14 18 14Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M12 14C11.4477 14 11 14.4477 11 15C11 15.5523 11.4477 16 12 16C12.5523 16 13 15.5523 13 15C13 14.4477 12.5523 14 12 14Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M6 14C5.44772 14 5 14.4477 5 15C5 15.5523 5.44772 16 6 16C6.55228 16 7 15.5523 7 15C7 14.4477 6.55228 14 6 14Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M18 8C17.4477 8 17 8.44772 17 9C17 9.55228 17.4477 10 18 10C18.5523 10 19 9.55228 19 9C19 8.44772 18.5523 8 18 8Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M12 8C11.4477 8 11 8.44772 11 9C11 9.55228 11.4477 10 12 10C12.5523 10 13 9.55228 13 9C13 8.44772 12.5523 8 12 8Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M6 8C5.44772 8 5 8.44772 5 9C5 9.55228 5.44772 10 6 10C6.55228 10 7 9.55228 7 9C7 8.44772 6.55228 8 6 8Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									<div class="text">
										<div class="name">Выберите тип конструкции</div>
										<div class="caption">Выберите из списка подходящий тип рекламной конструкции</div>
									</div>
								</div>
								<div class="item">
									<div class="img">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M3 7H8.0941C8.42444 7 8.58892 7 8.73733 7.0474C8.86867 7.08934 8.99021 7.15798 9.09375 7.24902C9.21075 7.3519 9.29586 7.49359 9.46582 7.77686L14.5337 16.2232C14.7036 16.5065 14.7887 16.6479 14.9057 16.7508C15.0092 16.8419 15.1304 16.9107 15.2617 16.9526C15.41 17 15.5764 17 15.9062 17H21.0003M15 7H21" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									<div class="text">
										<div class="name">Настройте опции</div>
										<div class="caption">Настройте размер и опции для точного расчета. Выберите дизайн и монтаж,  если хотитие получить продукцию «под ключ»</div>
									</div>
								</div>
								<div class="item">
									<div class="img">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 11V20M9 11H4.59961C4.03956 11 3.75981 11 3.5459 11.109C3.35774 11.2049 3.20487 11.3577 3.10899 11.5459C3 11.7598 3 12.04 3 12.6001V20H9M9 11V5.6001C9 5.04004 9 4.75981 9.10899 4.5459C9.20487 4.35774 9.35774 4.20487 9.5459 4.10899C9.75981 4 10.0396 4 10.5996 4H13.3996C13.9597 4 14.2403 4 14.4542 4.10899C14.6423 4.20487 14.7948 4.35774 14.8906 4.5459C14.9996 4.75981 15 5.04005 15 5.6001V8M9 20H15M15 20L21 20.0001V9.6001C21 9.04005 20.9996 8.75981 20.8906 8.5459C20.7948 8.35774 20.6429 8.20487 20.4548 8.10899C20.2409 8 19.9601 8 19.4 8H15M15 20V8" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									<div class="text">
										<div class="name">Произойдет расчет цены и сроков</div>
										<div class="caption">Уже на данном этапе будет доступна калькуляция и ориентировочные сроки изготовления</div>
									</div>
								</div>
								<div class="item">
									<div class="img">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 8C9 9.65685 10.3431 11 12 11C13.6569 11 15 9.65685 15 8M3 16.8002V7.2002C3 6.08009 3 5.51962 3.21799 5.0918C3.40973 4.71547 3.71547 4.40973 4.0918 4.21799C4.51962 4 5.08009 4 6.2002 4H17.8002C18.9203 4 19.4796 4 19.9074 4.21799C20.2837 4.40973 20.5905 4.71547 20.7822 5.0918C21 5.5192 21 6.07899 21 7.19691V16.8036C21 17.9215 21 18.4805 20.7822 18.9079C20.5905 19.2842 20.2837 19.5905 19.9074 19.7822C19.48 20 18.921 20 17.8031 20H6.19691C5.07899 20 4.5192 20 4.0918 19.7822C3.71547 19.5905 3.40973 19.2842 3.21799 18.9079C3 18.4801 3 17.9203 3 16.8002Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									<div class="text">
										<div class="name">Оформите заказ</div>
										<div class="caption">Теперь вы можете оформить заказ и оплатить его</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

    <!-- Cases Gallery Vue Component -->
    <div id="cases-app"></div>

    <!-- Feedback Gallery Vue Component -->
    <div id="feedback-app"></div>

    <!-- Footer Vue Component -->
    <div id="footer-app"></div>
</body>
</html>
