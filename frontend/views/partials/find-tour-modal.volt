<div class="modal fade" id="findTourModal" tabindex="-1" role="dialog" aria-labelledby="findTourModalLabel"
	 data-departures="Москва,С.Петербург,Екатеринбург"
	 data-countries="{{ formCountries }}"
	 data-regions="{{ formRegions }}"
	 data-from="{{ currentCity.departure.name }}"
	 data-from-id="{{ currentCity.departure.id }}"
>
	<div class="modal-dialog" role="document" data-toggle="validator">
		<div class="modal-content">
			<div class="loader">
				<div class="wrap">
					<div class="object"></div>
				</div>
			</div>
			<div class="wrap">
				<div class="modal-body steps">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="find-step-1">
							<h2 class="title">Мы поможем Вам с выбором идеального путешествия в три простых шага!</h2>
							<div class="form-group">
								<label for="find-departure">Введите город вылета:</label>
								<div class="departure">
									<input class="form-control" type="text" name="find-departure" id="find-departure" value="{{ currentCity.departure.name }}"/>
								</div>
							</div>
							<div class="buttons">
								<a class="btn btn-primary btn-lg pull-right" href="#find-step-2" data-toggle="tab">Поехали &rarr;</a>
							</div>
						</div>

						<div class="tab-pane fade" id="find-step-2">
							<h2 class="title">Где и когда хотите отдохнуть?</h2>
							<div class="where-wrap row">
								<div class="col-xs-6 left">
									<div class="form-group">
										<label for="find-where">Укажите страну или регион:</label>
										<div class="where">
											<input class="form-control" type="text" name="find-where" id="find-where" />
										</div>
									</div>
								</div>
								<div class="middle">
									<div class="or">или</div>
								</div>
								<div class="col-xs-6 right">
									<label>Выберите тип отдыха:</label>
									<ul class="types list-unstyled">
										<li>
											<input type="checkbox" id="find-type-beach" value="beach">
											<label for="find-type-beach">Пляжный</label>
										</li>
										<li>
											<input type="checkbox" id="find-type-excursion" value="excursion">
											<label for="find-type-excursion">Экскурсионный</label>
										</li>
										<li>
											<input type="checkbox" id="find-type-skiing" value="skiing">
											<label for="find-type-skiing">Горнолыжный</label>
										</li>
									</ul>
								</div>
							</div>
							<div class="when-wrap">
								<div class="form-group">
									<label for="find-when">Когда хотите поехать:</label>
									<div class="when">
										<input class="form-control" type="text" name="find-when" id="find-when"
											   placeholder="Например, в августе" />
									</div>
								</div>
							</div>
							<div class="buttons">
								<a class="btn btn-back pull-left" href="#find-step-1" data-toggle="tab">Назад</a>
								<a class="btn btn-primary btn-lg pull-right" href="#find-step-3" data-toggle="tab">Далее &rarr;</a>
							</div>
						</div>

						<div class="tab-pane fade" id="find-step-3">
							<h2 class="title">Еще немного подробностей...</h2>
							<div class="form-group">
								<label for="find-nights">На сколько ночей планируете поехать?</label>
								<div class="nights">
									<input class="form-control" type="text" name="find-nights" id="find-nights"
										   placeholder="Например, от 5 до 10 ночей" />
								</div>
							</div>
							<div class="form-group">
								<label for="find-people">Сколько человек едет?</label>
								<div class="people">
									<input class="form-control" type="text" name="find-people" id="find-people"
										   placeholder="Например, 2 человека" />
								</div>
							</div>
							<div class="form-group">
								<label for="find-hotel">Желаемый класс отеля</label>
								<div class="hotel">
									<input class="form-control" type="text" name="find-hotel" id="find-hotel"
										   placeholder="Например, 4 звезды и выше" />
								</div>
							</div>
							<div class="buttons">
								<a class="btn btn-back pull-left" href="#find-step-2" data-toggle="tab">Назад</a>
								<a class="btn btn-primary btn-lg pull-right" href="#find-step-4" data-toggle="tab">Далее &rarr;</a>
							</div>
						</div>
						<div class="tab-pane fade" id="find-step-4">
							<h2 class="title">Отправка заявки</h2>
							<div class="row">
								<div class="col-xs-6">
									<h4>Вы выбрали:</h4>
									<div class="well well-sm">
										<dl class="dl-horizontal" id="selected">
											<dt class="text-from">Город вылета:</dt>
											<dd class="from">{{ currentCity.departure.name }}</dd>
											<dt class="text-where hide">Куда:</dt>
											<dd class="where hide"></dd>
											<dt class="text-types hide">Тип отдыха:</dt>
											<dd class="types hide">
												<span class="beach hide">Пляжный</span>
												<span class="excursion hide">Экскурсионный</span>
												<span class="skiing hide">Горнолыжный</span>
											</dd>
											<dt class="text-when hide">Когда:</dt>
											<dd class="when hide"></dd>
											<dt class="text-nights hide">На сколько:</dt>
											<dd class="nights hide"></dd>
											<dt class="text-people hide">Кто едет:</dt>
											<dd class="people hide"></dd>
											<dt class="text-hotel hide">Класс отеля:</dt>
											<dd class="hotel hide"></dd>
										</dl>
									</div>
									<p class="message">
										Заявки на звонок, поступившие с 20:00 по 10:00 (МСК) обрабатываются на следующий день.
									</p>
								</div>
								<div class="col-xs-6 contacts">
									<h4>Контактные данные:</h4>
									<div class="form-group">
										<label for="find-name">Ваше имя</label>
										<input class="form-control" type="text" name="find-name" id="find-name"
											   placeholder="" required />
									</div>
									<div class="form-group">
										<label for="find-email">E-mail</label>
										<input class="form-control" type="text" name="find-email" id="find-email"
											   placeholder="" required />
									</div>
									<div class="form-group">
										<label for="find-phone">Телефон</label>
										<input class="form-control" type="text" name="find-phone" id="find-phone"
											   placeholder="" required />
									</div>
								</div>
							</div>
							<div class="buttons">
								<a class="btn btn-back pull-left" href="#find-step-3" data-toggle="tab">Назад</a>
								<a class="btn btn-primary btn-lg pull-right" id="sendFind" href="#" data-toggle="tab">Отправить заявку</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
