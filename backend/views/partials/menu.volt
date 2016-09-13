<div class="media sidebar-profile">
	<div class="media-left">
		<a href="#">
			{% if user.imageUrl %}
			<img src="{{ user.imageUrl }}" alt="{{ user.company }}" class="media-object img-circle"/>
			{% else %}
			<img src="//placehold.it/48x48" alt="{{ user.company }}" class="media-object img-circle">
			{% endif %}
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading">{{ user.name }}</h4>
		<span>{{ user.company }}</span>
	</div>
</div>
<h5 class="sidebar-title"></h5>
<ul class="nav nav-sidebar">
	<li{% if(current.controller == 'index') %} class="active"{% endif %}><a href="{{ url() }}"><i class="fa fa-home"></i> Главная</a></li>
</ul>

<h5 class="sidebar-title">Заявки</h5>
<ul class="nav nav-sidebar">
	<li{% if(current.controller == 'requests' AND current.action != 'add') %} class="active"{% endif %}><a href="{{ url('requests') }}"><i class="fa fa-file-text-o"></i> Все заявки</a></li>
	<li{% if(current.controller == 'requests' AND current.action == 'add') %} class="active"{% endif %}><a href="{{ url('requests/add') }}"><i class="fa fa-plus-square-o"></i> Создать заявку</a></li>
</ul>

{% if user.role is 'Admin' %}
<h5 class="sidebar-title">Платежи</h5>
<ul class="nav nav-sidebar">
	<li{% if(current.controller == 'payments' AND current.action != 'add') %} class="active"{% endif %}><a href="{{ url('payments') }}"><i class="fa fa-credit-card"></i> Все платежи</a></li>
</ul>
{% endif %}

<h5 class="sidebar-title">Туристы</h5>
<ul class="nav nav-sidebar">
	<li{% if(current.controller == 'tourists' AND current.action != 'add') %} class="active"{% endif %}><a href="{{ url('tourists') }}"><i class="fa fa-users"></i> Все туристы</a></li>
	<li{% if(current.controller == 'tourists' AND current.action == 'add') %} class="active"{% endif %}><a href="{{ url('tourists/add') }}"><i class="fa fa-user-plus"></i> Добавить</a></li>
</ul>

{% if user.role is 'Admin' %}

<h5 class="sidebar-title">Настройки</h5>
<ul class="nav nav-sidebar">
	<li{% if(current.controller == 'cities') %} class="active"{% endif %}><a href="{{ url('cities') }}"><i class="fa fa-building-o"></i> Города</a></li>
	<!--<li{% if(current.controller == 'populars') %} class="active"{% endif %}>><a href="{{ url('populars') }}"><i class="fa fa-star-o"></i> Курорты</a></li>-->
</ul>


<h5 class="sidebar-title">Яндекс</h5>
<ul class="nav nav-sidebar">
	<li{% if(current.controller == 'yandex' AND current.action == 'type' AND type == 'countries') %} class="active"{% endif %}><a href="{{ url('yandex/type/countries') }}">Страны</a></li>
	<li{% if(current.controller == 'yandex' AND current.action == 'type' AND type == 'departures') %} class="active"{% endif %}><a href="{{ url('yandex/type/departures') }}">Города вылета</a></li>
	<li{% if(current.controller == 'yandex' AND current.action == 'type' AND type == 'operators') %} class="active"{% endif %}><a href="{{ url('yandex/type/operators') }}">Операторы</a></li>
	<li{% if(current.controller == 'yandex' AND current.action == 'hotels') %} class="active"{% endif %}><a href="{{ url('yandex/hotels') }}">Отели</a></li>
</ul>
{% endif %}
