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
            <a href="{{route('v1.home')}}"><img src="{{ asset('public/v1/img/image_logo.jpg') }}"
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
        <div class="container">
            <div class="row gx-0">
                <div class="col-lg-2 d-none d-lg-inline-block">
                    <div class="header-logo">
                        <a href="{{route('v1.home')}}"><img src="{{ asset('public/v1/img/image_logo.jpg') }}"
                                alt="{{env('MAIL_FROM_NAME')}}"></a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="header-top">
                        <div class="row align-items-center">
                            <div class="col-xl-9">
                                <div class="news-area">
                                    <div class="title">Breaking News :</div>
                                    <div class="news-wrap">
                                        <div class="row slick-marquee">
                                            @php
                                                $marqueeNews = marquee_news_list($limit = 5);
                                            @endphp
                                            @foreach($marqueeNews as $marq)
                                                <!-- <a href="javascript:void(0);">{{$marq->title}}</a> -->
                                                <div class="col-auto"><a
                                                        href="{{ route('public.page', ['x' => $marq->slug])}}"
                                                        class="breaking-news">{{ substr($marq->title, 0, 100) }}...</a></div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 text-end d-none d-xl-block">
                                <div class="social-links"><span class="social-title">Follow Us :</span>
                                    @if(!empty($settings['follow_facebook']))
                                        <a href="{{ $settings['follow_facebook'] }}" target="_blank" title="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    @endif

                                    @if(!empty($settings['follow_twitter']))
                                        <a href="{{ $settings['follow_twitter'] }}" target="_blank" title="Twitter">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    @endif

                                    @if(!empty($settings['follow_linkdin']))
                                        <a href="{{ $settings['follow_linkdin'] }}" target="_blank" title="LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    @endif

                                    @if(!empty($settings['follow_instagram']))
                                        <a href="{{ $settings['follow_instagram'] }}" target="_blank" title="Instagram">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif

                                    @if(!empty($settings['follow_youtube']))
                                        <a href="{{ $settings['follow_youtube'] }}" target="_blank" title="YouTube">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    @endif

                                    @if(!empty($settings['follow_telegram']))
                                        <a href="{{ $settings['follow_telegram'] }}" target="_blank" title="Telegram">
                                            <i class="fab fa-telegram-plane"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-area">
                        <div class="row align-items-center justify-content-between">
                            <!-- <div class="col-auto d-none d-xl-block">
                                <div class="toggle-icon"><a href="#" class="simple-icon sideMenuToggler"><i class="far fa-bars"></i></a></div>
                            </div> -->
                            <div class="col-auto d-lg-none d-block">
                                <div class="header-logo">
                                    <a href="{{route('v1.home')}}"><img class="light-img"
                                            src="{{ asset('public/v1/img/image_logo.jpg') }}"
                                            alt="{{env('MAIL_FROM_NAME')}}"></a>
                                    <a href="{{route('v1.home')}}"><img class="dark-img"
                                            src="{{ asset('public/v1/img/image_logo.jpg') }}"
                                            alt="{{env('MAIL_FROM_NAME')}}"></a>
                                </div>
                            </div>
                            <div class="col-auto ms-2">
                                <nav class="main-menu d-none d-lg-inline-block">
                                    <ul>
                                        @if(!empty($smenu->items))
                                            @foreach($smenu->items as $k => $v)
                                                <li><a href="{{ $v->slug }}">{{ $v->display_name }}</a></li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-auto">
                                <div class="header-button">
                                    <a href="{{ route('register') }}" class="th-btn style3">Registration</a>
                                    <a href="{{ route('login') }}" class="th-btn style3">Login</a>
                                    <button type="button" class="th-menu-toggle d-block d-lg-none"><i
                                            class="far fa-bars"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>