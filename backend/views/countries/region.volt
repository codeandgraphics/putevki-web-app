<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('countries') }}">Популярные страны</a></li>
	<li><a href="{{ backend_url('countries/country/') }}{{ region.tourvisor.countryId }}">{{ region.tourvisor.country.name }}</a></li>
	<li class="active">{{ region.tourvisor.name }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ region.tourvisor.name }} – настройки</h4>
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
				{{ form.render('excerpt', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">Текст страницы</label>
				<div class="editable" style="height: 300px;">
					{{ region.about }}
				</div>
				{{ form.render('about', ["class":"form-control hidden"]) }}
			</div>

			<button type="submit" class="btn btn-success">Сохранить</button>
		</form>
	</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    var toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'code-block'],

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