<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Все туристы</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<form class="search form-inline pull-right" method="get">
			<div class="form-group">
				<div class="input-group">
					<input type="text" name="search" id="touristsSearch" class="form-control" value="{{ search }}">
					<div class="input-group-btn">
						<button type="submit" class="btn btn-primary btn-sm">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</div>
			</div>
		</form>
		<h4 class="panel-title">Все туристы</h4>
		<p>В этом разделе отображаются все туристы, которые бронировали туры в системе</p>
	</div>
	<div class="panel-body">
		<div class="table_pagination">
			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th>Имя в паспорте</th>
						<th>Номер паспорта</th>
						<th>Годен до</th>
						<th>Дата рождения</th>
						<th>Гражданство</th>
						<th>Телефон</th>
						<th>E-mail</th>
					</tr>
				</thead>
				<tbody>
				{% for tourist in page.items %}
					<tr>
						<td>
							{% if tourist.gender == 'm' %}
							<i class="fa fa-male"></i>
							{% else %}
							<i class="fa fa-female"></i>
							{% endif %}
						</td>
						<td>
							<a href="{{ url('tourists') }}/edit/{{ tourist.id }}">{{ tourist.passport_surname }} {{ tourist.passport_name }}</a>
						</td>
						<td>
							{{ tourist.passport_number }}
						</td>
						<td>
							{{ tourist.passport_endDate }}
						</td>
						<td>
							{{ tourist.birthDate }}
						</td>
						<td>
							{{ tourist.nationality }}
						</td>
						<td>
							{{ tourist.phone }}
						</td>
						<td>
							{{ tourist.email }}
						</td>
					</tr>
				{% else %}
				<tr>
					<td colspan="7" class="not-found">Туристы не найдены. Попробуйте изменить параметры поиска</td>
				</tr>
				{% endfor %}
				</tbody>
			</table>

			{% if page.items %}
			<ul class="pagination">
				{% if page.before != page.current %}
				<li class="paginate_button">
					<a href="{{ url('tourists') }}?page={{ page.before }}{{ searchAdd }}">назад</a>
				</li>
				{% else %}
				<li class="paginate_button disabled">
					<span>
						назад
					</span>
				</li>
				{% endif %}

				{% for i in 1..page.total_pages %}
				<li class="paginate_button{% if page.current == i %} active{% endif %}">
					<a href="{{ url('tourists') }}?page={{ i }}{{ searchAdd }}">{{ i }}</a>
				</li>
				{% endfor %}

				{% if page.next != page.current %}
				<li>
					<a href="{{ url('tourists') }}?page={{ page.next }}{{ searchAdd }}">вперед</a>
				</li>
				{% else %}
				<li class="disabled">
					<span>
						вперед
					</span>
				</li>
				{% endif %}
			</ul>
			{% endif %}
		</div>
	</div>
</div>