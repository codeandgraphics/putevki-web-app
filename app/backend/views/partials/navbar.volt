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
			<ul class="nav navbar-nav visible-xs">
				<li{% if(current.controller == 'index') %} class="active"{% endif %}><a href="{{ backend_url() }}"><i class="fa fa-home"></i> Главная</a></li>
				<li{% if(current.controller == 'requests' AND current.action != 'add') %} class="active"{% endif %}><a href="{{ backend_url('requests') }}"><i class="fa fa-file-text-o"></i> Все заявки</a></li>
				<li{% if(current.controller == 'payments' AND current.action != 'add') %} class="active"{% endif %}><a href="{{ backend_url('payments') }}"><i class="fa fa-credit-card"></i> Все платежи</a></li>
				<li{% if(current.controller == 'tourists' AND current.action != 'add') %} class="active"{% endif %}><a href="{{ backend_url('tourists') }}"><i class="fa fa-users"></i> Все туристы</a></li>
				<li{% if(current.controller == 'blog') %} class="active"{% endif %}><a href="{{ backend_url('blog') }}"><i class="fa fa-commenting"></i> Блог</a></li>
				<li{% if(current.controller == 'countries') %} class="active"{% endif %}><a href="{{ backend_url('countries') }}"><i class="fa fa-globe"></i> Страны и курорты</a></li>
				<li{% if(current.controller == 'cities') %} class="active"{% endif %}><a href="{{ backend_url('cities') }}"><i class="fa fa-building-o"></i> Города и офисы</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="#" id="subscribe" class="hidden">
						<i class="glyphicon glyphicon-send"></i> Подписаться на уведомления
					</a>
					<span id="unsubscribe" class="hidden">
						<i class="glyphicon glyphicon-send"></i> Вы подписаны на уведомления
					</span>
				</li>
				<li><a href="{{ backend_url('users/logout') }}"><i class="glyphicon glyphicon-log-out"></i> Выйти</a></li>
			</ul>
		</div>
	</div>
</nav>