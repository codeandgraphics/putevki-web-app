<div class="container bottomCountries">
	<h2 class="centered">Куда поехать отдыхать?</h2>
	<div class="row">
{% for item in countries %}
		<div class="col-xs-2 country">
			<img src="//static.tourvisor.ru/flags/calendar/flag_{{ item.tourvisor.id }}.gif" />
			<a href="{{ url('countries') }}/{{ item.country.uri }}">{{ item.tourvisor.name }}</a>
		</div>
{% endfor %}
	</div>
</div>

<div id="upButton">
	<i class="ion-chevron-up"></i>
</div>