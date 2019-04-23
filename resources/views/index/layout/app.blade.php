<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="format-detection" content="telephone=no">
    <!-- This make sence for mobile browsers. It means, that content has been optimized for mobile browsers -->
    <meta name="HandheldFriendly" content="true">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta-tags')

    <!-- Stylesheet -->
    <link href="/static/css/main.min.css?v=14" rel="stylesheet" type="text/css">
    <link href="/static/css/separate-css/custom.css?v=9" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="/favicon.png?v=1">

    <script>
        (function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)
    </script>
    <!--[if lt IE 9 ]>
    <script src="/static/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script>
    <script src="/static/js/separate-js/respond.min.js" type="text/javascript"></script>
    <meta content="no" http-equiv="imagetoolbar">
    <![endif]-->

    <link rel="stylesheet" href="/custom/css/main.css?v=28">

</head>