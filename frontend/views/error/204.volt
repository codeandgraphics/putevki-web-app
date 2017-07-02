{{ partial('error/header') }}
<body>

<div class="error-message">
	<div class="error">
		<img src="{{ static_url() }}img/logo_small.png" alt="Путёвки.ру">
		<div class="code">204</div>
		<div class="description">Путёвка устарела</div>
	</div>
	<div class="message">
		<div class="text">
			<p>Возможно, путёвка уже устарела, и оператор снял её.</p>

			<a href="#"  onclick="history.go(-1); return false;">Вернуться назад</a>
			<a href="{{ url() }}">На главную</a>
		</div>
	</div>
</div>

</body>
</html>