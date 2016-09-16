<div class="hero little">
	<div class="container">
		<form class="form-inline" action="" method="get" id="searchForm"
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
</div>


<div class="page" id="search" data-tourvisorId="{{ tourvisorId }}">
	<div class="container search">
		<div class="row no-gutter">
			<aside class="sidebar left">
				<div class="progressbar head">
					<div class="bar" style="width:0%;"></div>
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
								Найдено еще <span class="tours-found"></span>
								<p class="cheaper-found"> на <span class="price-found"></span> дешевле</p>
								<p class="other-found">по цене от <span class="price-found"></span></p>
								<button class="btn btn-default show-finished">Показать все</button>
							</div>
						</div>
						<form class="form" id="filters">

							<div class="hotel">
								<div class="form-group ion-ios-search-strong">
									<label for="filters-hotel">Название отеля:</label>
									<input type="text" id="filters-hotel" placeholder=""/>
								</div>
							</div>

							<div class="form-block">
								<label>Тип отеля</label>

								<ul class="list-unstyled" id="types">
									<li>
										<input type="checkbox" id="type-active" value="active" checked="checked">
										<label for="type-active">Активный</label>
									</li>
									<li>
										<input type="checkbox" id="type-relax" value="relax" checked="checked">
										<label for="type-relax">Спокойный</label>
									</li>
									<li>
										<input type="checkbox" id="type-family" value="family" checked="checked">
										<label for="type-family">Семейный</label>
									</li>
									<li>
										<input type="checkbox" id="type-health" value="health" checked="checked">
										<label for="type-health">Лечебный</label>
									</li>
									<li>
										<input type="checkbox" id="type-city" value="city" checked="checked">
										<label for="type-city">Городской</label>
									</li>
									<li>
										<input type="checkbox" id="type-beach" value="beach" checked="checked">
										<label for="type-beach">Пляжный</label>
									</li>
									<li>
										<input type="checkbox" id="type-deluxe" value="deluxe" checked="checked">
										<label for="type-deluxe">Эксклюзивный</label>
									</li>
								</ul>
							</div>

							<div class="form-block">
								<label>Цена тура</label>
								<input type="text" id="price" name="price" value="" />
							</div>

						</form>
					</div>

					<div class="tour-includes">
						В стоимость каждого тура входит:
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
						<li>1. Выбор направления</li>
						<li>&rarr;</li>
						<li class="current">2. Выбор тура</li>
						<li>&rarr;</li>
						<li>3. Перелет и оформление</li>
					</ul>
					<div class="departure">{% if params.departure.id != 99 %}Вылет из {% endif %}{{ params.departure.name_from }}</div>
				</div>
				<div class="search-banners">
				</div>
				<div class="content tours list">
					<div class="params">
						<div class="dropdown stars">
							Звезд:
							<button class="dropdown-toggle" type="button" id="starsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<div class="text">
									<?php
									$star = (int) $params->stars->name;
									for($i=1;$i<=$star;$i++){
										echo '<i class="ion-star"></i>';
									}
									?>
									<?=($params->stars->id == 6) ? '<span class="label label-warning">эксклюзив</span>': "и выше";?>
								</div>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="starsMenu">
								<li>
									<a href="#" data-stars="6">
										<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
										<span class="label label-warning">эксклюзив</span>
									</a>
								</li>
								<li role="separator" class="divider"></li>
								<li>
									<a href="#" data-stars="5">
										<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
										и выше
									</a>
								</li>
								<li>
									<a href="#" data-stars="4">
										<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
										и выше
									</a>
								</li>
								<li>
									<a href="#" data-stars="3">
										<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
										и выше
									</a>
								</li>
								<li>
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
									<?=$params->meal->name;?> <?=($params->meal->name !== 'UAI') ? 'и выше':'';?>
								</div>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="mealMenu">
								<?php
								foreach($meals as $meal)
								{
									?>
									<li>
										<a href="#" data-meal="<?=$meal->id;?>">
											<?=$meal->name;?> <?=($meal->name !== 'UAI') ? 'и выше':'';?>
											<small><?=$meal->russian;?></small>
										</a>
									</li>
									<?php
									if($meal->name === 'AI')
									{
										?>
										<li role="separator" class="divider"></li>
										<?php
									}
								}
								?>
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
										<div class="bg" style="background-image: url();"></div>
									</a>
								</div>
								<div class="price">
									<div class="text">Стоимость путевки в этот отель:</div>
									<div class="sum">
										<a href="#" class="btn btn-primary order" target="_blank"></a>
									</div>
									<div class="other">
										<a href="#" class="variants-open">показать другие варианты</a>
										<a href="#" class="variants-close">закрыть</a>
									</div>
								</div>
								<div class="about">
									<h4 class="title">
										<a href="#" target="_blank"></a>
									</h4>
									<div class="rating">
										<span class="stars">
										</span>
										<span class="review">
											<strong></strong> &mdash; <span></span>
										</span>
									</div>
									<ul class="types list-unstyled list-inline">
									</ul>
									<div class="description">
										Описания пока нет :(
									</div>
								</div>

								<div class="icons">
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
									<a href="#">
										Показать еще туры
									</a>
								</div>
							</div>

						</div>
					</div>

					<div class="more-results block">
						<a href="#">Показать еще</a>
						<div class="loader">
							<div class="wrap">
								<div class="object"></div>
								<span>Ищем лучшие предложения для вас...</span>
							</div>
						</div>
					</div>

					<div class="help block">
						<p>Нужна помощь с поиском туров?</p>
						<button class="btn btn-primary with-icon" data-toggle="modal" data-target="#callBackModal">
							<div class="icon">
								<i class="ion-ios-telephone"></i>
							</div>
							<div class="data">
								<span>Позвоните мне!</span>
								<small>Мы поможем с подбором</small>
							</div>
						</button>
						или
						<button class="btn btn-default with-icon" data-toggle="modal" data-target="#findTourModal">
							<div class="icon">
								<i class="ion-ios-email"></i>
							</div>
							<div class="data">
								<span>Напишите мне!</span>
								<small>Подберем и отправим на email</small>
							</div>
						</button>
					</div>

				</div>
			</section>
		</div>
	</div>
</div>