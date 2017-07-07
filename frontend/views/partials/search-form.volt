<form class="search-form" id="searchForm"
	  data-countries="{{ formCountries }}"
	  data-regions="{{ formRegions }}"
	  data-from="{{ params.search.from }}"
	  data-where='{{ params.search.where|json_encode }}'
	  data-when='{{ params.search.when|json_encode }}'
	  data-people='{{ params.search.people|json_encode }}'
	  data-filters='{{ params.search.filters|json_encode }}'
	  data-spy="affix"
	  data-offset-top="{% if page == 'main'%}398{% else %}150{% endif %}"
>
	<div class="form-container">
        {% set departure = params.search.fromEntity() %}
		<div class="loader">
			<div class="wrap">
				<div class="object"></div>
			</div>
		</div>
		<div class="inputs">
			<div class="where input">
				<input title="where" class="form-control" placeholder="Страна, регион или отель">
			</div>
			<div class="when input">
				<span class="range">± 2 дня</span>
				<div class="value"></div>
				<input title="when" />
			</div>
			<div class="length input popup-nights">
				<span class="range">± 2</span>
				<div class="value"></div>
				<div class="popup nights">
					<i class="popup-pointer"></i>
					<div class="selector">
						<div class="minus">–</div>
						<div class="plus">+</div>
					</div>
					<div class="range-checkbox">
						<input type="checkbox" id="nights-range-days" checked>
						<label for="nights-range-days">± 2 ночи</label>
					</div>
				</div>
			</div>
			<div class="people input popup-people">
				<div class="value"></div>
				<div class="popup people">
					<i class="popup-pointer"></i>
					<div class="adults selector">
						<div class="minus">-</div>
						<div class="plus">+</div>
						<div class="param"><span></span> <i class="ion-man"></i></div>
					</div>
					<div class="kids">
						<div class="kid template"><span></span> <i class="ion-ios-close-empty"></i></div>
					</div>
					<div class="add-kids">
						<div class="add">
							<select title="kids">
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
			<div class="from input dropdown">
				<span class="from-text range"{% if departure.id == 99 %} style="display:none;"{% endif %}>из</span>
				<div class="value">{{ departure.name_from }}</div>
				<select title="from-select">
					<optgroup label="Популярные">
						<option value="1" data-gen="Москвы">из Москвы</option>
						<option value="5" data-gen="Санкт-Петербурга">из Санкт-Петербурга</option>
						<option value="99" data-gen="Без перелета">Без перелета</option>
					</optgroup>
					<optgroup label="Все">
                        {% for item in departures %}
							<option value="{{ item.id }}" data-gen="{{ item.name_from }}">из {{ item.name_from }}</option>
                        {% endfor %}
					</optgroup>
				</select>
			</div>
			<div class="search-button">
				<button class="btn btn-primary">Искать туры</button>
			</div>
		</div>
	</div>
</form>