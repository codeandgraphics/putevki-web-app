<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Все заявки</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<a href="{{ url('requests/add') }}" class="btn btn-success btn-stroke btn-sm pull-right">Создать заявку</a>
		<h4 class="panel-title">Все заявки</h4>

		<p>...</p>
	</div>
	<div class="panel-body">
		<div class="table_pagination">
			<table class="table table-striped" id="requests">
				<thead>
				<tr>
					<th>&nbsp;</th>
					<th>
						Заказчик
						<small>Адрес</small>
					</th>
					<th>
						Дата
						<small>Время</small>
					</th>
					<th>
						Страна
						<small>Вылет</small>
					</th>
					<th class="text-center">
						Дата
						<small>Ночей</small>
					</th>
					<th class="text-center">
						Оператор
						<small>&nbsp;</small>
					</th>
					<th class="text-center">
						Статус
						<small>&nbsp;</small>
					</th>
					<th class="text-center">
						Сумма
						<small>оплачено</small>
					</th>
					<th class="text-center">
						Офис
						<small>Менеджер</small>
					</th>
					<th>
						&nbsp;
					</th>
				</tr>
				</thead>
				<tbody>
				{% for req in page.items %}
					{% set paid = req.getPaid() %}
					{% set payStatus = 'partial' %}
					{% if req.price <= paid %}
						{% set payStatus = 'full' %}
					{% endif %}
					{% if paid == 0 %}
						{% set payStatus = 'no' %}
					{% endif %}
				<tr class="header">
					<td>
						<button class="btn btn-default more">
							<i class="fa fa-arrow-down"></i>
						</button>
					</td>
					<td>
						<a href="{{ url('requests') }}/edit/{{ req.id }}">
						{% if req.subjectName and req.subjectSurname %}
							{{ req.subjectName }} {{ req.subjectSurname }}
						{% else %}
							не указан
						{% endif %}
							<small>
							{% if req.subjectAddress %}
								{{ req.subjectAddress }}
							{% else %}
								не указан
							{% endif %}
							</small>
						</a>

					</td>
					<td>
						{{ req.creationDate }}
					</td>
					<td>
						{% if req.hotelCountry %}
							{{ req.hotelCountry }}
						{% else %}
							не указана
						{% endif %}
						<small>
						{% if req.departure %}
							из {{ req.departure.name_from }}
						{% else %}
							не указан
						{% endif %}
						</small>
					</td>
					<td class="text-center">
						{% if req.flightToDepartureDate %}
							<?=Utils\Text::formatToDayMonth($req->flightToDepartureDate, 'd.m.Y');?>
						{% else %}
							не указана
						{% endif %}
						<small>
						{% if req.hotelNights %}
							<?=Utils\Text::humanize('nights', $req->hotelNights);?>
						{% else %}
							не указано
						{% endif %}
						</small>
					</td>
					<td class="text-center">
						{% if req.tourOperator %}
							{{ req.tourOperator.name }}
						{% else %}
							не указан
						{% endif %}
					</td>
					<td class="text-center text-middle">
						{% if req.status %}
						<div class="label {{ req.status.class }}">
							{{ req.status.name }}
						</div>
						{% else %}
						не указан
						{% endif %}
					</td>
					<td class="text-center price {{ payStatus }}">
						{{ req.getSum() }} руб.
						<small>{{ paid }} руб.</small>
					</td>
					<td class="text-center">
						{% if req.branch %}
						{{ req.branch.name }}
							{% if req.branch.manager %}
								<small>{{ req.branch.manager.name }}</small>
							{% endif %}
						{% else %}
						не указан
						{% endif %}
					</td>
					<td>
						<a href="{{ url('requests') }}/edit/{{ req.id }}" class="btn btn-warning edit pull-right">
							<i class="fa fa-edit"></i>
						</a>
					</td>
				</tr>
				<tr class="data">
					<td colspan="10">
						<div class="row">
							<div class="col-sm-4">
								<div class="info-group label-bottom">
									Заказчик
									<label>{{ req.subjectName }} {{ req.subjectPatronymic }} {{ req.subjectSurname }}</label>

									Телефон
									<label>{{ req.subjectPhone }}</label>

									E-mail
									<label><a href="mailto:{{ req.subjectEmail }}">{{ req.subjectEmail }}</a></label>

									Адрес
									<label>{{ req.subjectAddress }}</label>
								</div><br/>
								<a class="btn btn-warning btn-stroke btn-block" href="{{ url('requests/edit/') }}{{ req.id }}">
									<i class="fa fa-edit"></i>
									Редактировать
								</a>
							</div>
							<div class="col-sm-8">
								<div class="info-group">
									<p>
										<a href="{{ url('requests/agreement/') }}{{ req.id }}" target="_blank" class="btn btn-default btn-xs file">
											<i class="fa fa-file-pdf-o"></i>
											<span>Договор с заказчиком</span>
										</a>
										&nbsp;
										<a href="{{ url('requests/booking/') }}{{ req.id }}" target="_blank" class="btn btn-default btn-xs file">
											<i class="fa fa-file-pdf-o"></i>
											<span>Лист бронирования</span>
										</a>
									</p>
									<table class="table">
										<thead>
											<tr>
												<th>Данные заказа</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<i class="fa fa-hotel"></i>
													<span class="text-primary">{{ req.hotelName }}</span>,
													{{ req.hotelCountry }}, {{ req.hotelRegion }}
												</td>
											</tr>
											<tr>
												<td>
													<i class="fa fa-hotel"></i> {{ req.hotelMeal }}, {{ req.hotelPlacement }}, {{ req.hotelRoom }},
													{{ req.flightToArrivalDate }},
													<?=Utils\Text::humanize('nights', $req->hotelNights);?>
												</td>
											</tr>
											<tr>
												<td>
													<i class="fa fa-plane"></i>
													{{ req.flightToDepartureDate }}, {{ req.flightToNumber }},
													{{ req.flightToDepartureTerminal }} {{ req.flightToDepartureTime }}
													-  {{ req.flightToArrivalTerminal }} {{ req.flightToArrivalTime }}
												</td>
											</tr>
											<tr>
												<td>
													<i class="fa fa-plane"></i>
													{{ req.flightFromDepartureDate }}, {{ req.flightFromNumber }},
													{{ req.flightFromDepartureTerminal }} {{ req.flightFromDepartureTime }}
													-  {{ req.flightFromArrivalTerminal }} {{ req.flightFromArrivalTime }}
												</td>
											</tr>
										</tbody>
									</table>
									<br/>
									<table class="table">
										<thead>
										<tr>
											<th>Турист</th>
											<th>Дата рождения</th>
											<th>Загранпаспорт</th>
											<th>Действителен до</th>
										</tr>
										</thead>
										<tbody>
										{% for tourist in req.tourists %}
										<tr>
											<td>{{ tourist.tourist.passport_surname }} {{ tourist.tourist.passport_name }}</td>
											<td>{{ tourist.tourist.birthDate }}</td>
											<td>{{ tourist.tourist.passport_number }}</td>
											<td>{{ tourist.tourist.passport_endDate }}</td>
										</tr>
										{% endfor %}
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</td>
				</tr>
				{% endfor %}
				</tbody>
			</table>

			{% if page.items %}
			<ul class="pagination">
				{% if page.before != page.current %}
				<li class="paginate_button">
					<a href="{{ url('requests') }}?page={{ page.before }}">назад</a>
				</li>
				{% else %}
				<li class="paginate_button disabled">
					<span>
						назад
					</span>
				</li>
				{% endif %}

				{% for i in 1..page.total_pages %}
				<li class="paginate_button{% if page.current == i %} active{% endif %}">
					<a href="{{ url('requests') }}?page={{ i }}">{{ i }}</a>
				</li>
				{% endfor %}

				{% if page.next != page.current %}
				<li>
					<a href="{{ url('requests') }}?page={{ page.next }}">вперед</a>
				</li>
				{% else %}
				<li class="disabled">
					<span>
						вперед
					</span>
				</li>
				{% endif %}
			</ul>
			{% endif %}
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#requests button.more').on('click', function(){
			var $row = $(this).parent().parent();
			var $nextRow = $row.next();

			if($nextRow.hasClass('shown'))
			{
				$(this).find('i').removeClass('fa-arrow-up');
				$(this).find('i').addClass('fa-arrow-down');
				$nextRow.removeClass('shown');
			}
			else
			{
				$(this).find('i').removeClass('fa-arrow-down');
				$(this).find('i').addClass('fa-arrow-up');
				$nextRow.addClass('shown');
			}

			return false;
		});

	});
</script>