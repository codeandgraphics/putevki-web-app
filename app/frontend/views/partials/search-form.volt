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
				<input title="Куда едете?" class="form-control" placeholder="Страна, регион или отель">
			</div>
			<div class="when input">
				<span class="range">± 2 дня</span>
				<div class="value"></div>
				<input title="Когда?" />
			</div>
			<div class="length input popup-nights" title="На сколько?">
				<div class="value"></div>
				<div class="popup nights">
					<i class="popup-pointer"></i>
					<div class="days">
						<div class="day template"></div>
					</div>
					<div class="nights-range-text">
						6-7 ночей
					</div>
				</div>
			</div>
			<div class="people input popup-people" title="Сколько человек?">
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
							<select title="Дети">
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
				<div class="value">{{ departure.nameFrom }}</div>
				<select title="Откуда?">
					<optgroup label="Популярные">
						<option value="1" data-gen="Москвы">из Москвы</option>
						<option value="5" data-gen="Санкт-Петербурга">из Санкт-Петербурга</option>
						<option value="99" data-gen="Без перелета">Без перелета</option>
					</optgroup>
					<optgroup label="Все">
                        {% for item in departures %}
							<option value="{{ item.id }}" data-gen="{{ item.nameFrom }}">из {{ item.nameFrom }}</option>
                        {% endfor %}
					</optgroup>
				</select>
			</div>
			<div class="search-button">
				<button class="btn btn-primary">Искать туры</button>
			</div>
		</div>
	</div>
	<div class="params-container">
		<div class="dropdown stars">
			Класс отеля:
			<button class="dropdown-toggle" type="button" id="starsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<div class="text">
                    {% set star = params.search.starsEntity() %}
                    {% for i in 1..star.name %}<i class="ion-star"></i>{% endfor %}
                    {% if star.id == 6 %}<span class="label label-warning">эксклюзив</span>{% else %}и выше{% endif %}
				</div>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="starsMenu">
				<li{% if star.id == 6 %} class="active"{% endif %}>
					<a href="#" data-stars="6">
						<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
						<span class="label label-warning">эксклюзив</span>
					</a>
				</li>
				<li role="separator" class="divider"></li>
				<li{% if star.id == 5 %} class="active"{% endif %}>
					<a href="#" data-stars="5">
						<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
						и выше
					</a>
				</li>
				<li{% if star.id == 4 %} class="active"{% endif %}>
					<a href="#" data-stars="4">
						<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
						и выше
					</a>
				</li>
				<li{% if star.id == 3 %} class="active"{% endif %}>
					<a href="#" data-stars="3">
						<i class="ion-star"></i><i class="ion-star"></i><i class="ion-star"></i>
						и выше
					</a>
				</li>
				<li{% if star.id == 2 %} class="active"{% endif %}>
					<a href="#" data-stars="2">
						<i class="ion-star"></i><i class="ion-star"></i>
						и выше
					</a>
				</li>
			</ul>
		</div>

		<div class="dropdown meals">
			Питание:
			<button class="dropdown-toggle" type="button" id="mealMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<i class="ion-fork"></i> <i class="ion-knife"></i> &nbsp;
				<div class="text">
                    {% set meal = params.search.mealsEntity() %}
                    {{ meal.name }} {% if meal.name !== 'UAI' %}и выше{% endif %}
				</div>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="mealMenu">
                {% for item in meals %}
					<li{% if item.name == meal.name %} class="active"{% endif %}>
						<a href="#" data-meal="{{ item.id }}">
                            {{ item.name }} {% if item.name != 'UAI' %}и выше{% endif %}
							<small>{{ item.russian }}</small>
						</a>
					</li>
                    {% if item.name == 'AI' %}
						<li role="separator" class="divider"></li>
                    {% endif %}
                {% endfor %}
			</ul>
		</div>
	</div>
</form>