<div class="modal fade" id="callBackModal" tabindex="-1" role="dialog" aria-labelledby="callBackModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="loader">
				<div class="wrap">
					<div class="object"></div>
				</div>
			</div>
			<div class="wrap">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="callBackModalLabel">Поможем найти тур</h4>
					<div class="free">Бесплатно!</div>
				</div>
				<div class="modal-body">
					<form method="post" data-toggle="validator">
						<div class="form-group">
							<label for="callback-phone">Просто оставьте нам свой номер телефона, и мы вам перезвоним!</label>
							<input type="tel" name="findPhone" class="form-control" id="callback-phone" placeholder="+7 495 123-45-67" required>
						</div>

						<div class="submit">
							<button type="submit" class="btn btn-lg btn-primary">Подберите мне тур!</button>
						</div>

						{% if lastQueries|length > 0 %}
						<div class="queries">
							<h4>Вы искали:</h4>
							<ul class="list-unstyled">
								{% for query in lastQueries %}
								<li>{{ query }}<input type="hidden" name="query[]" value="{{query}}" /></li>
								{% endfor %}
							</ul>
						</div>
						{% endif %}

						<p>Наши менеджеры свяжутся с вами и помогут подобрать лучший тур.<br/>Абсолютно бесплатно!</p>
					</form>

					<div class="message">
						Заявки на звонок, поступившие с 20:00 по 10:00 (МСК) обрабатываются на следующий день.
					</div>

				</div>
			</div>
			<div class="message-success">
				<p>Спасибо! Наши менеджеры свяжутся с вами с ближайшее время!</p>
				<button type="button" class="btn btn-default" data-dismiss="modal">Ок</button>
			</div>
			<div class="message-error">
				<p>
					Извините, что-то пошло не так :(<br/>
					Попробуйте позже
				</p>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Ок</button>
			</div>
		</div>
	</div>
</div>