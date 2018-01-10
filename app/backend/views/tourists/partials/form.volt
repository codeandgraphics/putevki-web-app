<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Данные загранпаспорта</h4>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="passport_name">Имя в загранпаспорте</label>
					{{ form.render('passport_name', ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="passport_surname">Фамилия в загранпаспорте</label>
					{{ form.render('passport_surname', ['class': 'form-control']) }}
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="passport_number">Серия и номер паспорта</label>
					{{ form.render('passport_number', ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="passport_endDate">Годен до</label>
					<div class="input-group">
						{{ form.render('passport_endDate', ['class': 'form-control']) }}
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="birthDate">Дата рождения</label>
					<div class="input-group">
						{{ form.render('birthDate', ['class': 'form-control']) }}
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label for="passport_issued">Кем выдан</label>
					{{ form.render('passport_issued', ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="nationality">Гражданство</label>
					{{ form.render('nationality', ['class': 'form-control']) }}
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Дополнительные данные</h4>
	</div>
	<div class="panel-body">

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="phone">Телефон</label>
					{{ form.render('phone', ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="email">E-mail</label>
					{{ form.render('email', ['class': 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="gender">Пол</label>
					{{ form.render('gender', ['class': 'form-control']) }}
				</div>
			</div>
		</div>
	</div>
</div>