<nav class="navbar navbar-default navbar-fixed-top main">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="/">
				<img alt="Путевки.ру" src="/assets/img/logo.png">
			</a>
		</div>
		<div class="collapse navbar-collapse">
			<div class="phone navbar-text">
				<div class="number">
					<a href="tel:{{ currentCity.phone }}">{{ currentCity.phone }}</a>
				</div>
				служба поддержки клиентов
			</div>
			<div class="request navbar-text">
				<button class="btn btn-primary" data-toggle="modal" data-target="#callBackModal"><i class="ion-map"></i> Подберите мне тур</button>
			</div>
			<div class="location navbar-text navbar-right">
				<i class="ion-location"></i> <span>{{ currentCity.name }}</span>
				<small><a href="https://putevki.ru/?from_spb" target="_blank">Старая версия сайта<i class="ion-android-open"></i></a></small>
			</div>
		</div>
	</div>
</nav>