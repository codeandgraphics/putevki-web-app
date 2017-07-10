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

	<title>Панель управления Путёвки.ру</title>

	<link href="{{ static_url('static/admin/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ static_url('static/admin/css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ static_url('static/admin/css/jquery-ui.structure.min.css') }}" rel="stylesheet">
	<link href="{{ static_url('static/admin/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ static_url('static/admin/css/bootstrap-editable.css') }}" rel="stylesheet"/>
	<link href="{{ static_url('static/admin/css/animate.css') }}" rel="stylesheet">
	<link href="{{ static_url('static/admin/css/quill.snow.css') }}" rel="stylesheet">
	<link href="{{ static_url('static/admin/css/style.css') }}" rel="stylesheet">

	<script src="{{ static_url('static/admin/js/jquery.min.js?1.11.3') }}"></script>

	<!--[if lt IE 9]>
	<script src="{{ static_url('static/admin/js/html5shiv.min.js?3.7.3') }}"></script>
	<script src="{{ static_url('static/admin/js/respond.min.js?1.4.2') }}"></script>
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

<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="{{ static_url('static/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ static_url('static/admin/js/bootstrap-notify.min.js') }}"></script>
<script src="{{ static_url('static/admin/js/bootstrap-editable.min.js?1.5.1') }}"></script>
<script src="{{ static_url('static/admin/js/jquery-ui.min.js') }}"></script>
<script src="{{ static_url('static/admin/js/libs.js') }}"></script>
<script src="{{ static_url('static/admin/js/script.js') }}"></script>

</html>