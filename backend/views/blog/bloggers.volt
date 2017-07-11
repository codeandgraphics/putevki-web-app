<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('blog') }}">Блог</a></li>
	<li class="active">Блогеры</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Блогеры</h4>
		<p>Все блогеры</p>
		<p>
			<a href="{{ backend_url('blog') }}" class="btn btn-primary">Посты</a>
		</p>
	</div>
	<div class="panel-body">
		<table class="table"
			   id="populars"
			   data-popular-url="{{ backend_url('countries/_setPopular') }}"
			   data-active-url="{{ backend_url('countries/_setActive') }}"
			   data-visa-url="{{ backend_url('countries/_setVisa') }}"
		>
			<thead>
			<tr>
				<th>Имя</th>
				<th>URI</th>
			</tr>
			</thead>
			<tbody>
			{% for item in pagination.items %}
				<tr>
					<td>
						<a href="{{ backend_url('blog/blogger/') }}{{ item.id }}">
							<img src="{{ images_url('blog/bloggers/') }}{{ item.image }}" width="20" class="img-circle"/> {{ item.name }}
						</a>
					</td>
					<td>{{ item.uri }}</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>

		{% if pagination.items %}
			<ul class="pagination">
				{% if pagination.before != pagination.current %}
					<li class="paginate_button">
						<a href="{{ backend_url('blog/bloggers') }}?page={{ pagination.before }}">назад</a>
					</li>
				{% else %}
					<li class="paginate_button disabled"><span>назад</span></li>
				{% endif %}

				{% for i in 1..pagination.total_pages %}
					<li class="paginate_button{% if pagination.current == i %} active{% endif %}">
						<a href="{{ backend_url('blog/bloggers') }}?page={{ i }}">{{ i }}</a>
					</li>
				{% endfor %}

				{% if pagination.next != pagination.current %}
					<li>
						<a href="{{ backend_url('blog/bloggers') }}?page={{ pagination.next }}">вперед</a>
					</li>
				{% else %}
					<li class="disabled"><span>вперед</span></li>
				{% endif %}
			</ul>
		{% endif %}
	</div>
</div>