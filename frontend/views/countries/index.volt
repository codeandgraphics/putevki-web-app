<div class="page" id="countries">
	<div class="container flex">
		<section class="main">
			<div class="head">
				<h1>
					Куда поехать отдыхать?
					<small>Все страны на Путёвки.ру</small>
				</h1>
			</div>
			<div class="content">
				<div class="countries">
					{% for item in countries %}
						<div class="row country">
							<div class="col-xs-3">
								<img src="//static.tourvisor.ru/minprice/minprice-{{ item.tourvisor.id }}-1.jpg" class="img-responsive"/>
							</div>
							<div class="col-xs-9 about">
								<h3>
									<a href="{{ url('countries/') }}{{ item.country.uri }}">
										{{ item.tourvisor.name }}
									</a>
								</h3>
								<p>
									{{ item.country.excerpt }}
								</p>
								<a href="{{ url('countries/') }}{{ item.country.uri }}">Подробнее...</a>
							</div>
						</div>
					{% endfor %}
				</div>
			</div>
		</section>
	</div>
</div>