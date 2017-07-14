{% set version = config.frontend.version %}
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=1000">
	<meta name="apple-itunes-app" content="app-id=1195053087">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="icon" type="image/png" href="{{ static_url() }}static/yo.png">

	<meta name="mobile-web-app-capable" content="yes">

	{% if meta.keywords %}
		<meta name="keywords" content="{{ meta.keywords }}" />
	{% endif %}
	{% if meta.description %}
		<meta name="description" content="{{ meta.description }}" />
	{% endif %}

	<meta name="author" content="Путёвки.ру" />

	<meta property="og:title" content="{{ title }} Путёвки.ру" />
	<meta property="og:image" content="{{ static_url() }}static/yo.png" />
	<meta property="og:description" content="Поиск и продажа путёвок по ценам ниже чем у туроператоров" />

	<script type="text/javascript">
      var env = '{{ config.frontend.env }}';
      var version = '{{ config.frontend.version }}';
      var route = '{{ page }}';
      var branches = {{ branches|json_encode }};
      var cities = {{ cities|json_encode }};
      var currentCity = {{ city|json_encode }};
	</script>

	<script src="{{ static_url('bundle.js') }}"></script>

	<title>{{ title }} Путёвки.ру</title>
</head>
<body>

{{ partial('partials/header') }}

<main class="{{ page }}">

	{{ content() }}

	{{ partial('partials/footer-menu') }}
</main>

{{ partial('partials/footer') }}

{{ partial('partials/mobile-overlay') }}

{{ partial('partials/modals') }}

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

</body>
</html>