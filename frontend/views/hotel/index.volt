<div class="page" id="hotel" data-hotelId="{{ hotel.db.id }}">
	<div class="container flex tour">
		<section class="main left">
			<div class="head">
				<h1>
					{{ hotel.name|lower }}
				</h1>
				<div class="stars">
					{% for i in 0..4 %}
						<i class="star ion-ios-star{% if i >= hotel.stars %}-outline{% endif %}"></i>
					{% endfor %}

					{% if hotel.types['deluxe'] %}
						<small class="deluxe">Эксклюзивный</small>
					{% endif %}
				</div>
				<div class="place">
					<i class="ion-ios-location"></i>
					<span>{{ hotel.region }}, {{ hotel.country }}</span>
				</div>
			</div>

			<div class="content">
				<div class="title">
					<div class="gallery">
						<ul class="light-slider">
							{% for i in 0..hotel.imagescount-1 %}
								<li data-thumb="{{ hotel.images.image[i] }}">
									<img src="{{ hotel.images.image[i] }}" />
								</li>
							{% endfor %}
						</ul>
					</div>

					<div class="description">
						<ul class="types list-unstyled list-inline">
							{% for key, type in hotel.types %}
								<li class="type {{ key }}">{{ type }}</li>
							{% endfor %}
						</ul>
						{% if hotel.description is defined %}
							{{ hotel.description }}
						{% else %}
							<div class="message">
								К сожалению, описания отеля у нас пока нет :(
							</div>
						{% endif %}
					</div>
				</div>

				<div class="tours" id="tours">
					<h3>Туры в отель {{ hotel.name|lower }}</h3>

					<div class="hotel-form">
						<form class="search-form" action="" id="searchForm"
							  data-countries="{{ formCountries }}"
							  data-regions="{{ formRegions }}"
							  data-from="{{ params.search.from }}"
							  data-where='{{ params.search.where|json_encode }}'
							  data-when='{{ params.search.when|json_encode }}'
							  data-people='{{ params.search.people|json_encode }}'
							  data-filters='{{ params.search.filters|json_encode }}'
						>
							{% set departure = params.search.fromEntity() %}
							<div class="form-container">
								<div class="loader" style="display: none;">
									<div class="wrap">
										<div class="object"></div>
									</div>
								</div>
								<div class="inputs">
									<div class="when input">
										<span class="range">± 2 дня</span>
										<div class="value"></div>
										<input title="when" />
									</div>
									<div class="length input popup-nights">
										<span class="range">± 2</span>
										<div class="value"></div>
										<div class="popup nights">
											<i class="popup-pointer"></i>
											<div class="selector">
												<div class="minus">–</div>
												<div class="plus">+</div>
											</div>
											<div class="range-checkbox">
												<input type="checkbox" id="nights-range-days" checked>
												<label for="nights-range-days">± 2 ночи</label>
											</div>
										</div>
									</div>
									<div class="people input popup-people">
										<div class="value"></div>
										<div class="popup people">
											<i class="popup-pointer"></i>
											<div class="adults selector">
												<div class="minus">-</div>
												<div class="plus">+</div>
												<div class="param"><span></span> <i class="ion-man"></i></div>
											</div>
											<div class="kids">
												<div class="kid template"><span></span> <i class="ion-ios-close-empty"></i></div>
											</div>
											<div class="add-kids">
												<div class="add">
													<select title="kids">
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
									<div class="from input dropdown">
										<span class="from-text range"{% if departure.id == 99 %} style="display:none;"{% endif %}>из</span>
										<div class="value">{{ departure.name_from }}</div>
										<select title="from-select">
											<optgroup label="Популярные">
												<option value="1" data-gen="Москвы">из Москвы</option>
												<option value="5" data-gen="Санкт-Петербурга">из Санкт-Петербурга</option>
												<option value="99" data-gen="Без перелета">Без перелета</option>
											</optgroup>
											<optgroup label="Все">
												{% for item in departures %}
													<option value="{{ item.id }}" data-gen="{{ item.name_from }}">из {{ item.name_from }}</option>
												{% endfor %}
											</optgroup>
										</select>
									</div>
									<div class="search-button">
										<button class="btn btn-primary">Искать туры</button>
									</div>
								</div>
							</div>
							<div class="progressbar"></div>
						</form>
					</div>

					<div class="results">

						<div class="no-results">
							<i class="ion-sad-outline"></i>
							Извините, ничего не найдено...
						</div>

						<div class="loader" style="display: block;">
							<div class="wrap">
								<div class="object"></div>
								<span>Ищем лучшие предложения для вас...</span>
							</div>
						</div>

						<div class="variant template" data-price="">
							<div class="operator">
								<div class="icon">
									<img src="" data-src="//tourvisor.ru/pics/operators/searchlogo/{id}.gif" alt="">
								</div>
								<div class="data">
									<small>Туроператор:</small>
									<span></span>
								</div>
							</div>
							<div class="date">
								<div class="icon">
									<i class="ion-plane"></i>
								</div>
								<div class="data">
									<span></span>
									<small></small>
								</div>
							</div>
							<div class="room">
								<div class="icon">
									<i class="ion-key"></i>
								</div>
								<div class="data">
									<small>Номер:</small>
									<span></span>
								</div>
							</div>
							<div class="meal">
								<div class="icon">
									<i class="ion-fork"></i>
									<i class="ion-knife"></i>
								</div>
								<div class="data">
									<small>Питание:</small>
									<span></span>
								</div>
							</div>
							<div class="price">
								<div class="data">
									<a href="" class="btn btn-primary order" target="_blank"></a>
								</div>
							</div>
						</div>

						<div class="variants">
						</div>

						<div class="more">
							<a href="#">
								Поиск завершен. Показать все туры &rarr;
							</a>
						</div>
					</div>
				</div>

				<div class="services">
					<div class="grid-sizer"></div>
					{% if hotel.territory is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-territory">
								<div class="icon">
									<i class="ion-map"></i>
								</div>
								Территория
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-territory">
								{{ hotel.territory }}
							</div>
						</div>
					{% endif %}

					{% if hotel.meallist is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-meallist">
								<div class="icon">
									<i class="ion-fork"></i>
									<i class="ion-knife"></i>
								</div>
								Питание
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-meallist">
								{{ hotel.meallist }}
							</div>
						</div>
					{% endif %}

					{% if hotel.inroom is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-inroom">
								<div class="icon">
									<i class="ion-home"></i>
								</div>
								В номере
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-inroom">
								{{ hotel.inroom }}
							</div>
						</div>
					{% endif %}

					{% if hotel.roomtypes is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-roomtypes">
								<div class="icon">
									<i class="ion-home"></i>
								</div>
								Номерной фонд
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-roomtypes">
								{{ hotel.roomtypes }}
							</div>
						</div>
					{% endif %}

					{% if hotel.servicefree is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-servicefree">
								<div class="icon">
									<i class="ion-thumbsup"></i>
								</div>
								Бесплатно
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-servicefree">
								{{ hotel.servicefree }}
							</div>
						</div>
					{% endif %}

					{% if hotel.servicepay is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-servicepay">
								<div class="icon">
									<i class="ion-cash"></i>
								</div>
								Платно
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-servicepay">
								{{ hotel.servicepay }}
							</div>
						</div>
					{% endif %}

					{% if hotel.services is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-services">
								<div class="icon">
									<i class="ion-clipboard"></i>
								</div>
								Услуги отеля
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-services">
								{{ hotel.services }}
							</div>
						</div>
					{% endif %}

					{% if hotel.child is defined %}
						<div class="grid-item">
							<h3 data-toggle="collapse" data-target="#collapse-child">
								<div class="icon">
									<i class="ion-ios-body"></i>
								</div>
								Для детей
								<i class="ion-ios-arrow-down open"></i>
							</h3>
							<div class="collapse in item" id="collapse-child">
								{{ hotel.child }}
							</div>
						</div>
					{% endif %}
				</div>
			</div>
		</section>
		<aside class="sidebar right">
			<div class="head">
				<div class="rating">
					<i class="ion-heart"></i> {{ hotel.rating }} <span>&mdash; {{ hotel.humanizeRating }}</span>
				</div>
			</div>
			<div class="content">
				<div class="wrap">
					<div class="about">
						{% if hotel.build is defined %}
							<small>Построен:</small>
							<span>{{ hotel.build }}</span>
						{% endif %}

						{% if hotel.repair is defined %}
							<small>Реставрация:</small>
							<span>{{ hotel.repair }}</span>
						{% endif %}

						{% if hotel.placement is defined %}
							<small>Расположен:</small>
							<span>{{ hotel.placement }}</span>
						{% endif %}

						{% if hotel.phone is defined %}
							<small>Телефон:</small>
							<span>{{ hotel.phone }}</span>
						{% endif %}

						{% if hotel.site is defined %}
							<small>Сайт:</small>
							<span>
									<a href="http://{{ hotel.site }}" target="_blank">{{ hotel.site }}</a>
								</span>
						{% endif %}
					</div>
					{% if (hotel.coord1 is not null) and (hotel.coord2 is not null) %}
						<a href="//www.google.ru/maps/search/{{ hotel.name }}/@{{ hotel.coord1 }},{{ hotel.coord2 }},16z?hl=ru" target="_blank">
							<img src="//maps.googleapis.com/maps/api/staticmap?center={{ hotel.coord1 }},{{ hotel.coord2 }}&zoom=16&size=280x280&maptype=hybrid
	&markers=color:blue%7Clabel:H%7C{{ hotel.coord1 }},{{ hotel.coord2 }}" />
						</a>
					{% endif %}
				</div>
			</div>
		</aside>
	</div>
</div>
