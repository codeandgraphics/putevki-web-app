<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ url('tourists') }}">Туристы</a></li>
	<li class="active">Редактирование туриста</li>
</ol>

<form method="post">

	{{ partial('tourists/partials/form') }}

	<button type="submit" class="btn btn-success btn-block">Сохранить</button>
</form>