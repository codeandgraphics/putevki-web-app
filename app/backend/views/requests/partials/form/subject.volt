<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<label for="subjectSurname">Фамилия</label>
			{{ form.render('subjectSurname', ['class': 'form-control']) }}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="subjectName">Имя</label>
			{{ form.render('subjectName', ['class': 'form-control']) }}
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="subjectPatronymic">Отчество</label>
			{{ form.render('subjectPatronymic', ['class': 'form-control']) }}
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<label for="subjectAddress">Адрес</label>
			{{ form.render('subjectAddress', ['class': 'form-control']) }}
		</div>
		<div class="form-group">
			<label for="subjectPhone">Телефон</label>
			{{ form.render('subjectPhone', ['class': 'form-control']) }}
		</div>
		<div class="form-group">
			<label for="subjectEmail">E-mail</label>
			{{ form.render('subjectEmail', ['class': 'form-control']) }}
		</div>
	</div>
</div>

