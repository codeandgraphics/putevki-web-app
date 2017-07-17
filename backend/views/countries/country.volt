<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('countries') }}">Все страны</a></li>
	<li class="active">{{ country.tourvisor.name }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ country.tourvisor.name }} – настройки</h4>
		<p></p>
	</div>
	<div class="panel-body">
		<form method="post">
			<div class="form-group">
				<label for="name">Заголовок страницы</label>
				{{ form.render('title', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">URI страны</label>
				{{ form.render('uri', ["class":"form-control"]) }}
				<span class="help-block">Латиницей, в нижнем регистре, используется в URL</span>
			</div>
			<div class="form-group">
				<label for="name">Краткое описание</label>
				{{ form.render('excerpt', ["class":"form-control", "style":"height:70px;"]) }}
			</div>
			<div class="form-group">
				<label for="name">Текст страницы</label>
				<div class="editable">
					{{ country.about }}
				</div>
				{{ form.render('about', ["class":"form-control hidden"]) }}
			</div>
			<div class="form-group">
				<label for="name">Ключевые слова</label>
				{{ form.render('metaKeywords', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">Мета-описание</label>
				{{ form.render('metaDescription', ["class":"form-control", "style":"height:70px;"]) }}
			</div>

			<button type="submit" class="btn btn-success">Сохранить</button>
		</form>
	</div>
</div>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ country.tourvisor.name }} — Популярные регионы</h4>
		<p>Выбор популярных регионов для отображения в приложении </p>
	</div>
	<div class="panel-body">
		<table class="table table-hover" id="populars"
			   data-popular-url="{{ backend_url('countries/_setPopular') }}"
			   data-active-url="{{ backend_url('countries/_setActive') }}"
			   data-has-info-url="{{ backend_url('countries/_setHasInfo') }}"
		>
			<thead>
			<tr>
				<th>Страна</th>
				<th>Заголовок страницы</th>
				<th>URI</th>
				<th width="120">Популярный</th>
				<th width="100">На сайте</th>
				<th width="90">Включен</th>
			</tr>
			</thead>
			<tbody>
			{% for item in regions %}
				<tr>
					<td>
						<a href="{{ backend_url('countries/region/') }}{{ item.tourvisor.id }}">
							{{ item.tourvisor.name }}
						</a>
					</td>
					<td>{{ item.region.title }}</td>
					<td>{{ item.region.uri }}</td>
					<td class="isPopular text-center">
						<input type="checkbox" data-id="{{ item.tourvisor.id }}" {% if item.region.popular %} checked{% endif %}/>
					</td>
					<td class="hasInfo text-center">
						<input type="checkbox" data-id="{{ item.tourvisor.id }}" {% if item.region.hasInfo %} checked{% endif %}/>
					</td>
					<td class="isActive text-center">
						<input type="checkbox" data-id="{{ item.tourvisor.id }}" {% if item.region.active %} checked{% endif %}/>
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
    var popularUrl = $populars.data('popular-url');
    var activeUrl = $populars.data('active-url');
    var hasInfoUrl = $populars.data('has-info-url');

    $populars.find('.isPopular input').on('change', function(){
      var $item = $(this);
      var id = $item.data('id');
      var checked = $item.is(':checked');

      $.post(popularUrl, {
        type: 'region',
        id: id,
        checked: (checked) ? 1 : 0
      }, function(response){
        console.log(response);
      });
    });

    $populars.find('.isActive input').on('change', function(){
      var $item = $(this);
      var id = $item.data('id');
      var checked = $item.is(':checked');

      $.post(activeUrl, {
        type: 'region',
        id: id,
        checked: (checked) ? 1 : 0
      }, function(response){
        console.log(response);
      });
    });

    $populars.find('.hasInfo input').on('change', function(){
      var $item = $(this);
      var id = $item.data('id');
      var checked = $item.is(':checked');

      $.post(hasInfoUrl, {
        id: id,
        hasInfo: (checked) ? 1 : 0
      }, function(response){
        console.log(response);
      });
    });


    var toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'image'],

      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

      [{ 'header': [2, 3, 4, false] }],

      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
      [{ 'align': [] }],

      ['clean']                                         // remove formatting button
    ];

    var quill = new Quill('.editable', {
      modules: {
        toolbar: toolbarOptions
      },
      theme: 'snow'
    });

    quill.on('text-change', function(){
      document.getElementById('about').value = quill.root.innerHTML;
    });
  });
</script>