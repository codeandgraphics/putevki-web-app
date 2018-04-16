<div class="hotel">
	<div class="image">
		<a href="{{ hotelLink }}" class="bg" style="background-image: url({{ tour.hotelpicturemedium }});" target="_blank"></a>
	</div>
	<div class="info">
		<div class="touroperator">
			<img src="//tourvisor.ru/pics/operators/searchlogo/{{ tour.operatorcode }}.gif"/>
            {{ tour.operatorname }}
		</div>
		<div class="rating">
			<span class="stars">
			{% for i in 0..4 %}
				<i class="star ion-ios-star{% if i >= tour.hotelstars %}-outline{% endif %}"></i>
            {% endfor %}
			</span>
		</div>
		<h2>
			<a href="{{ hotelLink }}" target="_blank">{{ tour.hotelname|lower }}</a>
		</h2>
		<div class="place">
			<i class="ion-ios-location"></i> <span>{{ tour.hotelregionname }}, {{ tour.countryname }}</span>
		</div>
		<div class="description">
            {{ tour.hoteldescription }}
		</div>
		<div class="icons">
			<div class="date">
				<div class="icon">
					<i class="ion-calendar"></i>
				</div>
				<div class="data">
					<span><?=$date->format('d');?> <?=Utils\Text::humanize('months',(int) $date->format('m'));?></span>
					<small><?=Utils\Text::humanize('nights',$tour->nights);?></small>
				</div>
			</div>
			<div class="room">
				<div class="icon">
					<i class="ion-key"></i>
				</div>
				<div class="data">
					<small>Номер:</small>
					<span>{{ tour.room|capitalize }}</span>
				</div>
			</div>
			<div class="meal">
				<div class="icon">
					<i class="ion-fork"></i>
					<i class="ion-knife"></i>
				</div>
				<div class="data">
					<small>Питание:</small>
					<span><?=Utils\Text::humanize('meal',$tour->meal);?></span>
				</div>
			</div>
		</div>
	</div>
</div>