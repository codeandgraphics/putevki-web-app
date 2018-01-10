<?php
$name = str_ireplace([' ','&'], ['_','And'], ucwords(strtolower($tour->hotelname)));
$hotelLink = "/hotel/" . $name . "-" . $tour->hotelcode;

$date = DateTime::createFromFormat('d.m.Y',$tour->flydate);
?>
<div class="hero little">
	<div class="hero-overlay"></div>
	<div class="container">

	</div>
</div>

<div class="page" id="tour" data-tour='{{ tour|json_encode }}'>
	<div class="container flex tour">
		<section class="main left">
			<div class="steps head">
				<!--<h1></h1>-->
				<ul class="list-inline">
					<li>Выбор направления</li>
					<li>&rarr;</li>
					<li>Выбор путёвки</li>
					<li>&rarr;</li>
					<li class="current">Перелет и оформление</li>
				</ul>
			</div>
			<div class="content">
				<div class="tour-info">
					<!-- Отель -->
					{{ partial('tour/partials/hotel') }}
					<!-- Отель -->

					<!-- Перелет -->
					{{ partial('tour/partials/flights') }}
					<!-- Перелет -->

					<!-- Купить/забронировать -->
					{{ partial('tour/partials/buy') }}
					<!-- Купить/забронировать -->
				</div>
			</div>
		</section>
		<aside class="sidebar right">
			<div class="head">
				<div class="low-price">
					<div class="wrap">
						<div class="percent">100%</div>
						<div class="text">Гарантия лучшей цены</div>
					</div>
				</div>
			</div>
			<div class="content">
				<div class="wrap" id="prices">
					{{ partial('tour/partials/checkout', ['small': false]) }}
				</div>

				{{ partial('partials/tour-includes') }}

			</div>
		</aside>
	</div>
</div>