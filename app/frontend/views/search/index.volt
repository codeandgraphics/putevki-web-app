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
							<label>Цена путевки</label>
							<input id="price" name="price" value="" />
						</div>

					</form>
				</div>

				<div class="tour-includes">
					В стоимость каждой путевки входит:
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
					<li class="current">Выбор путевки</li>
					<li>&rarr;</li>
					<li>Перелет и оформление</li>
				</ul>
				<div class="departure"></div>
			</div>
			<div class="search-banners">
			</div>
			<div class="content tours list">
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
								<div class="text">Стоимость путевки в этот отель:</div>
								<div class="sum">
									от <span></span> р.
								</div>
								<div class="other">
									<a href="#" class="variants-open internal"><span>показать путевки</span></a>
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
									<span>Показать еще путевки</span>
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
							<span>Ищем лучшие путевки для вас...</span>
						</div>
					</div>
				</div>

				<div class="help block">
					<h4>Не удается найти нужную путевку? Мы поможем!</h4>
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