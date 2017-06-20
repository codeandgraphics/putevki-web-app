</main>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="{{ static_url() }}bundle.js"></script>

<script type="text/javascript">
	var env = '{{ config.frontend.env }}';
	var version = '{{ config.frontend.version }}';
</script>

<!--
{% if config.frontend.env == 'production' %}

	<script src="{{ static_url() }}js/common.min.js"></script>
	{% if page is not empty %}
		<script src="{{ static_url() }}js/{{ page }}.min.js"></script>
	{% endif %}

	<!— Yandex.Metrika counter —>
	<script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter31486713 = new Ya.Metrika({ id:31486713, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/31486713" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!— /Yandex.Metrika counter —>

{% else %}

	<script src="{{ url() }}development-assets-frontend/js/common/humanize.util.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/helpers.util.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/form.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/hotelForm.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/search.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/tour.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/findTour.class.js"></script>
	{% if page is not empty %}
		<script src="{{ url() }}development-assets-frontend/js/pages/{{ page }}.js?v{{ version }}"></script>
	{% endif %}
{% endif %}

-->

<script type="text/javascript">
	var branches = {{ branches|json_encode }};
	var cities = {{ cities|json_encode }};
	var currentCity = {{ currentCity|json_encode }};
</script>

{% if currentCity.meta_text %}
	<div class="meta">
		{{ currentCity.meta_text }}
	</div>
{% endif %}
</body>
</html>
