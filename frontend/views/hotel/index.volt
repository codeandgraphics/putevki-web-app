<div class="hero little">
	<div class="container">
	</div>
</div>

<div class="page" id="hotel" data-hotelId="{{ hotelId }}">
	<div class="container tour">
		<div class="row no-gutter">
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
							<div class="fotorama" data-nav="thumbs" data-width="400" data-height="260" data-loop="true">
								{% for i in 0..hotel.imagescount-1 %}
								<img src="{{ hotel.images.image[i] }}" />
								{% endfor %}
							</div>
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
							<form class="form-inline search" action="" method="get" id="searchForm"
								  data-departure="{{ params.departureId }}"
								  data-country="{{ hotel.db.country.id }}"
								  data-hotel="{{ hotel.db.id }}"
								  data-region="{{ hotel.db.region.id }}"
								  data-date="<?=implode('.', array_reverse(explode('-',$params->date)));?>"
								  data-date-range="{{ params.date_range }}"
								  data-nights="{{ params.nights }}"
								  data-nights-range="{{ params.nights_range }}"
								  data-adults="{{ params.adults }}"
								  data-kids="{{ params.kids }}"
								  data-stars="{{ params.starsId }}"
								  data-meal="{{ params.mealId }}"
								  data-operator="{{ operator }}"
								  data-countries="{{ formCountries }}"
								  data-regions="{{ formRegions }}"
								>
								<div class="progressbar"></div>
								<div class="loader" style="display: none;">
									<div class="wrap">
										<div class="object"></div>
									</div>
								</div>
								<div class="from form-group">
									<select name="departure" class="form-control">
										{% for departure in departures %}
										<option value="{{ departure.id }}">из {{ departure.name_from }}</option>
										{% endfor %}
									</select>
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
											<input type="checkbox" id="nights-range-days" value="1" name="nights-range-days" checked>
											<label for="nights-range-days">± 2 ночи</label>
										</div>
									</div>
								</div>
								<div class="people form-group popup-people">
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
								<div class="search form-group">
									<button class="btn btn-default">Искать туры</button>
								</div>
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
										<img src="//tourvisor.ru/pics/operators/searchlogo/18.gif" data-src="//tourvisor.ru/pics/operators/searchlogo/{id}.gif" alt="Biblioglobus">
									</div>
									<div class="data">
										<small>Туроператор:</small>
										<span>Biblioglobus</span>
									</div>
								</div>
								<div class="date">
									<div class="icon">
										<i class="ion-plane"></i>
									</div>
									<div class="data">
										<span>1 октября</span>
										<small>7 ночей</small>
									</div>
								</div>
								<div class="room">
									<div class="icon">
										<i class="ion-key"></i>
									</div>
									<div class="data">
										<small>Номер:</small>
										<span>family room</span>
									</div>
								</div>
								<div class="meal">
									<div class="icon">
										<i class="ion-fork"></i>
										<i class="ion-knife"></i>
									</div>
									<div class="data">
										<small>Питание:</small>
										<span>Все Включено</span>
									</div>
								</div>
								<div class="price">
									<div class="data">
										<a href="/tour/184531291399" class="btn btn-primary order" target="_blank">115 101 р.</a>
									</div>
								</div>
							</div>

							<div class="variants">
							</div>

							<div class="more">
								<a href="#">
									Показать еще туры
								</a>
							</div>
						</div>
					</div>

					<div class="row services">
						<div class="col-sm-6">
							{% if hotel.territory is defined %}
							<div class="grid-item">
								<h3 data-toggle="collapse" data-target="#collapse-territory">
									<div class="icon">
										<i class="ion-map"></i>
									</div>
									Территория
									<i class="ion-ios-arrow-down open"></i>
								</h3>
								<div class="collapse item" id="collapse-territory">
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
								<div class="collapse item" id="collapse-meallist">
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
								<div class="collapse item" id="collapse-inroom">
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
								<div class="collapse item" id="collapse-roomtypes">
									{{ hotel.roomtypes }}
								</div>
							</div>
							{% endif %}
						</div>
						<div class="col-sm-6">
							{% if hotel.servicefree is defined %}
							<div class="grid-item">
								<h3 data-toggle="collapse" data-target="#collapse-servicefree">
									<div class="icon">
										<i class="ion-thumbsup"></i>
									</div>
									Бесплатно
									<i class="ion-ios-arrow-down open"></i>
								</h3>
								<div class="collapse item" id="collapse-servicefree">
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
								<div class="collapse item" id="collapse-servicepay">
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
								<div class="collapse item" id="collapse-services">
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
								<div class="collapse item" id="collapse-child">
									{{ hotel.child }}
								</div>
							</div>
							{% endif %}
						</div>
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
						<dl class="dl-horizontal about">
							{% if hotel.build is defined %}
							<dt>Построен:</dt>
							<dd>{{ hotel.build }}</dd>
							{% endif %}

							{% if hotel.repair is defined %}
							<dt>Реставрация:</dt>
							<dd>{{ hotel.repair }}</dd>
							{% endif %}

							{% if hotel.placement is defined %}
							<dt>Расположен:</dt>
							<dd>{{ hotel.placement }}</dd>
							{% endif %}

							{% if hotel.phone is defined %}
							<dt>Телефон:</dt>
							<dd>{{ hotel.phone }}</dd>
							{% endif %}

							{% if hotel.site is defined %}
							<dt>Сайт:</dt>
							<dd>
								<a href="http://{{ hotel.site }}" target="_blank">{{ hotel.site }}</a>
							</dd>
							{% endif %}
						</dl>
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
</div>
