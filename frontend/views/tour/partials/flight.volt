<div class="flight{% if number == 0 %} active{% endif %}" data-fuel="{{ flight.fuelcharge.value }}" data-price="{{ flight.price.value }}" data-flight-id="{{ number }}">

	<div class="to" data-placement="top" data-toggle="tooltip" title="{{ flight.forward[0].company.name }}, рейс {{ flight.forward[0].number }}, {{ flight.forward[0].plane }}">
		<div class="departure">
			<div class="icon">
				<i class="ion-plane"></i>
			</div>
			<div class="data">
				<div class="date">{{ flight.forward[0].datefrom.format('d') }} <?=Utils\Text::humanize('months',(int) $flight->forward[0]->datefrom->format('m'));?></div>
				<div class="time">{{ flight.forward[0].datefrom.format('H:i') }}</div>
				<div class="airport">{{ flight.forward[0].departure.port.id }}, {{ flight.forward[0].departure.port.name }}</div>
			</div>
		</div>
		<div class="arrow">
			<i class="ion-chevron-right"></i>
		</div>
		<div class="arrival">
			<div class="icon" >
				<i class="ion-plane"></i>
			</div>
			<div class="data">
				<div class="date">{{ flight.forward[0].dateto.format('d') }} <?=Utils\Text::humanize('months',(int) $flight->forward[0]->dateto->format('m'));?></div>
				<div class="time">{{ flight.forward[0].dateto.format('H:i') }}</div>
				<div class="airport">{{ flight.forward[0].arrival.port.id }}, {{ flight.forward[0].arrival.port.name }}</div>
			</div>
		</div>
	</div>

	<div class="fuel">
		<div class="data">
			<div class="charge"><?=number_format($flight->fuelcharge->value, 0, '.', ' ');?> р.</div>
			<span>Топливный сбор</span>
		</div>
	</div>


	<div class="from" data-placement="top" data-toggle="tooltip" title="{{ flight.backward[0].company.name }}, рейс {{ flight.backward[0].number }}, {{ flight.backward[0].plane }}">
		<div class="departure">
			<div class="icon">
				<i class="ion-plane"></i>
			</div>
			<div class="data">
				<div class="date">{{ flight.backward[0].datefrom.format('d') }} <?=Utils\Text::humanize('months',(int) $flight->backward[0]->datefrom->format('m'));?></div>
				<div class="time">{{ flight.backward[0].datefrom.format('H:i') }}</div>
				<div class="airport">{{ flight.backward[0].departure.port.id }}, {{ flight.backward[0].departure.port.name }}</div>
			</div>
		</div>
		<div class="arrow">
			<i class="ion-chevron-right"></i>
		</div>
		<div class="arrival">
			<div class="icon" >
				<i class="ion-plane"></i>
			</div>
			<div class="data">
				<div class="date">{{ flight.backward[0].dateto.format('d') }} <?=Utils\Text::humanize('months',(int) $flight->backward[0]->dateto->format('m'));?></div>
				<div class="time">{{ flight.backward[0].dateto.format('H:i') }}</div>
				<div class="airport">{{ flight.backward[0].arrival.port.id }}, {{ flight.backward[0].arrival.port.name }}</div>
			</div>
		</div>
	</div>
</div>