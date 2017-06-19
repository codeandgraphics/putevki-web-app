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

	{% if currentCity.meta_keywords %}
		<meta name="keywords" content="{{ currentCity.meta_keywords }}" />
	{% endif %}
	{% if currentCity.meta_description %}
		<meta name="description" content="{{ currentCity.meta_description }}" />
	{% endif %}
	<meta name="author" content="Путевки.ру" />

	<title>{{ title }} Путевки.ру</title>

	<link rel="stylesheet" type="text/css" href="{{ static_url() }}css/common.min.css" />
	
	<link rel="stylesheet" type="text/css" href="{{ static_url() }}css/putevki.min.css" />
	{% if page is not empty %}
	<link rel="stylesheet" type="text/css" href="{{ static_url() }}css/{{ page }}.min.css" />
	{% endif %}
</head>
<body>
