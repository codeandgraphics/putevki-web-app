<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Блог</li>
</ol>

<div class="panel">
	<div class="panel-heading">
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
			{% for item in posts %}
				<tr>
					<td>
						<a href="{{ backend_url('blog/post/') }}{{ item.post.id }}">
							{{ item.post.title }}
						</a>
					</td>
					<td><img src="{{ item.author.image }}" width="20" class="img-circle"/> {{ item.author.name }}</td>
					<td>{{ item.post.created }}</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
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