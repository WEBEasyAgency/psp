<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Оформление заказа</title>

	<x-layout.favicon />

	{{-- Сторонние CSS --}}
	<link rel="stylesheet" href="/layout/css/libs.min.css">
	<link rel="stylesheet" href="/layout/css/app.min.css">
	<style>
/* BEGIN Checkbox Overrides for Order Page */
.checkbox {
    display: flex !important;
    align-items: flex-start !important;
    gap: 12px !important;
}

.checkbox input[type="checkbox"] {
    position: absolute !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
}

.checkbox input[type="checkbox"] + .check {
    display: block !important;
    width: 24px !important;
    height: 24px !important;
    min-width: 24px !important;
    background-color: #f6f6f6 !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    position: relative !important;
    transition: all 0.2s !important;
    flex-shrink: 0 !important;
}

.checkbox input[type="checkbox"]:hover + .check {
    border-color: #3C7BBB !important;
}

.checkbox input[type="checkbox"]:checked + .check {
    background-color: #3c7bbb !important;
    border-color: #3c7bbb !important;
}

.checkbox input[type="checkbox"]:checked + .check::after {
    content: "" !important;
    position: absolute !important;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: 16px !important;
    height: 16px !important;
    background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2.667 8L5.967 11.3L13.038 4.229' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
    background-size: contain !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
}

.checkbox input[type="checkbox"]:disabled + .check {
    opacity: 0.5 !important;
    cursor: not-allowed !important;
}
/* END Checkbox Overrides for Order Page */
	</style>
