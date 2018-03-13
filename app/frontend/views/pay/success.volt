{% set title = 'Ожидаем подтверждение платежа' %}
{% if payment.isSuccess() %}
	{% set title = 'Успешная оплата' %}
{% endif %}
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=1230">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="icon" type="image/png" href="{{ static_url() }}img/yo.png">

	<meta name="mobile-web-app-capable" content="yes">

	<title>{{ title }} – Путёвки.ру</title>

	<link rel="stylesheet" type="text/css" href="{{ static_url() }}css/putevki.min.css" />
</head>
<body>
<div style='font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; width: 500px; margin: 50px auto; text-align: center;'>
	Номер заказа: {{ payment.getOrder() }}<br/><br/>
	{% if payment.isSuccess() %}
		Оплата прошла успешно!<br/><br/>
		Наш менеджер скоро свяжется с вами.<br/>Спасибо и удачного путешествия!<br/><br/>
	{% else %}
		Ожидаем подтверждение платежа. <br/>
		Попробуйте <a href="javascript:location.reload();">обновить страницу</a> через 10 секунд.<br/><br/>
		Если платеж не проходит, свяжитесь с менеджером по телефону
		<a href="tel:{{ config.frontend.phoneLink }}">{{ config.frontend.phone }}</a> и назовите номер заказа.<br/><br/>
	{% endif %}
	<a href="{{ url() }}" style="font-size: 11px;">Путёвки.ру</a>
</div>
</body>
</html>
