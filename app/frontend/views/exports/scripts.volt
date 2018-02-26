<script type="text/javascript">
	var env = 'production';
</script>
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="{{ static_url() }}js/libraries.min.js"></script>
<script src="{{ static_url() }}js/common.min.js"></script>

<script type="text/javascript">
	var branches = {{ branches|json_encode }};
	var cities = {{ cities|json_encode }};
	var currentCity = {{ currentCity|json_encode }};
</script>