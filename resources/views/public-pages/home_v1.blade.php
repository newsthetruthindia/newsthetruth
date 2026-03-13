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
        <section class="space--- pt-4 pb-5">
            <div class="container-fluid px-lg-5">
                <div class="row g-4">
                    {{-- MAIN HERO STORY --}}
                    @php $first = $top_post->first(); @endphp
                    <div class="col-xl-8 col-lg-7">
                        <div class="premium-card h-100 position-relative border-0 shadow-lg overflow-hidden group">
                            <div class="blog-img h-100">
                                <img src="{{ $first->thumbnails ? url($first->thumbnails->url) : asset('public/v1/img/blog/blog_5_7.jpg') }}"
                                    alt="{{ $first->title }}" class="w-100 h-100 object-fit-cover transition-all duration-700 group-hover:scale-105">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4 p-lg-5 bg-gradient-to-t from-black/90 to-transparent">
                                    <div class="mb-3">
                                        <span class="badge bg-primary px-3 py-2 text-uppercase tracking-wider">
                                            {{ $first->metas()->where('key', 'category')->first()->description ?? 'Top Story' }}
                                        </span>
                                    </div>
                                    <h2 class="text-white display-5 fw-bold mb-3">
                                        <a href="{{ route('public.page', ['x' => $first->slug]) }}" class="text-white text-decoration-none hover-line">
                                            {{ $first->title }}
                                        </a>
                                    </h2>
                                    <div class="text-white/80 d-flex align-items-center small">
                                        <i class="fal fa-calendar-days me-2"></i> {{ $first->created_at->format('d M, Y') }}
                                        <span class="mx-3 opacity-30">|</span>
                                        <span class="fw-medium">By Editorial Team</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR TRENDING GRID --}}
                    <div class="col-xl-4 col-lg-5">
                        <div class="h-100 d-flex flex-column">
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 fw-bold border-start border-primary border-4 ps-3 text-uppercase small tracking-widest">Trending Now</h4>
                                <a href="#" class="small text-muted text-decoration-none hover:text-primary transition-colors">View All</a>
                            </div>
                            <div class="flex-grow-1 overflow-auto pe-2" style="max-height: 600px;">
                                @foreach($top_post->slice(1, 4) as $t_p)
                                    @php $link = route('public.page', ['x' => $t_p->slug]); @endphp
                                    <div class="premium-card mb-3 p-3 border-0 bg-light/50 hover:bg-white transition-all">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-4">
                                                <div class="rounded-3 overflow-hidden shadow-sm" style="aspect-ratio: 1/1;">
                                                    <img src="{{ $t_p->thumbnails ? url($t_p->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_1.jpg') }}"
                                                         class="w-100 h-100 object-fit-cover hover:scale-110 transition-transform duration-500" alt="{{ $t_p->title }}">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <span class="text-primary x-small fw-bold text-uppercase mb-1 d-block">{{ $t_p->metas()->where('key', 'category')->first()->title ?? 'Update' }}</span>
                                                <h5 class="mb-0 h6 fw-bold lh-base">
                                                    <a href="{{ $link }}" class="text-dark text-decoration-none hover-line">{{ \Illuminate\Support\Str::limit($t_p->title, 55) }}</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                                    <div class="premium-card h-100 p-0 border-0 bg-white">
                                        <div class="blog-img mb-0" style="aspect-ratio: 16/10;">
                                            <img src="{{ $the_data->thumbnails ? url($the_data->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_4.jpg') }}"
                                                alt="{{ $the_data->title }}" class="w-100 h-100 object-fit-cover transition-all duration-500 hover:scale-110">
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-primary px-3 py-2 text-uppercase tracking-wider small shadow-sm">
                                                    {{ $the_data->metas()->where('key', 'category')->first()->description ?? 'Bengal' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="blog-content p-4">
                                            <h3 class="h6 fw-bold lh-base mb-3">
                                                <a class="text-dark text-decoration-none hover-line" href="{{ route('public.page', ['x' => $the_data->slug]) }}">
                                                    {{ \Illuminate\Support\Str::limit($the_data->title, 65) }}
                                                </a>
                                            </h3>

                                            <div class="blog-meta d-flex align-items-center text-muted small">
                                                <i class="fal fa-calendar-days me-2"></i>
                                                {{ $the_data->updated_at->format('d M, Y') }}
                                            </div>
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
                            <div class="row align-items-center mb-4 mt-5">
                                <div class="col">
                                    <h2 class="sec-title show-line fw-bold text-uppercase tracking-wider h3 mb-0">The India</h2>
                                </div>
                            </div>
                            <div class="filter-active row g-4">
                                @foreach($others['BENGAL'] as $latest)
                                    <div class="col-md-6 filter-item cat1">
                                        <div class="premium-card h-100 p-0 border-0 bg-white">
                                            <div class="blog-img" style="aspect-ratio: 16/9;">
                                                @if($latest->thumbnails)
                                                    <img src="{{ url($latest->thumbnails->url) }}" class="w-100 h-100 object-fit-cover transition-all duration-500 hover:scale-105" />
                                                @else
                                                    <img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="{{$latest->title}}" class="w-100 h-100 object-fit-cover" />
                                                @endif
                                            </div>
                                            <div class="blog-content p-4">
                                                <h3 class="h5 fw-bold mb-3"><a class="text-dark text-decoration-none hover-line"
                                                        href="{{ route('public.page', ['x' => $latest->slug]) }}">{{ \Illuminate\Support\Str::limit($latest->title, 80) }}</a>
                                                </h3>
                                                <p class="text-muted small mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($latest->excerpt), 120) }}</p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="small text-muted"><i class="fal fa-calendar-days me-2"></i>{{ $latest->updated_at->format('d M, Y') }}</span>
                                                    <a href="{{ route('public.page', ['x' => $latest->slug]) }}"
                                                        class="th-btn style2 py-2 px-3 small">Read More<i
                                                            class="fas fa-arrow-up-right ms-2"></i></a>
                                                </div>
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
                                                <div class="row align-items-center mb-4 mt-5">
                                                    <div class="col">
                                                        <h2 class="sec-title show-line fw-bold text-uppercase tracking-wider h3 mb-0">THE Exclusive Truth</h2>
                                                    </div>
                                                </div>
                                                <div class="filter-active">
                                                    <div class="border-blog2 filter-item cat1">
                                                            <div class="premium-card p-0 border-0 bg-white">
                                                                <div class="blog-img" style="aspect-ratio: 16/9;">
                                                                    @if($the_exclusive->thumbnails)
                                                                        <img src="{{ url($the_exclusive->thumbnails->url) }}" class="w-100 h-100 object-fit-cover" />
                                                                    @else
                                                                        <img src="{{ asset('public/img/product/bg-1.jpg') }}"
                                                                            alt="{{ $the_exclusive->title }}" class="w-100 h-100 object-fit-cover" />
                                                                    @endif
                                                                </div>

                                                                <div class="blog-content p-4">
                                                                    <h3 class="h4 fw-bold mb-3">
                                                                        <a href="{{ route('public.page', ['x' => $the_exclusive->slug]) }}"
                                                                            class="text-dark text-decoration-none hover-line">
                                                                            {{ $the_exclusive->title }}
                                                                        </a>
                                                                    </h3>

                                                                    <p class="text-muted small mb-4">
                                                                        {!! \Illuminate\Support\Str::limit(strip_tags($the_exclusive->excerpt), 200) !!}
                                                                    </p>

                                                                    <a href="{{ route('public.page', ['x' => $the_exclusive->slug]) }}"
                                                                        class="th-btn style2 py-2 px-4 shadow-sm">Read More<i
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
                                                <div class="row align-items-center mb-4 mt-5">
                                                    <div class="col">
                                                        <h2 class="sec-title show-line fw-bold text-uppercase tracking-wider h3 mb-0">THE Untold Truth</h2>
                                                    </div>
                                                </div>
                                                <div class="filter-active">
                                                    <div class="border-blog2 filter-item cat1">
                                                        <div class="premium-card p-0 border-0 bg-white">
                                                            <div class="blog-img" style="aspect-ratio: 16/9;">
                                                                @if($the_untold->thumbnails)
                                                                    <img src="{{ url($the_untold->thumbnails->url) }}" class="w-100 h-100 object-fit-cover" />
                                                                @endif
                                                                @if(!empty($the_untold->gallery))
                                                                    @foreach($the_untold->gallery as $e_ut_k => $e_ut_v)
                                                                        @php
                                                                            if ($e_ut_k > 0)
                                                                                break;
                                                                            $e_ut_image = getAttachmentById($e_ut_v->media_id);
                                                                        @endphp
                                                                        @if(!empty($e_ut_image->url))
                                                                            <img src="{{ url($e_ut_image->url) }}" alt="{{ $the_untold->title }}" class="w-100 h-100 object-fit-cover" />
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>

                                                            <div class="blog-content p-4">
                                                                <h3 class="h4 fw-bold mb-3">
                                                                    <a href="{{ route('public.page', ['x' => $the_untold->slug]) }}"
                                                                        class="text-dark text-decoration-none hover-line">
                                                                        {{ $the_untold->title }}
                                                                    </a>
                                                                </h3>

                                                                <p class="text-muted small mb-4">
                                                                    {!! \Illuminate\Support\Str::limit(strip_tags($the_untold->excerpt), 200) !!}
                                                                </p>

                                                                <a href="{{ route('public.page', ['x' => $the_untold->slug]) }}"
                                                                    class="th-btn style2 py-2 px-4 shadow-sm">Read More<i
                                                                        class="fas fa-arrow-up-right ms-2"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        @endif
                        <!-- End : THE Untold Truth -->


                        <!-- Start : Yours Truly -->
                        @if(!empty($others['Your Truth']) && count($others['Your Truth']) > 0)
                            <div class="row align-items-center mb-4 mt-5">
                                <div class="col">
                                    <h2 class="sec-title show-line fw-bold text-uppercase tracking-wider h3 mb-0">Yours Truly</h2>
                                </div>
                            </div>
                            <div class="filter-active row g-4">
                                @foreach($others['Your Truth'] as $the_data)
                                    @if(!empty($the_data))
                                        <div class="col-md-6 filter-item cat1">
                                            <div class="premium-card h-100 p-0 border-0 bg-white">
                                                <div class="blog-img mb-0" style="aspect-ratio: 16/9;">
                                                    @if($the_data->thumbnails)
                                                        <img src="{{ url($the_data->thumbnails->url) }}" class="w-100 h-100 object-fit-cover transition-all duration-500 hover:scale-105" />
                                                    @else
                                                        <img src="{{ asset('public/img/product/bg-1.jpg') }}"
                                                            alt="{{ $the_data->title }}" class="w-100 h-100 object-fit-cover" />
                                                    @endif
                                                </div>

                                                <div class="blog-content p-4">
                                                    <h3 class="h5 fw-bold mb-3">
                                                        <a href="{{ route('public.page', ['x' => $the_data->slug]) }}"
                                                            class="text-dark text-decoration-none hover-line">
                                                            {{ $the_data->title }}
                                                        </a>
                                                    </h3>

                                                    <div class="text-muted small mb-4 excerpt-truncate">
                                                        {!! \Illuminate\Support\Str::limit(strip_tags($the_data->excerpt), 120) !!}
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mt-auto">
                                                        <span class="small text-muted"><i class="fal fa-calendar-days me-2"></i>{{ $the_data->updated_at->format('d M, Y') }}</span>
                                                        <a href="{{ route('public.page', ['x' => $the_data->slug]) }}"
                                                            class="th-btn style2 py-2 px-3 small">Read More<i
                                                                class="fas fa-arrow-up-right ms-2"></i></a>
                                                    </div>
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
                            <div class="nav tab-menu indicator-active bg-light p-2 rounded-3 mb-4" role="tablist">
                                @if(!empty($others['POLITICS']) && count($others['POLITICS']) > 0)
                                    <button class="tab-btn active border-0 bg-transparent fw-bold text-uppercase small tracking-wider py-2 px-3 transition-all" id="nav3-one-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav3-one" type="button" role="tab" aria-controls="nav3-one"
                                        aria-selected="true">Politics</button>
                                @endif
                                @if(!empty($others['WORLD']) && count($others['WORLD']) > 0)
                                    <button class="tab-btn border-0 bg-transparent fw-bold text-uppercase small tracking-wider py-2 px-3 transition-all" id="nav3-two-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav3-two" type="button" role="tab" aria-controls="nav3-two"
                                        aria-selected="false">World</button>
                                @endif
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="nav3-one" role="tabpanel"
                                    aria-labelledby="nav3-one-tab">
                                    <div class="d-flex flex-column gap-4">
                                        @foreach($others['POLITICS'] as $the_data)
                                            <div class="blog-content pb-3 border-bottom border-light">
                                                <h3 class="h6 fw-bold mb-2">
                                                    <a class="text-dark text-decoration-none hover-line"
                                                         href="{{ route('public.page', ['x' => $the_data->slug]) }}">{{ \Illuminate\Support\Str::limit($the_data->title, 65) }}</a>
                                                </h3>
                                                <div class="text-muted x-small d-flex align-items-center">
                                                    <i class="fal fa-calendar-days me-2"></i>{{ $the_data->updated_at->format('d M, Y') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav3-two" role="tabpanel" aria-labelledby="nav3-two-tab">
                                    <div class="d-flex flex-column gap-4">
                                        @foreach($others['WORLD'] as $the_data)
                                            <div class="blog-content pb-3 border-bottom border-light">
                                                <h3 class="h6 fw-bold mb-2">
                                                    <a class="text-dark text-decoration-none hover-line"
                                                         href="{{ route('public.page', ['x' => $the_data->slug]) }}">{{ \Illuminate\Support\Str::limit($the_data->title, 65) }}</a>
                                                </h3>
                                                <div class="text-muted x-small d-flex align-items-center">
                                                    <i class="fal fa-calendar-days me-2"></i>{{ $the_data->updated_at->format('d M, Y') }}
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