</head>
<body>
	<!-- Header Blade Component -->
	<x-layout.header />

	<main class="order-page inner-page">
		<section class="breadcrumbs">
			<div class="container">
				<ul>
					<li class="main">
						<a href="/">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3.33301 9.54425V14.0009C3.33301 14.9343 3.33301 15.4013 3.51466 15.7579C3.67445 16.0715 3.92924 16.3262 4.24284 16.486C4.59901 16.6675 5.0655 16.6675 5.9971 16.6675H14.0022C14.9338 16.6675 15.3997 16.6675 15.7558 16.486C16.0694 16.3262 16.3251 16.0715 16.4849 15.7579C16.6663 15.4017 16.6663 14.9354 16.6663 14.0038V9.54425C16.6663 9.09897 16.666 8.87621 16.6118 8.66901C16.5638 8.48539 16.4851 8.31162 16.3785 8.1546C16.2582 7.97741 16.091 7.83047 15.7559 7.53725L11.7559 4.03725C11.1337 3.49285 10.8226 3.22079 10.4725 3.11725C10.164 3.02602 9.83518 3.02602 9.52669 3.11725C9.17685 3.22071 8.86621 3.49251 8.24498 4.0361L4.24365 7.53726C3.90854 7.83048 3.74138 7.97741 3.62109 8.1546C3.5145 8.31162 3.43513 8.48539 3.38715 8.66901C3.33301 8.87621 3.33301 9.09897 3.33301 9.54425Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
					</li>
					<li class="separator">/</li>
					<li>Оформление заказа</li>
				</ul>
			</div>
		</section>
		<section class="order-form">
			<div class="container">
				<div class="title">
					<h1>Оформление заказа</h1>
				</div>
				<div class="order-block">
					<div class="grid">
						<div class="left-block">
							<div class="design-price">
								<div class="title">
									Требуется дизайн?
									<div class="icon">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.14648 9.07361C9.31728 8.54732 9.63015 8.07896 10.0508 7.71948C10.4714 7.36001 10.9838 7.12378 11.5303 7.03708C12.0768 6.95038 12.6362 7.0164 13.1475 7.22803C13.6587 7.43966 14.1014 7.78875 14.4268 8.23633C14.7521 8.68391 14.9469 9.21256 14.9904 9.76416C15.0339 10.3158 14.9238 10.8688 14.6727 11.3618C14.4215 11.8548 14.0394 12.2685 13.5676 12.5576C13.0958 12.8467 12.5533 12.9998 12 12.9998V14.0002M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 17V17.1L11.9502 17.1002V17H12.0498Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
								</div>
								<div class="list">
									<label class="radio">
										<input type="radio" name="design" value="own" checked="checked">
										<span class="check"></span>
										<span class="label">У меня есть готовый дизайн</span>
									</label>
									<label class="radio">
										<input type="radio" name="design" value="order">
										<span class="check"></span>
										<span class="label">Заказать услуги дизайнера</span>
									</label>
								</div>
								<label class="file">
									<input type="file" name="file" id="designFile">
									<span class="text" id="fileText">Загрузить дизайн-макет или файл</span>
									<span class="btn">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M4 8H8M8 8H12M8 8V12M8 8V4" stroke="#F6F6F6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										Выбрать файл
									</span>
								</label>
								<div class="all-price">
									<div class="caption">Итого</div>
									<div class="val" id="totalPrice">{{ number_format($price_good, 0, ',', ' ') }} ₽</div>
								</div>
							</div>
                            <style>
                                .file {
                                    display: none !important;
                                }
                            </style>
						</div>
						<div class="right-block">
							<div class="name">
                                <h4>{{ $description }}</h4>
                            </div>
							<div class="change-order"><a href="javascript:history.back()">Изменить заказ</a></div>
							<form class="form-block" id="orderForm">
								<!-- Скрытые поля с данными заказа -->
								<input type="hidden" name="calc_position_id" value="{{ $calc_position_id }}">
								<input type="hidden" name="price_good" value="{{ $price_good }}">

								<div class="form-field required">
									<div class="caption">Введите имя</div>
									<div class="field"><input type="text" name="fio" required="required" placeholder="Как вас зовут?"></div>
								</div>
								<div class="form-field required">
									<div class="caption">Телефон для связи</div>
									<div class="field"><input type="tel" name="phone" required="required" placeholder="+7 (___) ___-__-__"></div>
								</div>
								<div class="form-field required">
									<div class="caption">E-mail</div>
									<div class="field"><input type="email" name="email" required="required" placeholder="Введите E-mail"></div>
								</div>
								<div class="check-field">
									<label class="checkbox">
										<input type="checkbox" name="consent" required="required">
										<span class="check"></span>
										<span class="label">Я даю согласие на обработку моих персональных данных ИП Лыков П.С. (ИНН 631921444166) в целях обработки заявки и обратной связи. Политика конфиденциальности — по <a href="https://ra-psp.ru/policy/" target="_blank">ссылке</a>, политику обработки персональных данных — по <a href="https://ra-psp.ru/politika-v-otnoshenii-obrabotki-personalnyh-dannyh/" target="_blank">ссылке</a>.</span>
									</label>
								</div>
								<div class="btn-block">
									<a href="javascript:history.back()" class="back">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M18 18L12 12M12 12L6 6M12 12L18 6M12 12L6 18" stroke="#2C619D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										Назад
									</a>
									<button type="submit" id="submitBtn">Оформить заказ</button>
								</div>

								<!-- Блок сообщений -->
								<div id="orderMessage" style="display: none; margin-top: 20px; padding: 15px; border-radius: 8px;"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	<!-- Footer Blade Component -->
	<x-product.footer />

	{{-- Библиотеки (Swiper и др.) --}}
	<script src="/layout/js/libs.min.js"></script>
	{{-- app.min.js: инициализация Swiper слайдеров и другие обработчики --}}
	<script src="/layout/js/app.min.js"></script>
	<script>
	// Обработка формы заказа
	document.getElementById('orderForm').addEventListener('submit', async function(e) {
		e.preventDefault();

		const submitBtn = document.getElementById('submitBtn');
		const messageDiv = document.getElementById('orderMessage');

		// Получаем данные формы
		const formData = new FormData(this);
		const fio = formData.get('fio');
		const phone = formData.get('phone');
		const email = formData.get('email');
		const calc_position_id = parseInt(formData.get('calc_position_id'));
		const price_good = parseInt(formData.get('price_good'));

		// Дизайн
		const designType = document.querySelector('input[name="design"]:checked').value;

		// Блокируем кнопку
		submitBtn.disabled = true;
		submitBtn.textContent = 'Оформление...';

		try {
			// 1. Создаём контакт
			const contactRes = await fetch('/backend/api/calc/addContact', {
				method: 'POST',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					fio,
					phone,
					email
				})
			});

			if (!contactRes.ok) {
				const errorData = await contactRes.text();
				throw new Error(`Ошибка создания контакта: ${errorData}`);
			}
			const contactData = await contactRes.json();
			const client_id = contactData.client_id;

			// 2. Добавляем расчёт в калькуляцию
			const calcRes = await fetch('/backend/api/calc/addCalc', {
				method: 'POST',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					calc_position_id,
					price_good
				})
			});

			if (!calcRes.ok) {
				const errorData = await calcRes.text();
				throw new Error(`Ошибка добавления расчёта: ${errorData}`);
			}
			const calcData = await calcRes.json();
			const calc_id = calcData.calc_id;

			// 3. Сохраняем калькуляцию
			const saveRes = await fetch('/backend/api/calc/saveCalc', {
				method: 'POST',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					calc_id: calc_id,
					client_id: client_id
				})
			});

			if (!saveRes.ok) {
				const errorData = await saveRes.text();
				throw new Error(`Ошибка сохранения заказа: ${errorData}`);
			}

			// Успех! Перенаправляем на страницу благодарности
			window.location.href = `/thanx?calc_id=${calc_id}&client_id=${client_id}`;

		} catch (error) {
			messageDiv.style.display = 'block';
			messageDiv.style.backgroundColor = '#f8d7da';
			messageDiv.style.color = '#721c24';
			messageDiv.style.border = '1px solid #f5c6cb';
			messageDiv.innerHTML = `<strong>Ошибка:</strong> ${error.message}`;
		} finally {
			submitBtn.disabled = false;
			submitBtn.textContent = 'Оформить заказ';
		}
	});

	// Обработка выбора файла
	document.getElementById('designFile').addEventListener('change', function() {
		const fileText = document.getElementById('fileText');
		if (this.files.length > 0) {
			fileText.textContent = this.files[0].name;
		} else {
			fileText.textContent = 'Загрузить дизайн-макет или файл';
		}
	});
	</script>
</body>
</html>
