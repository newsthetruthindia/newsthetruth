@include('public-pages.header', ['body_classes'=>$body_classes])

<section class="home-archive top-stories">
	<div class="container-fluid p-1">
		<div class="row text-center"><h3>Top Stories</h3></div>
		<div class="row">
			<div class="col-md-12">
				@if( !empty( $top_post ) && count( $top_post ) > 0 )
					<div id="carosoul" class="carosoul owl-carousel owl-theme main-slide">
					@foreach( $top_post as $t_p )
						<div class="item" data-hash="hash{{ $t_p->id }}">
							<div class="col-md-7 text-center">
								@if( $t_p->thumbnails)
									<img src="{{ url($t_p->thumbnails->url) }}" />
								@else
									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
								@endif
							</div>
							<div class="col-md-5">
								<h3>
									<a href="{{ url($t_p->slug) }}">
										{{ $t_p->title }}
									</a>
								</h3>
								{{ $t_p->excerpt }}...
								<span class="read-more">
									<a href="{{ url($t_p->slug) }}">
										Read more
									</a>
								</span>
							</div>
						</div>
					@endforeach
					</div>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				@if( !empty( $top_post ) && count( $top_post ) > 0 )
					<div id="second-cor" class="second-cor owl-carousel owl-theme sub-slide">
						@foreach( $top_post as $t_p )
							@if( !empty( $t_p->thumbnails->url ) )
								<a class="item" href="#hash{{ $t_p->id }}">
									<img src="{{ url($t_p->thumbnails->url) }}" alt="" />
								</a>
							@endif
						@endforeach
					</div>
				@endif
			</div>
		</div>
	</div>
</section>
@if( !empty( $the_latest ) && !empty( $the_latest[0] ) )
	<section class="home-archive latest-news">
		<div class="container-fluid news-section just-in">
			<div class="row heading">
				<div class="col-md-12">
					<h2><span>Just</span> In</h2>
				</div>
			</div>
			<div class="row bg-white text-center">
				<div class="col-md-12 title">
				    <h3><a href="{{ url($the_latest[0]->slug) }}">{{ $the_latest[0]->title }}</a></h3>
					{!! $the_latest[0]->excerpt !!}...
					<span class="read-more">
						<a href="{{ url($the_latest[0]->slug) }}">
							Read more
						</a>
					</span>
				</div>
				<div class="col-lg-12 text-center">
					<div class="">
						@if( !empty( $the_latest[0]->thumbnails->url ) )
							<img src="{{ url($the_latest[0]->thumbnails->url) }}" />
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
@endif
<section class="home-archive">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span>THE</span> LATEST</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $the_latest ) && count( $the_latest ) > 0 )
					@foreach( $the_latest as $key => $latest )
						<div class="row article">
							<div class="col-md-1 index"><span>{{ ($key+1) }}.</span></div>
							<div class="col-xs-12 col-md-7 title">
								<h4><a href="{{ url($latest->slug) }}">{{ substr($latest->title,0,100); }}...</a></h4>
								{{ substr($latest->excerpt,0,100); }}...
								<span class="read-more">
									<a href="{{ url($latest->slug) }}">
										Read more
									</a>
								</span>
							</div>
							<div class="col-xs-12 col-md-4 video">
								@if( $latest->thumbnails)
									<img src="{{ url($latest->thumbnails->url) }}" />
								@else
									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
								@endif
							</div>
						</div>
					@endforeach
				@endif
				<div class="row text-center bg-white">
					<a href="{{ url('latest') }}" class="btn btn-tdanger m5">load more...</a>
				</div>
			</div>
			<div class="col-lg-3 add-section">
				<div class="add">
					@if( !empty( \App\Http\Controllers\SiteSettingsController::check('global_add') ) )
                        @php
                            $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('global_add')->description);
                        @endphp
                        @if( !empty( $logo->url ) )
                            <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 100%;"/>
                        @else
                        	<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
                        @endif
                    @else
                        <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
                    @endif
				</div>
			</div>
		</div>
	</div>
