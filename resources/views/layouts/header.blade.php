<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard V.2 | Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="{{ asset('public/image/x-icon') }}" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">
    <!-- Bootstrap CSS 
        ============================================== -->
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-toggle.min.css') }}">
    <!-- Bootstrap Toggle CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">
	<!-- nalika Icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/nalika-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/meanmenu.min.css') }}">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/s-style.css') }}">
    <!-- morrisjs CSS
        ============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/morrisjs/morris.css') }}">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/metisMenu/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/metisMenu/metisMenu-vertical.css') }}">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('public/style.css') }}">
    <!-- responsive CSS
		============================================ --> 
    <link rel="stylesheet" href="{{ asset('public/mystyle.css') }}">
    <!-- responsive CSS
        ============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/responsive.css') }}">
    <!-- modernizr JS
		============================================ -->
    <script src="{{ asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <!-- select picker CSS
        ============================================ -->
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/js/kothlin/css/kothing-editor.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script type="text/javascript">
        var csrf_token = "{{ csrf_token() }}";
        var base_url = "{{ url('') }}";
    </script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    