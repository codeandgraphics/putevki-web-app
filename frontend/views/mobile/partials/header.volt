<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="icon" type="image/png" href="/assets/img/yo.png">

    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ title }} Путевки.ру</title>

    <link rel="stylesheet" type="text/css" href="/assets/css/common.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/putevki.min.css" />

    {% if config.frontend.env == 'development' %}

        <link rel="stylesheet/less" type="text/css" href="/_admin/assets_frontend_dev/less/pages/{{ page }}.less" />
        <script>
            less = {
                env: "development"
            };
        </script>
        <script src="/_admin/assets_frontend_dev/js/less.min.js"></script>
        <script>less.watch();</script>

    {% else %}

    <link rel="stylesheet" type="text/css" href="/assets/css/{{ page }}.min.css" />

    {% endif %}

</head>
<body>