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
</head>
<body>
    @include('layouts.header_v1')
    <div class="breadcumb-wrapper">
        <div class="container">
            <ul class="breadcumb-menu">
                <li><a href="{{route('v1.home')}}">Home</a></li>
                <li>{{ $the_post->title }}</li>
            </ul>
        </div>
    </div>
    <section class="th-blog-wrapper blog-details bg-smoke space-extra-bottom">
        <div class="container">
            <div class="blog-style-bg">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="th-blog blog-single">

                            @if( !empty( $the_post->tags ) && count( $the_post->tags ) > 0 )
                            @foreach( $the_post->tags as $tag )
                            @if( !empty( $tag->tag_data ) )
                            <a data-theme-color="#4E4BD0" href="{{ route('public.page', ['x' => $tag->tag_data->slug]) }}" class="category">{{ $tag->tag_data->title }}</a>
                            @endif
                            @endforeach
                            @endif
                            <h1 class="blog-title">{{ $the_post->title }}</h1>
                            <div class="blog-meta">
                                <a class="author" href="javascript:void(0);">
                                    <i class="far fa-user"></i>{{ !empty($meta['credit']) ? $meta['credit'] : (!empty( $the_post->user ) ? $the_post->user->firstname.' '.$the_post->user->lastname:'Staff Reporter')}}</a>
                                <a href="javascript:void(0);"><i class="fal fa-calendar-days"></i>
                                    <?php date_default_timezone_set('Asia/Kolkata'); ?>

                                    {{ date('M d, Y h:i a', strtotime($the_post->created_at.'+330 minutes') ) }}</a>
                            </div>
                            @if(!empty($the_post->thumbnails))
                            <div class="row thumbnails">
                                <div class="col-lg-12">
                                    <div class="blog-img">
                                        <img
                                            src="{{ url($the_post->thumbnails->url) }}"
                                            srcset="{{ get_image_srcset($the_post->thumbnails->id) }}" />
                                    </div>


                                    @if(!empty($meta['pic_credit']))
                                    <div class="pic_credit">
                                        <p>Photo Credit</p>
                                        <p>{{ $meta['pic_credit'] }}</p>
                                    </div>
                                    @endif
                                </div>

                                @if(!empty($meta['img_meta']))
                                <div class="col-lg-12 img_meta">
                                    <p><i>{{ $meta['img_meta'] }}</i></p>
                                </div>
                                @endif
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
                                <div class="share-links-wrap">
                                    <div class="share-links">
                                        <span class="share-links-title">Share Post:</span>

                                        <div class="multi-social">

                                            @if(!empty($settings['share_facebook']) && $settings['share_facebook'] == '1' && !empty($the_post->thumbnails->url))
                                            <a
                                                href="https://www.facebook.com/sharer/sharer.php?u={{ url($the_post->slug) }}&t={{ $the_post->title }}&p[images][0]={{ url($the_post->thumbnails->url) }}"
                                                target="_blank"
                                                title="Facebook">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            @endif

                                            @if(!empty($settings['share_twitter']) && $settings['share_twitter'] == '1')
                                            <a
                                                href="https://twitter.com/intent/tweet?text={{ $the_post->title }}&url={{ url($the_post->slug) }}&via=NewsTheTruth"
                                                target="_blank"
                                                title="Twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                            @endif

                                            @if(!empty($settings['share_linkdin']) && $settings['share_linkdin'] == '1')
                                            <a
                                                href="https://www.linkedin.com/shareArticle?mini=true&url={{ url($the_post->slug) }}&title={{ $the_post->title }}"
                                                target="_blank"
                                                title="LinkedIn">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                            @endif

                                            @if(!empty($settings['share_pinterrest']) && $settings['share_pinterrest'] == '1' && !empty($the_post->thumbnails->url))
                                            <a
                                                href="https://pinterest.com/pin/create/button/?url={{ url($the_post->slug) }}&media={{ url($the_post->thumbnails->url) }}&description={{ $the_post->title }}"
                                                target="_blank"
                                                title="Pinterest">
                                                <i class="fab fa-pinterest-p"></i>
                                            </a>
                                            @endif

                                            @if(!empty($settings['share_tumbler']) && $settings['share_tumbler'] == '1')
                                            <a
                                                href="https://www.tumblr.com/share/link?url={{ url($the_post->slug) }}&name={{ $the_post->title }}&description={{ $the_post->description }}"
                                                target="_blank"
                                                title="Tumblr">
                                                <i class="fab fa-tumblr"></i>
                                            </a>
                                            @endif

                                            @if(!empty($settings['share_whatsapp']) && $settings['share_whatsapp'] == '1')
                                            <a
                                                href="https://web.whatsapp.com/send?text={{ url($the_post->slug) }}"
                                                target="_blank"
                                                title="WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            @endif

                                            @if(!empty($settings['share_telegram']) && $settings['share_telegram'] == '1')
                                            <a
                                                href="https://telegram.me/share/url?url={{ url($the_post->slug) }}&text={{ $the_post->title }}"
                                                target="_blank"
                                                title="Telegram">
                                                <i class="fab fa-telegram-plane"></i>
                                            </a>
                                            @endif

                                            {{-- Generic Share Button --}}
                                            <a
                                                href="#"
                                                id="ntt_share"
                                                data-url="{{ url($the_post->slug) }}"
                                                title="Share">
                                                <i class="fas fa-share-alt"></i>
                                            </a>

                                        </div>
                                    </div>
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
                        <aside class="sidebar-area">
                            <!-- <div class="widget widget_search">
                                <form class="search-form">
                                    <input type="text" placeholder="Enter Keyword">
                                    <button type="submit"><i class="far fa-search"></i></button>
                                </form>
                            </div> -->
                            <div class="widget">
                                <h3 class="widget_title">Recent Posts</h3>
                                <div class="recent-post-wrap">
                                    @foreach( $the_latest as $key => $latest )
                                    <div class="recent-post mb-3">
                                        <div class="media-img">
                                            <a href="{{ route('public.page', ['x' => $latest->slug]) }}">
                                                @if( $latest->thumbnails)
                                                <img src="{{ url($latest->thumbnails->url) }}" srcset="{{ get_image_srcset($latest->thumbnails->id)}}">
                                                @else
                                                <img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
                                                @endif
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="post-title"><a class="hover-line" href="{{ route('public.page', ['x' => $latest->slug]) }}">{{ substr($latest->title,0,70); }}...</a></h4>
                                            <div class="recent-post-meta"><a href="javascript:void(0);"><i class="fal fa-calendar-days"></i>{{ $latest->updated_at->format('d M, Y') }}</a></div>
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
            @if(!empty($similars) && count($similars) > 0)
            <div class="related-post-wrapper pt-30 mb-30">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="sec-title has-line">Related Post</h2>
                    </div>
                    <div class="col-auto">
                        <div class="sec-btn">
                            <div class="icon-box">
                                <button data-slick-prev="#related-post-slide" class="slick-arrow default">
                                    <i class="far fa-arrow-left"></i>
                                </button>
                                <button data-slick-next="#related-post-slide" class="slick-arrow default">
                                    <i class="far fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row tslider-shadow th-carousel"
                        id="related-post-slide"
                        data-slide-show="4"
                        data-lg-slide-show="3"
                        data-md-slide-show="2"
                        data-sm-slide-show="2"
                        data-xs-slide-show="2">
                    @foreach($similars as $latest)
                    <div class="col-sm-6 col-xl-4">
                        <div class="blog-style1">
                            <div class="blog-img">
                                <a href="{{ route('public.page', ['x' => $latest->slug]) }}">
                                    @if($latest->thumbnails)
                                    <img
                                        src="{{ url($latest->thumbnails->url) }}"
                                        srcset="{{ get_image_srcset($latest->thumbnails->id) }}"
                                        alt="{{ $latest->title }}" />
                                    @else
                                    <img
                                        src="{{ asset('public/img/product/bg-1.jpg') }}"
                                        alt="{{ $latest->title }}" />
                                    @endif
                                </a>
                            </div>

                            <h3 class="box-title-22 post-title-1">
                                <a class="hover-line" href="{{ route('public.page', ['x' => $latest->slug]) }}">
                                    {{ \Illuminate\Support\Str::limit($latest->title, 45) }}
                                </a>
                            </h3>

                            <div class="blog-meta">
                                
                                <a href="{{ route('public.page', ['x' => $latest->slug]) }}">
                                    <i class="fal fa-calendar-days"></i>
                                    {{ \Carbon\Carbon::parse($latest->post_publish_time)->format('d M, Y') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>
    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')
</body>

</html>