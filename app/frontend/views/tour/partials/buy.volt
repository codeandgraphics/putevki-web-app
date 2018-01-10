<div class="pay-variants locked">
	<div class="overflow"></div>
	<h3 class="hide">
		Как вам удобнее оплатить?
	</h3>

	<div class="message no-online hide">
		<i class="ion-alert-circled"></i> Извините, данную путёвку нельзя оплатить онлайн, но наши менеджеры свяжутся с вами и закажут её для вас!
	</div>

	<ul class="nav nav-pills" id="buy">
		<li class="variant active online">
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
		<li class="variant request">
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
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="buy-online">

			<form method="POST" id="online-form" data-toggle="validator" action="{{ url('ajax/formOnline') }}">
				<input type="hidden" name="flight" value="0" />

				{{ partial('tour/partials/person', ['type': 'online']) }}

				{{ partial('tour/partials/tourists', ['type': 'online']) }}

				<h3>Итого</h3>

				<div class="row checkout-block">
					<div class="col-xs-5">
						{{ partial('tour/partials/checkout', ['small' : true]) }}
					</div>
					<div class="col-xs-7">
						<div class="button-wrap">
							<button class="btn btn-primary btn-lg btn-block buy-online">
								Купить путёвку
							</button>

							<p class="small">Нажимая "Купить путёвку" Вы подтверждаете согласие на обработку персональных данных.</p>
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
		<div class="tab-pane" id="buy-request">
			<form method="POST" id="request-form" data-toggle="validator" action="{{ url('ajax/formRequest') }}">

				{{ partial('tour/partials/person', ['type': 'request']) }}

				<button class="btn btn-primary btn-lg btn-block buy-request">
					Отправить заявку
				</button>

				<div class="message">
					<p>Заявка на путёвку не является бронированием и не накладывает на Вас каких-либо обязательств.</p>
					<p>Отправляя запрос, Вы подтверждаете согласие на обработку персональных данных.</p>
				</div>

			</form>
		</div>
	</div>
</div>