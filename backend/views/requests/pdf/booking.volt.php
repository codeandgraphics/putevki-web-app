<link rel="stylesheet" href="<?php echo $this->url->get('assets/css/pdf/style.css'); ?>" />
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
					<td class="left"><?php echo $req->tourOperator->name; ?></td>
				</tr>
				<tr>
					<td class="right">Номер договора:</td>
					<td class="left">№<?php echo $req->getNumber(); ?></td>
				</tr>
				<tr>
					<td class="right">Дата бронирования:</td>
					<td class="left"><?php echo $req->getDate(); ?></td>
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
	<?php foreach ($req->tourists as $tourist) { ?>
	<tr>
		<td class="left"><?php echo $tourist->tourist->passport_surname; ?> <?php echo $tourist->tourist->passport_name; ?></td>
		<td><?php echo $tourist->tourist->birthDate; ?></td>
		<td><?php echo $tourist->tourist->passport_number; ?></td>
		<td class="right"><?php echo $tourist->tourist->passport_endDate; ?></td>
	</tr>
	<?php } ?>
	</tbody>
</table>


<h1>Перелет</h1>
<?php if ($req->flightToDepartureDate && $req->flightFromDepartureDate) { ?>
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
	<tr>
		<td class="left">
			<div class="number">
				<?php echo $req->flightToNumber; ?>
			</div>
			<div class="terminal">
				<?php echo $req->flightToDepartureTerminal; ?> - <?php echo $req->flightToArrivalTerminal; ?>
			</div>
		</td>
		<td>
			<?php echo $req->flightToDepartureDate; ?><br/>
			в <?php echo $req->flightToDepartureTime; ?>
		</td>
		<td>
			<?php echo $req->flightToArrivalDate; ?><br/>
			в <?php echo $req->flightToArrivalTime; ?>
		</td>
		<td>
			<?php if ($req->flightToClass) { ?>
				<?php echo $req->flightToClass; ?>
			<?php } else { ?>
				эконом
			<?php } ?>
		</td>
	</tr>
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
	<tr>
		<td class="left">
			<div class="number">
				<?php echo $req->flightFromNumber; ?>
			</div>
			<div class="terminal">
				<?php echo $req->flightFromDepartureTerminal; ?> - <?php echo $req->flightFromArrivalTerminal; ?>
			</div>
		</td>
		<td>
			<?php echo $req->flightFromDepartureDate; ?><br/>
			в <?php echo $req->flightFromDepartureTime; ?>
		</td>
		<td>
			<?php echo $req->flightFromArrivalDate; ?><br/>
			в <?php echo $req->flightFromArrivalTime; ?>
		</td>
		<td>
			<?php if ($req->flightFromClass) { ?>
				<?php echo $req->flightFromClass; ?>
			<?php } else { ?>
				эконом
			<?php } ?>
		</td>
	</tr>
	</tbody>
</table>
<p class="help">
	* Информация о рейсах и времени вылета является справочной. Время вылета (в рамках суток) и авиакомпания могут быть изменены по усмотрению туроператора.
</p>
<?php } else { ?>
<div class="message">
	Нет информации о рейсах. Туроператор предоставит информацию за несколько дней перед вылетом.
</div>
<?php } ?>

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
			<?php echo $req->hotelName; ?>
			<div><?php echo $req->hotelCountry; ?>, <?php echo $req->hotelRegion; ?></div>
		</td>
		<td>
			<?php echo $req->hotelMeal; ?>
		</td>
		<td>
			<?php echo $req->hotelPlacement; ?>
		</td>
		<td>
			<?php echo $req->hotelRoom; ?>
		</td>
		<td>
			<?php echo $req->flightToArrivalDate; ?>
			<div><?=Utils\Text::humanize('nights', $req->hotelNights);?></div>
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
				Стоимость услуг по договору № составляет <?php echo $req->price; ?> руб.
			</p>
		</td>
	</tr>
</table>


<h1>Сведения о туроператоре</h1>
<p class="operator">
	<strong><?php echo $req->tourOperator->name; ?> (<?php echo $req->tourOperator->legal; ?>)</strong><br/>
	<?php echo $req->tourOperator->guarantee; ?>
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
