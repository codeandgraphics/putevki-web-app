<ol class="breadcrumb">
	<li><a href="{{ backend_url('') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('blog') }}">Блог</a></li>
	<li class="active">{% if post is empty %}Новый пост{% else %}{{ post.title }}{% endif %}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{% if post is empty %}Новый пост{% else %}{{ post.title }}{% endif %}</h4>
		<p></p>
	</div>
	<div class="panel-body">
		<form method="post" enctype="multipart/form-data">
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
				<label for="createdBy">Блоггер</label>
                {{ form.render('createdBy', ["class":"form-control"]) }}
			</div>
			<div class="form-group">
				<label for="name">Превью-картинка</label>
				<div class="row">
					<div class="col-xs-3">
                        {% if post is not empty %}
							<img src="{{ images_url('blog/') }}{{ post.preview }}" class="img-responsive"/>
						{% endif %}
					</div>
					<div class="col-xs-9">
                        {{ form.render('preview', ["class":"form-control"]) }}
					</div>
				</div>
				<span class="help-block">Используется в боковом меню</span>
			</div>
			<div class="form-group">
				<label for="name">Краткое описание</label>
                {{ form.render('excerpt', ["class":"form-control", "style":"height:90px;"]) }}
			</div>
			<div class="form-group">
				<label for="name">Текст страницы</label>
				<div class="editable">
                    {{ post.content }}
				</div>
                {{ form.render('content', ["class":"form-control hidden"]) }}
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


<script type="text/javascript">
    $(document).ready(function(){

        $('.editable').summernote({
            minHeight: 200,
            callbacks: {
                onChange: function(contents) {
                    document.getElementById('content').value = contents;
                }
            }, 
            cleaner:{
                  action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                  newline: '<br>', // Summernote's default is to use '<p><br></p>'
                  notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                  keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                  keepClasses: false, // Remove Classes
                  badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                  badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                  limitChars: false, // 0/false|# 0/false disables option
                  limitDisplay: 'both', // text|html|both
                  limitStop: false // true/false
            }
        });
    });
</script>