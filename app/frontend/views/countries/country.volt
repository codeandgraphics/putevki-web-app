<div class="page country" id="countries">
	<div class="container flex">
		<section class="main left">
			<div class="head">
				<h1>
                    {{ country.title }}
				</h1>
			</div>
			<div class="content">
				<ol class="breadcrumb">
					<li><a href="{{ url('countries') }}">Страны</a></li>
					<li class="active">{{ country.tourvisor.name }}</li>
				</ol>
				<div class="about">
                    {{ country.about }}
				</div>

				<div class="countryDepartures">

                    {% if country.tourvisor.id == 1 %}
						<h2>Когда откроют Египет?</h2>
						{% if country.tourvisor.active %}
							Ура! Египет открыт для туристов из России!
						{% else %}
							Пока что авиаперелеты в Египет не открыли :(<br/><br/>
							Но мы можем предложить вам путёвки из <a href="https://putevki.travel" target="_blank">Беларуси</a>!
						{% endif %}
                    {% else %}
						<h2>Популярные города вылета</h2>
						<div class="row">
                            {% for departure in popularDepartures %}
								<div class="col-xs-6">
									<a href="{{ url('search') }}/{{ departure.name }}/{{ country.tourvisor.name }}">
                                        {{ country.title }} из {{ departure.nameFrom }}
									</a>
								</div>
                            {% endfor %}
						</div>
                    {% endif %}
				</div>
			</div>
		</section>
		<aside class="sidebar right">
			<div class="head">
				<div class="wrap">
					<h2>Регионы</h2>
				</div>
			</div>
			<div class="content">
				<div class="wrap">
					<div class="regions">
                        {% for item in regions %}
							<div class="region">
								<a href="{{ url('countries/') }}{{ country.uri }}/{{ item.region.uri }}" title="{{ item.region.title }}">
									<div class="image">
										<div class="bg" style="background-image: url('{{ images_url('regions/') }}{{ item.region.preview }}');"></div>
									</div>
									<div class="title">
										<h3>{{ item.tourvisor.name }}</h3>
									</div>
								</a>
							</div>
                        {% endfor %}
					</div>
				</div>
			</div>
		</aside>
	</div>
</div>