<!doctype html>
<html class="no-js" data-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{env('MAIL_FROM_NAME')}}</title>
    <meta name="author" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="description" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="keywords" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('public/v1/img/NTT_fav.png') }}">
    <meta name="theme-color" content="#ffffff">
    @include('layouts.stylesheets_v1')
</head>

<body>
    @include('layouts.header_v1')
    @if(!empty($top_post) && count($top_post) > 0)
        <section class="space--- pt-1">
            <div class="container">
                <div class="row">

                    {{-- LEFT BIG POST --}}
                    @php $first = $top_post->first(); @endphp
                    <div class="col-xl-9 mb-4 mb-xl-0">
                        <div class="row gy-4">
                            <div class="dark-theme img-overlay2">
                                <div class="blog-style3">

                                    <div class="blog-img">
                                        <img src="{{ $first->thumbnails ? url($first->thumbnails->url) : asset('public/v1/img/blog/blog_5_7.jpg') }}"
                                            alt="{{ $first->title }}">
                                    </div>

                                    <div class="blog-content">
                                        @php
                                            // $link = $first->metas()->where('key','alter_link')->first()->description ?? url($first->slug);
                                            $link = route('public.page', ['x' => $first->slug]);
                                        @endphp

                                        <a href="javascript:void(0);" class="category">
                                            {{ $first->metas()->where('key', 'category')->first()->description ?? 'Top Story' }}
                                        </a>

                                        <h3 class="box-title-30">
                                            <a class="hover-line" href="{{ $link }}">
                                                {{ $first->title }}
                                            </a>
                                        </h3>

                                        <div class="blog-meta">
                                            <a href="javascript:void(0);"><i
                                                    class="fal fa-calendar-days"></i>{{ $first->created_at->format('d M, Y') }}</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mt-35 mt-xl-0">
                        <div class="tab-content home-post-sidebar">
                            <div class="tab-pane fade show active" id="nav-one" role="tabpanel"
                                aria-labelledby="nav-one-tab">
                                <div class="row gy-4">
                                    @foreach($top_post->slice(1, 5) as $t_p)
                                        @php
                                            $link = $t_p->metas()->where('key', 'alter_link')->first()->description ?? url($t_p->slug);
                                        @endphp
                                        <div class="col-xl-12 col-md-6 border-blog">
                                            <div class="blog-style2 pb-2">
                                                <div class="blog-img"><img
                                                        src="{{ $t_p->thumbnails ? url($t_p->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_1.jpg') }}"
                                                        alt="{{ $t_p->title }}"></div>
                                                <div class="blog-content"><a data-theme-color="#FF9500"
                                                        href="javascript:void(0);"
                                                        class="category mb-1">{{ $t_p->metas()->where('key', 'category')->first()->title ?? 'News' }}</a>
                                                    <h3 class="box-title-cus"><a class="hover-line"
                                                            href="{{ $link }}">{{ \Illuminate\Support\Str::limit($t_p->title, 55) }}</a>
                                                    </h3>
                                                    <div class="blog-meta"><a href="javascript:void(0);"><i
                                                                class="fal fa-calendar-days"></i>{{ $t_p->created_at->format('d M, Y') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(!empty($others['BENGAL']) && count($others['BENGAL']) > 0)
        <div class="space">
            <div class="container">

                {{-- SECTION HEADER --}}
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="sec-title has-line">The Bengal</h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('add-citizen-journalism') }}" class="th-btn style3 glass-header border-0 me-3">
                            <i class="fas fa-camera-retro me-2"></i> Submit Your News
                        </a>
                    </div>

                    {{-- SLIDER --}}
                    <div class="row th-carousel" id="blog-slide1" data-slide-show="4" data-lg-slide-show="3"
                        data-md-slide-show="2" data-sm-slide-show="2" data-xs-slide-show="2">


                        @foreach($others['BENGAL'] as $the_data)
                            @if(!empty($the_data))
                                <div class="col-sm-6 col-xl-4">
                                    <div class="blog-style1">

                                        <div class="blog-img">
                                            <img src="{{ $the_data->thumbnails ? url($the_data->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_4.jpg') }}"
                                                alt="{{ $the_data->title }}">

                                            <a href="javascript:void(0);" class="category">
                                                {{ $the_data->metas()->where('key', 'category')->first()->description ?? 'Breaking' }}
                                            </a>
                                        </div>

                                        <h3 class="box-title-22 post-title-1">
                                            <a class="hover-line" href="{{ route('public.page', ['x' => $the_data->slug]) }}">
                                                {{ \Illuminate\Support\Str::limit($the_data->title, 65) }}
                                            </a>
                                        </h3>

                                        <div class="blog-meta">
                                            <a href="javascript:void(0);"><i class="fal fa-calendar-days"></i>
                                                {{ $the_data->updated_at->format('d M, Y') }}
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>

    @endif




        <section class="space pt-0">
            <div class="container">
                <div class="row">

                    <div class="col-xl-9">
                        <!-- Start : The Latest -->
                        @if(!empty($others['INDIA']) && count($others['INDIA']) > 0)
                            <div class="row align-items-center mt-4">
                                <div class="col">
                                    <h2 class="sec-title has-line">The India</h2>
                                </div>
                            </div>
                            <div class="filter-active">
                                @foreach($others['BENGAL'] as $latest)
                                    <div class="border-blog2 filter-item cat1">
                                        <div class="blog-style4">
                                            <div class="blog-img">
                                                @if($latest->thumbnails)
                                                    <img src="{{ url($latest->thumbnails->url) }}" />
                                                @else
                                                    <img src="{{ asset('public/img/product/bg-1.jpg') }}"
                                                        alt="{{$latest->title}}" />
                                                @endif
                                            </div>
                                            <div class="blog-content">
                                                <h3 class="box-title-24"><a class="hover-line"
                                                        href="{{ route('public.page', ['x' => $latest->slug]) }}">{{ substr($latest->title, 0, 100) }}...</a>
                                                </h3>
                                                <p class="blog-text">{{ substr($latest->excerpt, 0, 100) }}...</p>
                                                <div class="blog-meta">
                                                    <a href="javascript:void(0);"><i
                                                            class="fal fa-calendar-days"></i>{{ $latest->updated_at->format('d M, Y') }}</a>
                                                </div>
                                                <a href="{{ route('public.page', ['x' => $latest->slug]) }}"
                                                    class="th-btn style2">Read More<i
                                                        class="fas fa-arrow-up-right ms-2"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <!-- End : The Latest -->

                        <!-- Start : THE Exclusive Truth -->
                        @if(!empty($others['THE EXCLUSIVE TRUTH']))
                                                <?php
                            $the_exclusive = $others['THE EXCLUSIVE TRUTH'][0];
                                            ?>
                                                <div class="row align-items-center mt-4">
                                                    <div class="col">
                                                        <h2 class="sec-title has-line">THE Exclusive Truth</h2>
                                                    </div>
                                                </div>
                                                <div class="filter-active">
                                                    <div class="border-blog2 filter-item cat1">
                                                        <div class="blog-style4">
                                                            <div class="blog-img">
                                                                @if($the_exclusive->thumbnails)
                                                                    <img src="{{ url($the_exclusive->thumbnails->url) }}" />
                                                                @else
                                                                    <img src="{{ asset('public/img/product/bg-1.jpg') }}"
                                                                        alt="{{ $the_exclusive->title }}" />
                                                                @endif
                                                            </div>

                                                            <div class="blog-content">
                                                                <h3 class="box-title-24">
                                                                    <a href="{{ route('public.page', ['x' => $the_exclusive->slug]) }}"
                                                                        class="hover-line">
                                                                        {{ $the_exclusive->title }}
                                                                    </a>
                                                                </h3>

                                                                <p class="blog-text">
                                                                    {!! $the_exclusive->excerpt !!}...
                                                                </p>

                                                                <a href="{{ route('public.page', ['x' => $the_exclusive->slug]) }}"
                                                                    class="th-btn style2">Read More<i
                                                                        class="fas fa-arrow-up-right ms-2"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        @endif
                        <!-- End : THE Exclusive Truth -->


                        <!-- Start : THE Untold Truth -->
                        @if(!empty($others['THE UNTOLD TRUTH']))
                                                <?php
                            $the_untold = $others['THE UNTOLD TRUTH'][0];
                                            ?>
                                                <div class="row align-items-center mt-4">
                                                    <div class="col">
                                                        <h2 class="sec-title has-line">THE Exclusive Truth</h2>
                                                    </div>
                                                </div>
                                                <div class="filter-active">
                                                    <div class="border-blog2 filter-item cat1">
                                                        <div class="blog-style4">
                                                            <div class="blog-img">
                                                                @if($the_untold->thumbnails)
                                                                    <img src="{{ url($the_untold->thumbnails->url) }}" />
                                                                @endif
                                                                @if(!empty($the_untold->gallery))
                                                                    @foreach($the_untold->gallery as $e_ut_k => $e_ut_v)
                                                                        @php
                                                                            if ($e_ut_k > 0)
                                                                                break;
                                                                            $e_ut_image = getAttachmentById($e_ut_v->media_id);
                                                                        @endphp
                                                                        @if(!empty($e_ut_image->url))
                                                                            <img src="{{ url($e_ut_image->url) }}" alt="{{ $the_untold->title }}" />
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>

                                                            <div class="blog-content">
                                                                <h3 class="box-title-24">
                                                                    <a href="{{ route('public.page', ['x' => $the_untold->slug]) }}"
                                                                        class="hover-line">
                                                                        {{ $the_untold->title }}
                                                                    </a>
                                                                </h3>

                                                                <p class="blog-text">
                                                                    {!! $the_untold->excerpt !!}...
                                                                </p>

                                                                <a href="{{ route('public.page', ['x' => $the_untold->slug]) }}"
                                                                    class="th-btn style2">Read More<i
                                                                        class="fas fa-arrow-up-right ms-2"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        @endif
                        <!-- End : THE Untold Truth -->


                        <!-- Start : Yours Truly -->
                        @if(!empty($others['Your Truth']) && count($others['Your Truth']) > 0)
                            <div class="row align-items-center mt-4">
                                <div class="col">
                                    <h2 class="sec-title has-line">Yours Truly</h2>
                                </div>
                            </div>
                            <div class="filter-active">
                                @foreach($others['Your Truth'] as $the_data)
                                    @if(!empty($the_data))
                                        <div class="border-blog2 filter-item cat1">
                                            <div class="blog-style4">
                                                <div class="blog-img">
                                                    @if($the_data->thumbnails)
                                                        <img src="{{ url($the_data->thumbnails->url) }}" />
                                                    @else
                                                        <img src="{{ asset('public/img/product/bg-1.jpg') }}"
                                                            alt="{{ $the_data->title }}" />
                                                    @endif
                                                </div>

                                                <div class="blog-content">
                                                    <h3 class="box-title-24">
                                                        <a href="{{ route('public.page', ['x' => $the_data->slug]) }}"
                                                            class="hover-line">
                                                            {{ $the_data->title }}
                                                        </a>
                                                    </h3>

                                                    <p class="blog-text">
                                                        {!! $the_data->excerpt !!}...
                                                    </p>

                                                    <a href="{{ route('public.page', ['x' => $the_data->slug]) }}"
                                                        class="th-btn style2">Read More<i
                                                            class="fas fa-arrow-up-right ms-2"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <!-- End : Yours Truly -->
                    </div>

                    <div class="col-xl-3 mt-35 mt-xl-0 mb-10 sidebar-wrap sidebar-wrap-cus">
                        <div class="sidebar-area">
                            <div class="nav tab-menu indicator-active" role="tablist">
                                @if(!empty($others['POLITICS']) && count($others['POLITICS']) > 0)
                                    <button class="tab-btn active" id="nav3-one-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav3-one" type="button" role="tab" aria-controls="nav3-one"
                                        aria-selected="true">Politics</button>
                                @endif
                                @if(!empty($others['WORLD']) && count($others['WORLD']) > 0)
                                    <button class="tab-btn" id="nav3-two-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav3-two" type="button" role="tab" aria-controls="nav3-two"
                                        aria-selected="false">World</button>
                                @endif
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="nav3-one" role="tabpanel"
                                    aria-labelledby="nav3-one-tab">
                                    <div class="row gy-4">
                                        @foreach($others['POLITICS'] as $the_data)
                                            <div class="blog-content">
                                                <h3 class="box-title-18 box-title-c"><a class="hover-line"
                                                        href="{{ route('public.page', ['x' => $the_data->slug]) }}">{{ \Illuminate\Support\Str::limit($the_data->title, 65) }}</a>
                                                </h3>
                                                <div class="blog-meta"><a href="javascript:void(0);"><i
                                                            class="fal fa-calendar-days"></i>{{ $the_data->updated_at->format('d M, Y') }}</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav3-two" role="tabpanel" aria-labelledby="nav3-two-tab">
                                    <div class="row gy-4">
                                        @foreach($others['WORLD'] as $the_data)
                                            <div class="blog-content">
                                                <h3 class="box-title-18 box-title-c"><a class="hover-line"
                                                        href="{{ route('public.page', ['x' => $the_data->slug]) }}">{{ \Illuminate\Support\Str::limit($the_data->title, 65) }}</a>
                                                </h3>
                                                <div class="blog-meta"><a href="javascript:void(0);"><i
                                                            class="fal fa-calendar-days"></i>{{ $the_data->updated_at->format('d M, Y') }}</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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