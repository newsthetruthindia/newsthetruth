
@php
$smenu_id = \App\Http\Controllers\SiteSettingsController::check('secondary_menu');
$smenu = App\Models\Menu::where('id', $smenu_id->description )->first();
@endphp
<footer class="footer-wrapper footer-layout1 bg-dark text-white py-5" style="background-color: var(--ntt-dark) !important;">
    <div class="widget-area">
        <div class="container-fluid px-lg-5">
            <div class="row gy-5">
                {{-- About / Social --}}
                <div class="col-lg-4">
                    <div class="widget footer-widget">
                        <div class="th-widget-about">
                            <div class="about-logo mb-4">
                                <a href="{{ route('v1.home') }}">
                                    <img src="{{ asset('public/v1/img/image_logo.jpg') }}" alt="{{env('MAIL_FROM_NAME')}}" class="img-fluid rounded-2 grayscale brightness-200" style="max-height:60px;">
                                </a>
                            </div>
                            <p class="about-text text-white/60 lh-lg mb-4">
                                Welcome to News The Truth (NTT), an online English news portal at the intersection of authentic storytelling and informed citizenry. We amplify voices often overlooked, providing a platform for ordinary people to share stories that impact their lives.
                            </p>
                            @if(!empty($settings['enable_social_follow']) && $settings['enable_social_follow'] == '1')
                                <div class="th-social d-flex gap-3">
                                    @foreach(['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'telegram'] as $platform)
                                        @if(!empty($settings['follow_'.$platform]))
                                            <a href="{{ $settings['follow_'.$platform] }}" target="_blank" class="w-10 h-10 d-flex align-items-center justify-content-center rounded-circle bg-white/10 hover:bg-primary transition-all text-white text-decoration-none">
                                                <i class="fab fa-{{ $platform == 'linkdin' ? 'linkedin-in' : $platform }}"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="col-lg-2 offset-lg-1">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title text-white fw-bold text-uppercase tracking-widest small mb-4">Categories</h3>
                        <ul class="menu list-unstyled">
                            @if( !empty( $smenu->items ) )
                                @foreach( $smenu->items as $v )
                                    <li class="mb-2">
                                        <a href="{{ $v->slug }}" class="text-white/60 text-decoration-none hover:text-primary transition-colors small">{{ $v->display_name }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-lg-2">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title text-white fw-bold text-uppercase tracking-widest small mb-4">Resources</h3>
                        <ul class="menu list-unstyled">
                            @if( !empty( $fmenu->items ) )
                                @foreach( $fmenu->items as $v )
                                    <li class="mb-2">
                                        <a href="{{ $v->only_slug }}" class="text-white/60 text-decoration-none hover:text-primary transition-colors small">{{ $v->display_name }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Newsletter / Tags --}}
                <div class="col-lg-3">
                    <div class="widget footer-widget">
                        <h3 class="widget_title text-white fw-bold text-uppercase tracking-widest small mb-4">Latest Tags</h3>
                        <div class="tagcloud d-flex flex-wrap gap-2">
                            @foreach(footer_tag(12) as $tag)
                                <a href="{{ url($tag->slug) }}" class="px-3 py-1 bg-white/5 hover:bg-white/20 transition-all text-white/80 text-decoration-none small rounded-pill border border-white/10">{{ $tag->title }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-wrap border-top border-white/10 mt-5 pt-4">
        <div class="container">
            <p class="copyright-text text-white/40 small text-center mb-0">
                © {{ date('Y') }} <span class="text-white fw-bold">Newsthetruth</span>. All Rights Reserved. Modernized with <i class="fas fa-heart text-primary mx-1"></i>
            </p>
        </div>
    </div>
</footer>
{{-- Scroll to top --}}
<div class="scroll-top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
</div>