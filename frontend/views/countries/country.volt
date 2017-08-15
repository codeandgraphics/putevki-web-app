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

				<div class="block" id="hotCountry"
					 data-country="{{ country.tourvisor.id }}"
					 data-departure="{{ city.flight_city }}"
					 data-url="{{ url('ajax/hotTours') }}"
				>
					<h2>Горящие предложения</h2>
					<div class="loader">
						<div class="wrap">
							<div class="object">
							</div>
							Загружаем...
						</div>
					</div>
					{{ partial('partials/hot') }}
				</div>

				<div class="countryDepartures">
					<h2>Популярные города вылета</h2>
					<div class="row">
						{% for departure in popularDepartures %}
							<div class="col-xs-6">
								<a href="{{ url('search') }}/{{ country.tourvisor.name }}/{{ departure.name }}">
									{{ country.title }} из {{ departure.name_from }}
								</a>
							</div>
						{% endfor %}
					</div>
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