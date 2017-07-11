<link rel="stylesheet" href="{{ static_url('static/css/pdf/style.css') }}" />
<div class="header-wrap">&nbsp;</div>
<table class="title">
	<tr>
		<td class="left top">
			<h1 class="main">Лист бронирования</h1>
		</td>
		<td width="40%">
			<table>
				<tr>
					<td class="right">Туроператор:</td>
					<td class="left">{{ req.tourOperator.name }}</td>
				</tr>
				<tr>
					<td class="right">Номер договора:</td>
					<td class="left">№{{ req.getNumber() }}</td>
				</tr>
				<tr>
					<td class="right">Дата бронирования:</td>
					<td class="left">{{ req.getDate() }}</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


<h1>Туристы</h1>
<table class="tourists bordered">
	<thead>
	<tr>
		<th class="left">Турист</th>
		<th width="17%">Дата рождения</th>
		<th width="16%">Загранпаспорт</th>
		<th width="18%" class="right">Действителен до</th>
	</tr>
	</thead>
	<tbody>
	{% for tourist in req.tourists %}
	<tr>
		<td class="left">{{ tourist.tourist.passport_surname }} {{ tourist.tourist.passport_name }}</td>
		<td>{{ tourist.tourist.birthDate }}</td>
		<td>{{ tourist.tourist.passport_number }}</td>
		<td class="right">{{ tourist.tourist.passport_endDate }}</td>
	</tr>
	{% endfor %}
	</tbody>
</table>


<h1>Перелет</h1>
{% set flightsTo = req.getFlights('To') %}
{% set flightsFrom = req.getFlights('From') %}

{% if flightsTo|length > 0 and flightsFrom|length > 0 %}
<table class="flight bordered">
	<thead>
	<tr>
		<th class="left">Перелет туда*</th>
		<th>Вылет</th>
		<th>Прилет</th>
		<th>Класс</th>
	</tr>
	</thead>
	<tbody>
	{% for flight in flightsTo %}
	<tr>
		<td class="left">
			<div class="number">
				{{ flight.number }}
			</div>
			<div class="terminal">
				{{ flight.departure.port }} – {{ flight.arrival.port }}
			</div>
		</td>
		<td>
			{{ flight.departure.date }}<br/>
			в {{ flight.departure.time }}
		</td>
		<td>
			{{ flight.arrival.date }}<br/>
			в {{ flight.arrival.time }}
		</td>
		<td>
			{% if req.flightToClass %}
				Class
			{% else %}
				эконом
			{% endif %}
		</td>
	</tr>
	{% endfor %}
	</tbody>

	<thead>
	<tr>
		<th class="left">Перелет обратно*</th>
		<th>Вылет</th>
		<th>Прилет</th>
		<th>Класс</th>
	</tr>
	</thead>
	<tbody>
	{% for flight in flightsFrom %}
		<tr>
			<td class="left">
				<div class="number">
					{{ flight.number }}
				</div>
				<div class="terminal">
					{{ flight.departure.port }} – {{ flight.arrival.port }}
				</div>
			</td>
			<td>
				{{ flight.departure.date }}<br/>
				в {{ flight.departure.time }}
			</td>
			<td>
				{{ flight.arrival.date }}<br/>
				в {{ flight.arrival.time }}
			</td>
			<td>
				{% if req.flightToClass %}
					Class
				{% else %}
					эконом
				{% endif %}
			</td>
		</tr>
	{% endfor %}
	</tbody>
</table>
<p class="help">
	* Информация о рейсах и времени вылета является справочной. Время вылета (в рамках суток) и авиакомпания могут быть изменены по усмотрению туроператора.
</p>
{% else %}
<div class="message">
	Нет информации о рейсах. Туроператор предоставит информацию за несколько дней перед вылетом.
</div>
{% endif %}


{% set hotel = req.getHotel() %}
<h1>Проживание</h1>
<table class="placement bordered">
	<thead>
	<tr>
		<th class="left">Отель</th>
		<th>Питание</th>
		<th>Размещение</th>
		<th>Номер</th>
		<th>Дата</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="left">
			{{ hotel.name }}
			<div>{{ hotel.country }}, {{ hotel.region }}</div>
		</td>
		<td>
			{{ hotel.meal }}
		</td>
		<td>
			{{ hotel.placement }}
		</td>
		<td>
			{{ hotel.room }}
		</td>
		<td>
			{{ hotel.date }}
			<div><?=Utils\Text::humanize('nights', $hotel->nights);?></div>
		</td>
	</tr>
	</tbody>
</table>

<table class="services">
	<tr>
		<td width="50%" class="left top">
			<h1>Дополнительные услуги</h1>
			<ul>
				<li>Трансфер групповой</li>
				<li>Медицинская страховка</li>
			</ul>
		</td>
		<td width="50%" class="left top">
			<h1>Стоимость услуг</h1>
			<p class="price">
				Стоимость услуг по договору № составляет {{ req.price }} руб.
			</p>
		</td>
	</tr>
</table>


<h1>Сведения о туроператоре</h1>
<p class="operator">
	<strong>{{ req.tourOperator.name }} ({{ req.tourOperator.legal }})</strong><br/>
	{{ req.tourOperator.guarantee }}
</p>

<h2 class="rules">Правила бронирования</h2>
<table class="rules">
	<tr>
		<td class="left top">
			<ol>
				<li>Заказчик осуществляет бронирование тура самостоятельно на сайте www.putevki.ru</li>
				<li>Заказчик несет полную ответственность за достоверность предоставляемых данных
			необходимых для бронирования, в случае указания неверных сведений в процессе
			бронирования претензии от Заказчика не принимаются.</li>
				<li>В завершении бронирования Заказчику предоставляется Лист бронирования,
			являющийся неотъемлемой частью данного договора, в котором содержатся подробные
			сведения о туристическом продукте и окончательная стоимость тура. Также
			предоставляются сведения о туроператоре и о его финансовых гарантиях.</li>
				<li>Оплата услуг Заказчиком означает его согласие с условиями тура и данного договора.</li>
				<li>Турагент бронирует тур у Туроператора после оплаты тура Заказчиком и в течение 3х
			суток предоставляет Заказчику подтверждение бронирования по электронной почте,
			факсу или по телефону, после чего тур считается забронированным.</li>
			</ol>
		</td>
		<td class="left top" width="50%">
			<ol start="6">
				<li>В случае невозможности подтверждения заявки Заказчика вследствие лимита
					авиабилетов, отсутствия мест в отелях, отказа туроператора и/или иных обстоятельств не
					позволяющих предоставить комплекс заказанных туристских услуг, Турагент уведомляет
					об этом Заказчика не менее чем за 1 день до даты выезда. По соглашению сторон
					Турагент предлагает Заказчику альтернативные варианты (при их наличии) по
					согласованным срокам поездки и условиям размещения, либо расторгает настоящий
					Договор с соблюдением установленного порядка, возвращая полную стоимость
					туристического продукта.</li>
				<li>Выдача комплекта документов, необходимого для совершения поездки (авиабилеты,
					ваучер, медицинская страховка) производится на фирменной стойке туроператора в
					аэропорту вылета, за 2,5-3 часа до вылета. В случае, если возможна выписка электронных
					билетов, комплект документов высылается на электронную почту заказчика за 2-3 дня до
					вылета.</li>
			</ol>
		</td>
	</tr>
</table>

<!--<pagebreak type="NEXT-ODD" />-->
