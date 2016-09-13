<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=1230">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="icon" type="image/png" href="/assets/img/yo.png">

	<meta name="mobile-web-app-capable" content="yes">

	{% if currentCity.meta_keywords %}
		<meta name="keywords" content="{{ currentCity.meta_keywords }}" />
	{% endif %}
	{% if currentCity.meta_description %}
		<meta name="description" content="{{ currentCity.meta_description }}" />
	{% endif %}
	<meta name="author" content="Путевки.ру" />

	<title>{{ title }} Путевки.ру</title>

	<link rel="stylesheet" type="text/css" href="{{ url() }}assets/css/common.min.css" />
	{% if config.frontend.env == 'development' %}

		<link rel="stylesheet/less" type="text/css" href="{{ url() }}assets_frontend_dev/less/main.less" />
		{% if page is not empty %}
		<link rel="stylesheet/less" type="text/css" href="{{ url() }}assets_frontend_dev/less/pages/{{ page }}.less" />
		{% endif %}
		<script>
			less = {
				env: "development"
			};
		</script>
		<script src="{{ url() }}assets_frontend_dev/js/less.min.js"></script>
		<script>less.watch();</script>

	{% else %}

	<link rel="stylesheet" type="text/css" href="{{ url() }}assets/css/putevki.min.css" />
	{% if page is not empty %}
	<link rel="stylesheet" type="text/css" href="{{ url() }}assets/css/{{ page }}.min.css" />
	{% endif %}
	{% endif %}

</head>
<body>
