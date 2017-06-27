<div class="container countries">
	<h2 class="centered">Куда поехать отдыхать?</h2>
	<div class="row">
{% for country in countries %}
		<div class="col-xs-2 country">
			<img src="//static.tourvisor.ru/flags/calendar/flag_{{ country.id }}.gif" />
			<a href="{{ url('search') }}/{{ city.departure.name }}/{{ country.name }}">{{ country.name }}</a>
		</div>
{% endfor %}
	</div>
</div>

<div id="upButton">
	<i class="ion-chevron-up"></i>
</div>