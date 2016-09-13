<?php
$name = str_ireplace([' ','&'], ['_','And'], ucwords(strtolower($tour->hotelname)));
$hotelLink = "/hotel/" . $name . "-" . $tour->hotelcode;

$date = DateTime::createFromFormat('d.m.Y',$tour->flydate);
?>
<div class="hero little">
	<div class="container">

	</div>
</div>

<div class="page" id="tour" data-tour='{{ tour|json_encode }}'>
	<div class="container tour">
		<div class="row no-gutter">
			<section class="main left">
				<div class="steps head">
					<!--<h1></h1>-->
					<ul class="list-inline">
						<li>1. Выбор направления</li>
						<li>&rarr;</li>
						<li>2. Выбор путевки</li>
						<li>&rarr;</li>
						<li class="current">3. Перелет и оформление</li>
					</ul>
				</div>
				<div class="content">
					<div class="tour-info">

						<!--<pre>
							<?=var_dump($tour);?>
						</pre>-->

						<!-- Отель -->
						{{ partial('tour/partials/hotel') }}
						<!-- Отель -->

						<!-- Перелет -->
						{{ partial('tour/partials/flights') }}
						<!-- Перелет -->

						<!-- Что включено -->
						{{ partial('tour/partials/includes') }}
						<!-- Что включено -->


						<!-- Купить/забронировать -->
						{{ partial('tour/partials/buy') }}
						<!-- Купить/забронировать -->


						</form>
					</div>
				</div>
			</section>
			<aside class="sidebar right">
				<div class="head">
					<div class="lowPrice">
						<div class="wrap">
							<div class="percent">100%</div>
							<div class="text">Гарантия низкой цены</div>
						</div>
					</div>
				</div>
				<div class="content">
					<div class="wrap" id="prices">
						{{ partial('tour/partials/checkout') }}
					</div>

					{{ partial('partials/tour-includes') }}

				</div>
			</aside>
		</div>
	</div>
</div>