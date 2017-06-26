<nav class="navbar main">
	<div class="container">
			<a class="brand" href="/">
				<img alt="Путевки.ру" src="{{ static_url() }}img/logo.png">
			</a>
			<div class="phone">
				<div class="number">
					<a href="tel:{{ city.phone }}">{{ city.phone }}</a>
				</div>
				служба поддержки клиентов
			</div>
			<div class="request">
				<button class="btn btn-primary" data-toggle="modal" data-target="#findTourModal"><i class="ion-map"></i> Подберите мне тур</button>
			</div>
			<div class="location ">
				<i class="ion-location"></i> <a href="#" data-toggle="modal" data-target="#cityModal"><span>{{ city.name }}</span><b class="caret"></b></a>
			</div>
		</div>
</nav>