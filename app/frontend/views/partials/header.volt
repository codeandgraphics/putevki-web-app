<header class="{{ page }}">
	<div class="header-overlay"></div>
	<nav>
		<div class="header-bar">
			<div class="color-bar"></div>
			<div class="container" style="position: relative;">
				<ul class="links list-unstyled list-inline">
					<li>
						<a href="{{ url('') }}">Поиск путёвок</a>
					</li>
					<li>
						<a href="{{ url('countries') }}">Страны</a>
					</li>
					<li>
						<a href="{{ url('blog') }}">Блог</a>
					</li>
					<li>
						<a href="{{ url('app') }}" target="_blank">Приложение</a>
					</li>
					<li>
						<a href="{{ url('about') }}">О нас</a>
					</li>
					<li>
						<a href="{{ url('contacts') }}">Контакты</a>
					</li>
				</ul>
				<a href="//tours.putevki.ru" class="old-site" target="_blank">
					Старый поиск туров<i class="ion-share"></i>
				</a>
			</div>
		</div>
		<div class="main-header">
			<div class="container">
				<a class="brand" href="/"></a>
				<div class="phone">
					<div class="number">
						<a href="tel:+{{ city.phone|phone }}">{{ city.phone }}</a>
					</div>
					служба поддержки клиентов
				</div>
				<div class="request">
					<button class="btn btn-default" data-toggle="modal" data-target="#findTourModal"><i class="ion-map"></i> Подберите мне путёвку</button>
				</div>
				<div class="location">
					<i class="ion-location"></i>

					<a href="#" data-toggle="modal" data-target="#cityModal">
						<span>{{ city.name }}</span>
						<b class="caret"></b>
					</a>
				</div>
			</div>
		</div>
	</nav>
	<div class="hero">
		{% if page === 'main' %}
			<h1 class="title">Куда бы вы хотели поехать?</h1>
			{{ partial('partials/search-form') }}
		{% elseif page === 'search' %}
			{{ partial('partials/search-form') }}
		{% elseif page === 'country' and country.tourvisor.active %}
			{{ partial('partials/search-form') }}
		{% endif %}
	</div>
</header>
