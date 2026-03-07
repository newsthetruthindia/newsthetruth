<!doctype html>
<html class="no-js" data-theme="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $cat_data->title }} - {{env('MAIL_FROM_NAME')}}</title>
    <meta name="author" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="description" content="{{ $cat_data->title }}">
    <meta name="keywords" content="{{ $cat_data->title }}">
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
                <li>{{ $cat_data->title }} News</li>
            </ul>
        </div>
    </div>
    <section class="space">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="sec-title has-line">{{ $cat_data->title }} News</h1>
                            @if( $cat_data->subtitle )
                            <h2>{{ $cat_data->title }}</h2>
                            @endif
                        </div>
                    </div>
                    <div class="filter-active">
                        @if( !empty( $posts ) && count( $posts ) > 0 )
                        @foreach( $posts as $item)
                        @if( !empty( $item ) )
                        <div class="border-blog2 filter-item cat1">
                            <div class="blog-style4">
                                <div class="blog-img">
                                    @if( $item->thumbnails)
                                    <img src="{{ url($item->thumbnails->url) }}" alt="blog image">
                                    @else
                                    <img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
                                    @endif
                                </div>
                                <div class="blog-content">
                                    <h3 class="box-title-24"><a class="hover-line" href="{{ route('public.page', ['x' => $item->slug]) }}">{{ substr($item->title,0,100); }}...</a></h3>
                                    <p class="blog-text">{{ substr($item->excerpt,0,100); }}...</p>
                                    <div class="blog-meta">
                                        <a href="javascript:void(0);">
                                            <i class="fal fa-calendar-days"></i> {{ \Carbon\Carbon::parse($item->post_publish_time)->format('d M, Y') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                    {!! $posts->links('vendor.pagination.th-pagination') !!}
                    <h2 class="sec-title has-line mt-30">TOP POSTS</h2>
                    <div class="filter-active">
                        @if( !empty( $top_post ) && count( $top_post ) > 0 )
                        @foreach( $top_post as $key => $latest )
                        <div class="border-blog2 filter-item cat1">
                            <div class="blog-style4">
                                <div class="blog-img">
                                    @if( $latest->thumbnails)
                                    <img src="{{ url($latest->thumbnails->url) }}" />
                                    @else
                                    <img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
                                    @endif
                                </div>
                                <div class="blog-content">
                                    <h3 class="box-title-24"><a class="hover-line" href="{{ route('public.page', ['x' => $latest->slug])}}">{{ substr($latest->title,0,100); }}...</a></h3>
                                    <p class="blog-text">{!! $latest->excerpt !!}...</p>
                                    <div class="blog-meta">
                                        <a href="javascript:void(0);">
                                            <i class="fal fa-calendar-days"></i> {{ \Carbon\Carbon::parse($latest->post_publish_time)->format('d M, Y') }}
                                        </a>
                                    </div>
                                    <a href="{{ route('public.page', ['x' => $latest->slug])}}" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <!-- <h2>LATEST NEWS</h2> -->
                    <h2 class="sec-title has-line pt-5">LATEST NEWS</h2>
                    <div class="filter-active">
                        @if( !empty( $the_latest ) && count( $the_latest ) > 0 )

                        @foreach( $the_latest as $key => $latest )
                        <div class="border-blog2 filter-item cat1">
                            <div class="blog-style4">
                                <div class="blog-img">
                                    @if( $latest->thumbnails)
                                    <img src="{{ url($latest->thumbnails->url) }}" />
                                    @else
                                    <img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
                                    @endif
                                </div>
                                <div class="blog-content">
                                    <h3 class="box-title-24"><a class="hover-line" href="{{ route('public.page', ['x' => $latest->slug])}}">{{ substr($latest->title,0,100); }}...</a></h3>
                                    <p class="blog-text">{!! $latest->excerpt !!}...</p>
                                    <div class="blog-meta">
                                        <a href="javascript:void(0);"><i class="fal fa-calendar-days"></i>{{ $latest->updated_at->format('d M, Y') }}</a>
                                    </div>
                                    <a href="{{ route('public.page', ['x' => $latest->slug]) }}" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                </div>
                <div class="col-xl-3 mt-35 mt-xl-0 mb-10 sidebar-wrap">
                    <div class="sidebar-area">
                        <div class="widget widget_categories">
                            <h3 class="widget_title">More Category</h3>
                            <ul>
                                @if( !empty( $categories ) )
                                @foreach( $categories as $cat )
                                <li><a href="{{ route('public.page', ['x' => $cat->slug ])}}">{{ $cat->title }}</a></li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')
</body>

</html>