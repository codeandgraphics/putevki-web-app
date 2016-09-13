{% set flightsCount = tour.flights|length %}

{% if flightsCount > 0 %}
<h2>
	Как вам удобнее оплатить?
</h2>
{% else %}
<div class="message no-online">
	Извините, данный тур нельзя оплатить онлайн, но наши менеджеры свяжутся с вами и закажут его для вас!
</div>
{% endif %}

<div class="pay-variants">
	<ul class="nav nav-pills" id="buy">
		{% if flightsCount > 0 %}
		<li class="variant active">
			<a href="#buy-online">
				<div class="icon">
					<i class="ion-card"></i>
				</div>
				<div class="data">
					<span>Оплатить онлайн</span>
					<small>VISA, MasterCard</small>
				</div>
			</a>
		</li>
		{% else %}
		<li class="variant active">
			<a href="#buy-request">
				<div class="icon">
					<i class="ion-ios-telephone"></i>
				</div>
				<div class="data">
					<span>Оставить заявку</span>
					<small>Мы перезвоним вам</small>
				</div>
			</a>
		</li>
		{% endif %}
		<li class="variant">
			<a href="#buy-office">
				<div class="icon">
					<i class="ion-location"></i>
				</div>
				<div class="data">
					<span>Купить в офисе</span>
					<small>За наличные</small>
				</div>
			</a>
		</li>
	</ul>

	<div class="tab-content">
		{% if flightsCount > 0 %}
		<div class="tab-pane active" id="buy-online">

			<form method="POST" id="online-form" data-toggle="validator" action="/ajax/formOnline">
				<input type="hidden" name="flight" value="0" />

				{{ partial('tour/partials/person', ['type': 'online']) }}

				{{ partial('tour/partials/tourists', ['type': 'online']) }}

				<h2>Итого</h2>

				<div class="row checkout-block">
					<div class="col-xs-5">
						{{ partial('tour/partials/checkout') }}
					</div>
					<div class="col-xs-7">

						<div class="button-wrap">
							<button type="submit" class="btn btn-primary btn-lg btn-block buy-online">
								Купить тур
							</button>
						</div>

						<ul class="list-unstyled payment-info">
							<li>
								Это удобно
								<span><i class="ion-card"></i> Принимаем Visa, MastedCard, электронные деньги.</span>
							</li>
							<li>
								Это безопасно
								<span><i class="ion-locked"></i> Все данные передаются по зашифрованному каналу.</span>
							</li>
						</ul>
					</div>
				</div>
			</form>
		</div>
		{% else %}
		<div class="tab-pane active" id="buy-request">
			<form method="POST" id="request-form" data-toggle="validator" action="/ajax/formRequest">

				{{ partial('tour/partials/person', ['type': 'request']) }}

				{{ partial('tour/partials/tourists', ['type': 'request']) }}

				<button type="submit" class="btn btn-primary btn-lg btn-block buy-request">
					Отправить заявку
				</button>

				<div class="message">
					<p>Заявка на тур не является бронированием тура и не накладывает на Вас каких-либо обязательств.</p>
					<p>Отправляя запрос, Вы подтверждаете согласие на обработку персональных данных.</p>
				</div>

			</form>
		</div>
		{% endif %}
		<div class="tab-pane" id="buy-office">
			<form method="POST" id="office-form" data-toggle="validator" action="/ajax/formOffice">

				{{ partial('tour/partials/person', ['type': 'office']) }}

				{{ partial('tour/partials/buy-office') }}

			</form>
		</div>
	</div>
</div>