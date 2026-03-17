<!doctype html>
<html class="no-js" lang="zxx">
@include('layouts.stylesheets_v1')
<body class="{{ $body_classes }}">
    @include('layouts.header_v1')
    <section class="th-blog-area space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <h2 class="sec-title has-line pt-5">Search Results for: {{ $search_query }}</h2>
                    <div class="filter-active">
                        @if( !empty( $posts ) && count( $posts ) > 0 )
                            @foreach( $posts as $post )
                                <div class="border-blog2 filter-item cat1">
                                    <div class="blog-style4">
                                        <div class="blog-img">
                                            @if( $post->thumbnails)
                                                <img src="{{ url($post->thumbnails->url) }}" />
                                            @else
                                                <img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
                                            @endif
                                        </div>
                                        <div class="blog-content">
                                            <h3 class="box-title-24"><a class="hover-line" href="{{ route('public.page', ['x' => $post->slug])}}">{{ substr($post->title,0,100) }}...</a></h3>
                                            <p class="blog-text">{!! substr(strip_tags($post->description), 0, 150) !!}...</p>
                                            <div class="blog-meta">
                                                <a href="javascript:void(0);"><i class="fal fa-calendar-days"></i>{{ $post->updated_at->format('d M, Y') }}</a>
                                            </div>
                                            <a href="{{ route('public.page', ['x' => $post->slug]) }}" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="th-pagination mt-4">
                                {{ $posts->appends(['q' => $search_query])->links() }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                <p>No results found for "{{ $search_query }}". Try different keywords.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-3 mt-35 mt-xl-0 mb-10 sidebar-wrap">
                    <div class="sidebar-area">
                        <div class="widget widget_categories">
                            <h3 class="widget_title">Categories</h3>
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
