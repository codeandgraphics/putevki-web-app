<?php

$user = (object) $this->session->get('auth');

$current = new stdClass();
$current->controller = $this->dispatcher->getControllerName();
$current->action = $this->dispatcher->getActionName();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="favicon.ico">

	<title>Админ-панель Путевки.ру</title>

	<link href="{{ url('/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ url('/assets/css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ url('/assets/css/jquery-ui.structure.min.css') }}" rel="stylesheet">
	<link href="{{ url('/assets/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ url('/assets/css/animate.css') }}" rel="stylesheet">
	<link href="{{ url('/assets/css/style.css') }}" rel="stylesheet">
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

<div id="messages">
	<?php $this->flashSession->output(); ?>
</div>

{% if current.controller == 'users' and current.action == 'login' %}

	{{ content() }}

{% else %}

	{{ partial("partials/navbar") }}

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				{{ partial("partials/menu") }}
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				{{ content() }}
			</div>
		</div>
	</div>

	{{ partial("partials/footer") }}

{% endif %}
</body>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="{{ url('/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ url('/assets/js/bootstrap-notify.min.js') }}"></script>
<script src="{{ url('/assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ url('/assets/js/libs.js') }}"></script>
<script src="{{ url('/assets/js/script.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

</html>