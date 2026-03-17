@php
    $smenu_id = \App\Http\Controllers\SiteSettingsController::check('secondary_menu');
    $smenu = App\Models\Menu::where('id', $smenu_id->description)->first();
    $smenu_id_mobile = \App\Http\Controllers\SiteSettingsController::check('primary_menu');
    $smenu_mobile = App\Models\Menu::where('id', $smenu_id_mobile->description)->first();
@endphp
<div class="th-menu-wrapper">
    <div class="th-menu-area text-center">
        <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
        <div class="mobile-logo">
            <a href="{{route('v1.home')}}"><img src="{{ asset('v1/img/image_logo.jpg') }}"
                    alt="{{env('MAIL_FROM_NAME')}}"></a>
        </div>
        <div class="th-mobile-menu">
            <ul>
                @if(!empty($smenu_mobile->items))
                    @foreach($smenu_mobile->items as $k => $v)
                        <li><a href="{{ $v->slug }}">{{ $v->display_name }}</a></li>
                    @endforeach
                @endif
                <a href="{{ route('register') }}" class="th-btn style3 mb-3 mt-3">Registration</a>
                <a href="{{ route('login') }}" class="th-btn style3">Login</a>
            </ul>
        </div>
    </div>
</div>
<!-- <div class="switcher-fixed">
    <div class="theme-switcher">
        <button><span class="dark"><i class="fas fa-moon"></i></span> <span class="light"><i class="fas fa-sun-bright"></i></span></button>
    </div>
</div> -->
<header class="th-header header-layout5 dark-theme">
    <div class="sticky-wrapper glass-header">
        <div class="container-fluid px-lg-5">
            <div class="row align-items-center justify-content-between">
                {{-- Logo Area --}}
                <div class="col-auto">
                    <div class="header-logo py-3">
                        <a href="{{route('v1.home')}}">
                            <img src="{{ asset('v1/img/image_logo.jpg') }}" alt="{{env('MAIL_FROM_NAME')}}" style="max-height: 50px; filter: brightness(1.1);">
                        </a>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="col-auto d-none d-lg-block">
                    <nav class="main-menu">
                        <ul class="d-flex align-items-center list-unstyled mb-0">
                            @if(!empty($smenu->items))
                                @foreach($smenu->items as $k => $v)
                                    <li class="mx-3"><a href="{{ $v->slug }}" class="fw-medium text-uppercase small tracking-wider hover-line">{{ $v->display_name }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </nav>
                </div>

                {{-- Actions --}}
                <div class="col-auto">
                    <div class="header-button d-flex align-items-center">
                        {{-- Search Form --}}
                        <form action="{{ route('v1.search') }}" method="GET" class="d-none d-md-flex me-4">
                            <div class="input-group">
                                <input type="text" name="q" placeholder="Search..." class="form-control form-control-sm bg-dark border-secondary text-white" style="width: 150px;">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="far fa-search"></i></button>
                            </div>
                        </form>

                        {{-- Subtle News Ticker for Desktop --}}
                        <div class="d-none d-xl-flex align-items-center me-4 opacity-75 small">
                            <span class="text-primary fw-bold me-2">LATEST:</span>
                            <div class="news-wrap" style="max-width: 250px; overflow: hidden; white-space: nowrap;">
                                @php $marqueeNews = marquee_news_list($limit = 1); @endphp
                                @foreach($marqueeNews as $marq)
                                    <a href="{{ route('public.page', ['x' => $marq->slug])}}" class="text-inherit hover-line">{{ $marq->title }}</a>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ route('register') }}" class="th-btn style3 me-2">Register</a>
                        <a href="{{ route('login') }}" class="th-btn style3">Login</a>
                        <button type="button" class="th-menu-toggle d-block d-lg-none ms-3"><i class="far fa-bars"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>