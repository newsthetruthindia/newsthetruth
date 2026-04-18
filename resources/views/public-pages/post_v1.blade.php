<!doctype html>
<html class="no-js" data-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $the_post->title }} - {{env('MAIL_FROM_NAME')}}</title>
    <meta name="author" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="description" content="{{ $the_post->title }}">
    <meta name="keywords" content="{{ $the_post->title }}">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    @include('layouts.stylesheets_v1')
    <style>
        /* NTT NEWSROOM 2.0 - PC OPTIMIZED STYLES */
        :root {
            --ntt-primary: #8c0000;
            --ntt-secondary: #ff1a1a;
        }
        
        /* 1. Progress Bar */
        #ntt-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--ntt-primary), var(--ntt-secondary));
            z-index: 10000;
            transition: width 0.1s ease-out;
            box-shadow: 0 0 10px rgba(140, 0, 0, 0.4);
        }

        /* 2. PC-Only Floating Share */
        @media (min-width: 1200px) {
            .ntt-floating-share {
                position: fixed;
                left: 40px;
                top: 50%;
                transform: translateY(-50%);
                display: flex !important;
                flex-direction: column;
                gap: 15px;
                z-index: 90;
                animation: slideInLeft 0.8s cubic-bezier(0.23, 1, 0.32, 1);
            }
        }
        
        .ntt-floating-share { display: none; }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translate(-20px, -50%); }
            to { opacity: 1; transform: translate(0, -50%); }
        }

        /* 3. Hero Header Enhancements */
        .ntt-hero-header {
            position: relative;
            padding: 80px 0 60px;
            background: #ffffff;
            overflow: hidden;
        }
        
        .ntt-hero-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 800px;
            background: radial-gradient(circle at center, rgba(140, 0, 0, 0.04) 0%, transparent 70%);
            pointer-events: none;
        }

        /* 4. "The Gist" (Summary Box) */
        .ntt-gist-box {
            background: #f8fafc;
            border-left: 2px solid var(--ntt-primary);
            padding: 24px 30px;
            border-radius: 4px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }
        
        .ntt-gist-box::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at top right, rgba(140, 0, 0, 0.05) 0%, transparent 70%);
        }
        
        .ntt-gist-title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--ntt-primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* 5. "Up Next" Smart Peek */
        #ntt-up-next {
            position: fixed;
            bottom: -200px;
            right: 30px;
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: bottom 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        #ntt-up-next.visible { bottom: 30px; }

        .ntt-up-next-badge {
            font-size: 9px;
            font-weight: 900;
            color: white;
            background: var(--ntt-primary);
            padding: 2px 10px;
            border-radius: 100px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
</head>
<body class="bg-white">
    <div id="ntt-progress-bar"></div>
    
    <!-- PC Floating Share (Hidden on Mobile) -->
    <div class="ntt-floating-share">
        @foreach(['facebook', 'twitter', 'linkedin', 'whatsapp'] as $platform)
            @php
                $share_link = '';
                $icon = 'fab fa-'.$platform;
                if($platform == 'facebook') $share_link = "https://www.facebook.com/sharer/sharer.php?u=".url($the_post->slug);
                if($platform == 'twitter') $share_link = "https://twitter.com/intent/tweet?url=".url($the_post->slug);
                if($platform == 'linkedin') $share_link = "https://www.linkedin.com/shareArticle?url=".url($the_post->slug);
                if($platform == 'whatsapp') $share_link = "https://api.whatsapp.com/send?text=".url($the_post->slug);
            @endphp
            <a href="{{ $share_link }}" target="_blank" class="w-12 h-12 d-flex align-items-center justify-content-center rounded-circle bg-white shadow-md text-dark hover:bg-primary hover:text-white transition-all border border-light" title="Share on {{ ucfirst($platform) }}">
                <i class="{{ $icon }} fa-lg"></i>
            </a>
        @endforeach
    </div>
    @include('layouts.header_v1')
    <div class="breadcumb-wrapper py-4 bg-light border-bottom border-light">
        <div class="container-fluid px-lg-5">
            <ul class="breadcumb-menu list-unstyled d-flex align-items-center gap-2 mb-0 x-small text-uppercase tracking-wider">
                <li><a href="{{route('v1.home')}}" class="text-muted text-decoration-none hover:text-primary transition-colors">Home</a></li>
                <li class="text-muted opacity-50"><i class="far fa-chevron-right"></i></li>
                <li class="text-dark fw-bold">{{ \Illuminate\Support\Str::limit($the_post->title, 50) }}</li>
            </ul>
        </div>
    </div>
    <section class="th-blog-wrapper blog-details bg-smoke space-extra-bottom">
        <div class="container">
            <div class="blog-style-bg">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="th-blog blog-single bg-white p-4 p-md-5 rounded-4 shadow-sm mb-5">
                            <div class="mb-4">
                                @if( !empty( $the_post->tags ) && count( $the_post->tags ) > 0 )
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach( $the_post->tags as $tag )
                                            @if( !empty( $tag->tag_data ) )
                                                <a href="{{ route('public.page', ['x' => $tag->tag_data->slug]) }}" class="badge bg-primary/10 text-primary border border-primary/20 px-3 py-2 text-uppercase tracking-widest x-small text-decoration-none hover:bg-primary hover:text-white transition-all">
                                                    {{ $tag->tag_data->title }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <h1 class="h1 fw-black text-dark lh-tight mb-4" style="font-family: var(--ntt-font-heading); font-size: clamp(2rem, 5vw, 3.5rem); letter-spacing: -0.03em;">{{ $the_post->title }}</h1>
                                
                                <div class="blog-meta d-flex flex-wrap align-items-center gap-4 text-muted small pb-4 mb-4 border-bottom border-light">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ !empty($the_post->user->details->media->url) ? url($the_post->user->details->media->url) : 'https://ui-avatars.com/api/?name='.urlencode($the_post->user->firstname).'&background=random' }}" class="w-10 h-10 rounded-circle object-fit-cover border border-light shadow-sm" alt="Author">
                                        <div class="d-flex flex-column">
                                            <span class="x-small text-uppercase tracking-widest opacity-50">Reported By</span>
                                            <span class="fw-bold text-dark">{{ !empty($meta['credit']) ? $meta['credit'] : (!empty( $the_post->user ) ? $the_post->user->firstname.' '.$the_post->user->lastname:'Staff Reporter')}}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 border-start ps-4 border-light">
                                        <div class="d-flex flex-column">
                                            <span class="x-small text-uppercase tracking-widest opacity-50">Published On</span>
                                            <span class="fw-bold text-dark">{{ date('M d, Y', strtotime($the_post->created_at.'+330 minutes') ) }}</span>
                                        </div>
                                    </div>
                                    <div class="ms-auto d-md-flex d-none align-items-center gap-2 bg-light px-3 py-2 rounded-pill">
                                        <i class="fal fa-clock text-primary"></i>
                                        <span class="fw-bold text-dark x-small text-uppercase">5 Min Read</span>
                                    </div>
                                </div>

                                <!-- THE GIST (EXECUTIVE SUMMARY) -->
                                <div class="ntt-gist-box">
                                    <div class="ntt-gist-title">
                                        <i class="fas fa-bolt"></i> The Gist
                                    </div>
                                    <div class="text-dark fw-medium lh-relaxed" style="font-size: 1.1rem; opacity: 0.9;">
                                        {!! !empty($meta['excerpt']) ? $meta['excerpt'] : \Illuminate\Support\Str::limit(strip_tags($the_post->description), 200) !!}
                                    </div>
                                </div>
                            </div>
                            @if(!empty($the_post->thumbnails))
                            <div class="thumbnails mb-5">
                                <div class="blog-img rounded-4 overflow-hidden shadow-md">
                                    <img
                                        src="{{ url($the_post->thumbnails->url) }}"
                                        srcset="{{ get_image_srcset($the_post->thumbnails->id) }}"
                                        class="w-100 h-auto" />
                                    
                                    @if(!empty($meta['pic_credit']) || !empty($meta['img_meta']))
                                        <div class="p-3 bg-light/80 backdrop-blur-sm small text-muted italic">
                                            @if(!empty($meta['img_meta']))
                                                <div class="mb-1">{{ $meta['img_meta'] }}</div>
                                            @endif
                                            @if(!empty($meta['pic_credit']))
                                                <div class="x-small text-uppercase tracking-widest opacity-75">Photo: {{ $meta['pic_credit'] }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if( !empty( $the_post->audio_clip_url ) )

                            <div class="blog-audio">
                                <audio controls>
                                    <source src="{{  $the_post->audio_clip_url  }}" type="audio/mpeg">
                                </audio>
                            </div>
                            @endif
                            <div class="blog-content-wrap">
                                @if(!empty($settings['enable_social_share']) && $settings['enable_social_share'] == '1')
                                <div class="share-links-wrap mb-5 p-3 bg-light rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="share-links d-flex align-items-center gap-3">
                                        <span class="fw-bold text-uppercase small tracking-wider text-muted">Share:</span>

                                        <div class="multi-social d-flex gap-2">
                                            @foreach(['facebook', 'twitter', 'linkedin', 'whatsapp', 'telegram'] as $platform)
                                                @php
                                                    $share_link = '';
                                                    $icon = 'fab fa-'.$platform;
                                                    if($platform == 'facebook') $share_link = "https://www.facebook.com/sharer/sharer.php?u=".url($the_post->slug);
                                                    if($platform == 'twitter') $share_link = "https://twitter.com/intent/tweet?text=".urlencode($the_post->title)."&url=".url($the_post->slug);
                                                    if($platform == 'linkedin') $share_link = "https://www.linkedin.com/shareArticle?mini=true&url=".url($the_post->slug);
                                                    if($platform == 'whatsapp') $share_link = "https://api.whatsapp.com/send?text=".url($the_post->slug);
                                                    if($platform == 'telegram') $share_link = "https://telegram.me/share/url?url=".url($the_post->slug);
                                                @endphp
                                                <a href="{{ $share_link }}" target="_blank" class="w-9 h-9 d-flex align-items-center justify-content-center rounded-circle bg-white text-dark hover:bg-primary hover:text-white transition-all shadow-sm border border-light">
                                                    <i class="{{ $icon }}"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <button id="ntt_share" data-url="{{ url($the_post->slug) }}" class="th-btn style3 py-2 px-3 small rounded-pill">
                                        <i class="fas fa-copy me-2"></i>Copy Link
                                    </button>
                                </div>
                                @endif

                                <div class="blog-content">
                                    <!-- <div class="blog-info-wrap">
                                        <button class="blog-info print_btn">Print : <i class="fas fa-print"></i></button> <a class="blog-info" href="mailto:">Email : <i class="fas fa-envelope"></i> </a>
                                        <button class="blog-info ms-sm-auto">15k <i class="fas fa-thumbs-up"></i></button> <span class="blog-info">126k <i class="fas fa-eye"></i></span> <span class="blog-info">12k <i class="fas fa-share-nodes"></i></span>
                                    </div> -->
                                    <div class="content">
                                        {!! $the_post->description !!}
                                    </div>
                                    <!-- <div class="blog-tag">
                                        <h6 class="title">Related Tag :</h6>
                                        <div class="tagcloud"><a href="blog.html">Sports</a> <a href="blog.html">Politics</a> <a href="blog.html">Business</a></div>
                                    </div> -->
                                </div>
                            </div>
                            <!-- <div class="blog-navigation">
                                <div class="nav-btn prev">
                                    <div class="img"><img src="assets/img/blog/blog-nav-1.jpg" alt="blog img" class="nav-img"></div>
                                    <div class="media-body">
                                        <h5 class="title"><a class="hover-line" href="blog-details.html">Game on! Embrace the spirit of sportsmanship</a></h5><a href="blog-details.html" class="nav-text"><i class="fas fa-arrow-left me-2"></i>Prev</a>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="nav-btn next">
                                    <div class="media-body">
                                        <h5 class="title"><a class="hover-line" href="blog-details.html">Push your limits, redefine what's possible</a></h5><a href="blog-details.html" class="nav-text">Next<i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                    <div class="img"><img src="assets/img/blog/blog-nav-2.jpg" alt="blog img" class="nav-img"></div>
                                </div>
                            </div> -->
                            <!-- <div class="blog-author">
                                <div class="auhtor-img"><img src="assets/img/blog/blog-author.jpg" alt="Blog Author Image"></div>
                                <div class="media-body">
                                    <div class="author-top">
                                        <div>
                                            <h3 class="author-name"><a class="text-inherit" href="team-details.html">Ronald Richards</a></h3><span class="author-desig">Founder & CEO</span>
                                        </div>
                                        <div class="social-links"><a href="https://facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a> <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a> <a href="https://linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a> <a href="https://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></div>
                                    </div>
                                    <p class="author-text">Adventurer and passionate travel blogger. With a backpack full of stories and a camera in hand, she takes her readers on exhilarating journeys around the world.</p>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-lg-4 sidebar-wrap recent-post-sidebar">
                            <div class="sidebar-area">
                                <h3 class="widget_title text-uppercase tracking-widest small fw-bold mb-4">The Latest</h3>
                                <div class="recent-post-wrap d-flex flex-column gap-4">
                                    @foreach( $the_latest as $key => $latest )
                                    <div class="recent-post d-flex align-items-center gap-3 pb-3 border-bottom border-light">
                                        <div class="media-img w-20 h-20 flex-shrink-0 rounded-3 overflow-hidden">
                                            <a href="{{ route('public.page', ['x' => $latest->slug]) }}">
                                                @if( $latest->thumbnails)
                                                <img src="{{ url($latest->thumbnails->url) }}" class="w-100 h-100 object-fit-cover" srcset="{{ get_image_srcset($latest->thumbnails->id)}}">
                                                @else
                                                <img src="{{ asset('public/v1/img/blog/blog_5_2_4.jpg') }}" alt="" class="w-100 h-100 object-fit-cover" />
                                                @endif
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="h6 fw-bold mb-2">
                                                <a class="text-dark text-decoration-none hover-line" href="{{ route('public.page', ['x' => $latest->slug]) }}">
                                                    {{ \Illuminate\Support\Str::limit($latest->title, 60) }}
                                                </a>
                                            </h4>
                                            <div class="text-muted x-small d-flex align-items-center">
                                                <i class="fal fa-calendar-days me-2"></i>{{ $latest->updated_at->format('d M, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="widget">
                                <div class="widget-ads">
                                    <!-- <a href="javascript:void(0);"><img class="w-100" src="assets/img/ads/siderbar_ads_1.jpg" alt="ads"></a> -->
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 16-STORY SMART DISCOVERY GRID                               --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @php
                $similars_arr  = is_array($similars)    ? $similars    : (method_exists($similars, 'all')    ? $similars->all()    : []);
                $latest_arr    = is_array($the_latest)  ? $the_latest  : (method_exists($the_latest, 'all') ? $the_latest->all()  : []);
                $all_related   = array_slice($similars_arr, 0, 6);
                $all_trending  = array_slice($latest_arr, 0, 6);
                $all_highlights = array_slice($latest_arr, 6, 4);
                if (count($all_highlights) < 4) $all_highlights = array_slice($latest_arr, 0, 4);
            @endphp

            @if(count($all_related) > 0 || count($all_trending) > 0)
            <div class="ntt-discovery-hub pt-5 mt-5 border-top">

                {{-- SECTION 1: RELATED STORIES --}}
                @if(count($all_related) > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div>
                            <div class="text-uppercase fw-black" style="font-size:10px;letter-spacing:.3em;color:var(--ntt-primary);">Based on this story</div>
                            <h2 class="fw-black text-uppercase mb-0" style="font-size:clamp(1.5rem,3vw,2rem);letter-spacing:-.02em;font-style:italic;">Related Stories</h2>
                        </div>
                        <div class="flex-grow-1 border-bottom opacity-25 ms-3"></div>
                        <span class="badge bg-primary rounded-pill" style="font-size:9px;">{{ count($all_related) }}</span>
                    </div>
                    <div class="row g-3">
                        @foreach($all_related as $p)
                        <div class="col-6 col-md-4 col-xl-2">
                            <a href="{{ route('public.page', ['x' => $p->slug]) }}" class="text-decoration-none d-block h-100">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius:12px;transition:transform .3s,box-shadow .3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                    <div style="aspect-ratio:16/10;overflow:hidden;">
                                        <img src="{{ !empty($p->thumbnails) ? url($p->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_4.jpg') }}" alt="{{ $p->title }}" class="w-100 h-100 object-fit-cover" style="transition:transform .5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform=''">
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text fw-bold text-dark mb-1" style="font-size:.78rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ \Illuminate\Support\Str::limit($p->title, 55) }}</p>
                                        <span class="text-muted" style="font-size:.65rem;">{{ \Carbon\Carbon::parse($p->created_at)->format('d M') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- SECTION 2: VIRAL TRENDING --}}
                @if(count($all_trending) > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div>
                            <div class="text-uppercase fw-black" style="font-size:10px;letter-spacing:.3em;color:var(--ntt-primary);">What people are reading</div>
                            <h2 class="fw-black text-uppercase mb-0" style="font-size:clamp(1.5rem,3vw,2rem);letter-spacing:-.02em;font-style:italic;color:var(--ntt-primary);">Viral Trending</h2>
                        </div>
                        <div class="flex-grow-1 border-bottom opacity-25 ms-3"></div>
                        <span class="badge bg-primary rounded-pill" style="font-size:9px;">{{ count($all_trending) }}</span>
                    </div>
                    <div class="row g-3">
                        @foreach($all_trending as $p)
                        <div class="col-6 col-md-4 col-xl-2">
                            <a href="{{ route('public.page', ['x' => $p->slug]) }}" class="text-decoration-none d-block h-100">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius:12px;transition:transform .3s,box-shadow .3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                    <div style="aspect-ratio:16/10;overflow:hidden;position:relative;">
                                        <img src="{{ !empty($p->thumbnails) ? url($p->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_4.jpg') }}" alt="{{ $p->title }}" class="w-100 h-100 object-fit-cover">
                                        <div style="position:absolute;top:6px;left:6px;background:var(--ntt-primary);color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;letter-spacing:.2em;padding:2px 8px;border-radius:20px;">🔥 Trending</div>
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text fw-bold text-dark mb-1" style="font-size:.78rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ \Illuminate\Support\Str::limit($p->title, 55) }}</p>
                                        <span class="text-muted" style="font-size:.65rem;">{{ \Carbon\Carbon::parse($p->created_at)->format('d M') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- SECTION 3: EDITOR HIGHLIGHTS --}}
                @if(count($all_highlights) > 0)
                <div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div>
                            <div class="text-uppercase fw-black" style="font-size:10px;letter-spacing:.3em;color:var(--ntt-primary);">Selected by NTT Desk</div>
                            <h2 class="fw-black text-uppercase mb-0" style="font-size:clamp(1.5rem,3vw,2rem);letter-spacing:-.02em;font-style:italic;">Editor Highlights</h2>
                        </div>
                        <div class="flex-grow-1 border-bottom opacity-25 ms-3"></div>
                    </div>
                    <div class="row g-3">
                        @foreach($all_highlights as $p)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('public.page', ['x' => $p->slug]) }}" class="text-decoration-none d-block h-100">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius:12px;transition:transform .3s,box-shadow .3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                    <div style="aspect-ratio:16/10;overflow:hidden;">
                                        <img src="{{ !empty($p->thumbnails) ? url($p->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_4.jpg') }}" alt="{{ $p->title }}" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text fw-bold text-dark mb-1" style="font-size:.78rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ \Illuminate\Support\Str::limit($p->title, 55) }}</p>
                                        <span class="text-muted" style="font-size:.65rem;">{{ \Carbon\Carbon::parse($p->created_at)->format('d M') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            @endif

        </div>
    </section>
    <!-- UP NEXT PEEK-A-BOO (PC ONLY) -->
    @if(!empty($similars) && count($similars) > 0)
    @php $upNext = $similars[0]; @endphp
    <div id="ntt-up-next" class="d-none d-xl-block">
        <div class="ntt-up-next-badge">UP NEXT</div>
        <button onclick="document.getElementById('ntt-up-next').classList.remove('visible')" class="position-absolute top-0 end-0 m-2 btn btn-link text-muted p-0">
            <i class="fal fa-times"></i>
        </button>
        <div class="d-flex gap-3 align-items-center">
            <div class="flex-shrink-0 w-24 h-24 rounded-3 overflow-hidden">
                <img src="{{ !empty($upNext->thumbnails->url) ? url($upNext->thumbnails->url) : asset('public/v1/img/blog/blog_5_2_4.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Up Next">
            </div>
            <div class="flex-grow-1">
                <h4 class="h6 fw-bold mb-2 lh-sm">
                    <a href="{{ route('public.page', ['x' => $upNext->slug]) }}" class="text-dark text-decoration-none hover-primary">
                        {{ \Illuminate\Support\Str::limit($upNext->title, 50) }}
                    </a>
                </h4>
                <a href="{{ route('public.page', ['x' => $upNext->slug]) }}" class="text-primary text-uppercase tracking-widest fw-black x-small text-decoration-none">
                    Read Now <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.getElementById('ntt-progress-bar');
            const upNext = document.getElementById('ntt-up-next');
            let upNextTriggered = false;

            window.addEventListener('scroll', () => {
                // 1. Progress Bar Logic
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                if (progressBar) progressBar.style.width = scrolled + "%";

                // 2. Up Next Peek-a-boo Trigger (at 70% scroll)
                if (upNext && scrolled > 70 && !upNextTriggered) {
                    upNext.classList.add('visible');
                    upNextTriggered = true;
                }
            });
        });
    </script>
</body>

</html>