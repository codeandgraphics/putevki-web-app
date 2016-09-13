<div class="includes">
	<h2>
		Что входит в стоимость?
	</h2>

	<div class="row">
		<div class="col-xs-6 tour-includes in-tour">
			<dl class="dl-horizontal">
				<dt><i class="ion-key"></i></dt>
				<dd>Проживание в отеле</dd>

				{% if tour.flags.nomeal is null %}
					<dt><i class="ion-fork"></i><i class="ion-knife"></i></dt>
					<dd>Питание</dd>
				{% else %}
					<dt class="none"><i class="ion-fork"></i><i class="ion-knife"></i></dt>
					<dd class="none">Наличие питания уточняйте у менеджера!</dd>
				{% endif %}

				{% if tour.flags.nomedinsurance is null %}
					<dt><i class="ion-medkit"></i></dt>
					<dd>Медицинская страховка</dd>
				{% else %}
					<dt class="none"><i class="ion-medkit"></i></dt>
					<dd class="none">Наличие страховки уточняйте у менеджера!</dd>
				{% endif %}
			</dl>
		</div>
		<div class="col-xs-6 tour-includes in-tour">
			<dl class="dl-horizontal">
				{% if tour.flags.noflight is null %}
					<dt><i class="ion-plane"></i></dt>
					<dd>Перелет</dd>
				{% else %}
					<dt class="none"><i class="ion-plane"></i></dt>
					<dd class="none">Наличие перелета уточняйте у менеджера!</dd>
				{% endif %}

				{% if tour.flags.notransfer is null %}
					<dt><i class="ion-model-s"></i></dt>
					<dd>Трансфер</dd>
				{% else %}
					<dt class="none"><i class="ion-model-s"></i></dt>
					<dd class="none">Наличие трансфера уточняйте у менеджера!</dd>
				{% endif %}
			</dl>
		</div>
	</div>
</div>

{% if tour.addpayments is not null %}
<div class="add-payments">
	<h2>Доплаты к туру:</h2>
	<div class="payments">
		<ul>
			{% for payment in tour.addpayments %}
			<li>{{ payment.name }}: <b>{{ payment.amount }} руб.</b></li>
			{% endfor %}
		</ul>
	</div>
</div>
{% endif %}