<!doctype html>
<html class="no-js" data-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $the_page->title }} - {{env('MAIL_FROM_NAME')}}</title>
    <meta name="author" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="description" content="{{ $the_page->title }}">
    <meta name="keywords" content="{{ $the_page->title }}">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('public/v1/img/favicons/favicon-32x32.png') }}">
    <meta name="theme-color" content="#ffffff">
    @include('layouts.stylesheets_v1')
</head>

<body>
    @include('layouts.header_v1')
    <div class="breadcumb-wrapper">
        <div class="container">
            <ul class="breadcumb-menu">
                <li><a href="{{route('v1.home')}}">Home</a></li>
                <li>{{ $the_page->title }}</li>
            </ul>
        </div>
    </div>
    <section class="space">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="sec-title has-line">{{ $the_page->title }} </h1>
                            @if( $the_page->subtitle )
                            <h2>{{ $the_page->title }}</h2>
                            @endif
                        </div>
                    </div>
                    <div class="filter-active">
                        <div class="border-blog2 filter-item cat1">
                            <div class="blog-style4">
                                <div class="blog-content hover-line">
                                    {!! $the_page->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="add">
                        @if( !empty( \App\Http\Controllers\SiteSettingsController::check('global_add') ) )
                        @php
                        $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('global_add')->description);
                        @endphp
                        @if( !empty( $logo->url ) )
                        <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 100%;" />
                        @else
                        <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
                        @endif
                        @else
                        <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')
</body>

</html>