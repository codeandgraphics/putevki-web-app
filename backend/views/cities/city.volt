<ol class="breadcrumb">
	<li><a href="{{ backend_url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('cities') }}">Города</a></li>
	<li class="active">{{ city.name }}</li>
</ol>

<div class="panel">
	<div id="branchesMap" style="width: 100%; height: 300px"></div>
</div>

<div class="panel">
	<div class="panel-heading">
		<a href="{{ backend_url('cities') }}/branchAdd/{{ city.id }}" class="btn btn-success pull-right">Добавить филиал</a>
		<h4 class="panel-title">Филиалы в {{ city.name_pre }}</h4>
		<p>Список всех филиалов в городе</p>
	</div>
	<div class="panel-body">
		<table class="table">
			<thead>
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th width="130">Телефон</th>
				<th>E-mail</th>
				<th>Координаты</th>
			</tr>
			</thead>
			<tbody>
			{% for branch in branches %}
			<tr>
				<td><a href="{{ backend_url('cities') }}/branch/{{ city.id }}/{{ branch.id }}">{{ branch.name }}</a></td>
				<td>{{ branch.address }}</td>
				<td>{{ branch.phone }}</td>
				<td>{{ branch.email }}</td>
				<td>{{ branch.lat }}, {{ branch.lon }}</td>
			</tr>
			{% endfor %}
			</tbody>
		</table>

	</div>
</div>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Редактирование города</h4>
		<p></p>
	</div>
	<div class="panel-body">
		<form method="post">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
						<label for="name">Название города</label>
						{{ form.render('name', ["class":"form-control"]) }}
						<span class="help-block">Название на русском, склонения загрузятся автоматически</span>
					</div>

					<div class="form-group">
						<label for="uri">URI</label>
						{{ form.render('uri', ["class":"form-control"]) }}
						<span class="help-block">Название на английском, используется в ссылках</span>
					</div>

					<div class="form-group">
						<label for="flight_city">Город вылета</label>
						{{ form.render('flight_city', ["class":"form-control"]) }}
						<span class="help-block">Города из базы Tourvisor</span>
					</div>

					<div class="form-group">
						<label for="phone">Телефон</label>
						{{ form.render('phone', ["class":"form-control"]) }}
						<span class="help-block">Основной телефон, используемый на сайте</span>
					</div>

				</div>
				<div class="col-xs-6">

					<div class="form-group">
						<label for="lat">Широта</label>
						{{ form.render('lat', ["class":"form-control"]) }}
						<span class="help-block">Lat, используется для отображения карты филиалов</span>
					</div>

					<div class="form-group">
						<label for="lon">Долгота</label>
						{{ form.render('lon', ["class":"form-control"]) }}
						<span class="help-block">Lon, используется для отображения карты филиалов</span>
					</div>

					<div class="form-group">
						<label for="zoom">Зум</label>
						{{ form.render('zoom', ["class":"form-control"]) }}
						<span class="help-block">Zoom, используется для отображения карты филиалов</span>
					</div>

					<div class="form-group">
						<label for="active">Показывать на сайте?</label>
						{{ form.render('active', ["class":"form-control"]) }}
						<span class="help-block">Если выбрано "Выкл", город не отображается</span>

					</div>

					<div class="form-group">
						<label for="main">Главный</label>
						{{ form.render('main', ["class":"form-control"]) }}
						<span class="help-block">Если выбрано, город показывается крупнее остальных</span>
					</div>

				</div>
			</div>

			<h4 class="panel-title">Мета-данные</h4>
			<hr/>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="meta_keywords">Ключевые слова (meta keywords)</label>
						{{ form.render('meta_keywords', ["class":"form-control"]) }}
					</div>
					<div class="form-group">
						<label for="meta_description">Описание (meta description)</label>
						{{ form.render('meta_description', ["class":"form-control"]) }}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="meta_text">Текст</label>
						{{ form.render('meta_text', ["class":"form-control"]) }}
					</div>
				</div>
			</div>

			<hr/>

			<button type="submit" class="btn btn-success">Сохранить</button>

		</form>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		ymaps.ready(init);
		var myMap;

		function init(){
			myMap = new ymaps.Map("branchesMap", {
				center: [{{ city.lat }}, {{ city.lon }}],
				zoom: {{ city.zoom }}
			});

			myMap.behaviors.disable('scrollZoom');
			myMap.controls.remove("mapTools").remove("searchControl");

			var myCollection = new ymaps.GeoObjectCollection();

			{% for branch in branches %}
				var placemark = new ymaps.Placemark([{{ branch.lat }}, {{ branch.lon }}], {
					hintContent: '{{ branch.name }}'
				});

				myCollection.add(placemark);

			{% endfor %}

			myMap.geoObjects.add(myCollection);

		}
	});
</script>