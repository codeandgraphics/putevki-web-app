<ol class="breadcrumb">
	<li><a href="{{ backend_url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('requests') }}">Все заявки</a></li>
	<li class="active">Создание заявки</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Создание заявки</h4>
		<p>...</p>
	</div>
	<div class="panel-body">

	</div>
</div>


<form method="post">

	{{ partial('requests/partials/form') }}

	<button type="submit" class="btn btn-success btn-block">Создать</button>

</form>

{{ partial('requests/partials/touristAddModal') }}
