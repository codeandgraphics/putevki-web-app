<script type="text/javascript">
	var env = 'production';
</script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="{{ url() }}assets/js/libraries.min.js"></script>
<script src="{{ url() }}assets/js/common.min.js"></script>

<script type="text/javascript">
	var branches = {{ branches|json_encode }};
	var cities = {{ cities|json_encode }};
	var currentCity = {{ currentCity|json_encode }};
</script>