</section>
<section class="home-archive">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span>THE</span> Exclusive Truth</h2>
			</div>
		</div>
		@if( !empty( $the_exclusive ) )
			<div class="row bg-white single-stack">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="short-article">
							<h4>
								<a href="{{ url($the_exclusive->slug) }}">{{ $the_exclusive->title }}</a>
							</h4>
								{!! $the_exclusive->excerpt !!}...
								<br>
								<span class="read-more">
								<a href="{{ url($the_exclusive->slug) }}"><span>Read more</span></a></span>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 text-center">
						@if( $the_exclusive->thumbnails)
							<img src="{{ url($the_exclusive->thumbnails->url) }}" />
						@else
							<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
						@endif
					</div>
				</div>
				@if( !empty( $the_exclusive->gallery ) )
					<div class="row photo-story text-center">
						<div class="col-lg-12"><h3>Photo Story</h3></div>
						<div class="col-lg-12 text-center">
							@foreach($the_exclusive->gallery as $e_t_g_k => $e_t_g_v )
								@php
									$e_t_g_image = getAttachmentById($e_t_g_v->media_id);
								@endphp
								@if( !empty( $e_t_g_image->url ) )
									<img src="{{ url($e_t_g_image->url) }}" alt="" />
								@endif
							@endforeach
						</div>
					</div>
				@endif
			</div>
		@endif
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span>THE</span> Untold Truth</h2>
			</div>
		</div>
		<div class="row bg-white single-stack">
			<div class="row">
				<div class="col-md-6">
					<div class="short-article">
						@if( !empty( $the_untold ) )
							<h4>
								<a href="{{ url($the_untold->slug) }}">{{ $the_untold->title }}</a>
							</h4>
							{!! $the_untold->excerpt !!}...
							<br>
							<span class="read-more">
							<a href="{{ url($the_untold->slug) }}"><span>Read more</span></a></span>
						@endif
					</div>
				</div>
				<div class="col-md-6 text-center images">
					@if( !empty( $the_untold->gallery ) )
						@foreach($the_untold->gallery as $e_ut_k => $e_ut_v )
							@php
								if( $e_ut_k > 1) break;
								$e_ut_image = getAttachmentById($e_ut_v->media_id);
							@endphp
							@if( !empty( $e_ut_image->url ) )
								<img src="{{ url($e_ut_image->url) }}" alt="" />
							@endif
						@endforeach
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span>THE</span> Truth Dares</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $the_dares_post ) && count( $the_dares_post ) > 0 )
				
					@foreach( $the_dares_post as $the_data)
						@if( !empty( $the_data ) )
							<div class="row article">
								<div class="col-md-4 video">
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-md-7 title">
									<h4><a href="{{ url($the_data->slug) }}">{{ $the_data->title }}</a></h4>
									{!! $the_data->excerpt !!}...
									<span class="read-more">
										<a href="{{ url($the_data->slug) }}">
											Read more
										</a>
									</span>
								</div>
							</div>
						@endif
					@endforeach
					<div class="row text-center bg-white">
						<a href="{{ url($the_dares->slug) }}" class="btn btn-tdanger m5">load more...</a>
					</div>
				@endif
			</div>
			<div class="col-lg-3 add-section">
				<div class="add">
					@if( !empty( $the_dares ) && !empty( $the_dares->metas() ) && !empty( $the_dares->metas()->where('key', 'add_url')->first() ))
					<?php
						$the_dares_addUrl_items = $the_dares->metas()->where('key', 'add_url')->first()->description;
					?>
						@if( !empty($the_dares_addUrl_items ) )
							<img src="{{ $the_dares_addUrl_items }}" alt="" />
						@else
							@if( !empty( \App\Http\Controllers\SiteSettingsController::check('global_add') ) )
		                        @php
		                            $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('global_add')->description);
		                        @endphp
		                        @if( !empty( $logo->url ) )
		                            <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 100%;"/>
		                        @else
		                        	<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
		                        @endif
		                    @else
		                        <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
		                    @endif
						@endif
					@else
						@if( !empty( \App\Http\Controllers\SiteSettingsController::check('global_add') ) )
	                        @php
	                            $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('global_add')->description);
	                        @endphp
	                        @if( !empty( $logo->url ) )
	                            <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 100%;"/>
	                        @else
	                        	<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
	                        @endif
	                    @else
	                        <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
	                    @endif
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span></span> Yours Truly</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-12 news-section">
				@if( !empty( $the_yourtrue_post ) && count( $the_yourtrue_post ) > 0 )
					@foreach( $the_yourtrue_post as $the_data)
						@if( !empty( $the_data ) )
							<div class="row article">
								<div class="col-md-4 video">
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-md-7 title">
									<h4><a href="{{ url($the_data->slug) }}">{{ $the_data->title }}</a></h4>
									{!! $the_data->excerpt !!}...
									<span class="read-more">
										<a href="{{ url($the_data->slug) }}">
											Read more
										</a>
									</span>
								</div>
							</div>
						@endif
					@endforeach
				@endif
			</div>
		</div>
		@if( !empty( $the_yourtrue->slug ) )
    		<div class="row text-center bg-white">
    			<a class="btn btn-tdanger m5" href="{{ url($the_yourtrue->slug) }}">load more...</a>
    		</div>
		@endif
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span>THE</span> TRUTH TRIUMPHS</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-12 news-section">
				@if( !empty( $the_triumph_post )  && count( $the_triumph_post ) > 0 )
					@foreach( $the_triumph_post as $the_data)
						@if( !empty( $the_data ) )
							<div class="row article">
								<div class="col-md-4 video">
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-md-7 title">
									<h4><a href="{{ url($the_data->slug) }}">{{ $the_data->title }}</a></h4>
									{!! $the_data->excerpt !!}...
									<span class="read-more">
										<a href="{{ url($the_data->slug) }}">
											Read more
										</a>
									</span>
								</div>
							</div>
						@endif
					@endforeach
				@endif
			</div>
		</div>
		@if( !empty( $the_triumph->slug ) )
    		<div class="row text-center bg-white">
    			<a class="btn btn-tdanger m5" href="{{ url($the_triumph->slug) }}">load more...</a>
    		</div>
		@endif
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span></span> OPINION AND ANALYSIS</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-12 news-section">
				@if( !empty( $the_opinion_post )  && count( $the_opinion_post ) > 0 )
					@foreach( $the_opinion_post as $the_data)
						@if( !empty( $the_data ) )
							<div class="row article">
								<div class="col-md-4 video">
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-md-7 title">
									<h4><a href="{{ url($the_data->slug) }}">{{ $the_data->title }}</a></h4>
									<div>
										{!! $the_data->excerpt !!}...
										<span class="read-more">
											<a href="{{ url($the_data->slug) }}">
												Read more
											</a>
										</span>
									</div>
								</div>
							</div>
						@endif
					@endforeach
				@endif
			</div>
		</div>
		@if( !empty( $the_opinion->slug ) )
    		<div class="row text-center bg-white">
    			<a class="btn btn-tdanger m5" href="{{ url($the_opinion->slug) }}">load more...</a>
    		</div>
		@endif
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span></span> The World</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-12 news-section">
				@if( !empty( $the_world_post ) && count( $the_world_post ) > 0 )
					@foreach( $the_world_post as $the_data)
						@if( !empty( $the_data ) )
							<div class="row article">
								<div class="col-md-4 video">
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-md-7 title">
									<h4><a href="{{ url($the_data->slug) }}">{{ $the_data->title }}</a></h4>
									{!! $the_data->excerpt !!}...
									<span class="read-more">
										<a href="{{ url($the_data->slug) }}">
											Read more
										</a>
									</span>
								</div>
							</div>
						@endif
					@endforeach
				@endif
			</div>
		</div>
		@if( !empty( $the_world->slug ) )
    		<div class="row text-center bg-white">
    			<a class="btn btn-tdanger m5" href="{{ url($the_world->slug) }}">load more...</a>
    		</div>
		@endif
	</div>
</section>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h2><span>The</span> CITIZEN JOURNALIST</h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-12 news-section">
				
			</div>
		</div>
	</div>
</section>
@include('public-pages.footer')