<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('tourists') }}">Туристы</a></li>
	<li class="active">Добавление туриста</li>
</ol>

<form method="post">

	{{ partial('tourists/partials/form') }}

	<button type="submit" class="btn btn-success btn-block">Добавить</button>
</form>