<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('blog') }}">Блог</a></li>
	<li><a href="{{ backend_url('blog/bloggers') }}">Блоггеры</a></li>
	<li class="active">{{ blogger.name }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ post.title }}</h4>
		<p></p>
	</div>
	<div class="panel-body">
		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="name">Имя</label>
				{{ form.render('name', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">URI</label>
				{{ form.render('uri', ["class":"form-control"]) }}
				<span class="help-block">Латиницей, в нижнем регистре, используется в URL</span>
			</div>
			<div class="form-group">
				<label for="name">Ссылка</label>
				{{ form.render('link', ["class":"form-control"]) }}
				<span class="help-block">Латиницей, в нижнем регистре, используется в URL</span>
			</div>
			<div class="form-group">
				<label for="name">Превью-картинка</label>
				<div class="row">
					<div class="col-xs-3">
						<img src="{{ images_url('blog/bloggers/') }}{{ blogger.image }}" class="img-responsive"/>
					</div>
					<div class="col-xs-9">
						{{ form.render('image', ["class":"form-control"]) }}
					</div>
				</div>
				<span class="help-block">Используется в боковом меню</span>
			</div>
			<div class="form-group">
				<label for="name">Описание</label>
				{{ form.render('description', ["class":"form-control", "style":"height:100px;"]) }}
			</div>

			<div class="form-group">
				<label for="name">Ключевые слова</label>
				{{ form.render('metaKeywords', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">Мета-описание</label>
				{{ form.render('metaDescription', ["class":"form-control", "style":"height:70px;"]) }}
			</div>

			<div class="form-group">
				<label for="name">Опубликовано</label>
				{{ form.render('active', ["class":"form-control"]) }}
			</div>

			<button type="submit" class="btn btn-success">Сохранить</button>
		</form>
	</div>
</div>
