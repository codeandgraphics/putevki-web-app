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
				Наши представительства<br/><small>Более 30 филиалов по всей стране</small>
			</h2>
		</div>

		<div class="loader">
			<div class="wrap">
				<div class="object"></div>
			</div>
		</div>

		<div id="mainMap"></div>

		<div class="content">
			<div class="container">
				<ul id="mapCities" class="list-unstyled list-inline">
				</ul>
			</div>
		</div>
	</section>

	<section class="block benefits" id="benefits">
		<div class="container header">
			<h2 style="text-align: center;">Наши преимущества</h2>
		</div>

		<div class="content ">
			<div class="container">
				<?php
					$datetime1 = new DateTime('1997-04-01');
					$datetime2 = new DateTime();
					$interval = $datetime1->diff($datetime2);
				?>
				<div class="row">
					<div class="col-xs-6">
						<i class="ion-compass"></i>
						<a href="https://putevki.ru/o-kompanii" target="_blank">
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
						<a href="https://online.putevki.ru/uniteller" target="_blank">
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
						<i class="ion-thumbsup"></i> Поиск туров от лучших туроператоров
					</div>
					<div class="col-xs-6">
						<i class="ion-heart"></i> Персональный подход к каждому клиенту
					</div>
				</div>
			</div>
		</div>
	</section>
</div>