<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('countries') }}">Все страны</a></li>
	<li><a href="{{ backend_url('countries/country/') }}{{ region.tourvisor.countryId }}">{{ region.tourvisor.country.name }}</a></li>
	<li class="active">{{ region.tourvisor.name }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ region.tourvisor.name }} – настройки</h4>
		<p></p>
	</div>
	<div class="panel-body">
		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="name">Заголовок страницы</label>
                {{ form.render('title', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">URI региона</label>
                {{ form.render('uri', ["class":"form-control"]) }}
				<span class="help-block">Латиницей, в нижнем регистре, используется в URL</span>
			</div>
			<div class="form-group">
				<label for="name">Превью-картинка</label>
				<div class="row">
					<div class="col-xs-3">
						<img src="{{ images_url('regions/') }}{{ region.preview }}" class="img-responsive"/>
					</div>
					<div class="col-xs-9">
                        {{ form.render('preview', ["class":"form-control"]) }}
					</div>
				</div>
				<span class="help-block">Используется в боковом меню</span>
			</div>
			<div class="form-group">
				<label for="name">Текст страницы</label>
				<div class="editable">
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

        $('.editable').summernote({
            minHeight: 200,
            callbacks: {
                onChange: function(contents) {
                    document.getElementById('about').value = contents;
                }
            }
        });
    });
</script>