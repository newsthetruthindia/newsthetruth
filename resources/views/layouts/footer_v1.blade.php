
@php
$smenu_id = \App\Http\Controllers\SiteSettingsController::check('secondary_menu');
$smenu = App\Models\Menu::where('id', $smenu_id->description )->first();
@endphp
<footer class="footer-wrapper footer-layout1" data-bg-src="{{ asset('public/v1/img/bg/footer_bg_1.png') }}">
    <div class="widget-area py-4">
        <div class="container">
            <div class="row gy-4">

                {{-- About / Social --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="widget footer-widget mb-0">
                        <div class="th-widget-about text-center text-md-start">

                            {{-- Logo --}}
                            <div class="about-logo mb-2">
                                <a href="{{ route('v1.home') }}">
                                    <img src="{{ asset('public/v1/img/image_logo.jpg') }}" alt="{{env('MAIL_FROM_NAME')}}" class="img-fluid" style="max-height:50px;">
                                </a>
                            </div>

                            {{-- Description --}}
                            <p class="about-text small mb-3">Welcome to News The Truth (NTT), an online English news portal at the intersection of authentic storytelling and informed citizenry. Rooted in our deep commitment to amplifying voices often overlooked, we're not just a news outlet; we're a platform for ordinary people to share stories that directly impact their lives.</p>
                            
                            @if(!empty($settings['enable_social_follow']) && $settings['enable_social_follow'] == '1')
                            
                            {{-- Social Icons --}}
                            <div class="th-social style-black d-flex justify-content-center justify-content-md-start gap-2">
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

                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title">Categories</h3>
                        <div class="menu-all-pages-container">
                            <ul class="menu">
                                @if( !empty( $smenu->items ) )
                                @foreach( $smenu->items as $k =>$v )
                                <li>
                                    <a href="{{ $v->slug }}">
                                        {{ $v->display_name }}
                                    </a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title">Quick Link</h3>
                        <div class="menu-all-pages-container">
                            <ul class="menu">
                                @if( !empty( \App\Http\Controllers\SiteSettingsController::check('footer_menu') ) )
                                    @php
                                    $fmenu_id = \App\Http\Controllers\SiteSettingsController::check('footer_menu');
                                    $fmenu = App\Models\Menu::where('id', $fmenu_id->description )->first();
                                    @endphp
                                    @if( !empty( $fmenu->items ) )
                                    @foreach( $fmenu->items as $k =>$v )
                                    <li><a href="{{ $v->only_slug }}">{{ $v->display_name }}</a></li>
                                    @endforeach
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="widget widget_tag_cloud footer-widget">
                        <h3 class="widget_title">Popular Tags</h3>
                        <div class="tagcloud">
                            @php
                            $footer_tags = footer_tag($limit=10);
                            @endphp
                            @foreach($footer_tags as $footer_tag)
                            <a href="{{ url( $footer_tag->slug ) }}">{{$footer_tag->title}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="copyright-wrap py-3">
        <div class="container">
            <div class="row align-items-center gy-2 text-center text-lg-start">

                <div class="col-12">
                    <p class="copyright-text mb-0 small text-center">
                        © <?= date('Y') ?> <a href="{{ route('v1.home') }}">Newsthetruth</a>. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
{{-- Scroll to top --}}
<div class="scroll-top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
</div>