{% set env = config.frontend.env %}
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
	<link rel="icon" type="image/png" href="{{ static_url() }}static/yo.png">
	{% if page == 'search' %}
		<link rel="canonical" href="https://putevki.ru/search/{{ params.search.buildShortQueryString() }}/"/>
	{% endif %}

	<meta name="mobile-web-app-capable" content="yes">

    {% if meta.keywords %}
		<meta name="keywords" content="{{ meta.keywords }}" />
    {% endif %}
    {% if meta.description %}
		<meta name="description" content="{{ meta.description }}" />
    {% endif %}

	<meta name="author" content="Путёвки.ру" />

	<meta property="og:title" content="{{ title }} Путёвки.ру – интернет-магазин путёвок" />
	<meta property="og:image" content="{{ static_url() }}static/yo.png" />
	<meta property="og:description" content="{{ meta.description }}" />

	<script type="text/javascript">
        var env = '{{ config.frontend.env }}';
        var version = '{{ config.frontend.version }}';
        var route = '{{ page }}';
        var currentCity = {{ city|json_encode }};
	</script>

    {% if(env==='production') %}
	    <script src="{{ static_url('bundle.js') }}"></script>
    {% else %}
        <script src="/build/bundle.js"></script>
    {% endif %}
    
	<title>{{ title }} Путёвки.ру – интернет-магазин путевок</title>
</head>
<body>

{{ partial('partials/header') }}

<main class="{{ page }}">

    {{ content() }}

    {{ partial('partials/footer-menu') }}
</main>

{{ partial('partials/footer') }}

{{ partial('partials/mobile-overlay') }}

{{ partial('partials/modals') }}


<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<!--LiveInternet counter-->
<script type="text/javascript">
    new Image().src = "//counter.yadro.ru/hit?r"+
        escape(document.referrer)+((typeof(screen)=="undefined")?"":
            ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
            screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
        ";h"+escape(document.title.substring(0,150))+
        ";"+Math.random();
</script>
<!--/LiveInternet-->

<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(31486713, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/31486713" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->


</body>
</html>