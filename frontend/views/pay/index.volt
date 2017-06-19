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

	<title>{{ title }} – Путевки.ру</title>

	<link rel="stylesheet" type="text/css" href="{{ static_url() }}css/putevki.min.css" />

</head>
<body>
<div style='font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; width: 500px; margin: 50px auto; text-align: center;'>
	Перенаправляем вас на страницу оплаты...<br/><br/>
	<a href="{{ url() }}" style="font-size: 11px;">Путевки.ру</a>
</div>
<form action="https://wpay.uniteller.ru/pay/" method="POST" id="buy" style="display:none;">
	<input type="hidden" name="Shop_IDP" value="{{ uniteller.Shop_IDP }}">
	<input type="hidden" name="Order_IDP" value="{{ uniteller.Order_IDP }}">
	<input type="hidden" name="Subtotal_P" value="{{ uniteller.Subtotal_P }}">
	<input type="hidden" name="Lifetime" value="{{ uniteller.Lifetime }}">
	<input type="hidden" name="Signature" value="{{ uniteller.getPaymentSignature() }}">
	<input type="hidden" name="URL_RETURN_OK" value="{{ uniteller.URL_RETURN_OK }}">
	<input type="hidden" name="URL_RETURN_NO" value="{{ uniteller.URL_RETURN_NO }}">
	<input type="submit" name="Submit" value="Оплатить">
</form>

<script type="text/javascript">
	document.getElementById('buy').submit();
</script>

</body>
</html>
