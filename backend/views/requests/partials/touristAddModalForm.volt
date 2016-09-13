<form method="post">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-passport-surname">Фамилия в загранпаспорте</label>
				{{ text_field('tourist-passport-surname','class':'form-control') }}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-passport-name">Имя в загранпаспорте</label>
				{{ text_field('tourist-passport-name','class':'form-control') }}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-passport-number">Серия и номер паспорта</label>
				{{ text_field('tourist-passport-number','class':'form-control') }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-passport-issued">Кем выдан</label>
				{{ text_field('tourist-passport-issued','class':'form-control') }}
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="tourist-nationality">Гражданство</label>
				{{ text_field('tourist-nationality','class':'form-control') }}
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label for="tourist-passport-endDate">Годен до</label>
				<div class="input-group">
					{{ text_field('tourist-passport-endDate','class':'form-control dp') }}
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label for="tourist-birthDate">Дата рождения</label>
				<div class="input-group">
					{{ text_field('tourist-birthDate','class':'form-control dp') }}
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
				</div>
			</div>
		</div>
	</div>

	<hr/>

	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-phone">Телефон</label>
				{{ text_field('tourist-phone','class':'form-control') }}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-email">E-mail</label>
				{{ text_field('tourist-email','class':'form-control') }}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="tourist-gender">Пол</label>
				{{ text_field('tourist-gender','class':'form-control') }}
			</div>
		</div>
	</div>
	<input type="hidden" name="tourist-id" id="tourist-id" value="">
</form>