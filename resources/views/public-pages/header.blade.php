<!doctype html>

<html class="no-js" lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="profile" href="https://gmpg.org/xfn/11">

	<meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />

	@if( !empty( $seo_data['meta']['site_description'] ) )

	    <meta name="description" content="{{$seo_data['meta']['site_description']}}" />

	@endif

	@if( !empty( $seo_data['meta']['canonical'] ) )

	    <link rel="canonical" href="{{$seo_data['meta']['canonical']}}" />

	@else

	    <link rel="canonical" href="{{URL::full()}}" />

	@endif

	<meta property="og:locale" content="en_IN" />

	@if( !empty( $seo_data['meta']['type'] ) )

	    <meta property="og:type" content="{{$seo_data['meta']['type']}}" />

	@endif

	@if( !empty( $seo_data['meta']['site_title'] ) )

	    <meta property="og:title" content="{{$seo_data['meta']['site_title']}}" />

	@endif

	@if( !empty( $seo_data['meta']['site_description'] ) )

	    <meta property="og:description" content="{!! $seo_data['meta']['site_description'] !!}" />

	@endif

	<meta property="og:url" content="{{URL::full()}}" />

	<meta property="og:site_name" content="NTT" />

	@if( !empty( $seo_data['meta']['follow_facebook'] ) )

	    <meta property="article:publisher" content="{{$seo_data['meta']['follow_facebook']}}" />

	@endif

	@if( !empty( $seo_data['meta']['modified_time'] ) )

	    <meta property="article:modified_time" content="{{$seo_data['meta']['modified_time']}}" />

	@endif

	@if( !empty( $seo_data['meta']['image'] ) )

	    <meta property="og:image" content="{{$seo_data['meta']['image']}}" />

	@endif

	<meta property="og:image:width" content="190" />

	<meta property="og:image:height" content="72" />

	<meta property="og:image:type" content="image/jpg" />

	@if( !empty( $seo_data['meta']['site_title'] ) )

        <title>{{$seo_data['meta']['site_title'] }}</title>

    @endif

    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('site_icon') ) )

        @php

            $site_icon = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('site_icon')->description);

        @endphp

        @if( !empty( $site_icon->url ) )

            <link rel="icon" type="image/x-icon" href="{{ url($site_icon->url) }}">

        @endif

    @endif

    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('g_tag') ) )

        @php

            $g_tag = (\App\Http\Controllers\SiteSettingsController::check('g_tag')->description);

        @endphp

        @if( !empty( $g_tag ) )

            <!-- Google tag (gtag.js) -->

            <script async src="https://www.googletagmanager.com/gtag/js?id=G-{{ $g_tag }}"></script>

            <script>

                window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-{{ $g_tag }}'); 

            </script>

        @endif

    @endif

    

    <meta name="viewport" content="width=device-width, initial-scale=1">

    

    <!-- Google Fonts

		============================================ -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">

    <!-- Bootstrap CSS

		============================================ -->

    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/css/datapicker/datepicker3.css') }}">

    <!-- Bootstrap CSS 

        ============================================== -->

    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-toggle.min.css') }}">

    <!-- Bootstrap Toggle CSS

		============================================ -->

    <link rel="stylesheet" href="{{ asset('public/js/Owl/dist/assets/owl.carousel.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/js/Owl/dist/assets/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/fonts/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('public/css/mobile.css') }}">

    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>



