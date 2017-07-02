<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Популярные страны</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Популярные страны</h4>
		<p>Выбор популярных стран для отображения в приложении </p>
	</div>
	<div class="panel-body">
		<table class="table" id="populars" data-url="{{ backend_url('populars/_setPopular') }}">
			<thead>
			<tr>
				<th>Страна</th>
				<th>Популярная</th>
			</tr>
			</thead>
			<tbody>
			{% for country in countries %}
				<tr>
					<td>
						<a href="{{ backend_url('populars/country/') }}{{ country.id }}">
							{{ country.name }}
						</a>
					</td>
					<td>
						<input type="checkbox" data-id="{{ country.id }}" {% if country.popular %} checked{% endif %}/>
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
				type: 'country',
				id: id,
				checked: (checked) ? 1 : 0
			}, function(response){
				console.log(response);
			});
		});
	});
</script>