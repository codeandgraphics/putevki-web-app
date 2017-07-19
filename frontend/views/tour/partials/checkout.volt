<div class="checkout">
	{% if small %}
		<div class="prices inline tour-price">
			<small>Цена путёвки:</small>
			<span></span>
		</div>

		<div class="prices inline tour-fuel hidden">
			<small>Топливный сбор:</small>
			<span></span>
		</div>

		<div class="prices inline tour-visa hidden">
			<small>Визовый сбор:</small>
			<span></span>
		</div>
		<div class="tour-sum inline">
			<span>К оплате:</span>
			<strong></strong>
		</div>
	{% else %}
		<div class="params tour-name">
			<small>Путёвка:</small>
			<span>{{ tour.departurename }} &mdash; {{ tour.hotelregionname }}</span>
		</div>

		<div class="params tour-tourists">
			<small>Едут:</small>
			<span>
				{{ tour.adults }} <?=Utils\Text::humanize('adults', $tour->adults);?>
				{% if tour.child > 0 %}
					+ {{ tour.child }} <?=Utils\Text::humanize('kids', $tour->child);?>
				{% endif %}
			</span>
		</div>

		<div class="prices tour-price">
			<small>Цена путёвки:</small>
			<span></span>
		</div>

		<div class="prices tour-fuel hidden">
			<small>Топливный сбор:</small>
			<span></span>
		</div>

		<div class="prices tour-visa hidden">
			<small>Визовый сбор:</small>
			<span></span>
		</div>
		<div class="tour-sum">
			<span>Итого:</span>
			<strong></strong>
		</div>
	{% endif %}
</div>