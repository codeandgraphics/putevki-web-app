<form class="form-inline" action="" method="get" id="searchForm"
	  data-departure="{{ params.departureId }}"
	  data-country="{{ params.countryId }}"
	  data-region="{{ params.regionId }}"
	  data-date="<?=implode('.', array_reverse(explode('-',$params->date)));?>"
	  data-date-range="{{ params.date_range }}"
	  data-nights="{{ params.nights }}"
	  data-nights-range="{{ params.nights_range }}"
	  data-adults="{{ params.adults }}"
	  data-kids="{{ params.kids }}"
	  data-stars="{{ params.starsId }}"
	  data-meal="{{ params.mealId }}"
	  data-countries="{{ formCountries }}"
	  data-regions="{{ formRegions }}"
>
	<div class="loader">
		<div class="wrap">
			<div class="object"></div>
		</div>
	</div>
	<div class="where form-group">
		<input type="text" class="form-control" placeholder="Страна, регион или отель">
	</div>
	<div class="when form-group">
		<span class="range">± 2 дня</span>
		<div class="value"></div>
	</div>
	<div class="length form-group popup-nights">
		<span class="range">± 2</span>
		<div class="value"></div>
		<div class="popup nights hidden">
			<div class="selector">
				<div class="minus">-</div>
				<div class="plus">+</div>
				<div class="param"></div>
			</div>
			<div class="range-checkbox">
				<input type="checkbox" id="nights-range-days" value="1" name="nights-range-days" checked>
				<label for="nights-range-days">± 2 ночи</label>
			</div>
		</div>
	</div>
	<div class="people form-group popup-people">
		<div class="value"></div>
		<div class="popup people hidden">
			<div class="adults selector">
				<div class="minus">-</div>
				<div class="plus">+</div>
				<div class="param"></div>
			</div>
			<div class="kids">
				<div class="kid template"><span></span> <i class="ion-ios-close-empty"></i></div>
			</div>
			<div class="add-kids">
				<div class="add">
					<select>
						<option value="">Добавить ребенка</option>
						<option value="1">до 2х лет</option>
						<option value="2">2 года</option>
						<option value="3">3 года</option>
						<option value="4">4 года</option>
						<option value="5">5 лет</option>
						<option value="6">6 лет</option>
						<option value="7">7 лет</option>
						<option value="8">8 лет</option>
						<option value="9">9 лет</option>
						<option value="10">10 лет</option>
						<option value="11">11 лет</option>
						<option value="12">12 лет</option>
						<option value="13">13 лет</option>
						<option value="14">14 лет</option>
					</select>
					<div class="info">
						Чтобы взять с собой больше детей, разделите взрослых и детей на несколько групп или обратитесь в турагентство.
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="search form-group">
		<button class="btn btn-default">Искать туры</button>
	</div>
</form>