<body class="{{ (!empty($body_classes)? $body_classes: "default") }}">

    <!--[if lt IE 8]>

            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>

        <![endif]-->

    <header class="site-navbar site-navbar-target py-4 bg-white site-head" role="banner">

        <div class="container-fluid">

            <div class="row">

                <div class="col col-md-2 col-xs-3 order-xs-2">

                    <div class="site-mobile-menu site-navbar-target">

                        <div class="site-mobile-menu-header">

                            <div class="site-mobile-menu-close mt-3">

                                <span class="icon-close2 js-menu-toggle"></span>

                            </div>

                        </div>

                        <div class="site-mobile-menu-body">



                            @if( !empty( \App\Http\Controllers\SiteSettingsController::check('primary_menu') ) )

                                @php

                                    $pmenu_id = \App\Http\Controllers\SiteSettingsController::check('primary_menu');

                                    $pmenu = App\Models\Menu::where('id', $pmenu_id->description )->first();

                                @endphp

                                @if( !empty( $pmenu->items ) )

                                    <nav class="site-navigation text-right ml-auto d-none d-lg-none" role="navigation">

                                        <ul class="site-nav-wrap ">

                                            @foreach( $pmenu->items as $k =>$v )

                                                <li><a href="{{ $v->slug }}" class="nav-link">{{ $v->display_name }}</a></li>

                                            @endforeach

                                            <!--<li>

                                                <div class="input-group date" data-provide="datepicker">

                                                    <input type="text" class="form-control">

                                                    <div class="input-group-addon">

                                                        <span class="glyphicon glyphicon-th"></span>

                                                    </div>

                                                </div>

                                            </li>-->

                                        </ul>

                                    </nav>

                                @endif

                            @else

                                <nav class="site-navigation text-right ml-auto d-none d-lg-none" role="navigation">

                                    <ul class="site-nav-wrap ">

                                        <li class="active"><a href="index.html" class="nav-link">Home</a></li>

                                        <li><a href="about.html" class="nav-link">About</a></li>

                                        <li><a href="services.html" class="nav-link">Services</a></li>

                                        <li><a href="blog.html" class="nav-link">Blog</a></li>

                                        <li><a href="contact.html" class="nav-link">Contact</a></li>

                                    </ul>

                                </nav>

                            @endif

                        </div>

                    </div>

                    <div class=" order-1 text-left mr-auto">

                      <span class="d-inline-block d-lg-block"><a href="#" class="text-black site-menu-toggle js-menu-toggle py-5"><span class="icon-menu h1"></span></a></span>

                    </div>

                </div>

                <div class="col col-xs-9 col-md-8 order-xs-3 text-center main-logo">

                    

                    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('header_left') ) )

                        @php

                            $header_left = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('header_left')->description);

                        @endphp

                        @if( !empty( $header_left->url ) )

                            <span class="header-left hide-mobile">

                                <a href="{{ !empty(\App\Http\Controllers\SiteSettingsController::check('header_right_url')->description)?\App\Http\Controllers\SiteSettingsController::check('header_right_url')->description:'/' }}" target="_blank">

                                    <img class="add-beside-logo" src="{{ url($header_left->url) }}" alt=""/>

                                </a>

                            </span>

                        @endif

                    @endif

                    <strong>

                        @if( !empty( \App\Http\Controllers\SiteSettingsController::check('site_logo') ) )

                            @php

                                $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('site_logo')->description);

                            @endphp

                            @if( !empty( $logo->url ) )

                                <a href="/"><img class="main-logo" src="{{ url($logo->url) }}" alt=""/></a>

                            @endif

                        @else

                            <img class="main-logo" src="{{ asset('public/img/logo/logo.jpg') }}" alt=""/>

                        @endif

                    </strong>

                    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('header_right') ) )

                        @php

                            $header_right = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('header_right')->description);

                            

                        @endphp

                        @if( !empty( $header_right->url ) )

                            <span class="header-right hide-mobile">

                                <a href="{{ !empty(\App\Http\Controllers\SiteSettingsController::check('header_left_url')->description)?\App\Http\Controllers\SiteSettingsController::check('header_left_url')->description:'/' }}" target="_blank">

                                    <img class="add-beside-logo" src="{{ url($header_right->url) }}" alt=""/>

                                </a>

                            </span>

                        @endif

                    @endif

                </div>

                <div class="col col-xs-12 col-md-2 order-xs-1 support-button">

                    <!--<span class="pull-right btn btn-tdanger">support</span>-->

                    @if( !empty( Illuminate\Support\Facades\Auth::user()->type ) )

                        @if( Illuminate\Support\Facades\Auth::user()->type == 'user' )

                            <span class="pull-right">

                                <nav class="navbar navbar-expand-lg navbar-light bg-light user-menu">

                                    <div class="navbar-collapse" id="navbarNavDropdown">

                                        <ul class="navbar-nav">

                                            <li class="nav-item dropdown">

                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                    Dadshboard

                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                                                    <a href="{{ route('add-citizen-journalism') }}" class="dropdown-item">

                                                        Citizen Journalism

                                                    </a>

                                                    <a href="{{ route('list-citizen-journalism') }}" class="dropdown-item">

                                                        My Journalism

                                                    </a>

                                                    <a class="dropdown-item" href="{{ route('userlogout') }}">

                                                        <span class="icon nalika-unlocked author-log-ic"></span> {{ __('Logout') }}

                                                    </a>

                                                </div>

                                            </li>

                                        </ul>

                                    </div>

                                </nav>

                            </span>

                        @else

                            <span class="pull-right">

                                <span class="btn btn-primary">

                                    <a class="nav-link" href="{{ route('dashboard') }}" style="color:#fff;">{{ __('Dashboard') }}</a>

                                </span>

                            </span>

                        @endif

                    @else

                        <span class="pull-right btn btn-tprimary">

                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>

                        </span>

                        <span class="pull-right btn btn-tdefault">

                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>

                        </span>

                    @endif

                </div>

            </div>

        </div>

    </header>

    <section class="site-navbar site-navbar-target py-4 sub-nav" role="banner">

        <div class="container">

            <div class="row align-items-center position-relative">

                <div class="col-12 order-1 text-center mr-auto">

                    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('secondary_menu') ) )

                        @php

                            $smenu_id = \App\Http\Controllers\SiteSettingsController::check('secondary_menu');

                            $smenu = App\Models\Menu::where('id', $smenu_id->description )->first();

                        @endphp

                        @if( !empty( $smenu->items ) )

                            <nav class="site-navigation text-center ml-auto d-none d-lg-none" role="navigation">

                                <ul class="site-menu main-menu js-clone-nav ml-auto ">

                                    @foreach( $smenu->items as $k =>$v )

                                        <li><a href="{{ $v->slug }}" class="nav-link">{{ $v->display_name }}</a></li>

                                    @endforeach

                                   

                                </ul>

                            </nav>

                        @endif

                    @else

                        <nav class="site-navigation text-center ml-auto d-none d-lg-none" role="navigation">

                            <ul class="site-menu main-menu js-clone-nav ml-auto ">

                              <li class="active"><a href="index.html" class="nav-link">Home</a></li>

                              <li><a href="about.html" class="nav-link">About</a></li>

                              <li><a href="services.html" class="nav-link">Services</a></li>

                              <li><a href="blog.html" class="nav-link">Blog</a></li>

                              <li><a href="contact.html" class="nav-link">Contact</a></li>

                            </ul>

                          </nav>

                    @endif

                </div>

            </div>

        </div>

    </section>

    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('pop_up_add') ) )

        @php

            $pop_up_add = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('pop_up_add')->description);



             if( !empty( \App\Http\Controllers\SiteSettingsController::check('pop_up_add_url') ) ):

                $pop_up_add_url = \App\Http\Controllers\SiteSettingsController::check('pop_up_add_url')->description;

            endif;

        @endphp

        @if( !empty( $pop_up_add->url ) )

            <div class="popup" id="popup">

                <button id="popup-close" type="button" class="close" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

                @if( !empty( $pop_up_add_url ) )

                <a href="{{ $pop_up_add_url }}" target="_blank">

                @endif

                <img class="pop-up-logo" src="{{ url($pop_up_add->url) }}" alt=""/>

                @if( !empty( $pop_up_add_url ) )

                </a>

                @endif

            </div>

        @endif

    @endif

    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('mobile_pop_up_add') ) )

        @php

            $mobile_pop_up_add = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('mobile_pop_up_add')->description);

            if( !empty( \App\Http\Controllers\SiteSettingsController::check('mobile_pop_up_add_url') ) ):

                $mobile_pop_up_add_url = \App\Http\Controllers\SiteSettingsController::check('mobile_pop_up_add_url')->description;

            endif;

        @endphp

        @if( !empty( $mobile_pop_up_add->url ) )

            <div class="popup" id="mobile-popup">

                <button id="mpopup-close" type="button" class="close" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

                @if( !empty( $mobile_pop_up_add_url ) )

                <a href="{{ $mobile_pop_up_add_url }}" target="_blank">

                @endif

                <img class="pop-up-logo" src="{{ url($mobile_pop_up_add->url) }}" alt="" />

                @if( !empty( $mobile_pop_up_add_url ) )

                </a>

                @endif

            </div>

        @endif

    @endif

    