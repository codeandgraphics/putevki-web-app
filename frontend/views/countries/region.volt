<div class="page country" id="countries">
	<div class="container flex">
		<section class="main left">
			<div class="head">
				<h1>
					{{ region.title }}
				</h1>
			</div>
			<div class="content">
				<ol class="breadcrumb">
					<li><a href="{{ url('countries') }}">Страны</a></li>
					<li><a href="{{ url('countries') }}/{{ country.uri }}">{{ country.tourvisor.name }}</a></li>
					<li class="active">{{ region.tourvisor.name }}</li>
				</ol>
				<div class="about">
					{{ region.about }}
				</div>
			</div>
		</section>
		<aside class="sidebar right">
			<div class="head">
				<div class="wrap">
					<h2>Горячие предложения</h2>
				</div>
			</div>
			<div class="content">
				<div class="wrap">
					<div class="block" id="hot"
						 data-region="{{ region.tourvisor.id }}"
						 data-country="{{ country.tourvisor.id }}"
						 data-departure="{{ city.flight_city }}"
						 data-url="{{ url('ajax/hotTours') }}"
					>
						<div class="loader">
							<div class="wrap">
								<div class="object">
								</div>
								Загружаем...
							</div>
						</div>
						{{ partial('partials/hot') }}
					</div>
				</div>
			</div>
		</aside>
	</div>
</div>