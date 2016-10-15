</main>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="{{ url() }}assets/js/libraries.min.js"></script>

<script type="text/javascript">
	var env = '{{ config.frontend.env }}';
</script>

{% if config.frontend.env == 'production' %}


	<script src="{{ url() }}assets/js/common.min.js"></script>
	{% if page is not empty %}
		<script src="{{ url() }}assets/js/{{ page }}.min.js"></script>
	{% endif %}

	<!-- StreamWood code -->
	<link href="//clients.streamwood.ru/StreamWood/sw.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="//clients.streamwood.ru/StreamWood/sw.js" charset="utf-8"></script>
	<script type="text/javascript">
		swQ(document).ready(function(){
			swQ().SW({
				swKey: '807c4063f8fd3da43661d3087d697c7e',
				swDomainKey: '8f5926e6c9302a912044ab2eb2a71c12'
			});
			swQ('body').SW('load');
		});
	</script>
	<style type="text/css">
		.stream-wood-btn.sw-btn-position-bottom, .stream-wood-btn.sw-btn-position-bottom:hover, a.stream-wood-btn.sw-btn-position-bottom, a.stream-wood-btn.sw-btn-position-bottom:hover
		{
			right: 70px !important;
		}
	</style>
	<!-- /StreamWood code -->

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter40142310 = new Ya.Metrika({ id:40142310, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/40142310" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

{% else %}

	<script src="{{ url() }}development-assets-frontend/js/common/humanize.util.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/helpers.util.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/form.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/hotelForm.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/search.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/tour.class.js"></script>
	<script src="{{ url() }}development-assets-frontend/js/common/findTour.class.js"></script>
	{% if page is not empty %}
		<script src="{{ url() }}development-assets-frontend/js/pages/{{ page }}.js"></script>
	{% endif %}
{% endif %}

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
