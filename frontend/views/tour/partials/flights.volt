{% set flightsNum = tour.flights|length %}
<div class="flights">
	<h2>Перелет</h2>
	{% if flightsNum > 0 %}
		{% set flight = tour.flights[0] %}

		{% if flight.forward and flight.backward %}
			<div class="items">
				{{ partial('tour/partials/flight', ['flight': flight, 'number': 0]) }}
				{% if flightsNum > 1 %}
					<div class="variants" data-toggle="collapse" data-target="#more-flights">
						<a href="#">есть другие варианты перелета</a>
					</div>

					<div class="collapse" id="more-flights">
						{% for i in 1..(flightsNum-1) %}
							{{ partial('tour/partials/flight', ['flight': tour.flights[i], 'number': i ]) }}
						{% endfor %}
					</div>

				{% endif %}
			</div>

		{% else %}
			<div class="no-flights">
				<p>Не удалось найти информацию о перелетах.</p>
				<p>Для уточнения информации <a href="#" data-toggle="modal" data-target="#callBackModal">закажите звонок</a>, или позвоните нам по телефону {{ currentCity.phone }}</p>
			</div>

		{% endif %}


	{% else %}
		<div class="no-flights">
			<p>Не удалось найти информацию о перелетах.</p>
			<p>Для уточнения информации <a href="#" data-toggle="modal" data-target="#callBackModal">закажите звонок</a>, или позвоните нам по телефону {{ currentCity.phone }}</p>
		</div>
	{% endif %}
</div>

