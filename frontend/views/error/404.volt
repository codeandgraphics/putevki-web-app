{{ partial('error/header') }}
<body>

<div class="error-message">
	<div class="error">
		<img src="/assets/img/logo_small.png" alt="Путёвки.ру">
		<div class="code">404</div>
		<div class="description">Страница не найдена</div>
	</div>
	<div class="message">
		<div class="text">
			<p>Возможно, страница была удалена или перемещена.</p>

			<a href="#"  onclick="history.go(-1); return false;">Вернуться назад</a>
			<a href="{{ url() }}">На главную</a>
		</div>
	</div>
</div>

</body>
</html>