<ol class="breadcrumb">
	<li><a href="{{ backend_url('/') }}"><i class="fa fa-home"></i>Главная</a></li>
	<li><a href="{{ backend_url('cities') }}">Города</a></li>
	<li><a href="{{ backend_url('cities/city') }}/{{ city.id }}">{{ city.name }}</a></li>
	<li class="active">{{ branch.name }}</li>
</ol>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Редактирование филиала {{ branch.name }} в {{ city.namePre }}</h4>
		<p>...</p>
	</div>
	<div class="panel-body">
		<form method="post">
			<div class="row">
				<div class="col-xs-6">

					<div class="form-group">
						<label for="name">Название филиала</label>
						{{ form.render('name', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="active">Активный</label>
						{{ form.render('active', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="address">Адрес</label>
						{{ form.render('address', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="addressDetails">Дополнительный адрес</label>
						{{ form.render('addressDetails', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="timetable">Время работы</label>
						{{ form.render('timetable', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="phone">Телефон</label>
						{{ form.render('phone', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="site">Сайт</label>
						{{ form.render('site', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="email">E-mail</label>
						{{ form.render('email', ["class":"form-control"]) }}
					</div>

				</div>
				<div class="col-xs-6">

					<div class="well well-sm">
						<div class="form-group">
							<label for="email">Логин</label>
							<input type="text" class="form-control" disabled="disabled" value="{{ branch.manager.email }}" />
						</div>

						<div class="form-group">
							<label for="managerPassword">Пароль менеджера</label>
							{{ form.render('managerPassword', ["class":"form-control"]) }}
						</div>
					</div>

					<div class="form-group">
						<label for="additionalEmails">Дополнительные e-mail</label>
						{{ form.render('additionalEmails', ["class":"form-control"]) }}
						<span class="help-block">Дополнительные адреса для отправки писем (через запятую)</span>
					</div>

					<div class="form-group">
						<label for="lat">Широта (lat, используется для отображения карты филиалов) </label>
						{{ form.render('lat', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="lon">Долгота (lon, используется для отображения карты филиалов)</label>
						{{ form.render('lon', ["class":"form-control"]) }}
					</div>

					<div class="form-group">
						<label for="main">Офис Путевки.ру</label>
						{{ form.render('main', ["class":"form-control"]) }}
						<span class="help-block">Заявки поступают в официальный офис, или же в офис партнера</span>
					</div>

				</div>
			</div>

			<h4 class="panel-title">Мета-данные</h4>
			<hr/>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="metaKeywords">Ключевые слова (meta keywords)</label>
						{{ form.render('metaKeywords', ["class":"form-control"]) }}
					</div>
					<div class="form-group">
						<label for="metaDescription">Описание (meta description)</label>
						{{ form.render('metaDescription', ["class":"form-control"]) }}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="metaText">Текст</label>
						{{ form.render('metaText', ["class":"form-control"]) }}
					</div>
				</div>
			</div>

			<hr/>

			<button type="submit" class="btn btn-success">Сохранить</button>

		</form>
	</div>
</div>