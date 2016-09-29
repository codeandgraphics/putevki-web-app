<div class="benefits container">

	<h2 class="centered">Онлайн-магазин Путевки.ру</h2>

	<div class="row">
		<div class="col-xs-4">
			<div class="item visa">
				<i class="ion-card"></i>
				Удобная онлайн-оплата<br/>
				Visa и MasterCard
			</div>
		</div>
		<div class="col-xs-4">
			<div class="item world">
				<i class="ion-ios-world-outline"></i>
				Поиск туров<br/>
				по всему миру
			</div>
		</div>
		<div class="col-xs-4">
			<div class="item operators">
				<i class="ion-locked"></i>
				Только надежные<br/>
				туроператоры
			</div>
		</div>
	</div>

</div>

<div class="container countries">
	<h2 class="centered">Куда поехать отдыхать?</h2>
	<div class="row">
{% for country in countries %}
		<div class="col-xs-2 country">
			<img src="//static.tourvisor.ru/flags/calendar/flag_{{ country.id }}.gif" />
			<a href="{{ url('search') }}/{{ currentCity.name }}/{{ country.name }}">{{ country.name }}</a>
		</div>
{% endfor %}
	</div>
</div>

<div id="upButton">
	<i class="ion-chevron-up"></i>
</div>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-2 text-center">
				<a href="https://putevki.ru"><img src="/assets/img/logo_small.png" alt="Путёвки.ру" width="100"></a>
				Интернет-магазин туров
				2000-<?= date('Y'); ?> &copy; <a href="https://putevki.ru">putevki.ru</a>
			</div>
			<div class="col-xs-8">
				Путевки на отдых в Испании, Турции, Таиланде, Кубе, Доминикане, на Мальдивах, Шри-Ланке, Греции, Австрии, Андорре, ОАЭ и др. страах. Сайт интернет-магазина "Путёвки.ру" -  по продаже туров  в онлайне следующих направлений: Австрия, Андорра, Болгария, Греция, Доминикана, Испания, Италия, Кипр, Куба, Мальдивы, Мексика, ОАЭ, Турция, Таиланд, Франция, Шри-Ланка. Информация о ценах, указанная на сайте, не является ни рекламой, ни офертой  определяемой положениями Статьи 437 (2) Гражданского кодекса РФ.
			</div>
			<div class="col-xs-2">
				<div class="partner">
					<a href="http://uniteller.ru" target="_blank">
						<img width="150" src="{{ url() }}assets/img/uniteller.png">
					</a>
					Все платежи защищены
				</div>
			</div>
		</div>
		<div class="row legal">
			<div class="col-xs-12">
				ООО «Турфирма ТУРСФЕРА», юридический адрес: 194214, г. Санкт­Петербург, Выборгское шоссе, д.22,
				телефон: +7 (812) 643­34­51, факс: (812) 643­34­99,
				ИНН: 7802425145; КПП: 780201001; ОГРН: 1089847085536,
				р/сч 40702810716000807901 в Ст-Петербургском ф-ле ОАО «Промсвязьбанк» г.Санкт­Петербург,
				БИК 044030920 к/сч 30101810000000000920
			</div>
		</div>
	</div>
</footer>