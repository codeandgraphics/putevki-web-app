<div class="hero">
	<div class="hero-overlay"></div>
	<div class="main-header">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="/">
					<img alt="Путевки.ру" src="/assets/img/logo.png">
				</a>
			</div>
			<div class="collapse navbar-collapse">
				<div class="phone navbar-text">
					<div class="number">
						<a href="tel:{{ currentCity.phone }}">{{ currentCity.phone }}</a>
					</div>
					служба поддержки клиентов
				</div>
				<div class="request navbar-text">
					<button class="btn btn-primary" data-toggle="modal" data-target="#findTourModal"><i class="ion-map"></i> Подберите мне тур</button>
				</div>
				<div class="location navbar-text navbar-right">
					<i class="ion-location"></i> <a href="#" data-toggle="modal" data-target="#cityModal"><span>{{ currentCity.name }}</span><b class="caret"></b></a>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<form class="form-inline" method="get" id="searchForm"
			  data-departure="{{ params.departureId }}"
			  data-country="{{ params.countryId }}"
			  data-region="{{ params.regionId }}"
			  data-date="<?=implode('.', array_reverse(explode('-',$params->date)));?>"
			  data-date-range="{{ params.date_range }}"
			  data-nights="{{ params.nights }}"
			  data-nights-range="{{ params.nights_range }}"
			  data-adults="{{ params.adults }}"
			  data-kids="{{ params.kids }}"
			  data-stars="{{ params.starsId }}"
			  data-meal="{{ params.mealId }}"
			  data-countries="{{ formCountries }}"
			  data-regions="{{ formRegions }}"
		>
			<h1 class="title">Куда бы вы хотели поехать?</h1>
			<div class="search-form">
				<div class="loader">
					<div class="wrap">
						<div class="object"></div>
					</div>
				</div>
				<div class="where form-group">
					<input type="text" class="form-control" placeholder="Страна, регион или отель">
				</div>
				<div class="when form-group">
					<span class="range">± 2 дня</span>
					<div class="value"></div>
				</div>
				<div class="length form-group popup-nights">
					<span class="range">± 2</span>
					<div class="value"></div>
					<div class="popup nights hidden">
						<div class="selector">
							<div class="minus">-</div>
							<div class="plus">+</div>
							<div class="param"></div>
						</div>
						<div class="range-checkbox">
							<input type="checkbox" id="nights-range-days" checked>
							<label for="nights-range-days">± 2 ночи</label>
						</div>
					</div>
				</div>
				<div class="people form-group popup-people" data-adults="2" data-kids="">
					<div class="value"></div>
					<div class="popup people hidden">
						<div class="adults selector">
							<div class="minus">-</div>
							<div class="plus">+</div>
							<div class="param"></div>
						</div>
						<div class="kids">
							<div class="kid template"><span></span> <i class="ion-ios-close-empty"></i></div>
						</div>
						<div class="add-kids">
							<div class="add">
								<select>
									<option value="">Добавить ребенка</option>
									<option value="1">до 2х лет</option>
									<option value="2">2 года</option>
									<option value="3">3 года</option>
									<option value="4">4 года</option>
									<option value="5">5 лет</option>
									<option value="6">6 лет</option>
									<option value="7">7 лет</option>
									<option value="8">8 лет</option>
									<option value="9">9 лет</option>
									<option value="10">10 лет</option>
									<option value="11">11 лет</option>
									<option value="12">12 лет</option>
									<option value="13">13 лет</option>
									<option value="14">14 лет</option>
								</select>
								<div class="info">
									Чтобы взять с собой больше детей, разделите взрослых и детей на несколько групп или обратитесь в турагентство.
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="search-button">
					<button class="btn btn-default">Искать туры</button>
				</div>
				<div class="from dropdown">
					<span class="from-text"{% if currentDeparture.id == 99 %} style="display:none;"{% endif %}>Вылет из</span>
					<a id="fromDropdown" href="javascript:" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<span>{{ currentDeparture.name_from }}</span><b class="caret"></b>
					</a>
					<ul class="dropdown-menu pull-right" aria-labelledby="fromDropdown">
						<li><a href="javascript:" data-id="1" data-gen="Москвы">из <span>Москвы</span></a></li>
						<li><a href="javascript:" data-id="5" data-gen="Санкт-Петербурга">из <span>Санкт-Петербурга</span></a></li>
						<li><a href="javascript:" data-id="99" data-gen="Без перелета"><span>Без перелета</span></a></li>
						<li role="separator" class="divider"></li>
						{% for departure in departures %}
							<li><a href="javascript:" data-id="{{ departure.id }}" data-gen="{{ departure.name_from }}">из <span>{{ departure.name_from }}</span></a></li>
						{% endfor %}
					</ul>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="page" id="{{ page }}">
	<section class="block popular" id="popular">
		<div class="container header">
			<h2>
				Самые популярные направления
			</h2>
			<h2 class="second">Наши преимущества <span>Почему мы?</span></h2>
		</div>

		<div class="content">
			<div class="container">
				<div class="items">
					{% for popular in populars %}
						<div class="country item">
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

				<div class="our-benefits">
					<?php
					$datetime1 = new DateTime('1997-04-01');
					$datetime2 = new DateTime();
					$interval = $datetime1->diff($datetime2);
					?>
					<ul class="list-unstyled">
						<li>
							<i class="ion-compass"></i>
							<a href="https://putevki.ru/o-kompanii" target="_blank">
								Более <?=$interval->format('%y');?> лет на рынке туристических услуг
							</a>
						</li>
						<li>
							<i class="ion-heart"></i> Персональный подход к каждому клиенту
						</li>
						<li>
							<i class="ion-map"></i> <a href="#" class="offices">Офисы продаж</a> по всей России
						</li>
						<li>
							<i class="ion-thumbsup"></i> Поиск туров от лучших туроператоров
						</li>
						<li>
							<i class="ion-card"></i> Онлайн-оплата и бронирование
						</li>
					</ul>
				</div>
			</div>
		</div>

	</section>

	<section class="block mobile-promo" id="mobile-promo">
		<div class="container">
			<div class="promo-block">
				<img class="mobile" alt="Путевки.ру" src="/assets/img/app/mobile.png" />
				<div class="text">
					<h3>Все путёвки в твоем смартфоне!</h3>
					<p>Ищите самые выгодные цены на путёвки в любом месте,<br/>где бы вы не находились!</p>
				</div>
				<a target="_blank" href="{{ config.appStore }}" class="appstore">
					<img alt="Путевки.ру в App Store" src="/assets/img/app/appstore.png" />
				</a>
				<img class="google-play" alt="Путевки.ру в Google Play" src="/assets/img/app/google-play.png" />
			</div>
		</div>
	</section>

	<section class="block hot" id="hot">
		<div class="container header">
			<h2>
				Горящие туры <small>Самые выгодные предложения для вас!</small>
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
				<div class="items">

					<div class="hotel item template">
						<a href="#" target="_blank">
							<div class="image">
								<div class="discount">

								</div>
								<div class="bg" style="background-image: url();"></div>
							</div>
							<div class="about">
								<h4 class="title">

								</h4>
								<small class="where"><span class="region"></span>, <span class="country"></span></small>
								<div class="info">
									<div class="length">
										<span class="date"></span>,
										<span class="nights"></span>
									</div>
									<div class="price">
										<span></span> р/чел.
									</div>
								</div>
							</div>
						</a>
					</div>

				</div>
			</div>
		</div>

	</section>

	<section class="block map">
		<div class="container header">
			<h2>
				Наши представительства <small>Более 30 филиалов по всей стране</small>
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
</div>