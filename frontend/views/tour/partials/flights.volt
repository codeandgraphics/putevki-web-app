<div class="actualize">
	<div class="loader">
		<div class="wrap">
			<div class="object"></div>
			<span>Идет актуализация перелетов... Пожалуйста, подождите</span>
		</div>
	</div>
</div>

<div class="flights hide" data-url="{{ url('ajax/tourDetail/') }}{{ tour.id }}">
	<h3>Перелет</h3>

	<div class="flight template" data-fuel="" data-price="" data-flight-id="">
		<div class="forward" data-placement="top" data-toggle="tooltip" title="">
			<div class="departure">
				<div class="icon">
					<i class="ion-plane"></i>
				</div>
				<div class="data">
					<div class="date"></div>
					<div class="time"></div>
					<div class="airport"></div>
				</div>
			</div>
			<div class="arrow">
				<i class="ion-chevron-right"></i>
			</div>
			<div class="arrival">
				<div class="icon" >
					<i class="ion-plane"></i>
				</div>
				<div class="data">
					<div class="date"></div>
					<div class="time"></div>
					<div class="airport"></div>
				</div>
			</div>
		</div>

		<div class="fuel">
			<div class="data">
				<div class="charge"></div>
				<span>Топливный сбор</span>
			</div>
		</div>


		<div class="backward" data-placement="top" data-toggle="tooltip" title="">
			<div class="departure">
				<div class="icon">
					<i class="ion-plane"></i>
				</div>
				<div class="data">
					<div class="date"></div>
					<div class="time"></div>
					<div class="airport"></div>
				</div>
			</div>
			<div class="arrow">
				<i class="ion-chevron-right"></i>
			</div>
			<div class="arrival">
				<div class="icon" >
					<i class="ion-plane"></i>
				</div>
				<div class="data">
					<div class="date"></div>
					<div class="time"></div>
					<div class="airport"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="items">
	</div>

	<div class="variants hide" data-toggle="collapse" data-target="#more-flights">
		<a href="#">есть другие варианты перелета</a>
	</div>
	<div class="collapse" id="more-flights"></div>

	<div class="no-flights hide">
		<p>Не удалось найти информацию о перелетах.</p>
		<p>Для уточнения информации <a href="#" data-toggle="modal" data-target="#callBackModal">закажите звонок</a>, или позвоните нам по телефону {{ city.phone }}</p>
	</div>

	<div class="includes">
		<h3>
			Что входит в стоимость?
		</h3>

		<div class="row">
			<div class="col-xs-6 tour-includes in-tour">
				<dl class="dl-horizontal">
					<dt><i class="ion-key"></i></dt>
					<dd>Проживание в отеле</dd>

					<dt class="nomeal"><i class="ion-fork"></i><i class="ion-knife"></i></dt>
					<dd class="nomeal">
						<div>Питание</div>
						<span>Наличие питания уточняйте у менеджера!</span>
					</dd>

					<dt class="nomedinsurance"><i class="ion-medkit"></i></dt>
					<dd class="nomedinsurance">
						<div>Медицинская страховка</div>
						<span>Наличие страховки уточняйте у менеджера!</span>
					</dd>

				</dl>
			</div>
			<div class="col-xs-6 tour-includes in-tour">
				<dl class="dl-horizontal">
					<dt class="noflight"><i class="ion-plane"></i></dt>
					<dd class="noflight">
						<div>Перелет</div>
						<span>Наличие перелета уточняйте у менеджера!</span>
					</dd>

					<dt class="notransfer"><i class="ion-model-s"></i></dt>
					<dd class="notransfer">
						<div>Трансфер</div>
						<span>Наличие трансфера уточняйте у менеджера!</span>
					</dd>
				</dl>
			</div>
		</div>
	</div>

	<!--<div class="add-payments">
		<h3>Доплаты к туру:</h3>
		<div class="payments">
			<ul>
				<li>{payment.name}: <b>{payment.amount} руб.</b></li>
			</ul>
		</div>
	</div>-->
</div>

<div class="message no-actualize hide">
	<p>Не удалось актуализовать перелеты. Оставьте заявку, и мы обязательно свяжемся с вами!</p>
	<p>Для уточнения информации <a href="#" data-toggle="modal" data-target="#callBackModal">закажите звонок</a>, или позвоните нам по телефону {{ city.phone }}</p>
</div>

