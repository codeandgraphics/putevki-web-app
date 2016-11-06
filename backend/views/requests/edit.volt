<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ url('requests') }}">Все заявки</a></li>
	<li class="active">Редактирование заявки</li>
</ol>

<!--<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Редактирование заявки</h4>
		<p>...</p>
	</div>
	<div class="panel-body">

	</div>
</div>-->

<div class="row">
	<div class="col-sm-12">
		<!-- Действия с заявкой -->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Заявка №{{ req.id }}</h4>
			</div>
			<div class="panel-body">
				<a href="{{ url('requests/agreement/') }}{{ req.id }}" target="_blank" class="btn btn-primary btn-xs btn-stroke file">
					<i class="fa fa-file-pdf-o"></i>
					<span>Договор с заказчиком</span>
				</a>
				&nbsp;
				<a href="{{ url('requests/booking/') }}{{ req.id }}" target="_blank" class="btn btn-primary btn-xs btn-stroke file">
					<i class="fa fa-file-pdf-o"></i>
					<span>Лист бронирования</span>
				</a>
				&nbsp;
				<button id="delete-request" data-href="{{ url('requests/delete/') }}{{ req.id }}" class="btn btn-danger btn-xs btn-stroke pull-right">
					<i class="fa fa-trash"></i> Удалить заявку
				</button>
			</div>
		</div>
		<!-- Действия с заявкой -->
	</div>
</div>


<form method="post">

	{{ partial('requests/partials/form') }}

	<button type="submit" class="btn btn-success btn-block">Сохранить</button>

</form>

{{ partial('requests/partials/touristAddModal') }}

<script type="text/javascript">
	$(document).ready(function(){
		$('#delete-request').off('click').on('click', function(){
			if(confirm('Вы действительно хотите удалить заявку?')) {
				window.location.href = $(this).data('href');
			}
		});
	});
</script>


