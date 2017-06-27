<div class="checkout">
	<dl class="dl-horizontal">
		<dt class="tour-name">Путёвка:</dt>
		<dd class="tour-name">{{ tour.departurename }} &mdash; {{ tour.hotelregionname }}</dd>

		<dt class="tour-tourists">Едут:</dt>
		<dd class="tour-tourists">
			{{ tour.adults }} <?=Utils\Text::humanize('adults', $tour->adults);?>
			{% if tour.child > 0 %}
			+ {{ tour.child }} <?=Utils\Text::humanize('kids', $tour->child);?>
			{% endif %}
		</dd>

		<dt class="tour-price">Цена путёвки:</dt>
		<dd class="tour-price"></dd>

		<dt class="tour-fuel hidden">Топливный сбор:</dt>
		<dd class="tour-fuel hidden"></dd>

		<dt class="tour-visa hidden">Визовый сбор:</dt>
		<dd class="tour-visa hidden"></dd>
	</dl>
	<div class="tour-sum">
		<strong></strong>
		<span>К оплате:</span>
	</div>
</div>