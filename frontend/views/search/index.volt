<div class="page" id="search" data-searchId="{{ searchId }}" data-no-image="{{ static_url('img/no-image.png') }}">
	<div class="container flex search">
		<aside class="sidebar left">
			<div class="progressbar head">
				<div class="bar" style="width:0;"></div>
				<div class="percent">
					<span class="count">0</span> <span class="text">отелей найдено</span>
				</div>

				<div class="loader">
					<div class="wrap">
						<div class="object"></div>
					</div>
				</div>
			</div>
			<div class="content filters">
				<div class="wrap">
					<div class="overlay">
						<div class="filters-loading">
							<i class="ion-loop"></i>
							<small>Готовим фильтры...</small>
						</div>
					</div>
					<div class="search-process">
						<div class="found">
							Нашли ещё <span class="tours-found"></span>
							<p class="cheaper-found"> на <span class="price-found"></span> дешевле</p>
							<p class="other-found">по цене от <span class="price-found"></span></p>
							<button class="btn btn-default show-finished">Показать все</button>
						</div>
					</div>
					<form class="form" id="filters">

						<div class="hotel">
							<div class="form-group ion-ios-search-strong">
								<label for="filters-hotel">Название отеля:</label>
								<input id="filters-hotel" placeholder=""/>
							</div>
						</div>

						<div class="form-block">
							<label>Тип отеля</label>

							<ul class="list-unstyled" id="types">
								<li>
									<input type="checkbox" id="type-active" value="active" checked>
									<label for="type-active">Активный</label>
								</li>
								<li>
									<input type="checkbox" id="type-relax" value="relax" checked>
									<label for="type-relax">Спокойный</label>
								</li>
								<li>
									<input type="checkbox" id="type-family" value="family" checked>
									<label for="type-family">Семейный</label>
								</li>
								<li>
									<input type="checkbox" id="type-health" value="health" checked>
									<label for="type-health">Лечебный</label>
								</li>
								<li>
									<input type="checkbox" id="type-city" value="city" checked>
									<label for="type-city">Городской</label>
								</li>
								<li>
									<input type="checkbox" id="type-beach" value="beach" checked>
									<label for="type-beach">Пляжный</label>
								</li>
								<li>
									<input type="checkbox" id="type-deluxe" value="deluxe" checked>
									<label for="type-deluxe">Эксклюзивный</label>
								</li>
							</ul>
						</div>

						<div class="form-block">
							<label>Цена путёвки</label>
							<input id="price" name="price" value="" />
						</div>

					</form>
				</div>

				<div class="tour-includes">
					В стоимость каждой путёвки входит:
					<dl class="dl-horizontal">
						<dt><i class="ion-plane"></i></dt>
						<dd>Перелет</dd>
						<dt><i class="ion-key"></i></dt>
						<dd>Проживание в отеле</dd>
						<dt><i class="ion-fork"></i><i class="ion-knife"></i></dt>
						<dd>Питание</dd>
						<dt><i class="ion-model-s"></i></dt>
						<dd>Трансфер</dd>
						<dt><i class="ion-medkit"></i></dt>
						<dd>Медицинская страховка</dd>
					</dl>
				</div>
			</div>
		</aside>

		<section class="main right">
			<div class="steps head">
				<ul class="list-inline">
					<li class="current">Выбор путёвки</li>
					<li>&rarr;</li>
					<li>Перелет и оформление</li>
				</ul>
				<div class="departure"></div>
			</div>
			<div class="search-banners">
			</div>
			<div class="content tours list">
				<div class="params">
					<div class="dropdown stars">
						Звезд:
						<button class="dropdown-toggle" type="button" id="starsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<div class="text">
								{% set star = params.search.starsEntity() %}
								{% for i in 1..star.name %}<i class="ion-star"></i>{% endfor %}
								{% if star.id == 6 %}<span class="label label-warning">эксклюзив</span>{% else %}и выше{% endif %}
							</div>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="starsMenu">
							<li{% if star.id == 6 %} class="active"{% endif %}>
								<a href="#" data-stars="6">
									<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
									<span class="label label-warning">эксклюзив</span>
								</a>
							</li>
							<li role="separator" class="divider"></li>
							<li{% if star.id == 5 %} class="active"{% endif %}>
								<a href="#" data-stars="5">
									<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
									и выше
								</a>
							</li>
							<li{% if star.id == 4 %} class="active"{% endif %}>
								<a href="#" data-stars="4">
									<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
									и выше
								</a>
							</li>
							<li{% if star.id == 3 %} class="active"{% endif %}>
								<a href="#" data-stars="3">
									<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
									и выше
								</a>
							</li>
							<li{% if star.id == 2 %} class="active"{% endif %}>
								<a href="#" data-stars="2">
									<i class="ion-star"></i><i class="ion-star"></i>
									и выше
								</a>
							</li>
						</ul>
					</div>

					<div class="dropdown meals">
						Питание:
						<button class="dropdown-toggle" type="button" id="mealMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<i class="ion-fork"></i> <i class="ion-knife"></i> &nbsp;
							<div class="text">
								{% set meal = params.search.mealsEntity() %}
								{{ meal.name }} {% if meal.name !== 'UAI' %}и выше{% endif %}
							</div>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="mealMenu">
							{% for item in meals %}
								<li{% if item.name == meal.name %} class="active"{% endif %}>
									<a href="#" data-meal="{{ item.id }}">
										{{ item.name }} {% if item.name != 'UAI' %}и выше{% endif %}
										<small>{{ item.russian }}</small>
									</a>
								</li>
								{% if item.name == 'AI' %}
									<li role="separator" class="divider"></li>
								{% endif %}
							{% endfor %}
						</ul>
					</div>
				</div>
				<div class="no-results block">
					<i class="ion-sad-outline"></i>
					Извините, ничего не найдено...
				</div>
				<div class="items">
					<div class="item template" data-name="" data-price="">
						<div class="info">
							<div class="image">
								<a href="#" target="_blank">
									<div class="bg" style="background-image: url('');"></div>
								</a>
							</div>
							<div class="price">
								<div class="text">Стоимость путёвки в этот отель:</div>
								<div class="sum">
									от <span></span> р.
								</div>
								<div class="other">
									<a href="#" class="variants-open internal"><span>показать путёвки</span></a>
									<a href="#" class="variants-close internal"><span>скрыть</span></a>
								</div>
							</div>
							<div class="about">
								<h4 class="title">
									<a href="#" target="_blank"></a>
								</h4>
								<div class="rating">
									<span class="stars"></span>
									<span class="review">
												<strong></strong> &mdash; <span></span>
											</span>
								</div>
								<ul class="types list-unstyled list-inline">
								</ul>
								<div class="place">
									<i class="ion-location"></i>
									<span></span>
								</div>
								<div class="description">
									Описания пока нет :(
								</div>
							</div>
						</div>

						<div class="variants">
							<div class="items">
								<div class="variant template">
									<div class="operator">
										<div class="icon">
											<img src="" data-src="//tourvisor.ru/pics/operators/searchlogo/{id}.gif"/>
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
											<a href="#" class="btn btn-primary order" target="_blank"></a>
										</div>
									</div>
								</div>
							</div>
							<div class="more">
								<a href="#" class="internal">
									<span>Показать еще путёвки</span>
								</a>
							</div>
						</div>

					</div>
				</div>

				<div class="more-results block">
					<a href="#" class="internal">
						<span>Показать еще</span>
					</a>
					<div class="loader">
						<div class="wrap">
							<div class="object"></div>
							<span>Ищем лучшие путёвки для вас...</span>
						</div>
					</div>
				</div>

				<div class="help block">
					<h4>Не удается найти нужную путёвку? Мы поможем!</h4>
					<p>
						Просто оставьте нам свой номер телефона,
						и наши опытные менеджеры подберут для вас путешествие мечты!
					</p>
					<button class="btn btn-default with-icon" data-toggle="modal" data-target="#callBackModal">
							<span class="icon">
								<i class="ion-ios-telephone"></i>
							</span>
						<span class="data">
								Позвоните мне!
								<small>Мы поможем с подбором</small>
							</span>
					</button>
				</div>
			</div>
		</section>
	</div>
</div>