<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Города</li>
</ol>

<div class="panel">
	<div id="citiesMap" style="width: 100%; height: 300px"></div>
</div>

<div class="panel">
	<div class="panel-heading">
		<a href="{{ url('cities/add') }}" class="btn btn-success pull-right">Добавить город</a>
		<h4 class="panel-title">Все города</h4>
		<p>Все города, представленные на сайте</p>
	</div>
	<div class="panel-body">
		<table class="table">
			<thead>
			<tr>
				<th>Город</th>
				<th>Город вылета (Tourvisor)</th>
				<th>Телефон</th>
				<th>Координаты</th>
			</tr>
			</thead>
			<tbody>
			{% for city in cities %}
			<tr>
				<td><a href="{{ url('cities/city') }}/{{ city.id }}">{{ city.name }}</a></td>
				<td>{{ city.departure.name }}</td>
				<td>{{ city.phone }}</td>
				<td>{{ city.lat }}, {{ city.lon }}, {{city.zoom }}</td>
			</tr>
			{% endfor %}
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		ymaps.ready(init);
		var myMap;

		function init(){
			myMap = new ymaps.Map("citiesMap", {
				center: [55.76, 37.64],
				zoom: 4
			});

			myMap.behaviors.disable('scrollZoom');
			myMap.controls.remove("mapTools").remove("searchControl");

			var myCollection = new ymaps.GeoObjectCollection();

			{% for city in cities %}
				var placemark = new ymaps.Placemark([{{ city.lat }}, {{ city.lon }}], {
					hintContent: '{{ city.name }}'
				});

				myCollection.add(placemark);

			{% endfor %}

			myMap.geoObjects.add(myCollection);

			myMap.setBounds(myCollection.getBounds());

		}
	});
</script>