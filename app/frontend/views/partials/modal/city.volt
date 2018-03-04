<div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="cityModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="pull-right btn btn-default" data-dismiss="modal">Да</button>
				<h4 class="modal-title" id="cityModalLabel"><small>Ваш город:</small> {{ city.name }}</h4>
			</div>
			<div class="modal-body">
				<div class="wrap">
					<h5>Не ваш город? Выберите свой:</h5>
					<ul class="list-unstyled list-inline">
{% for item in cities %}
						<li><a href="/{{ item['uri'] }}" class="{% if item['main'] == 1%}main-city{% endif %} {% if item['id'] == city.id%}active{% endif %}">{{ item['name'] }}</a></li>
{% endfor %}
					</ul>
				</div>
				<div class="message">После выбора города, мы сможем подобрать для вас туры из ближайшего аэропорта, и рекомендовать вам ближайшие турагентства</div>
			</div>
		</div>
	</div>
</div>