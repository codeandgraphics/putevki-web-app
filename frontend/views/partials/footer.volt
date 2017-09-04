<footer class="{{ page }}">
	<div class="container">
		<div class="row">
			<div class="col-xs-3 text-center putevki">
				<a href="{{ url() }}" class="putevki-logo"></a>
				Интернет-магазин путёвок<br/>
				2000-<?= date('Y'); ?> &copy; <a href="{{ url() }}">putevki.ru</a>
			</div>
			<div class="col-xs-6 text-center">
				Сайт интернет-магазина "Путёвки.ру" по продаже туров онлайн следующих направлений: Австрия, Андорра, Болгария, Греция, Доминикана, Испания, Италия, Кипр, Куба, Мальдивы, Мексика, ОАЭ, Турция, Таиланд, Франция, Шри-Ланка, и другие страны.
				Информация о ценах, указанная на сайте, не является ни рекламой, ни офертой  определяемой положениями Статьи 437 (2) Гражданского кодекса РФ.
			</div>
			<div class="col-xs-3">
				<div class="partner">
					<a class="uniteller-logo" href="{{ url('uniteller') }}" target="_blank"></a>
					Все платежи защищены
				</div>
			</div>
		</div>
	</div>

	{% if city.meta_text %}
		<div class="meta">
			{{ city.meta_text }}
		</div>
	{% endif %}
</footer>
