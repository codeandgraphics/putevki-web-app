<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li class="active">Популярные страны</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Все страны и курорты</h4>
		<p>Популярные страны и курорты, визы, описания</p>
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
				<th>Страна</th>
				<th>Заголовок страницы</th>
				<th>URI</th>
				<th width="120">Виза</th>
				<th width="100">Популярная</th>
				<th width="100">Включена</th>
			</tr>
			</thead>
			<tbody>
			{% for item in countries %}
				<tr>
					<td>
						<a href="{{ backend_url('countries/country/') }}{{ item.tourvisor.id }}">
							{{ item.tourvisor.name }}
						</a>
					</td>
					<td>{{ item.country.title }}</td>
					<td>{{ item.country.uri }}</td>
					<td>
						<select data-id="{{ item.tourvisor.id }}">
							<option value="0"{% if item.country.visa === '0' %} selected{% endif %}>Нужна виза</option>
							<option value="1"{% if item.country.visa === '1' %} selected{% endif %}>Виза не нужна</option>
							<option value="2"{% if item.country.visa === '2' %} selected{% endif %}>Онлайн-виза</option>
						</select>
					</td>
					<td class="popular text-center">
						<input type="checkbox" data-id="{{ item.tourvisor.id }}" {% if item.country.popular %} checked{% endif %}/>
					</td>
					<td class="active text-center">
						<input type="checkbox" data-id="{{ item.tourvisor.id }}" {% if item.country.active %} checked{% endif %}/>
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