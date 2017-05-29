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

	<title>{{ title }} – Путевки.ру</title>

	<link rel="stylesheet" type="text/css" href="/assets/css/putevki.min.css" />

</head>
<body>
<div style='font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; width: 500px; margin: 50px auto; text-align: center;'>
	Данный платеж не может быть проведен.<br/><br/>
	Попробуйте позже, или свяжитесь с менеджером по телефону
	<a href="tel:{{ config.frontend.phoneLink }}">{{ config.frontend.phone }}</a>.<br/><br/>
	<a href="{{ url() }}" style="font-size: 11px;">Путевки.ру</a>
</div>
</body>
</html>
