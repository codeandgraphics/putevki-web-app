<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Меню</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{ backend_url('') }}">
				<img src="{{ static_url('static/yo.png') }}" alt="Путевки.ру"/> Панель управления Путёвки.ру
			</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{{ backend_url('users/logout') }}"><i class="glyphicon glyphicon-log-out"></i> Выйти</a></li>
			</ul>
		</div>
	</div>
</nav>