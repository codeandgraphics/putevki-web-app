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
	<link rel="icon" type="image/png" href="{{ static_url() }}/img/yo.png">

	<meta name="mobile-web-app-capable" content="yes">

	{% if city.meta_keywords %}
		<meta name="keywords" content="{{ city.meta_keywords }}" />
	{% endif %}
	{% if city.meta_description %}
		<meta name="description" content="{{ city.meta_description }}" />
	{% endif %}
	<meta name="author" content="Путевки.ру" />

	<script type="text/javascript">
      var env = '{{ config.frontend.env }}';
      var version = '{{ config.frontend.version }}';
      var route = '{{ page }}';
      var branches = {{ branches|json_encode }};
      var cities = {{ cities|json_encode }};
      var currentCity = {{ city|json_encode }};
	</script>

	<script src="{{ static_url() }}bundle.js"></script>

	<title>{{ title }} Путевки.ру</title>
</head>
<body>
