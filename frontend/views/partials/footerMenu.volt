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
			<div class="col-xs-2 text-center putevki">
				<a href="https://putevki.ru"><img src="/assets/img/logo_small.png" alt="Путёвки.ру" style="width:150px !important;"></a><br/>
				Интернет-магазин туров<br/>
				2000-<?= date('Y'); ?> &copy; <a href="https://putevki.ru">putevki.ru</a>
			</div>
			<div class="col-xs-7">
				Сайт интернет-магазина "Путёвки.ру" по продаже туров онлайн следующих направлений: Австрия, Андорра, Болгария, Греция, Доминикана, Испания, Италия, Кипр, Куба, Мальдивы, Мексика, ОАЭ, Турция, Таиланд, Франция, Шри-Ланка, и другие страны.
				Информация о ценах, указанная на сайте, не является ни рекламой, ни офертой  определяемой положениями Статьи 437 (2) Гражданского кодекса РФ.
			</div>
			<div class="col-xs-3">
				<div class="partner">
					<a href="{{ url('uniteller') }}" target="_blank">
						<img src="{{ url() }}assets/img/uniteller.png" alt="Uniteller" style="width:150px !important;">
					</a>
					ООО «Турфирма ТУРСФЕРА»<br/>
					ИНН:7802425145 КПП:780201001
				</div>
			</div>
		</div>
	</div>
</footer>