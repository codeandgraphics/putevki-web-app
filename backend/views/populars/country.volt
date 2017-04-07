<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ url('/populars') }}">Популярные страны</a></li>
	<li class="active">{{ country.name }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ country.name }} — Популярные регионы</h4>
		<p>Выбор популярных регионов для отображения в приложении </p>
	</div>
	<div class="panel-body">
		<table class="table" id="populars" data-url="{{ url('populars/_setPopular') }}">
			<thead>
			<tr>
				<th>Страна</th>
				<th>Популярный</th>
			</tr>
			</thead>
			<tbody>
			{% for region in country.regions %}
				<tr>
					<td>{{ region.name }}</td>
					<td>
						<input type="checkbox" data-id="{{ region.id }}" {% if region.popular %} checked{% endif %}/>
					</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		var $populars = $('#populars');
		var url = $populars.data('url');

		$populars.find('input').on('change', function(){
			var $item = $(this);
			var id = $item.data('id');
			var checked = $item.is(':checked');

			$.post(url, {
				type: 'region',
				id: id,
				checked: (checked) ? 1 : 0
			}, function(response){
				console.log(response);
			});
		});
	});
</script>