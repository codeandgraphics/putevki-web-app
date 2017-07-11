<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Блог</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<a href="{{ backend_url('blog/bloggers') }}" class="btn btn-success btn-stroke btn-sm pull-right">Блоггеры</a>
		<h4 class="panel-title">Блог</h4>
		<p>Все посты</p>
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
				<th>Заголовок поста</th>
				<th>Автор</th>
				<th>Дата публикации</th>
			</tr>
			</thead>
			<tbody>
			{% for item in pagination.items %}
				<tr>
					<td>
						<a href="{{ backend_url('blog/post/') }}{{ item.post.id }}">
							{{ item.post.title }}
						</a>
					</td>
					<td>
						<a href="{{ backend_url('blog/blogger/') }}{{ item.author.id }}">
							<img src="{{ images_url('blog/bloggers/') }}{{ item.author.image }}" width="17" class="img-circle"/>{{ item.author.name }}
						</a>
					</td>
					<td>{{ item.post.created }}</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>

		{% if pagination.items %}
			<ul class="pagination">
				{% if pagination.before != pagination.current %}
					<li class="paginate_button">
						<a href="{{ backend_url('blog') }}?page={{ pagination.before }}">назад</a>
					</li>
				{% else %}
					<li class="paginate_button disabled"><span>назад</span></li>
				{% endif %}

				{% for i in 1..pagination.total_pages %}
					<li class="paginate_button{% if pagination.current == i %} active{% endif %}">
						<a href="{{ backend_url('blog') }}?page={{ i }}">{{ i }}</a>
					</li>
				{% endfor %}

				{% if pagination.next != pagination.current %}
					<li>
						<a href="{{ backend_url('blog') }}?page={{ pagination.next }}">вперед</a>
					</li>
				{% else %}
					<li class="disabled"><span>вперед</span></li>
				{% endif %}
			</ul>
		{% endif %}
	</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    var $populars = $('#populars');
    var visaUrl = $populars.data('visa-url');
    var popularUrl = $populars.data('popular-url');
    var activeUrl = $populars.data('active-url');

    $populars.find('.popular input').on('change', function(){
      var $item = $(this);
      var id = $item.data('id');
      var checked = $item.is(':checked');

      $.post(popularUrl, {
        type: 'country',
        id: id,
        checked: (checked) ? 1 : 0
      }, function(response){
        console.log(response);
      });
    });

    $populars.find('.active input').on('change', function(){
      var $item = $(this);
      var id = $item.data('id');
      var checked = $item.is(':checked');

      $.post(activeUrl, {
        type: 'country',
        id: id,
        checked: (checked) ? 1 : 0
      }, function(response){
        console.log(response);
      });
    });

    $populars.find('select').on('change', function(){
      var $item = $(this);
      var id = $item.data('id');
      var visa = $item.val();

      $.post(visaUrl, {
        type: 'country',
        id: id,
        visa: visa
      }, function(response){
        console.log(response);
      });
    });
  });
</script>