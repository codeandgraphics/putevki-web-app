<div class="page" id="{{ page }}">
	<section class="block popular" id="popular">
		<div class="container header">
			<h2 style="text-align: center;">
				Самые популярные направления
			</h2>
		</div>

		<div class="content">
			<div class="container">
				<div class="row items">
                    {% for popular in populars %}
						<div class="item col-xs-2">
							<a href="{{ popular.url }}" target="_blank">
								<div class="image">
									<div class="bg" style="background-image: url('//static.tourvisor.ru/minprice/minprice-{{ popular.countryId }}-1.jpg');"></div>
								</div>
								<div class="about">
									<h4 class="title">
                                        {{ popular.region }}
									</h4>
									<small>{{ popular.country }}</small>
								</div>
							</a>
						</div>
                    {% endfor %}
				</div>
			</div>
		</div>
	</section>

	<section class="block mobile-promo" id="mobile-promo">
		<div class="container">
			<div class="promo-block">
				<div class="mobile"></div>
				<div class="text">
					<h3>Все путёвки в твоем смартфоне!</h3>
					<p>Ищите самые выгодные цены на путёвки в любом месте,<br/>где бы вы не находились!</p>
				</div>
				<a target="_blank" href="{{ config.defaults.appStore }}" class="appstore"></a>
				<a target="_blank" href="{{ config.defaults.googlePlay }}" class="google-play"></a>
			</div>
		</div>
	</section>

	<section class="block hot" id="hot" data-url="{{ url('ajax/hotTours') }}">
		<div class="container header">
			<h2 style="text-align: center;">
				Горящие туры<br/><small>Самые выгодные предложения для вас!</small>
			</h2>
		</div>
		<div class="content">
			<div class="container">
				<div class="loader">
					<div class="wrap">
						<div class="object">
						</div>
						Ищем самые горячие туры...
					</div>
				</div>
				{{ partial('partials/hot') }}
			</div>
		</div>
	</section>

	<section class="block map">
		<div class="container header">
			<h2 style="text-align: center;">
				Вылеты из {{ cities|length }} городов<br/><small>Мы отправляем туристов по всей России</small>
			</h2>
		</div>

		<div class="loader">
			<div class="wrap">
				<div class="object"></div>
			</div>
		</div>

		<div id="mainMap"></div>

	</section>

	<section class="block benefits" id="benefits">
		<div class="container header">
			<h2 style="text-align: center;">Наши преимущества</h2>
		</div>

		<div class="content">
			<div class="container">
				<?php
					$datetime1 = new DateTime('1997-04-01');
					$datetime2 = new DateTime();
					$interval = $datetime1->diff($datetime2);
				?>
				<div class="row">
					<div class="col-xs-6">
						<i class="ion-compass"></i>
						<a href="{{ url('about') }}" target="_blank">
							Более <?=$interval->format('%y');?> лет на рынке туристических услуг
						</a>
					</div>
					<div class="col-xs-6">
						<i class="ion-iphone" style="padding-left: 10px"></i>
						<a href="#" id="mobile-promo-link" class="internal">
							<span>Удобное мобильное приложение</span>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<i class="ion-card"></i>
						<a href="{{ url('uniteller') }}" target="_blank">
							Онлайн-оплата и бронирование
						</a>
					</div>
					<div class="col-xs-6">
						<i class="ion-map"></i>
						<a href="#" class="offices internal">
							<span>Офисы продаж</span>
						</a> по всей России
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<i class="ion-thumbsup"></i>
						<a href="{{ url('best-tours') }}" target="_blank">
							Поиск туров от лучших туроператоров
						</a>
					</div>
					<div class="col-xs-6">
						<i class="ion-heart"></i>
						<a href="{{ url('personal') }}" target="_blank">
							Персональный подход к каждому клиенту
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="block about" id="about">
		<div class="container header">
			<h2 style="text-align: center;">
				Поиск туров по всем туроператорам<br/>
				<small>Купить путёвку онлайн</small>
			</h2>
		</div>

		<div class="content">
			<div class="container">
				<p>
					<strong>Путёвки.ру</strong>&nbsp;— это интернет-магазин путёвок для поиска туров по&nbsp;всем
					туроператорам, который полностью заменяет обычное турагентство в&nbsp;вашем городе.
					Наш девиз&nbsp;— 5&nbsp;минут и&nbsp;вы&nbsp;турист! Чтобы купить путешествие мечты,
					вам потребуется только выход в&nbsp;интернет с&nbsp;компьютера или любого мобильного устройства.
				</p>
				<p>
					Вам не&nbsp;нужно тратить время на&nbsp;посещение нашего офиса. Для бронирования поездки вам
					достаточно только выбрать и&nbsp;заказать тур на&nbsp;сайте. Наш менеджер-эксперт свяжется
					с&nbsp;вами для обсуждения деталей поездки. Мы&nbsp;вышлем все необходимые документы на&nbsp;вашу
					электронную почту. Разумеется, мы&nbsp;всегда будем рады вашему личному визиту в&nbsp;наш офис.
				</p>
				<p>
					Все наши офисы&nbsp;— действующие агентства крупных туроператорских компаний в&nbsp;Европе:
					Tez Tour, TUI , а&nbsp;также крупнейшее в&nbsp;России объединение независимых агентств
					«Сеть Магазинов Горящих путёвок». Путёвки.ру&nbsp;— это прямые контракты и&nbsp;проверенные
					временем отношения с&nbsp;сотнями туроператоров с&nbsp;1997&nbsp;года. Мы&nbsp;обеспечиваем
					финансовые гарантии и&nbsp;защитутуристов со&nbsp;стороны закона. Наш магазин работает
					по&nbsp;технологии оплаты через защищенный банковский портал, что исключает возможность
					несанкционированного доступа к&nbsp;платежным данным вашей карты.
				</p>
				<p>
					Бронирование и&nbsp;поиск лучших туров онлайн. Горящие путёвки
					в&nbsp;<a href="{{ url('countries/turkey') }}" title="путёвки в Турцию">Турцию</a>,
					<a href="{{ url('countries/thailand') }}" title="путёвки в Таиланд">Таиланд</a>,
					<a href="{{ url('countries/dominican') }}" title="путёвки в Доминикану">Доминикану</a>,
					<a href="{{ url('countries/uae') }}" title="путёвки в ОАЭ">ОАЭ</a>
					и&nbsp;<a href="{{ url('') }}">другие направления</a>.
					Бронирование путёвок в&nbsp;режиме онлайн с&nbsp;оплатой путёвки банковской картой.
					путёвки от&nbsp;крупнейших туроператоров, 100% актуальность предложений, автоматическое
					отслеживание горящих предложений 24&nbsp;часа в&nbsp;сутки.
				</p>
			</div>
		</div>
	</section>
</div>