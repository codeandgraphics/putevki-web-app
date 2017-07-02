<div class="person">
	<h3>Покупатель</h3>
	<div class="wrap">
		{% if type == 'online' %}
		<div class="row">
			<div class="col-xs-4 form-group">
				<label for="person-surname-{{ type }}" class="control-label">Фамилия<sup>*</sup></label>
				<input type="text" class="form-control" id="person-surname-{{ type }}" name="person[surname]" placeholder="Иванов" required>
			</div>
			<div class="col-xs-4 form-group">
				<label for="person-name-{{ type }}" class="control-label">Имя<sup>*</sup></label>
				<input type="text" class="form-control" id="person-name-{{ type }}" name="person[name]" placeholder="Иван" required>
			</div>
			<div class="col-xs-4 form-group">
				<label for="person-patronymic-{{ type }}" class="control-label">Отчество</label>
				<input type="text" class="form-control" id="person-patronymic-{{ type }}" name="person[patronymic]" placeholder="Иванович">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6 form-group">
				<label for="person-phone-{{ type }}" class="control-label">Телефон<sup>*</sup></label>
				<input type="tel"
					   class="form-control"
					   id="person-phone-{{ type }}"
					   name="person[phone]"
					   data-inputmask="'mask':'+7 (999) 999-99-99'"
					   placeholder="+7 (495) 123-45-67"
					   required
				/>
			</div>
			<div class="col-xs-6 form-group">
				<label for="person-email-{{ type }}" class="control-label">Электронная почта<sup>*</sup></label>
				<input type="email" class="form-control" id="person-email-{{ type }}" name="person[email]" placeholder="ivan@ivanov.ru" required>
			</div>
		</div>
		{% else %}
		<div class="row">
			<div class="col-xs-4 form-group">
				<label for="person-name-{{ type }}" class="control-label">Имя<sup>*</sup></label>
				<input type="text" class="form-control" id="person-name-{{ type }}" name="person[name]" placeholder="Иван" required>
			</div>
			<div class="col-xs-4 form-group">
				<label for="person-phone-{{ type }}" class="control-label">Телефон<sup>*</sup></label>
				<input type="tel" class="form-control" id="person-phone-{{ type }}" name="person[phone]" placeholder="+7(495)123-45-67" required>
			</div>
			<div class="col-xs-4 form-group">
				<label for="person-email-{{ type }}" class="control-label">Электронная почта<sup>*</sup></label>
				<input type="email" class="form-control" id="person-email-{{ type }}" name="person[email]" placeholder="ivan@ivanov.ru" required>
			</div>
		</div>
		{% endif %}
		<div class="row">
			<div class="col-xs-12">
				<label class="control-label" for="tour-comments-{{ type }}">Хотите оставить комментарий к туру?</label>
				<textarea class="form-control" name="comments" id="tour-comments-{{ type }}" placeholder="Укажите пожелания к туру в этом поле"></textarea>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			{% if type == 'online' %}
			<div class="confirm form-group">
				<input type="checkbox" name="confirm" id="confirmation-{{ type }}" required>
				<label for="confirmation-{{ type }}" class="control-label">Я согласен с условиями <a href="{{ url('agreement.pdf') }}" target="_blank">договора-оферты на туристическое обслуживание</a>.</label>
			</div>
			{% endif %}
		</div>
		<div class="col-xs-6">
			<div class="message">
				<p>Эти данные мы используем для оформления договора, информирования о статусе поездки и отправки электронных документов.</p>
			</div>
		</div>
	</div>
</div>
